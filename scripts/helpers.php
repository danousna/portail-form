<?php

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