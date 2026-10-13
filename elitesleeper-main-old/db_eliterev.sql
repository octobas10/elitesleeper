-- phpMyAdmin SQL Dump
-- version 2.11.9.4
-- http://www.phpmyadmin.net
--
-- Host: 10.6.166.144
-- Generation Time: Aug 26, 2010 at 10:31 PM
-- Server version: 5.0.91
-- PHP Version: 5.2.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_elitelitesleeper`
--

-- --------------------------------------------------------

--
-- Table structure for table `elitesleeper_admin`
--

CREATE TABLE `elitesleeper_admin` (
  `id` int(1) NOT NULL auto_increment,
  `admin_name` varchar(200) NOT NULL,
  `admin_password` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `elitesleeper_admin`
--

INSERT INTO `elitesleeper_admin` VALUES(1, 'admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `elitesleeper_country`
--

CREATE TABLE `elitesleeper_country` (
  `id` int(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `elitesleeper_country`
--

INSERT INTO `elitesleeper_country` VALUES(1, 'United States ', 'USA');
INSERT INTO `elitesleeper_country` VALUES(2, 'Canada', 'canada');

-- --------------------------------------------------------

--
-- Table structure for table `elitesleeper_customer`
--

CREATE TABLE `elitesleeper_customer` (
  `id` bigint(10) NOT NULL auto_increment,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email_id` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `creation_date` date NOT NULL,
  `bill_street` varchar(100) NOT NULL,
  `bill_state` int(5) NOT NULL,
  `bill_country` int(5) NOT NULL,
  `bill_city` varchar(100) NOT NULL,
  `bill_zip` varchar(20) NOT NULL,
  `bill_phone` varchar(20) NOT NULL,
  `ship_street` varchar(20) NOT NULL,
  `ship_city` varchar(100) NOT NULL,
  `ship_state` int(5) NOT NULL,
  `ship_zip` varchar(20) NOT NULL,
  `ship_phone` varchar(20) NOT NULL,
  `ship_country` int(5) NOT NULL,
  `status` varchar(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `elitesleeper_customer`
--

INSERT INTO `elitesleeper_customer` VALUES(15, 'amar', 'gupta', 'amar@braintechnosys.com', '1111', '2010-08-09', 'mawana', 2, 1, 'meerut', '123456', '213132', 'mawana', 'mawana', 2, '123456', '213132', 1, '1');
INSERT INTO `elitesleeper_customer` VALUES(18, 'amar', 'gupta', 'amar@braintechnosys.com', '1111', '2010-08-09', 'gdfgdg', 14, 1, 'ggsdg', 'gds', 'dsg', 'gdfgdg', 'ggsdg', 14, 'gds', 'dsg', 1, '0');
INSERT INTO `elitesleeper_customer` VALUES(19, 'test', 'test', 'amar@braintechnosys.com', '1111', '2010-08-11', 'fdsff', 59, 2, 'fdsfffd', '2424', '42141244', 'fdsff', 'fdsfffd', 59, '2424', '42141244', 2, '0');
INSERT INTO `elitesleeper_customer` VALUES(20, 'amar', 'gupta', 'amar@braintechnosys.com', '1111', '2010-08-11', 'xczxc', 20, 1, 'xzc', '43545', '43534', 'xczxc', 'xczxc', 5, '43545', '43534', 1, '1');
INSERT INTO `elitesleeper_customer` VALUES(21, 'xbxcb', 'xcbx', 'amar@braintechnosys.com', '1111', '2010-08-12', 'dvdxxcv', 15, 1, 'vcxvxcv', '2423443', '43242342', 'dvdxxcv', 'vcxvxcv', 15, '2423443', '43242342', 1, '1');
INSERT INTO `elitesleeper_customer` VALUES(22, 'fdhfdh', 'gupta', 'amar@braintechnosys.com', '1111', '2010-08-17', 'xczxc', 1, 1, 'meerut', '3525', '53523', 'xczxc', 'meerut', 1, '3525', '53523', 1, '0');
INSERT INTO `elitesleeper_customer` VALUES(23, 'fdhfdh', 'bff', 'amar@braintechnosys.com', '1111', '2010-08-17', 'vcn', 1, 1, 'meerut', '5325', '325', 'vcn', 'meerut', 1, '5325', '325', 1, '1');
INSERT INTO `elitesleeper_customer` VALUES(24, 'fdhfdh', 'gupta', 'amar@braintechnosys.com', '1111', '2010-08-17', 'xczxc', 1, 1, 'vcn', '2423443', '43534', 'xczxc', 'vcn', 1, '2423443', '43534', 1, '1');
INSERT INTO `elitesleeper_customer` VALUES(25, 'amar', 'gupta', 'amar@braintechnosys.com', '1111', '2010-08-18', 'xczxc', 55, 2, 'xzc', '2423443', '43534', 'xczxc', 'xzc', 55, '2423443', '43534', 2, '1');
INSERT INTO `elitesleeper_customer` VALUES(26, 'Abhay', 'singh', 'abhay@braintechnosys.com', '1111', '2010-08-18', 'norteh', 51, 1, 'bellinghum', '98226', '3606762772', 'norteh', 'bellinghum', 51, '98226', '3606762772', 1, '0');
INSERT INTO `elitesleeper_customer` VALUES(27, 'Abhay', 'singh', 'abhay@braintechnosys.com', '1111', '2010-08-18', 'norteh', 51, 1, 'bellinghum', '98226', '3606762772', 'norteh', 'bellinghum', 51, '98226', '3606762772', 1, '1');
INSERT INTO `elitesleeper_customer` VALUES(28, 'amar', 'gupta', 'amar@braintechnosys.com', '1111', '2010-08-19', 'xczxc', 3, 1, 'xzc', '4366436', '636', 'xczxc', 'xzc', 3, '4366436', '636', 1, '0');
INSERT INTO `elitesleeper_customer` VALUES(29, 'fdhfdh', 'gupta', 'amar@braintechnosys.com', '1111', '2010-08-19', 'xczxc', 59, 2, 'meerut', '45325', '5325', 'xczxc', 'meerut', 59, '45325', '5325', 2, '0');
INSERT INTO `elitesleeper_customer` VALUES(30, 'amar', 'gupta', 'amar@braintechnosys.com', '1111', '2010-08-19', 'mawana', 19, 1, 'meerut', '54364366', '436436', 'mawana', 'meerut', 19, '54364366', '436436', 1, '1');

-- --------------------------------------------------------

--
-- Table structure for table `elitesleeper_faq`
--

CREATE TABLE `elitesleeper_faq` (
  `id` int(4) NOT NULL auto_increment,
  `ques` varchar(255) NOT NULL,
  `ans` text NOT NULL,
  `create_date` date NOT NULL,
  `status` enum('0','1') NOT NULL default '0' COMMENT '0=>inactive, 1=>active',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `elitesleeper_faq`
--

INSERT INTO `elitesleeper_faq` VALUES(4, 'About UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout Us', 'About UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout UsAbout Us', '2010-07-14', '1');
INSERT INTO `elitesleeper_faq` VALUES(2, 'cbxcb', 'bxcbxbbb', '2010-07-22', '1');

-- --------------------------------------------------------

--
-- Table structure for table `elitesleeper_pages`
--

CREATE TABLE `elitesleeper_pages` (
  `id` int(3) NOT NULL auto_increment,
  `title` varchar(300) NOT NULL,
  `heading` varchar(300) NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `details` text NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `elitesleeper_pages`
--

INSERT INTO `elitesleeper_pages` VALUES(1, 'Elite Rev How It Works', 'Elite Rev has an awakening effect without the jitters, a fatigue fighting ability with no crash, a mood enhancing ability to turn around a bad day.', 'Elite rev, super energy, hottest new energy product, herbs with miraculous powers, jeevani, guarana, rhodiola, L carnitine tartrate, L phenyalanine, party all night long, get high, feel high, endurance, stay awake, curb appetite, appetite suppressant, safe and legal way to get high, rev up, amp up', 'Elite Rev synergistically combines rare fatigue-fighting herbs with time-tested ingredients, to formulate the first supplement designed with the upscale party goer’s needs in mind.  Our proprietary blend promotes social poise, encourages mental alertness, while putting the energy back in your soul; Energy that you’ll need for a night out on the town.  Elite Rev effectively counters both physical fatigue and mental stress, the effects a demanding day puts on us all. ', '<p><strong><span style="font-family: Arial; font-size: large; color:#0b9211;">Elite</span></strong><span style="font-size: 12pt;color:#0b9211; font-family: ''Times New Roman'';"><span style="font-family: Arial; font-size: small;"><strong><span style="font-size: large;"> Rev -  Super energy for every day life<span style="color: #1c198a;"><br /></span></span> Mood Enhancing, Fatigue Fighting, Social Energy  for people  on the go</strong><br /><br /></span></span> <span style="color:#444444;font:13px Arial, Helvetica, sans-serif;">Elite Rev synergistically combines  rare  fatigue-fighting herbs with time-tested ingredients, to formulate the  first  supplement designed with the upscale partygoer&rsquo;s needs in mind.&nbsp; Our  proprietary  blend promotes social poise, encourages mental alertness, while putting  the  energy back in your soul; Energy that you&rsquo;ll need for a night out on the  town.&nbsp;  Nocturnal Nutrition effectively counters both physical fatigue and  mental  stress, the effects a demanding day puts on us all.&nbsp; We guarantee it.*</span></p>\r\n<p align="left"><span style="color:#444444;font:13px Arial, Helvetica, sans-serif;"> This is the first supplement designed specifically for the Night Life.&nbsp;  It is  potent herbal complex, combining both new and old ingredients setting it  far  apart from anything currently on the market.&nbsp; If your not satisfied for  any  reason return it for 110% of your money back.&nbsp; The key ingredient in  Jeevani is  the Arogyappacha plant.&nbsp; A team of scientists from the All India  Coordinated  Research Project on Ethnobiology (AICRPE) had stumbled on this plant  during the  course of their expedition in the Agasthyar hills in 1987.&nbsp; The local  guides  during the expedition&nbsp;were not stopping for rest.&nbsp; The local tribal  guides  accompanying the American scientists revealed the secret of the herb  which they  claimed to have miraculous powers.&nbsp; In a project spanning eight years,  the  institute went on to refine and validate the medicine now called  Jeevani.</span></p>\r\n<p align="left"><span style="color:#444444;font:13px Arial, Helvetica, sans-serif;"> Rhodiola&nbsp;once used by the KGB and studied in Russia for years has been claimed  to be an antidepressant and central nervous system enhancer.&nbsp; In Nocturnal  Nutrition it combines with the Arogyappacha Plant and acts as an anxiety  suppressor putting you at ease during the most stressful of situations going  against any&nbsp;negative mindset&nbsp;that otherwise would be present.&nbsp; Research reveals  it also decreases irritability and headaches.&nbsp; The active effects have been  attributed primarily for its ability to influence levels of activity&nbsp;of  monoamines and opioid peptides such as beta-endorphines.&nbsp; It is 100% safe and  has absolutely no negative&nbsp;side effects.<span style="color: black;"> </span> </span></p>\r\n<p align="justify"><span style="color:#444444;font:13px Arial, Helvetica, sans-serif;">Arogyappacha Plant in Jeevani, from the hills of India will soon  be the most prominent energy herb in the United States and for very good  reason.&nbsp; You must try it to believe it.<br /> <br /> The local tribesman eat it saying &ldquo;It gives a sudden burst of energy.&nbsp; We don&rsquo;t  feel hungry for several hours; we don&rsquo;t feel thirsty or tired.&nbsp; And it feels  like we can walk and run on the mountain for hours&rdquo; <br /> <br /> The director of one of one of India''s largest think tanks even stated, &ldquo;The  plant is like cocaine in terms of its energy boost, without being addictive&rdquo;.</span></p>', 1);
INSERT INTO `elitesleeper_pages` VALUES(2, 'FAQs for Elite Rev', 'Frequently Asked Questions for Elite Rev', 'FAQ elite rev, Jeevani the Arogyappacha plant, awakening, social energy, elite rev, super energy, hottest new energy product, herbs with miraculous powers, jeevani, guarana, rhodiola, L carnitine tartrate, L phenyalanine, party all night long, get high, feel high, endurance, stay awake, curb appetite, appetite suppressant, safe and legal way to get high, rev up, amp up', 'Frequently asked questions about Elite Rev', '<p align="left"><span style="color: #444444; font: 13px Arial, Helvetica, sans-serif;"><span style="font-size: small;color:#0b9211;"><strong>What is Elite  Rev?</strong></span><br /> <br /> This is the first supplement designed specifically for the Night Life.&nbsp;  It is  potent herbal complex, combining both new and old ingredients setting it  far  apart from anything currently on the market.&nbsp; If your not satisfied for  any  reason return it for 100% of your money back.&nbsp; The key ingredient in  Jeevani is  the Arogyappacha plant.&nbsp; A team of scientists from the All India  Coordinated  Research Project on Ethnobiology (AICRPE) had stumbled on this plant  during the  course of their expedition in the Agasthyar hills in 1987.&nbsp; The local  guides  during the expedition&nbsp;were not stopping for rest.&nbsp; The local tribal  guides  accompanying the American scientists revealed the secret of the herb  which they  claimed to have miraculous powers.&nbsp; In a project spanning eight years,  the  institute went on to refine and validate the medicine now called  Jeevani.<br /> <br /> <span style="font-size: small;color:#0b9211;"><strong>How does it work?</strong></span><br /> <br /> Rhodiola&nbsp;once used by the KGB and studied in Russia for years has been  claimed  to be an antidepressant and central nervous system enhancer.&nbsp; In  Nocturnal  Nutrition it combines with the Arogyappacha Plant and acts as an anxiety   suppressor putting you at ease during the most stressful of situations  going  against any&nbsp;negative mindset&nbsp;that otherwise would be present.&nbsp; Research  reveals  it also decreases irritability and headaches.&nbsp; The active effects have  been  attributed primarily for its ability to influence levels of activity&nbsp;of  monoamines and opioid peptides such as beta-endorphines.&nbsp; It is 100%  safe and  has absolutely no negative&nbsp;side effects.<span style="color: black;"> </span> </span></p>\r\n<p align="justify"><span style="color: #444444; font: 13px Arial, Helvetica, sans-serif;"> <span style="font-size: small;color:#0b9211;"><strong>What ingredients in Elite Rev gives you the energy?<br /> </strong></span><br /> Arogyappacha Plant in Jeevani, from the hills of India  will soon  be the most prominent energy herb in the United States and for very good   reason.&nbsp; You must try it to believe it. </span><span style="font: 13px Arial, Helvetica, sans-serif;">The secret ingredient in our formula is a  herbal  supplement&nbsp; called "Jeevani"&nbsp;originating from the hills of southwest  India.<br /> Jeevani is an herb known as "Arogyapacha" - the Ginseng of Kani tribes  of  Agastyar hills, Kerala, India&hellip;<br /> <br /> <span style="background-color: #FFFFFF;">It was </span> <span style="background-color: #ffffff;">realized  years ago by a  team of scientists on a native hiking expedition that the local trail  guides </span><span style="background-color: #FFFFFF;">were  outlasting every single  hiker in the tour despite the fact that the scientists were in  tremendous  shape.&nbsp; </span>Only then did they realize the guides were constantly chewing the plant,  which  is now the active ingredient in Jeevani.&nbsp; <br /> The plant was brought back identified and lab tests were conducted  revealing&nbsp;  enormous anti-fatigue properties.&nbsp; It is a key ingredient providing the  endurance of Nocturnal Nutrition.&nbsp;</span><span style="color: #444444; font: 13px Arial, Helvetica, sans-serif;">The local tribesman eat it saying &ldquo;It gives a sudden burst of energy.&nbsp;  We don&rsquo;t  feel hungry for several hours; we don&rsquo;t feel thirsty or tired.&nbsp; And it  feels  like we can walk and run on the mountain for hours&rdquo; The director of one of one of India''s largest think tanks even stated,  &ldquo;The  plant is like cocaine in terms of its energy boost, without being  addictive&rdquo;.</span><span style="font-size: 12pt; color: black;  font-family: Arial;"><br /> <br /> </span> <span style="font-size: small;"><span style="color: black; font-family: Arial; font-weight: 700;"><span style="font-size: small;color:#0b9211;"><strong>How much Elite Rev do I take daily?</strong></span></span></span></p>\r\n<p><span style="color: #444444; font: 13px Arial, Helvetica, sans-serif;">\r\n<p>Recommended Use:&nbsp; As a dietary supplement, take 1-2  tablets as  needed on an empty stomach with a full glass of water.&nbsp;<br /> Do not exceed 4 tablets in any 24 hour time span.</p>\r\n</span></p>', 1);

-- --------------------------------------------------------

--
-- Table structure for table `elitesleeper_payment`
--

CREATE TABLE `elitesleeper_payment` (
  `payment_id` bigint(10) NOT NULL auto_increment,
  `payment_date` date NOT NULL,
  `transaction_id` varchar(20) NOT NULL,
  `payment_amount` float(6,2) NOT NULL,
  `payment_nature` varchar(30) NOT NULL,
  `card_expiry_date` varchar(250) NOT NULL,
  `customer_id` bigint(10) NOT NULL,
  PRIMARY KEY  (`payment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=44 ;

--
-- Dumping data for table `elitesleeper_payment`
--

INSERT INTO `elitesleeper_payment` VALUES(1, '2010-08-04', '4UJ268303B995700G', 0.01, 'recurring', '', 2);
INSERT INTO `elitesleeper_payment` VALUES(2, '2010-08-05', '4M512048D93573612', 0.02, 'onetime', '', 5);
INSERT INTO `elitesleeper_payment` VALUES(3, '2010-08-05', '4M512048D93573612', 0.02, 'onetime', '', 5);
INSERT INTO `elitesleeper_payment` VALUES(4, '2010-08-05', '4M512048D93573612', 0.02, 'onetime', '', 5);
INSERT INTO `elitesleeper_payment` VALUES(5, '2010-08-05', '4M512048D93573612', 0.02, 'onetime', '', 5);
INSERT INTO `elitesleeper_payment` VALUES(6, '2010-08-05', '7TB293875J395493H', 0.02, 'onetime', '', 6);
INSERT INTO `elitesleeper_payment` VALUES(7, '2010-08-05', '70B00631TU431493X', 0.02, 'onetime', '', 9);
INSERT INTO `elitesleeper_payment` VALUES(8, '2010-08-05', '70B00631TU431493X', 0.02, 'onetime', '', 9);
INSERT INTO `elitesleeper_payment` VALUES(9, '2010-08-05', '70B00631TU431493X', 0.02, 'onetime', '', 9);
INSERT INTO `elitesleeper_payment` VALUES(10, '2010-08-05', '70B00631TU431493X', 0.02, 'onetime', '', 9);
INSERT INTO `elitesleeper_payment` VALUES(36, '2010-08-09', '83D97588VX241535V', 0.02, 'onetime', '', 15);
INSERT INTO `elitesleeper_payment` VALUES(35, '2010-08-09', '83D97588VX241535V', 0.02, 'onetime', '', 15);
INSERT INTO `elitesleeper_payment` VALUES(13, '2010-08-05', '70B00631TU431493X', 0.02, 'onetime', '', 9);
INSERT INTO `elitesleeper_payment` VALUES(34, '2010-08-09', '83D97588VX241535V', 0.02, 'onetime', '', 15);
INSERT INTO `elitesleeper_payment` VALUES(33, '2010-08-09', '83D97588VX241535V', 0.02, 'onetime', '', 15);
INSERT INTO `elitesleeper_payment` VALUES(16, '2010-08-05', '70B00631TU431493X', 0.02, 'onetime', '', 9);
INSERT INTO `elitesleeper_payment` VALUES(17, '2010-08-05', '70B00631TU431493X', 0.02, 'onetime', '', 9);
INSERT INTO `elitesleeper_payment` VALUES(18, '2010-08-05', '70B00631TU431493X', 0.02, 'onetime', '', 9);
INSERT INTO `elitesleeper_payment` VALUES(32, '2010-08-09', '83D97588VX241535V', 0.02, 'onetime', '', 15);
INSERT INTO `elitesleeper_payment` VALUES(20, '2010-08-05', '70B00631TU431493X', 0.02, 'onetime', '', 9);
INSERT INTO `elitesleeper_payment` VALUES(21, '2010-08-05', '70B00631TU431493X', 0.02, 'onetime', '', 9);
INSERT INTO `elitesleeper_payment` VALUES(37, '2010-08-11', '5M56378099804924P', 0.01, 'onetime', '', 20);
INSERT INTO `elitesleeper_payment` VALUES(27, '2010-08-08', '9EU2592449520791D', 0.02, 'onetime', '', 14);
INSERT INTO `elitesleeper_payment` VALUES(28, '2010-08-08', '9EU2592449520791D', 0.02, 'onetime', '', 14);
INSERT INTO `elitesleeper_payment` VALUES(29, '2010-08-08', '9EU2592449520791D', 0.02, 'onetime', '', 14);
INSERT INTO `elitesleeper_payment` VALUES(30, '2010-08-08', '9EU2592449520791D', 0.02, 'onetime', '', 14);
INSERT INTO `elitesleeper_payment` VALUES(31, '2010-08-08', '9EU2592449520791D', 0.02, 'onetime', '', 14);
INSERT INTO `elitesleeper_payment` VALUES(38, '2010-08-12', '5T0714893C075062W', 0.01, 'onetime', '1-2011', 21);
INSERT INTO `elitesleeper_payment` VALUES(39, '2010-08-17', '5S150897372535819', 0.01, 'onetime', '12-2010', 23);
INSERT INTO `elitesleeper_payment` VALUES(40, '2010-08-17', '9X287974PX288383E', 0.01, 'onetime', '12-2010', 24);
INSERT INTO `elitesleeper_payment` VALUES(41, '2010-08-18', '3927435706763645N', 0.01, 'onetime', '12-2010', 25);
INSERT INTO `elitesleeper_payment` VALUES(42, '2010-08-18', '87U939749V917743E', 0.01, 'onetime', '12-2010', 27);
INSERT INTO `elitesleeper_payment` VALUES(43, '2010-08-19', '5YP74420FJ668804E', 0.01, 'onetime', '12-2010', 30);

-- --------------------------------------------------------

--
-- Table structure for table `elitesleeper_product`
--

CREATE TABLE `elitesleeper_product` (
  `id` int(5) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL,
  `price` float(6,2) NOT NULL,
  `shipping_charge` float(6,2) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `elitesleeper_product`
--

INSERT INTO `elitesleeper_product` VALUES(13, 'yyy', 29.95, 4.95);

-- --------------------------------------------------------

--
-- Table structure for table `elitesleeper_state`
--

CREATE TABLE `elitesleeper_state` (
  `id` int(5) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `value` varchar(10) NOT NULL,
  `country_id` int(5) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=68 ;

--
-- Dumping data for table `elitesleeper_state`
--

INSERT INTO `elitesleeper_state` VALUES(1, 'Alabama (AL)', 'MN', 1);
INSERT INTO `elitesleeper_state` VALUES(2, 'Alaska (AK)', 'MS', 1);
INSERT INTO `elitesleeper_state` VALUES(3, 'Arizona (AZ)', 'MO', 1);
INSERT INTO `elitesleeper_state` VALUES(4, 'Arkansas (AR)', 'MT', 1);
INSERT INTO `elitesleeper_state` VALUES(5, 'California (CA)', 'NE', 1);
INSERT INTO `elitesleeper_state` VALUES(6, 'Colorado (CO)', 'NV', 1);
INSERT INTO `elitesleeper_state` VALUES(7, 'Connecticut (CT)', 'NH', 1);
INSERT INTO `elitesleeper_state` VALUES(8, 'Delaware (DE)', 'NJ', 1);
INSERT INTO `elitesleeper_state` VALUES(9, 'District of Columbia (DC)', 'NM', 1);
INSERT INTO `elitesleeper_state` VALUES(10, 'Florida (FL)', 'NY', 1);
INSERT INTO `elitesleeper_state` VALUES(11, 'Georgia (GA)', 'NC', 1);
INSERT INTO `elitesleeper_state` VALUES(12, 'Guam (GU)', 'ND', 1);
INSERT INTO `elitesleeper_state` VALUES(13, 'Hawaii (HI)', 'OH', 1);
INSERT INTO `elitesleeper_state` VALUES(14, 'Idaho (ID)', 'OK', 1);
INSERT INTO `elitesleeper_state` VALUES(15, 'Illinois (IL)', 'OR', 1);
INSERT INTO `elitesleeper_state` VALUES(16, 'Indiana (IN)', 'PA', 1);
INSERT INTO `elitesleeper_state` VALUES(17, 'Iowa (IA)', 'PR', 1);
INSERT INTO `elitesleeper_state` VALUES(18, 'Kansas (KS)', 'RI', 1);
INSERT INTO `elitesleeper_state` VALUES(19, 'Kentucky (KY)', 'SC', 1);
INSERT INTO `elitesleeper_state` VALUES(20, 'Louisiana (LA)', 'SD', 1);
INSERT INTO `elitesleeper_state` VALUES(21, 'Maine (ME)', 'TN', 1);
INSERT INTO `elitesleeper_state` VALUES(22, 'Maryland (MD)', 'TX', 1);
INSERT INTO `elitesleeper_state` VALUES(23, 'Massachusetts (MA)', 'UT', 1);
INSERT INTO `elitesleeper_state` VALUES(24, 'Michigan (MI)', 'VT', 1);
INSERT INTO `elitesleeper_state` VALUES(25, 'Minnesota (MN)', 'VA', 1);
INSERT INTO `elitesleeper_state` VALUES(26, 'Mississippi (MS)', 'VI', 1);
INSERT INTO `elitesleeper_state` VALUES(27, 'Missouri (MO)', 'WA', 1);
INSERT INTO `elitesleeper_state` VALUES(28, 'Montana (MT)', 'WV', 1);
INSERT INTO `elitesleeper_state` VALUES(29, 'Nebraska (NE)', 'WI', 1);
INSERT INTO `elitesleeper_state` VALUES(30, 'Nevada (NV)', 'WY', 1);
INSERT INTO `elitesleeper_state` VALUES(31, 'New Hampshire (NH)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(32, 'New Jersey (NJ)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(33, 'New Mexico (NM)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(34, 'New York (NY)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(35, 'North Carolina (NC)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(36, 'North Dakota (ND)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(37, 'Ohio (OH)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(38, 'Oklahoma (OK)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(39, 'Oregon (OR)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(40, 'Pennyslvania (PA)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(41, 'Puerto Rico (PR)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(42, 'Rhode Island (RI)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(43, 'South Carolina (SC)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(44, 'South Dakota (SD)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(45, 'Tennessee (TN)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(46, 'Texas (TX)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(47, 'Utah (UT)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(48, 'Vermont (VT)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(49, 'Virginia (VA)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(50, 'Virgin Islands (VI)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(51, 'Washington (WA)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(52, 'West Virginia (WV)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(53, 'Wisconsin (WI)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(54, 'Wyoming (WY)', '', 1);
INSERT INTO `elitesleeper_state` VALUES(55, 'Alberta (AB)', 'AB', 2);
INSERT INTO `elitesleeper_state` VALUES(56, 'British Columbia (BC)', 'BC', 2);
INSERT INTO `elitesleeper_state` VALUES(57, 'Manitoba (MB)', 'MB', 2);
INSERT INTO `elitesleeper_state` VALUES(58, 'New Brunswick (NB)', 'NB', 2);
INSERT INTO `elitesleeper_state` VALUES(59, 'Newfoundland and Labrador (NL)', 'NL', 2);
INSERT INTO `elitesleeper_state` VALUES(60, 'Northwest Territories (NT)', 'NT', 2);
INSERT INTO `elitesleeper_state` VALUES(61, 'Nova Scotia (NS)', 'NS', 2);
INSERT INTO `elitesleeper_state` VALUES(62, 'Nunavut (NU)', 'NU', 2);
INSERT INTO `elitesleeper_state` VALUES(63, 'Prince Edward Island (PE)', 'PE', 2);
INSERT INTO `elitesleeper_state` VALUES(64, 'Saskatchewan (SK)', 'SK', 2);
INSERT INTO `elitesleeper_state` VALUES(65, 'Ontario (ON)', 'ON', 2);
INSERT INTO `elitesleeper_state` VALUES(66, 'Quebec (QC)', 'QC', 2);
INSERT INTO `elitesleeper_state` VALUES(67, 'Yukon (YT)', 'YT', 2);
