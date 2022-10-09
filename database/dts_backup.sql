/*
SQLyog Enterprise - MySQL GUI v6.56
MySQL - 5.7.36-log : Database - db_dts
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_dts` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `db_dts`;

/*Table structure for table `delivery_method` */

DROP TABLE IF EXISTS `delivery_method`;

CREATE TABLE `delivery_method` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `method` char(50) DEFAULT NULL,
  `status` char(1) DEFAULT NULL COMMENT '1=Active 0=Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `delivery_method` */

insert  into `delivery_method`(`id`,`method`,`status`,`created_at`,`updated_at`) values (1,'Email','1','2019-05-29 10:47:01','2019-05-29 10:47:01'),(2,'Fax','1','2019-05-29 10:47:01','2019-05-29 10:47:01'),(3,'Hand Carry','1','2019-05-29 10:47:01','2019-05-29 10:47:01'),(4,'Post Mail','1','2019-05-29 10:47:01','2019-05-29 10:47:01');

/*Table structure for table `document` */

DROP TABLE IF EXISTS `document`;

CREATE TABLE `document` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `barcode` char(50) NOT NULL,
  `receive_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `transaction_type_id` int(10) DEFAULT NULL,
  `document_type_id` int(10) DEFAULT NULL,
  `source_type_id` int(10) DEFAULT NULL,
  `office_id` int(10) DEFAULT NULL,
  `delivery_method_id` int(10) DEFAULT NULL,
  `source_name` text,
  `sex` char(6) DEFAULT NULL,
  `contact_no` char(100) DEFAULT NULL,
  `email_address` char(100) DEFAULT NULL,
  `subject_matter` text,
  `documents_linked` char(200) DEFAULT NULL,
  `access_code` char(50) DEFAULT NULL,
  `transaction_end_date` datetime DEFAULT NULL,
  `document_status` char(1) DEFAULT NULL COMMENT 'D=Delayed, O=Ontime, G=On-going',
  `user_id` int(10) DEFAULT NULL,
  `to_mayor` char(1) DEFAULT NULL COMMENT '1=YES, 0=NO',
  `status` char(1) DEFAULT NULL COMMENT '1=Active 0=Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`,`barcode`),
  UNIQUE KEY `id` (`id`),
  KEY `document_id_index` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `document` */

/*Table structure for table `document_attachments` */

DROP TABLE IF EXISTS `document_attachments`;

CREATE TABLE `document_attachments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL COMMENT 'linked to document',
  `document_name` text,
  `token_name` text,
  `status` char(1) DEFAULT NULL COMMENT '1=Active 0=Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `barcode_index` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `document_attachments` */

/*Table structure for table `document_transaction` */

DROP TABLE IF EXISTS `document_transaction`;

CREATE TABLE `document_transaction` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `document_id` int(10) DEFAULT NULL COMMENT 'linked to document',
  `sequence` int(5) DEFAULT NULL,
  `transit_date_time` datetime DEFAULT NULL,
  `office_code` char(10) DEFAULT NULL,
  `receive_person` varchar(255) DEFAULT NULL,
  `receive_date_time` datetime DEFAULT NULL,
  `receive_action` varchar(255) DEFAULT NULL,
  `route_office_code` char(10) DEFAULT NULL,
  `release_person` varchar(255) DEFAULT NULL,
  `release_date_time` datetime DEFAULT NULL COMMENT 'this can be end date',
  `release_action` varchar(255) DEFAULT NULL,
  `remarks` text,
  `current_action` char(3) DEFAULT NULL COMMENT 'REL=Release, REC=Receive ',
  `status` char(1) DEFAULT NULL COMMENT '1=Active 0=Inactive this is used for transactions',
  `transaction_status` char(1) DEFAULT NULL COMMENT 'O=On-time, D=Delinquent',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `barcode_index` (`document_id`),
  KEY `trans_id_index` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `document_transaction` */

/*Table structure for table `document_type` */

DROP TABLE IF EXISTS `document_type`;

CREATE TABLE `document_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `document_code` char(5) NOT NULL,
  `document_type` char(50) DEFAULT NULL,
  `status` char(1) DEFAULT NULL COMMENT '1=Active 0=Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`,`document_code`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `document_type` */

insert  into `document_type`(`id`,`document_code`,`document_type`,`status`,`created_at`,`updated_at`) values (1,'COM','Communications','1','2019-05-29 10:50:00','2019-05-29 10:50:00'),(2,'PO','Purchase Order','1','2019-05-29 10:50:00','2019-05-29 10:50:00'),(3,'PR','Purchase Request','1','2019-05-29 10:50:00','2019-05-29 10:50:00'),(4,'PY','Payroll','1','2019-05-29 10:50:00','2019-12-13 14:20:59'),(5,'VC','Disbursement Voucher/Petty Cash','1','2019-05-29 10:50:00','2019-08-05 03:19:24'),(6,'AOC','Abstract of Canvass','1','2019-05-29 10:50:00','2019-08-16 02:59:45');

/*Table structure for table `holidays` */

DROP TABLE IF EXISTS `holidays`;

CREATE TABLE `holidays` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `holiday` char(200) DEFAULT NULL,
  `holiday_date` date DEFAULT NULL,
  `status` char(1) DEFAULT NULL COMMENT '1=Active, 0=Inactive',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `holidays` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

/*Table structure for table `months` */

DROP TABLE IF EXISTS `months`;

CREATE TABLE `months` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `month_value` char(2) DEFAULT NULL,
  `month` char(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Data for the table `months` */

insert  into `months`(`id`,`month_value`,`month`) values (1,'01','January'),(2,'02','February'),(3,'03','March'),(4,'04','April'),(5,'05','May'),(6,'06','June'),(7,'07','July'),(8,'08','August'),(9,'09','September'),(10,'10','October'),(11,'11','November'),(12,'12','December');

/*Table structure for table `office_performance` */

DROP TABLE IF EXISTS `office_performance`;

CREATE TABLE `office_performance` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `office_id` int(10) DEFAULT NULL,
  `document_code_id` char(5) DEFAULT NULL,
  `office_time` double DEFAULT NULL COMMENT 'in minutes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `office_performance` */

/*Table structure for table `offices` */

DROP TABLE IF EXISTS `offices`;

CREATE TABLE `offices` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `office_code` char(10) NOT NULL,
  `office_name` char(100) DEFAULT NULL,
  `status` char(1) DEFAULT NULL COMMENT '1=Active 0=Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`,`office_code`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `offices` */

insert  into `offices`(`id`,`office_code`,`office_name`,`status`,`created_at`,`updated_at`) values (1,'REC','Records','1',NULL,NULL);

/*Table structure for table `online_users` */

DROP TABLE IF EXISTS `online_users`;

CREATE TABLE `online_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varbinary(255) DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'as barcode',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

/*Data for the table `online_users` */

/*Table structure for table `source` */

DROP TABLE IF EXISTS `source`;

CREATE TABLE `source` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `source` char(20) DEFAULT NULL,
  `status` char(1) DEFAULT NULL COMMENT '1=Active 0=Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `source` */

insert  into `source`(`id`,`source`,`status`,`created_at`,`updated_at`) values (1,'Internal Client','1','2019-05-29 10:54:01','2019-05-29 10:54:01'),(2,'External Client','1','2019-05-29 10:54:01','2019-05-29 10:54:01');

/*Table structure for table `system_logs` */

DROP TABLE IF EXISTS `system_logs`;

CREATE TABLE `system_logs` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL,
  `action` char(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `system_logs` */

/*Table structure for table `transaction_type` */

DROP TABLE IF EXISTS `transaction_type`;

CREATE TABLE `transaction_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `transaction_type` char(20) DEFAULT NULL,
  `days` int(11) DEFAULT NULL,
  `status` char(1) DEFAULT NULL COMMENT '1=Active 0=Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `transaction_type` */

insert  into `transaction_type`(`id`,`transaction_type`,`days`,`status`,`created_at`,`updated_at`) values (1,'Simple',3,'1','2019-05-29 10:53:01','2019-12-11 17:06:51'),(2,'Complex',7,'1','2019-05-29 10:53:01','2019-12-11 17:06:56'),(3,'Highly Technical',20,'1','2019-05-29 10:53:01','2019-05-29 10:53:01');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office_id` int(10) DEFAULT NULL COMMENT 'linked to office',
  `access` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'A=Admin, R=Records, U=User',
  `status` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '1=Active 0=Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`email_verified_at`,`password`,`remember_token`,`office_id`,`access`,`status`,`created_at`,`updated_at`) values (1,'administrator','admin',NULL,'$2y$10$sD1p.IA0oEds6xufGRVdhu8PKQ/zFNaAgyEM8iI.PSi9h4O1YaSP.','YOcMBsr2mU4if9v3rnjBpWs8knf5VlJZgIrSoG7SCj25lqX9YV4rJwOvMsgH',1,'A','1','2019-05-28 00:59:16','2022-10-09 19:19:14');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
