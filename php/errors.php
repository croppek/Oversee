<?php
    
    session_start();

    if(isset($_COOKIE['language']))
    {
        $lang = $_COOKIE['language'];

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
        }
        
        return;
    }
    else if(isset($_POST['confirmation_content']))
    {
        require_once("connect.php");
    
        $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
        @mysqli_set_charset($polaczenie,"utf8");

        if($polaczenie->connect_errno != 0)
        {  
            echo 'bladpolaczeniazbaza';
        }
        else
        {
            $login = $_SESSION['login'];

            $rezultat = $polaczenie -> query("SELECT email FROM users WHERE login = '$login'");

            $email = mysqli_fetch_assoc($rezultat);
            $email = $email['email'];

            echo '

                <p style="text-align: center;">'. $xml->confirm_label . ' ' . $email .'</p>
                <div class="input-group input-group-lg" style="width: 50%; margin: 0 auto; float: none;">
                    <input id="confirm_code_input" type="text" class="form-control" style="text-align: center;">
                </div>

                <div id="error_alert" class="alert alert-danger" role="alert" style="width: 50%; margin: 20px auto 0; display: none;"></div>

                <br/>
                <button id="email_confirm_btn" class="btn btn-primary btn-md" type="button" style="width: 200px; margin: 10px auto 0; display: block;">'. $xml->potwierdz .'</button>
                
                <button id="back_to_login_btn" class="btn btn-primary btn-sm" type="button" style="width: 160px; margin: 10px auto 0; display: block;">'. $xml->powrotdologowania .'</button>

            ';
        }
    }

?>