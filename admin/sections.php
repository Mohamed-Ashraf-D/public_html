<?php
session_start();
$pageTitle = 'Comments';
if (isset($_SESSION['mobileAdmin'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

// start Manage comments page

    if ($do == 'Manage') {

        $stmt = $conn->prepare("SELECT * FROM sections");
        $stmt->execute();
        $sections = $stmt->fetchAll();
        if(!empty($sections)){
        ?>

        <div class="container">
            <h1 class="text-center">ادارة الفصول</h1>
            <div class="table-responsive">
                <table class=" main-table text-center table table-bordered table-striped table-sm">
                    <thead>
                    <tr>
                        <th>#رقم الفصل</th>
                        <th>الفصل</th>
                        <th>الوصف</th>
                        <th>التحكم</th>
                    </tr>
                    </thead>
                    <tbody class="default-number">
                    <?php
                    foreach ($sections as $section) {
                        echo "<tr>";
                        echo "<td>" .""."</td>";
                        echo "<td>" . $section['Name'] . "</td>";
                        echo "<td>" . $section['description'] . "</td>";
                        echo "<td>
                            <a href='sections.php?do=Edit&sectionId=" . $section['section_id'] . "' class=\"btn btn-primary btn-sm\"><i class='fa fa-edit'></i> تعديل</a>
                            <a href='sections.php?do=Delete&sectionId=" . $section['section_id'] . "' class=\"btn btn-danger btn-sm confirm\"><i class='fa fa-times'></i> حذف</a>";
                        echo "</td>";

                        echo "</tr>";
                    }
                    ?>

                    </tbody>

                </table>
                <a href='sections.php?do=Add' class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> اضافة فصل</a>

            </div>
        </div>
    <?php }else{
            echo "<div class='container'>";
            echo "<div class='alert alert-info'>لا يوجد فصول لعرضها</div>";
            echo "</div>";
        }
    }
     elseif ($do == 'Edit') { //Edit page
        // check if user id is get from get request and is numeric
        $sectionId = isset($_GET['sectionId']) && is_numeric($_GET['sectionId']) ? intval($_GET['sectionId']) : 0;
        //select all data from users table according to user id
        $stmt = $conn->prepare('SELECT * FROM sections WHERE section_id=?');
        // Execute query
        $stmt->execute(array($sectionId));
        //fetch data and store in array
        $rows = $stmt->fetch();
        //check if row is found in database
        $count = $stmt->rowCount();
        //show form if al its ok
        if ($count > 0) {
            ?>
            <h1 class="text-center">تعديل الفصل</h1>
            <div class="container">
                <form action="?do=Update" method="POST">
                    <input type="hidden" name="sectionId" value="<?= $sectionId ?>">
                    <div class="form-group row"><!-- start comment field-->
                        <label for="" class="col-sm-1 p-0 col-form-label">اسم الفصل</label>
                        <div class="col-sm-4">
                        <input type="text" class="form-control" name="section" value="<?=$rows['Name']?>">
                        </div>
                    </div>
                    <div class="form-group row"><!-- start comment field-->
                        <label for="" class="col-sm-1 p-0 col-form-label">الوصف</label>
                        <div class="col-sm-4">
                           <input type="text" class="form-control" name="desc" value="<?=$rows['description']?>">
                        </div>
                    </div> <!--end comment field-->

                    <div class="form-group row"><!--submit button field-->
                        <div class="offset-1 col-sm-4">
                            <input type="submit" id="" value="حفظ" class="form-control btn-primary">
                        </div>
                    </div> <!--end submit button field-->
                </form>
            </div>
            <?php
        } else {
            echo "<div class='container'>";
            $theMsg = '<div class="alert alert-danger">لايوجد هذا العنصر</div>';
            redirectHome($theMsg, '');
            echo "</div>";
        }
    } elseif ($do == 'Update') { //update page


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>تحديث الفصل</h1>";
            echo "<div class='container'>";
            //get variables from the form
            $sectionId = $_POST['sectionId'];
            $section = $_POST['section'];
            $desc = $_POST['desc'];

                //update the data in the data base
                $stmt2 = $conn->prepare('UPDATE sections SET Name=?,description=? WHERE section_id=?');
                $stmt2->execute(array($section,$desc, $sectionId));
                //echo success message
                $theMsg = "<div class='alert alert-success'>" . $stmt2->rowCount() . " records update successfully</div>";
                redirectHome($theMsg, 'back', 5);


        } else {
            $errorMsg = "لا يمكن المرور لهذه الصفحة مباشرة";
            redirectHome($errorMsg, 'back', 6);
        }
        echo "</div>";
    } elseif ($do == 'Delete') {
        //Delete user from database
        echo "<h1 class='text-center'>حذف الفصل</h1>";
        echo "<div class='container'>";
        // check if user id is get from get request and is numeric and store its value in userid
        $sectionId = isset($_GET['sectionId']) && is_numeric($_GET['sectionId']) ? intval($_GET['sectionId']) : 0;
        //select all data from users table according to user id
        // Execute query
        $check = checkItem('section_id', 'sections', $sectionId);

        //check if row is found in database
        //show form if al its ok
        if ($check > 0) {
            $stmt = $conn->prepare('DELETE FROM sections where section_id=:sectionId');
            $stmt->bindParam('sectionId', $sectionId);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " records deleted successfully</div>";
            redirectHome($theMsg, 'back', 5);

        } else {

            $theMsg = "<div class='alert alert-danger'>هذا المستخدم غير موجود</div>";
            redirectHome($theMsg, 'back', 5);

        }
        echo "</div>";
    }
    elseif ($do=='Add'){
        ?>
        <h1 class="text-center">اضافة صف دراسي</h1>
        <div class="container">
            <form action="?do=insert" method="POST">

                <div class="form-group row"><!--name field-->
                    <label for="" class="col-sm-1 p-0 col-form-label">اسم الفصل</label>
                    <div class="col-sm-4">
                        <input type="text" id="" name="name" placeholder="اسم الفصل الدراسي"
                               class="form-control" autocomplete="off" required="required">
                    </div>
                </div> <!--end name field-->
                <div class="form-group row"><!--description field-->
                    <label for="" class="col-sm-1 p-0 col-form-label">الوصف</label>
                    <div class="col-sm-4">
                        <input type="text" id="" name="description" placeholder="وصف الفصل الدراسي"
                               class="category form-control">
                    </div>
                </div> <!--end description field-->
                <div class="form-group row"><!--submit button field-->
                    <div class="offset-1 col-sm-4">
                        <input type="submit" id="" value="اضافة الفصل الدراسي" class="form-control btn-primary">
                    </div>
                </div> <!--end submit button field-->
            </form>

        </div>


        <?php
    }
    elseif ($do=='insert'){
        //insert category page
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>insert categories</h1>";
            echo "<div class='container'>";
            //get variables from the form
            $name = $_POST['name'];
            $disc = $_POST['description'];

                //insert the data in the data base
                $check = checkItem('Name', 'class', $name);
                if ($check == 1) {
                    $theMsg = '<div class="alert alert-danger">عفوا هذا الصف الدراسى موجود بالفعل</div>';
                    redirectHome($theMsg, 'back');

                } else {
                    //insert category info
                    $stmt = $conn->prepare("INSERT INTO 
                            sections(Name,Description) 
                            VALUES(:zname,:zdesc)");
                    $stmt->execute(array(
                        'zname'    => $name,
                        'zdesc'    => $disc,
                    ));
                    //echo success message
                    echo "<div class='container'>";
                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " تم اضافة السجل بنجاح</div>";
                    redirectHome($theMsg, 'back', 3);
                    echo "</div>";
                }


        } else {
            $errorMsg = "لا يمكن المرور الى هذه الصفحة مباشرة";
            redirectHome($errorMsg, '', 6);

        }
    }

    include $tpl . 'footer.php';
} else {
    header('Location:index.php');
    exit();
}


?>