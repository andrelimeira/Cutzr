<?php

    /**
     * @package com.Cutzr
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource src/com/Cutzr/MySQLYouTubeVideoStorage.php
     */
    namespace com\Cutzr;
    
    use com\Cutzr\YouTubeVideoStorage;
    use com\Cutzr\YouTubeVideo;
    
    use \PDO;
    use \PDOException;

    class MySQLYouTubeVideoStorage implements YouTubeVideoStorage
    {
        
        /**
         * Instância de PDO
         * @var PDO
         */
        private $dbh;

        /**
         * Define a instância de PDO para comunicar com banco
         * @param PDO $dbh instância de PDO
         * @return MySQLYouTubeVideoStorage
         */
        public function __construct(PDO $dbh)
        {
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_TIMEOUT, 3);
            $this->dbh = $dbh;
        }

        /**
         * Salva as informações de um vídeo no MySQL
         * @param YouTubeVideo $video vídeo do YouTube
         * @return boolean
         */
        public function store(YouTubeVideo $video)
        {
            $statement = <<<STATEMENT
INSERT INTO `youtube_videos`
       (`videoId`, `videoTitle`, `videoCategory`, `videoDuration`, `videoPublishedDate`, `videoViewsCount`, `videoUrl`)
VALUES (:videoID, :title, :category, :duration, :publishedDate, :viewsCount, :videoURL);
STATEMENT;
            
            $stmt = $this->dbh->prepare($statement);
            $stmt->bindValue(':videoID', $video->getVideoId());
            $stmt->bindValue(':title', $video->getVideoTitle());
            $stmt->bindValue(':category', $video->getVideoCategory());
            $stmt->bindValue(':duration', $video->getVideoDuration()->format('H:i:s'));
            $stmt->bindValue(':publishedDate', $video->getVideoPublishedDate()->format('Y-m-d H:i:s'));
            $stmt->bindValue(':viewsCount', $video->getVideoViewsCount());
            $stmt->bindValue(':videoURL', $video->getVideoUrl());
            
            try {
                return ($stmt->execute() === TRUE);
                
            } catch (PDOException $e) {
                /*
                 * A informação é coletada para uso interno, portanto não há porque
                 * exibir erros de banco de dados ao usuário
                 * ...
                 */
                return false;
            }
        }
    
    }