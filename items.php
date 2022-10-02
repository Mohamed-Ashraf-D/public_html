<?php
ob_start();
session_start();

$pageTitle = "Item details";
$userId = isset($_SESSION['uid']) ? $_SESSION['uid'] : "";
$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
// echo $itemid."<br>";
// echo $userId;
include "init.php";
// record that user online
if(isset($_SESSION['uid'])){
$session = session_id();
$time = time();
$time_check = $time - 1800;
$tbl_name = "status";
// who is online using session
$stmtC = $conn->prepare("SELECT * FROM $tbl_name WHERE session='$session'");
$stmtC->execute();
$count = $stmtC->rowCount();
if ($count == "0") {
    $sql1 = $conn->prepare("INSERT INTO $tbl_name(session, time,user_id)VALUES('$session', '$time',{$_SESSION['uid']})");
    $sql1->execute();
} else {
    $sql2 = $conn->prepare("UPDATE $tbl_name SET time='$time',user_id={$_SESSION['uid']} WHERE session = '$session'");
    $sql2->execute();
}
$sql3 = $conn->prepare("SELECT * FROM $tbl_name");
$sql3->execute();
$sql3C = $sql3->rowCount();
echo $sql3C;
$sql4 = $conn->prepare("DELETE FROM $tbl_name WHERE time<$time_check");
$sql4->execute();
}
//--------------------------------------------

if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) {
    $condition = "";
} else {
    if (isset($_SESSION['user'])) {
        $condition = " AND Cat_ID={$_SESSION['classID']}";}

}
//select all data from items table according to user id

?>

    <!--<h1 class="text-center">--><? //= $item['Name']
                                    ?>
    <!--</h1>-->

    <header>
        <div class="container-fluid">
        <?php if (isset($_SESSION['user'])) {
    $stmt = $conn->prepare("SELECT items.*,class.Name AS category_name,u.Username FROM items INNER JOIN class ON items.Cat_ID = class.ID INNER JOIN users u ON items.Member_ID = u.UserID WHERE item_ID=? {$condition}");
    // Execute query
    $stmt->execute(array($itemid));
    //fetch data and store in array

    $count = $stmt->rowCount();
    if ($count > 0) {
        $item = $stmt->fetch();
        ?>
                <section style="margin-top:200px">
                    <div class="container m-auto">
                        <div class="bg-white">
                            <div class="row">
                                <div class="col-12">
                                    <h2 class="text-center p-3"><?=$item['Name']?></h2>
                                </div>
                            </div>
                            <div class="row m-auto">
                                <div class="col-12 text-center embed-responsive embed-responsive-16by9">
                                    <?php
if (empty($item['videoCode']) && empty($item['video'])) {
            echo "<img alt='product-image' id='image' width='1000' height='350'  src='layout/img/placeholder-profile-sq.jpg'>";
        } elseif (!empty($item['video'])) {
            echo "
                                        <video width='320' height='240' controls controlsList='nodownload'>
                                            <source src='admin/uploads/" . $item['video'] . "' type='video/mp4' >
                                        </video>";
        } else {
            echo "<div id='boxes'>";
            echo '<div id="redbox" style="width:100;height: 225px; background-color:transparent;position:fixed;top:150px;right:400px;z-index:1200"></div>';
            echo '<div id="greenbox" style="width:100;height: 225px; background-color:transparent;position:fixed;top:375px;right:400px;z-index:1200"></div>';
            echo '<div id="bluebox" style="width:100;height: 225px; background-color:transparent;position:fixed;top:600px;right:400px;z-index:1200"></div>';
            echo "</div>";
            echo '<div style=""><iframe class="" src="https://www.youtube-nocookie.com/embed/' . $item['videoCode'] . '?modestbranding=1"  sandbox="allow-forms allow-scripts allow-pointer-lock allow-same-origin allow-top-navigation" allowfullscreen="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"><div style="width:500;height: 400px; background-color:red;position:absolute;top:50px;left:40px;"></div></iframe></div>';
        }
        ?>
                                </div>
                            </div>
                            <div class="row m-auto">
                                <div class="col-12">
                                    <div class="text-center mt-5">
                                        <a type="button" class="btn btn-info" href="<?=$item['explain_pdf']?>">تحميل PDF الشرح</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-auto">
                                <div class="col-12">
                                    <div class="text-center mt-5">
                                        <a type="button" class="btn btn-primary" href="<?=$item['test_link']?>">بدأ الاختبار</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-auto">




                                <div class="col-12">
                                    <div class="m-auto">
                                        <div class="form-group mt-4">
                                            <label for="comment" class="">ارسال تعليق</label>
                                            <textarea id="comment" class="form-control" placeholder="اكتب ملاحظاتك على الفيديو هذا سيساعدنا فى تحسين الخدمة" required rows="3" name="comment" id="comment_text" cols="40" class="ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"></textarea>
                                        </div>
                                        <button id='send-comment' class="btn btn-primary mb-3">ارسال التعليق</button>
    </div>
                                    <?php
?>


                                </div>

                            <?php } else {
        echo '<a href="login.php">Login</a> or <a href="login.php">Register</a> To Add comment';
    }?>
                            </div>
                </section>
                <section>
                    <div class="container">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" href="#tab3" data-toggle="tab">تعليقات الطلاب على الشرح</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#tab1" data-toggle="tab">ملاحظات</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#tab2" data-toggle="tab">خطوات المذاكرة</a>
                            </li>


                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane p-3 " id="tab1">


                                <h5>يرجى مشهادة الفيديو حتى ينتهى تلقائيا لتسجيل انك قمت بمشهادة الفيديو وانتظر حتى تظهر لك رسالة تاكيدية بالمشاهدة</h5>
                                <ul>
                                    <li><b>عند مواجهة اى مشكلة برجاء ارسال تعليق للمساعده على معرفة المشكلة وحلها</b><a href=""></a></li>


                                </ul>

                            </div>
                            <div class="tab-pane fade p-3 " id="tab2">

                                <h5>Key Features</h5>
                                <ul>
                                    <li>8.0-inch HD IPS LCD Screen</li>

                                </ul>
                                <h5>Specification</h5>
                                <ul>
                                    <li><b>SKU:</b> HU820MP0EYAXFNAFAMZ</li>

                                </ul>
                            </div>
                            <div class="tab-pane p-3 active" id="tab3">

                                <?php
$stmt = $conn->prepare("SELECT comments.*,u.Name As member FROM comments INNER JOIN users u on comments.user_id = u.UserID WHERE item_id=$item[item_ID]  ORDER BY c_id DESC ");
    $stmt->execute();
    $comments = $stmt->fetchAll();
    foreach ($comments as $comment) {
        ?>
                                    <div class="comment-box2">
                                        <div class="row">
                                            <div class="col-md-2 text-right p-0 m-0">
                                                <img class="rounded rounded-circle img-thumbnail" style="" src="layout/img/placeholder-profile-sq.jpg">
                                            </div>
                                            <div class="col-md-10 mt-1">
                                                <div class="message">
                                                    <h5 id='comment-owner'><?=$comment['member']?></h5>
                                                    <p id='datetime'><?=$comment['comment_date']?></p>
                                                    <p id='comment' class="lead">
                                                        <?=$comment['comment']?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }?>

                                
                            </div>

                        </div>


                    </div>
                </section>

            <?php
} else {
    echo "<br><br><br><br><br>";
    echo '<div class="container">';
    echo '<div class="alert alert-danger">Ther\'s no such id or this item is waiting Approval</div>';
    echo '</div>';
}
include $tpl . 'footer.php';

?>
            <script>


                $(function() {
                    <?php
$stmtWatched = $conn->prepare("SELECT * FROM videomembership WHERE users=$userId && video=$itemid");
$stmtWatched->execute();
$isWatched = $stmtWatched->fetchAll();

if (empty($isWatched)) {
    ?>


                    <?php if (!empty($item['videoCode'])) {
        ?>





    var countDownDate = new Date().getTime() +1/12*60*60*1000;

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();
  console.log(now);

  // Find the distance between now and the count down date
  var distance = countDownDate - now;

  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  // Output the result in an element with id="demo"
//   document.getElementById("comment").innerHTML = days + "d " + hours + "h "
//   + minutes + "m " + seconds + "s ";

  // If the count down is over, write some text
  if (distance < 0) {
    clearInterval(x);
    $.ajax({
                            method: 'POST',
                            url: 'wwvideo.php',
                            data: {
                                'student': <?=$userId?>,
                                'video': <?=$itemid?>
                            },
                            success: function(data) {
                                console.log(data)

                            }
                        })
  }
}, 1000);

<?php } else {
        ?>
const $video = document.querySelector("video");

const onTimeUpdate = event => {
    console.log(checkSkipped(event.target.currentTime));
}

let prevTime = 0;
const checkSkipped = currentTime => {
    const skip = [];
    const skipThershold = 2;

    // user skipped any part of the video
    if (currentTime - prevTime > skipThershold) {
        skip.push({
            periodSkipped: currentTime - prevTime,
            startAt: prevTime,
            endAt: currentTime,
        });
        prevTime = currentTime;
        return skip;
    }

    prevTime = currentTime;
    return false;
}

// $video.addEventListener("play", e => console.log('play'));
// $video.addEventListener("playing", e => console.log('playing'));

$video.addEventListener("timeupdate", onTimeUpdate);

$video.addEventListener("ended", e => {
    $.ajax({
        method: 'POST',
        url: 'wwvideo.php',
        data: {
            'student': <?=$userId?>,
            'video': <?=$itemid?>
        },
        success: function(data) {
            Swal.fire(data);

        }
    })
});
$video.addEventListener("pause", e => console.log('pause'));

<?php }?>
<?php }?>
                })
                $(document).ready(function(){
   $('video').bind('contextmenu',function() { return false; });
});
// Send comment to server
 var currentdate = new Date();
var datetime = "Last Sync: " + currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/"
                + currentdate.getFullYear() + " @ "
                + currentdate.getHours() + ":"
                + currentdate.getMinutes() + ":"
                + currentdate.getSeconds();
$('#send-comment').click(function(e){
    // alert('click');
    if($('#comment').val() !=='' && $('#comment').val().length >5){
        $.ajax({
                            method: 'POST',
                            url: 'comment.php',
                            data: {
                                'itemid': <?=$item['item_ID']?>,
                                'comment': $('#comment').val(),
                            },
                            success: function(data) {
                                
                                $('#tab3').prepend(`<div class="comment-box2">
                                        <div class="row">
                                            <div class="col-md-2 text-right p-0 m-0">
                                                <img class="rounded rounded-circle img-thumbnail" style="" src="layout/img/placeholder-profile-sq.jpg">
                                            </div>
                                            <div class="col-md-10 mt-1">
                                                <div class="message">
                                                    <h5 id='comment-owner'><?=$name?></h5>
                                                    <p id='datetime'>${datetime}</p>
                                                    <p id='comment' class="lead">
                                                        ${data}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`)
                                    Swal.fire('تم ارسال تعليقك شكرا لك');
                            }
                        })
    }else{
        Swal.fire('التعليق لا يمكن ان يكون فارغ ');
    }

})



            </script>