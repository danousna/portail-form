<?php
class Idea {

    private $conn;
    private $dir_images = 'uploads/full_size';
    private $dir_thumbs = 'uploads/thumb_size';

    // Get database access
    public function __construct(\PDO $pdo) 
    {
        $this->conn = $pdo;
        $this->make_dirs();
    }

    // List all ideas
    public function all() 
    {
        return $this->conn->query("SELECT id, content, COUNT(votes.idea) FROM ideas LEFT JOIN votes ON ideas.id = votes.idea GROUP BY id ORDER BY COUNT(votes.idea) DESC")->fetchAll();
    }

    // Post data
    public function post()
    {
        if (isset($_POST['idea']) && $_SESSION['user'] && strlen($_POST['idea']) > 5 && strlen($_POST['idea']) < 250)
        {
            if ($this->user_ideas() >= 5)
            {
                header("Location: ./");
            }
            else
            {
                $stmt = $this->conn->prepare('INSERT INTO ideas VALUES(NULL, :content, :user)');
                if ($stmt->execute([$_POST['idea'], $_SESSION['user']]))  {
                    $idea = $this->conn->lastInsertId();
                    $count = count($_FILES['file']['name']);

                    if ($count > 0) {
                        for ($i = 0; $i < $count; $i++)
                        {
                            $filename = $_SESSION['user'].'_'.time();
                            $stmt = $this->conn->prepare('INSERT INTO images VALUES(:idea, :image)');

                            if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $this->dir_images.'/'.$filename)) {
                                $stmt->execute([$idea, $filename]);
                            }
                        }
                    }

                    header("Location: ./");
                }
                else
                    echo "Une erreur est survenue";
            }
        }
        else {
            header("Location: ./");
        }
    }

    // Process image, validate, resize
    public function process_image() {
        // Code.
    }

    // Get user votes number
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

    // Get votes
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

    // Vote idea
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
                    header("Location: ./");
                else
                    echo "Une erreur est survenue";
            }
            else
            {
                $stmt = $this->conn->prepare('INSERT INTO votes VALUES(:idea, :user)');
                if ($stmt->execute([$_POST['id'], $_SESSION['user']]))
                    header("Location: ./");
                else
                    echo "Une erreur est survenue";
            }
        }
        else
            header("Location: ./");
    }

    // Make directories
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