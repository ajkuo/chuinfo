-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- 主機: 127.0.0.1
-- 產生時間： 2016-08-05 09:28:07
-- 伺服器版本: 5.7.9
-- PHP 版本： 5.6.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `chuinfo_empty`
--

-- --------------------------------------------------------

--
-- 資料表結構 `accounts`
--

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE IF NOT EXISTS `accounts` (
  `No` int(11) NOT NULL AUTO_INCREMENT,
  `SID` char(9) NOT NULL,
  `Password` char(128) NOT NULL,
  `Email` varchar(300) DEFAULT NULL,
  `Name` varchar(20) NOT NULL,
  `Nickname` varchar(20) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Gender` char(1) NOT NULL,
  `DeptNo` int(11) NOT NULL,
  `Permission` int(4) NOT NULL DEFAULT '0',
  `mail_token` varchar(64) DEFAULT NULL,
  `mail_sendtime` datetime DEFAULT NULL,
  `mail_resend` tinyint(1) NOT NULL DEFAULT '0',
  `Level` int(11) NOT NULL DEFAULT '0',
  `TotalExp` double NOT NULL DEFAULT '0',
  `TotalCont` double NOT NULL DEFAULT '0',
  `TotalPost` int(11) NOT NULL DEFAULT '0',
  `TotalResponse` int(11) NOT NULL DEFAULT '0',
  `TotalComment` int(11) NOT NULL DEFAULT '0',
  `dob` date DEFAULT NULL,
  `UserIp` varchar(50) DEFAULT NULL,
  `add_on` datetime DEFAULT NULL,
  `edit_on` datetime DEFAULT NULL,
  `lock_status` tinyint(1) NOT NULL DEFAULT '0',
  `lock_on` datetime DEFAULT NULL,
  `lock_by` varchar(20) DEFAULT NULL,
  `lock_reason` varchar(50) DEFAULT NULL,
  `del_status` tinyint(1) NOT NULL DEFAULT '0',
  `del_on` datetime DEFAULT NULL,
  `del_by` varchar(20) DEFAULT NULL,
  `ps1` varchar(1000) DEFAULT NULL,
  `ps2` varchar(1000) DEFAULT NULL,
  `ps3` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `accounts_history`
--

DROP TABLE IF EXISTS `accounts_history`;
CREATE TABLE IF NOT EXISTS `accounts_history` (
  `No` bigint(20) NOT NULL AUTO_INCREMENT,
  `UserNo` int(11) NOT NULL,
  `SID` char(9) NOT NULL,
  `DeptNo` int(11) NOT NULL,
  `Name` varchar(20) DEFAULT NULL,
  `Nickname` varchar(20) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Gender` char(1) DEFAULT NULL,
  `Email` varchar(300) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `UserIp` varchar(50) DEFAULT NULL,
  `edit_on` datetime DEFAULT NULL,
  `add_on` datetime DEFAULT NULL,
  PRIMARY KEY (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `accounts_pw_history`
--

DROP TABLE IF EXISTS `accounts_pw_history`;
CREATE TABLE IF NOT EXISTS `accounts_pw_history` (
  `No` bigint(20) NOT NULL AUTO_INCREMENT,
  `UserNo` int(11) NOT NULL,
  `SID` char(9) NOT NULL,
  `reason` varchar(100) DEFAULT NULL,
  `UserIp` varchar(50) DEFAULT NULL,
  `add_on` datetime DEFAULT NULL,
  PRIMARY KEY (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `course_group`
--

DROP TABLE IF EXISTS `course_group`;
CREATE TABLE IF NOT EXISTS `course_group` (
  `No` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(35) NOT NULL,
  `DeptNo` int(11) NOT NULL,
  `Teacher` varchar(20) DEFAULT NULL,
  `Credit` double DEFAULT NULL,
  `CourseTime` double DEFAULT NULL,
  `Type` char(1) DEFAULT NULL,
  `TeacherScore` double NOT NULL DEFAULT '0',
  `TeacherCount` int(11) NOT NULL DEFAULT '0',
  `CourseScore` double NOT NULL DEFAULT '0',
  `CourseCount` int(11) NOT NULL DEFAULT '0',
  `GradeScore` double NOT NULL DEFAULT '0',
  `GradeCount` int(11) NOT NULL DEFAULT '0',
  `RecommendScore` double NOT NULL DEFAULT '0',
  `RecommendCount` int(11) NOT NULL DEFAULT '0',
  `AverageScore` double NOT NULL DEFAULT '0',
  `TotalLike` int(11) NOT NULL DEFAULT '0',
  `TotalComment` int(11) NOT NULL DEFAULT '0',
  `add_on` datetime DEFAULT NULL,
  `del_status` tinyint(1) NOT NULL DEFAULT '0',
  `del_on` datetime DEFAULT NULL,
  `ps1` varchar(1000) DEFAULT NULL,
  `ps2` varchar(1000) DEFAULT NULL,
  `ps3` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `course_history`
--

DROP TABLE IF EXISTS `course_history`;
CREATE TABLE IF NOT EXISTS `course_history` (
  `No` int(11) NOT NULL AUTO_INCREMENT,
  `GroupNo` int(11) NOT NULL,
  `CID` varchar(10) CHARACTER SET utf8 NOT NULL,
  `Year` int(11) NOT NULL,
  `Term` int(11) NOT NULL,
  `Content` varchar(20000) CHARACTER SET utf8 DEFAULT NULL,
  `StdOfScore` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `ClassTime` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `Classroom` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `Member` int(11) NOT NULL DEFAULT '0',
  `MaxMember` int(11) NOT NULL DEFAULT '0',
  `add_on` datetime DEFAULT NULL,
  `del_status` tinyint(1) NOT NULL DEFAULT '0',
  `del_on` datetime DEFAULT NULL,
  PRIMARY KEY (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `course_like`
--

DROP TABLE IF EXISTS `course_like`;
CREATE TABLE IF NOT EXISTS `course_like` (
  `No` bigint(20) NOT NULL AUTO_INCREMENT,
  `SID` char(9) NOT NULL,
  `GroupNo` int(11) NOT NULL,
  `add_on` datetime DEFAULT NULL,
  `del_status` tinyint(1) NOT NULL DEFAULT '0',
  `del_on` datetime DEFAULT NULL,
  PRIMARY KEY (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `course_response`
--

DROP TABLE IF EXISTS `course_response`;
CREATE TABLE IF NOT EXISTS `course_response` (
  `No` bigint(11) NOT NULL AUTO_INCREMENT,
  `GroupNo` int(11) NOT NULL,
  `UserNo` int(11) NOT NULL,
  `Floor` int(11) NOT NULL DEFAULT '0',
  `Content` varchar(2000) NOT NULL,
  `Exp` double NOT NULL DEFAULT '0',
  `Cont` double NOT NULL DEFAULT '0',
  `anonymous` tinyint(1) NOT NULL DEFAULT '0',
  `UserIp` varchar(50) DEFAULT NULL,
  `add_on` datetime DEFAULT NULL,
  `edit_on` datetime DEFAULT NULL,
  `del_status` tinyint(1) NOT NULL DEFAULT '0',
  `del_on` datetime DEFAULT NULL,
  `del_by` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`No`),
  KEY `No` (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `course_response_history`
--

DROP TABLE IF EXISTS `course_response_history`;
CREATE TABLE IF NOT EXISTS `course_response_history` (
  `No` bigint(20) NOT NULL AUTO_INCREMENT,
  `GroupNo` int(11) NOT NULL,
  `ResId` bigint(20) NOT NULL,
  `Floor` int(11) NOT NULL,
  `UserNo` int(11) NOT NULL,
  `Content` varchar(2000) NOT NULL,
  `UserIp` varchar(50) DEFAULT NULL,
  `edit_on` datetime DEFAULT NULL,
  `add_on` datetime DEFAULT NULL,
  PRIMARY KEY (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `course_score`
--

DROP TABLE IF EXISTS `course_score`;
CREATE TABLE IF NOT EXISTS `course_score` (
  `No` bigint(11) NOT NULL AUTO_INCREMENT,
  `GroupNo` int(11) NOT NULL,
  `UserNo` int(11) NOT NULL,
  `TeacherScore` double NOT NULL DEFAULT '0',
  `CourseScore` double NOT NULL DEFAULT '0',
  `GradeScore` double NOT NULL DEFAULT '0',
  `RecommendScore` double NOT NULL DEFAULT '0',
  `referer` varchar(1000) DEFAULT NULL,
  `add_on` datetime DEFAULT NULL,
  `del_status` tinyint(1) NOT NULL DEFAULT '0',
  `del_on` datetime DEFAULT NULL,
  `del_by` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `No` int(11) NOT NULL AUTO_INCREMENT,
  `Type` char(1) NOT NULL,
  `Name` varchar(40) NOT NULL,
  `TotalUsers` int(11) NOT NULL DEFAULT '0',
  `add_on` datetime DEFAULT NULL,
  `del_status` tinyint(1) NOT NULL DEFAULT '0',
  `del_on` datetime DEFAULT NULL,
  `ps1` varchar(1000) DEFAULT NULL,
  `ps2` varchar(1000) DEFAULT NULL,
  `ps3` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`No`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4;

--
-- 資料表的匯出資料 `departments`
--

INSERT INTO `departments` (`No`, `Type`, `Name`, `TotalUsers`, `add_on`, `del_status`, `del_on`, `ps1`, `ps2`, `ps3`) VALUES
(1, 'S', '﻿電機工程學系\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(2, 'S', '資訊工程學系\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(3, 'S', '工業管理學系\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(4, 'S', '土木工程學系\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(5, 'S', '建築與都市計畫學系\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(6, 'S', '機械工程學系\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(7, 'S', '景觀建築學系\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(8, 'S', '應用統計學系\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(9, 'S', '資訊管理學系\r\n', 1, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(10, 'S', '企業管理學系\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(11, 'S', '財務管理學系\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(12, 'S', '國際企業學系\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(13, 'S', '運輸科技與物流管理學系\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(14, 'S', '外國語文學系\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(15, 'S', '營建管理學系\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(16, 'S', '行政管理學系\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(17, 'S', '餐旅管理學系\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(18, 'S', '生物資訊學系\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(19, 'S', '科技管理學系\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(20, 'S', '電子工程學系(原通訊工程學系)\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(21, 'S', '休閒遊憩規劃與管理學系\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(22, 'S', '電子工程學系\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(23, 'S', '光機電與材料學士學位學程\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(24, 'S', '觀光與會議展覽學士學位學程\r\n', 0, '2016-06-28 19:53:11', 0, NULL, NULL, NULL, NULL),
(25, 'S', '資訊學士學位學程\r\n', 0, '2016-06-28 19:53:12', 0, NULL, NULL, NULL, NULL),
(26, 'S', '國際金融管理學士學位學程\r\n', 0, '2016-06-28 19:53:12', 0, NULL, NULL, NULL, NULL),
(27, 'S', '應用日語學系\r\n', 0, '2016-06-28 19:53:12', 0, NULL, NULL, NULL, NULL),
(28, 'S', '創新設計與管理學士學位學程\r\n', 0, '2016-06-28 19:53:12', 0, NULL, NULL, NULL, NULL),
(29, 'S', '工業產品設計學系\r\n', 0, '2016-06-28 19:53:12', 0, NULL, NULL, NULL, NULL),
(30, 'S', '觀光與會展學系\r\n', 0, '2016-06-28 19:53:12', 0, NULL, NULL, NULL, NULL),
(31, 'S', '觀光學院學士班\r\n', 0, '2016-06-28 19:53:12', 0, NULL, NULL, NULL, NULL),
(32, 'S', '微學分課程', 0, '2016-06-28 19:53:12', 0, NULL, NULL, NULL, NULL),
(33, 'C', '通識教育中心', 0, '2016-06-29 22:15:30', 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `forum_post`
--

DROP TABLE IF EXISTS `forum_post`;
CREATE TABLE IF NOT EXISTS `forum_post` (
  `No` bigint(20) NOT NULL AUTO_INCREMENT,
  `UserNo` int(11) NOT NULL,
  `Type` int(11) DEFAULT NULL,
  `Title` varchar(30) DEFAULT NULL,
  `Content` varchar(12000) DEFAULT NULL,
  `anonymous` tinyint(1) NOT NULL DEFAULT '0',
  `Exp` double NOT NULL DEFAULT '0',
  `Cont` double NOT NULL DEFAULT '0',
  `TotalComment` int(11) NOT NULL DEFAULT '0',
  `TotalLike` int(11) NOT NULL DEFAULT '0',
  `TotalDislike` int(11) NOT NULL DEFAULT '0',
  `Popularity` int(11) NOT NULL DEFAULT '0',
  `Sticky` tinyint(1) NOT NULL DEFAULT '0',
  `Sort` int(11) NOT NULL DEFAULT '0',
  `Mark` tinyint(1) NOT NULL DEFAULT '0',
  `UserIp` varchar(50) DEFAULT NULL,
  `add_on` datetime DEFAULT NULL,
  `edit_on` datetime DEFAULT NULL,
  `del_status` tinyint(1) NOT NULL DEFAULT '0',
  `del_on` datetime DEFAULT NULL,
  `del_by` varchar(20) DEFAULT NULL,
  `ps1` varchar(1000) DEFAULT NULL,
  `ps2` varchar(1000) DEFAULT NULL,
  `ps3` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `forum_post_history`
--

DROP TABLE IF EXISTS `forum_post_history`;
CREATE TABLE IF NOT EXISTS `forum_post_history` (
  `No` bigint(20) NOT NULL AUTO_INCREMENT,
  `PostId` bigint(20) NOT NULL,
  `UserNo` int(11) NOT NULL,
  `UserIp` varchar(50) NOT NULL,
  `Type` int(11) NOT NULL,
  `Title` varchar(30) NOT NULL,
  `Content` varchar(12000) NOT NULL,
  `add_on` datetime NOT NULL,
  `edit_on` datetime NOT NULL,
  PRIMARY KEY (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `forum_type`
--

DROP TABLE IF EXISTS `forum_type`;
CREATE TABLE IF NOT EXISTS `forum_type` (
  `No` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(10) NOT NULL,
  `Code` varchar(10) NOT NULL,
  `Sort` int(11) NOT NULL DEFAULT '99',
  `TotalPost` int(11) NOT NULL DEFAULT '0',
  `PublicPermission` int(11) NOT NULL DEFAULT '0',
  `add_on` datetime DEFAULT NULL,
  `del_status` tinyint(1) NOT NULL DEFAULT '0',
  `del_on` datetime DEFAULT NULL,
  `ps1` varchar(1000) DEFAULT NULL,
  `ps2` varchar(1000) DEFAULT NULL,
  `ps3` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`No`),
  KEY `No` (`No`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- 資料表的匯出資料 `forum_type`
--

INSERT INTO `forum_type` (`No`, `Name`, `Code`, `Sort`, `TotalPost`, `PublicPermission`, `add_on`, `del_status`, `del_on`, `ps1`, `ps2`, `ps3`) VALUES
(1, '全部', 'all', 1, 0, 300, '2016-07-23 10:36:11', 0, NULL, NULL, NULL, NULL),
(2, '綜合', 'complex', 2, 0, 0, '2016-07-23 10:44:47', 0, NULL, NULL, NULL, NULL),
(3, '靠北', 'hate', 3, 0, 0, '2016-07-23 10:48:52', 0, NULL, NULL, NULL, NULL),
(4, '有趣', 'funny', 4, 0, 0, '2016-07-23 10:48:52', 0, NULL, NULL, NULL, NULL),
(5, '感情', 'love', 5, 0, 0, '2016-07-23 10:48:52', 0, NULL, NULL, NULL, NULL),
(6, '閒聊', 'chat', 6, 0, 0, '2016-07-23 10:48:52', 0, NULL, NULL, NULL, NULL),
(7, '揪人', 'group', 7, 0, 0, '2016-07-23 10:48:52', 0, NULL, NULL, NULL, NULL),
(8, '站務', 'sys', 99, 0, 100, NULL, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `log_course_detail`
--

DROP TABLE IF EXISTS `log_course_detail`;
CREATE TABLE IF NOT EXISTS `log_course_detail` (
  `No` bigint(20) NOT NULL AUTO_INCREMENT,
  `SID` char(9) NOT NULL,
  `GroupNo` int(11) NOT NULL,
  `Code` char(32) DEFAULT NULL,
  `time_str` char(10) NOT NULL,
  `add_on` datetime NOT NULL,
  `UserIp` varchar(50) DEFAULT NULL,
  `UserAgent` varchar(1000) DEFAULT NULL,
  `UserReferer` varchar(1000) DEFAULT NULL,
  `ps1` varchar(1000) DEFAULT NULL,
  `ps2` varchar(1000) DEFAULT NULL,
  `ps3` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `log_course_search`
--

DROP TABLE IF EXISTS `log_course_search`;
CREATE TABLE IF NOT EXISTS `log_course_search` (
  `No` bigint(20) NOT NULL AUTO_INCREMENT,
  `SID` char(9) NOT NULL,
  `DeptNo` int(11) DEFAULT NULL,
  `Type` varchar(5) DEFAULT NULL,
  `ClassTime` varchar(5) DEFAULT NULL,
  `Keyword` varchar(35) DEFAULT NULL,
  `add_on` datetime DEFAULT NULL,
  `ps1` varchar(1000) DEFAULT NULL,
  `ps2` varchar(1000) DEFAULT NULL,
  `ps3` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `log_login`
--

DROP TABLE IF EXISTS `log_login`;
CREATE TABLE IF NOT EXISTS `log_login` (
  `No` bigint(20) NOT NULL AUTO_INCREMENT,
  `SID` char(9) NOT NULL,
  `UserIp` varchar(50) DEFAULT NULL,
  `UserAgent` varchar(1000) DEFAULT NULL,
  `UserReferer` varchar(1000) DEFAULT NULL,
  `Status` tinyint(1) NOT NULL,
  `add_on` datetime NOT NULL,
  `ps1` varchar(1000) DEFAULT NULL,
  `ps2` varchar(1000) DEFAULT NULL,
  `ps3` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`No`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `log_manage_comment`
--

DROP TABLE IF EXISTS `log_manage_comment`;
CREATE TABLE IF NOT EXISTS `log_manage_comment` (
  `No` bigint(20) NOT NULL AUTO_INCREMENT,
  `UserNo` int(11) NOT NULL,
  `SID` char(9) NOT NULL,
  `Permission` int(11) NOT NULL,
  `Action` varchar(200) DEFAULT NULL,
  `UserIp` varchar(50) DEFAULT NULL,
  `UserAgent` varchar(1000) DEFAULT NULL,
  `add_on` datetime DEFAULT NULL,
  PRIMARY KEY (`No`),
  KEY `No` (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `log_manage_post`
--

DROP TABLE IF EXISTS `log_manage_post`;
CREATE TABLE IF NOT EXISTS `log_manage_post` (
  `No` bigint(20) NOT NULL AUTO_INCREMENT,
  `UserNo` int(11) NOT NULL,
  `SID` char(9) NOT NULL,
  `Permission` int(11) NOT NULL,
  `Action` varchar(2000) DEFAULT NULL,
  `UserIp` varchar(50) DEFAULT NULL,
  `UserAgent` varchar(1000) DEFAULT NULL,
  `add_on` datetime DEFAULT NULL,
  PRIMARY KEY (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `log_manage_response`
--

DROP TABLE IF EXISTS `log_manage_response`;
CREATE TABLE IF NOT EXISTS `log_manage_response` (
  `No` bigint(20) NOT NULL AUTO_INCREMENT,
  `UserNo` int(11) NOT NULL,
  `SID` char(9) NOT NULL,
  `Permission` int(11) NOT NULL,
  `Action` varchar(2000) DEFAULT NULL,
  `UserIp` varchar(50) DEFAULT NULL,
  `UserAgent` varchar(1000) DEFAULT NULL,
  `add_on` datetime DEFAULT NULL,
  PRIMARY KEY (`No`),
  KEY `No` (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `log_manage_user`
--

DROP TABLE IF EXISTS `log_manage_user`;
CREATE TABLE IF NOT EXISTS `log_manage_user` (
  `No` bigint(20) NOT NULL,
  `UserNo` int(11) NOT NULL,
  `SID` int(11) NOT NULL,
  `Permission` int(11) NOT NULL,
  `Action` varchar(2000) DEFAULT NULL,
  `UserIp` varchar(50) DEFAULT NULL,
  `UserAgent` varchar(1000) DEFAULT NULL,
  `add_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `mail_forget_password`
--

DROP TABLE IF EXISTS `mail_forget_password`;
CREATE TABLE IF NOT EXISTS `mail_forget_password` (
  `No` bigint(20) NOT NULL AUTO_INCREMENT,
  `SID` char(9) NOT NULL,
  `mail_token` char(64) NOT NULL,
  `mail_sendtime` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `UserIp` varchar(50) DEFAULT NULL,
  `UserAgent` varchar(1000) DEFAULT NULL,
  `add_on` datetime DEFAULT NULL,
  PRIMARY KEY (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `m_info`
--

DROP TABLE IF EXISTS `m_info`;
CREATE TABLE IF NOT EXISTS `m_info` (
  `TotalUser` int(11) NOT NULL DEFAULT '0',
  `TotalGroup` int(11) NOT NULL DEFAULT '0',
  `TotalComment` int(11) NOT NULL DEFAULT '0',
  `TotalPost` int(11) NOT NULL DEFAULT '0',
  `TotalResponse` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 資料表的匯出資料 `m_info`
--

INSERT INTO `m_info` (`TotalUser`, `TotalGroup`, `TotalComment`, `TotalPost`, `TotalResponse`) VALUES
(1, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- 資料表結構 `post_like`
--

DROP TABLE IF EXISTS `post_like`;
CREATE TABLE IF NOT EXISTS `post_like` (
  `No` bigint(20) NOT NULL AUTO_INCREMENT,
  `PostId` bigint(20) NOT NULL,
  `UserNo` int(11) NOT NULL,
  `PostLike` int(1) NOT NULL DEFAULT '0',
  `add_on` datetime DEFAULT NULL,
  `del_status` tinyint(1) NOT NULL DEFAULT '0',
  `del_on` datetime DEFAULT NULL,
  PRIMARY KEY (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `post_report`
--

DROP TABLE IF EXISTS `post_report`;
CREATE TABLE IF NOT EXISTS `post_report` (
  `No` bigint(20) NOT NULL AUTO_INCREMENT,
  `UserNo` int(11) NOT NULL,
  `SID` char(9) NOT NULL,
  `PostId` bigint(20) NOT NULL,
  `Content` varchar(200) NOT NULL,
  `Note` varchar(200) DEFAULT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT '0',
  `Mark` tinyint(1) NOT NULL DEFAULT '0',
  `add_on` datetime DEFAULT NULL,
  `ps1` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `post_response`
--

DROP TABLE IF EXISTS `post_response`;
CREATE TABLE IF NOT EXISTS `post_response` (
  `No` bigint(20) NOT NULL AUTO_INCREMENT,
  `PostId` bigint(20) NOT NULL,
  `UserNo` int(11) NOT NULL,
  `Content` varchar(2000) NOT NULL,
  `Floor` int(11) NOT NULL,
  `Anonymous` tinyint(1) NOT NULL DEFAULT '0',
  `TotalLike` int(11) NOT NULL DEFAULT '0',
  `TotalDislike` int(11) NOT NULL DEFAULT '0',
  `Exp` double NOT NULL DEFAULT '0',
  `Cont` double NOT NULL DEFAULT '0',
  `UserIp` varchar(50) DEFAULT NULL,
  `add_on` datetime DEFAULT NULL,
  `edit_on` datetime DEFAULT NULL,
  `del_status` tinyint(1) NOT NULL DEFAULT '0',
  `del_on` datetime DEFAULT NULL,
  `del_by` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `post_response_history`
--

DROP TABLE IF EXISTS `post_response_history`;
CREATE TABLE IF NOT EXISTS `post_response_history` (
  `No` bigint(20) NOT NULL AUTO_INCREMENT,
  `PostId` bigint(20) NOT NULL,
  `UserNo` int(11) NOT NULL,
  `ResId` bigint(20) NOT NULL,
  `Floor` int(11) NOT NULL,
  `Content` varchar(2000) NOT NULL,
  `UserIp` varchar(50) DEFAULT NULL,
  `edit_on` datetime DEFAULT NULL,
  `add_on` datetime DEFAULT NULL,
  PRIMARY KEY (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `post_response_like`
--

DROP TABLE IF EXISTS `post_response_like`;
CREATE TABLE IF NOT EXISTS `post_response_like` (
  `No` bigint(20) NOT NULL AUTO_INCREMENT,
  `UserNo` int(11) NOT NULL,
  `PostId` bigint(20) NOT NULL,
  `ResId` bigint(20) NOT NULL,
  `PostLike` int(11) NOT NULL,
  `add_on` datetime DEFAULT NULL,
  `del_status` tinyint(1) NOT NULL DEFAULT '0',
  `del_on` datetime DEFAULT NULL,
  PRIMARY KEY (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
