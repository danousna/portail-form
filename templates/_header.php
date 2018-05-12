<!doctype html>
<html lang="fr">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>SiMDE - Sondage</title>

    <style type="text/css">
        body {
            background: #E9EBEE;
        }

        .form-control {
            border: none;
            border-radius: 4px;
            background: #F6F7F9;
        }

        input:focus {
            outline: none !important;
            box-shadow: none !important;
            background: #F6F7F9 !important;
        }

        .btn {
            padding: 5px 14px;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 14px;
            font-weight: bold;
            border: none;
            border-radius: 4px;
        }

        .btn-secondary {
            background: #F6F7F9;
            color: #4B4F56;
        }

        .btn-secondary:hover, .btn-secondary:active, .btn-secondary:focus {
            background: #E9EBEE !important;
            color: #4B4F56 !important;
        }

        .btn-like {
            padding: 2px 7px !important;
            background: #F6F7F9;
            color: #4B4F56;
            font-size: 16px;
        }

        .idea {
            background: white;
            border-radius: 4px;
            border: 1px solid #DDDFE2;
        }

        a.img {
            opacity: .7;
        }

        a.img:hover {
            opacity: 1;
        }

        a.img svg {
            width: 100px;
        }

        .inputfile {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        .bg-block {
            position: fixed;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 8;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .loading {
            opacity: 0.5;
            pointer-events: none;
            position: relative;
        }

        .lightbox {
            position: fixed;
            width: 75%;
            height: 75%;
            z-index: 10;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .lightbox img {
            display: block;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-7 mx-auto">
                <div class="text-center mt-4 p-2">
                    <a href="https://assos.utc.fr"><img src="simde.svg" width="200"></a>
                    <h2 class="my-4">Sondage sur le portail des assos</h2>
                </div>

                <div class="my-3">
                    <p class="text-justify">
                        Le projet de refonte du portail des assos a été commencé. Nous nous adressons à tous les étudiants afin de recueillir des idées sur ce que ce nouveau portail pourrait être. 
                    <p class="text-justify">
                        Si vous avez toujours souhaité voir une fonctionnalité sur le portail, c'est le moment de le dire !
                    </p>
                </div>