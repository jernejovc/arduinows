
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `arduinows`
--

-- --------------------------------------------------------

--
-- Table structure for table `historical_data`
--

CREATE TABLE IF NOT EXISTS `historical_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `station` int(11) NOT NULL,
  `temperature` decimal(5,2) NOT NULL,
  `humidity` decimal(5,1) NOT NULL,
  `pressure` decimal(5,1) NOT NULL,
  `dew` decimal(5,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `station` (`station`),
  KEY `station_2` (`station`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stations`
--

CREATE TABLE IF NOT EXISTS `stations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `hash` (`hash`),
  KEY `id_2` (`id`,`name`,`description`,`location`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `weather_data`
--

CREATE TABLE IF NOT EXISTS `weather_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `station` int(11) NOT NULL,
  `temperature` decimal(5,2) NOT NULL,
  `humidity` decimal(4,1) NOT NULL,
  `pressure` decimal(5,1) NOT NULL,
  `dew` decimal(5,2) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`station`) REFERENCES stations(`id`),
  KEY `station` (`station`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DELIMITER $$
--
-- Events
--
CREATE EVENT `move_data_to_historical_data` ON SCHEDULE EVERY 1 DAY STARTS '2013-01-01 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN 
    INSERT INTO historical_data(timestamp, station, temperature, humidity, pressure, dew)
    SELECT timestamp, station, temperature, humidity, pressure, dew
    FROM weather_data
    WHERE UNIX_TIMESTAMP(SYSDATE()) - UNIX_TIMESTAMP(timestamp) > (366 * 86400);
    
    DELETE FROM weather_data
    WHERE UNIX_TIMESTAMP(SYSDATE()) - UNIX_TIMESTAMP(timestamp) > (366 * 86400);
END$$

DELIMITER ;