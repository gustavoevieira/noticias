<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'site_noticias_db');

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error){
    die("Erro na conexão com o banco de dados" . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>