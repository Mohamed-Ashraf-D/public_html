<?php
if (isset($_FILES["uploadingfile"]["tmp_name"])) {//No file chosen
//     echo "ERROR: Please browse for a file before clicking the upload button.";
//     exit();
// } else {
    $extension = pathinfo($_FILES['uploadingfile']['name'], PATHINFO_EXTENSION);//Gets the file extension
    if (
        (($_FILES["uploadingfile"]["type"] == "video/mp4")) && $extension == 'mp4'||
        (($_FILES["uploadingfile"]["type"] == "audio/mp3")) && $extension == 'mp3' ||
        (($_FILES["uploadingfile"]["type"] == "audio/wma")) && $extension == 'wma' ||
        (($_FILES["uploadingfile"]["type"] == "image/pjpeg")) && $extension == 'pjpeg' ||
        (($_FILES["uploadingfile"]["type"] == "image/gif")) && $extension == 'gif' ||
        (($_FILES["uploadingfile"]["type"] == "image/jpeg")) && $extension == 'jpeg' 
        
        ) {//Check if MP4 extension
        $folderPath = "uploads/";//Directory to put file into
        $original_file_name = $_FILES["uploadingfile"]["name"];//File name
        $size_raw = $_FILES["uploadingfile"]["size"];//File size in bytes
        $size_as_mb = number_format(($size_raw / 1048576), 2);//Convert bytes to Megabytes
        if (file_exists("uploads/".$_FILES["uploadingfile"]["name"]))
      {
      echo $_FILES["uploadingfile"]["name"] . " موجود بالفعل. ";
      }else{
        if (move_uploaded_file($_FILES["uploadingfile"]["tmp_name"], "$folderPath" . $_FILES["uploadingfile"]["name"])) {//Move file
            echo "$original_file_name تم تحميله";
            echo "$original_file_name تم تحميله الى $folderPath الحجم $size_as_mb Mb.";
        }else{echo "خطا فى التحميل";}}
    } else {
        echo "الملف ليس بصيغة MP4";
        exit;
    }
}