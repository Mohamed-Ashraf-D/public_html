<?php
function lang($phrase){
    static $lang =array(
        //Navbar links
      'ADMIN_HOME'=>'المنصة الرئيسية',
      'ITEM'=>'الدروس',
      'MEMBERS'=>'ادارة الطلاب',
      'STATISTICS'=>'Statistics',
      'COMMENTS'=>'التعليقات',
      'LOGS'=>'logs',
      'CATEGORIES'=>'الصفوف الدراسية',
      'EDIT PROFILE'=>'Edit profile',
      'SETTINGS'=>'Settings',
      'LOG OUT'=>'Log out',
        'RECEPTION'=>'Reception',
        //Members page words
        'USER NAME'=>'User Name',
        'PASSWORD'=>'Password',
        'EMAIL'=>'Email',
        'FULL NAME'=>'Full Name',

    );
    return $lang[$phrase];
}
?>