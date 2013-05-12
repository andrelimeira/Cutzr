CREATE TABLE IF NOT EXISTS `youtube_videos` (
    `videoId`               CHAR(11) NOT NULL,
    `videoTitle`            VARCHAR(120) COLLATE utf8_general_ci NOT NULL,
    `videoCategory`         CHAR(20) COLLATE utf8_general_ci NOT NULL,
    `videoDuration`         TIME NOT NULL,
    `videoPublishedDate`    DATETIME NOT NULL,
    `videoViewsCount`       DECIMAL(10,2) NOT NULL,
    `videoUrl`              TEXT NOT NULL COLLATE utf8_general_ci NOT NULL,
    PRIMARY KEY(`videoId`),
    UNIQUE KEY(
        `videoId`,
        `videoTitle`
    ), INDEX(`videoTitle`),
       INDEX(`videoCategory`),
       INDEX(`videoViewsCount`))
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci;