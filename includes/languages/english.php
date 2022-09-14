<?php
function lang($phrase){
    static $lang =array(
        //Navbar links
      'ADMIN_HOME'=>'Home',
      'ITEM'=>'Item',
      'MEMBERS'=>'Members',
      'STATISTICS'=>'Statistics',
      'COMMENTS'=>'Comments',
      'LOGS'=>'logs',
      'CATEGORIES'=>'Categories',
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