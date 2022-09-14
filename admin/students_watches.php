<?php
session_start();
$pageTitle = 'الحضور';
if (isset($_SESSION['mobileAdmin'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'View';

    // start Manage page
    if ($do == 'View') {
        //select all from items except admin
        $lessonid = isset($_GET['addId']) && is_numeric($_GET['addId']) ? intval($_GET['addId']) : 0;



?>

        <div class="container">
            <h1 class="text-center">الحضور</h1>

            <div class="table-responsive">
                <?php
                $stmt = $conn->prepare("SELECT Name FROM items WHERE item_ID=$lessonid");
                $stmt->execute();
                $lesson = $stmt->fetchColumn();



                ?>
                <h4 id="studennums"> <?= $lesson ?> </h4>
                <table class="main-table text-center table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>رقم</th>
                            <th>اسم الطالب</th>
                            <th>رقم الموبايل</th>

                        </tr>
                    </thead>
                    <tbody class="default-number">
                        
                        <?php
                        $stmt2 = $conn->prepare("SELECT users.name AS student ,users.mobile AS mobile FROM users JOIN videomembership ON users.UserID=videomembership.Users WHERE Video=$lessonid ");
                        $stmt2->execute();
                        $students = $stmt2->fetchAll();

                        if (!empty($students)) {
                            foreach ($students as $student) {
                                echo "<tr>";
                                echo "<td></td>";
                        ?>
                                
                                    
                                    <td><?= $student['student'] ?></td>
                                    <td><?= $student['mobile'] ?></td>
                                
                        <?php 
                          echo "</tr>";    
                    }
                        } ?>
                    </tbody>

                </table>

            </div>
        </div>
        <br><br><br><br><br>
<?php } else {
        echo "<div class='container'>";
        echo "<div class='alert alert-info'>there's is no items</div>";

        echo "</div>";
    }


    include $tpl . 'footer.php';
} else {
    header('Location:index.php');
    exit();
}
?>
<script>
    
</script>