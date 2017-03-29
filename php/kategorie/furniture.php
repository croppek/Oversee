<?php

    //#################################################### TUTAJ ZMIENIAĆ KATEGORIE ########################################################

    //#### funkcja wypisująca dane o przedmiocie w okienkach
    function render($tytul, $content, $db_header, $edit)
    {
        echo '

        <div class="col-md-3">
            <div class="panel panel-primary">';
        
            if($db_header != '')
            {
                echo '<div class="panel-heading" style="padding: 6px;">
                <h3 class="panel-title" style="text-align: center;" data-header="'.$db_header.'">'. $tytul .'<button class="btn btn-info btn-sm edit_item_info_btn">'. $edit .'</button></h3>';
            }
            else
            {
                echo '<div class="panel-heading" style="height: 39px;">
                <h3 class="panel-title" style="text-align: center;">'. $tytul .'</h3>';
            }
        
        
        echo       '</div>
                <div class="panel-body">'. $content .'</div>
            </div>
        </div>';
    }
    
    //tabela pokazująca historię dodanych komentarzy do danego przedmiotu
    if(isset($_GET['history']) && isset($id))
    {   
        //#################################################### TUTAJ ZMIENIAĆ KATEGORIE ########################################################
        if($result = $polaczenie->query("SELECT * FROM furniture_comments_history WHERE item_id='$id' ORDER BY when_added DESC")) 
        {
            if($result->num_rows < 1)
            {
                header('Location: ./?id='.$id);
            }
            
            echo '<h3 style="text-align: center;">'.$xml->historiakomentarzy.'</h3>

            <div id="table_wrapper" style="width: 90%; margin: 0 auto; float: none;">
                <table class="table-striped rtable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>'.$xml->komentarz.'</th>
                        <th>'.$xml->ktododal.'</th>
                        <th>'.$xml->kiedy.'</th>';
                        
                        if($permissions >= 2)
                        {
                            echo '<th>Admin</th>';
                        }
                        
                    echo '</tr></thead><tbody>';

                    //#################################################### TUTAJ ZMIENIAĆ KATEGORIE ########################################################
                    while($row = mysqli_fetch_array($result))
                    {   
                        echo '

                            <tr>
                                <td style="text-align: center; font-weight: bold;"><a href="./?id='.$id.'">'.$id.'</a></td>
                                <td style="display: none;">furniture</td>
                                <td style="max-width: 820px; min-width: 400px; word-break: break-all; white-space: normal;">'.$row['comment'].'</td>
                                <td>'.$row['who_added'].'</td>
                                <td style="word-spacing: 15px;"><b>'.$row['when_added'].'</b></td>';
                                
                                if($permissions >= 2)
                                {
                                    echo '<td><button class="btn btn-danger btn-sm remove_comment_from_db_btn" style="word-break: normal;">'. $xml->usun .'</button></td>';
                                }
                                    
                        echo '</tr>';
                    }  

            echo '</tbody></table></div>';
        }
    }
    //wypisywanie informacji o urządzeniu z użyciem w/w funkcji
    else if(isset($id))
    {
        $id = $row['id'];
        $placement = $row['placement'];
        $last_location = $row['last_location'];
        $type = $row['type'];
        $damaged = $row['damaged'];
        
        if($result = $polaczenie->query("SELECT comment FROM furniture_comments_history WHERE item_id='$id' ORDER BY when_added DESC LIMIT 1")) 
        {
            $row = mysqli_fetch_assoc($result);
                                
            $comment = $row['comment'];
        }
        
        $type = $xml->$type;
        
        if($damaged == 0)
        {
            $damaged = $xml->nie;
        }
        else
        {
            $damaged = $xml->tak;
        }

        if($comment == '')
        {
            $comment = $xml->brak;
        }
        
        $edit = $xml->edytuj;
        
        
        //#################################################### TUTAJ ZMIENIAĆ KATEGORIE ########################################################
        switch($permissions)
        {
            case 4:
                render('ID',$id,'',$edit);
                render($xml->kategoria,$xml->furniture,'',$edit);
                render($xml->ulokowanie,$placement,'placement',$edit);
                render($xml->ostatnialokalizacja,$last_location,'last_location',$edit);
                render($xml->typ,$type,'',$edit);
                render($xml->uszkodzony,$damaged,'damaged',$edit);
                render($xml->uwagi,$comment,'',$edit);
                
                break;
                
            case 3:
                render('ID',$id,'',$edit);
                render($xml->kategoria,$xml->furniture,'',$edit);
                render($xml->ulokowanie,$placement,'placement',$edit);
                render($xml->ostatnialokalizacja,$last_location,'last_location',$edit);
                render($xml->typ,$type,'',$edit);
                render($xml->uszkodzony,$damaged,'damaged',$edit);
                render($xml->uwagi,$comment,'',$edit);
                
                break;
            
            case 2:
                render('ID',$id,'',$edit);
                render($xml->kategoria,$xml->furniture,'',$edit);
                render($xml->ulokowanie,$placement,'placement',$edit);
                render($xml->ostatnialokalizacja,$last_location,'last_location',$edit);
                render($xml->typ,$type,'',$edit);
                render($xml->uszkodzony,$damaged,'damaged',$edit);
                render($xml->uwagi,$comment,'',$edit);
                
                break;
                
            case 1:
                render('ID',$id,'',$edit);
                render($xml->kategoria,$xml->furniture,'',$edit);
                render($xml->ulokowanie,$placement,'',$edit);
                render($xml->ostatnialokalizacja,$last_location,'last_location',$edit);
                render($xml->typ,$type,'',$edit);
                render($xml->uszkodzony,$damaged,'damaged',$edit);
                render($xml->uwagi,$comment,'',$edit);
                
                break;
                
            default:
                render('ID',$id,'',$edit);
                render($xml->kategoria,$xml->furniture,'',$edit);
                render($xml->ulokowanie,$placement,'',$edit);
                render($xml->ostatnialokalizacja,$last_location,'',$edit);
                render($xml->typ,$type,'',$edit);
                render($xml->uszkodzony,$damaged,'',$edit);
                render($xml->uwagi,$comment,'',$edit);
                
                break;
        }
        
        //#################################################### TUTAJ ZMIENIAĆ KATEGORIE ########################################################
        if($result = $polaczenie->query("SELECT * FROM furniture_comments_history WHERE item_id='$id'")) 
        {
            $ile_komentarzy = $result->num_rows;
        }
        
        if($permissions >= 1 || $ile_komentarzy > 0)
        {
            echo '<div class="col-md-12">';

                if($ile_komentarzy > 0)
                {
                        echo '<a href="./?history&id='.$id.'"><button class="btn btn-primary btn-lg" style="display: block; margin: 0 auto;">'. $xml->pokazhistorieuwag .'&nbsp;&nbsp;<span class="badge">'.$ile_komentarzy.'</span></button></a>';
                }
            
                if($permissions >= 1)
                {
                    echo '<button id="add_new_comment_btn" class="btn btn-warning btn-lg" data-toggle="modal" data-target="#add_new_comment_modal" style="display: block; margin: 20px auto 0;">'. $xml->dodajnowykomentarz .'</button>';
                }

                if($permissions >= 3)
                {
                    echo '<button id="remove_item_from_db_btn" class="btn btn-danger btn-md" style="display: block; margin: 20px auto 0;">'. $xml->usunprzedmiot .'</button>';
                }

            echo '</div>'; 
            
            echo '
            
            <div id="add_new_comment_modal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">'. $xml->modal_tytul4 .'</h4>
                    </div>
                <div id="new_comment_content" class="modal-body" style="padding-bottom: 75px;">
                    
                    <textarea id="new_comment_input" style="width: 100%; height: 150px; resize: vertical;"></textarea>
                    
                    <div id="error_alert" class="alert alert-danger" role="alert" style="width: 90%; margin: 5px auto 10px; display: none;">'. $xml_errors->blad20 .'</div>
                    
                    <input id="comment_checkbox" type="checkbox" name="notify_specialists">
                    <label id="label_for_comment_checkbox" for="comment_checkbox">'. $xml->powiadomspecialistow .'</label>
                
                    <div id="notification_settings" class="well" style="margin-bottom: 0; display: none;">
                    
                        <div class="input-group input-group-md" style="width: 80%; margin: 0 auto; float: none;">
                            <label for="specialization_input">'. $xml->wybierzspecjalistow .'</label>
                            <select class="form-control" id="specialization_select">
                                <option value="brak"></option>
                                <option value="architektura">'. $xml->option1 .'</option>
                                <option value="bibliotekarstwo">'. $xml->option2 .'</option>
                                <option value="blacharstwo">'. $xml->option3 .'</option>
                                <option value="hydraulika">'. $xml->option4 .'</option>
                                <option value="elektronika">'. $xml->option5 .'</option>
                                <option value="elektryka">'. $xml->option6 .'</option>
                                <option value="informatyka">'. $xml->option7 .'</option>
                                <option value="lakiernictwo">'. $xml->option8 .'</option>
                                <option value="mechanika">'. $xml->option9 .'</option>
                                <option value="stolarstwo">'. $xml->option10 .'</option>
                            </select>
                        </div>
                    
                    </div>
                
                    <div class="col-md-12">
                        <button id="add_comment_to_db" type="button" class="btn btn-primary btn-lg" style="display: block; margin: 15px auto 0;">'. $xml->dodajkomentarz .'</button>
                    </div>

                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">'. $xml->zamknij .'</button>
                    </div>
                </div>
                </div>
            </div>';
        }
        
    }
    //tabela z wszystkimi przedmiotami w kategorii
    else if(isset($_GET['category']))
    {
        //#################################################### TUTAJ ZMIENIAĆ KATEGORIE ########################################################
        echo '
        
        <h3 style="text-align: center; font-size: 25px; margin-bottom: 15px;">'.$xml->naglowekkategorii.' <b>'.$xml->furniture.'</b> ('.$all_items_in_category.')</h3>
        
        <div id="table_wrapper" style="width: 90%; margin: 0 auto; float: none;">
            <table class="table-striped rtable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>'.$xml->ulokowanie.'</th>
                    <th>'.$xml->typ.'</th>
                    <th>'.$xml->uszkodzony.'</th>
                </tr>
            </thead>
            <tbody>';
        
                while($row = mysqli_fetch_array($result))
                {
                    if($row['damaged'] == 0)
                    {
                        $damaged = $xml->nie;
                    }
                    else
                    {
                        $damaged = $xml->tak;
                    }
                    
					$type = $row['type'];
					
                    echo '
                    
                        <tr>
                            <td><a href="./?id='.$row['id'].'">'.$row['id'].'</a></td>
                            <td>'.$row['placement'].'</td>
                            <td>'.$xml->$type.'</td>
                            <td>'.$damaged.'</td>
                        </tr>
                    ';
                }  
        
        echo '</tbody></table></div>';
    }
    else
    {
        session_start();
        
        if(isset($_COOKIE['oversee_language']))
        {
            $lang = $_COOKIE['oversee_language'];

            if($lang == 'pl')
            {
                $xml = simplexml_load_file("../../xml/stronaglowna.xml");
                $xml_errors = simplexml_load_file("../../xml/bledy.xml");
            }
            else if($lang == 'en')
            {
                $xml = simplexml_load_file("../../xml/stronaglowna_en.xml");
                $xml_errors = simplexml_load_file("../../xml/bledy_en.xml");
            }
        }
        else
        {
            $xml = simplexml_load_file("../../xml/stronaglowna.xml");
            $xml_errors = simplexml_load_file("../../xml/bledy.xml");
        }
        
        //zwrócenie informacji o nagłówkach tabeli aby umożliwić dodawanie informacji o przedmiotach do bazy
        if(isset($_POST['give_headlines']))
        {
            //#################################################### TUTAJ ZMIENIAĆ KATEGORIE ########################################################
            echo '

            <form id="add_item_to_db_form">

                <div class="col-md-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="text-align: center;">'. $xml->ulokowanie .'</h3>
                        </div>
                        <div class="panel-body" style="height: 55px;">
                            <input id="adddb_placement_input" type="text" style="width: 100%;">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="text-align: center;">'. $xml->typ .'</h3>
                        </div>
                        <div class="panel-body" style="height: 55px;">            
                            <div class="input-group input-group-sm" style="width: 100%;">
                                <select class="form-control" name="type" id="adddb_type_input" required>
                                    <option value="" selected disabled>' . $xml->wybierz . '</option>
                                    <option value="krzeslo">' . $xml->krzeslo . '</option>
                                    <option value="stol">' . $xml->stol . '</option>
                                    <option value="biurko">' . $xml->biurko . '</option>
                                    <option disabled="disabled">------------------------------------------------</option>
                                    <option value="inny" style="font-weight: bold;">' . $xml->inny . '</option>
                                    <option disabled="disabled">------------------------------------------------</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="text-align: center;">'. $xml->uszkodzony .'</h3>
                        </div>
                        <div class="panel-body<div class="panel-body" style="height: 55px; text-align: center; padding-top: 20px;">
                            <input type="radio" name="damaged" value="0" checked> '. $xml->nie .'&nbsp;&nbsp;
                            <input type="radio" name="damaged" value="1"> '. $xml->tak .'
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="text-align: center;">'. $xml->komentarz .'</h3>
                        </div>
                        <div class="panel-body" style="height: 100px;">
                            <textarea id="adddb_comment_input" style="width: 100%; height: 75px; resize: vertical;"></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <button id="add_item_to_db" type="submit" class="btn btn-primary btn-lg" style="display: block; margin: 0 auto;">'. $xml->dodaj .'</button>
                </div>
            </form>
            ';
        }
        //dodanie informacji o przedmiocie do bazy danych
        else if(isset($_POST['id']) && isset($_POST['type']) && isset($_POST['damaged']))
        {        
            //#################################################### TUTAJ ZMIENIAĆ KATEGORIE ########################################################
            if(isset($_SESSION['permissions']) && $_SESSION['permissions'] >= 3)
            {
                $id = $_POST['id'];
                $name = $xml->furniture . '(id '. $id .')';
                $placement = $_POST['placement'];
                $type = $_POST['type'];
                $damaged = $_POST['damaged'];
                $comment = $_POST['comment'];

                $who_added = NULL;
                if($comment != '')
                {
                    $user_name = $_SESSION['name'];
                    $user_lastname = $_SESSION['lastname'];

                    $who_added = $user_name.' '.$user_lastname;
                }
                else
                {
                    $comment = NULL;
                }

                require '../connect.php';

                $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
                @mysqli_set_charset($polaczenie,"utf8");

                if($polaczenie->connect_errno == 0)
                {  
                    if($polaczenie->query("INSERT INTO furniture VALUES ('$id', '$placement', '$placement', '$type', '$damaged')")) 
                    {
                        if($polaczenie->query("INSERT INTO item_category VALUES ('$id', '$name', '$placement', 'furniture')")) 
                        {
                            if($comment != NULL)
                            {
                                if($polaczenie->query("INSERT INTO furniture_comments_history VALUES (NULL, '$comment', '$who_added', CURRENT_TIMESTAMP, '$id')")) 
                                {
                                    echo 'success';
                                }
                            }
                            else
                            {
                                echo 'success';
                            }
                        }
                    }
                    else 
                    {
                        echo $xml_errors->blad6;
                    }

                    $polaczenie->close();
                }
                else
                {
                    echo $xml_errors->blad6;
                }

            }
            else
            {
                echo $xml_errors->brakpermisji;
            }
        }
        //usunięcie przedmiotu z bazy danych
        else if(isset($_POST['remove_item']))
        {
            if(isset($_SESSION['permissions']) && $_SESSION['permissions'] >= 3)
            {
                $id = $_POST['remove_item'];

                require '../connect.php';

                $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
                @mysqli_set_charset($polaczenie,"utf8");

                if($polaczenie->connect_errno == 0)
                {  
                    //#################################################### TUTAJ ZMIENIAĆ KATEGORIE ########################################################
                    
                    if($polaczenie->query("DELETE FROM furniture WHERE id='$id'")) 
                    {
                        if($polaczenie->query("DELETE FROM item_category WHERE id='$id'")) 
                        {
                            if($polaczenie->query("DELETE FROM furniture_comments_history WHERE item_id='$id'")) 
                            {
                                echo 'success';
                            }
                        }
                    }
                    else 
                    {
                        echo $xml_errors->blad6;
                    }

                    $polaczenie->close();
                }
                else
                {
                    echo $xml_errors->blad6;
                }

            }
            else
            {
                echo $xml_errors->brakpermisji;
            }
        }
        //usunięcie komentarza z bazy danych
        else if(isset($_POST['remove_comment']))
        {
            if(isset($_SESSION['permissions']) && $_SESSION['permissions'] >= 2)
            {
                $id = $_POST['remove_comment'];

                require '../connect.php';

                $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
                @mysqli_set_charset($polaczenie,"utf8");

                if($polaczenie->connect_errno == 0)
                {  
                    
                    //#################################################### TUTAJ ZMIENIAĆ KATEGORIE ########################################################
                    
                    if($polaczenie->query("DELETE FROM furniture_comments_history WHERE id='$id'")) 
                    {
                        echo 'success';
                    }
                    else 
                    {
                        echo $xml_errors->blad6;
                    }

                    $polaczenie->close();
                }
                else
                {
                    echo $xml_errors->blad6;
                }

            }
            else
            {
                echo $xml_errors->brakpermisji;
            }
        }
        //zwrócenie rodzaju inputu do edycji z przyciskami
        else if(isset($_POST['item_header']) && isset($_POST['current_content']))
        {
            $item_header = $_POST['item_header'];
            $current_content = $_POST['current_content'];
            
            //#################################################### TUTAJ ZMIENIAĆ KATEGORIE ########################################################
            
            switch($item_header)
            {  
                case 'placement':
                    
                    echo '<input id="new_value" style="width: 100%;" type="text" value="'. $current_content. '"><div style="display: block; margin: 15px auto 0; width: 205px;"><button id="confirm_edit_btn" class="btn btn-default btn-md" style="margin: 0 40px 0 0">'. $xml->potwierdz .'</button><button id="close_edit_btn" class="btn btn-default btn-md">'. $xml->anuluj .'</button></div>';
                    
                    break;
                    
                case 'last_location':
                    
                    echo '<input id="new_value" style="width: 100%;" type="text" value="'. $current_content. '"><div style="display: block; margin: 15px auto 0; width: 205px;"><button id="confirm_edit_btn" class="btn btn-default btn-md" style="margin: 0 40px 0 0">'. $xml->potwierdz .'</button><button id="close_edit_btn" class="btn btn-default btn-md">'. $xml->anuluj .'</button></div>';
                    
                    break;
                    
                case 'damaged':
                    
                    echo '
                    
                    <div class="panel-body<div class="panel-body" style="height: 55px; text-align: center; padding-top: 20px;">';
                    
                    if($current_content == 'No' || $current_content == 'Nie')
                    {
                        echo '<input type="radio" name="new_value" value="0" checked>&nbsp;'. $xml->nie .'&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="new_value" value="1">&nbsp;'. $xml->tak;
                    }
                    else
                    {
                        echo '<input type="radio" name="new_value" value="0">&nbsp;'. $xml->nie .'&nbsp;&nbsp;
                            <input type="radio" name="new_value" value="1" checked>&nbsp;'. $xml->tak;
                    }
                    
                    
                    echo '</div>
                    
                    <div style="display: block; margin: 15px auto 0; width: 205px;"><button id="confirm_edit_btn" class="btn btn-default btn-md" style="margin: 0 40px 0 0">'. $xml->potwierdz .'</button><button id="close_edit_btn" class="btn btn-default btn-md">'. $xml->anuluj .'</button></div>';
                    
                    break;
            }
        }
        //edytowanie informacji o przedmiocie
        else if(isset($_POST['item_id']) && isset($_POST['item_header']) && isset($_POST['new_value']))
        {
            $header = $_POST['item_header'];
            $new_value = $_POST['new_value'];
            $id = $_POST['item_id'];
            
            require '../connect.php';

            $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
            @mysqli_set_charset($polaczenie,"utf8");

            if($polaczenie->connect_errno == 0)
            {  
                if($polaczenie->query("UPDATE furniture SET $header = '$new_value' WHERE id = '$id'")) 
                {
                    echo 'success';
                }
                else 
                {
                    echo $xml_errors->blad6;
                }

                $polaczenie->close();
            }
            else
            {
                echo $xml_errors->blad6;
            }
        }
        //dodawanie nowego komentarza do przedmiotu
        else if(isset($_POST['item_id']) && isset($_POST['notification']) && isset($_POST['specialization']) && isset($_POST['new_comment']) && isset($_POST['fullurl']))
        {
            $id = $_POST['item_id'];
            $send_notification = $_POST['notification'];
            $comment = $_POST['new_comment'];
            
            $user_name = $_SESSION['name'];
            $user_lastname = $_SESSION['lastname'];

            $who_added = $user_name.' '.$user_lastname;

            if(isset($_SESSION['permissions']) && $_SESSION['permissions'] >= 1)
            {
                if($send_notification == true)
                {
                    $full_url = $_POST['fullurl'];
                    $specialization = $_POST['specialization'];
                    $datetime = date("d-m-Y H:i:s");
                    
                    $wiadomosc = '

                    <html>
                    <head>
                        <title>Nowy komentarz.</title>
                    </head>
                    <body style="text-align: center; background-color: #fff; color: #033E6B; font-size: 1.2em; padding: 30px;">

                        <div style="font-size: 3em; border-bottom: 1px dashed #033E6B; padding: 10px; margin-bottom: 20px;">
                            <span style="color: #033E6B; font-weight: bold;">Oversee</span> <span style="color: #3F92D2">Systems</span>
                        </div>

                        <p>Powiadomienie dla użytkowników ze specjalizacją: <b>' . $specialization . '</b>.<br/>
                        Komentarz dodany: <span style="font-weight: bold; word-spacing: 10px;">'. $datetime .'</span>.<br/><br/>
                        Komentarz dostępny: </p>
                        <p style="padding: 20px; display: block; width: 150px; margin: 0 auto; border: 3px solid #033E6B; color: #fff; text-shadow:-1px -1px 0 #000,  1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000; border-radius: 10px; background-color: #66A3D2; font-size: 2em;"><a href="'. $full_url .'" target="_blank" style="color: #033E6B; text-decorations: none;"><b>TUTAJ</b></a></p>

                        <div style="width: 80%; height: 20px; background-color: #033E6B; color: #fff; margin: 35px auto 0; padding: 20px; display: block; border-radius: 10px; text-shadow:-1px -1px 0 #000,  1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;">Bartosz Kropidłowki | Oversee Systems © '. date("Y") .'</div>

                    </body>
                    </html>

                    ';

                    require '../connect.php';

                    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
                    @mysqli_set_charset($polaczenie,"utf8");

                    if($polaczenie->connect_errno == 0)
                    {  
                        if($rezultat = $polaczenie->query("SELECT email FROM users WHERE specialization = '$specialization'")) 
                        {   
                            while($row = mysqli_fetch_array($rezultat))
                            {   
                                $email = $row['email'];
                                
                                $ch = curl_init();

                                curl_setopt($ch, CURLOPT_URL,"http://oversee.zspwrzesnia.pl/php/global_mailer.php");
                                curl_setopt($ch, CURLOPT_POST, 1);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, 
                                            http_build_query(array('sender' => 'notifier', 'code' => 'E5rdaZK9', 'author_title' => 'Oversee Systems (no-reply)', 'recipient' => $email, 'subject' => 'Ważny komentarz o przedmiocie w systemie Oversee.', 'content' => $wiadomosc)));
                                
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                $server_output = curl_exec ($ch);

                                curl_close($ch);
                            }  
                        }
                        else 
                        {
                            echo $xml_errors->blad6;
                        }

                        $polaczenie->close();
                    }
                    else
                    {
                        echo $xml_errors->blad6;
                    }
                }

                if($comment != '')
                {
                    require '../connect.php';

                    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
                    @mysqli_set_charset($polaczenie,"utf8");

                    if($polaczenie->connect_errno == 0)
                    {  
                        if($polaczenie->query("INSERT INTO furniture_comments_history VALUES(NULL, '$comment', '$who_added', CURRENT_TIMESTAMP, '$id')")) 
                        {
                            echo 'success';
                        }
                        else 
                        {
                            echo $xml_errors->blad6;
                        }

                        $polaczenie->close();
                    }
                    else
                    {
                        echo $xml_errors->blad6;
                    }
                }
            }
            else
            {
                echo $xml_errors->brakpermisji;
            }
        }
    }

?>