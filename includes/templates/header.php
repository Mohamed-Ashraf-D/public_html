<!DOCTYPE html>
<html dir="rtl" lang="ar">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ويتم تعريف الكيمياء بإنها علم تحويل المواد. ومازالت مرونة الصناعة الكيميائية تزداد مع تطور المعارف، كما أنها انتشرت بالكامل خلال الصراعات الاقتصادية والعسكرية للقرن العشرين، بالرغم من الحفاظ على الحد المستمر من حجم الإستثمارات الجامد في المصانع. الكيمياء مع ايمن علام مع مستر ايمن هتتعلم الكيمياء بكل بساطة ايمن علام كيمياء aymanalamstu">
    <h1 class="hidden d-none">كيمياء ايمن علام للثانوية العامة </h1>
    <h2 class="hidden d-none">ayman alam chemistry aymanalamstu chemistry </h1>
        <title><?php getTitle(); ?></title>
        <link rel="stylesheet" href="<?= $css ?>bootstrap-rtl.min.css">
        <link rel="stylesheet" href="<?= $fonts ?>all.min.css">
        <link rel="stylesheet" href="<?= $css ?>sidebar.css">
        <link rel="stylesheet" href="<?= $css ?>sweetalert.css">
        <link rel="stylesheet" href="<?= $css ?>style.css">

        <!-- --------------------------------------------------- -->

        <!-- ----------------------------------------------------- -->
</head>

<body>


    <div class="upper-bar">
        <div class="container">
            <?php
            if (isset($_SESSION['user'])) {
                $user = $_SESSION['user'];
                $name = $_SESSION['name'];
                $uid = $_SESSION['uid'];
            ?>
                <div class="cart">
                    <?php
                    //    $stmt=$conn->prepare('SELECT COUNT(id) FROM cartitems WHERE user_id=?');
                    //    $stmt->execute(array($uid));
                    //    $countCartItem=$stmt->fetchColumn();
                    ?>
                    <div class="cart-link">

                    </div>
                </div>
                <div class="user-log-wrap">
                    <a class="dropdown-toggle text-white  user-log" href="dropdown" id="nav-app" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded rounded-circle" width="40" src="<?= $img ?>placeholder-profile-sq.jpg"> &nbsp;<?= substr($name, 0, 70) ?>
                    </a>
                    <div class="dropdown-menu" id="dropdown" aria-labelledby="nav-app">
                        <a class="dropdown-item" href="profile.php">حسابي</a>
                        <a class="dropdown-item" href="categories.php?pageId=<?= $_SESSION['classID'] ?>">الدروس</a>
                        <a class="dropdown-item" href="logout.php">تسجيل الخروج</a>
                    </div>
                </div>
            <?php

            } else {
            ?>
                <a href="login.php">
                    <button type="button" class="btn btn-sm btn-dark"> <span class="">تسجيل الدخول / تسجيل جديد</span></button>
                </a>
            <?php } ?>
        </div>
    </div>


    <nav class="navbar navbar-expand-lg navbar-dark bg-dark nav-2">
        <a class="navbar-brand" href="#">الكيمياء مع ايمن علام</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <ul class="navbar-nav">

                <li class="nav-item">
                    <?php
                    if (isset($_SESSION['user'])) {
                        $stmt3 = $conn->prepare("SELECT class.Name AS cname ,class.ID As ID  FROM class Join users on class.ID=users.class WHERE UserID=?");
                        $stmt3->execute(array($uid));
                        $class2 = $stmt3->fetch();
                        $class = isset($_GET['pageId']) ? $_GET['pageId'] : "";
                    ?>
                        <a class="nav-link active <?php if ($class == $class2['ID']) {
                                                        echo "hover";
                                                    } ?>" aria-current="page" href="categories.php?pageId=<?= $class2['ID'] ?>"><?= $class2['cname'] ?></a>
                    <?php } ?>
                </li>
                <?php
                if (isset($_SESSION['user'])) {
                    $user = $_SESSION['user'];
                    $uid = $_SESSION['uid'];
                    //get all classes except current class
                    $getAllCats = getAllFrom("*", "class", "WHERE parent=0 AND ID !={$_SESSION['classID']}", "", "ID", "ASC");
                    if ($_SESSION['isAdmin'] && $_SESSION['isAdmin']==1) {
                        foreach ($getAllCats as $cat) {

                ?>

                            <li class="nav-item">
                                <a class="nav-link active <?php if ($class == $cat['ID']) {
                                                                echo "hover";
                                                            } ?>" aria-current="page" href="categories.php?pageId=<?= $cat['ID'] ?>"><?= $cat['Name'] ?></a>
                            </li>
                    <?php
                        }
                    }
                } else {
                    ?>



                    <li class="nav-item">
                        <a class="nav-link" href="index.php">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://wa.me/+201098650383">تواصل واتساب</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">تسجيل دخول/ تسجيل جديد</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">عن مستر ايمن</a>
                    </li>

                <?php
                }

                ?>


                </li>

            </ul>

        </div>
    </nav>
    <?php if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
        $uid = $_SESSION['uid'];
        $className = $_SESSION['CLASS'];

    ?>

        <nav class="navbar navbar-expand navbar-dark bg-primary nav-3"> <a href="#menu-toggle" id="menu-toggle" class="navbar-brand"><span class="navbar-toggler-icon"></span></a> <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample02" aria-controls="navbarsExample02" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
            <div class="collapse navbar-collapse" id="navbarsExample02">
                <ul class="navbar-nav mr-auto">
                    <?php 
                    
                    if(isset($_GET['pageId']) && isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']==1){
                        $condition=$class;
                    }else{
                        $condition=$_SESSION['classID'];
                    }
                    $stmt=$conn->prepare("SELECT class.Name FROM class WHERE ID={$condition}");
                    $stmt->execute();
                    $className=$stmt->fetch();

                    ?>
                    <li class="nav-item active"> <a class="nav-link" href="#"> <span class="sr-only">(current)</span><?= $className['Name'] ?></a> </li>
                </ul>
                <form class="form-inline my-2 my-md-0"> </form>
            </div>
        </nav>

        <div id="wrapper" style="margin-top: 40px;" class="">
            <!-- Sidebar -->
            <div id="sidebar-wrapper">

                <ul class="sidebar-nav ">



                    <?php
                    $stmt = $conn->prepare("SELECT * From sections ");
                    $stmt->execute();
                    $sections = $stmt->fetchAll();
                    foreach ($sections as $section) {
                    ?>

                        <li class="catside">

                            <a href="#sub<?= $section['section_id'] ?>" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle sidedroptogle"><?= $section['Name'] ?></a>
                            <ul class="collapse list-unstyled" id="sub<?= $section['section_id'] ?>">
                                <?php $stmt = $conn->prepare("SELECT items.*,items.Name As itemName From items join class on items.Cat_ID=class.ID join sections on items.section=sections.section_id WHERE Cat_ID=? AND section=?");

                                if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) {
                                    $condition = $class;
                                } else {
                                    $condition = $class2['ID'];
                                }
                                $stmt->execute(array($condition, $section['section_id']));
                                $allCats = $stmt->fetchAll();
                                $sum = 0;
                                foreach ($allCats as $c) {
                                    $sum++;
                                ?>
                                    <li class="<?= ($itemid == $c['item_ID']) ? 'selected' : 's' ?>">
                                        <a href="items.php?itemid=<?= $c['item_ID'] ?>&&pageId=<?=$_GET['pageId']?>"> &nbsp;&nbsp;&nbsp;<?= " (" . $sum . ") " . $c['itemName'] ?></a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>


                </ul>

            </div> <!-- /#sidebar-wrapper -->

        <?php } ?>