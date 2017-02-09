$(document).ready(function(){

    $('#sign_in_btn').on('click',function(){
        
        $(this).attr("disabled", "disabled");
        
        var login = $('#login_input').val();
        var password = $('#password_input').val();
        
        if(login != '' && password != '')
        {
            $.post("php/login.php", {login: login, haslo: password}, function(data){
                            
                if(data == 'success')
                {
                    location.reload();
                }
                else if(data == 'bladpolaczeniazbaza')
                {
                    set_error('blad6');
                }
                else if(data == 'nieprawidlowehaslo')
                {
                    set_error('blad27');
                }
                else if(data == 'brakuzytkownika')
                {
                    set_error('blad27');
                }
                else if(data == 'potwierdzmail')
                {
                    alert('Potwierdź email!');
                }
                
                setTimeout(function(){$("#sign_in_btn").removeAttr("disabled")}, 1500);
                
            });
        }
        else
        {
            set_error('blad26');
            
            setTimeout(function(){$("#sign_in_btn").removeAttr("disabled")}, 1500);
        }
        
    });
    
    $('#log_out_btn').on('click',function(){
        
        $(this).attr("disabled", "disabled");
        
        $.post("php/logout.php", {logout: true}, function(data){
                            
            if(data == 'success')
            {
                location.reload();
            }

            setTimeout(function(){$("#log_out_btn").removeAttr("disabled")}, 1500);
                
        });
        
    });
    
});

//funkcja uzupełniająca treść alertów z błędami
function set_error(error_number)
{   
    if($('#error_alert').css('display') == 'block')
    {
        $('#error_alert').fadeOut(300, function(){

            $.post("php/errors.php", {error_number: error_number}, function(data){
        
                $('#error_alert').empty().append(data).fadeIn(500);
                
            });

        });
    }
    else
    {
        $.post("php/errors.php", {error_number: error_number}, function(data){
        
            $('#error_alert').empty().append(data).fadeIn(500);

        });
        
    }
}
    