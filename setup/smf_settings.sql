-- MySQL dump 10.16  Distrib 10.1.26-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: forum
-- ------------------------------------------------------
-- Server version	10.1.26-MariaDB-0+deb9u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `smf_settings`
--

DROP TABLE IF EXISTS `smf_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `smf_settings` (
  `variable` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `value` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`variable`(30))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `smf_settings`
--

LOCK TABLES `smf_settings` WRITE;
/*!40000 ALTER TABLE `smf_settings` DISABLE KEYS */;
INSERT INTO `smf_settings` VALUES ('smfVersion','2.0.12'),('news','SMF - Just Installed!'),('compactTopicPagesContiguous','5'),('compactTopicPagesEnable','1'),('enableStickyTopics','1'),('todayMod','1'),('karmaMode','0'),('karmaTimeRestrictAdmins','1'),('enablePreviousNext','0'),('pollMode','0'),('enableVBStyleLogin','1'),('enableCompressedOutput','1'),('karmaWaitTime','1'),('karmaMinPosts','0'),('karmaLabel','Karma:'),('karmaSmiteLabel','[smite]'),('karmaApplaudLabel','[applaud]'),('attachmentSizeLimit','128'),('attachmentPostLimit','192'),('attachmentNumPerPostLimit','4'),('attachmentDirSizeLimit','10240'),('attachmentUploadDir','/home/forums/online/forum/attachments'),('attachmentExtensions','doc,gif,jpg,mpg,pdf,png,txt,zip'),('attachmentCheckExtensions','0'),('attachmentShowImages','1'),('attachmentEnable','0'),('attachmentEncryptFilenames','1'),('attachmentThumbnails','1'),('attachmentThumbWidth','150'),('attachmentThumbHeight','150'),('censorIgnoreCase','1'),('mostOnline','11'),('mostOnlineToday','1'),('mostDate','1480484886'),('allow_disableAnnounce','1'),('trackStats','1'),('userLanguage','1'),('titlesEnable','0'),('topicSummaryPosts','15'),('enableErrorLogging','1'),('max_image_width','0'),('max_image_height','0'),('onlineEnable','0'),('cal_enabled','0'),('cal_maxyear','2020'),('cal_minyear','2008'),('cal_daysaslink','0'),('cal_defaultboard',''),('cal_showholidays','1'),('cal_showbdays','1'),('cal_showevents','1'),('cal_showweeknum','0'),('cal_maxspan','7'),('smtp_host',''),('smtp_port','25'),('smtp_username',''),('smtp_password',''),('mail_type','0'),('timeLoadPageEnable','0'),('totalMembers','27'),('totalTopics','44'),('totalMessages','114'),('simpleSearch','0'),('censor_vulgar',''),('censor_proper',''),('enablePostHTML','0'),('theme_allow','0'),('theme_default','1'),('theme_guests','3'),('enableEmbeddedFlash','0'),('xmlnews_enable','1'),('xmlnews_maxlen','255'),('hotTopicPosts','15'),('hotTopicVeryPosts','25'),('registration_method','3'),('send_validation_onChange','0'),('send_welcomeEmail','0'),('allow_editDisplayName','0'),('allow_hideOnline','1'),('guest_hideContacts','1'),('spamWaitTime','5'),('pm_spam_settings','10,5,20'),('reserveWord','0'),('reserveCase','1'),('reserveUser','1'),('reserveName','1'),('reserveNames','Admin\nWebmaster\nGuest\nroot'),('autoLinkUrls','1'),('banLastUpdated','1480183540'),('smileys_dir','/home/forums/online/forum/Smileys'),('smileys_url','https://staging.wetfishonline.com/forum/Smileys'),('avatar_directory','/home/forums/online/forum/avatars'),('avatar_url','https://staging.wetfishonline.com/forum/avatars'),('avatar_max_height_external','128'),('avatar_max_width_external','128'),('avatar_action_too_large','option_html_resize'),('avatar_max_height_upload','128'),('avatar_max_width_upload','128'),('avatar_resize_upload','1'),('avatar_download_png','1'),('failed_login_threshold','9'),('oldTopicDays','0'),('edit_wait_time','90'),('edit_disable_time','0'),('autoFixDatabase','1'),('allow_guestAccess','1'),('time_format','%B %d, %Y, %I:%M:%S %p'),('number_format','1234.00'),('enableBBC','1'),('max_messageLength','20000'),('signature_settings','0,300,0,0,0,0,0,0:abbr,acronym,anchor,b,bdo,black,blue,br,center,code,code,color,email,email,flash,font,ftp,ftp,glow,green,html,hr,i,img,img,iurl,iurl,left,li,list,list,ltr,me,move,nobbc,php,pre,quote,quote,quote,quote,quote,red,right,rtl,s,shadow,size,size,sub,sup,table,td,time,tr,tt,u,url,url,white'),('autoOptMaxOnline','0'),('defaultMaxMessages','5'),('defaultMaxTopics','20'),('defaultMaxMembers','30'),('enableParticipation','0'),('recycle_enable','0'),('recycle_board','0'),('maxMsgID','150'),('enableAllMessages','0'),('fixLongWords','0'),('knownThemes','3'),('who_enabled','0'),('time_offset','0'),('cookieTime','60'),('lastActive','15'),('smiley_sets_known','default,aaron,akyhne'),('smiley_sets_names','Alienine\'s Set\nAaron\'s Set\nAkyhne\'s Set'),('smiley_sets_default','default'),('cal_days_for_index','7'),('requireAgreement','1'),('unapprovedMembers','0'),('default_personal_text',''),('package_make_backups','1'),('databaseSession_enable','1'),('databaseSession_loose','1'),('databaseSession_lifetime','2880'),('search_cache_size','50'),('search_results_per_page','30'),('search_weight_frequency','30'),('search_weight_age','25'),('search_weight_length','20'),('search_weight_subject','15'),('search_weight_first_message','10'),('search_max_results','1200'),('search_floodcontrol_time','5'),('permission_enable_deny','0'),('permission_enable_postgroups','0'),('mail_next_send','0'),('mail_recent','0000000000|0'),('settings_updated','1541124346'),('next_task_time','1585731600'),('warning_settings','0,20,0'),('search_pointer','2'),('admin_features','cp'),('last_mod_report_action','1481393142'),('pruningOptions','30,180,180,180,30,0'),('cache_enable','0'),('reg_verification','0'),('visual_verification_type','0'),('enable_buddylist','0'),('birthday_email','happy_birthday'),('dont_repeat_theme_core','1'),('dont_repeat_smileys_20','1'),('dont_repeat_buddylists','1'),('attachment_image_reencode','1'),('attachment_image_paranoid','0'),('attachment_thumb_png','1'),('avatar_reencode','1'),('avatar_paranoid','0'),('default_timezone','America/Vancouver'),('memberlist_updated','1546661231'),('latestMember','40'),('latestRealName','testcache'),('rand_seed','927006344'),('mostOnlineUpdated','2020-04-01'),('currentAttachmentUploadDir','0'),('calendar_updated','1541192350'),('disable_wysiwyg','1'),('disabledBBC','abbr,acronym,anchor,bdo,black,blue,br,color,email,email,flash,font,ftp,ftp,glow,green,html,iurl,iurl,li,ltr,me,move,nobbc,php,pre,red,rtl,shadow,size,size,sub,sup,time,tt,white'),('spider_name_cache','a:19:{i:1;s:6:\"Google\";i:2;s:6:\"Yahoo!\";i:3;s:3:\"MSN\";i:4;s:15:\"Google (Mobile)\";i:5;s:14:\"Google (Image)\";i:6;s:16:\"Google (AdSense)\";i:7;s:16:\"Google (Adwords)\";i:8;s:15:\"Yahoo! (Mobile)\";i:9;s:14:\"Yahoo! (Image)\";i:10;s:12:\"MSN (Mobile)\";i:11;s:11:\"MSN (Media)\";i:12;s:4:\"Cuil\";i:13;s:3:\"Ask\";i:14;s:5:\"Baidu\";i:15;s:9:\"Gigablast\";i:16;s:15:\"InternetArchive\";i:17;s:5:\"Alexa\";i:18;s:6:\"Omgili\";i:19;s:9:\"EntireWeb\";}'),('disabled_profile_fields','icq,msn,aim,yim,location,gender,warning_status'),('enableErrorQueryLogging','1'),('custom_avatar_url',''),('custom_avatar_dir',''),('queryless_urls','0'),('notify_new_registration','0'),('global_character_set','UTF-8'),('previousCharacterSet','utf8');
/*!40000 ALTER TABLE `smf_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `smf_membergroups`
--

DROP TABLE IF EXISTS `smf_membergroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `smf_membergroups` (
  `id_group` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(80) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `description` text CHARACTER SET utf8 NOT NULL,
  `online_color` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `min_posts` mediumint(9) NOT NULL DEFAULT '-1',
  `max_messages` smallint(5) unsigned NOT NULL DEFAULT '0',
  `stars` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `group_type` tinyint(3) NOT NULL DEFAULT '0',
  `hidden` tinyint(3) NOT NULL DEFAULT '0',
  `id_parent` smallint(5) NOT NULL DEFAULT '-2',
  PRIMARY KEY (`id_group`),
  KEY `min_posts` (`min_posts`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `smf_membergroups`
--

LOCK TABLES `smf_membergroups` WRITE;
/*!40000 ALTER TABLE `smf_membergroups` DISABLE KEYS */;
INSERT INTO `smf_membergroups` VALUES (1,'Administrator','','#FF0000',-1,0,'',0,0,-2),(5,'Moderator','','',-1,0,'',0,0,-2),(3,'Individual Moderator (unused)','','',-1,0,'',0,0,-2),(4,'Member','','',0,0,'',0,0,0),(6,'NPC','','',-1,0,'',0,0,-2);
/*!40000 ALTER TABLE `smf_membergroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `smf_permissions`
--

DROP TABLE IF EXISTS `smf_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `smf_permissions` (
  `id_group` smallint(5) NOT NULL DEFAULT '0',
  `permission` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `add_deny` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_group`,`permission`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `smf_permissions`
--

LOCK TABLES `smf_permissions` WRITE;
/*!40000 ALTER TABLE `smf_permissions` DISABLE KEYS */;
INSERT INTO `smf_permissions` VALUES (-1,'calendar_view',1),(4,'search_posts',1),(0,'profile_extra_own',1),(0,'profile_identity_own',1),(5,'profile_extra_own',1),(0,'pm_send',1),(0,'pm_read',1),(5,'profile_identity_own',1),(4,'profile_view_own',1),(5,'manage_bans',1),(5,'pm_send',1),(4,'profile_identity_own',1),(5,'pm_read',1),(5,'profile_view_any',1),(5,'profile_view_own',1),(4,'profile_extra_own',1),(5,'calendar_view',1),(4,'pm_read',1),(5,'search_posts',1),(0,'profile_view_any',1),(0,'profile_view_own',1),(4,'pm_send',1),(4,'calendar_view',1),(0,'calendar_view',1),(0,'search_posts',1),(4,'profile_view_any',1),(6,'profile_identity_own',1),(6,'pm_send',1),(6,'pm_read',1),(6,'create_npc_shop',1),(6,'profile_view_any',1),(6,'profile_view_own',1),(6,'calendar_view',1),(6,'search_posts',1),(6,'profile_extra_own',1);
/*!40000 ALTER TABLE `smf_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `smf_permission_profiles`
--

DROP TABLE IF EXISTS `smf_permission_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `smf_permission_profiles` (
  `id_profile` smallint(5) NOT NULL AUTO_INCREMENT,
  `profile_name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`id_profile`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `smf_permission_profiles`
--

LOCK TABLES `smf_permission_profiles` WRITE;
/*!40000 ALTER TABLE `smf_permission_profiles` DISABLE KEYS */;
INSERT INTO `smf_permission_profiles` VALUES (1,'default'),(2,'no_polls'),(3,'reply_only'),(4,'read_only'),(5,'Disable Moderators');
/*!40000 ALTER TABLE `smf_permission_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `smf_smileys`
--

DROP TABLE IF EXISTS `smf_smileys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `smf_smileys` (
  `id_smiley` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `filename` varchar(48) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `description` varchar(80) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `smiley_row` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `smiley_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` tinyint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_smiley`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `smf_smileys`
--

LOCK TABLES `smf_smileys` WRITE;
/*!40000 ALTER TABLE `smf_smileys` DISABLE KEYS */;
INSERT INTO `smf_smileys` VALUES (1,':)','smiley.gif','Smiley',0,0,0),(2,';)','wink.gif','Wink',0,1,0),(3,':D','cheesy.gif','Cheesy',0,2,0),(4,';D','grin.gif','Grin',0,3,0),(5,'>:(','angry.gif','Angry',0,4,0),(6,':(','sad.gif','Sad',0,5,0),(7,':o','shocked.gif','Shocked',0,6,0),(8,'8)','cool.gif','Cool',0,7,0),(9,'???','huh.gif','Huh?',0,8,0),(10,'::)','rolleyes.gif','Roll Eyes',0,9,0),(11,':P','tongue.gif','Tongue',0,10,0),(12,':-[','embarrassed.gif','Embarrassed',0,11,0),(13,':-X','lipsrsealed.gif','Lips Sealed',0,12,0),(14,':-\\','undecided.gif','Undecided',0,13,0),(15,':-*','kiss.gif','Kiss',0,14,0),(16,':\'(','cry.gif','Cry',0,15,0),(17,'>:D','evil.gif','Evil',0,16,1),(18,'^-^','azn.gif','Azn',0,17,1),(19,'O0','afro.gif','Afro',0,18,1),(20,':))','laugh.gif','Laugh',0,19,1),(21,'C:-)','police.gif','Police',0,20,1),(22,'O:-)','angel.gif','Angel',0,21,1);
/*!40000 ALTER TABLE `smf_smileys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `smf_themes`
--

DROP TABLE IF EXISTS `smf_themes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `smf_themes` (
  `id_member` mediumint(8) NOT NULL DEFAULT '0',
  `id_theme` tinyint(4) unsigned NOT NULL DEFAULT '1',
  `variable` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `value` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id_theme`,`id_member`,`variable`(30)),
  KEY `id_member` (`id_member`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `smf_themes`
--

LOCK TABLES `smf_themes` WRITE;
/*!40000 ALTER TABLE `smf_themes` DISABLE KEYS */;
INSERT INTO `smf_themes` VALUES (0,1,'name','SMF Default Theme - Curve'),(0,1,'theme_url','https://staging.wetfishonline.com/forum/Themes/default'),(0,1,'images_url','https://staging.wetfishonline.com/forum/Themes/default/images'),(0,1,'theme_dir','/home/forums/online/forum/Themes/default'),(0,1,'show_bbc','1'),(0,1,'show_latest_member','0'),(0,1,'show_modify','1'),(0,1,'show_user_images','1'),(0,1,'show_blurb','0'),(0,1,'show_gender','0'),(0,1,'show_newsfader','0'),(0,1,'number_recent_posts','0'),(0,1,'show_member_bar','1'),(0,1,'linktree_link','1'),(0,1,'show_profile_buttons','0'),(0,1,'show_mark_read','1'),(0,1,'show_stats_index','0'),(0,1,'linktree_inline','0'),(0,1,'show_board_desc','1'),(0,1,'newsfader_time','5000'),(0,1,'allow_no_censored','0'),(0,1,'additional_options_collapsable','0'),(0,1,'use_image_buttons','1'),(0,1,'enable_news','0'),(0,1,'forum_width','90%'),(0,2,'name','Core Theme'),(0,2,'theme_url','https://staging.wetfishonline.com/forum/Themes/core'),(0,2,'images_url','https://staging.wetfishonline.com/forum/Themes/core/images'),(0,2,'theme_dir','/home/forums/online/forum/Themes/core'),(-1,1,'display_quick_reply','1'),(-1,1,'posts_apply_ignore_list','1'),(0,3,'theme_layers','html,body'),(0,3,'theme_url','https://staging.wetfishonline.com/forum/Themes/Redsy'),(0,1,'header_logo_url',''),(0,1,'site_slogan',''),(0,1,'smiley_sets_default','none'),(0,1,'show_group_key','0'),(0,1,'display_who_viewing','0'),(0,1,'hide_post_group','0'),(0,3,'theme_dir','/home/forums/online/forum/Themes/Redsy'),(0,3,'name','Redsy'),(-1,1,'wysiwyg_default','0'),(-1,1,'show_board_desc','0'),(-1,1,'show_children','0'),(-1,1,'use_sidebar_menu','0'),(-1,1,'show_no_avatars','0'),(-1,1,'show_no_signatures','0'),(-1,1,'show_no_censored','0'),(-1,1,'return_to_post','1'),(-1,1,'no_new_reply_warning','0'),(-1,1,'view_newest_first','0'),(-1,1,'view_newest_pm_first','0'),(-1,1,'popup_messages','0'),(-1,1,'copy_to_outbox','1'),(-1,1,'pm_remove_inbox_label','0'),(-1,1,'auto_notify','0'),(-1,1,'topics_per_page','0'),(-1,1,'messages_per_page','0'),(-1,1,'display_quick_mod','0'),(0,3,'images_url','https://staging.wetfishonline.com/forum/Themes/Redsy/images'),(0,3,'theme_templates','index'),(1,3,'collapse_header','0'),(2,3,'collapse_header','0'),(0,3,'header_logo_url',''),(0,3,'redsy_copyright',''),(0,3,'redsy_navbar','0'),(0,3,'redsy_navbar_height',''),(0,3,'forum_width','90%'),(0,3,'smiley_sets_default','none'),(0,3,'facebook_check','0'),(0,3,'facebook_text',''),(0,3,'twitter_check','0'),(0,3,'twitter_text',''),(0,3,'youtube_check','0'),(0,3,'youtube_text',''),(0,3,'rss_check','0'),(0,3,'rss_text',''),(0,3,'linktree_link','1'),(0,3,'show_mark_read','1'),(0,3,'allow_no_censored','0'),(0,3,'show_newsfader','0'),(0,3,'newsfader_time','5000'),(0,3,'number_recent_posts','0'),(0,3,'show_stats_index','0'),(0,3,'show_latest_member','0'),(0,3,'show_group_key','0'),(0,3,'display_who_viewing','0'),(0,3,'show_modify','1'),(0,3,'show_profile_buttons','0'),(0,3,'show_user_images','1'),(0,3,'show_blurb','0'),(0,3,'show_gender','0'),(0,3,'hide_post_group','1'),(0,3,'show_bbc','1'),(0,3,'additional_options_collapsable','0'),(1,3,'collapse_header_ic','0'),(3,3,'collapse_header','0'),(1,1,'show_board_desc','0'),(1,1,'show_children','0'),(1,1,'use_sidebar_menu','0'),(1,1,'show_no_avatars','0'),(1,1,'show_no_signatures','0'),(1,1,'return_to_post','1'),(1,1,'no_new_reply_warning','0'),(1,1,'view_newest_first','0'),(1,1,'topics_per_page','0'),(1,1,'messages_per_page','0'),(1,1,'display_quick_reply','1'),(1,1,'display_quick_mod','0'),(1,1,'auto_notify','0'),(15,1,'display_quick_reply','1'),(17,1,'display_quick_reply','1'),(19,1,'display_quick_reply','1'),(19,3,'collapse_header','1'),(18,3,'collapse_header','0'),(21,1,'display_quick_reply','1'),(23,1,'display_quick_reply','1'),(1,1,'admin_preferences','a:3:{s:2:\"pv\";s:7:\"classic\";s:3:\"app\";s:1:\"0\";s:2:\"sb\";s:8:\"internal\";}'),(1,1,'collapse_header','0'),(24,1,'display_quick_reply','1'),(25,1,'display_quick_reply','1'),(26,1,'display_quick_reply','1'),(27,1,'display_quick_reply','1'),(28,1,'display_quick_reply','1'),(30,1,'display_quick_reply','1'),(31,1,'display_quick_reply','1'),(32,1,'display_quick_reply','1'),(33,1,'display_quick_reply','1'),(34,1,'display_quick_reply','1'),(35,1,'display_quick_reply','1'),(36,1,'display_quick_reply','1'),(37,1,'display_quick_reply','1'),(38,1,'display_quick_reply','1'),(40,1,'display_quick_reply','1'),(39,1,'display_quick_reply','1');
/*!40000 ALTER TABLE `smf_themes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-04-01  8:15:07
