# Documentation

Un formulaire rapide avec vote pour la consultation des utcéens sur le nouveau portail des assos.

Pour installer :

- créer le fichier `scripts/db.php`.
- y insérer le code suivant en remplacant par vos identifiants :

```php
<?php
$host = 'HOTE';
$db   = 'BASE DE DONNEES';
$user = 'UTILISATEUR';
$pass = 'MOT DE PASSE';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$opt = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
];

$pdo = new \PDO($dsn, $user, $pass, $opt);
```


