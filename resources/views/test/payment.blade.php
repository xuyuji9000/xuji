<!DOCTYPE html>
<html>
<head>
	<title>payment</title>
	<script type="text/javascript" src="js/jweixin.js"></script>
	<script type="text/javascript" src="js/wx.js"></script>
</head>
<script type="text/javascript">
wx.checkJsApi({
    jsApiList: ['chooseImage'], // 需要检测的JS接口列表，所有JS接口列表见附录2,
    success: function(res) {
        // 以键值对的形式返回，可用的api值true，不可用为false
        // 如：{"checkResult":{"chooseImage":true},"errMsg":"checkJsApi:ok"}
    }
});
</script>
<body>

</body>
</html>