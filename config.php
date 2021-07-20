<?php
if( !defined('CONFIG_INCLUDE') )
{
    die("Invalid Access");
}
$host = '127.0.0.1';
$username = 'root';
$pass = 'root';
$dbname = 'rex_events';

try {
    $db = new PDO("mysql:host=" . $host . ";dbname=" . $dbname, $username, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
catch ( PDOException $e )
{

    echo $e->getMessage();
    die();
}

