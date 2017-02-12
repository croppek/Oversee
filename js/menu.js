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
    
    //obsługa zmiany języka strony
    
    $('#lang_btn_pl, #lang_btn_en').on('click', function(){
        
        var lang = $(this).data('language');
        
        $.post("php/set_page_language.php", {language: lang}, function(data){
            
            location.reload();
            
        });
        
    });
    
    //#####################################################################
    
    //obsługa dodawania informacji o przedmiocie do bazy danych
    
    var category;
    
    $('#add_todb_category').on('click', function(){
        
        $(this).attr("disabled", "disabled");
        
        category = $('#add_todb_categories_select').val();
        
        if(category != null)
        {    
            $('#modal_addtodb_content').css('display', 'none');
                
            $('#addtodb_modal_dialog').animate({width: '80%'});
            $('#modal_addtodb_content').animate({marginBottom: '320px'}, 500, function(){
                
                $('#modal_addtodb_content').fadeIn(500);
                
            });

            switch(category) 
            {
                case 'devices':

                    $.post("php/kategorie/devices.php", {give_headlines: true}, function(data){
                        
                        setTimeout(function(){
                            
                            $('#modal_addtodb_content').empty().append(data);
                            
                            $('#add_item_to_db_form').submit(function() {
                                return false;
                            });
                            
                        }, 50);

                    });

                    break;
            }
        }
        
        setTimeout(function(){$("#add_todb_category").removeAttr("disabled")}, 1500);
        
    });
    
    $('#modal_addtodb_content').on('click', '#add_item_to_db', function(){
        
        setTimeout(function(){
            $('#add_item_to_db').attr("disabled", "disabled");
        }, 25);
        
        switch(category)
        {
            case 'devices':
                
                var id = $('#item_id_holder').text();
                var name = $('#adddb_name_input').val();
                var placement = $('#adddb_placement_input').val();
                var type = $('#adddb_type_input').val();
                var damaged = $('input[name=damaged]:checked').val();
                var comment = $('#adddb_comment_input').val();
                
                break;
        }
        
        if(id != '' && name != '' && type != '' && damaged != '')
        {
            $.post("php/kategorie/devices.php", {id: id, name: name, placement: placement, type: type, damaged: damaged, comment: comment}, function(data){

                if(data == 'success')
                {
                    location.reload();
                }
                else
                {
                    alert(data);
                }

            });
        }
        
        setTimeout(function(){$("#add_item_to_db").removeAttr("disabled")}, 1500);
        
    });
    
});