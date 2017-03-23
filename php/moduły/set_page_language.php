<?php

    $lang = $_POST['language'];

    if($lang == 'PL')
    {
        setcookie('oversee_language', 'pl', time() + (10 * 365 * 24 * 60 * 60), "/");
    }
    else if($lang == 'EN')
    {
        setcookie('oversee_language', 'en', time() + (10 * 365 * 24 * 60 * 60), "/");
    }

    return;

?>