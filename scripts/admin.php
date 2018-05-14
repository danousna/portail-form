<?php
class Admin {

    private $conn;
    private $admins = array(
        'danousna',
    );

    /**
     * Get database access
     *
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo) 
    {
        $this->conn = $pdo;
    }

    public function isAdmin()
    {
        if (!isset($_SESSION['user']))
        {
            return false;
        }

        if (in_array($_SESSION['user'], $this->admins))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}