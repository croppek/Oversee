$(document).ready(function(){
    
    var menu_toggled = false;
    
    $('#toggle_menu_btn').on('click', function(){
        
        if(menu_toggled == false)
        {
            menu_toggled = true;
            
            $('#bs-example-navbar-collapse-1').css('text-align', 'center');
            $('#sign_in_btn').removeClass('pull-right').addClass('center-block');
        }
        else
        {
            menu_toggled = false;
            
            setTimeout(function(){
                
                $('#bs-example-navbar-collapse-1').css('text-align', 'left');
                $('#sign_in_btn').removeClass('center-block').addClass('pull-right');
                
            }, 250);
        }
        
    });
    
    $(window).resize(function(){
        
        if($('#toggle_menu_btn').css('display') == 'none')
        {
            $('#bs-example-navbar-collapse-1').css('text-align', 'left');
            $('#sign_in_btn').removeClass('center-block').addClass('pull-right');
        }
        else
        {
            $('#bs-example-navbar-collapse-1').css('text-align', 'center');
            $('#sign_in_btn').removeClass('pull-right').addClass('center-block');
        }
        
    });
    
});