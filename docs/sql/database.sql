-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Lun 23 Juillet 2012 à 13:32
-- Version du serveur: 5.5.9
-- Version de PHP: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `chocobo-riding`
--

-- --------------------------------------------------------

--
-- Structure de la table `chocobos`
--

CREATE TABLE `chocobos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `father` int(11) DEFAULT NULL,
  `mother` int(11) DEFAULT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `race_id` int(11) DEFAULT NULL,
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
  `pl` int(10) unsigned NOT NULL DEFAULT '0',
  `hp` int(11) NOT NULL DEFAULT '0',
  `mp` int(11) NOT NULL DEFAULT '0',
  `moral` float NOT NULL DEFAULT '0',
  `rage` int(11) NOT NULL DEFAULT '0',
  `speed` int(3) NOT NULL DEFAULT '0',
  `intel` int(3) NOT NULL DEFAULT '0',
  `endur` int(3) NOT NULL DEFAULT '0',
  `max_speed` float NOT NULL DEFAULT '0',
  `nb_races` int(11) NOT NULL DEFAULT '0',
  `mated` int(11) NOT NULL DEFAULT '0',
  `nb_mated` int(11) NOT NULL DEFAULT '0',
  `birthday` int(11) NOT NULL DEFAULT '0',
  `moved` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `race_id` (`race_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `circuits`
--

CREATE TABLE `circuits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `classe` int(11) NOT NULL,
  `pl` int(10) unsigned NOT NULL,
  `length` int(10) unsigned NOT NULL,
  `gils` int(10) unsigned NOT NULL DEFAULT '0',
  `xp` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `created` int(11) NOT NULL,
  `updated` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `comments_favorites`
--

CREATE TABLE `comments_favorites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `comment_notifications`
--

CREATE TABLE `comment_notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topic_id` int(10) unsigned NOT NULL,
  `comment_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `deleted_users`
--

CREATE TABLE `deleted_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `old_id` int(10) unsigned NOT NULL,
  `name` varchar(12) NOT NULL,
  `email` varchar(50) NOT NULL,
  `created` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `designs`
--

CREATE TABLE `designs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8 NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `general` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `discussions`
--

CREATE TABLE `discussions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` int(10) unsigned NOT NULL,
  `updated` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `effects`
--

CREATE TABLE `effects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `equipment_id` int(11) DEFAULT NULL,
  `name` varchar(20) CHARACTER SET utf8 NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `equipment_id` (`equipment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `equipment`
--

CREATE TABLE `equipment` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `flows`
--

CREATE TABLE `flows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `discussion_id` int(11) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`discussion_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `discussion_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `created` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`discussion_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `message_notifications`
--

CREATE TABLE `message_notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `discussion_id` int(10) unsigned NOT NULL,
  `message_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `nuts`
--

CREATE TABLE `nuts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` int(11) NOT NULL,
  `rarity` int(11) NOT NULL DEFAULT '0',
  `gender` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `level` int(11) NOT NULL DEFAULT '0',
  `price` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `nut_effects`
--

CREATE TABLE `nut_effects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nut_id` int(10) unsigned NOT NULL,
  `name` varchar(10) NOT NULL,
  `value` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `races`
--

CREATE TABLE `races` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `circuit_id` int(11) DEFAULT NULL,
  `start` int(11) NOT NULL DEFAULT '0',
  `script` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `circuit_id` (`circuit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `race_id` int(11) DEFAULT NULL,
  `chocobo_id` int(11) DEFAULT NULL,
  `name` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `box` int(10) unsigned NOT NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  `time` float unsigned NOT NULL,
  `course_avg` float unsigned NOT NULL,
  `notified` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `chocobo_id` (`chocobo_id`),
  KEY `race_id` (`race_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles_users`
--

CREATE TABLE `roles_users` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_role_id` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sites`
--

CREATE TABLE `sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `max_connected` int(11) NOT NULL,
  `time_connected` int(11) NOT NULL,
  `version_link` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `version_number` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `closed` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `successes`
--

CREATE TABLE `successes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title_id` int(11) DEFAULT NULL,
  `created` int(11) NOT NULL,
  `seen` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `title_id` (`title_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tags`
--

CREATE TABLE `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ref` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tags_topics`
--

CREATE TABLE `tags_topics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(10) unsigned NOT NULL,
  `topic_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `titles`
--

CREATE TABLE `titles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `nbr_users` int(11) NOT NULL DEFAULT '0',
  `pos` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `topics`
--

CREATE TABLE `topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `locked` tinyint(4) NOT NULL,
  `archived` tinyint(4) NOT NULL,
  `created` int(11) NOT NULL,
  `updated` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
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
  `shop` int(10) unsigned NOT NULL DEFAULT '0',
  `nbr_birthdays` int(11) NOT NULL DEFAULT '0',
  `nbr_chocobos_saled` int(11) NOT NULL DEFAULT '0',
  `notif_forum` tinyint(1) NOT NULL DEFAULT '0',
  `notif_site` tinyint(1) NOT NULL DEFAULT '1',
  `tutorial` tinyint(1) NOT NULL DEFAULT '0',
  `version` tinyint(1) NOT NULL DEFAULT '0',
  `api` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `updated` int(11) unsigned NOT NULL DEFAULT '0',
  `connected` int(11) unsigned NOT NULL DEFAULT '0',
  `activated` int(11) unsigned NOT NULL DEFAULT '0',
  `banned` int(11) unsigned NOT NULL DEFAULT '0',
  `deleted` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `design_id` (`design_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `vegetables`
--

CREATE TABLE `vegetables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` text CHARACTER SET utf8 NOT NULL,
  `rarity` int(11) NOT NULL DEFAULT '0',
  `level` int(11) NOT NULL DEFAULT '0',
  `price` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `vegetable_effects`
--

CREATE TABLE `vegetable_effects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vegetable_id` int(10) unsigned NOT NULL,
  `name` varchar(10) NOT NULL,
  `value` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `waves`
--

CREATE TABLE `waves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `race_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` varchar(140) COLLATE utf8_unicode_ci NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `race_id` (`race_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
