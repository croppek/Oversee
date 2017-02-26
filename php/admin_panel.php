<?php 

    session_start();
    
    //ustawianie języka strony na podstawie zapisanych ciasteczek
    $lang = 'pl';

    if(isset($_COOKIE['oversee_language']))
    {
        $lang = $_COOKIE['oversee_language'];
        
        if($lang == 'pl')
        {
            $xml = simplexml_load_file("../xml/panel_admina.xml");  
            $xml_errors = simplexml_load_file("../xml/bledy.xml"); 
            $support_location = "img/support-btn-pl.png";
            
            $link1 = './o-projekcie';
            $link2 = './ustawienia-konta';
            $link3 = './panel-administracyjny';
        }
        else if($lang == 'en')
        {
            $xml = simplexml_load_file("../xml/panel_admina_en.xml");
            $xml_errors = simplexml_load_file("../xml/bledy_en.xml"); 
            $support_location = "img/support-btn-en.png";
            
            $link1 = './about-project';
            $link2 = './account-settings';
            $link3 = './administration-panel';
        }
    }
    else
    {
        $xml = simplexml_load_file("../xml/panel_admina.xml");
        $xml_errors = simplexml_load_file("../xml/bledy.xml"); 
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
    else
    {
        header('Location: ./');
    }

    //zwrócienie kontentu pozostałych zakładek panelu administracyjnego
    if(isset($_POST['get_content']))
    {
        switch($_POST['get_content'])
        {
            case 'tab1':
                echo '
                    <h3 style="margin-bottom: 20px;">'. $xml->zmienlogo .'</h3>
                
                    <label for="set_image_input">'. $xml->adresurllogo .'</label>
                    <div class="input-group input-group-lg" style="margin: 0 auto; float: none;">
                        <span class="input-group-btn">
                            <button class="btn btn-info" type="button" data-toggle="modal" data-target="#infoModal_zmianalogo"><span class="glyphicon glyphicon-question-sign"></span></button>
                        </span>
                        <input id="set_image_input" onblur="logo_preview()" type="text" class="form-control">
                    </div>

                    <div id="infoModal_zmianalogo" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">'. $xml->zmienlogo .'</h4>
                            </div>
                        <div class="modal-body">
                            <p>'. $xml->modaltekstzmianalogo .'</p>
                        </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">'. $xml->zamknij .'</button>
                            </div>
                        </div>
                        </div>
                    </div>

                    <br/>

                    '. $xml->podglad .'
                    <div id="image_preview_div" style="border: 2px solid #484848; padding: 5px; width: 165px; height: 165px; border-radius: 5px; margin: 10px auto 0;">

                    </div>

                    <div id="error_alert" class="alert alert-danger" role="alert" style="width: 65%; margin: 20px auto 0; display: none;"></div>

                    <br/><br/>

                    <button id="change_logo_btn" class="btn btn-primary btn-lg" type="button" style="width: 200px;" disabled>'. $xml->zmienlogobtn .'</button>
                    
                    <div style="border-bottom: 1px solid black; margin: 20px auto 15px; width: 100%; display: block;"></div>
                    
                    <h3 style="margin-bottom: 20px;">'. $xml->zmiennazwe .'</h3>
                    
                    <div class="input-group input-group-lg" style="margin: 0 auto; float: none;">
                        <span class="input-group-btn">
                            <button class="btn btn-info" type="button" data-toggle="modal" data-target="#infoModal_zmiananazwy"><span class="glyphicon glyphicon-question-sign"></span></button>
                        </span>
                        <input id="set_page_name_input" type="text" class="form-control">
                    </div>

                    <div id="infoModal_zmiananazwy" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">'. $xml->zmiennazwe .'</h4>
                            </div>
                        <div class="modal-body">
                            <p>'. $xml->modaltekstzmiananazwy .'</p>
                        </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">'. $xml->zamknij .'</button>
                            </div>
                        </div>
                        </div>
                    </div>

                    <br/><br/>

                    <button id="change_page_name_btn" class="btn btn-primary btn-lg" type="button" style="width: 200px;">'. $xml->zmiennazwebtn .'</button>
                ';
                break; 
        
            case 'tab2':
                
                
                if(isset($_SESSION['permissions']) && $_SESSION['permissions'] >= 3)
                {
                    echo '
                        
                        <h3 style="text-align: center;">'.$xml->dodajnowegouzytkownika.'</h3>
                        
                        <br />
                        <label for="login_input">'. $xml->logintitle .'</label>
                        <div class="input-group input-group-lg add_new_user_inputs" style="width: 75%; margin: 0 auto; float: none;">
                            <input id="login_input" type="text" class="form-control" disabled />
                        </div>

                        <br />
                        <label for="specialization_input">'. $xml->podajimie .'</label>
                        <div class="input-group input-group-lg add_new_user_inputs" style="width: 75%; margin: 0 auto; float: none;">
                            <input id="name_input" onblur="generate_login()" type="text" class="form-control" />
                        </div>

                        <br />
                        <label for="specialization_input">'. $xml->podajnazwisko .'</label>
                        <div class="input-group input-group-lg add_new_user_inputs" style="width: 75%; margin: 0 auto; float: none;">
                            <input id="lastname_input" onblur="generate_login()" type="text" class="form-control" />
                        </div>
                        
                        <br />
                        <div class="input-group input-group-lg add_new_user_inputs" style="width: 75%; margin: 0 auto; float: none;">
                            <button class="btn btn-info btn-xs" type="button" data-toggle="modal" data-target="#modal_permissions_info"><span class="glyphicon glyphicon-question-sign"></span></button><label for="permissions_input">&nbsp;&nbsp;'. $xml->wybierzpermisje .'</label>
                            <select class="form-control" id="permissions_input">
                                <option value="1">'. $xml->moderator .'</option>
                                <option value="2">'. $xml->administrator .'</option>
                                <option value="3">'. $xml->superadmin .'</option>
                            </select>
                        </div>
                        
                        <br />
                        <div class="input-group input-group-lg add_new_user_inputs" style="width: 75%; margin: 0 auto; float: none;">
                            <label for="specialization_input">'. $xml->wybierzspecializacje .'</label>
                            <select class="form-control" id="specialization_input">
                                <option value="brak"></option>
                                <option value="architektura">'. $xml->option1 .'</option>
                                <option value="bibliotekarstwo">'. $xml->option2 .'</option>
                                <option value="blacharstwo">'. $xml->option3 .'</option>
                                <option value="hydraulika">'. $xml->option4 .'</option>
                                <option value="elektronika">'. $xml->option5 .'</option>
                                <option value="elektryka">'. $xml->option6 .'</option>
                                <option value="informatyka">'. $xml->option7 .'</option>
                                <option value="lakiernictwo">'. $xml->option8 .'</option>
                                <option value="mechanika">'. $xml->option9 .'</option>
                                <option value="stolarstwo">'. $xml->option10 .'</option>
                            </select>
                        </div>

                        <br />
                        <div id="error_alert" class="alert alert-danger" role="alert" style="width: 65%; margin: 20px auto 0; display: none;"></div>

                        <br/>
                        <button id="add_user_btn" class="btn btn-primary btn-lg" type="button" style="width: 200px;">'. $xml->dodajuzytkownika .'</button>
                        
                        <div id="modal_permissions_info" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">'. $xml->infoopermisjach .'</h4>
                                </div>
                            <div class="modal-body">
                                <p>'. $xml->permisjetekst .'</p>
                            </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">'. $xml->zamknij .'</button>
                                </div>
                            </div>
                            </div>
                        </div> 
                        
                        <div style="border-bottom: 1px solid black; margin: 20px auto 15px; width: 100%; display: block;"></div>
                    
                    ';
                    
                    require 'connect.php';

                    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
                    @mysqli_set_charset($polaczenie,"utf8");

                    if($polaczenie->connect_errno == 0)
                    {  
                        if($result = $polaczenie->query("SELECT * FROM users")) 
                        {
                            echo '
                            
                            <h3 style="text-align: center;">'.$xml->wszyscyuzytkownicy.'</h3>

                            <table class="table-striped rtable" style="margin-top: 20px;">
                            <thead>
                                <tr>
                                    <th>'.$xml->login.'</th>
                                    <th>'.$xml->email.'</th>
                                    <th>'.$xml->imie.'</th>
                                    <th>'.$xml->nazwisko.'</th>
                                    <th>'.$xml->specjalizacja.'</th>
                                    <th>'.$xml->uprawnienia.'</th>
                                    <th>'.$xml->edytujinformacje.'</th>
                                    <th>'.$xml->usunuzytkownika.'</th>
                                </tr>
                            </thead><tbody>';
                            
                            while($row = mysqli_fetch_array($result))
                            {   
                                switch($row['permissions'])
                                {
                                    case '1':
                                        $permissions = $xml->moderator;
                                        break;
                                    case '2':
                                        $permissions = $xml->administrator;
                                        break;
                                    case '3':
                                        $permissions = $xml->superadmin;
                                        break;
                                }
                                
                                echo '

                                <tr>
                                    <td>'.$row['login'].'</td>
                                    <td>'.$row['email'].'</td>
                                    <td>'.$row['name'].'</td>
                                    <td>'.$row['lastname'].'</td>
                                    <td>'.$row['specialization'].'</td>
                                    <td>'.$permissions.'</td>
                                    <td><button class="btn btn-info btn-sm edit_user_info_btn" style="word-break: normal;" title="Opcja tymczasowo niedostępna / This option is temporarily unavailable" disabled>'. $xml->edytuj .'</button></td>
                                    <td><button class="btn btn-danger btn-sm remove_user_from_db_btn" style="word-break: normal;">'. $xml->usun .'</button></td>
                                </tr>';
                            }
                            
                            echo '</tbody></table>';
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
                else
                {
                    echo $xml_errors->brakpermisji;
                }
                break;
                
            case 'tab3':
                
                require 'connect.php';

                $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
                @mysqli_set_charset($polaczenie,"utf8");

                if($polaczenie->connect_errno == 0)
                {  
                    $result = $polaczenie -> query("SELECT saved FROM installation WHERE what = 'lastid'");
                    
                    $wiersz = $result->fetch_assoc();
                    
                    $last_id = $wiersz['saved'];
                }
                
                echo '
                
                    <h3 style="margin-bottom: 20px;">'. $xml->generujkody .'</h3>
                    
                    <h4 style="margin-bottom: 20px;">'. $xml->gdziegenerowac .'</h4>
                    <br/><br/>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i>'. $xml->generujlinki .'</i> &nbsp;&nbsp;|&nbsp;&nbsp; '. $xml->zakresnumerowid .'
                            <input id="first_id_number_input" class="form-control" type="number" min="1" value="'. (int)$last_id .'" style="text-align: center; width: 100px; display: inline-block;">
                            &nbsp; - &nbsp;
                            <input id="second_id_number_input" class="form-control" type="number" min="1" value="'. (int)$last_id .'" style="text-align: center; width: 100px; display: inline-block;">
                            
                            &nbsp;&nbsp;&nbsp;
                            <input id="save_last_id_checkbox" type="checkbox" name="save_last_id_checkbox">
                            <label id="save_last_id_checkbox" for="save_last_id_checkbox" style="cursor: pointer;">'. $xml->zapiszostatninumerid .'</label>
                            
                            <br/><br/>
                            <button class="btn btn-primary btn-md" id="generate_id_links_btn" style="word-break: normal;">'. $xml->generujbtn .'</button>
                        </div>
                        <div class="panel-body">
                            <textarea style="width: 90%; height: 350px; resize: vertical;" id="generated_links_area"></textarea>
                        </div>
                    </div>
                
                ';
                break;
        }
        return;
    }

    //dodawanie nowego użytkownika do bazy danych
    if(isset($_POST['login']) && isset($_POST['password']) && isset($_POST['name']) && isset($_POST['lastname']) && isset($_POST['specialization']) && isset($_POST['permissions']))
    {
        $wszystko_OK = true;
            
        $login = $_POST['login'];
        $name = $_POST['name'];
        $lastname = $_POST['lastname'];
        $password = $_POST['password'];
        $specialization = $_POST['specialization'];
        $permissions = $_POST['permissions'];
        
        require 'connect.php';

        $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
        @mysqli_set_charset($polaczenie,"utf8");

        if($polaczenie->connect_errno != 0)
        {  
            echo 'bladpolaczeniazbazadanych';
        }
        else
        {
            //Sprawdzenie czy imię składa się tylko z liter
            if(preg_match('/^[a-ząćęłńóśźż]+$/ui', $name) == false)
            {
                $wszystko_OK = false;
                echo "imietylkozliter";
                return; 
            }

            //Sprawdzenie czy nazwisko składa się tylko z liter
            if(preg_match('/^[a-ząćęłńóśźż]+$/ui', $lastname) == false)
            {
                $wszystko_OK = false;
                echo "nazwiskotylkozliter";
                return; 
            }

            //Sprawdzenie czy login składa się tylko z liter i cyfr
            if(ctype_alnum($login) == false)
            {
                $wszystko_OK = false;
                echo "logintylkozliterinumerow";
                return; 
            }
            
            //Sprawdzenie długości hasła
            if(strlen($password) < 5 || strlen($password) > 30)
            {
                $wszystko_OK = false;
                echo "zladlugoschasla";
                return; 
            }

            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            //Sprawdzenie czy podany login jest już zajęty
            $rezultat = $polaczenie -> query("SELECT id FROM users WHERE login='$login'");

            $ile_loginow = $rezultat->num_rows;

            if($ile_loginow > 0)
            {
                $wszystko_OK = false;
                echo "zajetylogin";
                $polaczenie->close();
                return;
            }

            //Testy walidacyjne przeszły pomyślnie, wkładamy dane do bazy
            if($wszystko_OK == true)
            {      
                if($polaczenie->query("INSERT INTO users VALUES(NULL,'$login','$password_hash','','$name','$lastname','$specialization','','$permissions',0)"))
                {
                    switch($permissions)
                    {
                        case '1':
                            $permissions = $xml->moderator;
                            break;
                        case '2':
                            $permissions = $xml->administrator;
                            break;
                        case '3':
                            $permissions = $xml->superadmin;
                            break;
                    }
                    
                    echo '<h3 style="text-align: center;">'.$xml->uzytkownikdodany.'</h3>
                    
                    <br/>
                    <div id="table_wrapper" style="width: 90%; margin: 0 auto; float: none;">
                        <table class="table-striped rtable">
                        <thead>
                            <tr>
                                <th>'.$xml->login.'</th>
                                <th>'.$xml->haslo.'</th>
                                <th>'.$xml->imie.'</th>
                                <th>'.$xml->nazwisko.'</th>
                                <th>'.$xml->specjalizacja.'</th>
                                <th>'.$xml->uprawnienia.'</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>'.$login.'</td>
                                <td>'.$password.'</td>
                                <td>'.$name.'</td>
                                <td>'.$lastname.'</td>
                                <td>'.$specialization.'</td>
                                <td>'.$permissions.'</td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    
                    <br/><br/>
                    <button id="confirm_new_user_data_btn" class="btn btn-primary btn-lg" type="button" style="width: 200px;">'. $xml->ok .'</button>
                    ';
                }
                else
                {
                    echo "bladdodawania";
                }

                $polaczenie->close();
                
                return;
            }
        }
    }

    //usuwanie użytkownika z bazy danych
    if(isset($_POST['remove_user']) && isset($_POST['remove_email']))
    {
        $user = $_POST['remove_user'];
        $email = $_POST['remove_email'];
        
        require 'connect.php';

        $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
        @mysqli_set_charset($polaczenie,"utf8");

        if($polaczenie->connect_errno != 0)
        {  
            echo $polaczenie->connect_error;
        }
        else
        {
            if($polaczenie->query("DELETE FROM users WHERE login = '$user' AND email = '$email'"))
            {
                echo 'success';
            }
        }
        
        return;
    }

    //zapisywanie ostatniego wygenerowanego numeru ID
    if(isset($_POST['save_last_id']))
    {
        $last_id = $_POST['save_last_id'];
        
        require 'connect.php';

        $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
        @mysqli_set_charset($polaczenie,"utf8");

        if($polaczenie->connect_errno != 0)
        {  
            echo $polaczenie->connect_error;
        }
        else
        {
            if($polaczenie->query("UPDATE installation SET saved = '$last_id' WHERE what = 'lastid'"))
            {
                echo 'success';
            }
        }
        
        return;
    }

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
    <body data-version="0.55">
        
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
                        
                        <li><a href="./"><?php echo $xml->naglowek1; ?></a></li>
                        <li><a href="<?php echo $link1. '">'. $xml->naglowek2; ?></a></li>
                        
                        <?php
                            
                            if($logged == true && $permissions >= 1)
                            {
                                echo '<li><a href="'. $link2 .'">'. $xml->naglowek4 .'</a></li>';
                            }
                    
                            if($logged == true && $permissions >= 3)
                            {
                                echo '<li class="active"><a href="'. $link3 .'">'. $xml->naglowek5 .'</a></li>';
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
        
        <div class="jumbotron" id="admin_panel_jumbotron" style="padding-bottom: 30px;">
            <ul class="nav nav-tabs">
                <li role="presentation" class="active" id="tab1"><a class="not-active-link"><?php echo $xml->tab1; ?></a></li>
                <li role="presentation" id="tab2"><a class="not-active-link"><?php echo $xml->tab2; ?></a></li>
                <li role="presentation" id="tab3"><a class="not-active-link"><?php echo $xml->tab3; ?></a></li>
            </ul>
            
            <div id="admin_panel_content" style="width: 75%; margin: 0 auto; float: none;">
                <h3 style="margin-bottom: 20px;"><?php echo $xml->zmienlogo; ?></h3>
                
                <label for="set_image_input"><?php echo $xml->adresurllogo; ?></label>
                <div class="input-group input-group-lg" style="margin: 0 auto; float: none;">
                    <span class="input-group-btn">
                        <button class="btn btn-info" type="button" data-toggle="modal" data-target="#infoModal_zmianalogo"><span class="glyphicon glyphicon-question-sign"></span></button>
                    </span>
                    <input id="set_image_input" onblur="logo_preview()" type="text" class="form-control">
                </div>

                <div id="infoModal_zmianalogo" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"></h4>
                        </div>
                    <div class="modal-body">
                        <p><?php echo $xml->modaltekstzmianalogo; ?></p>
                    </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $xml->zamknij; ?></button>
                        </div>
                    </div>
                    </div>
                </div>

                <br/>

                <?php echo $xml->podglad; ?>
                <div id="image_preview_div" style="border: 2px solid #484848; padding: 5px; width: 165px; height: 165px; border-radius: 5px; margin: 10px auto 0;">

                </div>

                <div id="error_alert" class="alert alert-danger" role="alert" style="width: 65%; margin: 20px auto 0; display: none;"></div>

                <br/><br/>

                <button id="change_logo_btn" class="btn btn-primary btn-lg" type="button" style="width: 200px;" disabled><?php echo $xml->zmienlogobtn; ?></button>
                
                <div style="border-bottom: 1px solid black; margin: 20px auto 15px; width: 100%; display: block;"></div>
                    
                <h3 style="margin-bottom: 20px;"><?php echo $xml->zmiennazwe; ?></h3>

                <div class="input-group input-group-lg" style="margin: 0 auto; float: none;">
                    <span class="input-group-btn">
                        <button class="btn btn-info" type="button" data-toggle="modal" data-target="#infoModal_zmiananazwy"><span class="glyphicon glyphicon-question-sign"></span></button>
                    </span>
                    <input id="set_page_name_input" type="text" class="form-control">
                </div>

                <div id="infoModal_zmiananazwy" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><?php echo $xml->zmiennazwe; ?></h4>
                        </div>
                    <div class="modal-body">
                        <p><?php echo $xml->modaltekstzmiananazwy; ?></p>
                    </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $xml->zamknij; ?></button>
                        </div>
                    </div>
                    </div>
                </div>
                
                <br/><br/>

                <button id="change_page_name_btn" class="btn btn-primary btn-lg" type="button" style="width: 200px;"><?php echo $xml->zmiennazwebtn; ?></button>
            </div>
        </div>
        
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
                <a id="support_img_link" href="#" target="_blank"><img src="<?php echo $support_location; ?>" style="height: 25px; width: auto; margin-top: 7.5px;"/></a>
                <p class="text-muted" id="footer_content2">&nbsp;&nbsp; | &nbsp; <a href="#" target="_blank">Oversee Systems</a> &copy; <?php echo date("Y"); ?></p>
            </div>
        </footer>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
        <script src="js/system.js"></script>
        <script src="js/login.js"></script>
        <script src="js/cookies.js"></script>
    </body>
</html>