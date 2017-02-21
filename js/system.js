$(document).ready(function(){
    
    /*wyświetlanie aktualnego adresu URL
        console.log(window.location.protocol + "//" + window.location.host + "" + window.location.pathname);
    */
    
    //#####################################################################
    
    //obsługa ustawień przycisków menu dla wersji mobilnej
    
    var menu_toggled = false;
    
    $('#toggle_menu_btn').on('click', function(){
        
        if(menu_toggled == false)
        {
            menu_toggled = true;
            
            $('#bs-example-navbar-collapse-1').css('text-align', 'center');
            $('#nav_sign_in_btn').removeClass('pull-right').addClass('center-block');
            $('#log_out_btn').removeClass('pull-right').addClass('center-block');
            $('#lang_btns').removeClass('pull-right').removeClass('btn-group-xs').removeClass('lang_btns').addClass('lang_btns_mobile');
        }
        else
        {
            menu_toggled = false;
            
            setTimeout(function(){
                
                $('#bs-example-navbar-collapse-1').css('text-align', 'left');
                $('#nav_sign_in_btn').removeClass('center-block').addClass('pull-right');
                $('#log_out_btn').removeClass('center-block').addClass('pull-right');
                $('#lang_btns').removeClass('lang_btns_mobile').addClass('pull-right').addClass('btn-group-xs').addClass('lang_btns');
                
            }, 250);
        }
        
    });
    
    $(window).resize(function(){
        
        if($('#toggle_menu_btn').css('display') == 'none')
        {
            $('#bs-example-navbar-collapse-1').css('text-align', 'left');
            $('#nav_sign_in_btn').removeClass('center-block').addClass('pull-right');
            $('#log_out_btn').removeClass('center-block').addClass('pull-right');
            $('#lang_btns').removeClass('lang_btns_mobile').addClass('pull-right').addClass('btn-group-xs').addClass('lang_btns');
            $('#footer_content1, #footer_content2').css('float', 'left');
        }
        else
        {
            $('#bs-example-navbar-collapse-1').css('text-align', 'center');
            $('#nav_sign_in_btn').removeClass('pull-right').addClass('center-block');
            $('#log_out_btn').removeClass('pull-right').addClass('center-block');
            $('#lang_btns').removeClass('pull-right').removeClass('btn-group-xs').removeClass('lang_btns').addClass('lang_btns_mobile');
            $('#footer_content1, #footer_content2').css('float', 'none');
        }
        
    });
    
    //ustawienia stopki
    if($('#toggle_menu_btn').css('display') == 'block')
    {
        $('#footer_content1, #footer_content2').css('float', 'none');
    }
    
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
            
            if($('#toggle_menu_btn').css('display') == 'block')
            {
                $('#addtodb_modal_dialog').animate({width: '95%'});
                $('#modal_addtodb_content').animate({marginBottom: '50px'}, 500, function(){

                    $('#modal_addtodb_content').fadeIn(500);

                });
            }
            else
            {
                $('#addtodb_modal_dialog').animate({width: '80%'});
                $('#modal_addtodb_content').animate({marginBottom: '320px'}, 500, function(){

                    $('#modal_addtodb_content').fadeIn(500);

                });   
            }

            $.post("php/kategorie/"+category+".php", {give_headlines: true}, function(data){

                setTimeout(function(){

                    $('#modal_addtodb_content').empty().append(data);

                    $('#add_item_to_db_form').submit(function(){
                        return false;
                    });

                }, 50);
            });

        }
        
        setTimeout(function(){$("#add_todb_category").removeAttr("disabled")}, 1500);
        
    });
    
    $('#modal_addtodb_content').on('click', '#add_item_to_db', function(){
        
        setTimeout(function(){
            $('#add_item_to_db').attr("disabled", "disabled");
        }, 25);
        
        switch(category)
        {
            //#################################################### TUTAJ DODAWAĆ NOWE KATEGORIE ########################################################
            case 'devices':
                
                var id = $('#item_id_holder').text();
                var name = $('#adddb_name_input').val();
                var placement = $('#adddb_placement_input').val();
                var type = $('#adddb_type_input').val();
                var damaged = $('input[name=damaged]:checked').val();
                var comment = $('#adddb_comment_input').val();
                
                if(id != '' && name != '' && type != null && damaged != '')
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
                
                break;
        }
        
        setTimeout(function(){$("#add_item_to_db").removeAttr("disabled")}, 1500);
        
    });
    
    //#####################################################################
    
    //obsługa usuwania przedmiotu z bazy danych
    
    $('#remove_item_from_db_btn').on('click', function(){
       
        if(confirm("Czy na pewno chcesz usunąć ten przedmiot z bazy danych?\n\nAre you sure you want to remove this item from the database?"))
        {
            var id = $('#item_id_holder').text();
            var category = $('#item_category_holder').text();
            
            $.post("php/kategorie/"+ category +".php", {remove_item: id}, function(data){

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
        
    });
    
    //#####################################################################
    
    //obsługa usuwania komentarza z bazy danych
    
    $('.remove_comment_from_db_btn').on('click', function(){
        
        if(confirm("Czy na pewno chcesz usunąć ten komentarz?\n\nAre you sure you want to remove this comment?"))
        {
            var id = $(this).parent().parent().children(':first-child').text();
            var category = $(this).parent().parent().children(':first-child').next().text();
            
            $.post("php/kategorie/"+ category +".php", {remove_comment: id}, function(data){

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
        
    });
    
    //#####################################################################
    
    //obsługa edycji informacji o przedmiocie
    
    $('.edit_item_info_btn').on('click', function(){
        
        $(this).attr("disabled", "disabled");
        
        var category = $('#item_category_holder').text();
        var header =  $(this).parent().data('header');
        
        var element = $(this).parent().parent().parent().parent();
        var element_content = $(this).parent().parent().next();
        
        $('#page_blend').fadeIn(250, function(){
            
            var current_content = element_content.text();
            var current_position = element.offset();
            current_position_left = current_position.left + 'px';
            current_position_top = current_position.top + 'px';
            
            if($('#toggle_menu_btn').css('display') == 'block' || $('body').width() <= 974)
            {
                current_position_top = '100px';
            }
            
            var def_position = element.css('position');
            var def_left = element.css('left');
            var def_top = element.css('top');
            var def_zindex = element.css('z-index');
            var def_width = element.css('width');

            element.css({'position': 'fixed', 'left': current_position_left, 'top': current_position_top, 'z-index': '10', 'width': def_width});
            $('.panel').css({'-webkit-box-shadow': '0px 0px 10px 0px rgba(255,255,255,1)', '-moz-box-shadow': '0px 0px 10px 0px rgba(255,255,255,1)', 'box-shadow': '0px 0px 10px 0px rgba(255,255,255,1)'});
                    
            $.post("php/kategorie/"+category+".php", {item_header: header, current_content: current_content}, function(data){

                element_content.html(data);

            });
            
            //zamknięcie trybu edycji
            $(element_content).on('click', '#close_edit_btn', function(){
                
                element.css({'position': def_position, 'left': def_left, 'top': def_top, 'z-index': def_zindex, 'width': ''});
                $('.panel').css({'-webkit-box-shadow': '', '-moz-box-shadow': '', 'box-shadow': ''});
                element_content.text(current_content);
                $('#page_blend').fadeOut(500);
                
                setTimeout(function(){$(".edit_item_info_btn").removeAttr("disabled")}, 500);
                
            });
            
            //potwierdzenie edycji i zapisanie nowych danych do bazy danych
            $(element_content).on('click', '#confirm_edit_btn', function(){
                
                var new_value = $('#new_value').val();
                
                if(new_value === undefined)
                {
                    new_value = $('input[name=new_value]:checked').val();
                }
                
                if(new_value == '')
                {
                    $('#error_alert').fadeIn(500);
                }
                else
                {
                    var id = $('#item_id_holder').text();
                    
                    $('#error_alert').fadeOut(500);
                            
                    $.post("php/kategorie/"+category+".php", {item_id: id, item_header: header, new_value: new_value}, function(data){

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
                
            });
            
        });
        
    });
    
    //#####################################################################
    
    //obsługa przycisku dodania komentarza do przedmiotu
    var inform_specialist = false;
    
    $("#comment_checkbox").change(function() 
    {
        if(this.checked)
        {
            inform_specialist = true;
            $('#notification_settings').slideDown(100);
        }
        else
        {
            inform_specialist = false;
            $('#notification_settings').slideUp(100);
        }
    });
    
    $('#add_comment_to_db').on('click', function(){
        
        $(this).attr("disabled", "disabled");
        
        var category = $('#item_category_holder').text();
        var id = $('#item_id_holder').text();
        var send_notification = false;
        var specialization = null;
        
        var full_url = window.location.protocol + "//" + window.location.host + "" + window.location.pathname + '?id=' + id;
        
        var comment = $('#new_comment_input').val();
        
        if(comment == '')
        {  
            $('#error_alert').fadeIn(500);
        }
        else
        {
            $('#error_alert').fadeOut(500);
            
            if(inform_specialist == true)
            {
                specialization = $('#specialization_select').val();

                if(specialization == 'brak')
                {
                    $('#specialization_select').css({'outline': '#ea7676 solid thin', 'background-color': '#f2dede'});
                }
                else
                {
                    $('#specialization_select').css({'outline': '', 'background-color': ''});
                    
                    send_notification = true;
                }
            }
            
            $.post("php/kategorie/"+category+".php", {item_id: id, notification: send_notification, specialization: specialization, new_comment: comment, fullurl: full_url}, function(data){

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
        
        setTimeout(function(){$("#add_comment_to_db").removeAttr("disabled")}, 1500);
        
    });
    
});