/*
Navicat MySQL Data Transfer

Source Server         : vega.beget.ru
Source Server Version : 50616
Source Host           : vega.beget.ru:3306
Source Database       : katiho_test

Target Server Type    : MYSQL
Target Server Version : 50616
File Encoding         : 65001

Date: 2014-06-26 12:04:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for image_tags
-- ----------------------------
DROP TABLE IF EXISTS `image_tags`;
CREATE TABLE `image_tags` (
  `image_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  KEY `tag_id` (`tag_id`),
  KEY `image_id` (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for images
-- ----------------------------
DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `like_count` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `date` (`date`,`like_count`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=997846 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for likes
-- ----------------------------
DROP TABLE IF EXISTS `likes`;
CREATE TABLE `likes` (
  `user_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `image_id` (`image_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tags
-- ----------------------------
DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9991 DEFAULT CHARSET=utf8;
