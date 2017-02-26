<?php

    if(isset($_POST['save_cookie']))
    {
        //ciasteczko zapisane na rok
        if(setcookie('oversee_cookies', 'accepted',time() + (1 * 365 * 24 * 60 * 60), "/"))
        {
            echo 'success';   
        }
    }
    else if(isset($_POST['update_checked']))
    {
        session_start();
        
        $_SESSION['update_checked'] = true;
        
        echo 'success';
    }

?>