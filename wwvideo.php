<?php
include('admin/connect.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['student'])) {
        $student = $_POST['student'];
        $video = $_POST['video'];
        // stmt to check if already user watch this video or not
        $stmtcheck = $conn->prepare("SELECT * FROM videomembership WHERE Users=? AND Video=?");
        $stmtcheck->execute(array($student, $video));
        $count = $stmtcheck->rowCount();
        if ($count > 0) {
            echo "تم تسجيل المشاهدة اعد تحميل الصفحة حتى لاتظهر الرسالة مرة اخرى";
            // echo $count;
        } else {
            $stmt = $conn->prepare("INSERT INTO videomembership(Users,Video) VALUES (?,?)");
            $stmt->execute(array($student, $video));
            if ($stmt) {
                echo "تم تسجيل انك شاهدت الفيديو شكرا للمشاهدة";
            }
        }
    }
}
