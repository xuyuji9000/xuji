var temp;
$.ajax({
    async:  true,
    type:   "POST",
    url:    "/weixin/getimp",
    dataType:   'json',
    data:   {url: window.location.href},
    success:    function(data){
        temp = data;
    }
});

alert(temp);
