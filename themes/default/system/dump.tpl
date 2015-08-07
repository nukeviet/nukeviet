-- NUKEVIET 4.0
-- Module: Database
-- http://www.nukeviet.vn
--
-- Host: {|SERVER_NAME|}
-- Generation Time: {|GENERATION_TIME|}
-- Server version: {|SQL_VERSION|}
-- PHP Version: {|PHP_VERSION|}

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET SESSION `character_set_client`='utf8';
SET SESSION `character_set_results`='utf8';
SET SESSION `character_set_connection`='utf8';
SET SESSION `collation_connection`='{|DB_COLLATION|}';
SET NAMES 'utf8';
ALTER DATABASE DEFAULT CHARACTER SET `utf8` COLLATE `{|DB_COLLATION|}`;

--
-- Database: `{|DB_NAME|}`
--@@@


-- ---------------------------------------


--
-- Table structure for table `{|TABLE_NAME|}`
--

DROP TABLE IF EXISTS `{|TABLE_NAME|}`;
{|TABLE_STR|};@@@

--
-- Dumping data for table `{|TABLE_NAME|}`
--

INSERT INTO `{|TABLE_NAME|}` VALUES