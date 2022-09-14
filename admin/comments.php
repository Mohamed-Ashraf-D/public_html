<?php
session_start();
$pageTitle = 'Comments';
if (isset($_SESSION['mobileAdmin'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

// start Manage comments page

    if ($do == 'Manage') {

        $stmt = $conn->prepare("SELECT comments.*,items.Name AS itemName,users.Name AS userName FROM comments INNER JOIN items ON comments.item_id=items.item_ID INNER JOIN users ON comments.user_id=users.UserID ORDER BY c_id DESC");
        $stmt->execute();
        $comments = $stmt->fetchAll();
        if(!empty($comments)){
        ?>

        <div class="container">
            <h1 class="text-center">ادارة التعليقات</h1>
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered table-striped table-sm">
                    <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Comment</th>
                        <th>Item Name</th>
                        <th>User Name</th>
                        <th>Added Date</th>
                        <th>Control</th>
                    </tr>
                    </thead>
                    <tbody class="default-number">
                    <?php
                    foreach ($comments as $comment) {
                        echo "<tr>";
                        echo "<td>" . $comment['c_id'] . "</td>";
                        echo "<td>" . $comment['comment'] . "</td>";
                        echo "<td>" . $comment['itemName'] . "</td>";
                        echo "<td>" . $comment['userName'] . "</td>";
                        echo "<td>" . $comment['comment_date'] . "</td>";
                        echo "<td>
                            <a href='comments.php?do=Edit&commId=" . $comment['c_id'] . "' class=\"btn btn-primary btn-sm\"><i class='fa fa-edit'></i> Edit</a>
                            <a href='comments.php?do=Delete&commId=" . $comment['c_id'] . "' class=\"btn btn-danger btn-sm confirm\"><i class='fa fa-times'></i> Delete</a>";
                        if ($comment['status']==0){
                            echo " <a href='comments.php?do=Approve&commId=".$comment['c_id']."' class=\"btn btn-info btn-sm activate\"><i class='fa fa-check'></i> Approve</a>";
                        }
                        echo "</td>";

                        echo "</tr>";
                    }
                    ?>

                    </tbody>

                </table>
            </div>
        </div>
    <?php }else{
            echo "<div class='container'>";
            echo "<div class='alert alert-info'>There's no comments to show</div>";
            echo "</div>";
        }
    }
     elseif ($do == 'Edit') { //Edit page
        // check if user id is get from get request and is numeric
        $commId = isset($_GET['commId']) && is_numeric($_GET['commId']) ? intval($_GET['commId']) : 0;
        //select all data from users table according to user id
        $stmt = $conn->prepare('SELECT * FROM comments WHERE c_id=?');
        // Execute query
        $stmt->execute(array($commId));
        //fetch data and store in array
        $rows = $stmt->fetch();
        //check if row is found in database
        $count = $stmt->rowCount();
        //show form if al its ok
        if ($count > 0) {
            ?>
            <h1 class="text-center">تعديل التعليق</h1>
            <div class="container">
                <form action="?do=Update" method="POST">
                    <input type="hidden" name="commId" value="<?= $commId ?>">
                    <div class="form-group row"><!-- start comment field-->
                        <label for="" class="col-sm-1 p-0 col-form-label">Comment</label>
                        <div class="col-sm-4">
                           <textarea class="form-control" name="comment"><?=$rows['comment']?></textarea>
                        </div>
                    </div> <!--end comment field-->

                    <div class="form-group row"><!--submit button field-->
                        <div class="offset-1 col-sm-4">
                            <input type="submit" id="" value="Save" class="form-control btn-primary">
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
    } elseif ($do == 'Update') { //update page


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Update Comment</h1>";
            echo "<div class='container'>";
            //get variables from the form
            $commId = $_POST['commId'];
            $comment = $_POST['comment'];

                //update the data in the data base
                $stmt2 = $conn->prepare('UPDATE comments SET comment=? WHERE c_id=?');
                $stmt2->execute(array($comment, $commId));
                //echo success message
                $theMsg = "<div class='alert alert-success'>" . $stmt2->rowCount() . " records update successfully</div>";
                redirectHome($theMsg, 'back', 5);


        } else {
            $errorMsg = "you can't browse this page directly";
            redirectHome($errorMsg, 'back', 6);
        }
        echo "</div>";
    } elseif ($do == 'Delete') {
        //Delete user from database
        echo "<h1 class='text-center'>حذف التعليق</h1>";
        echo "<div class='container'>";
        // check if user id is get from get request and is numeric and store its value in userid
        $commId = isset($_GET['commId']) && is_numeric($_GET['commId']) ? intval($_GET['commId']) : 0;
        //select all data from users table according to user id
        // Execute query
        $check = checkItem('c_id', 'comments', $commId);

        //check if row is found in database
        //show form if al its ok
        if ($check > 0) {
            $stmt = $conn->prepare('DELETE FROM comments where c_id=:commId');
            $stmt->bindParam('commId', $commId);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " records deleted successfully</div>";
            redirectHome($theMsg, 'back', 5);

        } else {

            $theMsg = "<div class='alert alert-danger'>this user not exist</div>";
            redirectHome($theMsg, 'back', 5);

        }
        echo "</div>";
    }elseif ($do=='Approve'){
        echo "<h1 class='text-center'>Approve Comment</h1>";
        echo "<div class='container'>";
        // check if comm id is get from get request and is numeric and store its value in commId
        $commId = isset($_GET['commId']) && is_numeric($_GET['commId']) ? intval($_GET['commId']) : 0;
        //select all data from users table according to user id
        // Execute query
        $check = checkItem('c_id', 'comments', $commId);

        //check if row is found in database
        //show form if al its ok
        if ($check > 0) {
            $stmt = $conn->prepare('UPDATE comments SET status=1 where c_id=?');
            $stmt->execute(array($commId));
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " records Updated successfully</div>";
            redirectHome($theMsg, 'back', 5);

        } else {

            $theMsg = "<div class='alert alert-danger'>this comment not exist</div>";
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
    
</script>