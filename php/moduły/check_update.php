<?php

    if($permissions >= 3)
    {            
        if(!isset($_SESSION['update_checked']))
        {
            if(isset($file_homepage) && $file_homepage === true)
            {
                $file_dir = "php/wersja.txt";
            }
            else
            {
                $file_dir = "wersja.txt";
            }
            
            //odczyt aktualnej wersji z pliku
            $myfile = fopen($file_dir, "r") or die("Unable to open file!");
            $actual_version = fread($myfile,filesize($file_dir));
            fclose($myfile);

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL,"http://oversee.zspwrzesnia.pl/php/check_update.php");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 
                        http_build_query(array('version' => $actual_version)));

            // receive server response ...
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $server_output = curl_exec ($ch);

            curl_close ($ch);

            if($server_output == 'update')
            {
                echo '
                <div id="system_update">

                        <p style="font-weight: bold; padding: 10px 10px 0 10px; text-align: center;">'.$xml->updatetekst.'</p>

                        <div id="update_btns_wrap" class="btn-group-sm">

                            <a href="http://oversee.zspwrzesnia.pl" target="_blank" class="btn btn-info" role="button" style="margin-right: 40px;">'. $xml->aktualizuj .'</a>
                            <button id="nieteraz_update_btn" class="btn btn-info">'. $xml->nieteraz .'</button>

                        </div>

                </div>';   
            }
        }
    }

?>