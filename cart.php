<?php
ob_start();
session_start();
if (isset($_SESSION['user'])) {
    include 'admin/connect.php';
    $userId=$_SESSION['uid'];
    if (isset($_POST['pId']) && is_numeric($_POST['pId'])) {
        $pid = intval($_POST['pId']);
        $stmt = $conn->prepare('SELECT price,item_ID FROM items WHERE item_ID=?');
        $stmt->execute(array($pid));
        $item = $stmt->fetch();
        $price=$item['price'];
        $itemId=$item['item_ID'];
        $userIdP=$userId;
        $stmt2=$conn->prepare('SELECT * FROM cartitems WHERE item_id=? AND user_id=?');
        $stmt2->execute(array($itemId,$_SESSION['uid']));
        $count=$stmt2->rowCount();
        if ($count>0) {
            $alreadyExist='this item is already Exist';
        }else{
            $stmt3=$conn->prepare('INSERT INTO cartitems(product_q, product_total, item_id, user_id) VALUES (1,?,?,?)');
            $stmt3->execute(array($price,$itemId,$userId));
            $stmt4=$conn->prepare('SELECT count(id) FROM cartitems WHERE user_id=?');
            $stmt4->execute(array($userId));
            $countCartItem=$stmt4->fetchColumn();
            echo $countCartItem;
        }


    }
}
?>
