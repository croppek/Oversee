<?php 

    session_start();
    
    //ustawianie języka strony na podstawie zapisanych ciasteczek
    $lang = 'pl';

    if(isset($_COOKIE['oversee_language']))
    {
        $lang = $_COOKIE['oversee_language'];
        
        if($lang == 'pl')
        {
            $xml = simplexml_load_file("xml/stronaglowna.xml");  
            $xml_errors = simplexml_load_file("xml/bledy.xml"); 
            $support_location = "img/support-btn-pl.png";
            
            $link1 = './o-projekcie';
            $link2 = './ustawienia-konta';
            $link3 = './panel-administracyjny';
        }
        else if($lang == 'en')
        {
            $xml = simplexml_load_file("xml/stronaglowna_en.xml");
            $xml_errors = simplexml_load_file("xml/bledy_en.xml"); 
            $support_location = "img/support-btn-en.png";
            
            $link1 = './about-project';
            $link2 = './account-settings';
            $link3 = './administration-panel';
        }
    }
    else
    {
        $xml = simplexml_load_file("xml/stronaglowna.xml");
        $xml_errors = simplexml_load_file("xml/bledy.xml"); 
        $support_location = "img/support-btn-pl.png";
        
        $link1 = './o-projekcie';
        $link2 = './ustawienia-konta';
        $link3 = './panel-administracyjny';
    }
    
    //sprawdzenie czy użytkownik jest zalogowany oraz ustalenie jego permisji
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

    require 'connect.php';

?>

<!DOCTYPE html>
<?php

    if($lang == 'pl')
    {
        echo '<html lang="pl">';
    }
    else if($lang == 'en')
    {
        echo '<html lang="en">';
    }

?>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>--page_title--</title>

        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        
        <link href="css/style.css" rel="stylesheet">
        
        <!-- FAVICON LINKING -->
        <link rel="icon" type="image/png" sizes="32x32" href="img/oversee-logo.png">
    
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
      
    </head>
    <body data-version="1.0">
        
        <nav class="navbar navbar-inverse navbar-static-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false" id="toggle_menu_btn">
                        <span class="sr-only"><?php echo $xml->naglowek3; ?></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>
                    
                    <a class="navbar-brand" href="./">
                        <img alt="Brand" id="page_logo" class="logo_img" src="img/oversee-logo.png">
                    </a>
                    <p class="navbar-text" style="font-weight: bold; font-style: italic;">--page_title--</p>
                    
                </div>
                
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        
                        <?php
                            if(isset($_GET['id']) || isset($_GET['category']) || isset($_GET['history']))
                            {
                                echo '<li>';
                            }
                            else
                            {
                                echo '<li class="active">';
                            }
                        ?>
                        
                        <a href="./"><?php echo $xml->naglowek1; ?></a></li>
                        <li><a href="<?php echo $link1. '">'. $xml->naglowek2; ?></a></li>
                        
                        <?php
                            
                            if($logged == true && $permissions >= 1)
                            {
                                echo '<li><a href="'. $link2 .'">'. $xml->naglowek4 .'</a></li>';
                            }
                    
                            if($logged == true && $permissions >= 3)
                            {
                                echo '<li><a href="'. $link3 .'">'. $xml->naglowek5 .'</a></li>';
                            }
                    
                        ?>
                    
                    </ul>
                    
                    <?php
                        if($logged == true)
                        {
                            echo '<button type="button" id="log_out_btn" class="btn btn-warning navbar-btn pull-right">'. $xml->wyloguj .'</button>';
                        }
                        else
                        {
                            echo '<button type="button" id="nav_sign_in_btn" class="btn btn-primary navbar-btn pull-right" data-toggle="modal" data-target="#loginModal">'. $xml->zaloguj .'</button>';
                        }
                    ?>
                    
                    <div class="btn-group btn-group-xs pull-right lang_btns" id="lang_btns" role="group">
                        <button type="button" id="lang_btn_pl" data-language="PL" class="btn btn-default">PL</button>
                        <button type="button" id="lang_btn_en" data-language="EN" class="btn btn-default">EN</button>
                    </div>
                    
                </div>
              
            </div>
        </nav>
    
        <div id="page_blend"></div>
        
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
                                    
                                switch($category)
                                {
                                    case 'devices':
                                        include 'php/kategorie/devices.php';
                                        break;
                                }
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
                                    
                                    switch($category)
                                    {
                                        case 'devices':
                                            include 'php/kategorie/devices.php';
                                            break;
                                    }
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
                                
                                switch($category)
                                {
                                    case 'devices':
                                        include 'php/kategorie/devices.php';
                                        break;
                                }
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
        
        <div id="loginModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $xml->modal_tytul2; ?></h4>
                </div>
            <div id="modal_login_content" class="modal-body">
                
                <div id="email_confirm_content" style="display: none;"></div>
                
                <div id="login_content">
                    
                    <div id="login_input_group" class="input-group input-group-lg" style="width: 45%; margin: 0 auto; float: none;">
                        <input id="login_input" type="text" class="form-control" name="login_input" style="text-align: center; border-radius: 5px;" placeholder="<?php echo $xml->placeholder1; ?>" onfocus="this.placeholder = '' " onblur="this.placeholder='<?php echo $xml->placeholder1; ?>'"/>
                    </div>
                    <br/>
                    <div id="login_input_group" class="input-group input-group-lg" style="width: 45%; margin: 0 auto; float: none;">
                        <input id="password_input" type="password" class="form-control" name="password_input" style="text-align: center; border-radius: 5px;" placeholder="<?php echo $xml->placeholder2; ?>" onfocus="this.placeholder = '' " onblur="this.placeholder='<?php echo $xml->placeholder2; ?>'"/>
                    </div>
                    <br/>

                    <div id="error_alert" class="alert alert-danger" role="alert" style="width: 90%; margin: 10px auto 10px; display: none;"></div>

                    <br/>

                    <button type="button" id="sign_in_btn" class="btn btn-primary"><?php echo $xml->zaloguj; ?></button>
                    
                </div>

            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $xml->zamknij; ?></button>
                </div>
            </div>
            </div>
        </div>
        
        <!-- Sprawdzenie czy dostępna jest aktualizacja systemu -->
        <?php 
            if($permissions >= 3)
            {            
                if(!isset($_SESSION['update_checked']))
                {
                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL,"http://oversee.zspwrzesnia.pl/php/check_update.php");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, 
                                http_build_query(array('version' => '1.0')));

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

                                    <a href="#" target="_blank" class="btn btn-info" role="button" style="margin-right: 40px;">'. $xml->aktualizuj .'</a>
                                    <button id="nieteraz_update_btn" class="btn btn-info">'. $xml->nieteraz .'</button>

                                </div>

                        </div>';   
                    }
                }
            }
        ?>
        
        <!-- Alert o używaniu ciasteczek w serwisie -->
        <?php 
            if(!isset($_COOKIE['oversee_cookies']))
            {               
                echo '
                <div id="cookies_politics">

                    <!-- Ciasteczko pobrane ze strony ClipArtBest.com -->
                    <div id="cookie_img"><img src="img/cookie.png" /></div>

                    <div id="cookie_txt">'. $xml->ciasteczkatekst .'<br/>

                        <div id="cookie_btns_wrap" class="btn-group-sm">';

                            if(isset($lang) && $lang == 'pl')
                            {
                                $link = "https://pl.wikipedia.org/wiki/HTTP_cookie";  
                            }
                            else if(isset($lang) && $lang == 'en')
                            {
                                $link = "https://en.wikipedia.org/wiki/HTTP_cookie"; 
                            }
                            else
                            {
                                $link = "https://pl.wikipedia.org/wiki/HTTP_cookie";  
                            }
                            
                            echo '
                            <a href="'. $link .'" target="_blank" class="btn btn-info" role="button">'. $xml->dowiedzsiewiecej .'</a>
                            <button id="rozumiem_cookie_btn" class="btn btn-info">'. $xml->rozumiem .'</button>

                        </div>

                    </div>

                </div>';
            }
        ?>
    
        <!-- Stopka z odnośnikami do stron autora -->
        <footer class="footer navbar-fixed-bottom">
            <div class="container">
                <p class="text-muted" id="footer_content1"><a href="https://github.com/croppek" target="_blank">Bartosz Kropidłowski</a> &nbsp;</p>
                <a id="support_img_link" href="http://oversee.zspwrzesnia.pl/wspieraj-projekt" target="_blank"><img src="<?php echo $support_location; ?>" style="height: 25px; width: auto; margin-top: 7.5px;"/></a>
                <p class="text-muted" id="footer_content2">&nbsp;&nbsp; | &nbsp; <a href="http://oversee.zspwrzesnia.pl" target="_blank">Oversee Systems</a> &copy; <?php echo date("Y"); ?></p>
            </div>
        </footer>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
        <script src="js/system.js"></script>
        <script src="js/login.js"></script>
        <script src="js/cookies.js"></script>
        <script src="js/jquery.idle.min.js"></script>
        
        <?php
        
        if($permissions >= 2)
        {
            if(!isset($_GET['id']) && !isset($_GET['category']) && !isset($_GET['history']) && !isset($_GET['name']) && !isset($_GET['location']))
            {
                echo '<script src="js/idle_refresher.js"></script>';   
            }
        }
        
        ?>
        
    </body>
</html>