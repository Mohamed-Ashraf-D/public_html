<?php



session_start();
$pageTitle = 'الطلاب';
if (isset($_SESSION['mobileAdmin'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // start Manage page

    if ($do == 'Manage') {
        //select all from users except admin
        $query = '';
        if (isset($_GET['page']) && $_GET['page'] == 'pending') {
            $query = 'AND RegStatus=0';
        }
        $limit=30;
        isset($_GET['pageid'])? $pageid=$_GET['pageid']:$pageid=1;
        $start=($pageid-1) * $limit;
        $next=$pageid+1;
        $prev=$pageid-1;
        $stmt = $conn->prepare("SELECT * FROM users where GroupID!=1 $query ORDER BY UserID DESC LIMIT $start,$limit");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $stmt2=$conn->prepare("SELECT * FROM users where GroupID!=1 $query");
        $stmt2->execute();
        $pages=$stmt2->fetchAll();
        $page=count($pages);
         $pagination=$page/$limit;
        if (isset($_POST['subs_period'])) {
            $userid = $_POST['userid'];
            $stmt2 = $conn->prepare("SELECT Date FROM users Where UserID=?");
            $stmt2->execute(array($userid));
            $row = $stmt2->fetchColumn();

            $subs_period = is_numeric($_POST['subs_period']) ? intval($_POST['subs_period']) : 1;
            if ($subs_period === 1 || $subs_period === 3 || $subs_period === 9) {

                echo $subs_period;
                $stmt = $conn->prepare('UPDATE users SET subs_period=?,endDate=?,Date=now() WHERE UserID=?');

                $today = strtotime(date('Y-m-d'));
                //    echo $today."\n";
                $endDate = date("Y-m-d", strtotime("+$subs_period month", $today)) . "\n";
                $stmt->execute(array($subs_period, $endDate, $userid));
                $theMsg = "<div class='alert alert-success'>تمت اضافة مدة الاشتراك </div>";

                redirectHome($theMsg, 'back', 1);
            }
        }

        if (!empty($rows)) {
?>
            <div class="container-fluid">
                <h1 class="text-center">ادارة الطلاب</h1>
                <form>
                    <div class="m-auto w-50">

                        <label>بحث عن طالب</label>
                        <input class="form-control" placeholder="ابحث برقم الموبايل" type="text" id="live-search">
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered table-striped table-sm rtl" dir="rtl">
                        <thead>
                            <tr>
                                <th>رقم الطالب</th>
                                <th>الصورة</th>
                                <th>الاسم</th>
                                <th>رقم الموبايل</th>
                                <th>رقم ولي الامر</th>
                                <th>البلد </th>
                                <th>الصف </th>
                                <th>تاريخ التسجيل</th>
                                <th>مدة الاشتراك</th>
                                <th>تاريخ الانتهاء</th>
                                <th>التحكم</th>
                            </tr>
                        </thead>
                        <tbody id="student">
                            <?php
                            
                            foreach ($rows as $row) {
                                echo "<tr>";
                                
                                echo "<td class='student-id'>" . "" . "</td>";
                                echo "<td>";
                                if (empty($row['avatar'])) {
                                    echo "<img class='rounded rounded-circle' style='width: 50px;height: 50px' src='../layout/img/placeholder-profile-sq.jpg'>";
                                } else {
                                    echo "<img class='rounded rounded-circle' style='width: 50px;height: 50px' src='uploads/avatars/" . $row['avatar'] . "'>";
                                }
                                echo "</td>";
                                echo "<td>" . $row['name'] . "</td>";
                                echo "<td>" . $row['mobile'] . "</td>";
                                echo "<td>" . $row['fatherMobile'] . "</td>";
                                $gov = selectColumn("governName", "governorate", "govID", $row['governate']);
                                echo "<td>" . $gov . "</td>";
                                $class = selectColumn("Name", "class", "ID", $row['class']);
                                echo "<td>" . $class . "</td>";
                                echo "<td>" . $row['Date'] . "</td>";

                            ?><td>

                                    <form method='post' action='members.php'>
                                        <select class='form-control' name='subs_period'>

                                            <option value='1' <?php if ($row['subs_period'] == 1) echo "selected" ?>>شهر واحد</option>
                                            <option value='3' <?php if ($row['subs_period'] == 3) echo "selected" ?>>ثلاثة اشهر</option>
                                            <option value='9' <?php if ($row['subs_period'] == 9) echo "selected" ?>>تسعة اشهر</option>
                                        </select>
                                        <input type='hidden' name='userid' value='<?= $row['UserID'] ?>'>
                                        <button type='submit' class="btn btn-info btn-sm activate"><i class='fa fa-check'></i> تاكيد</لا>
                                    </form>
                                </td>
                                <td> <?= $row['endDate'] ?> </td>

                            <?php


                                echo "<td>
                            <a href='members.php?do=Edit&userid=" . $row['UserID'] . "' class=\"btn btn-primary btn-sm\"><i class='fa fa-edit'></i> تعديل</a>
                            <a href='members.php?do=Delete&userid=" . $row['UserID'] . "' class=\"btn btn-danger btn-sm confirm\"><i class='fa fa-times'></i> حذف</a>";
                                if ($row['RegStatus'] == 0) {
                                    echo " <a type='submit' href='members.php?do=Activate&userid=" . $row['UserID'] . "&' class=\"btn btn-info btn-sm activate\"><i class='fa fa-check'></i> تفعيل</a>";
                                }
                                echo "</td>";

                                echo "</tr>";
                            }

                            ?>

                        </tbody>

                    </table>
                    <div class="m-auto w-25">
                        
                            
                                <nav aria-label="Page navigation example ">
                                   
                                    <ul class="pagination ">
                                        <li class="page-item"><a class="page-link <?php if($pageid==1)echo"noclick" ?>" href="?pageid=<?=$prev?> ">السابق</a></li>
                                        <?php for($i=1;$i<=$pagination;$i++){?>
                                        <li class="page-item"><a class="page-link" href="?pageid=<?=$i?>"><?=$i?></a></li>
                                        <?php } ?>
                                        <li class="page-item"><a class="page-link" href="?pageid=<?=$next?>">التالى</a></li>
                                    </ul> 
                               
                                </nav>
                           
                        
                    </div>
                </div>
                <a href="members.php?do=Add" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> اضافة طالب جديد</a>
            </div>

        <?php } else {
            echo "<div class='container'>";
            echo "<div class='alert alert-info'>لا يوجد طلاب فى الوقت الحالي</div>";
            echo "<a href=\"members.php?do=Add\" class=\"btn btn-primary btn-sm\"><i class=\"fa fa-plus\"></i> new member</a>";
            echo "</div>";
        }
        ?>
    <?php } elseif ($do == 'Add') {
    ?>
        <h1 class="text-center">اضافة طالب جديد</h1>
        <div class="container">
            <form class="form-row" action="?do=insert" dir="rtl" method="POST" enctype="multipart/form-data">

                <div class="form-group row">
                    <!--username field-->
                    <label for="" class="col-sm-4 col-2 col-lg-2 p-0 col-form-label">الاسم</label>
                    <div class="col-sm-8 col-lg-6 col-4">

                        <input type="text" minlength="20" maxlength="40" class="form-control" required="required" autocomplete="off" name="name" placeholder='ادخل الاسم رباعى'">

                            </div>
                </div> <!--end username field-->
                <div class=" form-group row">
                        <!--password field-->
                        <label for="" class="col-sm-4 col-2 col-lg-2 p-0 col-form-label">رقم الموبايل</label>
                        <div class="col-sm-8 col-lg-6 col-4">
                            <input type="tel" class="form-control" autocomplete="off" name="mobile" placeholder="رقم الموبايل" min="0" max="11" maxlength="11" minlength="11" pattern="{11,11}">

                            <i class="show-pass fas fa-eye"></i>
                        </div>
                    </div>
                    <!--end password field-->
                    <div class="form-group row">
                        <!--Email field-->
                        <label for="" class="col-sm-4 col-2 col-lg-2 p-0 col-form-label">رقم ولى الامر</label>
                        <div class="col-sm-8 col-lg-6 col-4">
                            <input type="tel" class="form-control" autocomplete="off" name="father-mobile" placeholder="رقم ولى الامر" min="0" max="11" maxlength="11" minlength="11" pattern="{11,11}">

                        </div>
                    </div>
                    <!--end Email field-->
                    <div class="form-group row">
                        <!--Full Name field-->
                        <label for="" class="col-sm-4 col-2 col-lg-2 p-0 col-form-label">كلمة المرور</label>
                        <div class="col-sm-8 col-lg-6 col-4">
                            <input type="password" minlength="4" class="form-control" autocomplete="new-password" name="password" placeholder="اكتب كلمة مرور قوية">

                        </div>
                    </div>
                    <div class="form-group row">
                        <!--Full Name field-->
                        <label for="" class="col-sm-4 col-2 col-lg-2 p-0 col-form-label">كلمة المرور</label>
                        <div class="col-sm-8 col-lg-6 col-4">
                            <input type="password" minlength="4" class="form-control" autocomplete="retype-password" name="re-password" placeholder="اعادة ادخال كلمة المرور">

                        </div>
                    </div>
                    <!--end Full Name field-->

                    <!--end Profile picture field-->

                    <div class="form-group row">
                        <!--Profile picture field-->
                        <label for="" class="col-sm-4 col-2 col-lg-2 p-0 col-form-label">اختر المحافظة</label>
                        <div class="col-sm-8 col-lg-6 col-4">
                            <select name="governate" class="form-control m-1">

                                <option>اختر المحافظة</option>
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
                    </div>
                    <div class="form-group row">
                        <!--Profile picture field-->
                        <label for="" class="col-sm-4 col-2 col-lg-2 p-0 col-form-label">اختر الصف الدراسي</label>
                        <div class="col-sm-8 col-lg-6 col-4">
                            <select name="class" class="form-control m-1">
                                <option value="">اختر الصف الدراسي</option>
                                <?php
                                $classes = getAllFrom('*', 'class', 'WHERE parent=0', '', '', 'ID', '');
                                foreach ($classes as $class) {
                                ?>
                                    <option value="<?= $class['ID'] ?>"><?= $class['Name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <!--submit button field-->
                        <div class=" col-lg-6 col-sm-8 offset-lg-2 offset-2 offset-sm-4">
                            <input type="submit" id="" value="اضافة الطالب" class="form-control btn-primary">
                        </div>
                    </div>
                    <!--end submit button field-->

            </form>

        </div>
        <?php } elseif ($do == 'insert') {
        //insert member page
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $class2 = "selected";
            $formError = array();
            $name = $_POST['name'];
            $mobile = $_POST['mobile'];
            $father_mobile = $_POST['father-mobile'];
            $password = $_POST['password'];
            $password2 = $_POST['re-password'];
            $governat = $_POST['governate'];
            $class = $_POST['class'];
            $client_mac = md5($_SERVER['HTTP_USER_AGENT'] .  $_SERVER['REMOTE_ADDR']);
            if (isset($userName)) {
                $mobile = preg_replace('/[^0-9]/', '', $mobile);
                if (strlen($mobile) === 11) {
                    //Phone is 10 characters in length (###) ###-####
                    $formError[] = "رقم الهاتف لابد ان ايكون مكون من 11 رقم";
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
            if (isset($email)) {
                $filterEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
                if (filter_var($filterEmail) != true) {
                    $formError[] = "خطا فى الاميل";
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
                    echo "<div class='container'>";
                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " تم التسجيل بنجاح</div>";
                    redirectHome($theMsg, 'back', 1);
                    echo "</div>";
                }
            }
        } else {
            $errorMsg = "you can't browse this page directly";
            redirectHome($errorMsg, '', 6);
        }
    } elseif ($do == 'Edit') { //Edit page
        // check if user id is get from get request and is numeric
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        //select all data from users table according to user id
        $stmt = $conn->prepare('SELECT * FROM users WHERE UserID=? LIMIT 1');
        // Execute query
        $stmt->execute(array($userid));
        //fetch data and store in array
        $rows = $stmt->fetch();
        //check if row is found in database
        $count = $stmt->rowCount();
        //show form if al its ok
        if ($count > 0) {
        ?>
            <h1 class="text-center">تعديل بيانات الطالب</h1>
            <div class="container">
                <form action="?do=Update" method="POST" dir="rtl">
                    <input type="hidden" name="userid" value="<?= $userid ?>">
                    <div class="form-group row">
                        <!--username field-->
                        <label for="" class="col-sm-1 p-0 col-form-label">الاسم</label>
                        <div class="col-sm-4">
                            <input type="text" id="" name="name" placeholder="" value="<?= $rows['name'] ?>" class="form-control" autocomplete="off" required="required">
                        </div>
                    </div>
                    <!--end username field-->
                    <div class="form-group row">
                        <!--password field-->
                        <label for="" class="col-sm-1 p-0 col-form-label">كلمة السر</label>
                        <div class="col-sm-4">
                            <input type="hidden" name="oldPassword" value="<?= $rows['Password'] ?>">
                            <input type="password" id="" name="newPassword" placeholder="اترك كلمة السر فارغه اذا لم ترد التغيير" class="form-control" autocomplete="new-password">
                        </div>
                    </div>
                    <!--end password field-->
                    <div class="form-group row">
                        <!--Email field-->
                        <label for="" class="col-sm-1 p-0 col-form-label">رقم الهاتف</label>
                        <div class="col-sm-4">
                            <input type="tel" class="form-control" autocomplete="off" name="mobile" value="<?= $rows['mobile'] ?>" placeholder="رقم الموبايل" min="0" max="11" maxlength="11" minlength="11" pattern="{11,11}">
                        </div>
                    </div>
                    <!--end Email field-->
                    <div class="form-group row">
                        <!--Full Name field-->
                        <label for="" class="col-sm-1 p-0 col-form-label">رقم ولى الامر</label>
                        <div class="col-sm-4">
                            <input type="tel" class="form-control" value="<?= $rows['fatherMobile'] ?>" autocomplete="off" name="fatherMobile" placeholder="رقم ولى الامر" min="0" max="11" maxlength="11" minlength="11" pattern="{11,11}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <!--Full Name field-->
                        <label for="" class="col-sm-1 p-0 col-form-label">المحافظة</label>
                        <div class="col-sm-4">
                            <select name="governorate" class="form-control m-1">

                                <option>اختر المحافظة</option>
                                <?php
                                $governorates = getAllFrom('*', 'governorate', '', '', '', 'govID', '');
                                foreach ($governorates as $governorate) {

                                ?>
                                    <option value="<?= $governorate['govID']; ?>" <?= ($governorate['govID'] == $rows['governate']) ? 'selected' : '' ?>><?= $governorate['governName']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <!--Full Name field-->
                        <label for="" class="col-sm-1 p-0 col-form-label">الصف الدراسي</label>
                        <div class="col-sm-4">
                            <select name="class" class="form-control m-1">
                                <option value="">اختر الصف الدراسي</option>
                                <?php
                                $classes = getAllFrom('*', 'class', 'WHERE parent=0', '', '', 'ID', '');
                                foreach ($classes as $class) {
                                ?>
                                    <option value="<?= $class['ID'] ?>" <?= ($rows['class'] == $class['ID']) ? 'selected' : '' ?>><?= $class['Name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <!--end Full Name field-->
                    <div class="form-group row">
                        <!--submit button field-->
                        <div class="offset-1 col-sm-4">
                            <input type="submit" id="" value="Save" class="form-control btn-primary">
                        </div>
                    </div>
                    <!--end submit button field-->
                </form>
            </div>
<?php
        } else {
            echo "<div class='container'>";
            $theMsg = '<div class="alert alert-danger">There is no such id</div>';
            redirectHome($theMsg, '');
            echo "</div>";
        }
    } elseif ($do == 'Update') { //update page


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Update Members</h1>";
            echo "<div class='container'>";
            //get variables from the form
            $idu = $_POST['userid'];
            $name = $_POST['name'];
            $mobile = $_POST['mobile'];
            $father_mobile = $_POST['fatherMobile'];
            $governorate = $_POST['governorate'];
            $class = $_POST['class'];

            //password trick
            $pass = '';
            if (empty($_POST['newPassword'])) { //check if user leave password empty
                $pass = $_POST['oldPassword']; // if he left password empty => then keep old password as it was
            } else {
                $pass = sha1($_POST['newPassword']); // if he write password then change to new password
            }
            //end password trick
            $errorForm = array();
            if (strlen($name) < 20) {
                $errorForm[] = "لا يمكن ان يكون الاسم اقل من 25 حرف";
            }
            if (strlen($name) > 45) {
                $errorForm[] = "الاسم لا يمكن ان يكون  اكثر من 45 حرف";
            }
            if (empty($name)) {
                $errorForm[] = "لا يمكن ترك الاسم فارغ";
            }
            if (empty($mobile)) {
                $errorForm[] = "لايمكن ترك الموبايل فارغ";
            }
            if (empty($father_mobile)) {
                $errorForm[] = "يجب كتابة رقم ولى الامر";
            }
            foreach ($errorForm as $error) {
                echo "<div class='alert alert-danger'>" . $error . "</div>";
            }
            if (empty($errorForm)) {
                $stmt3 = $conn->prepare("SELECT * FROM users WHERE mobile=? AND UserID!=?");
                $stmt3->execute(array($mobile, $idu));
                $count2 = $stmt3->rowCount();
                if ($count2 == 1) {
                    $theMsg = '<div class="alert-danger alert">sorry this user is already exist</div>';
                    redirectHome($theMsg, 'back', 3);
                } else {
                    //update the data in the data base
                    $stmt2 = $conn->prepare('UPDATE users SET name=?,mobile=?,fatherMobile=?,Password=?,governate=?,class=? WHERE UserID=?');
                    $stmt2->execute(array($name, $mobile, $father_mobile, $pass, $governorate, $class, $idu));
                    //echo success message
                    $theMsg = "<div class='alert alert-success'>" . $stmt2->rowCount() . " records update successfully</div>";
                    redirectHome($theMsg, 'back', 1);
                }
            }
        } else {
            $errorMsg = "you can't browse this page directly";
            redirectHome($errorMsg, 'back', 6);
        }
        echo "</div>";
    } elseif ($do == 'Delete') {
        //Delete user from database
        echo "<h1 class='text-center'>Delete Member</h1>";
        echo "<div class='container'>";
        // check if user id is get from get request and is numeric and store its value in userid
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        //select all data from users table according to user id
        // Execute query
        $check = checkItem('userid', 'users', $userid);

        //check if row is found in database
        //show form if al its ok
        if ($check > 0) {
            $stmt = $conn->prepare('DELETE FROM users where UserID=:userid');
            $stmt->bindParam('userid', $userid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " records deleted successfully</div>";
            redirectHome($theMsg, 'back', 1);
        } else {

            $theMsg = "<div class='alert alert-danger'>this user not exist</div>";
            redirectHome($theMsg, 'back', 5);
        }
        echo "</div>";
    } elseif ($do == 'Activate') {
        echo "<h1 class='text-center'>Activate Member</h1>";
        echo "<div class='container'>";
        // check if user id is get from get request and is numeric and store its value in userid
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        //select all data from users table according to user id
        // Execute query
        $check = checkItem('userid', 'users', $userid);

        //check if row is found in database
        //show form if al its ok
        if ($check > 0) {
            $stmt = $conn->prepare('UPDATE users SET RegStatus=1 where UserID=?');
            $stmt->execute(array($userid));
            $dt = strtotime("2012-12-21");
            echo date("Y-m-d", strtotime("+1 month", $dt)) . "\n";
            $stmt = $conn->prepare('INSERT into users');

            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " records Updated successfully</div>";
            redirectHome($theMsg, 'back', 1);
        } else {

            $theMsg = "<div class='alert alert-danger'>this user not exist</div>";
            redirectHome($theMsg, 'back', 5);
        }
    }

    include $tpl . 'footer.php';
} else {
    header('Location:index.php');
    exit();
}

?>
<script>
    x=[];
            for(let i=<?=$start+1?>;i<=<?=($pageid*$limit)?>;i++){
            x.push(i);
    }
    var rowsCount = $('#student tr');
    for(let z=0;z<=x.length;z++){
        var firstCol = rowsCount[z].firstChild; //n=3
        firstCol.innerText=x[z];
    }
        
    
</script>
