<?php

    /**
     * @package com.Cutzr
     * @filesource src/com/Cutzr/YouTubeVideo.php
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     */
    namespace com\Cutzr;
    use \DateTime;
    
    class YouTubeVideo
    {
        
        /**
         * Identificador do vídeo no YouTube
         * @var string
         */
        private $videoId;
        
        /**
         * Título do vídeo
         * @var string
         */
        private $videoTitle;
        
        /**
         * Categoria do vídeo
         * @var string
         */
        private $videoCategory;
        
        /**
         * Duração do vídeo
         * @var integer
         */
        private $videoDuration;
        
        /**
         * Data de publicação do vídeo no YouTube
         * @var string
         */
        private $videoPublishedDate;
        
        /**
         * Quantidade de reproduções do vídeo
         * @var integer 
         */
        private $videoViewsCount;
        
        /**
         * URL do vídeo no YouTube
         * @var string
         */
        private $videoUrl;
        
        /**
         * Define o ID do vídeo no YouTube
         * @param string $videoId identificador do vídeo
         * @return YouTubeVideo
         */
        public function setVideoId($videoId)
        {
            $this->videoId = $videoId;
            return $this;
        }

        /**
         * Define o título do vídeo
         * @param string $videoTitle título do vídeo
         * @return YouTubeVideo
         */
        public function setVideoTitle($videoTitle)
        {
            $this->videoTitle = $videoTitle;
            return $this;
        }
        
        /**
         * Recupera a categoria do vídeo
         * @param string $videoCategory categoria do vídeo
         * @return YouTubeVideo
         */
        public function setVideoCategory($videoCategory)
        {
            $this->videoCategory = $videoCategory;
            return $this;
        }

        /**
         * Define o tempo de duração do vídeo
         * @param string $videoDuration duração do vídeo
         * @return YouTubeVideo
         */
        public function setVideoDuration($videoDuration)
        {
            $this->videoDuration = $videoDuration;
            return $this;
        }

        /**
         * Define a data de publicação do vídeo
         * @param DateTime $videoPublishedDate data de publicação
         * @return YouTubeVideo
         */
        public function setVideoPublishedDate(DateTime $videoPublishedDate)
        {
            $this->videoPublishedDate = $videoPublishedDate->format('Y-m-d H:i:s');
            return $this;
        }

        /**
         * Define a quantidade de visualizações no vídeo
         * @param integer $videoViewsCount quantidade de visualizações
         * @return YouTubeVideo
         */
        public function setVideoViewsCount($videoViewsCount)
        {
            $this->videoViewsCount = $videoViewsCount;
            return $this;
        }

        /**
         * Define a URL do vídeo no YouTube
         * @param string $videoUrl URL do vídeo
         * @return YouTubeVideo
         */
        public function setVideoUrl($videoUrl)
        {
            $this->videoUrl = $videoUrl;
            return $this;
        }
        
        /**
         * Recupera o ID do vídeo
         * @return string
         */
        public function getVideoId()
        {
            return $this->videoId;
        }

        /**
         * Recupera o titulo do vídeo
         * @return string
         */
        public function getVideoTitle()
        {
            return $this->videoTitle;
        }
        
        /**
         * Recupera a categoria do vídeo
         * @return string
         */
        public function getVideoCategory()
        {
            return $this->videoCategory;
        }

        /**
         * Recupera o tempo de duração do vídeo
         * @return DateTime
         */
        public function getVideoDuration()
        {
            $videoDuration = round($this->videoDuration);
            $H = ($videoDuration / 3600);
            $i = ($videoDuration / 60 % 60);
            $s = ($videoDuration % 60);
            
            return DateTime::createFromFormat('H:i:s', sprintf('%02d:%02d:%02d', $H, $i, $s));
        }

        /**
         * Recupera a data de publicação do vídeo no YouTube
         * @return DateTime
         */
        public function getVideoPublishedDate()
        {
            return DateTime::createFromFormat('Y-m-d H:i:s', $this->videoPublishedDate);
        }

        /**
         * Recupera a quantidade de visualizações no vídeo
         * @return integer
         */
        public function getVideoViewsCount()
        {
            return $this->videoViewsCount;
        }
        
        /**
         * Recupera a URL do vídeo no YouTube
         * @return string
         */
        public function getVideoUrl()
        {
            return $this->videoUrl;
        }

    }