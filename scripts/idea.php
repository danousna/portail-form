<?php
class Idea {

    private $conn;

    // Get database access
    public function __construct(\PDO $pdo) 
    {
        $this->conn = $pdo;
    }

    // List all ideas
    public function all() 
    {
        return $this->conn->query("SELECT id, content, COUNT(votes.idea) FROM ideas LEFT JOIN votes ON ideas.id = votes.idea GROUP BY id ORDER BY COUNT(votes.idea) DESC")->fetchAll();
    }

    // Post data
    public function post()
    {
        if (isset($_POST['idea']) && strlen($_POST['idea']) > 5 && strlen($_POST['idea']) < 250)
        {
            $stmt = $this->conn->prepare('INSERT INTO ideas VALUES(NULL, :content)');
            if ($stmt->execute([$_POST['idea']]))
                header("Location: ./");
            else
                echo "Une erreur est survenue";
        }
        else
            header("Location: ./");
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
}