<?php
session_start();
$pageTitle = 'Categories';
if (isset($_SESSION['mobileAdmin'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

// start Manage page
    if($do=='Manage'){
        $sort='ASC';
        $sort_array=array('ASC','DESC');
        if (isset($_GET['sort'])&&in_array($_GET['sort'],$sort_array)){
            $sort=$_GET['sort'];
        }
        $stmt2=$conn->prepare("SELECT * FROM class  ORDER BY Ordering $sort");
        $stmt2->execute();
        $cats=$stmt2->fetchAll();?>
        <div class="container categories">
            <h1 class="text-center">ادارة الصفوف</h1>
                    <div class="card">
                        <div class="card-header">
                            <span>ادارة الصفوف الدراسية</span>
                            <div class="option">
                                ترتيب <i class="fa fa-sort"></i>:[
                                <a class="<?php if ($sort=='ASC'){echo 'active';}?>" href="?sort=ASC">تصاعدى |</a>
                                <a class="<?php if ($sort=='DESC'){echo 'active';}?>" href="?sort=DESC">تنازلى</a> ]
                                عرض <i class="fa fa-eye"></i>:[
                                <span data-view="Full">كامل</span> |
                                <span data-view="Classic">كلاسيكى</span> ]
                            </div>
                        </div>
                        <div class="card-body">
                            <?php
                            echo "<ul class='list-unstyled list-group'>";
                            foreach ($cats as $cat){

                                echo "<li class='cat list-group-item'>";
                                    echo "<div class='hidden-buttons'>";
                                        echo "<a href='categories.php?do=Edit&&catid=".$cat['ID']."' class='btn btn-primary btn-sm btn-edit' style='color: white;'>تعديل <i class='fa fa-edit'></i></a>";
                                        echo "<a href='categories.php?do=Delete&catid=".$cat['ID']."' class='confirm btn btn-danger btn-sm btn-del' style='color: white;'>حذف <i class='fa fa-times'></i></a>";
                                    echo "</div>";
                                    echo "<h3 class='p-3 bg-light borderd '>".$cat['Name']."</h3>";
                                    echo "<div class='full-view pb-3'>";
//                                        echo "<p >";if ($cat['Description']==''){echo "this is empty";}else{echo $cat['Description'];} echo "</p>";
                                        if ($cat['Visibility']==1){echo "<span class='visibility'>Hidden <span class='fa fa-eye'></span></span>";}else{echo "";}
                                        if ($cat['Allow_Comment']==1){echo "<span class='commenting'>comment disabled <span class='fa fa-window-close'></span></span>";}else{echo "";}
                                        if ($cat['Allow_Ads']==1){echo "<span class='advertises'>ads disabled <span class='fa fa-window-close'></span></span>";}else{echo "";}



                                // $childCats=getAllFrom("*","class","","","ID","ASC");
                                // if (!empty($childCats)) {
                                // echo "<h5 class='ml-4 mt-3'>Sub Categories</h5>";
                                // echo "<ul class='ml-4 list-unstyled list-group pb-2'>";
                                // foreach ($childCats as $c){?>
                                <!-- //     <li class="child-link list-group-item list-group-item-action bg-light">
                                //         <a class="subcat " href='categories.php?do=Edit&&catid=<?=$c['ID']?>'><h6><?=$c['Name']?></h6></a>
                                //         <a href='categories.php?do=Delete&catid=<?=$c['ID']?>' class='confirm show-delete'>Delete</a>
                                //     </li> -->
                                    <?php
                                // }
                                // echo "</ul>";
                                
                                echo "</div>";
                                echo "</li>";
                                echo "<hr>";

                            }


                            echo "</ul>";

                            ?>
                            <?php

                            ?>
                        </div>
                    </div>
            <div class="btn-add mt-5 mb-5">
                <a href="categories.php?do=Add" class="btn btn-info">اضافة صف جديد <i class="fa fa-plus"></i></a>
            </div>
        </div>
        <?php
    }
    elseif ($do=='Add'){
        ?>
        <h1 class="text-center">اضافة صف دراسي</h1>
        <div class="container">
            <form action="?do=insert" method="POST">

                <div class="form-group row"><!--name field-->
                    <label for="" class="col-sm-1 p-0 col-form-label">اسم الصف</label>
                    <div class="col-sm-4">
                        <input type="text" id="" name="name" placeholder="اسم الصف الدراسي"
                               class="form-control" autocomplete="off" required="required">
                    </div>
                </div> <!--end name field-->
                <div class="form-group row"><!--description field-->
                    <label for="" class="col-sm-1 p-0 col-form-label">الوصف</label>
                    <div class="col-sm-4">
                        <input type="text" id="" name="description" placeholder="وصف الصف الدراسي"
                               class="category form-control">
                    </div>
                </div> <!--end description field-->
                <div class="form-group row"><!--Ordering field-->
                    <label for="" class="col-sm-1 p-0 col-form-label">الترتيب</label>
                    <div class="col-sm-4">
                        <input type="text" id="" name="ordering" placeholder="ترتيب الصف"
                               class="form-control" >
                    </div>
                </div> <!--end ordering field-->
                

                <div class="form-group row"><!--submit button field-->
                    <div class="offset-1 col-sm-4">
                        <input type="submit" id="" value="اضافة الصف الدراسي" class="form-control btn-primary">
                    </div>
                </div> <!--end submit button field-->
            </form>

        </div>


        <?php
    }
    elseif ($do=='Edit'){

        //Edit page
        // check if user id is get from get request and is numeric
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
        //select all data from users table according to user id
        $stmt = $conn->prepare('SELECT * FROM class WHERE ID=?');
        // Execute query
        $stmt->execute(array($catid));
        //fetch data and store in array
        $cat = $stmt->fetch();
        //check if row is found in database
        $count = $stmt->rowCount();
        //show form if al its ok
        if ($count > 0) {
            ?>
            <h1 class="text-center">تعديل الصف الدراسي</h1>
            <div class="container">
                <form action="?do=Update" method="POST">

                    <div class="form-group row"><!--name field-->
                        <input type="hidden" name="catid" value="<?=$cat['ID']?>">
                        <label for="" class="col-sm-1 p-0 col-form-label">اسم الصف</label>
                        <div class="col-sm-4">
                            <input type="text" id="" name="name" placeholder="اسم الصف الدراسي"
                                   class="form-control"  required="required" value="<?=$cat['Name']?>">
                        </div>
                    </div> <!--end name field-->
                    <div class="form-group row"><!--description field-->
                        <label for="" class="col-sm-1 p-0 col-form-label">الوصف</label>
                        <div class="col-sm-4">
                            <input type="text" id="" name="description" placeholder="وصف الصف"
                                   class="category form-control" value="<?=$cat['Description']?>">
                        </div>
                    </div> <!--end description field-->
                    <div class="form-group row"><!--Ordering field-->
                        <label for="" class="col-sm-1 p-0 col-form-label">الترتيب</label>
                        <div class="col-sm-4">
                            <input type="text" id="" name="ordering" placeholder="ترتيب الصف الدراسي"
                                   class="form-control" value="<?=$cat['Ordering']?>">
                        </div>
                    </div> <!--end ordering field-->
                    

                    

                    

                    

                    <div class="form-group row"><!--submit button field-->
                        <div class="offset-1 col-sm-4">
                            <input type="submit" id="" value="تحديث الصف" class="form-control btn-primary">
                        </div>
                    </div> <!--end submit button field-->
                </form>
            </div>
            <?php
        } else {
            echo "<div class='container'>";
            $theMsg = '<div class="alert alert-danger">There is no such id</div>';
            redirectHome($theMsg, '');
            echo "</div>";
        }

    }
    elseif ($do=='Update'){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Update Category</h1>";
            echo "<div class='container'>";
            //get variables from the form
            $catid = $_POST['catid'];
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $order = $_POST['ordering'];



                //update the data in the data base
                $stmt2 = $conn->prepare('UPDATE class SET Name=?,Description=?,Ordering=? WHERE ID=?');
                $stmt2->execute(array($name, $desc, $order,$catid));
                //echo success message
                $theMsg = "<div class='alert alert-success'>" . $stmt2->rowCount() . " records update successfully</div>";
                redirectHome($theMsg, 'back', 3);


        } else {
            $errorMsg = "you can't browse this page directly";
            redirectHome($errorMsg, 'back', 3);
        }
        echo "</div>";

    }
    elseif ($do=='insert'){
        //insert category page
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>insert categories</h1>";
            echo "<div class='container'>";
            //get variables from the form
            $name = $_POST['name'];
            $disc = $_POST['description'];
            $order = $_POST['ordering'];

                //insert the data in the data base
                $check = checkItem('Name', 'class', $name);
                if ($check == 1) {
                    $theMsg = '<div class="alert alert-danger">عفوا هذا الصف الدراسى موجود بالفعل</div>';
                    redirectHome($theMsg, 'back');

                } else {
                    //insert category info
                    $stmt = $conn->prepare("INSERT INTO 
                            class(Name,Description,Ordering) 
                            VALUES(:zname,:zdesc,:zorder)");
                    $stmt->execute(array(
                        'zname'    => $name,
                        'zdesc'    => $disc,
                        'zorder'   => $order,
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
    elseif ($do=='Delete'){

        //Delete user from database
        echo "<h1 class='text-center'>Delete Category</h1>";
        echo "<div class='container'>";
        // check if user id is get from get request and is numeric and store its value in userid
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
        //select all data from users table according to user id
        // Execute query
        $check = checkItem('ID', 'class', $catid);

        //check if row is found in database
        //show form if al its ok
        if ($check > 0) {
            $stmt = $conn->prepare('DELETE FROM class where ID=:zid');
            $stmt->bindParam('zid', $catid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " records deleted successfully</div>";
            redirectHome($theMsg, 'back', 5);

        } else {

            $theMsg = "<div class='alert alert-danger'>this user not exist</div>";
            redirectHome($theMsg, 'back', 5);

        }
        echo "</div>";

    }



    include $tpl . 'footer.php';
} else {
    header('Location:index.php');
    exit();
}