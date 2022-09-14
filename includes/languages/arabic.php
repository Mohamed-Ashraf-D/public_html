<?php
function lang($phrase){
    static $lang =array(
        'MESSAGE'=>'مرحبا',
        'ADMIN'=>'مدير',
        'Reception'=>'الاستقبال'
    );
    return $lang[$phrase];
}
?>