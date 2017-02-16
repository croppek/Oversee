$(document).ready(function(){

    //#####################################################################
    
    //obsługa zdarzenia kliknięcia przyciusku "Zaloguj się"
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
                    
                    $('#password_input').val('');
                }
                else if(data == 'brakuzytkownika')
                {
                    set_error('blad27');
                }
                else if(data == 'potwierdzmail')
                {   
                    $.post("php/errors.php", {confirmation_content: true}, function(data){
                        
                        if(data == 'bladpolaczeniazbaza')
                        {
                            set_error('blad6');
                        }
                        else
                        {
                            $('#error_alert').fadeOut(500);

                            $('#modal_login_content').fadeOut(500, function(){
                                
                                $('#password_input').val('');
                                
                                $('#login_content').css('display', 'none');
                                
                                $('#email_confirm_content').empty().append(data).css('display', 'block');
                                
                                $('#confirm_email_form').submit(function(){
                                    return false;
                                });
                                
                                $('#modal_login_content').fadeIn(500);

                            });
                        }
                    });
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
    
    //#####################################################################
    
    //obsługa zdarzenia kliknięcia przyciusku "Wyloguj się"
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
    
    //#####################################################################
    
    //obsługa zdarzenia kliknięcia przyciusku potwierdzającego adres email
    $('#modal_login_content').on('click', '#email_confirm_btn', function(){
        
        $('#email_confirm_btn').attr("disabled", "disabled");
        
        var code = $('#confirm_code_input').val();
        var new_email = $('#new_email_holder').text();
        
        if(code == '')
        {
            set_error('blad28');
        }
        else
        {
            $.post("php/login.php", {confirmation_code: code, new_email: new_email}, function(data){

                if(data == 'confirmed')
                {
                    $('#error_alert').fadeOut(500);
                    
                    $('#modal_login_content').fadeOut(500, function(){

                        $('#email_confirm_content').empty().append('<h3 style="text-align: center;">Aktywacja zakończona powodzeniem, proszę się zalogowac.<br/><br/><small>Activation completed successfully, please log in.</small></h3>');
                               
                        $('#modal_login_content').fadeIn(500);

                    });
                    
                    
                    setTimeout(function(){
                        
                        $('#modal_login_content').fadeOut(500, function(){

                            $('#email_confirm_content').empty().css('display', 'none');

                            $('#login_content').css('display', 'block');
                               
                            $('#modal_login_content').fadeIn(500);

                        });
                        
                    }, 5000);
                }
                else if(data == 'fail')
                {
                    set_error('blad23');
                }

            });
        }
        
        setTimeout(function(){$("#email_confirm_btn").removeAttr("disabled")}, 1500);
        
    });
    
    //#####################################################################
    
    //obsługa zdarzenia kliknięcia przyciusku powrotu do logowania
    $('#modal_login_content').on('click', '#back_to_login_btn', function(){
        
        $('#back_to_login_btn').attr("disabled", "disabled");
        
        $('#error_alert').fadeOut(500);

        $('#modal_login_content').fadeOut(500, function(){

            $('#email_confirm_content').empty().css('display', 'none');
            
            $('#login_content').css('display', 'block');

            $('#modal_login_content').fadeIn(500);

        });
        
        setTimeout(function(){$("#back_to_login_btn").removeAttr("disabled")}, 1500);
        
    });
    
    //#####################################################################
    
    //wyświetlenie informacji przy potwierdzaniu emaila
    var email_help_clicked = false;
    
    $('#modal_login_content').on('click', '#email_help_btn', function(){
        
        if(email_help_clicked == false)
        {
            email_help_clicked = true;
            
            $('#info_alert').fadeIn(500);
        }
        else
        {
            email_help_clicked = false;
            
            $('#info_alert').fadeOut(500);
        }
        
    });
    
    //#####################################################################
    
    //obsługa przycisku przekazująca podany adres email
    $('#modal_login_content').on('click', '#email_enter_btn', function(){
        
        var new_email = $('#new_email_input').val();
        
        if(new_email != '')
        {
            if(validateEmail() == true)
            {
                $.post("php/errors.php", {confirmation_content2: true, new_email: new_email}, function(data){
                        
                    if(data == 'bladpolaczeniazbaza')
                    {
                        set_error('blad6');
                    }
                    else
                    {
                        $('#error_alert').fadeOut(500);

                        $('#modal_login_content').fadeOut(500, function(){

                            $('#password_input').val('');

                            $('#login_content').css('display', 'none');

                            $('#email_confirm_content').empty().append(data).css('display', 'block');

                            $('#modal_login_content').fadeIn(500);

                        });
                    }
                });   
            } 
        }
        
    });
    
});

//#####################################################################

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

//#####################################################################

//funkcja sprawdzająca poprawność email'a
function validateEmail()
{
    var x = $('#new_email_input').val();
    var atpos = x.indexOf("@");
    var dotpos = x.lastIndexOf(".");
    
    if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) 
    {   
        if($('#error_alert').css('display') == 'block')
        {
            $('#error_alert').fadeOut(500, function(){
                
                $('#new_email_input').addClass("input_error");
                set_error('blad13');

                $('#error_alert').fadeIn(500);

            });
        }
        else
        {
            $('#new_email_input').addClass("input_error");
            set_error('blad13');

            $('#error_alert').fadeIn(500);
        }
        
        return false;
    }
    else
    {
        $('#error_alert').fadeOut(500);
        
        $('#new_email_input').removeClass("input_error");
        
        return true;
    }
}
    