<?php

    //#### funkcja wypisująca dane od urządzeniu w okienkach
    function devices($tytul, $content)
    {
        echo '

        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title" style="text-align: center;">'. $tytul .'</h3>
                </div>
                <div class="panel-body">
                    '. $content .'
                </div>
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
            echo '

                <table class="table table-striped" align="center" style="width: 95%;">
                    <tr>
                        <th>'.$xml->urzadzenie.'</th>
                        <th style="width: 50%;">'.$xml->uwagi.'</th>
                        <th>'.$xml->ktododal.'</th>
                        <th>'.$xml->kiedy.'</th>
                    </tr>';

                    while($row = mysqli_fetch_array($result))
                    {   
                        echo '

                            <tr>
                                <td><a href="./?id='.$id.'">'.$nazwa.'</a></td>
                                <td>'.$row['comment'].'</td>
                                <td>'.$row['who_added'].'</td>
                                <td style="word-spacing: 15px;"><b>'.$row['when_added'].'</b></td>
                            </tr>
                        ';
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
        $comments = $row['comments'];
        $damaged = $row['damaged'];
        
        $type = $xml->$type;
        
        if($damaged == 0)
        {
            $damaged = $xml->nie;
        }
        else
        {
            $damaged = $xml->tak;
        }

        if($comments == '')
        {
            $comments = $xml->brak;
        }

        devices('ID',$id);
        devices($xml->nazwa,$name);
        devices($xml->ulokowanie,$placement);
        devices($xml->ostatnialokalizacja,$last_location);
        devices($xml->typ,$type);
        devices($xml->uwagi,$comments);
        devices($xml->uszkodzony,$damaged);
        
        if($result = $polaczenie->query("SELECT * FROM devices_comments_history WHERE device_id='$id'")) 
        {
            $ile_komentarzy = $result->num_rows;
        }
        
        if($ile_komentarzy > 0)
        {
            echo '
            <div class="col-md-12">
                <a href="./?history&id='.$id.'"><button class="btn btn-primary btn-lg" style="display: block; margin: 0 auto;">'. $xml->pokazhistorieuwag .'&nbsp;&nbsp;<span class="badge">'.$ile_komentarzy.'</span></button></a>
            </div>';   
        }
    }
    //tabela z wszystkimi urządzeniami w kategorii "urządzenia"
    else if(isset($_GET['category']))
    {
        echo '
        
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
    //zwrócenie informacji o nagłówkach tabeli aby umożliwić dodawanie informacji o przedmiotach do bazy
    else if(isset($_POST['give_headlines']))
    {
        if(isset($_COOKIE['language']))
        {
            $lang = $_COOKIE['language'];

            if($lang == 'pl')
            {
                $xml = simplexml_load_file("../../xml/stronaglowna.xml");  
            }
            else if($lang == 'en')
            {
                $xml = simplexml_load_file("../../xml/stronaglowna_en.xml");
            }
        }
        else
        {
            $xml = simplexml_load_file("../../xml/stronaglowna.xml");
        }
        
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
                        <h3 class="panel-title" style="text-align: center;">'. $xml->uwagi .'</h3>
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
    else if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['type']) && isset($_POST['damaged']))
    {
        if(isset($_COOKIE['language']))
        {
            $lang = $_COOKIE['language'];

            if($lang == 'pl')
            {
                $xml_errors = simplexml_load_file("../../xml/bledy.xml"); 
            }
            else if($lang == 'en')
            {
                $xml_errors = simplexml_load_file("../../xml/bledy_en.xml"); 
            }
        }
        else
        {
            $xml_errors = simplexml_load_file("../../xml/bledy.xml"); 
        }
        
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
                if($polaczenie->query("INSERT INTO devices VALUES ('$id', '$name', '$placement', '$placement', '$type', '$comment', '$who_added', '$damaged')")) 
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

?>