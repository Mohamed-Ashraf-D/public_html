<?php
ob_start();
session_start();
$pageTitle = 'Profile';
include "init.php";

if (isset($_SESSION['user'])){
    $getUser=$conn->prepare("SELECT * FROM users join class on users.class=class.ID WHERE mobile=?");
    $getUser->execute(array($sessionUser));
    $info=$getUser->fetch();
    $userId=$info['UserID']
?>
<h1 class="text-center">البيانات الشخصية</h1>
<div class="information block mt-5">
    <div class="container">
        <div class="card">
            <div class="card-header bg-primary text-white">My Information</div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-unlock"></i>
                        <span> اسم الطالب: </span><?=$info['name']?>
                    </li>
                    <li>
                        <i class="fa fa-user"></i>
                        <span> رقم الهاتف: </span><?=$info['mobile']?>
                    </li>
                    <li>
                        <i class="fa fa-envelope"></i>
                        <span> الصف الدراسي: </span><?=$info['Name']?>
                    </li>
                    <li>
                        <i class="fa fa-tag"></i>
                        <span> حالة الاشتراك: </span><?php
                        if ($info['RegStatus']==1) {
                            echo 'مفعل';}
                        else{
                            echo 'غير مفعل';
                        }
                        ?>
                    </li>
                    <li>
                        <i class="fa fa-calendar"></i>
                        <span>تاريخ التفعيل: </span><?=substr($info['Date'],0,11)?>
                    </li>
                </ul>
                <a href="" class="btn mt-3 btn-info">تعديل البيانات</a>

            </div>
        </div>
    </div>
</div>
<div class="my-ads block">
    <div class="container">

        
    </div>
</div>
<div class="my-comments block">
    <div class="container">
        <div class="card">
            <div class="card-header bg-primary text-white">Latest Comments</div>
            <div class="card-body">
                <?php
                $myComments=getAllFrom("comment","comments","WHERE user_id=$userId","","c_id");

                if (!empty($myComments)){
                    foreach ($myComments as $comment) {
                        echo '<p>'.$comment['comment'].'</p>';
                    }
                }else{
                    echo 'There\'s no comment ';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
}else{
    header('Location:login.php');
    exit();
}
?>
<?php
include $tpl . 'footer.php';
ob_end_flush();
?>
