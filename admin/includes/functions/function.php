<?php


//function to get all rows from database
function getAllFrom($field,$table,$where=NULL,$and=NULL,$orderBy,$ordering='DESC'){
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
    echo "<div  class='alert alert-primary'>سيتم اعادة توجيهك الى $link بعد <span class='alert-count'></span> ثوانى</div>";
   
header("refresh:$seconds;url=$url");
// exit();
}

//function to check item is it found or not
function checkItem($select,$from,$value){
    global $conn;
    $statement=$conn->prepare("SELECT $select FROM $from where $select=?");
    $statement->execute(array($value));
    $count=$statement->rowCount();
    return $count;
}
//function to count item of database
function countItem($item,$table,$where){
    global $conn;
    $stmt=$conn->prepare("SELECT count($item) FROM $table $where");
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