<?php

    $logged = false;
    $permissions = 0;

    if(isset($_SESSION['zalogowany']))
    {
        $logged = $_SESSION['zalogowany'];
        
        if($logged == true)
        {
            $permissions = $_SESSION['permissions'];
        }
    }

?>