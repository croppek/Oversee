<?php

    session_start();

    require_once("connect.php");
    
    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
    @mysqli_set_charset($polaczenie,"utf8");
    
    if($polaczenie->connect_errno != 0)
    {  
        echo 'bladpolaczeniazbaza';
    }
    else
    {   
        if(isset($_POST['login']) && isset($_POST['haslo']))
        {
            $login = $_POST['login'];
            $haslo = $_POST['haslo'];

            //Zapobieganie wstrzykiwania SQL w polu loginu
            $login = htmlentities($login, ENT_QUOTES, "UTF-8");

            if($rezultat = @$polaczenie->query(sprintf("SELECT * FROM users WHERE login='%s'", mysqli_real_escape_string($polaczenie, $login))))
            {
                $ilu_userow = $rezultat -> num_rows;

                if($ilu_userow > 0)
                {
                    $wiersz = $rezultat -> fetch_assoc();

                    //Porównywanie podanego hasła z zahashowanym w bazie
                    if(password_verify($haslo, $wiersz['password']))
                    {
                        $_SESSION['login'] = $wiersz['login'];

                        $email_activated = $polaczenie -> query("SELECT email_confirmed FROM users WHERE login = '$login'");

                        $wynik = $email_activated -> fetch_assoc();

                        $confirmation = $wynik['email_confirmed'];

                        if($confirmation == '1')
                        {
                            $_SESSION['permissions'] = $wiersz['permissions'];
                            $_SESSION['specialization'] = $wiersz['specialization'];
                            $_SESSION['email'] = $wiersz['email'];
                            $_SESSION['name'] = $wiersz['name'];
                            $_SESSION['lastname'] = $wiersz['lastname'];
                            $_SESSION['zalogowany'] = true;

                            echo 'success';
                        }
                        else
                        {
                            echo "potwierdzmail";
                        }
                    }
                    else
                    {
                        echo "nieprawidlowehaslo";
                    }
                }
                else
                {
                    echo "brakuzytkownika";
                }
            }
        }
        else if(isset($_POST['confirmation_code']) && isset($_POST['new_email']))
        {
            $code = $_POST['confirmation_code'];
            $new_email = $_POST['new_email'];
            $login = $_SESSION['login'];
            
            $rezultat = $polaczenie -> query("SELECT code FROM users WHERE login = '$login'");
            
            $db_code = mysqli_fetch_assoc($rezultat);
            $db_code = $db_code['code'];
            
            if($db_code == $code)
            {
                if($polaczenie -> query("UPDATE users SET email_confirmed = '1' WHERE login = '$login'"))
                {
                    if($polaczenie -> query("UPDATE users SET email = '$new_email' WHERE login = '$login'"))
                    {
                        echo 'confirmed';
                    }
                }
            }
            else
            {
                echo 'fail';
            }
        }
        else if(isset($_POST['old_password']) && isset($_POST['new_password']))
        {
            $old_password = $_POST['old_password'];
            $new_password = $_POST['new_password'];
            $email = $_SESSION['email'];
            $login = $_SESSION['login'];
            $login = htmlentities($login, ENT_QUOTES, "UTF-8");
            
            if($rezultat = @$polaczenie->query(sprintf("SELECT password FROM users WHERE login='%s'", mysqli_real_escape_string($polaczenie, $login))))
            {
                $wiersz = $rezultat -> fetch_assoc();
                
                //Porównywanie podanego hasła z zahashowanym w bazie
                if(password_verify($old_password, $wiersz['password']))
                {
                    $znaki = 'ACEFHJKM58NPRTUVWXY4937';
                    $kod = '';

                    for ($i = 0; $i < 6; $i++) 
                    {
                        $kod .= $znaki[rand(0, strlen($znaki) - 1)];
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

                        <p>Kod potwierdzający zmianę hasła dla użytkownika <b>' . $login . '</b> to:</p>
                        <p style="padding: 20px; display: block; width: 150px; margin: 0 auto; border: 3px solid #033E6B; color: #fff; text-shadow:-1px -1px 0 #000,  1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000; border-radius: 10px; background-color: #66A3D2; font-size: 2em; "> ' . $kod . '</p>

                        <div style="width: 80%; height: 20px; background-color: #033E6B; color: #fff; margin: 35px auto 0; padding: 20px; display: block; border-radius: 10px; text-shadow:-1px -1px 0 #000,  1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;">Bartosz Kropidłowki | Oversee Systems © '. date("Y") .'</div>

                    </body>
                    </html>

                    ';

                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL,"http://kroptech.net/oversee/php/global_mailer.php");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, 
                                http_build_query(array('sender' => 'account_settings', 'code' => '4QgdtZ4t', 'author_title' => 'Oversee Systems (no-reply)', 'recipient' => $email, 'subject' => 'Kod potwierdzający dla konta w systemie Oversee.', 'content' => $wiadomosc)));

                    // receive server response ...
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $server_output = curl_exec ($ch);

                    curl_close ($ch);
                    
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                    
                    $_SESSION['new_password_code'] = $kod;
                    $_SESSION['new_password'] = $password_hash;

                    echo 'success';
                }
                else
                {
                    echo "nieprawidlowehaslo";
                }
            }
        }
        else if(isset($_POST['conf_code']))
        {
            $code = $_POST['conf_code'];
            $login = $_SESSION['login'];
            
            if($code == $_SESSION['new_password_code'])
            {
                $new_password = $_SESSION['new_password'];
                
                if($polaczenie -> query("UPDATE users SET password = '$new_password' WHERE login = '$login'"))
                {
                    echo 'success';
                    
                    unset($_SESSION['new_password']);
                    unset($_SESSION['new_password_code']);
                }
            }
            else
            {
                echo 'nieprawidlowykod';
            }
        }
        
        $polaczenie->close();
    }

?>