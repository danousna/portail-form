<?php
require_once('cas/cas.php');
unset($_SESSION['user']);
$cas = new Cas($casUrl, $myUrl);
$cas->logout();
