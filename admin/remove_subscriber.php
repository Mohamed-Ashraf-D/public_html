<?php
session_start();
$pageTitle = 'Members';


    include 'init.php';
$stmt=$conn->prepare("SELECT * FROM users ");
$stmt->execute();
$rows=$stmt->fetchAll();
foreach($rows as $row){
    if($row['endDate']<date('Y-m-d')){
        echo $row['UserID']."removed"."<br>";
        $stmt=$conn->prepare("DELETE FROM users Where UserID=? AND GroupID !=1 AND endDate!='' ");
        $stmt->execute(array($row['UserID']));
    }else{
        echo $row['UserID']."no"."<br>";
    }


}


    include $tpl . 'footer.php';
