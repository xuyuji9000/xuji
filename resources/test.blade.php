<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data" name="upload_form">
 <label>选择图片文件</label>
  <input name="imgfile" type="file" accept="image/*"/>
  <input name="upload" type="submit" value="上传" />
    </form> 
<?php
if (isset($_FILES['imgfile']) && is_uploaded_file($_FILES['imgfile']['tmp_name']))
{
    $imgFile = $_FILES['imgfile'];
    $imgFileName = $imgFile['name'];
    $imgType = $imgFile['type'];
    $imgSize = $imgFile['size'];
    $imgTmpFile = $imgFile['tmp_name'];
    move_uploaded_file($imgTmpFile, 'upfile/'.$imgFileName);
    $validType = false;
    $upRes = $imgFile['error'];
    if ($upRes == 0)
    {
        if ($imgType == 'image/jpeg'
            || $imgType == 'image/png'
            || $imgType == 'image/gif')
        {
            $validType = true;
        }
        if($validType){
            $strPrompt = sprintf("文件%s上传成功<br>"
            . "文件大小: %s字节<br>"
                . "<img src='upfile/%s'>"
                , $imgFileName, $imgSize, $imgFileName
            );
            echo $strPrompt;
        }
    }
}
?>
</body>
</html>
