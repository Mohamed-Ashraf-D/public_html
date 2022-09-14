<?php
session_start();
$pageTitle = 'Members';
if (isset($_SESSION['mobile'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

// start Manage page
if($do=='Manage'){
    echo "Welcome to Manage page";
}
elseif ($do=='Add'){
    ?>



<?php
}
elseif ($do=='Edit'){

}
elseif ($do=='Update'){

}
elseif ($do=='insert'){

}
elseif ($do=='Delete'){

}



    include $tpl . 'footer.php';
} else {
    header('Location:index.php');
    exit();
}