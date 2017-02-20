$('#rozumiem_cookie_btn').on('click', function(){
    
    $.post("php/cookies.php", {save_cookie: true}, function(data){
        
        if(data == 'success')
        {
            $('#cookies_politics').animate({marginLeft: "-370px"}, function(){
                
                $(this).css('display', 'none');
                
            });
        }
        else
        {
            alert(data);
        }

    });
    
});