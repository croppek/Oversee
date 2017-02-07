<?php

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
    
    if(isset($id))
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
    }
    else
    {
        echo '
        
            <table class="table" align="center" style="width: 95%;">
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