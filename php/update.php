<?php

    //napisać funkcję aktualizującą bazę danych, sprawdzającą nazwę, logo i tło i przekazanie jej do funkcji zamieniającej kod w plikach
    update1();

    function update1()
    {
        //uzupełnienie bazy danych o nowe rekordy i tabele
        require 'connect.php';
        
        $polaczenie = @mysqli_connect($host, $db_user, $db_password, $db_name);
        @mysqli_set_charset($polaczenie,"utf8");
        
        //tworzenie nowej kategorii "meble"
        $polaczenie->query("CREATE TABLE furniture ( id INT NOT NULL , placement TEXT NOT NULL , last_location TEXT NOT NULL , type TEXT NOT NULL , damaged BOOLEAN NOT NULL , PRIMARY KEY (id)) ENGINE = InnoDB");

        //#### tworzenie tabeli z historią komentarzy dla kategorii "meble"
        $polaczenie->query("CREATE TABLE furniture_comments_history ( id INT NOT NULL AUTO_INCREMENT , comment TEXT NOT NULL , who_added TEXT NOT NULL , when_added TIMESTAMP NOT NULL , item_id INT NOT NULL , PRIMARY KEY (id)) ENGINE = InnoDB");

        //#### dodawanie kategorii do zbiorczej tabeli
        $polaczenie->query("INSERT INTO categories (id, name) VALUES (NULL, 'furniture')");
        
        $polaczenie->close();
        
        //############################################### 
        
        set_page_attr();
        
        //###############################################
        
        sleep(5);

        $searchF = array('update');
        $replaceW = array('home');

        $file = file_get_contents("index.php");
        $file = str_replace($searchF, $replaceW, $file);

        $fh = fopen("index.php", 'w');
        if(fwrite($fh, $file) != false)
        {
            header('Location: ./');
        }
    }

    function set_page_attr()
    {    
        require 'connect.php';
        
        $polaczenie = @mysqli_connect($host, $db_user, $db_password, $db_name);
        @mysqli_set_charset($polaczenie,"utf8");
        
        if($result = $polaczenie->query("SELECT info FROM page_info WHERE type='name'")) 
        { 
            $row = mysqli_fetch_assoc($result);
                                
            $name = $row['info'];
            
            send_to_instalator('page_title', $name);
        }
        
        sleep(1);
        
        if($result = $polaczenie->query("SELECT info FROM page_info WHERE type='logo'"))
        {
            $row = mysqli_fetch_assoc($result);

            $logo = $row['info'];

            send_to_instalator('logo_source', $logo);
        }
    }

    function send_to_instalator($param, $value)
    {    
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/php/instalator_backend.php';
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,'http://'.$_SERVER['HTTP_HOST'].'/php/instalator_backend.php');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 
                    http_build_query(array($param => $value)));

        curl_exec ($ch);
        
        if(curl_error($ch))
        {
            echo 'CURL error: ' . curl_error($ch);
        }
        
        curl_close ($ch);
    }

?>