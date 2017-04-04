<?php

    $lang = 'pl';

    if(isset($_COOKIE['oversee_language']))
    {
        $lang = $_COOKIE['oversee_language'];
        
        if($lang == 'pl')
        {
            //wczytanie plików z tesktami w odpowiednim języku
            if(isset($file_homepage) && $file_homepage === true)
            {
                $xml = simplexml_load_file("xml/stronaglowna.xml");    
            }
            else if(isset($file_about) && $file_about === true)
            {
                $xml = simplexml_load_file("../xml/oprojekcie.xml");  
            }
            else if(isset($file_usersettings) && $file_usersettings === true)
            {
                $xml = simplexml_load_file("../xml/ustawienia_konta.xml");  
            }
            else if(isset($file_adminpanel) && $file_adminpanel === true)
            {
                $xml = simplexml_load_file("../xml/panel_admina.xml");  
            }
            
            //wczytanie pliku z błędami
            if(isset($file_homepage) && $file_homepage === true)
            { 
                $xml_errors = simplexml_load_file("xml/bledy.xml");   
            }
            else
            {
                $xml_errors = simplexml_load_file("../xml/bledy.xml");
            }
            
            $support_location = "img/support-btn-pl.png";
            
            $link1 = './o-projekcie';
            $link2 = './ustawienia-konta';
            $link3 = './panel-administracyjny';
        }
        else if($lang == 'en')
        {
            //wczytanie plików z tesktami w odpowiednim języku
            if(isset($file_homepage) && $file_homepage === true)
            {
                $xml = simplexml_load_file("xml/stronaglowna_en.xml");    
            }
            else if(isset($file_about) && $file_about === true)
            {
                $xml = simplexml_load_file("../xml/oprojekcie_en.xml");  
            }
            else if(isset($file_usersettings) && $file_usersettings === true)
            {
                $xml = simplexml_load_file("../xml/ustawienia_konta_en.xml");  
            }
            else if(isset($file_adminpanel) && $file_adminpanel === true)
            {
                $xml = simplexml_load_file("../xml/panel_admina_en.xml");  
            }
            
            //wczytanie pliku z błędami
            if(isset($file_homepage) && $file_homepage === true)
            { 
                $xml_errors = simplexml_load_file("xml/bledy_en.xml");   
            }
            else
            {
                $xml_errors = simplexml_load_file("../xml/bledy_en.xml");
            }
            
            $support_location = "img/support-btn-en.png";
            
            $link1 = './about-project';
            $link2 = './account-settings';
            $link3 = './administration-panel';
        }
    }
    else
    {
        //wczytanie plików z tesktami w odpowiednim języku
        if(isset($file_homepage) && $file_homepage === true)
        {
            $xml = simplexml_load_file("xml/stronaglowna.xml");    
        }
        else if(isset($file_about) && $file_about === true)
        {
            $xml = simplexml_load_file("../xml/oprojekcie.xml");  
        }
        else if(isset($file_usersettings) && $file_usersettings === true)
        {
            $xml = simplexml_load_file("../xml/ustawienia_konta.xml");  
        }
        else if(isset($file_adminpanel) && $file_adminpanel === true)
        {
            $xml = simplexml_load_file("../xml/panel_admina.xml");  
        }

        //wczytanie pliku z błędami
        if(isset($file_homepage) && $file_homepage === true)
        { 
            $xml_errors = simplexml_load_file("xml/bledy.xml");   
        }
        else
        {
            $xml_errors = simplexml_load_file("../xml/bledy.xml");
        }
        
        $support_location = "img/support-btn-pl.png";
        
        $link1 = './o-projekcie';
        $link2 = './ustawienia-konta';
        $link3 = './panel-administracyjny';
    }

?>