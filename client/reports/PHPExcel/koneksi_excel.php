<?php

    $host = '127.0.0.1'; 
    $username = 'cesco_user'; 
    $password = 'srnGRkhl3c'; 
    $database = 'cesco_logistics';

    $pdo = new PDO('mysql:host='.$host.';dbname='.$database, $username, $password);
?>