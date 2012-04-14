-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Lun 05 Septembre 2011 à 22:20
-- Version du serveur: 5.5.9
-- Version de PHP: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `menencia`
--

-- --------------------------------------------------------

--
-- Structure de la table `cr_chocobos`
--

CREATE TABLE `cr_chocobos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `father` int(11) DEFAULT NULL,
  `mother` int(11) DEFAULT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `circuit_id` int(11) DEFAULT NULL,
  `circuit_last` int(11) DEFAULT NULL,
  `gender` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `classe` int(11) NOT NULL DEFAULT '0',
  `colour` int(11) NOT NULL DEFAULT '0',
  `job` int(11) NOT NULL DEFAULT '0',
  `level` int(11) NOT NULL DEFAULT '0',
  `lvl_limit` int(11) NOT NULL DEFAULT '0',
  `xp` int(11) NOT NULL DEFAULT '0',
  `fame` float NOT NULL DEFAULT '0',
  `points` int(2) NOT NULL DEFAULT '0',
  `breath` float NOT NULL DEFAULT '0',
  `energy` float NOT NULL DEFAULT '0',
  `spirit` int(11) NOT NULL DEFAULT '0',
  `moral` float NOT NULL DEFAULT '0',
  `rage` int(11) NOT NULL DEFAULT '0',
  `speed` int(3) NOT NULL DEFAULT '0',
  `intel` int(3) NOT NULL DEFAULT '0',
  `endur` int(3) NOT NULL DEFAULT '0',
  `max_speed` float NOT NULL DEFAULT '0',
  `nb_trainings` int(11) NOT NULL DEFAULT '0',
  `nb_compets` int(11) NOT NULL DEFAULT '0',
  `nb_rides` int(11) NOT NULL DEFAULT '0',
  `mated` int(11) NOT NULL DEFAULT '0',
  `nb_mated` int(11) NOT NULL DEFAULT '0',
  `birthday` int(11) NOT NULL DEFAULT '0',
  `moved` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `circuit_id` (`circuit_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=375 ;

-- --------------------------------------------------------

--
-- Structure de la table `cr_circuits`
--

CREATE TABLE `cr_circuits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `race` int(11) NOT NULL DEFAULT '0',
  `surface` int(11) NOT NULL DEFAULT '0',
  `location_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `classe` int(11) NOT NULL DEFAULT '0',
  `length` int(11) NOT NULL DEFAULT '0',
  `start` int(11) NOT NULL DEFAULT '0',
  `finished` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `location_id` (`location_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=32473 ;

-- --------------------------------------------------------

--
-- Structure de la table `cr_comments`
--

CREATE TABLE `cr_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `created` int(11) NOT NULL,
  `updated` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=914 ;

-- --------------------------------------------------------

--
-- Structure de la table `cr_designs`
--

CREATE TABLE `cr_designs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8 NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `general` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Structure de la table `cr_effects`
--

CREATE TABLE `cr_effects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `equipment_id` int(11) DEFAULT NULL,
  `name` varchar(20) CHARACTER SET utf8 NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `equipment_id` (`equipment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=404 ;

-- --------------------------------------------------------

--
-- Structure de la table `cr_equipment`
--

CREATE TABLE `cr_equipment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `chocobo_id` int(11) DEFAULT NULL,
  `name` varchar(10) CHARACTER SET utf8 NOT NULL,
  `rarity` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `resistance` int(11) NOT NULL DEFAULT '0',
  `element` int(11) NOT NULL DEFAULT '0',
  `price` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `chocobo_id` (`chocobo_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Structure de la table `cr_facts`
--

CREATE TABLE `cr_facts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `result_id` int(11) DEFAULT NULL,
  `action` varchar(40) CHARACTER SET utf8 NOT NULL,
  `values` varchar(30) CHARACTER SET utf8 NOT NULL,
  `general` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `result_id` (`result_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=20731 ;

-- --------------------------------------------------------

--
-- Structure de la table `cr_favorites`
--

CREATE TABLE `cr_favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Structure de la table `cr_flows`
--

CREATE TABLE `cr_flows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=97 ;

-- --------------------------------------------------------

--
-- Structure de la table `cr_interests`
--

CREATE TABLE `cr_interests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `comment_id` int(11) DEFAULT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `comment_id` (`comment_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `cr_locations`
--

CREATE TABLE `cr_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `speed` int(11) NOT NULL,
  `intel` int(11) NOT NULL,
  `endur` int(11) NOT NULL,
  `classe` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Structure de la table `cr_notifs`
--

CREATE TABLE `cr_notifs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=112 ;

-- --------------------------------------------------------

--
-- Structure de la table `cr_nuts`
--

CREATE TABLE `cr_nuts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` int(11) NOT NULL,
  `rarity` int(11) NOT NULL DEFAULT '0',
  `gender` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `level` int(11) NOT NULL DEFAULT '0',
  `speed` int(11) NOT NULL DEFAULT '0',
  `intel` int(11) NOT NULL DEFAULT '0',
  `endur` int(11) NOT NULL DEFAULT '0',
  `colour` int(11) NOT NULL DEFAULT '0',
  `price` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=107 ;

-- --------------------------------------------------------

--
-- Structure de la table `cr_results`
--

CREATE TABLE `cr_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `circuit_id` int(11) DEFAULT NULL,
  `chocobo_id` int(11) DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  `avg_speed` float NOT NULL DEFAULT '0',
  `breath` int(11) NOT NULL DEFAULT '0',
  `energy` int(11) NOT NULL DEFAULT '0',
  `spirit` int(11) NOT NULL DEFAULT '0',
  `moral` int(11) NOT NULL DEFAULT '0',
  `xp` int(11) NOT NULL DEFAULT '0',
  `gils` int(11) NOT NULL DEFAULT '0',
  `fame` int(11) NOT NULL DEFAULT '0',
  `rage` int(11) NOT NULL DEFAULT '0',
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `circuit_id` (`circuit_id`),
  KEY `chocobo_id` (`chocobo_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=29555 ;

-- --------------------------------------------------------

--
-- Structure de la table `cr_roles`
--

CREATE TABLE `cr_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Structure de la table `cr_roles_users`
--

CREATE TABLE `cr_roles_users` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_role_id` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cr_sites`
--

CREATE TABLE `cr_sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `max_connected` int(11) NOT NULL,
  `time_connected` int(11) NOT NULL,
  `version_link` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `version_number` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `closed` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `cr_successes`
--

CREATE TABLE `cr_successes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title_id` int(11) DEFAULT NULL,
  `created` int(11) NOT NULL,
  `seen` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `title_id` (`title_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=391 ;

-- --------------------------------------------------------

--
-- Structure de la table `cr_titles`
--

CREATE TABLE `cr_titles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `nbr_users` int(11) NOT NULL DEFAULT '0',
  `pos` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=299 ;

-- --------------------------------------------------------

--
-- Structure de la table `cr_topics`
--

CREATE TABLE `cr_topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `shared` tinyint(4) NOT NULL,
  `locked` tinyint(4) NOT NULL,
  `archived` tinyint(4) NOT NULL,
  `created` int(11) NOT NULL,
  `updated` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=129 ;

-- --------------------------------------------------------

--
-- Structure de la table `cr_users`
--

CREATE TABLE `cr_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `fame` float NOT NULL DEFAULT '1',
  `gender` int(11) NOT NULL DEFAULT '0',
  `birthday` text COLLATE utf8_unicode_ci NOT NULL,
  `image` text COLLATE utf8_unicode_ci NOT NULL,
  `locale` text COLLATE utf8_unicode_ci NOT NULL,
  `design_id` int(11) DEFAULT NULL,
  `quest` int(11) NOT NULL DEFAULT '0',
  `gils` int(11) NOT NULL DEFAULT '200',
  `boxes` int(3) NOT NULL DEFAULT '2',
  `items` int(11) NOT NULL DEFAULT '10',
  `nbr_birthdays` int(11) NOT NULL DEFAULT '0',
  `nbr_chocobos_saled` int(11) NOT NULL DEFAULT '0',
  `notif_forum` tinyint(1) NOT NULL DEFAULT '0',
  `notif_site` tinyint(1) NOT NULL DEFAULT '1',
  `tutorial` tinyint(1) NOT NULL DEFAULT '0',
  `version` tinyint(1) NOT NULL DEFAULT '0',
  `api` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `registered` int(11) NOT NULL DEFAULT '0',
  `changed` int(11) NOT NULL DEFAULT '0',
  `connected` int(11) NOT NULL DEFAULT '0',
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `design_id` (`design_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=198 ;

-- --------------------------------------------------------

--
-- Structure de la table `cr_vegetables`
--

CREATE TABLE `cr_vegetables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` text CHARACTER SET utf8 NOT NULL,
  `rarity` int(11) NOT NULL DEFAULT '0',
  `value` int(11) NOT NULL DEFAULT '0',
  `price` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1919 ;

-- --------------------------------------------------------

--
-- Structure de la table `cr_waves`
--

CREATE TABLE `cr_waves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `circuit_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` varchar(140) COLLATE utf8_unicode_ci NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `circuit_id` (`circuit_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1314 ;
