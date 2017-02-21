<?php 

    session_start();
    
    //ustawianie języka strony na podstawie zapisanych ciasteczek
    if(isset($_COOKIE['oversee_language']))
    {
        $lang = $_COOKIE['oversee_language'];
        
        if($lang == 'pl')
        {
            $xml = simplexml_load_file("../xml/stronaglowna.xml");  
            $xml_errors = simplexml_load_file("../xml/bledy.xml"); 
            $support_location = "img/support-btn-pl.png";
            
            $link1 = './o-projekcie';
            $link2 = './ustawienia-konta';
            $link3 = './panel-administracyjny';
        }
        else if($lang == 'en')
        {
            $xml = simplexml_load_file("../xml/stronaglowna_en.xml");
            $xml_errors = simplexml_load_file("../xml/bledy_en.xml"); 
            $support_location = "img/support-btn-en.png";
            
            $link1 = './about-project';
            $link2 = './account-settings';
            $link3 = './administration-panel';
        }
    }
    else
    {
        $xml = simplexml_load_file("../xml/stronaglowna.xml");
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

?>

<!DOCTYPE html>
<html lang="en">
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
        
        <p>Test o account settings</p>
        
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