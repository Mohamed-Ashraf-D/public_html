<?php
ob_start();
session_start();
if (isset($_SESSION['user'])) {
    include 'admin/connect.php';
    $userId=$_SESSION['uid'];

    if (isset($_POST['cartItemId']) || isset($_POST['cartItemQ']) && is_numeric($_POST['cartItemQ']) && is_numeric($_POST['cartItemId'])) {
        $cartItemId = $_POST['cartItemId'];
        $cartItemQ =$_POST['cartItemQ'];
        $productTotal=array();
        for ($i=0;$i<count($_POST['cartItemId']);$i++){
            $cartItemId2=intval($cartItemId[$i]);
            $stmt=$conn->prepare('SELECT price FROM items WHERE item_ID=?');
            $stmt->execute(array($cartItemId2));
             $itemPrice=$stmt->fetch();
            $productTotal[]= $itemPrice['price']*$cartItemQ[$i];
        }
        for ($i=0;$i<count($cartItemId);$i++){
            $stmt1=$conn->prepare('UPDATE cartitems SET product_q=?,product_total=? WHERE item_id=?');
            $itemQuantity[$i]=$cartItemQ[$i];
            $totalPrice[$i]=$productTotal[$i];
            $cartItemId3[$i]=$cartItemId[$i];
           $stmt1->execute(array($itemQuantity[$i],$totalPrice[$i],$cartItemId3[$i]));
        }
        if ($stmt1) {
            echo 'success';
        }else{
            echo 'error';
        }

    }
}