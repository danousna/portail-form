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
        if (!isset($_POST['idea']) || !$_SESSION['user'] || strlen($_POST['idea']) <= 5 || strlen($_POST['idea']) > 250)
            header("Location: ./");

        if ($this->user_ideas() > 5)
            header("Location: ./");

        $file_count = count($_FILES['file']['name']);
        $images = array();

        if ($file_count > 0)
        {
            foreach ($_FILES["file"]["error"] as $key => $error)
            {
                if ($error == UPLOAD_ERR_OK)
                {
                    // TODO :: NO PNG HARDCODED !!!
                    $filename = time().'_'.sha1_file($_FILES['file']['tmp_name'][$key]).'.png';

                    try 
                    {    
                        $img = $this->process_image($key, 2000);
                        $thumb = $this->process_image($key, 200);
                    }
                    catch (ImagickException $e)
                    {
                        $_SESSION['error'] = "Un ou des fichiers ne sont pas des images valides.";
                        header("Location: ./");
                    }

                    if ($img && $thumb)
                    {
                        if ($img->writeImage($this->dir_images.$filename) && $thumb->writeImage($this->dir_thumbs.$filename))
                            $images[] = $filename;
                        else
                            $_SESSION['error'] = "Erreur interne, veuillez réessayer.";
                            header("Location: ./");
                    }
                    else {
                        $_SESSION['error'] = "Erreur interne, veuillez réessayer.";
                        header("Location: ./");
                    }
                }
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

                $_SESSION['success'] = "Succès !";
                header("Location: ./");
            }

        }
        else // No images
        {
            $stmt = $this->conn->prepare('INSERT INTO ideas VALUES(NULL, :content, :user)');
            if ($stmt->execute([$_POST['idea'], $_SESSION['user']])) {
                $_SESSION['success'] = "Succès !";
                header("Location: ./");
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
        $format = strtolower($imagick->getImageFormat());

        if ($format != 'jpg' || $format != 'jpeg' || $format != 'png')
            return NULL;

        $imageprops = $imagick->getImageGeometry();
        $width = $imageprops['width'];
        $height = $imageprops['height'];
        $ratio = $width / $height;

        $newWidth = $size;
        $newHeight = $size / $ratio;

        if ($width > $size || $height > $size)
            $imagick->resizeImage($newWidth,$newHeight, \Imagick::FILTER_LANCZOS, 0.9, true);

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
            return $count[0]["COUNT(user)"];
        else
            return 0;
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

        foreach ($votes as $vote) {
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
                    header("Location: ./#".$_POST['id']);
                else {
                    $_SESSION['error'] = "Erreur interne, veuillez réessayer.";
                    header("Location: ./");
                }
            }
            else
            {
                $stmt = $this->conn->prepare('INSERT INTO votes VALUES(:idea, :user)');
                if ($stmt->execute([$_POST['id'], $_SESSION['user']]))
                    header("Location: ./#".$_POST['id']);
                else {
                    $_SESSION['error'] = "Erreur interne, veuillez réessayer.";
                    header("Location: ./");
                }
            }
        }
        else
            header("Location: ./");
    }

    /** 
    * Make directories
    *
    */
    protected function make_dirs()
    {
        if (!file_exists($this->dir_images)) {
            mkdir($this->dir_images, 0755, true);
        }

        if (!file_exists($this->dir_thumbs)) {
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

function convertImage($image)
{
    // jpg, png, gif or bmp?
    $exploded = explode('.',$image);
    $ext = $exploded[count($exploded) - 1]; 

    if (preg_match('/jpg|jpeg/i',$ext))
        $imageTmp=imagecreatefromjpeg($image);
    else if (preg_match('/png/i',$ext))
        $imageTmp=imagecreatefrompng($image);
    else if (preg_match('/gif/i',$ext))
        $imageTmp=imagecreatefromgif($image);
    else if (preg_match('/bmp/i',$ext))
        $imageTmp=imagecreatefrombmp($image);
    else
        return NULL;

    return $imageTmp;
}