<?php

define('DB_HOST','localhost');
define('DB_USER','client1458');
define('DB_PASS','Shohzod2341009');
define('DB_NAME','client1458_seonExpress');

$conn = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);

if (!$conn){
    echo "MYSQLI CONNECT ERROR";
}
require_once "func.php";

?>
