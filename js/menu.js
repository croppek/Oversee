$(document).ready(function(){
    
    //#####################################################################
    
    //obsługa ustawień przycisków menu dla wersji mobilnej
    
    var menu_toggled = false;
    
    $('#toggle_menu_btn').on('click', function(){
        
        if(menu_toggled == false)
        {
            menu_toggled = true;
            
            $('#bs-example-navbar-collapse-1').css('text-align', 'center');
            $('#nav_sign_in_btn').removeClass('pull-right').addClass('center-block');
            $('#lang_btns').removeClass('pull-right').removeClass('btn-group-xs').removeClass('lang_btns').addClass('lang_btns_mobile');
        }
        else
        {
            menu_toggled = false;
            
            setTimeout(function(){
                
                $('#bs-example-navbar-collapse-1').css('text-align', 'left');
                $('#nav_sign_in_btn').removeClass('center-block').addClass('pull-right');
                $('#lang_btns').removeClass('lang_btns_mobile').addClass('pull-right').addClass('btn-group-xs').addClass('lang_btns');
                
            }, 250);
        }
        
    });
    
    $(window).resize(function(){
        
        if($('#toggle_menu_btn').css('display') == 'none')
        {
            $('#bs-example-navbar-collapse-1').css('text-align', 'left');
            $('#nav_sign_in_btn').removeClass('center-block').addClass('pull-right');
            $('#lang_btns').removeClass('lang_btns_mobile').addClass('pull-right').addClass('btn-group-xs').addClass('lang_btns');
        }
        else
        {
            $('#bs-example-navbar-collapse-1').css('text-align', 'center');
            $('#nav_sign_in_btn').removeClass('pull-right').addClass('center-block');
            $('#lang_btns').removeClass('pull-right').removeClass('btn-group-xs').removeClass('lang_btns').addClass('lang_btns_mobile');
        }
        
    });
    
    //#####################################################################
    
    //osbługa zmiany języka strony
    
    $('#lang_btn_pl, #lang_btn_en').on('click', function(){
        
        var lang = $(this).data('language');
        
        $.post("php/set_page_language.php", {language: lang}, function(data){
            
            location.reload();
            
        });
        
    });
    
    //#####################################################################
    
});