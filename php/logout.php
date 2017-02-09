<?php 
        
    session_start();
    
    if($_POST['logout'] == true)
    {  
        //Usuwanie sesji
        session_unset();

        session_destroy();

        session_regenerate_id(); 
        
        echo "success";
    }

?>