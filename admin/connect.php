<?php
//   $dsn='mysql:host=localhost;dbname=shop';
//   $user='root';
//   $pass='#M1411997a';
//   $option=array(
//     PDO::MYSQL_ATTR_INIT_COMMAND =>'SET NAMES utf8'
//   );

// try {
//     $conn=new PDO($dsn,$user,$pass,$option);
//     $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
// }
// catch (PDOException $e){
//     echo 'Failed to connect'.$e->getMessage();
// }


// ------------------------------

require_once 'pdoconfig.php';
 
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // echo "Connected to $dbname at $host successfully.";
} catch (PDOException $pe) {
    die("Could not connect to the database $dbname :" . $pe->getMessage());
}

?>