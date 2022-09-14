<?php
include("connect.php");
include('includes/functions/function.php');
$input = $_POST['input'];
$stmt = "SELECT * FROM users WHERE mobile LIKE '%${input}%' and  avatar!=1";
$getAll = $conn->prepare($stmt);
$getAll->execute();
$rows = $getAll->fetchAll();

foreach ($rows as $row) {
?>
    <tr>
        <td class="student-id"></td>
        <td><?php 
        if (empty($row['avatar'])) {
            echo "<img class='rounded rounded-circle' style='width: 50px;height: 50px' src='../layout/img/placeholder-profile-sq.jpg'>";
        } else {
            echo "<img class='rounded rounded-circle' style='width: 50px;height: 50px' src='uploads/avatars/" . $row['avatar'] . "'>";
        }
        ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['mobile'] ?></td>

        <td><?= $row['fatherMobile'] ?></td>
        <?php
        $gov = selectColumn("governName", "governorate", "govID", $row['governate']);
        ?>
        <td><?= $gov ?></td>
        <?php
        $class = selectColumn("Name", "class", "ID", $row['class']);
        ?>
        <td><?= $class ?></td>
        <td><?= $row['Date'] ?></td>
        <td>

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
                                ?>
    </tr>
<?php

}
?>
?>