<?php

    /**
     * @package com.Cutzr
     * @filesource src/com/Cutzr/YouTubeVideoStorage.php
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     */
    namespace com\Cutzr;
    use com\Cutzr\YouTubeVideo;

    interface YouTubeVideoStorage
    {
    
        /**
         * Armazena informações do vídeo em algum lugar
         * @param \YouTubeVideo $video vídeo que será armazenado
         * @return boolean
         */
        public function store(YouTubeVideo $video);
        
    }