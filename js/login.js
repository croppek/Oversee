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
    
    //obsługa przycisku zmieniająca hasło
    $('#change_password_button').on('click', function(){
        
        var old_password = $('#old_password_input').val();
        var password = $('#password_input').val();
        var password2 = $('#password2_input').val();
        
        if(old_password != '')
        {
            validation_OK = true;
            
            if(password == '')
            {
                $('#password_input').addClass("input_error");
                
                validation_OK = false;
                
                error_number = 'blad10';
            }
            else
            {
                if(checkPassword() == 'ok')
                {
                    $('#password_input').removeClass("input_error");
                    $('#password_input').addClass("password_ok");
                }
                else
                {
                    validation_OK = false;
                    error_number = 'blad12';
                    
                    $('#password_input').removeClass("password_ok");
                    $('#password_input').addClass("input_error"); 
                }
            }
            
            if(password2 == '')
            {
                $('#password2_input').addClass("input_error");
                
                validation_OK = false;
                
                error_number = 'blad10';
            }
            else
            {
                if(checkPassword() == 'ok')
                {
                    $('#password2_input').removeClass("input_error");
                    $('#password2_input').addClass("password_ok");
                }
                else
                {
                    validation_OK = false;
                    error_number = 'blad12';
                    
                    $('#password2_input').removeClass("password_ok");
                    $('#password2_input').addClass("input_error"); 
                }
            }
            
            if(validation_OK == true)
            {
                $.post("php/login.php", {old_password: old_password, new_password: password}, function(data){

                    if(data == 'bladpolaczeniazbaza')
                    {
                        set_error('blad6');
                    }
                    else if(data == 'nieprawidlowehaslo')
                    {
                        set_error('blad29');
                    }
                    else if(data == 'success')
                    {
                        $('#error_alert').fadeOut(500);
                        
                        $('#change_password_inputs').fadeOut(500,function(){
                           
                            $.post("php/account_settings.php", {get_content: 'conf_code'}, function(data){
                                
                                $('#change_password_inputs').empty().append(data).fadeIn(500);
                                $('#change_password_button').fadeOut(250, function(){
                                    
                                   $('#confirm_new_password_button').fadeIn(250);
                                    
                                });
                                
                            });
                            
                        });
                    }
                });      
            }
            else
            {
                set_error(error_number);
            }
        }
        else
        {
            set_error('blad10');
        }
        
    });
    
     //obsługa przycisku zmieniająca hasło
    $('#confirm_new_password_button').on('click', function(){
        
        var code = $('#confirm_code_input').val();
        
        if(code == '')
        {
            set_error('blad28');
        }
        else
        {
            $.post("php/login.php", {conf_code: code}, function(data){
                                
                if(data == 'bladpolaczeniazbaza')
                {
                    set_error('blad6');
                }
                else if(data == 'nieprawidlowykod')
                {
                    set_error('blad23');
                }
                else if(data == 'success')
                {
                    $('#error_alert').fadeOut(500);
                    
                    $('#confirm_new_password_button').fadeOut(500);
                    $('#change_password_inputs').fadeOut(500,function(){
                        
                        $.post("php/account_settings.php", {get_content: 'thanks'}, function(data){
                                
                            $('#change_password_inputs').empty().append(data).fadeIn(500);

                        });
                        
                    });
                }

            });
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

//#####################################################################

//funkcja sprawdzająca poprawność podanych haseł
conf_pass = false;
conf_pass2 = false;

function checkPassword()
{
    var error_number;
    
    if($('#password_input').val().length < 5 || $('#password_input').val().length > 30)
    {
        conf_pass2 = false;
        
        $('#password_input, #password2_input').removeClass("password_ok");
        $('#password_input, #password2_input').addClass("input_error");
        
        error_number = 'blad11';
        
        if($('#error_alert').css('display') == 'block')
        {
            $('#error_alert').fadeOut(500, function(){
                
                set_error(error_number);
                
                $('#error_alert').fadeIn(500);
                
            });
        }
        else
        {
            set_error(error_number);
                
            $('#error_alert').fadeIn(500);
        }
    }
    else if($('#password_input').val().length >= 5 && $('#password_input').val().length <= 30)
    {
        conf_pass2 = true;
        
        if($('#password2_input').val() != "" && $('#password_input').val() != "")
        {
            var haslo1 = $('#password_input').val();
            var haslo2 = $('#password2_input').val();

            if(haslo1 == haslo2)
            {
                conf_pass = true;
                
                if(conf_pass == true)
                {
                    $('#password_input, #password2_input').removeClass("input_error");
                    $('#password_input, #password2_input').addClass("password_ok");
                    
                    $('#error_alert').fadeOut(500);
                    return 'ok';
                }
            }
            else
            {
                $('#password_input, #password2_input').removeClass("password_ok");
                $('#password_input, #password2_input').addClass("input_error");
                
                error_number = 'blad12';
                
                if($('#error_alert').css('display') == 'block')
                {
                    $('#error_alert').fadeOut(500, function(){
                        
                        set_error(error_number);

                        $('#error_alert').fadeIn(500);

                    });
                }
                else
                {
                    set_error(error_number);

                    $('#error_alert').fadeIn(500);
                }
                
                conf_pass = false;
                
                return 'fail';
            }
        }
    }
}
    