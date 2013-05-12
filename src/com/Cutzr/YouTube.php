<?php

    /**
     * @package com.Cutzr
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource src/com/Cutzr/YouTube.php
     */

    namespace com\Cutzr;
    
    use com\Cutzr\YouTubeResponseUnMarshaller;
    use \DOMDocument;

    class YouTube
    {
        
        /**
         * Código HTTP
         * @const integer
         */
        const HTTP_CODE_OK = 200;
        
        /**
         * Endpoint de vídeos do YouTube
         * @const string
         */
        const VIDEO_ENDPOINT = 'http://youtube.com/v';

        /**
         * Recupera o ID do vídeo através da URL
         * @param string $videoURL endereço do vídeo no youtube
         * @return string
         */
        public static function getVideoID($videoURL)
        {
            $expression = '#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#';
            preg_match($expression, $videoURL, $matches);
            return array_shift($matches);
        }

        /**
         * Recupera informações de um vídeo no YouTube
         * @param string $videoID identificador do vídeo
         * @return \YouTubeVideo|NULL
         */
        public static function getYouTubeVideo($videoID)
        {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, sprintf('http://gdata.youtube.com/feeds/api/videos/%s', $videoID));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPGET, true);
            $responseXML = curl_exec($curl);
            
            if(curl_getinfo($curl, CURLINFO_HTTP_CODE) === YouTube::HTTP_CODE_OK) {
                
                $document = new DOMDocument('1.0', 'UTF-8');
                $document->preserveWhiteSpace = false;
                $document->loadXML($responseXML);
                
                $unMarshaller = new YouTubeResponseUnMarshaller();
                $video = $unMarshaller->unMarshall($document);
                
                $video->setVideoId($videoID);
                $video->setVideoUrl(sprintf('http://youtube.com/watch/?v=%s', $videoID));
                return $video;
            }
        }
        
        /**
         * Atalho para recuperar informações do vídeo do YouTube
         * @param string $videoURL url do vídeo no YouTube
         * @return YouTubeVideo
         */
        public static function getYoutubeVideoByURL($videoURL) 
        {
            $videoID = YouTube::getVideoID($videoURL);
            return YouTube::getYouTubeVideo($videoID);
        }
        
    }