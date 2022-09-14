<?php
session_start();
$noNavbar = '';
$pageTitle = 'Login';

if (isset($_SESSION['mobileAdmin'])) {
    header('Location:dashboard.php');
}
include "init.php";

//check if user coming from http request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
     $mobile = $_POST['mobile'];
     $password = $_POST['pass'];
    $hashedPass = sha1($password);
// check if user exist in database
    $stmt = $conn->prepare('SELECT name,UserID,mobile,Password FROM users WHERE mobile=? AND Password=?  AND GroupID=1 Limit 1 ');

    $stmt->execute(array($mobile,$hashedPass));
    $rows=$stmt->fetch();
    
    $count = $stmt->rowCount();
//check if the count>0 then enter to control panel

    if ($count > 0) {
        $_SESSION['mobileAdmin'] = $mobile;// define and pass username to session
        $_SESSION['name']=$rows['name'];
        $_SESSION['ID']=$rows['UserID'];// pass id from database to  session variable
        header('Location:dashboard.php');
        exit();

    }else{
        echo "something went wrong";
    }
}

?>

<form class="form login" dir="rtl" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
    <h4 class="text-center">تسجيل الدخول</h4>
    <input class="form-control" type="text" name="mobile" autocomplete="username" placeholder="ادخل رقم الهاتف">
    <input class="form-control" type="password" name="pass" autocomplete="current-password" placeholder="ادخل كلمة السر">
    <input class="btn btn-primary btn-block form-control" type="submit" value="login"/>
</form>

<?php
// include $tpl . 'footer.php';
?>
