<?php
session_start();
include('admin/connect.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['comment'])){
            $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
            $itemID = $_POST['itemid'];
            $userId = $_SESSION['uid'];
            if (!empty($comment)) {
                $date = date('Y-m-d H:i:s');
                $stmt = $conn->prepare("INSERT INTO comments(comment,status,comment_date,item_id,user_id) VALUES (:mcomment,0,:datetime,:mitemid,:muserid)");
                $stmt->execute(array(
                    'mcomment' => $comment,
                    'mitemid' => $itemID,
                    'muserid' => $userId,
                    'datetime'=>$date
                ));
                if ($stmt) {
                    echo  $comment;
                }
            } else {
                echo 'حدث خطأ';
            }
        }
    }
?>