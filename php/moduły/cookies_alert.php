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