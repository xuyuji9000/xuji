<!DOCTYPE html>
<html lang="en">
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
    body, html,#allmap {width: 100%;height: 100%;overflow:hidden;margin:0;font-family:"微软雅黑";}
    </style> 
    
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=D79e1b6e5c0e87abbf6589bbabdb80c8"></script>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<title>徐记激光焊接导航</title>
</head>
<body>
<div id="allmap"></div>
</body>
</html>
<script type="text/javascript">
var Latitude = {{ $Latitude }};
var Longitude = {{ $Longitude }};
$(document).ready(function(){
// 百度地图API功能
var start = new BMap.Point(Longitude, Latitude);
var end = "钟塔路";
var map = new BMap.Map("allmap");
map.centerAndZoom(new BMap.Point(120.61031,31.313698), 11);

var driving = new BMap.DrivingRoute(map, {renderOptions:{map: map,autoViewport: true}});
driving.search(start, end);
});
</script>
