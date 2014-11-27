SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `slogger`
--

-- --------------------------------------------------------

--
-- Table structure for table `errors`
--

CREATE TABLE IF NOT EXISTS `errors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `priority` enum('notice','low','medium','fatal') COLLATE utf8_czech_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `message` text COLLATE utf8_czech_ci NOT NULL,
  `service` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `created` datetime NOT NULL,
  `hash` char(32) COLLATE utf8_czech_ci NOT NULL,
  `reported` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`),
  KEY `reported` (`reported`),
  KEY `priority` (`priority`),
  KEY `created` (`created`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=3700 ;

-- --------------------------------------------------------

--
-- Table structure for table `errors_reported`
--

CREATE TABLE IF NOT EXISTS `errors_reported` (
  `hash` char(32) COLLATE utf8_czech_ci NOT NULL,
  `reported` datetime NOT NULL,
  PRIMARY KEY (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `pass` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `lastLoggedIn` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=3 ;

