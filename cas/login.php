<?php
$cas = new Cas($casUrl, $myUrl);
$user = $cas->authenticate();

if ($user == -1) {
    $cas->login();
}
else {
    //$user['user'];
    $_SESSION['user'] = $user['user'];
    $_SESSION['nom'] = $user['nom'];
    $_SESSION['prenom'] = $user['prenom'];
    $_SESSION['mail'] = $user['mail'];

    unset($_GET['ticket']);
    $url = strtok($myUrl, '?');
    header("Location: ".$url );
}
