<?php
ob_start();
session_start();
$pageTitle='Dashboard';

if(isset($_SESSION['mobileAdmin'])){
    include 'init.php';
    $NumUser=5;
    $theLatestUsers=getLatestu('*','users','UserID','GroupID',1,$NumUser);
    $NumItems=5;
    $LatestItems=getLatest('*','items','Item_ID',$NumItems);
    $numComments=10;

?>
    <!--Start Dashboard page -->
    <div class="home-stats">
        <div class="container  text-center">
            <h1>لوحة التحكم</h1>
            <div class="row">
            
                <div class="col-md-3">
                    <div class="stat st-members">
                        <i class="fa fa-users"></i>
                        <div class="info">
                            كل الطلاب
                            <span><a href="members.php"><?php echo countItem('UserID','users','WHERE GroupID!=1')?></a></span>
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-connected-user">
                         <i class="fa fa-cloud"></i>
                        <div class="info"> 
                            المتصلين حاليا
                            <span>
                                <?php
                                
                                $session=session_id();
                                $time=time();
                                $time_check=$time-1800;
                                $tbl_name="status";
                                
                                $stmtC=$conn->prepare("SELECT * FROM $tbl_name WHERE session='$session'");
                                $stmtC->execute();
                                $count=$stmtC->rowCount();
                                if($count=="0"){
                                $sql1=$conn->prepare("INSERT INTO $tbl_name(session, time,user_id)VALUES('$session', '$time','{$_SESSION['ID']}')");
                                $sql1->execute();
                                }
                                else {
                                $sql2=$conn->prepare("UPDATE $tbl_name SET time='$time', user_id={$_SESSION['ID']} WHERE session = '$session'  ");
                                $sql2->execute();
                                }
                                $sql3=$conn->prepare("SELECT * FROM $tbl_name");
                                $sql3->execute();
                                $sql3C=$sql3->rowCount();
                                echo  $sql3C-1 ;
                                $sql4=$conn->prepare("DELETE FROM $tbl_name WHERE time<$time_check");
                                $sql4->execute(); 
                            ?></span>
                           
                           
                        </div>
                        
                    </div>
                    <?php $users = count(glob(session_save_path() . '/*'));?>
                            <span>  عدد الطلاب اللى دخلو المنصة خلال الساعات الاخيرة (<?=$users?>) </span>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pendin">
                        <i class="fa fa-user-plus"></i>
                        <div class="info">
                            فى انتظار تفعيل الاشتراك
                            <span><a href="members.php?do=Manage&&page=pending"><?=checkItem('RegStatus','users','0')?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-items">
                        <i class="fa fa-tag"></i>
                        <div class="info">
                            كل الدروس
                            <span><a href="items.php"><?php echo countItem('item_ID','items','')?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-comments">
                        <i class="fa fa-comments"></i>
                       <div class="info">
                           كل التعليقات
                           <span><a href="comments.php"><?php echo countItem('c_id','comments','')?></a></span>
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="latest">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-user"></i> اخر <?=$NumUser?> اشخاص قامو بالتسجيل
                            <span class="float-right toggle-info">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled latest-users">
                                <?php
                                if (!empty($theLatestUsers)){
                                foreach ($theLatestUsers as $users){
                                    echo"<li>".$users['name'];
                                    echo "<a href='members.php?do=Edit&userid=".$users['UserID']."'> ";
                                    echo" <span class='btn btn-primary btn-sm float-right'> Edit ";
                                    echo" <span class='fa fa-edit'></span> ";
                                    if ($users['RegStatus']==0){
                                        echo " <a href='members.php?do=Activate&userid=".$users['UserID']."' class=\"btn btn-info btn-sm activate btn-activ float-right\"><i class='fa fa-check'></i> Activate </a> ";
                                    }
                                    echo "</span>";
                                    echo "</a>";
                                    echo "</li>";
                                }}else{
                                    echo "Ther's no record to show";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-tag"></i> اخر <?=$NumItems?> دروس تم اضافتها
                            <span class="float-right toggle-info">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="card-body">

                            <ul class="list-unstyled latest-users">
                                <?php
                                if (!empty($LatestItems)){
                                foreach ($LatestItems as $item){
                                    echo"<li>".$item['Name'];
                                    echo "<a href='newad.php?do=Edit&addId=".$item['item_ID']."'> ";
                                    echo" <span class='btn btn-primary btn-sm float-right'> Edit ";
                                    echo" <span class='fa fa-edit'></span> ";
                                    
                                        // echo " <a href='items.php?do=Activate&itemid=".$item['item_ID']."' class=\"btn btn-info btn-sm activate btn-activ float-right\"><i class='fa fa-check'></i> Activate </a> ";
                                    
                                    echo "</span>";
                                    echo "</a>";
                                    echo "</li>";
                                }
                                ?>
                                    <?php
                                }else{
                                    echo "There's no record to show";
                                }
                                    ?>
                            </ul>


                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-comments"></i> اخر <?=$numComments?> تعليقات
                            <span class="float-right toggle-info">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled latest-comments">
                            <?php
                            $stmt = $conn->prepare("SELECT comments.*,users.Username AS userName FROM comments INNER JOIN  users ON comments.user_id=users.UserID  ORDER BY c_id DESC LIMIT $numComments");
                            $stmt->execute();
                            $comments = $stmt->fetchAll();
                            if (!empty($comments)){
                            foreach ($comments as $comment){
                                echo "<div class='comment-box'>";
                                echo '<span class="user-n"><a href="members.php?do=Edit&userid='.$comment['user_id'].'">'.$comment['userName'].'</a></span>';
                                echo '<p class="user-c">'.$comment['comment'].'</p>';
                                echo "</div>";
                            }
                            ?>
                                <?php }else {
                                    echo "لا يوجد تعليقات الان";
                            }?>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>


        </div>

    </div>

    <!--End Dashboard page -->

    <?php

    include $tpl . 'footer.php';

}
else{
    header('Location:index.php');
    exit();
}
ob_end_flush();
?>