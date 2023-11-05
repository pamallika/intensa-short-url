CREATE DATABASE IF NOT EXISTS intensa_dev;
USE intensa_dev;

--
-- TABLE STRUCTURE FOR `links`
--

DROP TABLE IF EXISTS `links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `links` (
                        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                        `origin_uri` varchar(250) NOT NULL,
                        `short_url` varchar(250) NOT NULL UNIQUE,
                        `short_url_hash` text NULL DEFAULT NULL,
                        PRIMARY KEY (`id`),
                        KEY `id` (`id`)
                    );