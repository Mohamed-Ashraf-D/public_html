<?php
ob_start();
session_start();
$pageTitle = 'الصفوف الدراسية';
include "init.php";
if (isset($_SESSION['user'])) {


    $session = session_id();
    $time = time();
    $time_check = $time - 7200;
    $tbl_name = "status";

    $stmtC = $conn->prepare("SELECT * FROM $tbl_name WHERE session='$session'");
    $stmtC->execute();
    $count = $stmtC->rowCount();
    if ($count == "0") {
        $sql1 = $conn->prepare("INSERT INTO $tbl_name(session, time)VALUES('$session', '$time')");
        $sql1->execute();
    } else {
        $sql2 = $conn->prepare("UPDATE $tbl_name SET time='$time' WHERE session = '$session'");
        $sql2->execute();
    }
    $sql3 = $conn->prepare("SELECT * FROM $tbl_name");
    $sql3->execute();
    $sql3C = $sql3->rowCount();
    // echo  $sql3C ;
    $sql4 = $conn->prepare("DELETE FROM $tbl_name WHERE time<$time_check");
    $sql4->execute();
?>
    <div class="container">
        <div class="container">
            <div class="row cards">
                <?php
                if (isset($_GET['pageId']) && is_numeric($_GET['pageId'])) {
                    $category = intval($_GET['pageId']);
                    //                        $getAllItems=getAllFrom("*","items","WHERE Cat_ID={$category} ","AND Approve=1","item_ID","DESC");
                    //                        SELECT item.*, category.* FROM items AS item JOIN categories AS category   ON item.Cat_ID=category.ID WHERE parent={$category} OR Cat_ID={$category} AND Approve=1 ORDER BY item_ID DESC
                    
                    if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']==1){
                        $condition="";
                    }else{
                        $condition="AND ID={$_SESSION['classID']}";
                    }
                    $stmt = $conn->prepare("SELECT items.Name  AS item_name,class.ID AS classID ,sections.Name AS section_name,items.section AS section,items.item_ID  AS item_id ,items.Add_date AS item_datem,items.img AS img FROM items JOIN class ON items.Cat_ID=ID JOIN sections ON sections.section_id=items.section WHERE parent={$category} OR Cat_ID={$category} $condition ORDER BY item_id ASC ");
                    $stmt->execute();
                    $getAllItems = $stmt->fetchAll();
                    foreach ($getAllItems as $item) {
                ?>
                        <div class="col-sm-4 col-md-4">
                            <a style="text-decoration: none;color: inherit" href="items.php?itemid=<?= $item['item_id'] ?>&&pageId=<?=$item['classID']?>">
                                <div class="card card-body m-1" id="1" data-href="product_details.html">
                                    <?php
                                    if (empty($item['img'])) {
                                        echo "<img alt='product-image' width='150' height='300' class='card-img-top' src='admin/layout/img/placeholder-profile-sq.jpg'>";
                                    } else {
                                        echo "<img alt='product-image' width='150' height='300' class='card-img-top' src='admin/layout/img/{$item['img']}'>";
                                    }
                                    ?> <div class="card-body">
                                        <h6 class="card-text item-name">
                                            <?= substr($item['item_name'], 0, 100) ?>
                                        </h6>
                                        <h5 class="desc-price"><?= $item['section_name']?></h5>
                                        <span class="rating">
                                            <span class="fas fa-star checked"></span>
                                            <span class="fas fa-star checked"></span>
                                            <span class="fas fa-star checked"></span>
                                            <span class="fas fa-star"></span>
                                            <span class="fas fa-star"></span>
                                        </span>
                                        <button class="btn btn-primary rounded mx-auto d-block w-100">عرض الدرس</button>
                                    </div>
                                </div>
                            </a>
                        </div>
                <?php }
                } else {
                    echo '<div class="alert alert-danger">you did not specify page id</div>';
                } ?>
            </div>
        </div>


    </div>
<?php }
include $tpl . 'footer.php'; ?>