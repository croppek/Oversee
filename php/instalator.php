<!DOCTYPE html>
<html lang="pl">
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Instalator</title>
        
        <!-- FAVICON LINKING -->
        <link rel="icon" type="image/png" sizes="32x32" href="img/oversee-logo.png">

        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        
        <script src="js/instalator.js"></script>
        
    </head>
    <body>
        
        <!-- Element wyświetlany podczas sprawdzania postępu wcześniej prowadzonej instalacji -->
        <div id="full_screen_loader" style="position: fixed; left: 0; top: 0; width: 100%; height: 100%; background-color: rgb(51, 51, 51); z-index: 5; text-align: center; color: #fff; display: none;"><br/><br/><h2>Proszę czekać, sprawdzanie postępu wcześniej prowadzonej instalacji...<br/><small style="color: #009dff;">Please wait, checking the progress of previously conducted installation...</small></h2><br/>
        
            <div class="progress" style="width: 30%; height: 50px; margin: 30px auto 0;">
              <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
              </div>
            </div>
            
        </div>
        
        <!-- Element wyświetlany podczas ładowania instalatora -->
        <div id="full_screen_loader2" style="position: fixed; left: 0; top: 0; width: 100%; height: 100%; background-color: rgb(51, 51, 51); z-index: 5; text-align: center; color: #fff; display: none;"><br/><br/><h2>Proszę czekać, przygotowywanie instalatora...<br/><small style="color: #009dff;">Please wait, preparing installation...</small></h2><br/>
        
            <div class="progress" style="width: 30%; height: 50px; margin: 30px auto 0;">
              <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
              </div>
            </div>
            
        </div>
        
        <div class="container text-center vertical-center">
            <div class="jumbotron" id="instalation_jumbotron" style="min-width: 100%; display: none;">
                
                <!-- Progress bar prowadzonej instalacji -->
                <div id="installation_progressbar" class="progress" style="display: none;">
                    <div id="installation_progress" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                    </div>
                </div>
                
                <div id="jumbotron_context" style="width: 100%; height: auto;">
                
                    <h1>Wybierz język <small>/ Choose language:</small></h1>
                    <div class="btn-group btn-group-lg btn-group-justified" role="group" aria-label="" style="margin-top: 50px;">
                        <button id="polish_btn" type="button" class="btn btn-default" style="width: 300px; height: 60px;">Polski</button>
                        <button id="english_btn" type="button" class="btn btn-default" style="width: 300px; height: 60px;">English</button>
                    </div>
                    
                </div>
            </div>
        </div>

        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
        
        <script>
            
            setTimeout(function(){
                
                $('#instalation_jumbotron').fadeIn(250);
                
            },1000);
        
        </script>
        
    </body>
</html>