<?php

/*
 * Upload function checks for the valid image file and uploads to directory
 *
 */
function uploadImage($files)
{
    $target_dir = "/home/kjs5335/Sites/756/ecommerceproject/assests/image/";
    $target_file = $target_dir . basename($files["fileToUpload"]["name"]);

    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image

        $check = getimagesize($files["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }



// Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
// Check file size
    if ($files["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
// Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($files["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $files["fileToUpload"]["name"]). " has been uploaded.";

        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

}
?>
