<?php

//function to check item is it found or not
function checkItem($select,$from,$value){
    global $conn;
    $statement=$conn->prepare("SELECT $select FROM $from where $select=?");
    $statement->execute(array($value));
    $count=$statement->rowCount();
    return $count;
}

//function to get all rows from database
function getAllFrom($field,$table,$where=NULL,$and=NULL,$orderBy=NULL,$ordering='DESC'){
    global $conn;
    $getAll=$conn->prepare("SELECT $field FROM $table $where $and ORDER BY $orderBy $ordering");
    $getAll->execute();
    $rows=$getAll->fetchAll();
    return $rows;
}



//function to get title if $pageTitle is found in page
function getTitle(){
    global $pageTitle;
    if (isset($pageTitle)){
        echo $pageTitle;
    }else{
        echo 'Default';

    }

}
//check if user is activated
//function to check regStatus of user
function checkUserStatus($user){
    global $conn;
    $stmtx = $conn->prepare('
                    SELECT
                           Username,RegStatus 
                    FROM 
                         users 
                    WHERE 
                          Username=? 
                      AND 
                          RegStatus=0');
    $stmtx->execute(array($user));
    $status = $stmtx->rowCount();
    return $status;
}
//function to redirect to home page if error
function redirectHome($errorMsg,$url,$seconds=3){
    if($url==null){
        $url='index.php';
        $link='Home Page';
    }else{
        if(isset($_SERVER['HTTP_REFERER'])&&$_SERVER['HTTP_REFERER']!=='') {
           $url= $_SERVER['HTTP_REFERER'];
            $link='الصفحة السابقة';
        }else{
            $url='index.php';
            $link='Home Page';

        }

    }
    echo $errorMsg;
    echo "<div class='alert alert-primary'>سيتم اعادة توجيهك الى الصفحة السابقة $link بعد $seconds</div>";
header("refresh:$seconds;url=$url");
exit();
}


//function to count item of database
function countItem($item,$table){
    global $conn;
    $stmt=$conn->prepare("SELECT count($item) FROM $table");
    $stmt->execute();
    $count=$stmt->fetchColumn();
    return $count;
}
//function to get latest 5 users from database
function getLatest($select,$table,$ordered,$limit=5){
    global $conn;
    $getStm=$conn->prepare("SELECT $select FROM $table  ORDER BY $ordered DESC LIMIT $limit ");
    $getStm->execute();
    $rows=$getStm->fetchAll();
    return $rows;
}
//function to get latest 5 users from where
function getLatestu($select,$table,$ordered,$id,$value,$limit=5){
    global $conn;
    $getStm=$conn->prepare("SELECT $select FROM $table WHERE $id !=? ORDER BY $ordered DESC LIMIT $limit ");
    $getStm->execute(array($value));
    $rows=$getStm->fetchAll();
    return $rows;
}
function selectColumn($column,$table,$id,$value)
{
    global $conn;
    $stmt=$conn->prepare("SELECT $column FROM $table WHERE $id =? ");
    $stmt->execute(array($value));
    $column=$stmt->fetchColumn();
    return $column;

}
// Encrypt cookie
function encryptCookie( $value ) {

   $key = hex2bin(openssl_random_pseudo_bytes(4));

   $cipher = "aes-256-cbc";
   $ivlen = openssl_cipher_iv_length($cipher);
   $iv = openssl_random_pseudo_bytes($ivlen);

   $ciphertext = openssl_encrypt($value, $cipher, $key, 0, $iv);

   return( base64_encode($ciphertext . '::' . $iv. '::' .$key) );
}

// Decrypt cookie
function decryptCookie( $ciphertext ) {

   $cipher = "aes-256-cbc";

   list($encrypted_data, $iv,$key) = explode('::', base64_decode($ciphertext));
   return openssl_decrypt($encrypted_data, $cipher, $key, 0, $iv);

}