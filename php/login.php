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
        
        $polaczenie->close();
    }

?>