<?php
class Idea {

    private $conn;
    public $dir_images = 'uploads/';
    public $dir_thumbs = 'uploads/t/';

    /**
     * Get database access
     *
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo) 
    {
        $this->conn = $pdo;
        $this->make_dirs();
    }

    /** 
     * List all ideas
     *
     * @return PDOStatement
     */
    public function all() 
    {
        return $this->conn->query("SELECT id, content, COUNT(votes.idea) AS nb_votes FROM ideas LEFT JOIN votes ON ideas.id = votes.idea GROUP BY id ORDER BY COUNT(votes.idea) DESC")->fetchAll();
    }

    /**
     * Get idea's images
     *
     * @param array $idea
     * @return PDOStatement
     */
    public function get_images($idea)
    {
        return $this->conn->query("SELECT image FROM images WHERE idea = ".$idea["id"])->fetchAll();
    }

    /**
     * Post data
     *
     * @return header("Location: ./") | error
     */
    public function post()
    {
        if (!isset($_POST['idea']) || !$_SESSION['user'])
        {
            message('error', 'Problème lors de l\'envoi du formulaire ou taille maximale des fichiers dépassée.');
            redirect('./');
        }

        if (strlen($_POST['idea']) < 6)
        {
            message('error', 'Idée trop courte.');
            redirect('./');
        }

        if (strlen($_POST['idea']) > 250)
        {
            message('error', 'Idée trop longue.');
            redirect('./');
        }

        if ($this->user_ideas() > 5)
        {
            message('error', 'Nombre maximum d\'idées atteint.');
            redirect('./');
        }

        $file_count = count($_FILES['file']['name']);
        $images = array();
        $fileError = false;

        if ($file_count > 0)
        {
            foreach ($_FILES["file"]["error"] as $key => $error)
            {
                if ($error == UPLOAD_ERR_OK)
                {
                    $extension = pathinfo($_FILES['file']['name'][$key], PATHINFO_EXTENSION);
                    $filename = time().'_'.sha1_file($_FILES['file']['tmp_name'][$key]).'.'.$extension;
                                   
                    try
                    {
                        $img = $this->process_image($key, 2000);
                        $thumb = $this->process_image($key, 200);

                        if ($img && $thumb) {
                            if ($img->writeImage($this->dir_images.$filename) && $thumb->writeImage($this->dir_thumbs.$filename))
                            {
                                $images[] = $filename;
                            }
                            else
                            {
                                $fileError = true;
                            }
                        }
                        else
                        {
                            $fileError = true;
                        }
                    }
                    catch (ImagickException $e)
                    {
                        $fileError = true;
                    }
                }
            }

            if ($fileError == true)
            {
                foreach ($images as $image)
                {
                    unlink($this->dir_images.$image);
                    unlink($this->dir_thumbs.$image);
                }

                message('error', 'Un ou des fichiers ne sont pas des images valides (jpg, jpeg, png).');
                redirect('./');
            } 

            $stmt = $this->conn->prepare('INSERT INTO ideas VALUES(NULL, :content, :user)');
            if ($stmt->execute([$_POST['idea'], $_SESSION['user']]))
            {
                $idea = $this->conn->lastInsertId();

                foreach ($images as $image)
                {
                    $stmt = $this->conn->prepare('INSERT INTO images VALUES(:image, :idea)');
                    $stmt->execute([$image, $idea]);
                }

                message('success', 'Idée publiée !');
                redirect('./');
            }

        }
        else // No images
        {
            $stmt = $this->conn->prepare('INSERT INTO ideas VALUES(NULL, :content, :user)');
            if ($stmt->execute([$_POST['idea'], $_SESSION['user']]))
            {
                message('success', 'Idée publiée !');
                redirect('./');
            }
        }
    }

    /**
     * Process image, validate, resize
     *
     * @param int $key
     * @param int $size
     * @return \Imagick | NULL
     */
    public function process_image($key, $size)
    {
        $imagick = new \Imagick($_FILES['file']['tmp_name'][$key]);

        $valid_formats = array(
            'jpg',
            'jpeg',
            'png',
        );

        $format = strtolower($imagick->getImageFormat());

        if (!in_array($format, $valid_formats))
        {
            return NULL;
        }

        $imageprops = $imagick->getImageGeometry();
        $width = $imageprops['width'];
        $height = $imageprops['height'];
        $ratio = $width / $height;

        $newWidth = $size;
        $newHeight = $size / $ratio;

        if ($width > $size || $height > $size)
        {
            $imagick->resizeImage($newWidth,$newHeight, \Imagick::FILTER_LANCZOS, 0.9, true);
        }

        return $imagick;
    }

    /** 
     * Get user votes number
     *
     * @return int
     */
    public function user_ideas()
    {
        $stmt = $this->conn->prepare('SELECT COUNT(user) FROM ideas WHERE user = :user GROUP BY user');
        $stmt->execute([$_SESSION['user']]);
        $count = $stmt->fetchAll();

        if ($count)
        {
            return $count[0]["COUNT(user)"];
        }
        else
        {
            return 0;
        }
    }

    /** 
     * Get votes
     *
     * @return array
     */
    public function votes()
    {
        $stmt = $this->conn->prepare('SELECT * FROM votes WHERE user = :user');
        $stmt->execute([$_SESSION['user']]);
        $votes = $stmt->fetchAll();

        $data = array();

        foreach ($votes as $vote)
        {
            $data[] = $vote['idea'];
        }

        return $data;
    }

    /**
     * Vote idea
     *
     * @return header("Location: ./") | error
     */
    public function vote()
    {
        if (isset($_POST['id']) && is_string($_POST['id']))
        {
            $stmt = $this->conn->prepare('SELECT * FROM votes WHERE idea = :idea AND user = :user');
            $stmt->execute([$_POST['id'], $_SESSION['user']]);

            if ($stmt->fetchColumn())
            {
                $stmt = $this->conn->prepare('DELETE FROM votes WHERE idea = :idea AND user = :user');
                if ($stmt->execute([$_POST['id'], $_SESSION['user']]))
                {
                    message();
                    redirect('./#'.$_POST['id']);
                }
                else
                {
                    message('error', 'Erreur interne, veuillez réessayer.');
                    redirect('./');
                }
            }
            else
            {
                $stmt = $this->conn->prepare('INSERT INTO votes VALUES(:idea, :user)');
                if ($stmt->execute([$_POST['id'], $_SESSION['user']]))
                {
                    message();
                    redirect('./#'.$_POST['id']);
                }
                else 
                {
                    message('error', 'Erreur interne, veuillez réessayer.');
                    redirect('./');
                }
            }
        }
        else
        {
            message('error', 'Erreur interne, veuillez réessayer.');
            redirect('./');
        }
    }

    /** 
     * Make directories
     *
     */
    protected function make_dirs()
    {
        if (!file_exists($this->dir_images))
        {
            mkdir($this->dir_images, 0755, true);
        }

        if (!file_exists($this->dir_thumbs))
        {
            mkdir($this->dir_thumbs, 0755, true);
        }
    }
}

// Dump and die
function dd($data)
{
    print_r($data);
    die;
}

function redirect($location)
{
    header("Location: " . $location);
    exit(0);
}

function message($type = NULL, $content = NULL)
{
    if (isset($_SESSION['success']))
    {
        unset($_SESSION['success']);
    }
    
    if (isset($_SESSION['error']))
    {
        unset($_SESSION['error']);
    }

    if ($type == 'error') {
        $_SESSION['error'] = $content;
    }
    else if ($type == 'success')
    {
        $_SESSION['success'] = $content;
    }
}