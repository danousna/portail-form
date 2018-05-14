<?php

session_start();

require_once('scripts/helpers.php');
require_once('scripts/db.php');
require_once('scripts/idea.php');
require_once('scripts/admin.php');

/* cas */
$myUrl = "http://".$_SERVER['HTTP_HOST'].strtok($_SERVER["REQUEST_URI"],'?');
$casUrl = "https://cas.utc.fr/cas/";
require_once('cas/xml.php');
require_once('cas/cas.php');

$ideas = new Idea($pdo);
$admin = new Admin($pdo);

if (!isset($_SESSION['user']))
{
    if (isset($_GET['ticket']) OR (isset($_GET['section']) AND $_GET['section'] == 'login'))
        include('cas/login.php');
    else
        include('templates/guest.php');
}
else
{
    if (isset($_GET['section']) && $_GET['section'] == 'login')
        header("Location: ./");

    if (isset($_GET['section']) && $_GET['section'] == 'logout')
        include_once('cas/logout.php');

    if (isset($_GET['section']) && $_GET['section'] == 'post')
        $ideas->post();

    if (isset($_GET['section']) && $_GET['section'] == 'vote')
        $ideas->vote();

    if (isset($_GET['section']) && $_GET['section'] == 'delete' && $admin->isAdmin())
        $ideas->delete();

    else
        include_once('templates/user.php');
}
