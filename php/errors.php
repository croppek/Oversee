<?php
    
    session_start();

    if(isset($_COOKIE['oversee_language']))
    {
        $lang = $_COOKIE['oversee_language'];

        if($lang == 'pl')
        {
            $xml = simplexml_load_file("../xml/bledy.xml"); 
        }
        else if($lang == 'en')
        {
            $xml = simplexml_load_file("../xml/bledy_en.xml"); 
        }
    }
    else
    {
        $xml = simplexml_load_file("../xml/bledy.xml"); 
    }

    if(isset($_POST['error_number']))
    {
        $error_number = $_POST['error_number'];
        
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
            case 'blad26':
                echo $xml->blad26;
                break;
            case 'blad27':
                echo $xml->blad27;
                break;
            case 'blad28':
                echo $xml->blad28;
                break;
            case 'blad29':
                echo $xml->blad29;
                break;
        }
        
        return;
    }
    else if(isset($_POST['confirmation_content']))
    {
            echo '
                
                <form id="confirm_email_form">
                    <p style="text-align: center;">'. $xml->podajemail .'</p>
                    <div class="input-group input-group-md" style="width: 50%; margin: 0 auto; float: none;">
                        <input id="new_email_input" type="email" class="form-control" required>
                        <div class="input-group-btn">
                            <button id="email_help_btn" type="button" class="btn btn-default" aria-label="Help"><span class="glyphicon glyphicon-question-sign"></span></button>
                        </div>
                    </div>

                    <div id="error_alert" class="alert alert-danger" role="alert" style="width: 70%; margin: 20px auto 0; display: none;"></div>
                    <div id="info_alert" class="alert alert-info" role="alert" style="width: 70%; margin: 20px auto 0; display: none;">'. $xml->emailinfo .'</div>

                    <br/>
                    <button id="email_enter_btn" class="btn btn-primary btn-md" type="submit" style="width: 200px; margin: 10px auto 0; display: block;">' . $xml->dalej . '</button>
                </form>
                
                <button id="back_to_login_btn" class="btn btn-primary btn-sm" type="button" style="width: 160px; margin: 10px auto 0; display: block;">'. $xml->powrotdologowania .'</button>

            ';
    }
    else if(isset($_POST['confirmation_content2']) && isset($_POST['new_email']))
    {
        $login = $_SESSION['login'];

        $email = $_POST['new_email'];
        
        $znaki = 'ACEFHJKM58NPRTUVWXY4937';
        $kod = '';

        for ($i = 0; $i < 6; $i++) 
        {
            $kod .= $znaki[rand(0, strlen($znaki) - 1)];
        }
        
        require 'connect.php';

        $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
        @mysqli_set_charset($polaczenie,"utf8");

        if($polaczenie->connect_errno == 0)
        {  
            $polaczenie -> query("UPDATE users SET code = '$kod' WHERE login = '$login'");

            $polaczenie->close();
        }
        else
        {
            echo $xml_errors->blad6;
        }

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
                    http_build_query(array('sender' => 'errors', 'code' => 'WzChrqeB', 'author_title' => 'Oversee Systems (no-reply)', 'recipient' => $email, 'subject' => 'Kod potwierdzający dla konta w systemie Oversee.', 'content' => $wiadomosc)));

        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);

        curl_close ($ch);

        echo '

            <p style="text-align: center;">'. $xml->confirm_label . ' <span id="new_email_holder">' . $email .'</span></p>
            <div class="input-group input-group-lg" style="width: 50%; margin: 0 auto; float: none;">
                <input id="confirm_code_input" type="text" class="form-control" style="text-align: center;">
            </div>

            <div id="error_alert" class="alert alert-danger" role="alert" style="width: 50%; margin: 20px auto 0; display: none;"></div>

            <br/>
            <button id="email_confirm_btn" class="btn btn-primary btn-md" type="button" style="width: 200px; margin: 10px auto 0; display: block;">'. $xml->potwierdz .'</button>

            <button id="back_to_login_btn" class="btn btn-primary btn-sm" type="button" style="width: 160px; margin: 10px auto 0; display: block;">'. $xml->powrotdologowania .'</button>
        ';
    }

?>