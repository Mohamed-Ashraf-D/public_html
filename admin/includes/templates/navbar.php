<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="index.php">الكيمياء مع ايمن علام</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="../index.php"><?= lang('ADMIN_HOME') ?> <span class="sr-only">(current)</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="items.php"><?= lang('ITEM') ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="members.php"><?= lang('MEMBERS') ?></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="comments.php"><?= lang('COMMENTS') ?></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="categories.php">الصفوف الدراسية</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="sections.php">الفصول الدراسية</a>
      </li>

      <li class="nav-item dropdown ">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?= $_SESSION['name']; ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="members.php?do=Edit&userid=<?= $_SESSION['ID'] ?>">تعديل بيانات المدير</a>
          <a class="dropdown-item" href="../index.php">زيارة المنصة</a>
          <a class="dropdown-item" href="newad.php?do=Add">اضافة درس جديد</a>
          <a class="dropdown-item" href="logout.php">تسجيل الخروج</a>
        </div>
      </li>
      
    </ul>

  </div>
</nav>
