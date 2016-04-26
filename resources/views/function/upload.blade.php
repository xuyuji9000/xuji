<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
    <form id="imageUploadForm" action="/function/uploadImg" method="post" enctype="multipart/form-data" name="upload_form">
 <label>选择图片文件</label>
  <input name="imgfile" type="file" accept="image/*"/>
  <input name="upload" type="submit" value="上传" />
    </form> 
    <img src="/img/test.jpg?w=105&filt=sepia" alt="">
</body>
<script src="/js/jquery.js" type="text/javascript"></script>
<script  type="text/javascript">
	$(document).ready(function (e) {
	    $('#imageUploadForm').on('submit',(function(e) {
	        e.preventDefault();
	        var formData = new FormData(this);

	        $.ajax({
	            type:'POST',
	            url: $(this).attr('action'),
	            data:formData,
	            cache:false,
	            contentType: false,
	            processData: false,
	            success:function(data){
	                console.log("success");
	                console.log(data);
	            },
	            error: function(data){
	                console.log("error");
	                console.log(data);
	            }
	        });
	    }));
	});
</script>
</html>
