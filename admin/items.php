<?php
session_start();
$pageTitle = 'Items';
if (isset($_SESSION['mobileAdmin'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // start Manage page
    if ($do == 'Manage') {
        //select all from items except admin

        $stmt = $conn->prepare("SELECT items.* ,class.Name AS CLASS ,users.name as uname FROM items
JOIN class ON class.ID=items.Cat_ID
INNER JOIN users ON users.UserID=items.Member_ID ORDER BY item_ID DESC ");
        $stmt->execute();
        $items = $stmt->fetchAll();
        if (!empty($items)) {
?>

            <div class="container">
                <h1 class="text-center">ادارة الدروس</h1>

                <div class="table-responsive">
                    <?php
                    $stmt = $conn->prepare('SELECT * FROM class');
                    $stmt->execute();
                    $classes = $stmt->fetchAll();
                    foreach ($classes as $class) {


                    ?>
                        <h4><?= $class['Name'] ?></h4>
                        <table class=" main-table text-center table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>#رقم الدرس</th>
                                    <th>اسم الدرس</th>
                                    <th>الوصف</th>
                                    <th>تاريخ الاضافة</th>
                                    <th>الصف</th>
                                    <th>المضيف</th>
                                    <th>التحكم</th>
                                </tr>
                            </thead>
                            <tbody class="default-number">
                                <?php
                                $stmt = $conn->prepare("SELECT * FROM items INNER join users ON items.Member_ID=users.UserID WHERE Cat_ID=?");
                                $stmt->execute(array($class['ID']));
                                $lessons = $stmt->fetchAll();
                                foreach ($lessons as $lesson) {
                                    echo "<tr>";
                                    echo "<td>" . $lesson['item_ID'] . "</td>";
                                    echo "<td>" . substr($lesson['Name'], 0, 90) . "</td>";
                                    echo "<td>" . substr($lesson['Description'], 0, 90) . "</td>";
                                    echo "<td>" . $lesson['Add_date'] . "</td>";
                                    echo "<td>" . $class['Name'] . "</td>";
                                    echo "<td>" . $lesson['name'] . "</td>";
                                    echo "<td>
                            <a href='newad.php?do=Edit&addId=" . $lesson['item_ID'] . "' class=\"btn btn-primary btn-sm\"><i class='fa fa-edit'></i> تعديل</a>
                            <a href='newad.php?do=Delete&addId=" . $lesson['item_ID'] . "' class=\"btn btn-danger btn-sm confirm\"><i class='fa fa-times'></i> حذف</a>
                            <a href='students_watches.php?do=View&addId=" . $lesson['item_ID'] . "' class=\"btn btn-info btn-sm\"><i class='fa fa-eye'></i> من حضر الدرس؟</a>
                            
                            ";


                                    echo "</td>";

                                    echo "</tr>";
                                }
                                ?>

                            </tbody>

                        </table>
                    <?php } ?>
                </div>
                <a href='newad.php?do=Add' class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> new item</a>
            </div>
            <br><br><br><br><br>
        <?php } else {
            echo "<div class='container'>";
            echo "<div class='alert alert-info'>there's is no items</div>";
            echo '<a href="newad.php?do=Add" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> new item</a>
';
            echo "</div>";
        }
    } elseif ($do == 'Add') {
        ?>
        <h1 class="text-center">اضافة درس جديد</h1>
        <div class="container">
            <form action="?do=insert" method="POST">

                <div class="form-group row">
                    <!--name field-->
                    <label for="" class="col-sm-1 p-0 col-form-label">Name</label>
                    <div class="col-sm-4">
                        <input type="text" id="" name="name" placeholder="Name of Item" class="form-control">
                    </div>
                </div>
                <!--end description field-->
                <div class="form-group row">
                    <!--name field-->
                    <label for="" class="col-sm-1 p-0 col-form-label">Description</label>
                    <div class="col-sm-4">
                        <input type="text" id="" name="desc" placeholder="Describe item" class="form-control">
                    </div>
                </div>
                <!--end description field-->
                <div class="form-group row">
                    <!--price field-->
                    <label for="" class="col-sm-1 p-0 col-form-label">Item Price</label>
                    <div class="col-sm-4">
                        <input type="text" id="" name="price" placeholder="item price" class="form-control">
                    </div>
                </div>
                <!--end price field-->
                <div class="form-group row">
                    <!--Country field-->
                    <label for="" class="col-sm-1 p-0 col-form-label">Country</label>
                    <div class="col-sm-4">
                        <input type="text" id="" name="country" placeholder="Country made" class="form-control">
                    </div>
                </div>
                <!--end country  field-->
                <div class="form-group row">
                    <!--Status field-->
                    <label for="status" class="col-sm-1 p-0 col-form-label">Status</label>
                    <div class="col-sm-4">
                        <select id="status" name="status">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">like New</option>
                            <option value="3">used</option>
                            <option value="4">old</option>
                        </select>
                    </div>
                </div>
                <!--end status field-->
                <div class="form-group row">
                    <!--Members field-->
                    <label for="status" class="col-sm-1 p-0 col-form-label">Members</label>
                    <div class="col-sm-4">
                        <select id="status" name="member">
                            <option value="0">...</option>
                            <?php
                            $allMember = getAllFrom('*', 'users', '', '', 'UserID');
                            foreach ($allMember as $user) {
                                echo "<option value='" . $user['UserID'] . "'>" . $user['Username'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!--end of members field-->
                <div class="form-group row">
                    <!--Categories field-->
                    <label for="status" class="col-sm-1 p-0 col-form-label">Categories</label>
                    <div class="col-sm-4">
                        <select id="status" name="cat">
                            <option value="0">...</option>
                            <?php
                            $allCats = getAllFrom('*', 'categories', 'WHERE parent=0', '', 'ID');
                            foreach ($allCats as $cat) {
                                echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                $allChild = getAllFrom("*", "categories", "WHERE parent={$cat['ID']}", "", "ID");
                                foreach ($allChild as $child) {
                                    echo "<option value='" . $child['ID'] . "'>--- "  . $child['Name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!--end of categories field-->
                <div class="form-group row">
                    <!--Tags field-->
                    <label for="" class="col-sm-1 p-0 col-form-label">Tags</label>
                    <div class="col-sm-4">
                        <input type="text" id="" name="tags" placeholder="Seprate tags with comma (,)" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <!--submit button field-->
                    <div class="offset-1 col-sm-4">
                        <input type="submit" id="" value="Add Item" class="form-control btn-primary">
                    </div>
                </div>
                <!--end submit button field-->
            </form>

        </div>


        <?php
    } elseif ($do == 'Edit') {
        //Edit page
        // check if item id is get from get request and is numeric
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        //select all data from items table according to user id
        $stmt = $conn->prepare('SELECT * FROM items WHERE item_ID=?');
        // Execute query
        $stmt->execute(array($itemid));
        //fetch data and store in array
        $item = $stmt->fetch();
        //check if row is found in database
        $count = $stmt->rowCount();
        //show form if al its ok
        if ($count > 0) {
        ?>
            <h1 class="text-center">تعديل درس</h1>
            <div class="container">
                <form action="?do=Update" method="POST">

                    <div class="form-group row">
                        <!--name field-->
                        <input type="hidden" name="itemid" value="<?= $itemid ?>">
                        <label for="" class="col-sm-1 p-0 col-form-label">اسم الدرس</label>
                        <div class="col-sm-4">
                            <input type="text" id="" name="name" placeholder="Name of Item" class="form-control" value="<?php echo $item['Name'] ?>">
                        </div>
                    </div>
                    <!--end description field-->
                    <div class="form-group row">
                        <!--name field-->
                        <label for="" class="col-sm-1 p-0 col-form-label">وصف الدرس</label>
                        <div class="col-sm-4">
                            <input type="text" id="" name="desc" placeholder="Describe item" class="form-control" value="<?php echo $item['Description'] ?>">
                        </div>
                    </div>
                    <!--end description field-->
                    <div class="form-group row">
                        <!--price field-->
                        <label for="" class="col-sm-1 p-0 col-form-label">كود الفيديو</label>
                        <div class="col-sm-4">
                            <input type="text" id="" name="video-code" placeholder="كود الفيديو" class="form-control" value="<?php echo $item['videoCode'] ?>">
                        </div>
                    </div>
                    <!--end price field-->

                    <!--end status field-->
                    <div class="form-group row mt-2">
                        <!--Members field-->
                        <label for="status" class="col-sm-1 p-0 col-form-label">المدرس</label>
                        <div class="col-sm-4">
                            <select id="status" name="teacher">
                                <?php
                                $allMembers = getAllFrom('*', 'users', '', '', 'UserID');
                                foreach ($allMembers as $user) {
                                    echo "<option value='" . $user['UserID'] . "'";
                                    if ($item['Member_ID'] == $user['UserID']) {
                                        echo 'selected';
                                    }
                                    echo ">" . $user['name'] . "</>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!--end of members field-->
                    <div class="form-group row mt-2">
                        <!--Categories field-->
                        <label for="status" class="col-sm-1 p-0 col-form-label">الصف</label>
                        <div class="col-sm-4">
                            <select id="status" name="class">
                                <?php
                                $allCat = getAllFrom('*', 'class', '', '', 'ID');
                                foreach ($allCat as $cat) {
                                    echo "<option value='" . $cat['ID'] . "'";
                                    if ($item['Cat_ID'] == $cat['ID']) {
                                        echo 'selected';
                                    }
                                    echo ">" . $cat['Name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!--end of categories field-->
                    <div class="form-group mt-2 row">
                        <!--Status field-->
                        <label for="status" class="col-sm-1 p-0 col-form-label">الفصل الدراسي</label>
                        <div class="col-sm-4">
                            <select class=" form-control" required name="section">

                                <option value="">الفصل الدراسي</option>
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
                    <!--end status field-->
                    <div class="form-group row">
                        <!--price field-->
                        <label for="" class="col-sm-1 p-0 col-form-label">رابط الاختبار</label>
                        <div class="col-sm-4">
                            <input type="text" id="" name="test_link" placeholder="لينك الاختبار ان وجد" class="form-control" value="<?php echo $item['test_link'] ?>">
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <!--submit button field-->
                        <div class="offset-1 col-sm-4">
                            <input type="submit" id="" value="حفظ التعديل" class="form-control btn-primary">
                        </div>
                    </div>
                    <!--end submit button field-->
                </form>

                <!--                comments /////////////////////////////////////////-->
                <?php
                $stmt = $conn->prepare("SELECT comments.*,users.Username AS userName FROM comments INNER JOIN items ON comments.item_id=items.item_ID INNER JOIN users ON comments.user_id=users.UserID WHERE comments.item_id=?");
                $stmt->execute(array($itemid));
                $rows = $stmt->fetchAll();
                if (!empty($rows)) {

                ?>


                    <h1 class="text-center">Mange [<?= $item['Name'] ?>] Comments</h1>
                    <div class="table-responsive">
                        <table class="default-number main-table text-center table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Comment</th>
                                    <th>User Name</th>
                                    <th>Added Date</th>
                                    <th>Control</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($rows as $row) {
                                    echo "<tr>";
                                    echo "<td>" . $row['comment'] . "</td>";
                                    echo "<td>" . $row['userName'] . "</td>";
                                    echo "<td>" . $row['comment_date'] . "</td>";
                                    echo "<td>
                            <a href='comments.php?do=Edit&commId=" . $row['c_id'] . "' class=\"btn btn-primary btn-sm\"><i class='fa fa-edit'></i> Edit</a>
                            <a href='comments.php?do=Delete&commId=" . $row['c_id'] . "' class=\"btn btn-danger btn-sm confirm\"><i class='fa fa-times'></i> Delete</a>";
                                    if ($row['status'] == 0) {
                                        echo " <a href='comments.php?do=Approve&commId=" . $row['c_id'] . "' class=\"btn btn-info btn-sm activate\"><i class='fa fa-check'></i> Approve</a>";
                                    }
                                    echo "</td>";

                                    echo "</tr>";
                                }
                                ?>

                            </tbody>

                        </table>
                    </div>
                <?php } ?>


            </div>


<?php
        } else {
            echo "<div class='container'>";
            $theMsg = '<div class="alert alert-danger">There is no such id</div>';
            redirectHome($theMsg, '');
            echo "</div>";
        }
    } elseif ($do == 'Update') {


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Update Item</h1>";
            echo "<div class='container'>";
            //get variables from the form
            $id = $_POST['itemid'];
            $name = $_POST['name'];
            $desc = $_POST['desc'];
            $vCode = $_POST['video-code'];
            $teacher = $_POST['teacher'];
            $class = $_POST['class'];
            $section = $_POST['section'];
            $testLink = $_POST['test_link'];


            $errorForm = array();
            if (empty($name)) {
                $errorForm[] = "لا يمكن ترك الاسم فارغ";
            }
            if (empty($desc)) {
                $errorForm[] = "لابد من كتابة وصف";
            }
            if (empty($vCode)) {
                $errorForm[] = "كود الفيديو هام للغاية";
            }
            if ($teacher == 0) {
                $errorForm[] = "اختر اسم المدرس";
            }
            if ($class == 0) {
                $errorForm[] = "اختر الصف الدراسي";
            }
            if ($section == 0) {
                $errorForm[] = "اختر الفصل الدراسي";
            }


            foreach ($errorForm as $error) {
                echo "<div class='alert alert-danger'>" . $error . "</div>";
            }
            if (empty($errorForm)) {
                //update the data in the data base
                $stmt2 = $conn->prepare('UPDATE items SET Name=?,Description=?,videoCode=?,Member_ID=?,Cat_ID=?,section=?,test_link=? WHERE item_ID=?');
                $stmt2->execute(array($name, $desc, $vCode, $teacher, $class, $section, $testLink, $id));
                //echo success message
                $theMsg = "<div class='alert alert-success'>" . $stmt2->rowCount() . " سجل تم تحديثه بنجاح</div>";
                redirectHome($theMsg, 'back', 5);
            }
        } else {
            $errorMsg = "you can't browse this page directly";
            redirectHome($errorMsg, 'back', 6);
        }
        echo "</div>";
    } elseif ($do == 'insert') {
        //insert member page
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Add Item</h1>";
            echo "<div class='container'>";
            //get variables from the form
            $name = $_POST['name'];
            $desc = $_POST['desc'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $member = $_POST['member'];
            $category = $_POST['cat'];
            $tags = $_POST['tags'];

            $errorForm = array();
            if (empty($name)) {
                $errorForm[] = "name can't be empty";
            }
            if (empty($desc)) {
                $errorForm[] = "Description can't be empty";
            }
            if (empty($price)) {
                $errorForm[] = "price can't be empty";
            }
            if (empty($country)) {
                $errorForm[] = "country can't be empty";
            }
            if ($status == 0) {
                $errorForm[] = "Status can't be empty";
            }
            if ($member == 0) {
                $errorForm[] = "Member can't be empty";
            }
            if ($category == 0) {
                $errorForm[] = "Category can't be empty";
            }

            foreach ($errorForm as $error) {
                echo "<div class='alert alert-danger'>" . $error . "</div>";
            }
            if (empty($errorForm)) {
                $stmt = $conn->prepare("INSERT INTO 
                            items(Name,Description,price,Add_date,Country_made,Status,Cat_ID,Member_ID,tags) 
                            VALUES(:mname,:mdesc,:mprice,now(),:mcountry,:mstatus,:mcat,:mmember,:mtags)");
                $stmt->execute(array(
                    'mname' => $name,
                    'mdesc' => $desc,
                    'mprice' => $price,
                    'mcountry' => $country,
                    'mstatus' => $status,
                    'mmember' => $member,
                    'mcat' => $category,
                    'mtags' => $tags,

                ));
                //echo success message
                echo "<div class='container'>";
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " records inserted successfully</div>";
                redirectHome($theMsg, 'back', 5);
                echo "</div>";
            }
        } else {
            $errorMsg = "you can't browse this page directly";
            redirectHome($errorMsg, '', 6);
        }
    } elseif ($do == 'Delete') {
        //Delete user from database
        echo "<h1 class='text-center'>Delete Item</h1>";
        echo "<div class='container'>";
        // check if item id is get from get request and is numeric and store its value in itemuid
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        //select all data from item table according to item id
        // Execute query
        $check = checkItem('item_ID', 'items', $itemid);

        //check if row is found in database
        //show form if al its ok
        if ($check > 0) {
            $stmt = $conn->prepare('DELETE FROM items where item_ID=:itemid');
            $stmt->bindParam('itemid', $itemid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " records deleted successfully</div>";
            redirectHome($theMsg, 'back', 5);
        } else {

            $theMsg = "<div class='alert alert-danger'>this user not exist</div>";
            redirectHome($theMsg, 'back', 5);
        }
    } elseif ($do = 'Approve') {
        echo "<h1 class='text-center'>Approve Item</h1>";
        echo "<div class='container'>";
        // check if user id is get from get request and is numeric and store its value in userid
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        //select all data from users table according to user id
        // Execute query
        $check = checkItem('Item_ID', 'items', $itemid);

        //check if row is found in database
        //show form if al its ok
        if ($check > 0) {
            $stmt = $conn->prepare('UPDATE items SET Approve=1 where item_ID=?');
            $stmt->execute(array($itemid));
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " records Updated successfully</div>";
            redirectHome($theMsg, 'back', 3);
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
