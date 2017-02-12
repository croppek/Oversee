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
                            /*
                            #########Wysyłanie maila przez api

                            $kod = $wiersz['confirmation_code'];
                            $dokogo = $wiersz['email'];
                            $temat = "Kod aktywacyjny dla konta w serwisie DestinyFlip.";

                            $wiadomosc = '
                            <html>
                            <head>
                                <title>Aktywuj swoje konto!</title>
                            </head>
                            <body style="text-align: center; background-color: rgba(25, 30, 39, 1); color: #fff; font-size: 1.2em; padding: 30px;">

                                <div style="font-size: 3em; border-bottom: 1px dashed rgba(206, 192, 132, 0.7); padding: 10px; margin-bottom: 20px;">
                                    Destiny<b><span style="color: rgba(206, 192, 132, 0.9);">Flip</span></b>
                                </div>

                                <p>Kod aktywacyjny dla użytkownika ' . $login_r . ' to:</p>
                                <p style="padding: 20px; display: block; width: 150px; margin: 0 auto; border: 3px solid rgba(206, 192, 132, 0.7); border-radius: 10px; background-color: rgba(96, 96, 96, 0.88); font-size: 2em; "> ' . $kod . '</p>

                                <div style="width: 80%; height: 20px; background-color: rgb(52, 63, 82); margin: 35px auto 0; padding: 20px; display: block; border-radius: 10px;">DestinyFlip © 2016 | Wszelkie prawa zastrzeżone</div>

                            </body>
                            </html>
                            ';

                            require 'PHPMailerAutoload.php';
                            require_once('class.phpmailer.php');    // dodanie klasy phpmailer
                            require_once('class.smtp.php');    // dodanie klasy smtp

                            $mail = new PHPMailer();    //utworzenie nowej klasy phpmailer
                            $mail->CharSet="UTF-8";
                            $mail->From = "no-reply@destinyflip.net";    //Pełny adres e-mail
                            $mail->FromName = "No-reply DestinyFlip";    //imię i nazwisko lub nazwa użyta do wysyłania wiadomości
                            $mail->Host = "s06.emailserver.pl";    //adres serwera SMTP wysyłającego e-mail
                            $mail->Mailer = "smtp";    //do wysłania zostanie użyty serwer SMTP
                            $mail->SMTPAuth = true;    //włączenie autoryzacji do serwera SMTP
                            $mail->Username = "support@destinyflip.com.pl";    //nazwa użytkownika do skrzynki e-mail
                            $mail->Password = "SuperHaslo123@";    //hasło użytkownika do skrzynki e-mail
                            $mail->Port = 587; //port serwera SMTP 
                            $mail->Subject = $temat;    //Temat wiadomości, można stosować zmienne i znaczniki HTML
                            //$mail->Body = $wiadomosc;    //Treść wiadomości, można stosować zmienne i znaczniki HTML
                            $mail->MsgHTML($wiadomosc);
                            $mail->SMTPAutoTLS = false;   //wyłączenie TLS
                            $mail->SMTPSecure = '';    // 
                            $mail->AddAddress ($dokogo);    //adres skrzynki e-mail oraz nazwa adresata, do którego trafi wiadomość

                            $mail->Send();*/

                            echo "potwierdzmail";

                            /*$headers = "MIME-Version: 1.0" . "\r\n";
                            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                            $headers .= 'From: <no-reply@destinyflip.net>' . "\r\n";

                            mail($dokogo,$temat,$wiadomosc,$headers);*/
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
        else if(isset($_POST['confirmation_code']))
        {
            $code = $_POST['confirmation_code'];
            $login = $_SESSION['login'];
            
            $rezultat = $polaczenie -> query("SELECT code FROM users WHERE login = '$login'");
            
            $db_code = mysqli_fetch_assoc($rezultat);
            $db_code = $db_code['code'];
            
            if($db_code == $code)
            {
                $polaczenie -> query("UPDATE users SET email_confirmed = '1' WHERE login = '$login'");
                
                echo 'confirmed';
            }
            else
            {
                echo 'fail';
            }
        }
        
        $polaczenie->close();
    }

?>