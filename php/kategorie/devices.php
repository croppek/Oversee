<?php

    //#################################################### TUTAJ ZMIENIAĆ KATEGORIE ########################################################

    //#### funkcja wypisująca dane o urządzeniu w okienkach
    function devices($tytul, $content, $db_header, $edit)
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
    
    //tabela pokazująca historię dodanych komentarzy do danego urządzenia
    if(isset($_GET['history']) && isset($id))
    {  
        if($result = $polaczenie->query("SELECT name FROM $category WHERE id='$id'")) 
        {
            $row = mysqli_fetch_assoc($result);

            $nazwa = $row['name'];
        }
        
        //#################################################### TUTAJ ZMIENIAĆ KATEGORIE ########################################################
        if($result = $polaczenie->query("SELECT * FROM devices_comments_history WHERE device_id='$id' ORDER BY when_added DESC")) 
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
                        <th>'.$xml->urzadzenie.'</th>
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
                                <td style="display: none;">'.$row['id'].'</td>
                                <td style="display: none;">devices</td>
                                <td style="min-width: 100px; text-align: center; white-space: normal;"><a href="./?id='.$id.'">'.$nazwa.'</a></td>
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
        $name = $row['name'];
        $placement = $row['placement'];
        $last_location = $row['last_location'];
        $type = $row['type'];
        $damaged = $row['damaged'];
        
        if($result = $polaczenie->query("SELECT comment FROM devices_comments_history WHERE device_id='$id' ORDER BY when_added DESC LIMIT 1")) 
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
            case 3:
                devices('ID',$id,'',$edit);
                devices($xml->nazwa,$name,'name',$edit);
                devices($xml->ulokowanie,$placement,'placement',$edit);
                devices($xml->ostatnialokalizacja,$last_location,'last_location',$edit);
                devices($xml->typ,$type,'',$edit);
                devices($xml->uszkodzony,$damaged,'damaged',$edit);
                devices($xml->uwagi,$comment,'',$edit);
                
                break;
            
            case 2:
                devices('ID',$id,'',$edit);
                devices($xml->nazwa,$name,'name',$edit);
                devices($xml->ulokowanie,$placement,'placement',$edit);
                devices($xml->ostatnialokalizacja,$last_location,'last_location',$edit);
                devices($xml->typ,$type,'',$edit);
                devices($xml->uszkodzony,$damaged,'damaged',$edit);
                devices($xml->uwagi,$comment,'',$edit);
                
                break;
                
            case 1:
                devices('ID',$id,'',$edit);
                devices($xml->nazwa,$name,'',$edit);
                devices($xml->ulokowanie,$placement,'',$edit);
                devices($xml->ostatnialokalizacja,$last_location,'last_location',$edit);
                devices($xml->typ,$type,'',$edit);
                devices($xml->uszkodzony,$damaged,'damaged',$edit);
                devices($xml->uwagi,$comment,'',$edit);
                
                break;
                
            default:
                devices('ID',$id,'',$edit);
                devices($xml->nazwa,$name,'',$edit);
                devices($xml->ulokowanie,$placement,'',$edit);
                devices($xml->ostatnialokalizacja,$last_location,'',$edit);
                devices($xml->typ,$type,'',$edit);
                devices($xml->uszkodzony,$damaged,'',$edit);
                devices($xml->uwagi,$comment,'',$edit);
                
                break;
        }
        
        //#################################################### TUTAJ ZMIENIAĆ KATEGORIE ########################################################
        if($result = $polaczenie->query("SELECT * FROM devices_comments_history WHERE device_id='$id'")) 
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
    //tabela z wszystkimi urządzeniami w kategorii "urządzenia"
    else if(isset($_GET['category']))
    {
        //#################################################### TUTAJ ZMIENIAĆ KATEGORIE ########################################################
        echo '
        
        <p id="category_title" style="text-align: center; font-size: 25px; margin-bottom: 15px;">'.$xml->naglowekkategorii.' <b>'.$xml->devices.'</b> ('.$all_items_in_category.')</p>
        
        <div id="table_wrapper" style="width: 90%; margin: 0 auto; float: none;">
            <table class="table-striped rtable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>'.$xml->nazwa.'</th>
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
                    
                    echo '
                    
                        <tr>
                            <td><a href="./?id='.$row['id'].'">'.$row['id'].'</a></td>
                            <td><a href="./?id='.$row['id'].'">'.$row['name'].'</a></td>
                            <td>'.$row['placement'].'</td>
                            <td>'.$xml->$row['type'].'</td>
                            <td>'.$damaged.'</td>
                        </tr>
                    ';
                }  
        
        echo '</tbody></table></div>';
    }
    else if((isset($_POST['give_headlines'])) || (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['type']) && isset($_POST['damaged'])) || (isset($_POST['remove_item'])) || (isset($_POST['remove_comment'])) || (isset($_POST['item_header']) && isset($_POST['current_content'])) || (isset($_POST['item_id']) && isset($_POST['item_header']) && isset($_POST['new_value'])) || (isset($_POST['item_id']) && isset($_POST['notification']) && isset($_POST['specialization']) && isset($_POST['new_comment']) && isset($_POST['fullurl'])))
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
                            <h3 class="panel-title" style="text-align: center;">'. $xml->nazwa .'</h3>
                        </div>
                        <div class="panel-body" style="height: 55px;">
                            <input id="adddb_name_input" type="text" style="width: 100%;" required>
                        </div>
                    </div>
                </div>

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
                                    <option value="stacjarobocza">' . $xml->stacjarobocza . '</option>
                                    <option value="serwer">' . $xml->serwer . '</option>
                                    <option value="laptop">' . $xml->laptop . '</option>
                                    <option value="terminal">' . $xml->terminal . '</option>
                                    <option value="monitor">' . $xml->monitor . '</option>
                                    <option value="myszkomputerowa">' . $xml->myszkomputerowa . '</option>
                                    <option value="klawiatura">' . $xml->klawiatura . '</option>
                                    <option value="glosniki">' . $xml->glosniki . '</option>
                                    <option value="sluchawki">' . $xml->sluchawki . '</option>
                                    <option value="mikrofon">' . $xml->mikrofon . '</option>
                                    <option value="kalkulator">' . $xml->kalkulator . '</option>
                                    <option value="projektor">' . $xml->projektor . '</option>
                                    <option disabled="disabled">------------------------------------------------</option>
                                    <option value="telefon">' . $xml->telefon . '</option>
                                    <option value="smartphone">' . $xml->smartphone . '</option>
                                    <option value="automatycznasekretarka">' . $xml->automatycznasekretarka . '</option>
                                    <option value="faks">' . $xml->faks . '</option>
                                    <option disabled="disabled">------------------------------------------------</option>
                                    <option value="drukarka">' . $xml->drukarka . '</option>
                                    <option value="ksero">' . $xml->ksero . '</option>
                                    <option value="skaner">' . $xml->skaner . '</option>
                                    <option value="urzadzeniewielofunkcyjne">' . $xml->urzadzeniewielofunkcyjne . '</option>
                                    <option disabled="disabled">------------------------------------------------</option>
                                    <option value="telewizor">' . $xml->telewizor . '</option>
                                    <option value="dekoder">' . $xml->dekoder . '</option>
                                    <option value="radio">' . $xml->radio . '</option>
                                    <option value="odtwarzaczvideo">' . $xml->odtwarzaczvideo . '</option>
                                    <option value="wierzahifi">' . $xml->wierzahifi . '</option>
                                    <option value="wzmacniacz">' . $xml->wzmacniacz . '</option>
                                    <option value="kamera">' . $xml->kamera . '</option>
                                    <option value="tablicainteraktywna">' . $xml->tablicainteraktywna . '</option>
                                    <option disabled="disabled">------------------------------------------------</option>
                                    <option value="lodowka">' . $xml->lodowka . '</option>
                                    <option value="pralka">' . $xml->pralka . '</option>
                                    <option value="zmywarka">' . $xml->zmywarka . '</option>
                                    <option value="mikrofalowka">' . $xml->mikrofalowka . '</option>
                                    <option value="grzejnikelektryczny">' . $xml->grzejnikelektryczny . '</option>
                                    <option value="wentylator">' . $xml->wentylator . '</option>
                                    <option value="klimatyzator">' . $xml->klimatyzator . '</option>
                                    <option disabled="disabled">------------------------------------------------</option>
                                    <option value="odkurzacz">' . $xml->odkurzacz . '</option>
                                    <option value="toster">' . $xml->toster . '</option>
                                    <option value="frytkownica">' . $xml->frytkownica . '</option>
                                    <option value="suszarka">' . $xml->suszarka . '</option>
                                    <option value="waga">' . $xml->waga . '</option>
                                    <option value="zegar">' . $xml->zegar . '</option>
                                    <option value="lampa">' . $xml->lampa . '</option>
                                    <option disabled="disabled">------------------------------------------------</option>
                                    <option value="pila">' . $xml->pila . '</option>
                                    <option value="wiertarka">' . $xml->wiertarka . '</option>
                                    <option value="maszynadoszycia">' . $xml->maszynadoszycia . '</option>
                                    <option value="spawarka">' . $xml->spawarka . '</option>
                                    <option value="kosiarkaelektryczna">' . $xml->kosiarkaelektryczna . '</option>
                                    <option value="dmuchawa">' . $xml->dmuchawa . '</option>
                                    <option disabled="disabled">------------------------------------------------</option>
                                    <option value="sprzetdoradioterapii">' . $xml->sprzetdoradioterapii . '</option>
                                    <option value="sprzetdobadankardiologicznych">' . $xml->sprzetdobadankardiologicznych . '</option>
                                    <option value="sprzetdodializoterapii">' . $xml->sprzetdodializoterapii . '</option>
                                    <option value="sprzetdowentylacjipluc">' . $xml->sprzetdowentylacjipluc . '</option>
                                    <option value="urzadzeniemedycznewykorzystujacetechnikenuklearna">' . $xml->urzadzeniemedycznewykorzystujacetechnikenuklearna . '</option>
                                    <option disabled="disabled">------------------------------------------------</option>
                                    <option value="czujnikdymu">' . $xml->czujnikdymu . '</option>
                                    <option value="regulatorciepla">' . $xml->regulatorciepla . '</option>
                                    <option value="termostat">' . $xml->termostat . '</option>
                                    <option disabled="disabled">------------------------------------------------</option>
                                    <option value="automatdowydawanianajopowgoracych">' . $xml->automatdowydawanianajopowgoracych . '</option>
                                    <option value="automatdowydawaniabutelekipuszek">' . $xml->automatdowydawaniabutelekipuszek . '</option>
                                    <option value="automatdowydawaniaproduktowstalych">' . $xml->automatdowydawaniaproduktowstalych . '</option>
                                    <option value="bankomat">' . $xml->bankomat . '</option>
                                    <option value="innyautomat">' . $xml->innyautomat . '</option>
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
        else if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['type']) && isset($_POST['damaged']))
        {        
            //#################################################### TUTAJ ZMIENIAĆ KATEGORIE ########################################################
            if(isset($_SESSION['permissions']) && $_SESSION['permissions'] >= 3)
            {
                $id = $_POST['id'];
                $name = $_POST['name'];
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
                    if($polaczenie->query("INSERT INTO devices VALUES ('$id', '$name', '$placement', '$placement', '$type', '$damaged')")) 
                    {
                        if($polaczenie->query("INSERT INTO item_category VALUES ('$id', '$name', '$placement', 'devices')")) 
                        {
                            if($comment != NULL)
                            {
                                if($polaczenie->query("INSERT INTO devices_comments_history VALUES (NULL, '$comment', '$who_added', CURRENT_TIMESTAMP, '$id')")) 
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
                    
                    if($polaczenie->query("DELETE FROM devices WHERE id='$id'")) 
                    {
                        if($polaczenie->query("DELETE FROM item_category WHERE id='$id'")) 
                        {
                            if($polaczenie->query("DELETE FROM devices_comments_history WHERE device_id='$id'")) 
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
                    
                    if($polaczenie->query("DELETE FROM devices_comments_history WHERE id='$id'")) 
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
                case 'name':
                    
                    echo '<input id="new_value" style="width: 100%;" type="text" value="'. $current_content. '"><div id="error_alert" class="alert alert-danger" role="alert" style="width: 100%; margin: 20px auto 0; display: none;">'. $xml_errors->blad20 .'</div><div style="display: block; margin: 15px auto 0; width: 205px;"><button id="confirm_edit_btn" class="btn btn-default btn-md" style="margin: 0 40px 0 0">'. $xml->potwierdz .'</button><button id="close_edit_btn" class="btn btn-default btn-md">'. $xml->anuluj .'</button></div>';
                    
                    break;
                    
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
                        echo '<input type="radio" name="new_value" value="0">'. $xml->nie .'&nbsp;&nbsp;
                            <input type="radio" name="new_value" value="1" checked>'. $xml->tak;
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
                if($polaczenie->query("UPDATE devices SET $header = '$new_value' WHERE id = '$id'")) 
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

                                curl_setopt($ch, CURLOPT_URL,"http://kroptech.net/oversee/php/global_mailer.php");
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
                        if($polaczenie->query("INSERT INTO devices_comments_history VALUES(NULL, '$comment', '$who_added', CURRENT_TIMESTAMP, '$id')")) 
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