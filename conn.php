<?php

$__DBHOST = "localhost";
$__DBUSER = "root";
$__DBPASS = "";
$__DBNAME = "gelir-gider";
try {
    $db = new PDO("mysql:host=$__DBHOST;dbname=$__DBNAME;charset=utf8", $__DBUSER, $__DBPASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
catch(PDOException $e)
    {
    echo "Bağlantı hatası: " . $e->getMessage();
    exit;
    }
?>
