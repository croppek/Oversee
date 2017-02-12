<?php

    //#### funkcja wypisująca dane od urządzeniu w okienkach
    function devices($tytul, $content)
    {
        echo '

        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title" style="text-align: center;">'. $tytul .'</h3>
                </div>
                <div class="panel-body">
                    '. $content .'
                </div>
            </div>
        </div>';
    }
    
    //tabela pokazująca historię dodanych komentarzy do danego urządzenia
    if(isset($_GET['history']) && isset($id))
    {  
        if($result = $polaczenie->query("SELECT name FROM $category WHERE id='$id'")) 
        {
            $row = mysqli_fetch_assoc($result);

            $nazwa = $row['name'];
        }

        if($result = $polaczenie->query("SELECT * FROM devices_comments_history WHERE device_id='$id' ORDER BY when_added DESC")) 
        {
            echo '

                <table class="table table-striped" align="center" style="width: 95%;">
                    <tr>
                        <th>'.$xml->urzadzenie.'</th>
                        <th style="width: 50%;">'.$xml->uwagi.'</th>
                        <th>'.$xml->ktododal.'</th>
                        <th>'.$xml->kiedy.'</th>
                    </tr>';

                    while($row = mysqli_fetch_array($result))
                    {   
                        echo '

                            <tr>
                                <td><a href="./?id='.$id.'">'.$nazwa.'</a></td>
                                <td>'.$row['comment'].'</td>
                                <td>'.$row['who_added'].'</td>
                                <td style="word-spacing: 15px;"><b>'.$row['when_added'].'</b></td>
                            </tr>
                        ';
                    }  

            echo '</table>';
        }
    }
    //wypisywanie informacji o urządzeniu z użyciem w/w funkcji
    else if(isset($id))
    {
        $id = $row['id'];
        $name = $row['name'];
        $placement = $row['placement'];
        $last_location = $row['last_location'];
        $type = $row['type'];
        $comments = $row['comments'];
        $damaged = $row['damaged'];

        if($damaged == 0)
        {
            $damaged = $xml->nie;
        }
        else
        {
            $damaged = $xml->tak;
        }

        if($comments == '')
        {
            $comments = $xml->brak;
        }

        devices('ID',$id);
        devices($xml->nazwa,$name);
        devices($xml->ulokowanie,$placement);
        devices($xml->ostatnialokalizacja,$last_location);
        devices($xml->typ,$type);
        devices($xml->uwagi,$comments);
        devices($xml->uszkodzony,$damaged);
        
        if($result = $polaczenie->query("SELECT * FROM devices_comments_history WHERE device_id='$id'")) 
        {
            $ile_komentarzy = $result->num_rows;
        }
        
        if($ile_komentarzy > 0)
        {
            echo '
            <div class="col-md-12">
                <a href="./?history&id='.$id.'"><button class="btn btn-primary btn-lg" style="display: block; margin: 0 auto;">'. $xml->pokazhistorieuwag .'&nbsp;&nbsp;<span class="badge">'.$ile_komentarzy.'</span></button></a>
            </div>';   
        }
    }
    //tabela z wszystkimi urządzeniami w kategorii "urządzenia"
    else
    {
        echo '
        
            <table class="table table-striped" align="center" style="width: 95%;">
                <tr>
                    <th>ID</th>
                    <th>'.$xml->nazwa.'</th>
                    <th>'.$xml->ulokowanie.'</th>
                    <th>'.$xml->typ.'</th>
                    <th>'.$xml->uszkodzony.'</th>
                </tr>';
        
                while($row = mysqli_fetch_array($result))
                {
                    if($row['damaged'] == 0)
                    {
                        $damaged = $xml->nie;
                    }
                    else
                    {
                        $damaged = $xml->tak;
                    }
                    
                    echo '
                    
                        <tr>
                            <td><a href="./?id='.$row['id'].'">'.$row['id'].'</a></td>
                            <td><a href="./?id='.$row['id'].'">'.$row['name'].'</a></td>
                            <td>'.$row['placement'].'</td>
                            <td>'.$row['type'].'</td>
                            <td>'.$damaged.'</td>
                        </tr>
                    ';
                }  
        
        echo '</table>';
    }

?>