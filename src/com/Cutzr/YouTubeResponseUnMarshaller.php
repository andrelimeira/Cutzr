<?php

    /**
     * @package com.Cutzr
     * @filesource src/com/Cutzr/YouTubeResponseUnMarshaller.php
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     */

    namespace com\Cutzr;

    use com\Cutzr\YouTubeVideo;
    use \DateTime;

    use \DOMDocument;
    use \DOMXPath;

    class YouTubeResponseUnMarshaller
    {

        /**
         * Extraí informações de uma resposta da API do YouTube
         * @param DOMDocument $document documento com uma resposta da API
         * @return YouTubeVideo
         */
        public function unMarshall(DOMDocument $document)
        {
            $video = new YouTubeVideo();
            $video->setVideoTitle($this->getVideoTitle($document))
                    ->setVideoCategory($this->getVideoCategory($document))
                    ->setVideoViewsCount($this->getVideoViewsCount($document))
                    ->setVideoPublishedDate($this->getVideoPublishedDate($document))
                    ->setVideoDuration($this->getVideoDuration($document));

            return $video;
        }

        /**
         * Recupera a data de publicação do vídeo
         * @param DOMDocument $document
         * @return DateTime
         */
        private function getVideoPublishedDate(DOMDocument $document)
        {
            $publishedDate = $document->getElementsByTagName('published')->item(0)->nodeValue;
            return new DateTime($publishedDate);
        }

        /**
         * Recupera em segundos o tempo de duração do vídeo
         * @param DOMDocument $document
         * @return integer
         */
        private function getVideoDuration(DOMDocument $document)
        {
            $durationNode = $document->getElementsByTagName('duration')->item(0);
            return (integer) $durationNode->getAttribute('seconds');
        }

        /**
         * Recupera o titulo do vídeo
         * @param DOMDocument $document
         * @return string
         */
        private function getVideoTitle(DOMDocument $document)
        {
            $videoTitle = $document->getElementsByTagName('title')->item(0);
            return $videoTitle->nodeValue;
        }

        /**
         * Recupera o número de visualizações do vídeo
         * @param DOMDocument $document
         * @return integer
         */
        private function getVideoViewsCount(DOMDocument $document)
        {
            $viewsCount = $document->getElementsByTagName('statistics')->item(0);
            return (integer) $viewsCount->getAttribute('viewCount');
        }

        /**
         * Recupera a categoria do vídeo
         * @param DOMDocument $document
         * @return string
         */
        private function getVideoCategory(DOMDocument $document)
        {
            $xpath = new DOMXPath($document);
            return $xpath->query('//media:category[@label]')->item(0)->nodeValue;
        }

    }