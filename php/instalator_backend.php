<?php

    //sprawdzanie postępu instalacji w przypadku przerwania wcześniejszej próby
    if(isset($_POST['status']))
    {
        $status = $_POST['status'];
        
        switch($status)
        {
            case 1:
                
                //sprawdzenie czy język instalatora został już wcześniej wybrany, jeśli tak pokazanie kolejnego kroku
                if(isset($_COOKIE['oversee_language']) && isset($_COOKIE['oversee_xml_url']))
                {
                    $xml = simplexml_load_file($_COOKIE['oversee_xml_url']) or die("Error: Cannot create object");
                    
                    add_content1($xml);
                }
                else
                {
                    echo 'fail';
                }
                
                break;
                
            case 2:
                
                require 'connect.php';
                
                $polaczenie = @new mysqli($host, $db_user, $db_password);
                
                //sprawdzanie czy zapisane dane w pliku connect.php są prawidłowe, jeśli tak pokazanie kolejnego kroku
                if($polaczenie->connect_errno == 0)
                {  
                    $polaczenie->close();
                    
                    $xml = simplexml_load_file($_COOKIE['oversee_xml_url']) or die("Error: Cannot create object");
                    
                    add_content2($xml);
                }
                else
                {
                    echo 'fail';
                }
                
                break;
                
            case 3:
                
                //sprawdzanie czy baza jest utworzona i istnieje tabela users
                require 'connect.php';
                
                $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
                @mysqli_set_charset($polaczenie,"utf8");
                
                if($polaczenie->connect_errno == 0)
                {  
                    if($result = $polaczenie->query("SHOW TABLES LIKE 'users'")) 
                    {
                        if($result->num_rows == 1) 
                        {
                            $xml = simplexml_load_file($_COOKIE['oversee_xml_url']) or die("Error: Cannot create object");
                    
                            add_content3($xml);
                        }
                        else
                        {
                            echo 'fail';
                        }
                    }
                    else 
                    {
                        echo 'fail';
                    }
                    
                    $polaczenie->close();
                }
                else
                {
                    echo 'fail';
                }
                
                break;
                
            case 4:
                
                //sprawdzanie czy w tabeli users znajduje się konto właściciela
                require 'connect.php';
                
                $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
                @mysqli_set_charset($polaczenie,"utf8");
                
                if($polaczenie->connect_errno == 0)
                {  
                    if($result = $polaczenie->query("SELECT * FROM users")) 
                    {
                        if($result->num_rows > 0) 
                        {
                            $xml = simplexml_load_file($_COOKIE['oversee_xml_url']) or die("Error: Cannot create object");
                    
                            add_content4($xml);
                        }
                        else
                        {
                            echo 'fail';
                        }
                    }
                    else 
                    {
                        echo 'fail';
                    }
                    
                    $polaczenie->close();
                }
                else
                {
                    echo 'fail';
                }
                
                break;
                
            case 5:
                
                //sprawdzenie czy adres email został potwierdzony
                require 'connect.php';
                
                $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
                @mysqli_set_charset($polaczenie,"utf8");
                
                if($polaczenie->connect_errno == 0)
                {  
                    if($result = $polaczenie->query("SELECT email_confirmed FROM users WHERE id = '1'")) 
                    {
                        $row = mysqli_fetch_assoc($result);
                        
                        if($row['email_confirmed'] == 1)
                        {
                            $xml = simplexml_load_file($_COOKIE['oversee_xml_url']) or die("Error: Cannot create object");
                    
                            add_content5($xml);
                        }
                        else
                        {
                            echo 'fail';
                        }
                    }
                    else 
                    {
                        echo 'fail';
                    }
                    
                    $polaczenie->close();
                }
                else
                {
                    echo 'fail';
                }
                
                break;
                
            case 6:
                
                //sprawdzenie czy logo strony zostało zapisane
                require 'connect.php';
                
                $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
                @mysqli_set_charset($polaczenie,"utf8");
                
                if($polaczenie->connect_errno == 0)
                {  
                    if($result = $polaczenie->query("SELECT saved FROM installation WHERE what = 'image'")) 
                    {
                        $row = mysqli_fetch_assoc($result);
                        
                        if($row['saved'] == '1')
                        {
                            $xml = simplexml_load_file($_COOKIE['oversee_xml_url']) or die("Error: Cannot create object");
                    
                            add_content6($xml);
                        }
                        else
                        {
                            echo 'fail';
                        }
                    }
                    else 
                    {
                        echo 'fail';
                    }
                    
                    $polaczenie->close();
                }
                else
                {
                    echo 'fail';
                }
                
                break;
                
            default:
                echo 'fail';
        }
        
        return;
    }
    //wybranie języka instalatora i zapisanie wyboru w ciasteczku
    else if(isset($_POST['lang']))
    {    
        if($_POST['lang'] == 'pl')
        {
            $xml = simplexml_load_file("../xml/instalator.xml") or die("Error: Cannot create object");
        
            setcookie('oversee_xml_url', "../xml/instalator.xml", time() + (1 * 365 * 24 * 60 * 60), "/");
            setcookie('oversee_language', 'pl', time() + (10 * 365 * 24 * 60 * 60), "/");
            
            //funkcja pokazująca pierwszy krok instalacji
            add_content1($xml);
            
            return;
        }
        else if($_POST['lang'] == 'en')
        {
            $xml = simplexml_load_file("../xml/instalator_en.xml") or die("Error: Cannot create object");
        
            setcookie('oversee_xml_url', "../xml/instalator_en.xml", time() + (1 * 365 * 24 * 60 * 60), "/");
            setcookie('oversee_language', 'en', time() + (10 * 365 * 24 * 60 * 60), "/");
            
            //funkcja pokazująca pierwszy krok instalacji
            add_content1($xml);
            return;
        }
    }
    //wybranie i wysłanie treści błędu
    else if(isset($_POST['error_number']))
    {
        $error_number = $_POST['error_number'];
        
        $xml = simplexml_load_file($_COOKIE['oversee_xml_url']) or die("Error: Cannot create object");
        
        switch($error_number) 
        {
            case 'blad1':
                echo $xml->blad1;
                break;
            case 'blad2':
                echo $xml->blad2;
                break;
            case 'blad3':
                echo $xml->blad3;
                break;
            case 'blad4':
                echo $xml->blad4;
                break;
            case 'blad5':
                echo $xml->blad5;
                break;
            case 'blad6':
                echo $xml->blad6;
                break;
            case 'blad7':
                echo $xml->blad7;
                break;
            case 'blad8':
                echo $xml->blad8;
                break;
            case 'blad9':
                echo $xml->blad9;
                break;
            case 'blad10':
                echo $xml->blad10;
                break;
            case 'blad11':
                echo $xml->blad11;
                break;
            case 'blad12':
                echo $xml->blad12;
                break;
            case 'blad13':
                echo $xml->blad13;
                break;
            case 'blad14':
                echo $xml->blad14;
                break;
            case 'blad15':
                echo $xml->blad15;
                break;
            case 'blad16':
                echo $xml->blad16;
                break;
            case 'blad17':
                echo $xml->blad17;
                break;
            case 'blad18':
                echo $xml->blad18;
                break;
            case 'blad19':
                echo $xml->blad19;
                break;
            case 'blad20':
                echo $xml->blad20;
                break;
            case 'blad21':
                echo $xml->blad21;
                break;
            case 'blad22':
                echo $xml->blad22;
                break;
            case 'blad23':
                echo $xml->blad23;
                break;
            case 'blad24':
                echo $xml->blad24;
                break;
            case 'blad25':
                echo $xml->blad25;
                break;
        }
        
        return;
    }
    //funkcja testująca połączenie z serwerem MySQL
    else if(isset($_POST['server_name']) && isset($_POST['username']) && isset($_POST['password']) && !isset($_POST['save_connection']))
    {
        test_connection($_POST['server_name'],$_POST['username'],$_POST['password']);
        return;
    }
    //funkcja zapisująca poprawne dane połączenia w pliku connect.php
    else if(isset($_POST['save_connection']) && isset($_POST['server_name']) && isset($_POST['username']) && isset($_POST['password']))
    {
        $searchF = array('--host--','--username--','--password--');
        $replaceW = array($_POST['server_name'],$_POST['username'],$_POST['password']);

        $file = file_get_contents("connect.php");
        $file = str_replace($searchF, $replaceW, $file);
        
        $fh = fopen("connect.php", 'w');
        if(fwrite($fh, $file) != false)
        {
            echo 'saved';
        }
        else
        {
            echo 'fail';    
        }
        
        fclose($fh);
        
        return;
    }
    //funkcja tworząca bazę danych o podanej nazwie
    else if(isset($_POST['database_name']))
    {
        create_database($_POST['database_name']);
        
        return;
    }
    //walidacja danych użytkownika (super admin), który ma być dodany do bazy danych
    else if(isset($_POST['login']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['name']) && isset($_POST['lastname']) && isset($_POST['specialization']))
    {
        $wszystko_OK = true;
            
        $login = $_POST['login'];
        $name = $_POST['name'];
        $lastname = $_POST['lastname'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $specialization = $_POST['specialization'];
        
        require_once("connect.php");

        $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
        @mysqli_set_charset($polaczenie,"utf8");

        if($polaczenie->connect_errno != 0)
        {  
            echo 'bladpolaczeniazbazadanych';
        }
        else
        {
            //Sprawdzenie czy imię składa się tylko z liter
            if(preg_match('/^[a-ząćęłńóśźż]+$/ui', $name) == false)
            {
                $wszystko_OK = false;
                echo "imietylkozliter";
                return; 
            }

            //Sprawdzenie czy nazwisko składa się tylko z liter
            if(preg_match('/^[a-ząćęłńóśźż]+$/ui', $lastname) == false)
            {
                $wszystko_OK = false;
                echo "nazwiskotylkozliter";
                return; 
            }

            //Sprawdzenie czy login składa się tylko z liter i cyfr
            if(ctype_alnum($login) == false)
            {
                $wszystko_OK = false;
                echo "logintylkozliterinumerow";
                return; 
            }

            //Filtrowanie i sprawdzenie poprawności adresu email
            $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

            if(filter_var($emailB, FILTER_VALIDATE_EMAIL) == false || $emailB != $email)
            {
                $wszystko_OK = false;
                echo "niepoprawnyemail";
                return;
            }

            //Sprawdzenie długości hasła
            if(strlen($password) < 5 || strlen($password) > 30)
            {
                $wszystko_OK = false;
                echo "zladlugoschasla";
                return; 
            }

            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            //Sprawdzenie czy podany email jest już zajęty
            $rezultat = $polaczenie -> query("SELECT id FROM users WHERE email='$email'");

            $ile_maili = $rezultat->num_rows;

            if($ile_maili > 0)
            {
                $wszystko_OK = false;
                echo "zajetymail";
                $polaczenie->close();
                return;
            }

            //Sprawdzenie czy podany login jest już zajęty
            $rezultat = $polaczenie -> query("SELECT id FROM users WHERE login='$login'");

            $ile_loginow = $rezultat->num_rows;

            if($ile_loginow > 0)
            {
                $wszystko_OK = false;
                echo "zajetylogin";
                $polaczenie->close();
                return;
            }

            //Testy walidacyjne przeszły pomyślnie, wkładamy dane do bazy
            if($wszystko_OK == true)
            {
                $znaki = 'ACEFHJKM58NPRTUVWXY4937';

                $kod = '';

                for ($i = 0; $i < 6; $i++) 
                {
                    $kod .= $znaki[rand(0, strlen($znaki) - 1)];
                }
                
                $permissions = 3;

                if($polaczenie->query("INSERT INTO users VALUES(NULL,'$login','$password_hash','$email','$name','$lastname','$specialization','$kod','$permissions',0)"))
                {
                    $wiadomosc = '

                    <html>
                    <head>
                        <title>Aktywuj swoje konto!</title>
                    </head>
                    <body style="text-align: center; background-color: #fff; color: #033E6B; font-size: 1.2em; padding: 30px;">

                        <div style="font-size: 3em; border-bottom: 1px dashed #033E6B; padding: 10px; margin-bottom: 20px;">
                            <span style="color: #033E6B; font-weight: bold;">Oversee</span> <span style="color: #3F92D2">Systems</span>
                        </div>

                        <p>Kod aktywacyjny dla użytkownika <b>' . $login . '</b> to:</p>
                        <p style="padding: 20px; display: block; width: 150px; margin: 0 auto; border: 3px solid #033E6B; color: #fff; text-shadow:-1px -1px 0 #000,  1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000; border-radius: 10px; background-color: #66A3D2; font-size: 2em; "> ' . $kod . '</p>

                        <div style="width: 80%; height: 20px; background-color: #033E6B; color: #fff; margin: 35px auto 0; padding: 20px; display: block; border-radius: 10px; text-shadow:-1px -1px 0 #000,  1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;">Bartosz Kropidłowki | Oversee Systems © '. date("Y") .'</div>

                    </body>
                    </html>

                    ';

                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL,"http://kroptech.net/oversee/php/global_mailer.php");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, 
                                http_build_query(array('sender' => 'instalator', 'code' => 'tvboFoCW', 'author_title' => 'Oversee Systems (no-reply)', 'recipient' => $email, 'subject' => 'Kod potwierdzający dla konta w systemie Oversee.', 'content' => $wiadomosc)));

                    // receive server response ...
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $server_output = curl_exec ($ch);

                    curl_close ($ch);

                    echo 'success';
                }
                else
                {
                    echo "bladdodawania";
                }

                $polaczenie->close();
                
                return;
            }
        }
    }
    //sprawdzanie kodu potwierdzającego email
    else if(isset($_POST['confirm_code']))
    {
        $code = $_POST['confirm_code'];
        
        require_once("connect.php");

        $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
        @mysqli_set_charset($polaczenie,"utf8");

        if($polaczenie->connect_errno != 0)
        {  
            echo 'bladpolaczeniazbazadanych';
        }
        else
        {
            $rezultat = $polaczenie -> query("SELECT code FROM users WHERE id = '1'");
            
            $db_code = mysqli_fetch_assoc($rezultat);
            $db_code = $db_code['code'];
            
            if($code == $db_code)
            {
                if($polaczenie->query("UPDATE users SET email_confirmed = '1' WHERE id = 1"))
                {
                    echo true;
                }
                else
                {
                    echo 'bladpolaczeniazbazadanych'; 
                }
            }
            else
            {
                echo false;
            }
            
            $polaczenie->close();
        }
        
        return;
    }
    //zapisanie adresu url do loga strony
    else if(isset($_POST['logo_source']))
    {
        $logo_url = $_POST['logo_source'];
        
        if(isset($_POST['old_logo_url']))
        {
            $searchF = array($_POST['old_logo_url']);
        }
        else
        {
            $searchF = array('img/oversee-logo.png');    
        }
        
        $replaceW = array($logo_url);
        
        //nadpisywanie w podstronach
        $file = file_get_contents("about.php");
        $file = str_replace($searchF, $replaceW, $file);
        
        $fh = fopen("about.php", 'w');
        fwrite($fh, $file);
        
        $file = file_get_contents("account_settings.php");
        $file = str_replace($searchF, $replaceW, $file);
        
        $fh = fopen("account_settings.php", 'w');
        fwrite($fh, $file);
        
        $file = file_get_contents("admin_panel.php");
        $file = str_replace($searchF, $replaceW, $file);
        
        $fh = fopen("admin_panel.php", 'w');
        fwrite($fh, $file);
        
        //nadpisywanie w stronie domowej
        $file = file_get_contents("home.php");
        $file = str_replace($searchF, $replaceW, $file);
        
        $fh = fopen("home.php", 'w');
        if(fwrite($fh, $file) != false)
        {
            require_once("connect.php");

            $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
            @mysqli_set_charset($polaczenie,"utf8");

            if($polaczenie->connect_errno != 0)
            {  
                echo 'bladpolaczeniazbazadanych';
            }
            else
            {
                if($polaczenie->query("INSERT INTO installation VALUES (NULL,'image','1')"))
                {
                    $xml = simplexml_load_file($_COOKIE['oversee_xml_url']) or die("Error: Cannot create object");
            
                    add_content6($xml);
                }
                else
                {
                    echo 'bladpolaczeniazbazadanych'; 
                }

                $polaczenie->close();
            }
        }
        else
        {
            echo 'fail';    
        }
        
        fclose($fh);
        
        return;
    }
    //zapisanie nazwy strony w pliku
    else if(isset($_POST['page_title']))
    {
        $page_title = $_POST['page_title'];
        
        if(strlen($page_title) < 4 || strlen($page_title) > 50)
        {
            echo 'zladlugosc';
        }
        else
        {
            $searchF = array('--page_title--');
            $replaceW = array($page_title);

            //nadpisywanie w podstronach
            $file = file_get_contents("about.php");
            $file = str_replace($searchF, $replaceW, $file);

            $fh = fopen("about.php", 'w');
            fwrite($fh, $file);

            $file = file_get_contents("account_settings.php");
            $file = str_replace($searchF, $replaceW, $file);

            $fh = fopen("account_settings.php", 'w');
            fwrite($fh, $file);

            $file = file_get_contents("admin_panel.php");
            $file = str_replace($searchF, $replaceW, $file);

            $fh = fopen("admin_panel.php", 'w');
            fwrite($fh, $file);
            
            //nadpisywanie w stronie domowej
            $file = file_get_contents("home.php");
            $file = str_replace($searchF, $replaceW, $file);

            $fh = fopen("home.php", 'w');
            if(fwrite($fh, $file) != false)
            {   
                $xml = simplexml_load_file($_COOKIE['oversee_xml_url']) or die("Error: Cannot create object");

                add_content7($xml);
            }
            else
            {
                echo 'fail';    
            }

            fclose($fh);
        }
        
        return;
    }
    //funkcja wypełniająca kontent kolejnych kroków intalacji
    else if(isset($_POST['content']))
    {
        $xml = simplexml_load_file($_COOKIE['oversee_xml_url']) or die("Error: Cannot create object");
        
        switch($_POST['content'])
        {
            case 'text1':
                add_content1($xml);
                break;
            case 'text2':
                add_content2($xml);
                break;
            case 'text3':
                add_content3($xml);
                break;  
            case 'text4':
                add_content4($xml);
                break;
            case 'text5':
                add_content5($xml);
                break;
            case 'text6':
                add_content6($xml);
                break;
            case 'text7':
                add_content7($xml);
                break;
        }
        
        return;
    }
    
    //podanie danych do połączenia z serwerem MySQL
    function add_content1($xml)
    {
        echo $xml->tekst1 . '
        
        <br />
        <div class="input-group input-group-lg" style="width: 50%; margin: 0 auto; float: none;">
            <span class="input-group-btn">
                <button class="btn btn-info" type="button" data-toggle="modal" data-target="#infoModal1"><span class="glyphicon glyphicon-question-sign"></span></button>
            </span>
            <input id="server_name_input" type="text" class="form-control" placeholder="'. $xml->placeholder1 .'">
        </div>
        
        <div id="infoModal1" class="modal fade" role="dialog">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">'. $xml->placeholder1 .'</h4>
                </div>
            <div class="modal-body">
                <p>'. $xml->modaltekst1 .'</p>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">'. $xml->zamknij .'</button>
                </div>
            </div>
            </div>
        </div>
        
        <br />
        <div class="input-group input-group-lg" style="width: 50%; margin: 0 auto; float: none;">
            <span class="input-group-btn">
                <button class="btn btn-info" type="button" data-toggle="modal" data-target="#infoModal2"><span class="glyphicon glyphicon-question-sign"></span></button>
            </span>
            <input id="username_input" type="text" class="form-control" placeholder="'. $xml->placeholder2 .'">
        </div>
        
        <div id="infoModal2" class="modal fade" role="dialog">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">'. $xml->placeholder2 .'</h4>
                </div>
            <div class="modal-body">
                <p>'. $xml->modaltekst2 .'</p>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">'. $xml->zamknij .'</button>
                </div>
            </div>
            </div>
        </div>
        
        <br />
        <div class="input-group input-group-lg" style="width: 50%; margin: 0 auto; float: none;">
            <span class="input-group-btn">
                <button class="btn btn-info" type="button" data-toggle="modal" data-target="#infoModal3"><span class="glyphicon glyphicon-question-sign"></span></button>
            </span>
            <input id="password_input" type="password" class="form-control" placeholder="'. $xml->placeholder3 .'">
        </div>
        
        <div id="infoModal3" class="modal fade" role="dialog">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">'. $xml->placeholder3 .'</h4>
                </div>
            <div class="modal-body">
                <p>'. $xml->modaltekst3 .'</p>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">'. $xml->zamknij .'</button>
                </div>
            </div>
            </div>
        </div>
        
        <div id="error_alert" class="alert alert-danger" role="alert" style="width: 50%; margin: 20px auto 0; display: none;"></div>
        
        <div id="loading_bar" class="progress" style="width: 30%; margin: 30px auto 0; display: none;">
          <div class="progress-bar progress-bar-warning progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
            <span class="sr-only">100% Complete</span>
          </div>
        </div>
        
        <br/><br/>
        <button id="installation_next_btn" class="btn btn-primary btn-lg" type="button" style="width: 200px;">'. $xml->dalej .'</button>
        
        ';
    }
    
    //Ustalenie nazwy bazy danych
    function add_content2($xml)
    {
        echo $xml->tekst2 . '
        
        <br />
        <div class="input-group input-group-lg" style="width: 50%; margin: 0 auto; float: none;">
            <span class="input-group-btn">
                <button class="btn btn-info" type="button" data-toggle="modal" data-target="#infoModal1"><span class="glyphicon glyphicon-question-sign"></span></button>
            </span>
            <input id="database_name_input" type="text" class="form-control" placeholder="'. $xml->placeholder4 .'">
        </div>
        
        <div id="infoModal1" class="modal fade" role="dialog">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">'. $xml->placeholder4 .'</h4>
                </div>
            <div class="modal-body">
                <p>'. $xml->modaltekst4 .'</p>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">'. $xml->zamknij .'</button>
                </div>
            </div>
            </div>
        </div>
        
        <div id="error_alert" class="alert alert-danger" role="alert" style="width: 50%; margin: 20px auto 0; display: none;"></div>
        
        <br/><br/>
        <button id="installation_next_btn" class="btn btn-primary btn-lg" type="button" style="width: 200px;">'. $xml->dalej .'</button>
        
        ';
    }
    
    //dodanie pierwszego konta, super admina
    function add_content3($xml)
    {
        echo $xml->tekst3 . '
        
        <br />

        <div class="input-group input-group-lg" style="width: 50%; margin: 0 auto; float: none;">
            <input id="login_input" type="text" class="form-control" placeholder="'. $xml->placeholder5 .'" disabled />
        </div>

        <br />

        <div class="input-group input-group-lg" style="width: 50%; margin: 0 auto; float: none;">
            <input id="name_input" onblur="generate_login()" type="text" class="form-control" placeholder="'. $xml->placeholder6 .'" />
        </div>

        <br />

        <div class="input-group input-group-lg" style="width: 50%; margin: 0 auto; float: none;">
            <input id="lastname_input" onblur="generate_login()" type="text" class="form-control" placeholder="'. $xml->placeholder7 .'" />
        </div>

        <br />

        <div class="input-group input-group-lg" style="width: 50%; margin: 0 auto; float: none;">
            <span class="input-group-btn">
                <button class="btn btn-info" type="button" data-toggle="modal" data-target="#infoModal8"><span class="glyphicon glyphicon-question-sign"></span></button>
            </span>
            <input id="password_input" onblur="checkPassword()" type="password" class="form-control" placeholder="'. $xml->placeholder8 .'" />
        </div>
        
        <div id="infoModal8" class="modal fade" role="dialog">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">'. $xml->placeholder8 .'</h4>
                </div>
            <div class="modal-body">
                <p>'. $xml->modaltekst8 .'</p>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">'. $xml->zamknij .'</button>
                </div>
            </div>
            </div>
        </div> 
        
        <br />
        
        <div class="input-group input-group-lg" style="width: 50%; margin: 0 auto; float: none;">
            <input id="password2_input" onblur="checkPassword()" type="password" class="form-control" placeholder="'. $xml->placeholder9 .'" />
        </div>

        <br />

        <div class="input-group input-group-lg" style="width: 50%; margin: 0 auto; float: none;">
            <input id="email_input" type="email" onblur="validateEmail()" class="form-control" placeholder="'. $xml->placeholder10 .'" />
        </div>

        <br />

        <div class="input-group input-group-lg" style="width: 50%; margin: 0 auto; float: none;">
            <label for="specialization_input">'. $xml->placeholder11 .'</label>
            <select class="form-control" id="specialization_input">
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

        <br />

        <div id="error_alert" class="alert alert-danger" role="alert" style="width: 50%; margin: 20px auto 0; display: none;"></div>

        <br/><br/>
        <button id="installation_next_btn" class="btn btn-primary btn-lg" type="button" style="width: 200px;">'. $xml->stworz .'</button>
        ';
    }

    //Potwierdzenie adresu email przez wpisanie kodu z wiadomości
    function add_content4($xml)
    {
        echo $xml->tekst4 . '
        
        <br />
        <div class="input-group input-group-lg" style="width: 50%; margin: 0 auto; float: none;">
            <label for="confirm_code_input">'. $xml->placeholder12 .'</label>
            <input id="confirm_code_input" type="text" class="form-control">
        </div>
        
        <div id="error_alert" class="alert alert-danger" role="alert" style="width: 50%; margin: 20px auto 0; display: none;"></div>
        
        <br/><br/>
        <button id="installation_next_btn" class="btn btn-primary btn-lg" type="button" style="width: 200px;">'. $xml->dalej .'</button>
        
        ';
    }

    //ustawianie loga strony
    function add_content5($xml)
    {
        echo $xml->tekst5 . '
        
        <br />
        
        <label for="set_image_input">'. $xml->placeholder13 .'</label>
        <div class="input-group input-group-lg" style="width: 50%; margin: 0 auto; float: none;">
            <span class="input-group-btn">
                <button class="btn btn-info" type="button" data-toggle="modal" data-target="#infoModal13"><span class="glyphicon glyphicon-question-sign"></span></button>
            </span>
            <input id="set_image_input" onblur="logo_preview()" type="text" class="form-control">
        </div>

        <div id="infoModal13" class="modal fade" role="dialog">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
            <div class="modal-body">
                <p>'. $xml->modaltekst13 .'</p>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">'. $xml->zamknij .'</button>
                </div>
            </div>
            </div>
        </div>
        
        <br/>
        
        '. $xml->podglad .'
        <div id="image_preview_div" style="border: 2px solid #484848; padding: 5px; width: 165px; height: 165px; border-radius: 5px; margin: 10px auto 0;">
        
        </div>
        
        <div id="error_alert" class="alert alert-danger" role="alert" style="width: 50%; margin: 20px auto 0; display: none;"></div>

        <br/><br/>

        <button id="skip_btn" class="btn btn-primary btn-lg" type="button" style="width: 200px; margin-right: 20px;">'. $xml->pomin .'</button>
        <button id="installation_next_btn" class="btn btn-primary btn-lg" type="button" style="width: 200px;" disabled>'. $xml->dalej .'</button>
        
        ';
    }

    //Ustawianie nazwy strony
    function add_content6($xml)
    {
        echo $xml->tekst6 . '
        
        <br />
        
        <div class="input-group input-group-lg" style="width: 50%; margin: 0 auto; float: none;">
            <span class="input-group-btn">
                <button class="btn btn-info" type="button" data-toggle="modal" data-target="#infoModal14"><span class="glyphicon glyphicon-question-sign"></span></button>
            </span>
            <input id="set_page_name_input" type="text" class="form-control">
        </div>

        <div id="infoModal14" class="modal fade" role="dialog">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">'. $xml->placeholder14 .'</h4>
                </div>
            <div class="modal-body">
                <p>'. $xml->modaltekst14 .'</p>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">'. $xml->zamknij .'</button>
                </div>
            </div>
            </div>
        </div>

        <div id="error_alert" class="alert alert-danger" role="alert" style="width: 50%; margin: 20px auto 0; display: none;"></div>

        <br/><br/>

        <button id="installation_next_btn" class="btn btn-primary btn-lg" type="button" style="width: 200px;">'. $xml->dalej .'</button>
        
        ';
    }

    //Zakończenie instalacji
    function add_content7($xml)
    {
        $searchF = array('instalator');
        $replaceW = array('home');

        $file = file_get_contents("../index.php");
        $file = str_replace($searchF, $replaceW, $file);
        
        $fh = fopen("../index.php", 'w');
        if(fwrite($fh, $file) != false)
        {
            echo $xml->tekst7 . '

            <br/><br/>

            <button id="installation_next_btn" class="btn btn-primary btn-lg" type="button" style="width: 350px;">'. $xml->stronaglowna .'</button>

            ';
        }
        
        fclose($fh);
        
        return;
    }

    //funkcja testująca połączenie z serwerem MySQL
    function test_connection($host, $db_user, $db_password)
    {
        $polaczenie = mysqli_connect($host, $db_user, $db_password);

        if($polaczenie)
        {  
            $polaczenie->close();
            echo 'success';
        }
        else
        {
            echo 'fail';
        }
    }
    
    //funkcja tworząca bazę danych
    function create_database($new_db_name)
    {
        $good_to_go = false;
        
        require 'connect.php';
        
        $polaczenie = @mysqli_connect($host, $db_user, $db_password, $new_db_name);
        @mysqli_set_charset($polaczenie,"utf8");

        if($polaczenie)
        {  
            $good_to_go = true;
        }
        else
        {
            $polaczenie = @new mysqli($host, $db_user, $db_password);
            @mysqli_set_charset($polaczenie,"utf8");

            if($polaczenie->connect_errno != 0)
            { 
                echo 'connect_error';
            }
        }
        
        if($good_to_go == false)
        {
            if($polaczenie->query("CREATE DATABASE $new_db_name CHARACTER SET utf8 COLLATE utf8_polish_ci"))
            {
                $good_to_go = true;
            }
            else 
            {
                echo 'creating_db_error';
            }
        }
        
        if($good_to_go == true)
        {
            $searchF = '--database--';
            $replaceW = $new_db_name;

            $file = file_get_contents("connect.php");
            $file = str_replace($searchF, $replaceW, $file);

            $fh = fopen("connect.php", 'w');
            if(fwrite($fh, $file) != false)
            {
                //tworzenie tabeli użytkownicy
                if($polaczenie->query("CREATE TABLE $new_db_name.users ( id INT NOT NULL AUTO_INCREMENT , login TEXT NOT NULL , password TEXT NOT NULL , email TEXT NOT NULL , name TEXT NOT NULL , lastname TEXT NOT NULL , specialization TEXT NOT NULL , code TEXT NOT NULL , permissions TINYINT NOT NULL DEFAULT '1' , email_confirmed TINYINT NOT NULL DEFAULT '0' , PRIMARY KEY (id)) ENGINE = InnoDB")) 
                {
                    //tworzenie podstawowych tabel w bazie danych
                    $polaczenie->query("CREATE TABLE $new_db_name.item_category ( id INT NOT NULL , name TEXT NOT NULL , location TEXT NOT NULL , category TEXT NOT NULL , PRIMARY KEY (id)) ENGINE = InnoDB");

                    $polaczenie->query("CREATE TABLE $new_db_name.installation ( id INT NOT NULL AUTO_INCREMENT , what TEXT NOT NULL, saved TINYINT NOT NULL DEFAULT '0' , PRIMARY KEY (id)) ENGINE = InnoDB");

                    $polaczenie->query("CREATE TABLE $new_db_name.categories ( id INT NOT NULL AUTO_INCREMENT , name TEXT NOT NULL , PRIMARY KEY (id)) ENGINE = InnoDB");

                    //#### tworzenie tabel z kategoriami
                    $polaczenie->query("CREATE TABLE $new_db_name.devices ( id INT NOT NULL , name TEXT NOT NULL , placement TEXT NOT NULL , last_location TEXT NOT NULL , type TEXT NOT NULL , damaged BOOLEAN NOT NULL , PRIMARY KEY (id)) ENGINE = InnoDB");

                    //#### tworzenie tabeli z historią komentarzy dla kategorii "urządzenia"
                    $polaczenie->query("CREATE TABLE $new_db_name.devices_comments_history ( id INT NOT NULL AUTO_INCREMENT , comment TEXT NOT NULL , who_added TEXT NOT NULL , when_added TIMESTAMP NOT NULL , device_id INT NOT NULL , PRIMARY KEY (id)) ENGINE = InnoDB");

                    //#### dodawanie wszystkich kategorii do zbiorczej tabeli
                    $polaczenie->query("INSERT INTO $new_db_name.categories (id, name) VALUES (NULL, 'devices')");

                    echo 'saved';
                }
                else
                {
                    echo 'creating_table_error';
                }
            }
            else
            {
                echo 'saving_error';    
            }

            fclose($fh);

            $polaczenie->close();
        }
    }

?>