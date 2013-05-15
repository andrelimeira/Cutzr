<?php
    
    require_once 'config/bootstrap.php';
    
    use com\Cutzr\YouTube;
    use com\Cutzr\MySQLYouTubeVideoStorage;
    
    $requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
    if ($requestMethod == 'POST') {
        /**
         * Recupera um parâmetro enviado por HTTP-POST
         * @param string $param parâmetro enviado na requisição
         * @param mixed $returnValue valor de retorno caso o parâmetro não for encontrado
         * @return mixed
         */
        function getPost($param, $returnValue = NULL)
        {
            return isset($_POST[$param]) ? $_POST[$param] : $returnValue;
        }
            
        /**
         * Converte um tempo no formato H:i:s para segundos
         * @param string $time tempo no formato H:i:s
         * @return integer
         */
        function toSeconds($time)
        {
            sscanf($time, '%d:%d:%d', $H, $i, $s);
            return (($H * 3600) + ($i * 60) + $s);
        }
        
               $errors = array();
              $videoHD = (bool) getPost('video-hd', 0);
        $relatedVideos = (bool) getPost('related-videos', 0);
        $videoAutoplay = (bool) getPost('video-autoplay', 0);
        
             $videoURL = getPost('video-url');
            $startTime = toSeconds(getPost('video-start'));
              $endTime = toSeconds(getPost('video-end'));
        
        $videoOutputQueryString = array(
                  'hd' => $videoHD,
                 'rel' => $relatedVideos,
            'autoplay' => $videoAutoplay
        );
        
        $errors[] = empty($videoURL) ? _('Informe a URL do vídeo que será cortado!') : NULL;
        $errors[] = ($startTime == 0 && $endTime == 0) ? _('Você não informou o tempo em que o vídeo deve começar ou terminar!') : NULL;
        $errors[] = ($startTime > 0 && $endTime > 0 ) ? ($startTime >= $endTime) ? _('O tempo em que o vídeo termina deve ser maior do que o tempo que o vídeo começa!') : NULL : NULL;
        $errors = array_filter($errors);
        
        if (count($errors) == 0) {
                 $videoID = YouTube::getVideoID($videoURL);
            $youTubeVideo = YouTube::getYouTubeVideo($videoID);
            
            if ($videoID == NULL && (!$youTubeVideo instanceof YouTubeVideo)) {
                $errors[] = _('Não conseguimos cortar o vídeo do YouTube, provavelmente o link informado é inválido!');
            } else {
                $dataSourceName = 'mysql:host=localhost;dbname=cutzr;charset=utf8';
                $videoStorage = new MySQLYouTubeVideoStorage(new PDO($dataSourceName, 'root', NULL));
                $videoStorage->store($youTubeVideo);

                $videoOutputQueryString = array_merge($videoOutputQueryString, array(
                      'end' => $endTime,
                    'start' => $startTime
                ));

                $videoEndpoint = sprintf('%s/%s?%s', YouTube::VIDEO_ENDPOINT, $videoID, http_build_query($videoOutputQueryString));
                header(sprintf('Location: %s', $videoEndpoint));
            }
        } else {
            $startTime = $startTime > 0 ? date('H:i:s', mktime(0, 0, $startTime)) : NULL;
              $endTime = $endTime > 0 ? date('H:i:s', mktime(0, 0, $endTime)) : NULL;
        }
    }
    
    /**
     * Recupera o attributo "value" para um HTMLInput
     * @param mixed $variable variável com o valor
     * @return string|NULL
     */
    function getHTMLInputValue($variable)
    {
        return empty($variable) ? NULL : sprintf('value="%s"', $variable);
    }

    /**
     * Determina se o campo está marcado através do valor da variável
     * @param mixed $variable variável que determina se o campo está marcado
     * @return string|NULL
     */
    function guessChecked($variable)
    {
        return $variable ? 'checked="checked"' : NULL;
    }
    
?>
<!DOCTYPE HTML>
<html lang="pt-BR" dir="ltr" xmlns:fb="http://ogp.me/ns/fb#">
    <head>
        <title>Cutzr</title>
        <meta charset="UTF-8">
        <meta name="robots" content="index,follow" />
        <meta name="description" content="Corte vídeos do YouTube instantaneamente, de forma intuitiva e rápida!" />
        <meta name="keywords" content="cortar vídeos, vídeos youtube, youtube, cortar, trecho, pedaço, trecho do vídeo" />
        
        <meta property="og:title" name="og:title" content="Cutzr" />
        <meta property="og:type" name="og:type" content="website" />
        <meta property="og:image" name="og:image" content="http://cutzr.starth.com.br/assets/edge/cutzr/img/Cutzr.ico" />
        <meta property="og:url" name="og:url" content="http://cutzr.starth.com.br" />
        <meta property="og:description" name="og:description" content="Corte vídeos do YouTube instantaneamente, de forma intuitiva e rápida!" />
    
        <link rel="icon" href="assets/edge/cutzr/img/Cutzr.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="assets/edge/cutzr/img/Cutzr.ico" type="image/x-icon" />
        <link rel="stylesheet" href="assets/edge/bootstrap/united/css/bootstrap.min.css" type="text/css" />
        <link rel="stylesheet" href="assets/edge/cutzr/css/style.css" media="all" type="text/css" />
        <script type="text/javascript" src="assets/edge/Cutzr.js"></script>
    </head>
    <body>
        <!-- GitHub Ribbon -->
        <a href="https://github.com/Cutzr" target="blank" id="github-ribbon">
            <img src="https://s3.amazonaws.com/github/ribbons/forkme_right_red_aa0000.png" alt="Fork me on GitHub" />
        </a>
        <!-- GitHub Ribbon -->
        <div class="container">
            <div class="head">
                <h1>Corte um vídeo do YouTube :)</h1>
                <p class="lead">Informe o tempo em que você quer que o vídeo comece e/ou termine!</p>
            </div>
            <div class="video">
                <div class="row">
                    <div class="span12">
                        <form method="POST" class="form-horizontal">
                            <fieldset>
                                <div class="video-address span12">
                                    <div class="input-prepend">
                                        <span class="add-on"><i class="icon-film"></i></span>
                                        <input type="text" name="video-url" class="input-xxlarge" <?= isset($videoURL) ? getHTMLInputValue($videoURL) : NULL ?> id="video-url" placeholder="URL do vídeo no YouTube" />
                                    </div>
                                </div>
                                <div class="video-output span12">
                                    <div class="row">
                                        Começar em &nbsp;
                                        <div class="input-prepend">
                                            <span class="add-on"><i class="icon-play"></i></span>
                                            <input class="input-small input-time" type="text" <?= isset($startTime) ? getHTMLInputValue($startTime) : NULL ?> name="video-start" placeholder="00:00:00" />
                                        </div>
                                        &nbsp;
                                        Terminar em &nbsp;
                                        <div class="input-prepend">
                                            <span class="add-on"><i class="icon-stop"></i></span>
                                            <input class="input-small input-time" type="text" <?= isset($endTime) ? getHTMLInputValue($endTime) : NULL ?> name="video-end" placeholder="00:00:00" />
                                        </div>
                                    </div>
                                </div>
                                <div class="span12">
                                    <hr />
                                    <div class="span6">
                                        <div class="video-reproduction-settings">
                                            <label class="checkbox">
                                                <input type="checkbox" name="video-hd" id="video-hd" <?= isset($videoHD) ? guessChecked($videoHD) : NULL ?> /> 
                                                Reproduzir em qualidade HD se possível ?
                                            </label>
                                            <label class="checkbox">
                                                <input type="checkbox" name="video-autoplay" id="video-autoplay" <?= isset($videoAutoplay) ? guessChecked($videoAutoplay) : NULL ?> /> 
                                                Reproduzir vídeo automaticamente ?
                                            </label>
                                            <label class="checkbox">
                                                <input type="checkbox" name="related-videos" id="video-related" <?= isset($relatedVideos) ? guessChecked($relatedVideos) : NULL ?> /> 
                                                Exibir vídeos relacionados ?
                                            </label>
                                        </div>
                                        <div class="ready">
                                            <button class="btn btn-inverse" type="submit">
                                                <i class="icon-ok-circle icon-white"></i>
                                                Pronto, faça a mágica!
                                            </button>
                                        </div>
                                    </div>
                                    <div class="span5" id="footer">
                                        <div class="pull-left">
                                            <a href="http://facebook.com/VCutzr" target="blank">
                                                <i class="icon icon-facebook"></i>
                                            </a>
                                            <i class="icon icon-youtube"></i>
                                        </div>
                                        <div class="pull-left footer-content">
                                            <p>&copy; Cutzr &ndash; 2013</p>
                                            <p><i>&mdash; "Seja simples, simples assim."</i></p>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="social">
                                            <fb:like href="http://www.facebook.com/VCutzr" send="false" layout="box_count" width="450" show_faces="false" action="like">
                                            </fb:like>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                    
                                    if(isset($errors) && ($c = count($errors))) {
                                        echo '<div class="span12">';
                                        echo '<hr />';
                                        echo '<div class="alert alert-info alert-block">';
                                        echo '<h4>Houston, we have a problem!</h4>';
                                        if ($c > 1) {
                                            $errorList = implode('</li><li>', $errors);
                                            printf('<ul class="errors-list"><li>%s</li></ul>', $errorList);
                                        } else 
                                            vprintf('<p>%s</p>', $errors);
                                        
                                        echo '</div>';
                                        echo '</div>';
                                        
                                    }
                                    echo PHP_EOL;
                                ?>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="fb-root"></div>
        <script type="text/javascript">
            (function(d, s, id) {
                if(d.getElementById(id)) return;
                var js = d.createElement(s), f = d.getElementsByTagName(s).item(0);
                js.src = '//connect.facebook.net/pt_BR/all.js#xfbml=1';
                f.parentNode.insertBefore(js, f);
            }(document, 'script', 'facebook-jssdk'));

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-40871303-1']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
        </script>
    </body>
</html>