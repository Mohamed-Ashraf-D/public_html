<?php
                                include('admin/connect.php');
                                session_start();

                                $session=session_id();
                                $time=time();
                                $time_check=$time-1800;
                                $tbl_name="status";
                                
                                $stmtC=$conn->prepare("SELECT * FROM $tbl_name WHERE session='$session'");
                                $stmtC->execute();
                                $count=$stmtC->rowCount();
                                if($count=="0"){
                                $sql1=$conn->prepare("INSERT INTO $tbl_name(session, time)VALUES('$session', '$time')");
                                $sql1->execute();
                                }
                                else {
                                $sql2=$conn->prepare("UPDATE $tbl_name SET time='$time' WHERE session = '$session'");
                                $sql2->execute();
                                }
                                $sql3=$conn->prepare("SELECT * FROM $tbl_name");
                                $sql3->execute();
                                $sql3C=$sql3->rowCount();
                                echo  $sql3C ;
                                $sql4=$conn->prepare("DELETE FROM $tbl_name WHERE time<$time_check");
                                $sql4->execute();
                                
                                ?>