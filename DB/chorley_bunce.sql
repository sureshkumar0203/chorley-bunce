-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2017 at 07:26 AM
-- Server version: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `chorley_bunce`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_temp`
--

CREATE TABLE IF NOT EXISTS `cart_temp` (
  `cart_id` int(11) NOT NULL,
  `allocation_id` bigint(20) NOT NULL,
  `chef_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `supplier_id` bigint(20) NOT NULL,
  `unit_price` double NOT NULL,
  `qty` double NOT NULL,
  `total_price` double NOT NULL,
  `special_notes` longtext NOT NULL,
  `dated` date NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cart_temp`
--

INSERT INTO `cart_temp` (`cart_id`, `allocation_id`, `chef_id`, `product_id`, `supplier_id`, `unit_price`, `qty`, `total_price`, `special_notes`, `dated`) VALUES
(1, 10, 6, 13, 3, 1, 1, 1, '', '2016-12-14'),
(2, 10, 6, 15, 3, 1, 1, 1, 'Vcbvcbvcbvc vcbvc bvc bvbvc', '2016-12-14'),
(6, 3, 6, 7, 1, 20, 10, 180, '', '2017-01-25'),
(5, 13, 6, 13, 3, 1, 1, 1, '', '2017-01-05'),
(7, 3, 6, 24, 1, 56, 8, 436.8, '', '2017-01-25'),
(10, 12, 6, 13, 3, 1, 1.5, 1.5, '', '2017-01-27'),
(11, 12, 6, 10, 3, 8, 2.75, 22, '', '2017-01-27');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_photo` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `category_photo`, `created_date`, `updated_date`) VALUES
(1, 'Fruits', '1472883638.jpg', '2016-08-26 18:30:28', '2016-09-03 11:50:38'),
(2, 'Drinks', '1472216487.jpg', '2016-08-26 18:31:27', '2016-08-26 18:31:27'),
(3, 'Meat', '1472216597.png', '2016-08-26 18:33:18', '2016-08-26 18:33:18'),
(4, 'Veg', '1472821946.png', '2016-09-02 18:42:26', '2016-09-02 18:42:26'),
(5, 'Fish', '1472880676.png', '2016-09-03 11:00:12', '2016-09-03 11:01:16'),
(6, 'Spices', '1472881315.jpg', '2016-09-03 11:11:55', '2016-09-03 11:11:55'),
(7, 'Grocery', '1481030798.png', '2016-12-06 18:56:38', '2016-12-06 18:56:38');

-- --------------------------------------------------------

--
-- Table structure for table `chefs_registration`
--

CREATE TABLE IF NOT EXISTS `chefs_registration` (
  `chefs_id` bigint(20) NOT NULL,
  `chefs_name` varchar(255) NOT NULL,
  `chefs_email` varchar(255) NOT NULL,
  `chefs_contact_number` varchar(255) NOT NULL,
  `chefs_acc_number` varchar(255) NOT NULL,
  `chefs_address` varchar(255) NOT NULL,
  `chefs_message` text NOT NULL,
  `unique_id` varchar(255) NOT NULL,
  `chefs_psw` varchar(255) NOT NULL,
  `login_status` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chefs_registration`
--

INSERT INTO `chefs_registration` (`chefs_id`, `chefs_name`, `chefs_email`, `chefs_contact_number`, `chefs_acc_number`, `chefs_address`, `chefs_message`, `unique_id`, `chefs_psw`, `login_status`, `created_date`, `updated_date`) VALUES
(3, 'Prakash nayak', 'praksh@gmail.com', '9861845555', 'DGRDD3424', 'test address', 'Always live in servicesssssssssssss.', 'prakash', 'TVRJeg==', 1, '2016-09-02 18:41:58', '2017-01-05 11:28:41'),
(4, 'suresh Kumar', 'suresh@bletindia.com', '9861245555', '1234567', 'Bhubaneswar', '', 'suresh', 'YzNWeVpYTm8=', 1, '2016-12-03 12:11:04', '2017-01-30 11:27:03'),
(6, 'Manua', 'manua@gmail.com', '9861842222', 'MANU456', 'Nayapalli, Bhubaneswar', 'Always live in services. Gffgg', 'mano44', 'WkdWdGJ3PT0=', 1, '2016-12-03 15:11:09', '2017-01-30 11:47:37');

-- --------------------------------------------------------

--
-- Table structure for table `chef_token`
--

CREATE TABLE IF NOT EXISTS `chef_token` (
  `id` bigint(20) NOT NULL,
  `chef_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `device_token` varchar(255) NOT NULL COMMENT 'This is an unique id to identify device',
  `fcm_id` varchar(255) NOT NULL COMMENT 'This id used for only android devices.This id is used for push notification',
  `device_type` varchar(30) NOT NULL COMMENT 'ios or android',
  `created_date` datetime NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chef_token`
--

INSERT INTO `chef_token` (`id`, `chef_id`, `token`, `device_token`, `fcm_id`, `device_type`, `created_date`) VALUES
(3, 6, '0.98882900 1483595853', '', '', 'ios', '2017-01-05 11:27:33');

-- --------------------------------------------------------

--
-- Table structure for table `core`
--

CREATE TABLE IF NOT EXISTS `core` (
  `id` int(11) NOT NULL,
  `admin_name` varchar(120) NOT NULL,
  `email` varchar(80) NOT NULL,
  `alt_email` varchar(80) NOT NULL,
  `contact_no` varchar(55) NOT NULL,
  `password` varchar(100) NOT NULL,
  `facebook_left_url` varchar(255) NOT NULL,
  `facebook_right_url` varchar(255) NOT NULL,
  `twitter_left_url` varchar(255) NOT NULL,
  `twitter_right_url` varchar(255) NOT NULL,
  `active_status` int(11) NOT NULL,
  `site_url` varchar(120) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `core`
--

INSERT INTO `core` (`id`, `admin_name`, `email`, `alt_email`, `contact_no`, `password`, `facebook_left_url`, `facebook_right_url`, `twitter_left_url`, `twitter_right_url`, `active_status`, `site_url`) VALUES
(1, 'Administrator', 'demo@demo.com', 'joelcroft658@gmail.com', '+91-9898789999', 'fe01ce2a7fbac8fafaed7c982a04e229', 'http://facebook.com', 'http://facebook.com', 'http://twitter.com', 'http://twitter.com', 1, 'http://192.168.0.170/chorley-bunce/');

-- --------------------------------------------------------

--
-- Table structure for table `email_template`
--

CREATE TABLE IF NOT EXISTS `email_template` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `contents` text CHARACTER SET utf8 NOT NULL,
  `created_date` date NOT NULL,
  `updated_on` date NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_template`
--

INSERT INTO `email_template` (`id`, `title`, `contents`, `created_date`, `updated_on`) VALUES
(1, 'Chefs Account approved email', '<body>\r\n       <table width="805" style="border-radius:25px; overflow:hidden; border:1px solid #EEE; margin:35px 25px; box-shadow:0 0 5px rgba(0,0,0,0.1);" cellpadding="0" cellspacing="0">\r\n      	<tr>\r\n            <td style="color:#999; background:#EEE; text-align:center;">\r\n               \r\n                   <img src="http://192.168.0.170/chorley-bunce/images/cb_logo-back.png" style="float:left;" />            \r\n            </td>\r\n        </tr>\r\n        <tr>\r\n        	<td>\r\n            	<div style="min-height:300px; padding:35px; color:#555; font-size:14px;">\r\n                	Dear <strong>%FULLNAME%</strong> ,<br/><br/>\r\n                    <div style="padding-left:30px; line-height:20px;">\r\n                   	  <strong style="font-size:20px;">Your account has been approved successfully.</strong><br/><br/>\r\n                      <strong>Your Login Credential as follows </strong> <br/><br/>\r\n                        \r\n                        <strong>USER ID : </strong> %LOGINID% <br/>\r\n                        <strong>Password : </strong> %PASSWORD% <br/><br/><br/>                      \r\n                    </div>\r\n                    Thanks<br/>\r\n                    <strong>%ADMINNAME%</strong><br/>\r\n                    <strong>%ADMINEMAIL%</strong>\r\n                    \r\n                </div>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n        	<td style="background:#EEE;">\r\n            	<div style="padding:15px; text-align:center; font-size:12px; color:#999;">&copy; Chorley Bunce %CURRENTYEAR% . All Rights Reserved. </div>\r\n            </td>\r\n        </tr>\r\n      </table>\r\n</body>', '2015-12-03', '2016-05-23'),
(2, 'Chefs Account declined email', '<body>\r\n       <table width="805" style="border-radius:25px; overflow:hidden; border:1px solid #EEE; margin:35px 25px; box-shadow:0 0 5px rgba(0,0,0,0.1);" cellpadding="0" cellspacing="0">\r\n      	<tr>\r\n            <td style="color:#999; background:#EEE; text-align:center;">\r\n               \r\n                   <img src="http://192.168.0.170/chorley-bunce/images/cb_logo-back.png" style="float:left;" />            \r\n            </td>\r\n        </tr>\r\n        <tr>\r\n        	<td>\r\n            	<div style="min-height:300px; padding:35px; color:#555; font-size:14px;">\r\n                	Dear <strong>%FULLNAME%</strong> ,<br/><br/>\r\n                    \r\n                    <div style="padding-left:30px; line-height:20px;">\r\n                   	  <strong style="font-size:20px;">\r\n                        Your account has been declined by the chorley bunce.<br/></strong>\r\n                        For any other information contact to chorley bunce in  <strong>%ADMINEMAIL%\r\n                      </strong>\r\n                      <br/><br/><br/>\r\n                    </div>\r\n                    \r\n                    <strong>Thanks</strong><br/>\r\n                    %ADMINNAME%<br/>\r\n                    %ADMINEMAIL%\r\n                    \r\n                </div>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n        	<td style="background:#EEE;">\r\n            	<div style="padding:15px; text-align:center; font-size:12px; color:#999;">&copy; Chorley Bunce %CURRENTYEAR% . All Rights Reserved. </div>\r\n            </td>\r\n        </tr>\r\n      </table>\r\n</body>', '2015-12-03', '2015-12-03'),
(3, 'When admin register a sub domain - Mail goes to user', '<body>\r\n       <table width="805" style="border-radius:25px; overflow:hidden; border:1px solid #EEE; margin:35px 25px; box-shadow:0 0 5px rgba(0,0,0,0.1);" cellpadding="0" cellspacing="0">\r\n      	<tr>\r\n            <td style="color:#999; background:#EEE; text-align:center;">\r\n               \r\n                   <img src="http://192.168.0.170/chorley-bunce/images/cb_logo-back.png" style="float:left;" />            \r\n            </td>\r\n        </tr>\r\n        <tr>\r\n        	<td>\r\n            	<div style="min-height:300px; padding:35px; color:#555; font-size:14px;">\r\n                	Dear <strong>%FULLNAME%</strong> ,<br/><br/>\r\n                    <div style="padding-left:30px; line-height:20px;">\r\n                   	  <strong style="font-size:20px;">\r\n                      A new job allocated to you.Job allocation details as follows.</strong><br/><br/>\r\n                      \r\n                        <strong>Allocation A/C Number: </strong> %ALLOACNO% <br/><br/>\r\n                        \r\n                        <strong>Job Name : </strong> %JOBNAME% <br/>\r\n                        <strong>Location : </strong> %LOCATION% <br/>\r\n                        <strong>Date: : </strong> %DATE% <br/><br/><br/>                      \r\n                    </div>\r\n                    Thanks<br/>\r\n                    <strong>%ADMINNAME%</strong><br/>\r\n                    <strong>%ADMINEMAIL%</strong>\r\n                    \r\n                </div>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n        	<td style="background:#EEE;">\r\n            	<div style="padding:15px; text-align:center; font-size:12px; color:#999;">&copy; Chorley Bunce %CURRENTYEAR% . All Rights Reserved. </div>\r\n            </td>\r\n        </tr>\r\n      </table>\r\n</body>', '2015-12-04', '2015-12-04'),
(4, 'Sub domain activated mail - Mail goes to user', '<body>\r\n       <table width="805" style="border-radius:25px; overflow:hidden; border:1px solid #EEE; margin:35px 25px; box-shadow:0 0 5px rgba(0,0,0,0.1);" cellpadding="0" cellspacing="0">\r\n      	<tr>\r\n        	<td style="color:#999; background:#EEE; text-align:center;">\r\n            	<h2 style="">\r\n                	<img src="http://and.democrat/images/logo.png" style="width:70px; height:70px; float:left; margin:15px;" />\r\n                	<span style="display:inline-block; margin-top: 26px; font-size: 35px; text-transform:uppercase;">%HEADERTEXT%</span>\r\n            		<img src="http://and.democrat/images/logo.png" style="width:70px; height:70px; float:right; margin:15px;" />\r\n                </h2>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n        	<td>\r\n            	<div style="min-height:300px; padding:35px; color:#555; font-size:14px;">\r\n                	Dear <strong>%FULLNAME%</strong> ,<br/><br/>\r\n\r\n                    <div style="padding-left:30px; line-height:20px;">\r\n                      <strong>Congratulations!</strong><br/><br/>\r\n                   	  <strong style="font-size:20px;">Your account &amp; sub domain have been activated successfully.</strong><br/><br/>\r\n                        <strong>Your Profile URL is the following :</strong> %SUBDOMAINURL% <br/>\r\n                        <br/>\r\n                        \r\n                      <strong>Your Login Credentials are </strong> <br/><br/>\r\n                        \r\n                        <strong>Profile Name : </strong> %SUBDOMAINNAME% <br/>\r\n                        <strong>Password : </strong> %PASSWORD% <br/><br/>\r\n                        \r\n                        \r\n                        You can now log-in and start editing your Profile!<br/><br/>\r\n                        \r\n                        \r\n                       <strong> PLEASE NOTE : </strong> <br/><br/>\r\n                       \r\n                       We will not display your email address publicly unless you expressely do so.<br/><br/>\r\n                       \r\n                       You can use this Profile&nbsp;for free for a period of %DAYS% days. &nbsp; After that, if you wish to continue using it, you will need to claim it and re-publish it again. <br/><br/>\r\n\r\n                       If you wish to own the Profile for ONE FULL YEAR, just click on the UPGRADE button in your EDIT PAGE and follow the instructions.<br><br>\r\n                       \r\n                       \r\n                       If you subscribed to our newsletter by mistake or if you no longer want to receive emails from us, you can always unsubscribe by sending a message to %ADMINEMAIL%\r\n\r\n                       \r\n                        <br/><br/><br/><br/>                       \r\n                    </div>\r\n                    Thanks<br/>\r\n                    <strong>%ADMINNAME%</strong><br/>\r\n                    <strong>%ADMINEMAIL%</strong>\r\n                    \r\n                </div>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n        	<td style="background:#EEE;">\r\n            	<div style="padding:15px; text-align:center; font-size:12px; color:#999;">All rights &copy; The Freedom of Speech Network %CURRENTYEAR%</div>\r\n            </td>\r\n        </tr>\r\n      </table>\r\n</body>', '2015-12-04', '2015-12-04'),
(5, 'Admin forgot password - Mail goes to the admin', '<body>\r\n  <table width="805" style="border-radius:25px; overflow:hidden; border:1px solid #EEE; margin:35px 25px; box-shadow:0 0 5px rgba(0,0,0,0.1);" cellpadding="0" cellspacing="0">\r\n    <tr>\r\n        <td style="color:#999; background:#EEE; text-align:center;">\r\n            <h2>\r\n               <img src="http://192.168.0.170/chorley-bunce/images/cb_logo.png" style="float:left;" />            </h2>\r\n        </td>\r\n    </tr>\r\n    <tr>\r\n        <td>\r\n            <div style="min-height:300px; padding:35px; color:#555; font-size:14px;">\r\n                Dear <strong>%ADMINNAME%</strong> ,<br/><br/>\r\n                <div style="padding-left:30px; line-height:20px;">\r\n                  <p><strong>Your login credential as follows</strong><br/><br/>\r\n                  <strong>Email  :</strong> %EMAIL% <br/>\r\n                  <strong>Password :</strong> %PASSWORD% \r\n                    <br/>\r\n                    \r\n                    <br/><br/>\r\n                    \r\n                    \r\n                    <br/><br/>\r\n                  </p>\r\n</div>\r\n                Best Wishes<br/>\r\n                <strong>%ADMINNAME%</strong><br/>\r\n                \r\n                \r\n            </div>\r\n        </td>\r\n    </tr>\r\n    <tr>\r\n        <td style="background:#EEE;">\r\n            <div style="padding:15px; text-align:center; font-size:12px; color:#999;">\r\n            	&copy; Chorley Bunce %CURRENTYEAR% . All Rights Reserved. \r\n            </div>\r\n        </td>\r\n    </tr>\r\n  </table>\r\n</body>', '2015-12-04', '2015-12-04'),
(6, 'User claim profile email confirmation Template :: Mail goes to user', '<body>\r\n    <table width="805" style="border-radius:25px; overflow:hidden; border:1px solid #EEE; margin:35px 25px; box-shadow:0 0 5px rgba(0,0,0,0.1);" cellpadding="0" cellspacing="0">\r\n    <tr>\r\n        <td style="color:#999; background:#EEE; text-align:center;">\r\n            <h2 style="">\r\n                <img src="http://and.democrat/images/logo.png" style="width:70px; height:70px; float:left; margin:15px;" />\r\n                <span style="display:inline-block; margin-top: 26px; font-size: 35px; text-transform:uppercase;">%HEADERTEXT%</span>\r\n                <img src="http://and.democrat/images/logo.png" style="width:70px; height:70px; float:right; margin:15px;" />\r\n            </h2>\r\n        </td>\r\n    </tr>\r\n    <tr>\r\n        <td>\r\n            <div style="min-height:300px; padding:35px; color:#555; font-size:14px;">\r\n                Dear <strong>%FULLNAME%</strong> ,<br/><br/>\r\n                <div style="padding-left:30px; line-height:20px;">\r\n                  <strong style="font-size:20px;">Please click in the link to confirm your email address & become a owner of the profile %CLAIMPROFILEURL%.</strong><br/><br/>\r\n                  \r\n                  <a href="%DOMAINURL%/confirm-email-address-for-claim-profile?id=%CLAIMPROFILEID%&token=%TOKEN%&ce=%CLAIMEMAIL%&cn=%CLAIMFULLNAME%&ifs=%INTERESTFORSUBSCRIBE%">Click here to confirm your email address</a><br/><br/><br/>\r\n                    \r\n                 \r\n                    \r\n                   \r\n                    \r\n              <strong> NOTE : </strong><br/><br/>\r\n                    <span style="color:#F00;">if your email address is not confirmed then any other people may take this profile.</span><br/>\r\n                    After your email confirmation we will send your account login credential to your  email address.<br/><br/>                       \r\n                </div>\r\n                Thanks<br/>\r\n                <strong>%ADMINNAME%</strong><br/>\r\n                <strong>%ADMINEMAIL%</strong>\r\n                \r\n            </div>\r\n        </td>\r\n    </tr>\r\n    <tr>\r\n        <td style="background:#EEE;">\r\n            <div style="padding:15px; text-align:center; font-size:12px; color:#999;">All rights &copy; The Freedom of Speech Network %CURRENTYEAR%</div>\r\n        </td>\r\n    </tr>\r\n  </table>\r\n</body>', '2015-12-17', '2015-12-17'),
(7, 'When a user claimed a profile :: Mail goes to admin', '<body>\r\n  <table width="805" style="border-radius:25px; overflow:hidden; border:1px solid #EEE; margin:35px 25px; box-shadow:0 0 5px rgba(0,0,0,0.1);" cellpadding="0" cellspacing="0">\r\n    <tr>\r\n        <td style="color:#999; background:#EEE; text-align:center;">\r\n            <h2 style="">\r\n                <img src="http://and.democrat/images/logo.png" style="width:70px; height:70px; float:left; margin:15px;" />\r\n                <span style="display:inline-block; margin-top: 26px; font-size: 35px; text-transform:uppercase;">%HEADERTEXT%</span>\r\n                <img src="http://and.democrat/images/logo.png" style="width:70px; height:70px; float:right; margin:15px;" />\r\n            </h2>\r\n        </td>\r\n    </tr>\r\n    <tr>\r\n        <td>\r\n            <div style="min-height:300px; padding:35px; color:#555; font-size:14px;">\r\n                Dear <strong>%ADMINNAME%</strong> ,<br/><br/>\r\n                <div style="padding-left:30px; line-height:20px;">\r\n                  <strong style="font-size:20px;">I am %USERNAME% recently getting the ownership of this sub domain.</strong><br/><br/>\r\n                    <strong>Sub Domain URL :</strong> %SUBDOMAINURL% <br/>\r\n                    <br/>\r\n                    \r\n                  <strong>Thank you very much for such facility.</strong> <br/><br/>\r\n                    \r\n                   \r\n                    <br/><br/>                    \r\n                </div>\r\n                Thanks<br/>\r\n                <strong>%USERNAME%</strong><br/>\r\n                <strong>%USEREMAIL%</strong>\r\n                \r\n            </div>\r\n        </td>\r\n    </tr>\r\n    <tr>\r\n        <td style="background:#EEE;">\r\n            <div style="padding:15px; text-align:center; font-size:12px; color:#999;">All rights &copy; The Freedom of Speech Network %CURRENTYEAR%</div>\r\n        </td>\r\n    </tr>\r\n  </table>\r\n</body>', '2015-12-17', '2015-12-17'),
(8, 'Chefs Login credential mail', '<body>\r\n       <table width="805" style="border-radius:25px; overflow:hidden; border:1px solid #EEE; margin:35px 25px; box-shadow:0 0 5px rgba(0,0,0,0.1);" cellpadding="0" cellspacing="0">\r\n      	<tr>\r\n            <td style="color:#999; background:#EEE; text-align:center;">\r\n                <h2>\r\n                   <img src="http://192.168.0.170/chorley-bunce/images/cb_logo.png" style="float:left;" />            </h2>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n        	<td>\r\n            	<div style="min-height:300px; padding:35px; color:#555; font-size:14px;">\r\n                	Dear <strong>%FULLNAME%</strong> ,<br/><br/>\r\n                    <div style="padding-left:30px; line-height:20px;">\r\n                   	  <strong style="font-size:20px;">Your account has been created successfully.</strong><br/><br/>\r\n                      <strong>Your Login Credential as follows </strong> <br/><br/>\r\n                        \r\n                        <strong>Login ID : </strong> %LOGINID% <br/>\r\n                        <strong>Password : </strong> %PASSWORD% <br/><br/><br/>                      \r\n                    </div>\r\n                    Thanks<br/>\r\n                    <strong>%ADMINNAME%</strong><br/>\r\n                    <strong>%ADMINEMAIL%</strong>\r\n                    \r\n                </div>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n        	<td style="background:#EEE;">\r\n            	<div style="padding:15px; text-align:center; font-size:12px; color:#999;">&copy; Chorley Bunce %CURRENTYEAR% . All Rights Reserved. </div>\r\n            </td>\r\n        </tr>\r\n      </table>\r\n</body>', '2016-08-25', '2016-08-25'),
(9, 'Sub domain Activation :: Mail goes to the user', '<style>@import url(https://fonts.googleapis.com/css?family=Special+Elite);body{font-family: ''Special Elite'', cursive;}</style>\r\n<body>\r\n      <table style="width:95%; border-radius:25px; overflow:hidden; border:1px solid #EEE; margin:35px 2.5%; box-shadow:0 0 5px rgba(0,0,0,0.1);" cellpadding="0" cellspacing="0">\r\n      	<tr>\r\n        	<td style="color:#999; background:#EEE; text-align:center;">\r\n            	<h2 style="">\r\n                	<img src="http://and.democrat/images/logo.png" style="width:70px; height:70px; float:left; margin:15px;" />\r\n                	<span style="display:inline-block; margin-top: 26px; font-size: 42px; text-transform:uppercase;">%HEADERTEXT%</span>\r\n            		<img src="http://and.democrat/images/logo.png" style="width:70px; height:70px; float:right; margin:15px;" />\r\n                </h2>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n        	<td>\r\n            	<div style="min-height:300px; padding:35px; color:#555; font-size:14px;">\r\n                	Dear <strong>%FULLNAME%</strong> ,<br/><br/>\r\n                    <div style="padding-left:30px; line-height:20px;">\r\n                   	  <strong style="font-size:20px;">Your sub domain has been created successfully.</strong><br/><br/>\r\n                        <strong>Your Profile URL :</strong> %SUBDOMAINURL% <br/>\r\n                        <br/>\r\n                        \r\n                      <strong>Your Login Credential as follows </strong> <br/><br/>\r\n                        \r\n                      \r\n                        <strong>Email : </strong> %EMAIL% <br/>\r\n                        <strong>Password : </strong> %PASSWORD% <br/><br/><br/>\r\n                        \r\n                        \r\n                       <strong> NOTE : </strong> If your profile url is not coming then wait for atleast 1 hour.After that check again.Other wise contact us by our email <strong>%ADMINEMAIL%</strong>\r\n                       \r\n                       \r\n                        <br/><br/><br/><br/>                       \r\n                    </div>\r\n                    Thanks<br/>\r\n                    <strong>%ADMINNAME%</strong><br/>\r\n                    <strong>%ADMINEMAIL%</strong>\r\n                    \r\n                </div>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n        	<td style="background:#EEE;">\r\n            	<div style="padding:15px; text-align:center; font-size:12px; color:#999;">All rights &copy; The Freedom of Speech Network %CURRENTYEAR%</div>\r\n            </td>\r\n        </tr>\r\n      </table>\r\n</body>', '2015-12-03', '2015-12-03'),
(10, 'Mail goes to admin :: When a user give a payment', '<body>\r\n  <table width="805" style="border-radius:25px; overflow:hidden; border:1px solid #EEE; margin:35px 25px; box-shadow:0 0 5px rgba(0,0,0,0.1);" cellpadding="0" cellspacing="0">\r\n    <tr>\r\n        <td style="color:#999; background:#EEE; text-align:center;">\r\n            <h2 style="">\r\n                <img src="http://and.democrat/images/logo.png" style="width:70px; height:70px; float:left; margin:15px;" />\r\n                <span style="display:inline-block; margin-top: 26px; font-size: 35px; text-transform:uppercase;">%HEADERTEXT%</span>\r\n                <img src="http://and.democrat/images/logo.png" style="width:70px; height:70px; float:right; margin:15px;" />\r\n            </h2>\r\n        </td>\r\n    </tr>\r\n    <tr>\r\n        <td>\r\n            <div style="min-height:300px; padding:35px; color:#555; font-size:14px;">\r\n                Dear <strong>%ADMINNAME%</strong> ,<br/><br/>\r\n                <div style="padding-left:30px; line-height:20px;">\r\n                  <p>\r\n                  <strong>I am %USERNAME% recently paid my subdomain renewal fee.<br>\r\n						My subdomain name is %SUBDOMAINURL%.</strong>\r\n                  </p><br/><br/>\r\n</div>\r\n                Best Wishes<br/>\r\n                <strong>%USERNAME%</strong><br/>\r\n                \r\n                \r\n            </div>\r\n        </td>\r\n    </tr>\r\n    <tr>\r\n        <td style="background:#EEE;">\r\n            <div style="padding:15px; text-align:center; font-size:12px; color:#999;">All rights &copy; The Freedom of Speech Network %CURRENTYEAR%</div>\r\n        </td>\r\n    </tr>\r\n  </table>\r\n</body>', '2016-02-09', '2016-02-09'),
(11, 'News Letter', '<table width="805" style="border-radius:25px; overflow:hidden; border:1px solid #EEE; margin:35px 25px; box-shadow:0 0 5px rgba(0,0,0,0.1); font-family: ''Special Elite'';" cellpadding="0" cellspacing="0">\r\n      	<tr>\r\n        	<td style="color:#999; text-align:center;">\r\n            	 <div style="background:#EEE; padding:1px">\r\n                     <table>\r\n                        <tr>\r\n                            <td><img src="http://and.democrat/images/logo.png" style="width:70px; height:70px; float:left; margin:15px;" /></td>\r\n                            <td><h2>democrat.and.republican</h2></td>\r\n                            <td><img src="http://and.democrat/images/logo.png" style="width:70px; height:70px; float:right; margin:15px;" /></td>\r\n                        </tr>\r\n                    </table>\r\n                </div>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n        	<td>\r\n            	<div style="min-height:300px; padding:35px; color:#555; font-size:14px;">\r\n                	Dear <strong>Customer</strong> ,<br/><br/>\r\n                    <div style="padding-left:30px; line-height:20px;">\r\n                   	  Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.\r\n\r\n\r\n                        \r\n                     <br/><br/><br/><br/>                       \r\n                    </div>\r\n                    Thanks<br/>\r\n                    <strong>%ADMINNAME%</strong><br/>\r\n                    <strong>%ADMINEMAIL%</strong>\r\n                    \r\n                </div>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n        	<td style="background:#EEE;">\r\n            	<div style="padding:15px; text-align:center; font-size:12px; color:#999;">All rights &copy; The Freedom of Speech Network %CURRENTYEAR%</div>\r\n            </td>\r\n        </tr>\r\n      </table>', '2016-03-01', '2016-03-01'),
(12, 'Confirm Comment', '<body>\r\n        <table width="805" style="border-radius:25px; overflow:hidden; border:1px solid #EEE; margin:35px 25px; box-shadow:0 0 5px rgba(0,0,0,0.1);" cellpadding="0" cellspacing="0">\r\n      	<tr>\r\n        	<td style="color:#999; background:#EEE; text-align:center;">\r\n            	<h2 style="">\r\n                	<img src="http://and.democrat/images/logo.png" style="width:70px; height:70px; float:left; margin:15px;" />\r\n                	<span style="display:inline-block; margin-top: 26px; font-size: 35px; text-transform:uppercase;">%HEADERTEXT%</span>\r\n            		<img src="http://and.democrat/images/logo.png" style="width:70px; height:70px; float:right; margin:15px;" />\r\n                </h2>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n        	<td>\r\n            	<div style="min-height:300px; padding:35px; color:#555; font-size:14px;">\r\n                	Dear <strong>%FULLNAME%</strong> ,<br/><br/>\r\n                    <div style="padding-left:30px; line-height:20px;">\r\n                   	  <p><strong style="font-size:20px;"> Comment posted successfully.</strong><br/><br/>\r\n                      <strong>Your Comment  :</strong> </p>\r\n                   	  <p>%COMMENT% <br/><br/><br/>\r\n                   	  </p>\r\n                </div>\r\n                    Thanks<br/>\r\n                    <strong>%ADMINNAME%</strong><br/>\r\n                    <strong>%ADMINEMAIL%</strong>\r\n                    \r\n                </div>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n        	<td style="background:#EEE;">\r\n            	<div style="padding:15px; text-align:center; font-size:12px; color:#999;">All rights &copy; The Freedom of Speech Network %CURRENTYEAR%</div>\r\n            </td>\r\n        </tr>\r\n      </table>\r\n</body>', '2016-03-04', '2016-03-04'),
(13, 'Report of  offensive :: Mail goes to Admin', '<body>\r\n  <table width="805" style="border-radius:25px; overflow:hidden; border:1px solid #EEE; margin:35px 25px; box-shadow:0 0 5px rgba(0,0,0,0.1);" cellpadding="0" cellspacing="0">\r\n    <tr>\r\n        <td style="color:#999; background:#EEE; text-align:center;">\r\n            <h2 style="">\r\n                <img src="http://and.democrat/images/logo.png" style="width:70px; height:70px; float:left; margin:15px;" />\r\n                <span style="display:inline-block; margin-top: 26px; font-size: 35px; text-transform:uppercase;">%HEADERTEXT%</span>\r\n                <img src="http://and.democrat/images/logo.png" style="width:70px; height:70px; float:right; margin:15px;" />\r\n            </h2>\r\n        </td>\r\n    </tr>\r\n    <tr>\r\n        <td>\r\n            <div style="min-height:300px; padding:35px; color:#555; font-size:14px;">\r\n                Dear <strong>%ADMINNAME%</strong> ,<br/><br/>\r\n                <div style="padding-left:30px; line-height:20px;">\r\n                  <strong style="font-size:20px;">\r\n                  The following profile contain offensive mterial/illigal/misleading information. \r\n                  </strong><br/><br/>\r\n                    <strong>Sub domail URL :</strong> %SUBDOMAINURL% <br/>\r\n                    <br/><br/><br/><br/>                    \r\n                </div>\r\n                Thanks<br/>\r\n                <strong>%USEREMAIL%</strong>\r\n                \r\n            </div>\r\n        </td>\r\n    </tr>\r\n    <tr>\r\n        <td style="background:#EEE;">\r\n            <div style="padding:15px; text-align:center; font-size:12px; color:#999;">All rights &copy; The Freedom of Speech Network %CURRENTYEAR%</div>\r\n        </td>\r\n    </tr>\r\n  </table>\r\n</body>', '2016-05-02', '2016-05-02'),
(14, 'Contact us email Template', '<body>\r\n        <table width="805" style="border-radius:25px; overflow:hidden; border:1px solid #EEE; margin:35px 25px; box-shadow:0 0 5px rgba(0,0,0,0.1);" cellpadding="0" cellspacing="0">\r\n      	<tr>\r\n        	<td style="color:#999; background:#EEE; text-align:center;">\r\n            	<h2 style="">\r\n                	<img src="http://and.democrat/images/logo.png" style="width:70px; height:70px; float:left; margin:15px;" />\r\n                	<span style="display:inline-block; margin-top: 26px; font-size: 35px; text-transform:uppercase;">%HEADERTEXT%</span>\r\n            		<img src="http://and.democrat/images/logo.png" style="width:70px; height:70px; float:right; margin:15px;" />\r\n                </h2>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n        	<td>\r\n            	<div style="min-height:300px; padding:35px; color:#555; font-size:14px;">\r\n                	Dear <strong>%ADMINNAME%</strong> ,<br/><br/>\r\n                    <div style="padding-left:30px; line-height:20px;">\r\n                   	  <br/><br/>\r\n                      \r\n                     \r\n                        <strong>Name :</strong> %FULLNAME% <br/>\r\n                        <strong>Email :</strong> %EMAIL% <br/><br/>\r\n                        \r\n                        \r\n                        <br/>\r\n                        \r\n                        \r\n                  <strong> Message : </strong><br/><br/> %MESSAGE%<br/>\r\n                        <br/><br/><br/>                       \r\n                    </div>\r\n                    Thanks<br/>\r\n                    <strong>%ADMINNAME%</strong><br/>\r\n                    <strong>%ADMINEMAIL%</strong>\r\n                    \r\n                </div>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n        	<td style="background:#EEE;">\r\n            	<div style="padding:15px; text-align:center; font-size:12px; color:#999;">All rights &copy; The Freedom of Speech Network %CURRENTYEAR%</div>\r\n            </td>\r\n        </tr>\r\n      </table>\r\n</body>', '2016-05-18', '2016-05-18');

-- --------------------------------------------------------

--
-- Table structure for table `job_allocations`
--

CREATE TABLE IF NOT EXISTS `job_allocations` (
  `allo_id` bigint(20) NOT NULL,
  `chef_id` bigint(20) NOT NULL,
  `allocation_ac_no` varchar(255) NOT NULL,
  `job_name` varchar(255) NOT NULL,
  `job_location` varchar(255) NOT NULL,
  `allocation_date` date NOT NULL,
  `created_date` date NOT NULL,
  `updated_date` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `job_allocations`
--

INSERT INTO `job_allocations` (`allo_id`, `chef_id`, `allocation_ac_no`, `job_name`, `job_location`, `allocation_date`, `created_date`, `updated_date`) VALUES
(1, 4, 'MON12341', 'The king of the jungle', 'Jungle', '2016-12-07', '2016-12-03', '2016-12-03'),
(3, 6, 'ALAN34543', 'My Bday Party', 'Bhubaneswar Club', '2017-01-26', '2016-12-05', '2017-01-25'),
(4, 6, 'AL435F', 'Jhalmudi Party', 'La Festa', '2017-01-31', '2016-12-09', '2017-01-30'),
(10, 6, '12312', 'asdasdsa', 'asdsad', '2016-12-29', '2016-12-10', '2016-12-10'),
(11, 6, '1234', 'Manua Bday', 'La Feasta', '2016-12-31', '2016-12-10', '2016-12-10'),
(12, 6, 'dsaad', 'SURA PEEBA', 'dfdsf', '2017-02-01', '2016-12-10', '2017-01-30'),
(13, 6, 'N32N21', 'Kie Kala Mote', 'Manchswar', '2017-01-08', '2017-01-05', '2017-01-05');

-- --------------------------------------------------------

--
-- Table structure for table `master_order`
--

CREATE TABLE IF NOT EXISTS `master_order` (
  `order_id` bigint(20) NOT NULL,
  `allocation_id` bigint(20) NOT NULL,
  `chef_id` bigint(20) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `order_accno` varchar(255) NOT NULL,
  `chefs_name` varchar(255) NOT NULL,
  `chefs_email` varchar(255) NOT NULL,
  `chefs_contact_number` varchar(155) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `delivery_address` longtext NOT NULL,
  `supplier_acno` varchar(155) NOT NULL,
  `delivery_notes` longtext NOT NULL,
  `grand_total` double NOT NULL,
  `order_status` varchar(100) NOT NULL,
  `delivery_datetime` varchar(255) NOT NULL,
  `order_date` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `master_order`
--

INSERT INTO `master_order` (`order_id`, `allocation_id`, `chef_id`, `supplier_id`, `order_accno`, `chefs_name`, `chefs_email`, `chefs_contact_number`, `job_title`, `delivery_address`, `supplier_acno`, `delivery_notes`, `grand_total`, `order_status`, `delivery_datetime`, `order_date`) VALUES
(1, 11, 6, 3, '1482499150631', 'Prakash nayak', 'praksh@gmail.com', '9861845555', 'Manua Bday', 'Fdgdfg', 'ABD34324DF', '', 2, 'delevrd', '2016-12-23 06:53 PM', '2016-12-23'),
(2, 12, 6, 3, '1485497023632', 'Manua', 'manua@gmail.com', '9861842222', 'SURA PEEBA', 'Rrryuuu', 'ABD34324DF', '', 17.5, 'notdelevrd', '2017-01-27 11:38 AM', '2017-01-27');

-- --------------------------------------------------------

--
-- Table structure for table `measurment_units`
--

CREATE TABLE IF NOT EXISTS `measurment_units` (
  `id` int(11) NOT NULL,
  `unit_name` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `measurment_units`
--

INSERT INTO `measurment_units` (`id`, `unit_name`, `created_date`, `updated_date`) VALUES
(1, 'Ltrs', '2016-08-26 18:34:02', '2016-08-26 18:34:02'),
(2, 'Kg', '2016-08-26 18:34:06', '2016-08-26 18:34:06'),
(3, 'Pcs', '2016-08-26 18:34:11', '2016-08-26 18:34:11'),
(4, 'Packet', '2016-08-26 18:34:16', '2016-08-26 18:34:16');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE IF NOT EXISTS `order_items` (
  `order_item_id` bigint(150) NOT NULL,
  `allocation_id` bigint(20) NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `chef_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `supplier_id` bigint(20) NOT NULL,
  `unit_price` double NOT NULL,
  `qty` bigint(20) NOT NULL,
  `total_price` double NOT NULL,
  `special_notes` longtext COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `allocation_id`, `order_id`, `chef_id`, `product_id`, `supplier_id`, `unit_price`, `qty`, `total_price`, `special_notes`) VALUES
(1, 11, 1, 6, 13, 3, 1, 1, 1, ''),
(2, 11, 1, 6, 15, 3, 1, 1, 1, ''),
(3, 12, 2, 6, 62, 3, 8, 2, 16, ''),
(4, 12, 2, 6, 22, 3, 1, 2, 1.5, '');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `product_id` bigint(20) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `prd_cat_id` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_code` varchar(155) NOT NULL,
  `qty_details` varchar(255) NOT NULL,
  `product_photo` varchar(255) NOT NULL,
  `prd_unit_id` int(11) NOT NULL,
  `product_price` double NOT NULL,
  `product_details` longtext NOT NULL,
  `prd_avl_status` varchar(4) NOT NULL,
  `prd_status` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `supplier_id`, `prd_cat_id`, `product_name`, `product_code`, `qty_details`, `product_photo`, `prd_unit_id`, `product_price`, `product_details`, `prd_avl_status`, `prd_status`, `created_date`, `updated_date`) VALUES
(6, 1, '2,1', 'Mixed Fruit Juice', 'J004', '500 gm pack', '1472821517.jpg', 2, 50, 'This is very and tasty. Love to drink it.', 'Yes', 0, '2016-09-02 18:35:17', '2016-09-02 18:35:17'),
(7, 1, '3', 'Chicken Leg Piece', 'CH-990', '1 Piece', '1472821686.jpg', 3, 20, 'Healthy and hygienic chicken.', 'Yes', 0, '2016-09-02 18:38:06', '2016-09-02 18:38:06'),
(8, 1, '4', 'Mushroom', 'MS808', '1 Kg Pack', '1472822220.jpg', 2, 30, 'Buttom Mushromm. Tasty tasty', 'Yes', 0, '2016-09-02 18:47:00', '2016-09-02 18:47:00'),
(9, 3, '1', 'Ripe Mango', 'MG001', '1 Kg Pack', '1472822477.jpeg', 2, 4, 'Tasf sdf dsf dsf dsf dsf dsf dsf dsf df sf dsf ds fds. sdfds fdsf ds', 'Yes', 0, '2016-09-02 18:51:17', '2016-09-02 18:51:17'),
(10, 3, '5', 'Ilishi', 'FS-004', '1 Kg', '1472880824.jpeg', 2, 8, 'This is a very tasty fish.', 'Yes', 0, '2016-09-03 11:03:44', '2016-09-03 11:03:44'),
(11, 3, '5', 'Pamfret Fish', 'PM09', '1 Kg', '1472881005.jpeg', 2, 5, 'Very very tasty fish when cooked with mustard paste.', 'Yes', 0, '2016-09-03 11:06:15', '2016-09-03 11:06:46'),
(12, 3, '4', 'Brokoli', 'BK90', '1 Pc', '1472881119.jpg', 3, 2, 'dsf dsf dsf dsf dsf dsfrewf ert etret cvbcb tr grycvbvc gcb cvbvcb vcbvc.', 'Yes', 0, '2016-09-03 11:08:39', '2016-09-03 11:08:39'),
(13, 3, '6', 'Coriander', 'CN95', '100 gm Pack', '1472881491.jpg', 4, 1, 'sdf dsdf ghgb jh fghgrt rty rt bvvc n vbngh nvbn bv.', 'Yes', 0, '2016-09-03 11:14:51', '2016-09-03 11:14:51'),
(14, 3, '6', 'Javitri', 'JV44', '10 gm Pack', '1472881848.jpg', 4, 1, 'dfg dfg fdg fdg fdg htrg gf rt rtyry yrtyrty rty fghh fgh gf.', 'Yes', 0, '2016-09-03 11:20:48', '2016-09-03 11:20:48'),
(15, 3, '6', 'Clove', 'CV22', '10 gm Pack', '1472881916.jpg', 4, 1, 'fdgd gfdg tr gcgretre re ret re cv bgcv cvbcf er xcb fgd cvbgcvb vcb cvb vcy rtyyu uy yui yui yuij hjkjh hjk hjk hjk hjk jhk jh.', 'Yes', 0, '2016-09-03 11:21:56', '2016-09-03 11:21:56'),
(16, 3, '6,4', 'Coriander Leaf', 'CL04', '50 gm Bundle Pc', '1472882018.jpg', 3, 1, 'gdf dfg d fdg fdg fdg dfgfd gfdg fdgfdgdfgdf gdg dfg fdg fdg dfgdf dfg df', 'Yes', 0, '2016-09-03 11:23:38', '2016-09-03 11:23:38'),
(17, 3, '1', 'Green Grapes', 'GP444', '1 Kg', '1472882192.jpg', 2, 15, 'dfg fdg ffdg f fdh fgh fgh fgh fgh fgh fgh gfhtfhtr hfgh fgh fgh', 'Yes', 0, '2016-09-03 11:26:33', '2016-09-03 11:26:33'),
(18, 3, '1', 'Pomegranete', 'PM863', '1 Kg', '1472882313.jpeg', 2, 14, 'dvsfg dfg ffdg fdg fdgre ertret ret ret c bcvb cvbcvbvcb cvbvcbvcb.', 'Yes', 0, '2016-09-03 11:28:33', '2016-09-03 11:28:33'),
(19, 3, '4', 'Cauli Flower', 'CLF441', '1 Pc', '1472882595.jpeg', 3, 1, 'df dsfdsf dsf dfg dg fdgfdg fdg fdg fdgfdg fdg fdgfdg fdgdfg fdgdfgfdgfd g fdgfdg fdgfd.', 'Yes', 0, '2016-09-03 11:33:15', '2016-09-03 11:33:15'),
(20, 3, '2', 'Soda Water', 'SD234', '1 pack', '1472882813.jpg', 4, 1, 'vfddf gfdj sljfdlsufd sfds ier w sdfs lf sdfsdfds s sdfj jksdf sdfj sf s sdfdsjkf sf sdf sd.', 'Yes', 0, '2016-09-03 11:36:53', '2016-09-03 11:36:53'),
(21, 3, '2', 'Pineapple juice can', 'PJC254', '300 ml of Can', '1472883128.jpg', 4, 1, 'uoi sdnsdf sfhuewr sdfskjf dsf dsjfds fdsf ksdfsf sj sdfdsf sdfdsjf sdf sdjfds ff.', 'Yes', 0, '2016-09-03 11:42:08', '2016-09-03 11:42:08'),
(22, 3, '4', 'Carrots', 'CT45', '1 Kg', '1472883532.jpg', 2, 1, 'sd f tfg fh fghgf fgh fgtryrt rtyrt yrty  nvbnv fghf fgh fghgf.', 'Yes', 0, '2016-09-03 11:48:52', '2016-09-03 11:48:52'),
(23, 4, '4', 'Raw Banana', 'RB104', '6 Pc Amount', '1472888936.jpg', 3, 1.5, 'dfs sdf dfgf gf ksdhf sdfhsdfs fhds f dsf sdjkf dsf dsf dsjkf ds sdfs.', 'Yes', 0, '2016-09-03 13:18:56', '2016-09-03 13:18:56'),
(24, 1, '2,4', 'Soup', 'ABC0912', '6 case 45*85g', '1473229862.jpg', 1, 56, 'Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum .', 'Yes', 0, '2016-09-07 12:01:02', '2016-09-07 12:01:02'),
(25, 4, '6', 'Cumin Powder', 'CM008', '50 gm Pack', '1473334906.jpeg', 4, 1, 'sdf d fgd fg fh f fgh gfh fggfh m uy  fghgfhgf fgh g fghgfh gfh gfhgfhgfhgfhrt trhh tr htry tr cvb v nb vbnvbnbvn vggf.', 'Yes', 0, '2016-09-08 17:11:46', '2016-09-08 17:11:46'),
(26, 4, '1', 'Green Apple', 'GA900', '1 Kg', '1473335000.jpeg', 2, 5, 'dfg dfg fdg fdg fdg fdg fdg fdgdrte5r tdrgtert ret e5r dfg fd.', 'Yes', 0, '2016-09-08 17:13:20', '2016-09-08 17:13:20'),
(27, 4, '1', 'Orange', 'OR433', '1 kg', '1473335097.jpeg', 2, 4, 'sdf sf dsfs dsjhfjkl ljkdsf slkdfj dsf dsklfjsdkf slfjds fsdfjkdslf slkdfjdksl dslkfj dskfjdskfj dsklfjdsk jfds.', 'Yes', 0, '2016-09-08 17:14:57', '2016-09-08 17:14:57'),
(28, 4, '4', 'Cabbage', 'CB33', '1 Pc', '1473335210.jpeg', 3, 1, 'dfg fd gfdg fdg fdg dfg fdg fdgfdgfdg fdgfdg fdg fdg fdg fdgr dfdsgdf gfdg gdfg.', 'Yes', 0, '2016-09-08 17:16:50', '2016-09-08 17:16:50'),
(29, 4, '5', 'Rohu Fish', 'RF552', '1 Kg', '1473335307.jpeg', 2, 8, 'sdf sdf dfg fdg df fdgdfgfd fdg dfg fdg fdgfd gdfg d', 'Yes', 0, '2016-09-08 17:18:27', '2016-09-08 17:18:27'),
(30, 4, '5', 'Catla Fish', 'CT333', '1 Kg', '1473335401.jpg', 2, 9, 'sdff dsf dfg fdg fdg fdg fdgfd gfd gdfg dfgfdgfd. dfgfdg fdgfdretr.ert ret. etre re. ertre erer dfgtrdgre retre ret retre qwewqe bvnvnvb uytutyry.', 'Yes', 0, '2016-09-08 17:20:02', '2016-09-08 17:20:02'),
(31, 2, '4', 'Lady Finger Vegetable', 'LD', '1 Kg', '1473341564.jpg', 2, 1, 'df dfgd dg erewewr sdfsfdsf dsffdsf yrtytr uutyygghf mhnvbghf.', 'Yes', 0, '2016-09-08 19:02:44', '2016-09-08 19:02:44'),
(32, 4, '4', 'Snake Gourd', 'SG234', '1 Kg', '1473341677.jpg', 2, 1, 'df fdg fdgd df rere. kdfggf sdfds dsf dsf dgtgre dfhtry try  g hgjhgj ghjhgj vvbnbvn bvnbvn bvnvb nbvn bvnbvnbvnvn vb yhhrth fghgf hgfhf hg.', 'Yes', 0, '2016-09-08 19:04:37', '2016-09-08 19:04:37'),
(33, 2, '5', 'Khainga Fish', 'KH23', 'Per Kg', '1473859342.jpg', 2, 7, 'sdf dsf d dsfsf dgfhjg gfhgf hfgh gf', 'Yes', 0, '2016-09-14 18:52:22', '2016-09-14 18:52:22'),
(34, 2, '1', 'Guava', 'GV43', '1 kg', '1473859381.jpg', 2, 1, 'fds sf sf ds thrhy ryrty', 'Yes', 0, '2016-09-14 18:53:01', '2016-09-14 18:53:01'),
(35, 2, '4', 'Radish', 'RD3232', '2 pc', '1473859415.jpg', 3, 1, 'dsgf hfg jty gyjyg', 'Yes', 0, '2016-09-14 18:53:35', '2016-09-14 18:53:35'),
(36, 2, '4', 'Raw Jackfruit', 'RJC56', '1 kg', '1473859453.jpeg', 2, 1, 'cvbcvb fdgdfh', 'Yes', 0, '2016-09-14 18:54:13', '2016-09-14 18:54:13'),
(37, 2, '4', 'Tomato', 'TM 006', '1 Kg', '1479540331.jpeg', 2, 2, 'sdff sf sf dsf dsf dsf dsf dsf dsf dsf dsf dsf dsfdsfdsfds dsfdsf dsf dsfdsfdsf dsfdsfdsfds fds fdsfdsfdsf ds.', 'Yes', 0, '2016-11-19 12:55:31', '2016-11-19 12:55:31'),
(38, 1, '1', 'Ripe Papaya', 'PP21', '1 kg', '1479540510.jpeg', 2, 1.5, 'cfg fg dfgdf der ewt et rete gfd gfd cvx. dfgdfg df dfg dfd power yet sessdewrwr swe2w wfewrew erewr wrre w.', 'Yes', 0, '2016-11-19 12:58:30', '2016-11-19 12:59:11'),
(39, 2, '1', 'Lichi', 'LT098', '1 Kg', '1479540690.jpeg', 2, 2, 'dfg fdg fdgfdgydui adoiuyfreo sdfuyoidsf yosdf yuoisdf yuioe oiwer owqr.', 'Yes', 0, '2016-11-19 13:01:30', '2016-11-19 13:02:07'),
(40, 2, '4', 'Ridge Guard', 'RG23', '1 Kg', '1479540898.png', 2, 1, 'sdf sdftiwe sipo sdfjk ew sdfnsfsdfse nmnxcv sfesrwe qaads popisef hgsf dsxcvz jsdkfjkl.', 'Yes', 0, '2016-11-19 13:04:58', '2016-11-19 13:04:58'),
(41, 4, '1', 'Strawberries', 'STB222', '1 Kg', '1481017510.jpg', 2, 2, 'ddf dsfds fs', 'Yes', 0, '2016-12-06 15:15:11', '2016-12-06 15:15:11'),
(42, 4, '4', 'Lemons', 'LM11', '10 Pc', '1481030625.png', 3, 0.5, 'fdgfdg dfgfdg fdgytjt uikiuloiu ukuyy ytuytu tyu ytu yt', 'Yes', 0, '2016-12-06 18:53:46', '2016-12-06 18:53:46'),
(43, 5, '7', 'Sugar', 'SG99', '1 Kg', '1481031166.jpg', 2, 1, 'sdfs dsfdsfdsf dsf sdfds ds', 'Yes', 0, '2016-12-06 19:02:46', '2016-12-06 19:02:46'),
(44, 5, '7', 'Patanjali Atta', 'ATT12', '1 Kg', '1481031304.jpg', 2, 1, 'rwewer ewrew rewr ewr ew', 'Yes', 0, '2016-12-06 19:05:04', '2016-12-06 19:05:04'),
(45, 5, '7', 'India Gate Basmati Rice', 'IGR44', '1 Kg Pack', '1481031579.jpg', 4, 1, 'fdfgd gfd gfdg fdg fdg fdg fdg', 'Yes', 0, '2016-12-06 19:09:39', '2016-12-06 19:09:39'),
(46, 5, '7', 'Dabur Honey', 'DH124', '200 gm bottle', '1481031716.jpg', 4, 1, 'dfg fdg fdg fd gfdg fdg fdg fdg fdgfdgfdg dfg fdg fd fd gfdgfdgfdgfdgfd dfgfdgfd.', 'Yes', 0, '2016-12-06 19:11:56', '2016-12-06 19:11:56'),
(47, 5, '7', 'Kissan Tomato Ketchup', 'KTK44', '1 Kg Bottle', '1481031919.jpg', 3, 1, 'ert brh fghgfh fgjty uyiy iuyiuy uyiuy uyiuyi.', 'Yes', 0, '2016-12-06 19:15:19', '2016-12-06 19:15:19'),
(48, 5, '7', 'Noodles', 'ND123', '500 gm', '1481181727.jpg', 4, 1, 'ljoi sdifwer wesihwoe5u8 sdfsif dsofu sofsd fs fsoiuods uosdf dsfo usoufewjklwr j lewj lkj n vxvx vxvxcv cxvwerw r rewrewr.', 'Yes', 0, '2016-12-08 12:52:07', '2016-12-08 12:52:07'),
(49, 5, '7', 'Rajdhani Maida', 'RM43', '1 Kg Pack', '1481181937.png', 2, 1, 'sfsdfs ewrwr ewrewrew errety tyuyt uyiiui yuiuiou khuhkj vvbc xxxvcvx ds.', 'Yes', 0, '2016-12-08 12:55:37', '2016-12-08 12:55:37'),
(50, 5, '7', 'Freedom Sunflower Oil', 'FSO12', '1 Litre', '1481182234.jpg', 1, 2, 'dsfsf sdffre s erwer s sdgdsfsd sdfwe4rew siooo  nljlj  j,nkjlj  ..', 'Yes', 0, '2016-12-08 13:00:34', '2016-12-08 13:00:34'),
(51, 5, '7', 'Pattanjali Mustard Oil', 'PMT43', '1 Litre Pouch', '1481187014.jpg', 1, 2, 'Patanjlai sorisha tela bhari badhia. tsrewer sdfj mnloiu qwass zxcfrk tyuierhg bvnxm dsfkls.', 'Yes', 0, '2016-12-08 14:20:14', '2016-12-08 14:20:14'),
(52, 5, '6', 'Red Chilli', 'RC45', '100 gm Pack', '1481187470.jpg', 4, 1, 'dsfswe wrewrew ewrewrewr ewrrewrewfefsdfsf fdfdsd', 'No', 0, '2016-12-08 14:27:51', '2016-12-08 14:27:51'),
(53, 5, '7', 'Kewra Water', 'KW22', '100 ml bottle', '1481188807.jpg', 4, 1, 'dsfds fdsf dsfdsf dsfdsfjlkj uooew ewtretet retretwq zczvbcvxcv. dfdsfds fdsfewwerew ewrewrew trytty iyeeiedd mvbwff mewortu djf bjkhiewn bxvbsefh sfbek. cxvbmds fewrew mopip poiiu lkjh nmlbv xcvz qqsew, whree sfw I fohfnd that sfwe is betuful. sufhi my wief isa gdoo lkidfre pertne.', 'Yes', 0, '2016-12-08 14:50:07', '2016-12-08 14:50:07'),
(54, 5, '7', 'Amul Cow Ghee', 'ACG24', '500gm Pack', '1481193439.JPG', 4, 5, 'dsfdsfds dsjfkjo uiu ere uiohnk wewr qwesa poinm ytemm wernnk qaddcckl bmvzxc elhih. sdfbds qazxsw dsfds bmnb eddhkh sdfdsfds.', 'Yes', 0, '2016-12-08 16:07:19', '2016-12-08 16:07:19'),
(55, 5, '7', 'Amul Butter', 'ABT44', '500 gm Pack', '1481193648.jpg', 4, 5, 'fdg dfgfdg fdgfd fdrt yututy jhljhkjh iooiop qwewqqe cvxx vcc assd fggfhhgf jhlhjt wewer zxxzzx fghgfhgfh rutyoi ertoui dglfjssd cxvnbm sfjsldk ero xhdfsl k sdfhls df gtdfds.', 'Yes', 0, '2016-12-08 16:10:48', '2016-12-08 16:10:48'),
(56, 5, '7', 'Rajma', 'RJM44', '1 Kg pack', '1481194379.jpg', 2, 1.5, 'hkjsdfds fdhkjh erwwer wwrw asdasd poiph ljlhhh gbnmxvc ewfrw sadaf xczccc iytererw. xfsdfsd cbvnvc qeqwere sddsfdgdf asasada vcxxvnb erytrtry zxcvvcv asdfsf ghhjkhg yuoiuoyt ertre cvbvcb fdgdfgfd tyutyrty.', 'Yes', 0, '2016-12-08 16:22:59', '2016-12-08 16:22:59'),
(57, 2, '6', 'Ruchi Curry Powder', 'RCD33', '100 gm pack', '1481699529.jpg', 4, 1, 'dfgfd fdg fdg fdg fdg fd gfdg fdg fdg fdgfdgrdgre.', 'Yes', 0, '2016-12-14 12:42:09', '2016-12-14 12:42:09'),
(58, 1, '6', 'Bharat Curry powder', 'BCP', '100 gm Pack', '1481699583.jpeg', 4, 1, 'dfgdgdfgf dgd gfdg fdg fdgrer retrete etrertretre dfgdfgd adsadqw.', 'Yes', 0, '2016-12-14 12:43:03', '2016-12-14 12:43:03'),
(59, 1, '4', 'Cucumber', 'CCMB2', '1 Kg', '1481699807.jpg', 2, 1, 'dsfsfds fds fdsf ds fdsf dsdsfsfds fds fdsf ds fdsf dsdsfsfds fds fdsf ds fdsf dsdsfsfds fds fdsf ds fdsf dsdsfsfds fds fdsf ds fdsf dsdsfsfds fds fdsf ds fdsf dsdsfsfds fds fdsf ds fdsf dsdsfsfds fds fdsf ds fdsf dsdsfsfds fds fdsf ds fdsf dsdsfsfds fds fdsf ds fdsf dsdsfsfds fds fdsf ds fdsf dsdsfsfds fds fdsf ds fdsf dsdsfsfds fds fdsf ds fdsf dsdsfsfds fds fdsf ds fdsf dsdsfsfds fds fdsf ds fdsf ds.', 'Yes', 0, '2016-12-14 12:46:47', '2016-12-14 12:46:47'),
(60, 4, '4', 'Bitter Guard', 'BG23', '1 Kg', '1481699868.jpg', 2, 1, 'dfgdfg dfg fdg fdgtry ry tuyu yytu ytuytu yuyt ytuiuyi uyiuyjhkkjhk jhkjhkjhmn qeweq zcxczcz lkjkjhkjh adssada vcvcvbcbvc wqeeqweq gfddf.', 'Yes', 0, '2016-12-14 12:47:48', '2016-12-14 12:47:48'),
(61, 1, '5', 'Crab', 'CB44', '1 Kg', '1481700279.png', 2, 4, 'gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu sdfdsf.', 'Yes', 0, '2016-12-14 12:54:39', '2016-12-14 12:54:39'),
(62, 3, '5', 'Tiger prawn', 'TP24', '1 Kg', '1481700320.jpeg', 2, 8, 'sdfdsf sdf dsfdsfdsf gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu esfrrd.', 'Yes', 0, '2016-12-14 12:55:20', '2016-12-14 12:55:20'),
(63, 2, '1', 'Cherry Fruit', 'CF433', '100 gm pack', '1481700662.jpg', 4, 2, 'dssfgfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu dsffs.', 'Yes', 0, '2016-12-14 13:01:02', '2016-12-14 13:01:02'),
(64, 3, '1', 'Blue Berry Fruit', 'BBF678', '500 gm Pack', '1481700727.jpg', 4, 4, 'gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu yrtytry.', 'Yes', 0, '2016-12-14 13:02:07', '2016-12-14 13:02:07'),
(65, 4, '1', 'BlackBerries', 'BB441', '500 gm Pack', '1481700786.jpg', 4, 4, 'gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu ioip.', 'Yes', 0, '2016-12-14 13:03:06', '2016-12-14 13:03:06'),
(66, 3, '1', 'Coconut', 'CC11', '1 Pc', '1481706171.jpeg', 3, 0.5, 'df dsfdsfdsf gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu ew.', 'Yes', 0, '2016-12-14 14:32:51', '2016-12-14 14:32:51'),
(67, 4, '1,7', 'Cashew', 'CW121', '100 gm Packet', '1481706236.jpg', 4, 1, 'gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu gfdgfdg ertret qwqe asdsad zxcxzcz mbnmbv opuiiiu qwew.', 'Yes', 0, '2016-12-14 14:33:56', '2016-12-14 14:33:56'),
(68, 5, '5,1', 'Dry Grapes', 'DG44', '100 gm Pack', '1481706313.jpg', 4, 1, 'sdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqq.', 'Yes', 0, '2016-12-14 14:35:13', '2016-12-14 14:35:13'),
(69, 5, '1,7', 'Pista Badam', 'PB34', '100 gm Pack', '1481706403.jpg', 4, 1, 'sdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqq.', 'Yes', 0, '2016-12-14 14:36:43', '2016-12-14 14:36:43'),
(70, 1, '1,7', 'Kissan Jam', 'KJ44', '500 gm Pack', '1481706753.jpg', 4, 2, 'sdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqq.', 'Yes', 0, '2016-12-14 14:42:33', '2016-12-14 14:42:33'),
(71, 2, '1', 'Green Coconut', 'GP00', '1 Pc', '1481706944.png', 3, 0.5, 'sdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqq.', 'Yes', 0, '2016-12-14 14:45:44', '2016-12-14 14:45:44'),
(72, 5, '2', 'Mineral Water', 'MW12', '1 Litre Pack', '1481707089.jpg', 1, 0.5, 'sdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqqsdfdsf dsf dsfdsf dsfopo yreiut sdskfnsd lwerw zdcbcxv sdfsd sef weqq.', 'Yes', 0, '2016-12-14 14:48:09', '2016-12-14 14:48:09');

-- --------------------------------------------------------

--
-- Table structure for table `product_banners`
--

CREATE TABLE IF NOT EXISTS `product_banners` (
  `id` bigint(11) NOT NULL,
  `prd_id` bigint(20) NOT NULL,
  `banner_photo` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_banners`
--

INSERT INTO `product_banners` (`id`, `prd_id`, `banner_photo`, `created_date`, `updated_date`) VALUES
(2, 2, '1481796499.png', '2016-12-15 15:32:36', '2016-12-15 15:38:19');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE IF NOT EXISTS `suppliers` (
  `sid` bigint(20) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `supp_acc_number` varchar(255) NOT NULL,
  `address` longtext NOT NULL,
  `supplier_photo` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`sid`, `full_name`, `email`, `contact_number`, `supp_acc_number`, `address`, `supplier_photo`, `created_date`, `updated_date`) VALUES
(1, 'Suresh Kumar', 'sureshkumar02@gmail.com', '9861245555', 'ABCS3424', 'Plot No - 1242 P - 8', '1472216335.png', '2016-08-26 18:28:56', '2016-12-14 12:47:26'),
(2, 'Manoranjan Swain', 'ms@bletindia.com', '9878767788', 'DFH312312', 'Nayapalli,CRP', '1472216377.png', '2016-08-26 18:29:37', '2016-09-08 10:54:42'),
(3, 'Sukanata', 'sukanta@gmail.com', '6456456456', 'ABD34324DF', 'address', '1472821598.jpg', '2016-09-02 18:36:38', '2016-09-02 18:37:01'),
(4, 'Jitendra Kathua', 'jitendra.kathua@bletindia.com', '90908787', 'SUP84012356', 'At - My Village\r\nPO- Local Town\r\nOwn District', '1472888613.jpg', '2016-09-03 13:13:33', '2016-12-14 12:46:06'),
(5, 'Joel Croft', 'joelcroft658@gmail.com', '9878765477', '907856DC', 'Cuttack', '1481031098.png', '2016-12-06 19:01:38', '2016-12-14 12:45:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_temp`
--
ALTER TABLE `cart_temp`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chefs_registration`
--
ALTER TABLE `chefs_registration`
  ADD PRIMARY KEY (`chefs_id`);

--
-- Indexes for table `chef_token`
--
ALTER TABLE `chef_token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core`
--
ALTER TABLE `core`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `email_template`
--
ALTER TABLE `email_template`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_allocations`
--
ALTER TABLE `job_allocations`
  ADD PRIMARY KEY (`allo_id`);

--
-- Indexes for table `master_order`
--
ALTER TABLE `master_order`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `measurment_units`
--
ALTER TABLE `measurment_units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_banners`
--
ALTER TABLE `product_banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`sid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_temp`
--
ALTER TABLE `cart_temp`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `chefs_registration`
--
ALTER TABLE `chefs_registration`
  MODIFY `chefs_id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `chef_token`
--
ALTER TABLE `chef_token`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `core`
--
ALTER TABLE `core`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `email_template`
--
ALTER TABLE `email_template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `job_allocations`
--
ALTER TABLE `job_allocations`
  MODIFY `allo_id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `master_order`
--
ALTER TABLE `master_order`
  MODIFY `order_id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `measurment_units`
--
ALTER TABLE `measurment_units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` bigint(150) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=73;
--
-- AUTO_INCREMENT for table `product_banners`
--
ALTER TABLE `product_banners`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `sid` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
