<?php 

    session_start();
    
    //ustawianie języka strony na podstawie zapisanych ciasteczek
    $file_homepage = true;
    require 'php/moduły/choose_language.php';
    
    //sprawdzenie czy użytkownik jest zalogowany oraz ustalenie jego permisji
    require 'php/moduły/check_permissions.php';

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
        
        <!-- Wczytanie modułu odpowiedzialnego za wyszukiwanie przedmiotów -->
        <?php
            require 'php/moduły/search_items.php';
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
                    
                    <div class="input-group input-group-lg login_input_group">
                        <input id="login_input" type="text" class="form-control" name="login_input" style="text-align: center; border-radius: 5px;" placeholder="<?php echo $xml->placeholder1; ?>" onfocus="this.placeholder = '' " onblur="this.placeholder='<?php echo $xml->placeholder1; ?>'"/>
                    </div>
                    <br/>
                    <div class="input-group input-group-lg login_input_group">
                        <input id="password_input" type="password" class="form-control" name="password_input" style="text-align: center; border-radius: 5px;" placeholder="<?php echo $xml->placeholder2; ?>" onfocus="this.placeholder = '' " onblur="this.placeholder='<?php echo $xml->placeholder2; ?>'"/>
                    </div>
                    <br/>

                    <div id="error_alert" class="alert alert-danger" role="alert"></div>

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
            require 'php/moduły/check_update.php';
        ?>
        
        <!-- Alert o używaniu ciasteczek w serwisie -->
        <?php 
            require 'php/moduły/cookies_alert.php';
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