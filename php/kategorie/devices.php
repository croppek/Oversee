<?php

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

        if($result = $polaczenie->query("SELECT * FROM devices_comments_history WHERE device_id='$id' ORDER BY when_added DESC")) 
        {
            if($result->num_rows < 1)
            {
                header('Location: ./?id='.$id);
            }
            
            echo '

                <table class="table table-striped" align="center" style="width: 95%;">
                    <tr>
                        <th>'.$xml->urzadzenie.'</th>
                        <th style="width: 40%;">'.$xml->uwagi.'</th>
                        <th>'.$xml->ktododal.'</th>
                        <th>'.$xml->kiedy.'</th>';
                        
                        if($permissions >= 3)
                        {
                            echo '<th>Admin</th>';
                        }
                        
                    echo '</tr>';

                    while($row = mysqli_fetch_array($result))
                    {   
                        echo '

                            <tr>
                                <td style="display: none;">'.$row['id'].'</td>
                                <td><a href="./?id='.$id.'">'.$nazwa.'</a></td>
                                <td>'.$row['comment'].'</td>
                                <td>'.$row['who_added'].'</td>
                                <td style="word-spacing: 15px;"><b>'.$row['when_added'].'</b></td>';
                                
                                if($permissions >= 2)
                                {
                                    echo '<td><button class="btn btn-danger btn-sm remove_comment_from_db_btn">'. $xml->usun .'</button></td>';
                                }
                                    
                        echo '</tr>';
                    }  

            echo '</table>';
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
        
        if($result = $polaczenie->query("SELECT * FROM devices_comments_history WHERE device_id='$id'")) 
        {
            $ile_komentarzy = $result->num_rows;
        }
        
        if($permissions >= 3 || $ile_komentarzy > 0)
        {
            echo '<div class="col-md-12">';

                if($ile_komentarzy > 0)
                {
                        echo '<a href="./?history&id='.$id.'"><button class="btn btn-primary btn-lg" style="display: block; margin: 0 auto;">'. $xml->pokazhistorieuwag .'&nbsp;&nbsp;<span class="badge">'.$ile_komentarzy.'</span></button></a>';
                }

                if($permissions >= 3)
                {
                    echo '<button id="remove_item_from_db_btn" class="btn btn-danger btn-md" style="display: block; margin: 20px auto 0;">'. $xml->usunprzedmiot .'</button>';
                }

            echo '</div>'; 
        }
    }
    //tabela z wszystkimi urządzeniami w kategorii "urządzenia"
    else if(isset($_GET['category']))
    {
        echo '
        
            <p style="text-align: center; font-size: 25px; margin-bottom: 15px;">'.$xml->naglowekkategorii.' <b>'.$xml->devices.'</b></p>
        
            <table class="table table-striped" align="center" style="width: 95%;">
                <tr>
                    <th>ID</th>
                    <th>'.$xml->nazwa.'</th>
                    <th>'.$xml->ulokowanie.'</th>
                    <th>'.$xml->typ.'</th>
                    <th>'.$xml->uszkodzony.'</th>
                </tr>';
        
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
        
        echo '</table>';
    }
    else if((isset($_POST['give_headlines'])) || (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['type']) && isset($_POST['damaged'])) || (isset($_POST['remove_item'])) || (isset($_POST['remove_comment'])))
    {
        if(isset($_COOKIE['language']))
        {
            $lang = $_COOKIE['language'];

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
                            <textarea id="adddb_comment_input" style="width: 100%; height: 75px;"></textarea>
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
            session_start();

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
                        if($polaczenie->query("INSERT INTO item_category VALUES ('$id', 'devices')")) 
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
            session_start();

            if(isset($_SESSION['permissions']) && $_SESSION['permissions'] >= 3)
            {
                $id = $_POST['remove_item'];

                require '../connect.php';

                $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
                @mysqli_set_charset($polaczenie,"utf8");

                if($polaczenie->connect_errno == 0)
                {  
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
            session_start();

            if(isset($_SESSION['permissions']) && $_SESSION['permissions'] >= 3)
            {
                $id = $_POST['remove_comment'];

                require '../connect.php';

                $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
                @mysqli_set_charset($polaczenie,"utf8");

                if($polaczenie->connect_errno == 0)
                {  
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
    }

?>