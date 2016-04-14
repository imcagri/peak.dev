SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Veritabanı: `permaxis_peak`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `gifts`
--

CREATE TABLE IF NOT EXISTS `gifts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` int(10) unsigned NOT NULL,
  `receiver_id` int(10) unsigned NOT NULL,
  `gift_id` int(10) unsigned NOT NULL,
  `pending` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  KEY `date` (`date`),
  KEY `gift_id` (`gift_id`),
  KEY `pending` (`pending`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `gift_types`
--

CREATE TABLE IF NOT EXISTS `gift_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `img` varchar(50) NOT NULL,
  `order` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order` (`order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Tablo döküm verisi `gift_types`
--

INSERT INTO `gift_types` (`id`, `title`, `price`, `img`, `order`) VALUES
(1, 'Flower', '7.00', 'flower', 1),
(2, 'Love', '12.00', 'heart', 2),
(3, 'Coin', '3.00', 'coin', 3);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` char(50) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `email` char(50) NOT NULL,
  `coin` decimal(10,2) NOT NULL,
  `img` varchar(50) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `password` (`password`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `email`, `coin`, `img`, `status`) VALUES
(1, 'nikola', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Nikola Tesla', '', '1155.00', 'nikola', 0),
(2, 'albert', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Albert Einstein', '', '916.60', 'albert', 0),
(3, 'niels', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Niels Bohr', '', '875.00', 'niels', 0),
(4, 'stephen', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Stephen Hawking', '', '461.33', 'stephen', 0),
(5, 'aziz', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Aziz Nesin', '', '868.00', 'aziz', 0);

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `gifts`
--
ALTER TABLE `gifts`
  ADD CONSTRAINT `FK_gifts_gift_types` FOREIGN KEY (`gift_id`) REFERENCES `gift_types` (`id`),
  ADD CONSTRAINT `FK_gifts_users` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_gifts_users_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
