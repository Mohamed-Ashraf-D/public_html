<?php
ob_start();
session_start();
$noNavar = '';
$pageTitle = 'Login';
include 'init.php';
$client_mac = md5($_SERVER['HTTP_USER_AGENT']);
if (isset($_SESSION['user'])) {
    header('Location:categories.php?pageId=' . $_SESSION['classID'].'&loginhead');
    exit();
} else if (isset($_COOKIE['rememberme'])) {
    // Decrypt cookie variable value
    $userid = decryptCookie($_COOKIE['rememberme']);
    // check if user exist in database
    $stmt = $conn->prepare("SELECT UserID,class.ID AS ID,users.GroupID AS GroupID,users.RegStatus AS RegStatus,mobile,users.name,Password,class.name as CLASS FROM users join class on users.class=class.ID WHERE UserID=? AND clientMac=? || clientMac=?");

    $stmt->execute(array($userid,$_COOKIE['mac'],$client_mac));
    $get = $stmt->fetch();

    $count = $stmt->rowCount();
    //check if the count>0 then enter to control panel
    
    if ($count > 0) {
        $name = $get['name'];
        $_SESSION['user'] = $get['mobile'];
        $_SESSION['classID'] =$get['ID'];// this page id for all categories according to class
        $_SESSION['name'] = $name; // define and pass username to session
        $_SESSION['uid'] = $get['UserID'];
        $_SESSION['CLASS'] = $get['CLASS'];
        $_SESSION['isAdmin'] = $get['GroupID'];


        header('Location:categories.php?pageId=' . $get['ID'].'&K2K');

        exit();
    }
}

//check if user coming from http request
$cookieMac = "";
$client_mac = md5($_SERVER['HTTP_USER_AGENT']);
if (!isset($_COOKIE['mac'])) {
    setcookie("mac", $client_mac, time() + (10 * 365 * 24 * 60 * 60));
    //  $cookieMac=$_COOKIE['mac'];
    $cookie = "cookie set";
} else {
    $cookieMac = $_COOKIE['mac'];
    $cookie = "cookie already found and set";
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $mobile = $_POST['mobile'];
        $password = $_POST['password'];
        $hashedPass = sha1($password);



        // set cookie if not set and store cookie variable to check if the same in database or not


        $cookieMac = $_COOKIE['mac'];
        // check if user exist in database
        $stmt = $conn->prepare("SELECT UserID,class.ID AS ID,users.GroupID AS GroupID,users.RegStatus AS RegStatus,mobile,users.name,Password,class.name as CLASS FROM users join class on users.class=class.ID WHERE class.ID=users.class AND mobile=? AND Password=? AND (clientMac=? || clientMac=?)");

        $stmt->execute(array($mobile, $hashedPass, $client_mac, $cookieMac));
        $get = $stmt->fetch();

        $count = $stmt->rowCount();
        //check if the count>0 then enter to control panel

        if ($count > 0 && $get['RegStatus'] == 1) {
            $name = $get['name'];
            $_SESSION['user'] = $mobile;
            $_SESSION['name'] = $name; // define and pass username to session
            $_SESSION['uid'] = $get['UserID'];
            $_SESSION['CLASS'] = $get['CLASS'];
            $_SESSION['isAdmin'] = $get['GroupID'];
            $_SESSION['classID'] =$get['ID'];
            $userid = $get['UserID'];

            if (isset($_POST['rememberme'])) {

                // Set cookie variables
                $days = 30;
                $value = encryptCookie($userid);
                setcookie("rememberme", $value, time() + ($days *  24 * 60 * 60 * 1000));
            }

            header('Location:categories.php?pageId='. $get['ID']);
        
        } elseif ($count > 0 && $get['RegStatus'] == 0) {
            $formError[] = "  برجاء الاتصال بهذا الرقم 01098650383 لتفعيل الحساب والدخول للمنصة";
            $class = "selected";
        } else {
            $formError[] = "  خطا فى اسم المستخدم او كلمة المرور اوالجهاز المستخدم غير مسجل";
        }
    } else {
        $class2 = "selected";
        $formError = array();
        $name = $_POST['name'];
        $mobile = $_POST['mobile'];
        $father_mobile = $_POST['father-mobile'];
        $password = $_POST['password'];
        $password2 = $_POST['re-password'];
        $governat = $_POST['governate'];
        $class = $_POST['class'];
        $client_mac = md5($_SERVER['HTTP_USER_AGENT']);
        if (isset($mobile)) {

            if (strlen($mobile) !== 11 && strlen($father_mobile) !== 11) {
                //Phone is 10 characters in length (###) ###-####
                $formError[] = "رقم الهاتف لابد ان ايكون مكون من 11 رقم";
            }
            if ($governat == "") {
                $formError[] = "من فضلك اختر المحافظة";
            }
            if ($class == "") {
                $formError[] = "من فضلك اختر الصف الدراسي";
            }
        }
        if (isset($password) && isset($password2)) {
            if (empty($password)) {
                $formError[] = "لايمكن ترك كلمة المرور فارغه";
            }
            if (sha1($password) !== sha1($password2)) {
                $formError[] = 'كلمتا المرور لا بد ان تكونا متطابقتين';
            }
        }
        if (isset($mobile) &&  isset($father_mobile)) {

            if (!preg_match('/^[0-9]{11}+$/', $mobile) && !preg_match('/^[0-9]{11}+$/', $father_mobile)) {
                $formError[] = "خطا فى رقم الموبايل";
            }
            if (empty($father_mobile) || empty($mobile)) {
                $formError[] = "لابد من كتابة ارقام الهواتف بشكل صحيح";
            }
            if ($mobile === $father_mobile) {
                $formError[] = "لابد ان يكون رقم هاتف ولي الامر ليس نفس هاتف الطالب";
            }
        }
        //if the user not exist in database and no error proceed to add user
        if (empty($formError)) {
            //insert the data in the data base
            $check = checkItem('mobile', 'users', $mobile);
            if ($check == 1) {
                $formError[] = 'عفوا هذاالمستخدم موجود بالفعل';
            } else {

                $stmt = $conn->prepare("INSERT INTO
                            users(name,mobile,fatherMobile,Password,governate,class,clientMac,RegStatus,subs_period,Date)
                            VALUES(:uname,:umobile,:ufatherMobile,:upass,:governate,:class,:clientmac,0,1,now())");
                $stmt->execute(array(
                    'uname' => $name,
                    'umobile' => $mobile,
                    'ufatherMobile' => $father_mobile,
                    'upass' => sha1($password),
                    'governate' => $governat,
                    'class' => $class,
                    'clientmac' => $client_mac
                ));
                //echo success message
                $successMsg = "تم التسجيل بنجاح";
            }
        }
    }
}
?>
<div class="container login-page m-auto">
    <h1>

    </h1>
    <?php
    // echo $client_mac = md5($_SERVER['HTTP_USER_AGENT'])."<br>";
    // echo$cookie;
    //     if (strpos($_SERVER['HTTP_USER_AGENT'], 'SamsungBrowser') !== false) {
    //     // User agent is Google Chrome
    //     echo "Samsung Browser is used";

    // }
    // echo $_SERVER['HTTP_USER_AGENT'];
    ?>
    <h3 class="text-center">
        <span data-click="login" class="selected">تسجيل الدخول</span> |
        <span data-click="signup" id="signup">تسجيل جديد</span>
    </h3>
    <!--    Start of login page-->
    <form class="login" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">

        <div class="input-container">
            <input type="tel" dir="auto" class="form-control rtl" autocomplete="username" name="mobile" placeholder="رقم الموبايل" onfocus="this.placeholder = ''" onblur="this.placeholder = 'رقم الموبايل'" min="0" max="11" maxlength="11" minlength="11" pattern="{11,11}">
        </div>
        <div class="input-container">
            <input type="password" class="form-control" autocomplete="current-password" name="password" placeholder="ادخل كلمة المرور" onfocus="this.placeholder = ''" onblur="this.placeholder = 'ادخل كلمة المرور'">
        </div>
        <?php
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'SamsungBrowser') !== false) {
            // User agent is Google Chrome
            echo " هذا المتصفح غير مدعوم برجاء تنزيل متصفح كروم للاندرويد او سفارى للايفون";
            echo "<ul>";
            echo "<li><a href='https://play.google.com/store/apps/details?id=com.android.chrome'> لمتصفح كروم للاندرويد اضغط هنا</a></li>";
            echo "<li><a href='https://apps.apple.com/no/app/safari/id1146562112'> لمتصفح سفارى للايفون اضغط هنا</a></li>";
            echo "<li><a href='https://www.google.com.sa/intl/ar/chrome/?brand=YTUH&gclid=CjwKCAjw9suYBhBIEiwA7iMhNLBydfeOMoe3JeGNzP0-RICGAKUH0lCJ4NB4NzA_Q0jDH2CWnxuWOxoCDvMQAvD_BwE&gclsrc=aw.ds'> لمتصفح كروم للكمبيوتر اضغط هنا</a></li>";
            echo "<li><a href='https://support.apple.com/en-us/HT201260'> لمتصفح سفارى للكمبيوتر اضغط هنا</a></li>";
            echo "</ul>";
        } else {
        ?>
            <input class="btn btn-primary btn-block form-control " name="login" type="submit" value="LOGIN">
            <div class="form-check">
  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="rememberme">
  <label class="form-check-label" for="flexCheckDefault">
    تذكرنى لتسجيل الدخول تلقائى
  </label>
</div>

        <?php } ?>
    </form>
    <!--    End of login page-->
    <!--    Start of signup page-->
    <form class="signup" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
        <p class="">
            1_برجاء استخدام نفس الجهاز ونفس المتصفح الذى ستخدمه فيما بعد سيتم تسجيل هذا الجهاز فقط
        </p>
        <p class="">
            2_ فى حالة وجود مشكلة او استفسار برجاء التواصل مع 01098650383
        </p>
        <div class="input-container">
            <input type="text" dir="auto" id="name" minlength="15" maxlength="40" class="form-control" required autocomplete="off" name="name" placeholder=" الاسم رباعى" onfocus="this.placeholder = ''" onblur="this.placeholder = 'ادخل الاسم رباعى'">
        </div>
        <div class="input-container">
            <input onkeypress="return onlyNumberKey(event)" required type="tel" id="tel1" class="form-control" autocomplete="off" name="mobile" placeholder="رقم الموبايل" onfocus="this.placeholder = ''" onblur="this.placeholder = 'رقم الموبايل'" min="0" max="11" maxlength="11" minlength="11" pattern="^01[0-2]\d{1,8}$">
        </div>
        <div class="input-container">
            <input onkeypress="return onlyNumberKey(event)" type="tel" id="tel2" class="form-control" required autocomplete="off" name="father-mobile" placeholder="رقم موبايل ولى الامر" onfocus="this.placeholder = ''" onblur="this.placeholder = 'رقم موبايل ولى الامر'" min="0" max="11" maxlength="11" minlength="11" pattern="^01[0-2]\d{1,8}$">
        </div>
        <div class="input-container">
            <input type="password" minlength="4" class="form-control pass" autocomplete="new-password" name="password" placeholder="اكتب كلمة مرور قوية" onfocus="this.placeholder = ''" onblur="this.placeholder = 'اكتب كلمة مرور قوية'">
        </div>
        <div class="input-container">
            <input type="password" minlength="4" class="form-control pass" autocomplete="retype-password" name="re-password" placeholder="اعادة ادخال كلمة المرور" onfocus="this.placeholder = ''" onblur="this.placeholder = 'اعادة ادخال كلمة المرور'">
        </div>
        <div class="input-container">

            <select id="governorate" name="governate" class="form-control">

                <option value="">اختر المحافظة</option>
                <?php
                $governorates = getAllFrom('*', 'governorate', '', '', '', 'govID', '');
                foreach ($governorates as $governorate) {

                ?>
                    <option value="<?= $governorate['govID']; ?>"><?= $governorate['governName']; ?></option>
                <?php
                }
                ?>
            </select>
        </div>

        <div class="input-container mt-2">
            <select id="class" name="class" class="form-control">
                <option value="">اختر الصف الدراسي</option>
                <?php
                $classes = getAllFrom('*', 'class', 'WHERE parent=0', '', '', 'ID', '');
                foreach ($classes as $class) {
                ?>
                    <option value="<?= $class['ID'] ?>"><?= $class['Name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <br>
        <div class="input-container">
            <input class="btn btn-success btn-block form-control" id="signup-btn" type="submit" name="signup" value="تسجيل">
        </div>

    </form>
    <!--    End of sign up page-->
    <div class="text-center mt-5">
        <?php
        if (!empty($formError)) {
            foreach ($formError as $error) {
                echo '<span class="alert alert-danger" style="font-size: 20px;" class="text-danger">' . $error . '</span>' . '<br><br><br>';
            }
        }
        if (isset($successMsg)) {
            echo '<span class="alert alert-success" style="font-size: 20px;" class="text-danger">' . $successMsg . '</span>' . '<br>';
        }

        ?>
    </div>
</div>
<?php
include $tpl . "footer.php";
ob_end_flush();
?>

<?php

// function UniqueMachineID($salt = "") {  
//     if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {  

//         $temp = sys_get_temp_dir().DIRECTORY_SEPARATOR."diskpartscript.txt";  
//         if(!file_exists($temp) && !is_file($temp)) file_put_contents($temp, "select disk 0\ndetail disk");  
//         $output = shell_exec("diskpart /s ".$temp);  
//         $lines = explode("\n",$output);  
//         $result = array_filter($lines,function($line) {  
//             return stripos($line,"ID:")!==false;  
//         });  


//         if(count($result)>0) {  
//             $result=array_values($result);
//             $result1= array_shift($result);  
//             $result = explode(":",$result1);  
//             $result=end($result);
//             $result = trim($result);         
//         } else $result = $output;         
//     } else {  
//         $result = shell_exec("blkid -o value -s UUID");    
//         if(stripos($result,"blkid")!==false) {  
//             $result = $_SERVER['HTTP_HOST'];  
//         }  
//     }     
//     return md5($salt.md5($result));  

// }  


// echo UniqueMachineID();  
?>