<?php
ob_start();
session_start();
$pageTitle = 'الصفحة الرئيسية ايمن علام كيمياء | Ayman Allam online ';
include "init.php";
?>


<div class="row container">

</div>
<?php if (!isset($_SESSION['user'])) { ?>
    <div class="container-fluid">
        <div class="row" style="margin-top: 143px;">
            <div class="col-6 p-0 m-0 m-auto">
                <div class="h-50 d-flex justify-content-center align-items-center align-middle  d-block ">
                    <h2 class="h1 mr-5 mainhead">الكيمياء مع ايمن علام</h2>

                </div>
                <div class="h-25 mt-4 d-flex justify-content-center align-items-center align-middle  d-block ">
                    <a href="login.php">
                        <button type="button" class="btn btn-sm btn-dark"> <span class="">تسجيل الدخول / تسجيل جديد</span></button>
                    </a>
                </div>
            </div>
            <div class="col-6 m-0 p-0">
                <img src="layout/img/2357346.png" class="img-fluid m-0 p-0">
            </div>
        </div>
        <div class="row">
            <div class="col-12 m-0 p-0">
                <div class="description ">
                    <h1 class="h1 d-title mb-5 mt-3 mr-5 text-center text-white">عن مستر ايمن علام</h1>
                    <div class="m-auto text-center">
                        <img src="layout/img/WhatsApp Image 2022-08-21 at 1.16.32 AM.jpeg" class="profile-image rounded-circle border border-white border-4">
                        <p class="text-white h3 desc-p">ايمن علام مصرى الجنسية اعيش فى المنصورة خريج كلية التربية قسم الكيمياء والفيزياء جامعة الازهر معلم كيمياء فى وزارة التربية والتعليم المصرية اسعى لتدريب وتعليم الطلاب وتخريج دفعات اكفاء
                           <br> <b>الخبراات</b><br>
                            الخبرة العملية وفهم نظرية وممارسة الكيمياء
                            معرفة وافية لتطوير وتنفيذ وتحليل تقييمات الطلاب
                            قدرة هائلة على إنشاء والحفاظ على معايير أكاديمية عالية
                            قدرة مذهلة على العمل بشكل تعاوني مع الإدارة
                            قدرة هائلة على تثقيف الطلاب حول مختلف جوانب الكيمياء.
                            قدرة كبيرة على تسهيل التعلم بين مجموعة كبيرة من المتعلمين.
                            فهم قوي للمفاهيم الأساسية في الكيمياء.
                            معرفة واسعة في الكيمياء العضوية والكيمياء التحليلية والفصل الكيميائي.</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php
}else{
    echo $_SESSION['classID'];
    header('Location:categories.php?pageId='.$_SESSION['classID'].'&index');
}
?>

<?php
include $tpl . 'footer.php';
ob_end_flush();
?>