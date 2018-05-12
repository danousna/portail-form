<?php

// error_reporting(0);
// ini_set('display_errors', 0);

require_once('scripts/db.php');
require_once('scripts/idea.php');

/* CAS */
$myUrl = "http://".$_SERVER['HTTP_HOST'].strtok($_SERVER["REQUEST_URI"],'?');
$casUrl = "https://cas.utc.fr/cas/";
require_once('cas/xml.php');
require_once('cas/cas.php');

session_start();

$ideas = new Idea($pdo);

if (!isset($_SESSION['user']))
{
    if (isset($_GET['ticket']) OR (isset($_GET['section']) AND $_GET['section'] == 'login'))
        include('cas/login.php');
    else
        include('templates/guest.php');
}
else
{
    if (isset($_GET['section']) AND $_GET['section'] == 'login')
        header("Location: ./");

    if (isset($_GET['section']) AND $_GET['section'] == 'logout')
        include_once('cas/logout.php');

    if (isset($_GET['section']) AND $_GET['section'] == 'post')
        $ideas->post();

    if (isset($_GET['section']) AND $_GET['section'] == 'vote')
        $ideas->vote();

    else
        include_once('templates/user.php');
}
