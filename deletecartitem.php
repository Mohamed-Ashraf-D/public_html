<?php
ob_start();
session_start();
if (isset($_SESSION['user'])) {
    include 'admin/connect.php';
    $userId=$_SESSION['uid'];
    if (isset($_POST['cartItemId']) && is_numeric($_POST['cartItemId'])) {
        $pid = intval($_POST['cartItemId']);
        $stmt=$conn->prepare('SELECT * FROM cartitems WHERE id=? AND user_id=?');
        $stmt->execute(array($pid,$userId));
        $count=$stmt->rowCount();
        if ($count>0) {
            $stmt=$conn->prepare('DELETE FROM cartitems WHERE id=? AND user_id=?');
            $stmt->execute(array($pid,$userId));
            echo 'yes';
        }

    }

}