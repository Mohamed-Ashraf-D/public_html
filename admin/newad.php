<?php
ob_start();
session_start();

$pageTitle = 'اضافة درس جديد';

include "init.php";
if (isset($_SESSION['mobileAdmin'])) {

    $do = isset($_GET['do']) ? $_GET['do'] : 0;
    if ($do == 'Insert') {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $imageName = "";
            $extension = "";
            $videoName = "";
            $videoNameNoExt = "";
            $imageTmp = "";
            $imageSize = "";
            $imageType = "";
            $allowExte = "";
            $arrExt = "";
            $ext = "";
            if (isset($_FILES['pImage']) || isset($_FILES['uploadingfile'])) {

                $imageName = $_FILES['pImage']['name'];
                $extension = pathinfo($_FILES['uploadingfile']['name'], PATHINFO_EXTENSION);
                $videoName = $_FILES['uploadingfile']['name'];
                $videoNameNoExt = $_FILES['uploadingfile']['name'];
                $imageTmp = $_FILES['pImage']['tmp_name'];
                $imageSize = $_FILES['pImage']['size'];
                $imageType = $_FILES['pImage']['type'];
                $allowExte = array("jpg", "png", "jpeg", "gif");
                $arrExt = explode('.', $imageName);
                $ext = strtolower(end($arrExt));
            }



            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $desc = filter_var($_POST['desc'], FILTER_SANITIZE_STRING);
            $class = filter_var($_POST['class'], FILTER_SANITIZE_STRING);
            $section = filter_var($_POST['section'], FILTER_SANITIZE_STRING);
            $videoCode = filter_var($_POST['vcode'], FILTER_SANITIZE_STRING);
            $testLink = filter_var($_POST['test_link'], FILTER_SANITIZE_STRING);
            $explain_pdf = filter_var($_POST['PDF'], FILTER_SANITIZE_STRING);
            // if (empty($videoNameNoExt)) {
            //     $formError[] = "برجاء اختيار الفيديو الخاص بالدرس" . $imageName;
            // }
            echo $class;
            $formError = array();

            if (strlen($name) < 4) {
                $formError[] = 'name must be more than 3 characters';
            }
            if (strlen($desc) < 4) {
                $formError[] = 'description must be more than 9 character';
            }



            if (empty($class)) {
                $formError[] = 'you should select class';
            }
            if ($imageName !== "" && empty($imageName)) {
                $formError[] = "برجاء اختيار الصورة";
            }

            if ($imageName !== "" && empty($imageName)) {
                $formError[] = 'اختر صورة لوصف الدرس';
            }
            if ($imageName !== ""  && $imageSize > 4194304) {
                $formError[] = 'الصورة لابد ان تكون اقل من اربعة ميجا';
            }
            if ($imageName !== "" && !empty($arrExt) && !in_array($ext, $allowExte)) {
                $formError[] = "you can upload file from type PNG | JPG | JPEG | GIF";
            }
            if (empty($videoName) && empty($videoCode)) {
                $formError[] = "لا بد من اختيار الفيديو او الكود من اليوتيوب";
            }
            if (empty($formError)) {

                $pImage = rand(0, 1000000) . '_' . $imageName;
                $destination = 'layout/img/' . $pImage;
                move_uploaded_file($imageTmp, $destination);
                $stmt = $conn->prepare("INSERT INTO
                            items(Name,Description,section,videoCode,Add_date,Cat_ID,Member_ID,explain_pdf,test_link,img,video)
                            VALUES(:mname,:mdesc,:msection,:mvideoCode,now(),:mclass,:mmember,:mexplainpdf,:mtestlink,:mimg,:mvideo)");
                $stmt->execute(array(
                    'mname' => $name,
                    'mdesc' => $desc,
                    'msection' => $section,
                    'mvideoCode' => $videoCode,
                    'mclass' => $class,
                    'mmember' => $_SESSION['ID'],
                    'mtestlink' => $testLink,
                    'mexplainpdf' => $explain_pdf,
                    'mimg' => $pImage,
                    'mvideo' => $videoName


                ));
                if ($stmt) {
                    echo "<div class='container'>";
                    $theMsg = '<div class="alert alert-success">تم اضافة الدرس بنجاح</div>';
                    redirectHome($theMsg, 'back', '1');
                    echo "</div>";
                    echo "successfull inserted";
                } else {
                    echo "<div class='container'>";
                    $theMsg = '<div class="alert alert-success">تم اضافة الدرس بنجاح</div>';
                    redirectHome($theMsg, 'back', '1');
                    echo "</div>";
                    echo "حدث خطا";
                }
            } else {

                foreach ($formError as $err) {
                    $theMsg = '<div class="alert alert-danger">' . $err . '</div>';
                }
                redirectHome($theMsg, 'back', '5');
                echo $theMsg;
            }
        }
    } elseif ($do == 'Update') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_FILES['pImage']) || isset($_FILES['uploadingfile'])) {

                $imageName = $_FILES['pImage']['name'];
                $extension = pathinfo($_FILES['uploadingfile']['name'], PATHINFO_EXTENSION);
                $videoName = $_FILES['uploadingfile']['name'];
                $videoNameNoExt = $_FILES['uploadingfile']['name'];
                $imageTmp = $_FILES['pImage']['tmp_name'];
                $imageSize = $_FILES['pImage']['size'];
                $imageType = $_FILES['pImage']['type'];
                $allowExte = array("jpg", "png", "jpeg", "gif");
                $arrExt = explode('.', $imageName);
                $ext = strtolower(end($arrExt));
            }


           
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $desc = filter_var($_POST['desc'], FILTER_SANITIZE_STRING);
            $class = filter_var($_POST['class'], FILTER_SANITIZE_STRING);
            $section = filter_var($_POST['section'], FILTER_SANITIZE_STRING);
            $videoCode = filter_var($_POST['vcode'], FILTER_SANITIZE_STRING);
            $testLink = filter_var($_POST['test_link'], FILTER_SANITIZE_STRING);
            $explain_pdf = filter_var($_POST['PDF'], FILTER_SANITIZE_STRING);
            $teacher = filter_var($_POST['teacher'], FILTER_VALIDATE_INT);
            $addId = filter_var($_POST['addId'], FILTER_VALIDATE_INT);
            // if (empty($videoNameNoExt)) {
            //     $formError[] = "برجاء اختيار الفيديو الخاص بالدرس" . $imageName;
            // }
            echo $class;
            $formError = array();

            if (strlen($name) < 4) {
                $formError[] = 'name must be more than 3 characters';
            }
            if (strlen($desc) < 4) {
                $formError[] = 'description must be more than 9 character';
            }



            if (empty($class)) {
                $formError[] = 'you should select class';
            }
            if ($imageName !== "" && empty($imageName)) {
                $formError[] = "برجاء اختيار الصورة";
            }

            if ($imageName !== "" && empty($imageName)) {
                $formError[] = 'اختر صورة لوصف الدرس';
            }
            if ($imageName !== ""  && $imageSize > 4194304) {
                $formError[] = 'الصورة لابد ان تكون اقل من اربعة ميجا';
            }
            if ($imageName !== "" && !empty($arrExt) && !in_array($ext, $allowExte)) {
                $formError[] = "you can upload file from type PNG | JPG | JPEG | GIF";
            }
            if (empty($videoName) && empty($videoCode)) {
                $formError[] = "لا بد من اختيار الفيديو او الكود من اليوتيوب";
            }
            if (empty($formError)) {

                
                if(!empty($imageName)){
                    $pImage = rand(0, 1000000) . '_' . $imageName;
                    $destination = 'layout/img/' . $pImage;
                    move_uploaded_file($imageTmp, $destination);
                    $img3=',img='."'$pImage'";
                    echo $img3;
                     $img2=strval($img3);
                }else{
                    $img2="";
                    
                }
                if(!empty($videoName)){
                    $video=$videoName;
                    $video=',video='."'$videoName'";
                }else{
                    $video="";
                }
                    $stmt = $conn->prepare("UPDATE items SET Name=?,Description=?,section=?,videoCode=?,Cat_ID=?,Member_ID=?,explain_pdf=?,test_link=?  $img2 $video WHERE item_ID=? ");
                    $stmt->execute(array($name, $desc, $section, $videoCode, $class, $teacher,$explain_pdf, $testLink, $addId));
                    if ($stmt) {
                        echo "<div class='container'>";
                        $theMsg = '<div class="alert alert-success">Item Updated Successfully</div>';
                         redirectHome($theMsg, 'back', '1');
                        echo "</div>";
                    }
                
                // else {
                //     echo "<div class='container'>";
                //     $theMsg = '<div class="alert alert-danger">خطا برجاء اختيار فيديو الدرس</div>';
                //     redirectHome($theMsg, 'back', '5');
                //     echo "</div>";
                //     echo '<div>';
                // }
            } else {
                foreach ($formError as $err) {
                    echo "<div class='container'>";
                    $theMsg = '<div class="alert alert-danger">' . $err . '</div>';
                    redirectHome($theMsg, 'back', '5');
                    echo "</div>";
                    echo '<div>';
                }
            }
        }
    } elseif ($do == 'Delete') {
        //Delete user from database
        echo "<h1 class='text-center'>Delete Item</h1>";
        echo "<div class='container'>";
        // check if item id is get from get request and is numeric and store its value in itemuid
        $addId = isset($_GET['addId']) && is_numeric($_GET['addId']) ? intval($_GET['addId']) : 0;
        //select all data from item table according to item id
        // Execute query
        $check = checkItem('item_ID', 'items', $addId);

        //check if row is found in database
        //show form if al its ok
        if ($check > 0) {
            $stmt = $conn->prepare('DELETE FROM items where item_ID=:addid');
            $stmt->bindParam('addid', $addId);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " records deleted successfully</div>";
            redirectHome($theMsg, 'back', 1);
        } else {

            $theMsg = "<div class='alert alert-danger'>this user not exist</div>";
            redirectHome($theMsg, 'back', 5);
        }
    } elseif ($do == 'Add') {

?>

        <h1 class="text-center">انشاء درس جديد</h1>
        <div class="new-ads block">
            <div class="container">
                <div class="card">
                    <div class="card-header bg-primary text-white">اضافة درس جديد</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <form class="main-form mt-3" action="?do=Insert" method="POST" enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <!--name field-->
                                        <label for="" class="col-sm-2 m-2 text-center col-form-label">اسم الدرس</label>
                                        <div class="col-sm-9">
                                            <input type="text" pattern=".{4,}" title="this field should be at least 4 character" required id="" name="name" placeholder="اسم الدرس" class="form-control live m-2" data-class=".live-title">
                                        </div>
                                    </div>
                                    <!--end description field-->
                                    <div class="form-group row">
                                        <!--name field-->
                                        <label for="" class="col-sm-2 m-2 text-center col-form-label">الوصف</label>
                                        <div class="col-sm-9">
                                            <input type="text" title="This field should be at least 10 character" name="desc" placeholder="وصف الدرس" class="form-control live m-2" data-class=".live-desc">
                                        </div>
                                    </div>
                                    <!--end description field-->

                                    <!--end country  field-->
                                    <div class="form-group row">
                                        <!--Status field-->
                                        <label for="status" class="col-sm-2 m-2 text-center col-form-label">الفصل الدراسي</label>
                                        <div class="col-sm-9">
                                            <select id="section" class="m-2 form-control" required name="section">

                                                <option value="">الفصل الدراسي</option>
                                                <?php
                                                $sections = getAllFrom('*', 'sections', '', '', 'section_id', 'ASC');
                                                foreach ($sections as $section) { ?>
                                                    <option value='<?= $section['section_id'] ?>'><?= $section['Name'] ?> </option>;
                                                <?php
                                                }
                                                ?>

                                            </select>
                                        </div>
                                    </div>
                                    <!--end status field-->

                                    <div class="form-group row">
                                        <!--Categories field-->
                                        <label for="status" class="col-sm-2 text-center m-2 col-form-label">الصف</label>
                                        <div class="col-sm-9">
                                            <select class="form-control m-2" required id="class" name="class">
                                                <option value="">الصف </option>
                                                <?php
                                                $cats = getAllFrom('*', 'class', 'where parent=0', '', 'ID');
                                                foreach ($cats as $cat) { ?>
                                                    <option value='<?= $cat['ID'] ?>'><?= $cat['Name'] ?> </option>;
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <!--end of categories field-->

                                    <div class="form-group row">
                                        <!--name field-->
                                        <label for="" class="col-sm-2 m-2 text-center col-form-label">كود الفيديو</label>
                                        <div class="col-sm-9">
                                            <input type="text" title="This field should be at least 10 character" name="vcode" placeholder="اكتب كود الفيديو من اليوتيوب" class="form-control live m-2" data-class=".live-desc">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <!--name field-->
                                        <label for="" class="col-sm-2 m-2 text-center col-form-label">لينك PDF الشرح</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="PDF" pattern=".{5,}" title="This field should be at least 10 character" name="PDF" placeholder="لينك PDF الشرح من جوجل درايف" class="form-control live m-2" data-class="">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <!--name field-->
                                        <label for="" class="col-sm-2 m-2 text-center col-form-label">لينك الاختبار</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="" pattern=".{5,}" title="This field should be at least 10 character" name="test_link" placeholder="لينك اختبار الدرس ان وجد" class="form-control live m-2" data-class=".live-desc">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <!--Upload image field field-->
                                        <label for="" class="col-sm-2 m-2 text-center  col-form-label">اختر صورة الدرس</label>
                                        <div class="col-sm-9">
                                            <input type="file" onchange="loadFile(event)" id="" name="pImage" class="form-control m-2">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <!--Upload video field field-->
                                        <label for="" class="col-sm-2 m-2 text-center  col-form-label">اختر فيديو الدرس</label>
                                        <div class="col-sm-9">
                                            <input type="file" id="uploadingfile" name="uploadingfile" class="form-control m-2">
                                            <!-- <input class="btn btn-primary" type="button" value="Upload File" name="btnSubmit"> -->
                                            <div class="progress" id="progressDiv">
                                                <progress id="progressBar" value="0" max="100" style="width:100%; height: 1.2rem;"></progress>
                                            </div>
                                            <h3 id="statusP"></h3>
                                            <p id="uploaded_progress"></p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <!--submit button field-->
                                        <div class="offset-2  col-sm-9">
                                            <input type="submit" id="add-lesson" value="اضافة الدرس" class="form-control btn-primary m-4" onclick="uploadFileHandler()">
                                        </div>
                                    </div>
                                    <!--end submit button field-->
                                </form>
                            </div>
                            <div class="col-4">
                                <div class="card m-1" id="1" data-href="product_details.html">
                                    <img alt="product-image" width="150" height="300" class="card-img-top" id="output" src="https://aymanalamstu.online/admin/layout/img/placeholder-profile-sq.jpg">
                                    <div class="card-body live-preview">
                                        <h6 class="card-text item-name live-title">

                                        </h6>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--                  Start check if there's error or not an display error-->
                        <?php

                        if (!empty($formError)) {
                            foreach ($formError as $err) {
                                echo '<div class="alert alert-danger">' . $err . '</div>';
                            }
                        }

                        ?>

                        <!--                  End check if there's error or not an display error-->
                    </div>
                </div>
            </div>
        </div>
        <br><br><br><br><br>

        <?php


    } elseif ($do == 'Edit') {
        $addId = isset($_GET['addId']) && is_numeric($_GET['addId']) ? intval($_GET['addId']) : 0;
        $stmt2 = $conn->prepare("SELECT * FROM items WHERE item_ID=?");
        $stmt2->execute(array($addId));
        $item = $stmt2->fetch();
        $count = $stmt2->rowCount();
        if ($count > 0) {
        ?>
            <h1 class="text-center">تعديل الدرس</h1>
            <div class="new-ads block">
                <div class="container">
                    <div class="card">
                        <div class="card-header bg-primary text-white">تعديل الدرس</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <form class="main-form mt-3" action="?do=Update" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="addId" value="<?= $item['item_ID'] ?>">
                                        <div class="form-group row">
                                            <!--name field-->
                                            <label for="" class="col-sm-2 col-form-label text-center">اسم الدرس</label>
                                            <div class="col-sm-9">
                                                <input type="text" pattern=".{4,}" title="this field should be at least 4 character" required id="" name="name" placeholder="Name of Item" class="form-control live" data-class=".live-title" value="<?= $item['Name'] ?>">
                                            </div>
                                        </div>
                                        <!--end description field-->
                                        <div class="form-group row">
                                            <!--name field-->
                                            <label for="" class="col-sm-2 col-form-label text-center">الوصف</label>
                                            <div class="col-sm-9">
                                                <input type="text" required id="" pattern=".{10,}" title="This field should be at least 10 character" name="desc" placeholder="Describe item" class="form-control live" data-class=".live-desc" value="<?= $item['Description'] ?>">
                                            </div>
                                        </div>
                                        <!--end description field-->



                                        <div class="form-group row">
                                            <!--Categories field-->
                                            <label for="status" class="col-sm-2 text-center  col-form-label">الصف الدراسي</label>
                                            <div class="col-sm-9">
                                                <select class="form-control m-1" required id="status" name="class">
                                                    <option value="">...</option>
                                                    <?php
                                                    $cats = getAllFrom('*', 'class', '', '', 'ID');
                                                    foreach ($cats as $cat) {
                                                    ?>
                                                        <option value="<?= $cat['ID'] ?>" <?php if ($item['Cat_ID'] == $cat['ID']) {
                                                                                                echo 'selected';
                                                                                            } ?>><?= $cat['Name'] ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <!--end of categories field-->
                                        <div class="form-group row">
                                            <!--Categories field-->
                                            <label for="status" class="col-sm-2 text-center  col-form-label">الفصل الدراسي</label>
                                            <div class="col-sm-9">
                                                <select class="form-control m-1" required id="status" name="section">
                                                    <option value="">...</option>
                                                    <?php
                                                    $sections = getAllFrom('*', 'sections', '', '', 'section_id', 'ASC');
                                                    foreach ($sections as $section) { ?>
                                                        <option value='<?= $section['section_id'] ?> ' <?= ($section['section_id'] === $item['section']) ? 'selected' : ''; ?>><?= $section['Name'] ?> </option>;
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <!--end of categories field-->
                                        <div class="form-group row">
                                            <!--Tags field-->
                                            <label for="" class="col-sm-2 text-center col-form-label">كود الفيديو</label>
                                            <div class="col-sm-9">
                                                <input type="text" id="" name="vcode" placeholder="كود الفيديو من اليوتيوب" class="form-control" value="<?= $item['videoCode'] ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <!--name field-->
                                            <label for="" class="col-sm-2 text-center col-form-label">لينك PDF الشرح</label>
                                            <div class="col-sm-9">
                                                <input type="text" id="PDF" pattern=".{5,}" value="<?= $item['explain_pdf'] ?>" title="This field should be at least 10 character" name="PDF" placeholder="لينك PDF الشرح من جوجل درايف" class="form-control live" data-class="">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <!--Tags field-->
                                            <label for="" class="col-sm-2 text-center col-form-label">رابط الاختبار</label>
                                            <div class="col-sm-9">
                                                <input type="text" id="" name="test_link" placeholder="لينك الاختبار ان وجد" class="form-control" value="<?= $item['test_link'] ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <!--Categories field-->
                                            <label for="status" class="col-sm-2 text-center  col-form-label">اسم المدرس</label>
                                            <div class="col-sm-9">
                                                <select class="form-control m-1" required id="status" name="teacher">
                                                    <option value="">...</option>
                                                    <<?php
                                                        $allMembers = getAllFrom('*', 'users', 'where GroupID=1 AND avatar!=1', '', '', 'UserID');
                                                        foreach ($allMembers as $user) {
                                                            echo "<option value='" . $user['UserID'] . "'";
                                                            if ($item['Member_ID'] == $user['UserID']) {
                                                                echo 'selected';
                                                            }
                                                            echo ">" . $user['name'] . "</>";
                                                        }
                                                        ?> </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <!--Upload image field field-->
                                            <label for="" class="col-sm-2  text-center  col-form-label">اختر صورة الدرس</label>
                                            <div class="col-sm-9">
                                                <input type="file" onchange="loadFile(event)" id="" name="pImage" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <!--Upload video field field-->
                                            <label for="" class="col-sm-2  text-center  col-form-label">اختر فيديو الدرس</label>
                                            <div class="col-sm-9">
                                                <input type="file" id="uploadingfile" name="uploadingfile" class="form-control ">
                                                <div class="progress" id="progressDiv">
                                                    <progress id="progressBar" value="0" max="100" style="width:100%; height: 1.2rem;"></progress>
                                                </div>
                                                <h3 id="statusP"></h3>
                                                <p id="uploaded_progress"></p>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <!--submit button field-->
                                            <div class="offset-2  col-sm-9">
                                                <input type="submit" id="add-lesson" value="تحديث الدرس" class="form-control btn-primary m-4" onclick="uploadFileHandler()">
                                            </div>
                                        </div>
                                        <!--end submit button field-->
                                    </form>
                                </div>
                                <div class="col-4">
                                    <div class="card m-1" id="1" data-href="product_details.html">
                                        <?php
                                        if (empty($item['img'])) {
                                            echo "<img alt='product-image' id='output' width='150' height='300' class='card-img-top' src='layout/img/placeholder-profile-sq.jpg'>";
                                        } else {
                                            echo "<img alt='product-image' id='output' width='150' height='300' class='card-img-top' src='layout/img/{$item['img']}'>";
                                        }
                                        ?>
                                        <div class="card-body live-preview">
                                            <h6 class="card-text item-name live-title">
                                                <?= $item['Name'] ?>
                                            </h6>

                                            <span class="rating">
                                                <span class="fas fa-star checked"></span>
                                                <span class="fas fa-star checked"></span>
                                                <span class="fas fa-star checked"></span>
                                                <span class="fas fa-star"></span>
                                                <span class="fas fa-star"></span>
                                            </span>
                                            <button class="btn btn-primary rounded mx-auto w-100 d-block">مشاهدة الدرس
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--                  Start check if there's error or not an display error-->
                            <?php
                            if (!empty($formError)) {
                                foreach ($formError as $err) {
                                    echo '<div class="alert alert-danger">' . $err . '</div>';
                                }
                            }
                            ?>
                            <!--                  End check if there's error or not an display error-->
                        </div>
                    </div>
                </div>
            </div>
            <br><br>


<?php
        }
    } else {
        echo "<div class='container'>";
        $theMsg = '<div class="alert alert-danger">There is no such id</div>';
        redirectHome($theMsg, 'bsck', 5);
        echo "</div>";
    } //end of if do = edit

    ob_end_flush();
} else {
    header('Location:index.php');
    exit();
}
include $tpl . 'footer.php';

?>