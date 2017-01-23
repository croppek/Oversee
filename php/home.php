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
    <body>
        <img id="logo-saver" src="img/oversee-logo.png" style="display: none;"/>
        
        <nav class="navbar navbar-inverse navbar-static-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false" id="toggle_menu_btn">
                        <span class="sr-only">Rozwiń menu</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>
                    
                    <a class="navbar-brand" href="#">
                        <img alt="Brand" src="img/oversee-logo.png" style="margin-top: -12px; height: 45px; width: 45px;">
                    </a>
                    <p class="navbar-text">--page_title--</p>
                    
                </div>
                
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#">Szukaj sprzętu</a></li>
                        <li><a href="#">O projekcie</a></li>
                    </ul>
                    
                    <button type="button" id="sign_in_btn" class="btn btn-primary navbar-btn pull-right">Zaloguj się</button>
                </div>
              
            </div>
        </nav>
        
        <div class="col-md-6">
            <div class="jumbotron" style="padding: 15px;">
                <h2 style="text-align: center;">Podaj ID:</h2>
                <div class="input-group"> 
                    <input class="form-control" aria-label="Text input with multiple buttons"> 

                    <div class="input-group-btn"> 
                        <button id="id_help_btn" type="button" class="btn btn-default" aria-label="Help"><span class="glyphicon glyphicon-question-sign"></span></button> 
                        <button id="search_by_id" type="button" class="btn btn-default">Szukaj</button> 
                    </div> 
                </div>
            </div>
        </div>

        <div class="col-md-6">  
            <div class="jumbotron" style="padding: 15px;">  
                
                <h2 style="text-align: center;">Wyszukaj według kryterii:</h2>

            </div>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
        <script src="js/menu.js"></script>
    </body>
</html>