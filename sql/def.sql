-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vygenerováno: Pondělí 24. ledna 2011, 13:54
-- Verze MySQL: 5.1.36
-- Verze PHP: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Databáze: `tepelna`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `act`
--

CREATE TABLE IF NOT EXISTS `act` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` date NOT NULL,
  `expire` date NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `act` varchar(255) NOT NULL,
  `act_seo` varchar(255) NOT NULL,
  `short_text` text NOT NULL,
  `short_text_copy` int(1) NOT NULL DEFAULT '1',
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file` varchar(255) NOT NULL,
  `file_title` varchar(255) NOT NULL,
  `file_type` int(1) NOT NULL COMMENT '0: relative url    1: absolute url',
  `file_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(255) NOT NULL,
  `link_title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(255) NOT NULL,
  `priority` int(1) NOT NULL,
  `type` varchar(10) NOT NULL,
  `user` int(11) DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29518 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `name` varchar(20) NOT NULL COMMENT 'textový',
  `value` text NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `sites`
--

CREATE TABLE IF NOT EXISTS `sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `title_seo` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `t_sites` int(1) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `order_sites` int(11) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `urls`
--

CREATE TABLE IF NOT EXISTS `urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `sef` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rewrite_user_id` int(11) DEFAULT '0' COMMENT 'prepis uzivatele s timto id',
  `login` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `company` varchar(50) NOT NULL,
  `street` varchar(50) NOT NULL,
  `post` varchar(50) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `ico` varchar(20) NOT NULL,
  `dic` varchar(20) NOT NULL,
  `i_name` varchar(50) NOT NULL,
  `i_street` varchar(50) NOT NULL,
  `i_post` varchar(50) NOT NULL,
  `i_zip` varchar(10) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` int(2) NOT NULL DEFAULT '01',
  `news` int(1) NOT NULL DEFAULT '1',
  `hash` varchar(200) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;


--
--  Some data
--

INSERT INTO`sites` (`id`,`title`,`title_seo`,`description`,`t_sites`,`content`,`order_sites`,`active`)
  VALUES ('1','defaulttitulek','','defaultdescription','0','<h1>Nadpish1</h1><p>defaultnítext</p>','1','1');

INSERT INTO  `urls` (`id` ,`url` ,`sef`)
  VALUES (NULL , 'home/index/1', '');

INSERT INTO  `settings` (`name`,`value`) VALUES ('footer','footer-text &copy;2011');
INSERT INTO  `settings` (`name`,`value`) VALUES ('footer_webmaster','1');