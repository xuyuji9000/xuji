var temp='';
$.ajax({
    async:  false,
    type:   "POST",
    url:    "/weixin/getimp",
    dataType:   'json',
    data:   {url: window.location.href.split("#")[0]},
    success:    function(data){
        if(data)
            temp = data;
    }
});

wx.config({
    debug:  true,
    appId:  temp.appid,
    timestamp:  temp.timestamp,
    nonceStr:   temp.nonceStr,
    signature:  temp.signature,
    jsApiList:  ['openLocation', 'getLocation']
});

wx.ready(function(){
})

wx.error(function(res){
    var retval = '';
    for(p in res) {
        document.write("[\""+p+"\"]="+res[p]+"\n");
    }
})
