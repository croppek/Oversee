<?php 

    session_start();
    
    //ustawianie języka strony na podstawie zapisanych ciasteczek
    $file_usersettings = true;
    require 'moduły/choose_language.php';
    
    //zwrócenie nowego contentu zmiany hasła
    if(isset($_POST['get_content']))
    {
        switch($_POST['get_content'])
        {
            case 'conf_code':
                echo '
        
                <label for="confirm_code_input">'. $xml->potwierdzzmiane .'</label>
                <input id="confirm_code_input" type="text" class="form-control" style="text-align: center; border-radius: 5px;">

                ';
                
                break;
                
            case 'thanks':
                
                echo '<h2 style="text-align: center;">'. $xml->powodzeniezmianyhasla .'</h2>';
                
                break;
        }
        
        return;
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
    <body>
        
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
                                echo '<li class="active"><a href="'. $link2 .'">'. $xml->naglowek4 .'</a></li>';
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
            
        <div class="jumbotron" id="account_settings_jumbotron">
            <ul class="nav nav-tabs">
                <li role="presentation" class="active"><a href="#"><?php echo $xml->tab1; ?></a></li>
            </ul>
            
            <h3 style="margin-bottom: 20px;"><?php echo $xml->zmienhaslo; ?></h3>
            
            <div class="input-group input-group-lg" id="change_password_inputs" style="width: 45%; margin: 0 auto; float: none;">
                <label for="old_password_input"><?php echo $xml->starehaslo; ?></label>
                <input id="old_password_input" type="password" class="form-control" style="text-align: center; border-radius: 5px;"/>
                <br/>
                <label for="password_input" style="margin-top: 20px;"><?php echo $xml->nowehaslo; ?></label>
                <input id="password_input" type="password" class="form-control" style="text-align: center; border-radius: 5px;"/>
                <br/>
                <label for="password2_input" style="margin-top: 20px;"><?php echo $xml->powtorzhaslo; ?></label>
                <input id="password2_input" type="password" class="form-control" style="text-align: center; border-radius: 5px;"/>
            </div>
            
            <div id="error_alert" class="alert alert-danger" role="alert" style="width: 45%; margin: 25px auto 0; display: none;"></div>
            <button id="change_password_button" class="btn btn-primary btn-lg" type="button" style="width: 200px; margin-top: 20px;"><?php echo $xml->zmienhaslo; ?></button>
            <button id="confirm_new_password_button" class="btn btn-primary btn-lg" type="button" style="width: 200px; margin-top: 20px; display: none;"><?php echo $xml->potwierdz; ?></button>
        </div>
        
        <!-- Sprawdzenie czy dostępna jest aktualizacja systemu -->
        <?php 
            if($permissions >= 3)
            {
                if(!isset($_SESSION['update_checked']))
                {
                    //odczyt aktualnej wersji z pliku
                    $myfile = fopen("wersja.txt", "r") or die("Unable to open file!");
                    $actual_version = fread($myfile,filesize("wersja.txt"));
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
    </body>
</html>