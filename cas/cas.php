<?php
class Cas
{
    private $url;
    private $casUrl;

    public function __construct($casUrl, $url)
    {
        $this->url = $url;
        $this->casUrl = $casUrl;
    }

    public function login()
    {
        header('Location: '.$this->casUrl.'login?service='.$this->url);
    }

    public function logout()
    {
        header('Location: '.$this->casUrl.'logout?service='.$this->url);
    }

    public function authenticate()
    {
        if (!isset($_GET['ticket'])) return -1;

        $response = file_get_contents($this->casUrl.'serviceValidate?service='.$this->url.'&ticket='.$_GET['ticket']);
        if (empty($response)) return -1;

        $user = Xml::parseCasReturn($response);
        if (empty($user)) return -1;
        if ($user == -1) return -1;

        return $user;
    }
}
