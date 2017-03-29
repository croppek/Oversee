<?php

    //jeśli istnieje któraś ze zmiennych, wyświetlam konkretne informację zamiast strony głównej
    if(isset($_GET['id']) || isset($_GET['category']) || isset($_GET['history']) || isset($_GET['name']) || isset($_GET['location']))
    {
        //odczytywanie i wyświetlanie danych z historią komentarzy 
        if(isset($_GET['history']))
        {
            $id = $_GET['id'];

            $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
            @mysqli_set_charset($polaczenie,"utf8");

            if($polaczenie->connect_errno == 0)
            {  
                if($result = $polaczenie->query("SELECT category FROM item_category WHERE id='$id'")) 
                {
                    if($result->num_rows > 0) 
                    {
                        $row = mysqli_fetch_assoc($result);

                        $category = $row['category'];

                        $category_dir = 'php/kategorie/'.$category.'.php';

                        include $category_dir;
                    }
                    else
                    {
                        echo $xml->brakwyniku;
                    }
                }
                else 
                {
                    echo $xml_errors->blad6;
                }

                $polaczenie->close();
            }
            else
            {
                echo $xml_errors->blad6;
            }
        }
        //odczytywanie i wyświetlanie danych o danych przedmiocie
        else if(isset($_GET['id']))
        {
            $id = $_GET['id'];

            echo '<div id="item_id_holder" style="display: none;">'.$id.'</div>';

            $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
            @mysqli_set_charset($polaczenie,"utf8");

            if($polaczenie->connect_errno == 0)
            {  
                if($result = $polaczenie->query("SELECT category FROM item_category WHERE id='$id'")) 
                {
                    if($result->num_rows > 0) 
                    {
                        $row = mysqli_fetch_assoc($result);

                        $category = $row['category'];

                        echo '<div id="item_category_holder" style="display: none;">'.$category.'</div>';

                        if($result = $polaczenie->query("SELECT * FROM $category WHERE id='$id'")) 
                        {
                            $row = mysqli_fetch_assoc($result);

                            $category_dir = 'php/kategorie/'.$category.'.php';

                            include $category_dir;
                        }
                    }
                    else
                    {
                        echo $xml->brakwyniku;

                        if($logged == true && $permissions >= 3)
                        {
                            echo '
                            <div class="col-md-12">
                                <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#add_to_db_modal1" style="display: block; margin: 20px auto 0;">'. $xml->dodajdobazy .'</button>
                            </div>';
                        }
                    }
                }
                else 
                {
                    echo $xml_errors->blad6;
                }

                $polaczenie->close();
            }
            else
            {
                echo $xml_errors->blad6;
            }
        }
        //odczytywanie i wyświetlanie danych o danej kategorii
        else if(isset($_GET['category']))
        {
            $category = $_GET['category'];

            $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
            @mysqli_set_charset($polaczenie,"utf8");

            if($polaczenie->connect_errno == 0)
            {  
                if($result = $polaczenie->query("SELECT * FROM $category ORDER BY id ASC")) 
                {
                    if($result->num_rows > 0) 
                    {
                        $all_items_in_category = $result->num_rows; 

                        $category_dir = 'php/kategorie/'.$category.'.php';

                        include $category_dir;
                    }
                    else
                    {
                        echo $xml->brakwkategorii;
                    }
                }
                else 
                {
                    echo $xml->kategorianieinstnieje;
                }

                $polaczenie->close();
            }
            else
            {
                echo $xml_errors->blad6;
            }
        }
        //odczytywanie i wyświetlanie przedmiotów o podanej nazwie
        else if(isset($_GET['name']))
        {
            $name = $_GET['name'];

            $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
            @mysqli_set_charset($polaczenie,"utf8");

            if($polaczenie->connect_errno == 0)
            {  
                if($result = $polaczenie->query("SELECT * FROM item_category WHERE LOWER(name) LIKE LOWER('%$name%') ORDER BY name ASC")) 
                {
                    if($result->num_rows > 0)
                    {
                        echo '<h3 style="text-align: center; padding: 0 10px 0;">'.$xml->przedmiotyopodanejnazwie.' ('.$result->num_rows.'):</h3>

                        <br/>
                        <div id="table_wrapper" style="width: 90%; margin: 0 auto; float: none;">
                            <table class="table-striped rtable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>'.$xml->nazwa.'</th>
                                    <th>'.$xml->ulokowanie.'</th>
                                    <th>'.$xml->kategoria.'</th>

                                </tr></thead><tbody>';

                                while($row = mysqli_fetch_array($result))
                                {   
                                    $category = $row['category'];

                                    echo '

                                        <tr>
                                            <td><a href="./?id='.$row['id'].'">'.$row['id'].'</a></td>
                                            <td style="min-width: 100px; text-align: center; white-space: normal;"><a href="./?id='.$row['id'].'">'.$row['name'].'</a></td>
                                            <td style="min-width: 100px; text-align: center; white-space: normal;">'.$row['location'].'</td>
                                            <td style="min-width: 100px; text-align: center; white-space: normal;"><a href="./?category='.$row['category'].'">'.$xml->$category.'</a></td>
                                        </tr>';
                                }  

                        echo '</tbody></table></div>';   
                    }
                    else
                    {
                        echo '<h2 style="text-align: center;">'.$xml->brakprzedmiotowonazwie.'</h2>';
                    }
                }
                else 
                {
                    echo $xml_errors->blad6;
                }

                $polaczenie->close();
            }
            else
            {
                echo $xml_errors->blad6;
            }
        }
        //odczytywanie i wyświetlanie przedmiotów o podanej lokalizacji
        else if(isset($_GET['location']))
        {
            $location = $_GET['location'];

            $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
            @mysqli_set_charset($polaczenie,"utf8");

            if($polaczenie->connect_errno == 0)
            {  
                if($result = $polaczenie->query("SELECT * FROM item_category WHERE LOWER(location) LIKE LOWER('%$location%') ORDER BY name ASC")) 
                {
                    if($result->num_rows > 0)
                    {
                        echo '<h3 style="text-align: center; padding: 0 10px 0;">'.$xml->przedmiotyopodanejlokacji.' ('.$result->num_rows.'):</h3>

                        <br/>
                        <div id="table_wrapper" style="width: 90%; margin: 0 auto; float: none;">
                            <table class="table-striped rtable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>'.$xml->nazwa.'</th>
                                    <th>'.$xml->ulokowanie.'</th>
                                    <th>'.$xml->kategoria.'</th>

                                </tr></thead><tbody>';

                                while($row = mysqli_fetch_array($result))
                                {   
                                    $category = $row['category'];

                                    echo '

                                        <tr>
                                            <td><a href="./?id='.$row['id'].'">'.$row['id'].'</a></td>
                                            <td style="min-width: 100px; text-align: center; white-space: normal;"><a href="./?id='.$row['id'].'">'.$row['name'].'</a></td>
                                            <td style="min-width: 100px; text-align: center; white-space: normal;">'.$row['location'].'</td>
                                            <td style="min-width: 100px; text-align: center; white-space: normal;"><a href="./?category='.$row['category'].'">'.$xml->$category.'</a></td>
                                        </tr>';
                                }  

                        echo '</tbody></table></div>';   
                    }
                    else
                    {
                        echo '<h2 style="text-align: center;">'.$xml->brakprzedmiotowzlokacji.'</h2>';
                    }
                }
                else 
                {
                    echo $xml_errors->blad6;
                }

                $polaczenie->close();
            }
            else
            {
                echo $xml_errors->blad6;
            }
        }
    }
    else
    {
        //wyświetlanie najnowszych komentarzy dla administratora i super administratora
        if($permissions >= 2)
        {
            echo  '

            <div class="col-md-12">
                <div class="jumbotron" style="max-height: 350px; overflow: auto; padding-top: 0;">';

                //#################################################### TUTAJ ZMIENIAĆ KATEGORIE ########################################################
                $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
                @mysqli_set_charset($polaczenie,"utf8");

                if($polaczenie->connect_errno == 0)
                {  
                    //if($result = $polaczenie->query("SELECT * FROM devices_comments_history UNION SELECT * FROM innakategoria UNION SELECT * FROM innakategoria ORDER BY when_added DESC LIMIT 25")) 
                    if($result = $polaczenie->query("SELECT * FROM devices_comments_history ORDER BY when_added DESC LIMIT 25")) 
                    {
                        echo '<h3 style="text-align: center;">'.$xml->najnowszekomentarze.'</h3>';

                        if($result->num_rows > 0)
                        {
                            echo '<div id="table_wrapper" style="width: 100%; margin: 0 auto; float: none;">
                                <table class="table-striped rtable">
                                <thead>
                                    <tr>
                                        <th>'.$xml->przedmiot.'</th>
                                        <th>'.$xml->kategoria.'</th>
                                        <th>'.$xml->komentarz.'</th>
                                        <th>'.$xml->ktododal.'</th>
                                        <th>'.$xml->kiedy.'</th>

                                    </tr></thead><tbody>';

                                    while($row = mysqli_fetch_array($result))
                                    {
                                        $item_id = $row['item_id'];
                                        $category = '';
                                        $nazwa = '';

                                        if($result2 = $polaczenie->query("SELECT category FROM item_category WHERE id='$item_id'")) 
                                        {
                                            $row2 = mysqli_fetch_assoc($result2);

                                            $category = $row2['category'];
                                        }

                                        if($result3 = $polaczenie->query("SELECT name FROM $category WHERE id='$item_id'")) 
                                        {
                                            $row3 = mysqli_fetch_assoc($result3);

                                            $nazwa = $row3['name'];
                                        }

                                        echo '

                                            <tr>
                                                <td style="min-width: 100px; text-align: center; white-space: normal;"><a href="./?id='.$item_id.'">'.$nazwa.'</a></td>
                                                <td style="min-width: 100px; text-align: center; white-space: normal;"><a href="./?category='.$category.'">'.$xml->$category.'</a></td>
                                                <td style="max-width: 820px; min-width: 400px; word-break: break-all; white-space: normal;">'.$row['comment'].'</td>
                                                <td>'.$row['who_added'].'</td>
                                                <td style="word-spacing: 15px;"><b>'.$row['when_added'].'</b></td>
                                            </tr>';
                                    }  

                            echo '</tbody></table></div>';
                        }
                        else
                        {
                            echo '<h2 style="text-align: center;">'.$xml->brakkomentarzy.'</h2>';
                        }
                    }
                    else 
                    {
                        echo mysqli_error($polaczenie);
                    }
                }

            echo '</div>
            </div>
            ';
        }    

        //wyszukiwanie według numeru ID, nazwy oraz kategorii
        echo  '

        <div class="col-md-6">
            <div class="jumbotron">

                <h2 style="text-align: center; margin-bottom: 15px;">' . $xml->content1 . '</h2>

                <form method="get">
                    <div id="search_input_group" class="input-group" style="width: 65%; margin: 0 auto;"> 
                        <input id="id_number_input" class="form-control" name="id" type="number" min="1" value="1" style="text-align: center;" aria-label="Text input with multiple buttons"> 
                        <div class="input-group-btn"> 
                            <button id="id_help_btn" type="button" class="btn btn-default" aria-label="Help" data-toggle="modal" data-target="#infoModal1"><span class="glyphicon glyphicon-question-sign"></span></button> 
                            <button id="search_by_id" type="submit" class="btn btn-default">' . $xml->szukaj . '</button> 
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div class="col-md-6">  
            <div class="jumbotron">  

                <h2 style="text-align: center; margin-bottom: 15px;">' . $xml->content2 . '</h2>

                <form method="get">
                    <div id="search_input_group2" class="input-group input-group-md" style="width: 65%; margin: 0 auto; float: none;">
                        <select class="form-control" name="category" id="categories_select">
                            <option selected disabled>' . $xml->wybierz . '</option>';

                                $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
                                @mysqli_set_charset($polaczenie,"utf8");

                                if($polaczenie->connect_errno == 0)
                                {  
                                    if($result = $polaczenie->query("SELECT * FROM categories ORDER BY name ASC")) 
                                    {
                                        if($result->num_rows > 0) 
                                        {
                                            while($row = mysqli_fetch_array($result))
                                            {
                                                $name = $row['name'];

                                                echo '<option value="'. $name .'">'. $xml->$name .'</option>';
                                            }
                                        }
                                        else
                                        {
                                            echo $xml->brakkategorii;
                                        }
                                    }
                                    else 
                                    {
                                        echo $xml_errors->blad6;
                                    }

                                    $polaczenie->close();
                                }
                                else
                                {
                                    echo $xml_errors->blad6;
                                }


                        echo '</select>
                        <span class="input-group-btn">
                            <button id="search_by_category" type="submit" class="btn btn-default">' . $xml->szukaj . '</button>
                        </span>
                    </div>
                </form>

            </div>
        </div>

        <div class="col-md-6">
            <div class="jumbotron">

                <h2 style="text-align: center; margin-bottom: 15px;">' . $xml->content3 . '</h2>

                <form method="get">
                    <div id="search_input_group" class="input-group" style="width: 65%; margin: 0 auto;"> 
                        <input id="item_name_search_input" class="form-control" name="name" type="text" style="text-align: center;" required> 
                        <div class="input-group-btn">  
                            <button id="search_by_name" type="submit" class="btn btn-default">' . $xml->szukaj . '</button> 
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div class="col-md-6">
            <div class="jumbotron">

                <h2 style="text-align: center; margin-bottom: 15px;">' . $xml->content4 . '</h2>

                <form method="get">
                    <div id="search_input_group" class="input-group" style="width: 65%; margin: 0 auto;"> 
                        <input id="item_location_search_input" class="form-control" name="location" type="text" style="text-align: center;" required> 
                        <div class="input-group-btn">  
                            <button id="search_by_location" type="submit" class="btn btn-default">' . $xml->szukaj . '</button> 
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div class="col-md-12" style="display: block; height: 50px;"></div>

        <div id="infoModal1" class="modal fade" role="dialog">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">' . $xml->modal_tytul1 . '</h4>
                </div>
            <div class="modal-body">
                <p>' . $xml->modal_tekst1 . '</p>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">' . $xml->zamknij . '</button>
                </div>
            </div>
            </div>
        </div>

        ';
    }

    if($logged == true && $permissions >= 3)
    {
        echo '
        <div id="add_to_db_modal1" class="modal fade" role="dialog">
            <div id="addtodb_modal_dialog" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">'. $xml->modal_tytul3 .'</h4>
                    </div>
                    <div id="modal_addtodb_content" class="modal-body">

                        <p style="text-align: center; font-weight: bold;">'. $xml->wybierzkategorie .'</p>
                        <div class="input-group input-group-md" style="width: 85%; margin: 0 auto; float: none;">
                            <select class="form-control" name="category" id="add_todb_categories_select">
                                <option selected disabled>' . $xml->wybierz . '</option>';

                                    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
                                    @mysqli_set_charset($polaczenie,"utf8");

                                    if($polaczenie->connect_errno == 0)
                                    {  
                                        if($result = $polaczenie->query("SELECT * FROM categories ORDER BY name ASC")) 
                                        {
                                            if($result->num_rows > 0) 
                                            {
                                                while($row = mysqli_fetch_array($result))
                                                {
                                                    $name = $row['name'];

                                                    echo '<option value="'. $name .'">'. $xml->$name .'</option>';
                                                }
                                            }
                                            else
                                            {
                                                echo $xml->brakkategorii;
                                            }
                                        }
                                        else 
                                        {
                                            echo $xml_errors->blad6;
                                        }

                                        $polaczenie->close();
                                    }
                                    else
                                    {
                                        echo $xml_errors->blad6;
                                    }


                            echo '</select>
                            <span class="input-group-btn">
                                <button id="add_todb_category" type="submit" class="btn btn-default">' . $xml->dalej . '</button>
                            </span>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">'. $xml->zamknij .'</button>
                    </div>
                </div>
            </div>
        </div>';
    }

?>