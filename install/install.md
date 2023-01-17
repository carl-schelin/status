### Installation

You'll need to create the statususer account for access to the status management database.

create user 'statususer'@'localhost' identified by 'password';

Then give the user access:

grant privilege on status.* to 'statususer'@'localhost';

Then finish:

flush privileges;

### Create database

create database status;

### Add tables

This is the set of mysql tables for the status management app.

CREATE TABLE `st_bandf` (
  `bf_id` int(10) NOT NULL AUTO_INCREMENT,
  `bf_name` int(10) NOT NULL DEFAULT '0',
  `bf_borf` int(10) NOT NULL DEFAULT '0',
  `bf_week` int(10) NOT NULL DEFAULT '0',
  `bf_text` text NOT NULL,
  `bf_dev` int(10) NOT NULL DEFAULT '0',
  `bf_status` int(10) NOT NULL,
  PRIMARY KEY (`bf_id`)
); 

CREATE TABLE `st_cande` (
  `ce_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ce_name` int(10) unsigned NOT NULL DEFAULT '0',
  `ce_week` int(10) unsigned NOT NULL DEFAULT '0',
  `ce_text` text,
  PRIMARY KEY (`ce_id`)
); 

CREATE TABLE `st_class` (
  `cls_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cls_name` char(70) DEFAULT NULL,
  `cls_template` int(10) NOT NULL DEFAULT '0',
  `cls_project` int(10) unsigned DEFAULT '1',
  `cls_title` char(100) NOT NULL DEFAULT '',
  `cls_help` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`cls_id`)
); 

create table st_epics (
  epic_id int(10) not null auto_increment,
  epic_jira char(60) not null default '',
  epic_title char(255) not null default '',
  epic_user int(10) not null default 0,
  epic_closed int(10) not null default 0,
  primary key (epic_id)
);

CREATE TABLE `st_events` (
  `evt_id` int(10) NOT NULL AUTO_INCREMENT,
  `evt_group` int(10) NOT NULL DEFAULT '0',
  `evt_task` char(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`evt_id`)
); 

CREATE TABLE `st_grouplist` (
  `gpl_id` int(10) NOT NULL AUTO_INCREMENT,
  `gpl_group` int(10) NOT NULL DEFAULT '0',
  `gpl_user` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gpl_id`)
); 

CREATE TABLE `st_groups` (
  `grp_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `grp_name` char(70) NOT NULL DEFAULT '',
  `grp_email` char(100) NOT NULL,
  `grp_day` int(10) NOT NULL DEFAULT '5',
  `grp_manager` int(10) NOT NULL DEFAULT '0',
  `grp_report` int(10) NOT NULL DEFAULT '0',
  `grp_members` char(254) NOT NULL DEFAULT '',
  `grp_disabled` int(10) NOT NULL DEFAULT '0',
  `grp_changedby` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`grp_id`)
); 

CREATE TABLE `st_levels` (
  `lvl_id` int(8) NOT NULL AUTO_INCREMENT,
  `lvl_name` varchar(255) NOT NULL,
  `lvl_level` int(1) NOT NULL,
  `lvl_disabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lvl_id`)
); 

CREATE TABLE `st_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_user` char(30) NOT NULL DEFAULT '',
  `log_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `log_source` char(30) NOT NULL DEFAULT '',
  `log_detail` char(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`log_id`)
); 

CREATE TABLE `st_polls` (
  `poll_id` int(10) NOT NULL AUTO_INCREMENT,
  `poll_pid` int(10) NOT NULL DEFAULT '0',
  `poll_desc` char(100) NOT NULL,
  `poll_question` char(100) NOT NULL,
  `poll_users` longtext NOT NULL,
  `poll_options` int(10) NOT NULL DEFAULT '0',
  `poll_selects` int(10) NOT NULL DEFAULT '0',
  `poll_type` int(10) NOT NULL DEFAULT '0',
  `poll_owner` int(10) NOT NULL DEFAULT '0',
  `poll_days` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`poll_id`)
); 

CREATE TABLE `st_progress` (
  `pro_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pro_name` char(70) NOT NULL DEFAULT '',
  `pro_desc` char(70) NOT NULL DEFAULT '',
  PRIMARY KEY (`pro_id`)
); 

CREATE TABLE `st_project` (
  `prj_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prj_name` char(30) NOT NULL DEFAULT '',
  `prj_code` int(10) unsigned NOT NULL DEFAULT '0',
  `prj_task` char(30) NOT NULL DEFAULT '',
  `prj_desc` char(100) NOT NULL,
  `prj_group` int(10) unsigned NOT NULL DEFAULT '1',
  `prj_close` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`prj_id`)
); 

CREATE TABLE `st_ras` (
  `ras_id` int(10) NOT NULL AUTO_INCREMENT,
  `ras_name` char(100) NOT NULL,
  `ras_code` int(10) NOT NULL DEFAULT '0',
  `ras_link` char(255) NOT NULL DEFAULT '',
  `ras_status` char(255) NOT NULL DEFAULT '',
  `ras_manager` char(100) NOT NULL,
  `ras_resource` int(10) NOT NULL DEFAULT '0',
  `ras_group` int(10) NOT NULL DEFAULT '0',
  `ras_jan` int(10) NOT NULL DEFAULT '0',
  `ras_feb` int(10) NOT NULL DEFAULT '0',
  `ras_mar` int(10) NOT NULL DEFAULT '0',
  `ras_apr` int(10) NOT NULL DEFAULT '0',
  `ras_may` int(10) NOT NULL DEFAULT '0',
  `ras_jun` int(10) NOT NULL DEFAULT '0',
  `ras_jul` int(10) NOT NULL DEFAULT '0',
  `ras_aug` int(10) NOT NULL DEFAULT '0',
  `ras_sep` int(10) NOT NULL DEFAULT '0',
  `ras_oct` int(10) NOT NULL DEFAULT '0',
  `ras_nov` int(10) NOT NULL DEFAULT '0',
  `ras_dec` int(10) NOT NULL DEFAULT '0',
  `ras_closed` int(10) NOT NULL DEFAULT '0',
  `ras_priority` int(10) NOT NULL DEFAULT '0',
  `ras_check` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ras_id`)
); 

CREATE TABLE `st_report` (
  `rep_id` int(10) NOT NULL AUTO_INCREMENT,
  `rep_user` int(10) NOT NULL DEFAULT '0',
  `rep_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rep_group` int(10) NOT NULL DEFAULT '0',
  `rep_status` int(10) NOT NULL DEFAULT '0',
  `rep_task` text NOT NULL,
  PRIMARY KEY (`rep_id`)
); 

CREATE TABLE `st_status` (
  `strp_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `strp_week` int(10) unsigned NOT NULL DEFAULT '0',
  `strp_name` int(10) unsigned NOT NULL DEFAULT '0',
  `strp_jira` int(10) NOT NULL DEFAULT '0',
  `strp_class` int(10) unsigned NOT NULL DEFAULT '0',
  `strp_type` int(10) unsigned NOT NULL DEFAULT '0',
  `strp_progress` int(10) unsigned NOT NULL,
  `strp_project` int(10) unsigned NOT NULL DEFAULT '0',
  `strp_day` int(10) unsigned NOT NULL DEFAULT '0',
  `strp_time` int(10) unsigned NOT NULL DEFAULT '0',
  `strp_task` char(255) NOT NULL DEFAULT '',
  `strp_save` int(10) unsigned NOT NULL DEFAULT '1',
  `strp_quarter` int(10) unsigned NOT NULL DEFAULT '0',
  `strp_yearmon` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`strp_id`)
); 

CREATE TABLE `st_themes` (
  `theme_id` int(10) NOT NULL AUTO_INCREMENT,
  `theme_name` char(40) NOT NULL DEFAULT '',
  `theme_title` char(40) NOT NULL DEFAULT '',
  `theme_disabled` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`theme_id`)
); 

CREATE TABLE `st_titles` (
  `tit_id` int(10) NOT NULL AUTO_INCREMENT,
  `tit_name` char(60) NOT NULL DEFAULT '',
  `tit_level` int(10) NOT NULL DEFAULT '0',
  `tit_order` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tit_id`)
); 

CREATE TABLE `st_todo` (
  `todo_id` int(10) NOT NULL AUTO_INCREMENT,
  `todo_name` char(255) NOT NULL,
  `todo_class` int(10) NOT NULL DEFAULT '0',
  `todo_project` int(10) NOT NULL DEFAULT '0',
  `todo_group` int(10) NOT NULL DEFAULT '0',
  `todo_save` int(10) NOT NULL DEFAULT '0',
  `todo_entered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `todo_due` int(10) NOT NULL DEFAULT '0',
  `todo_day` int(10) NOT NULL DEFAULT '0',
  `todo_time` int(10) NOT NULL DEFAULT '0',
  `todo_completed` int(10) NOT NULL DEFAULT '0',
  `todo_user` int(10) NOT NULL,
  `todo_priority` int(10) NOT NULL DEFAULT '0',
  `todo_status` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`todo_id`)
); 

CREATE TABLE `st_type` (
  `typ_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `typ_name` char(70) NOT NULL DEFAULT '',
  `typ_desc` char(70) NOT NULL DEFAULT '',
  PRIMARY KEY (`typ_id`)
); 

CREATE TABLE `st_users` (
  `usr_id` int(8) NOT NULL AUTO_INCREMENT,
  `usr_level` int(1) NOT NULL DEFAULT '2',
  `usr_disabled` int(1) NOT NULL DEFAULT '0',
  `usr_name` varchar(20) NOT NULL,
  `usr_first` varchar(255) NOT NULL,
  `usr_last` varchar(255) NOT NULL,
  `usr_email` varchar(255) NOT NULL,
  `usr_passwd` varchar(32) NOT NULL,
  `usr_reset` int(10) NOT NULL DEFAULT '0',
  `usr_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usr_group` int(1) unsigned NOT NULL DEFAULT '0',
  `usr_theme` int(10) NOT NULL DEFAULT '7',
  `usr_maillist` int(1) NOT NULL DEFAULT '0',
  `usr_cande` int(10) unsigned NOT NULL DEFAULT '0',
  `usr_manager` int(1) unsigned NOT NULL DEFAULT '0',
  `usr_template` int(10) unsigned NOT NULL DEFAULT '3',
  `usr_supervisor` int(1) NOT NULL DEFAULT '0',
  `usr_director` int(1) NOT NULL DEFAULT '0',
  `usr_vicepresident` int(10) NOT NULL DEFAULT '0',
  `usr_projects` char(255) NOT NULL DEFAULT '',
  `usr_report` int(10) NOT NULL DEFAULT '0',
  `usr_confirm` int(10) NOT NULL DEFAULT '0',
  `usr_title` int(10) NOT NULL DEFAULT '0',
  `usr_phone` char(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`usr_id`)
); 

create table st_userstories (
  user_id int(10) not null auto_increment,
  user_epic int(10) not null default 0,
  user_jira char(60) not null default '',
  user_task char(255) not null default '',
  user_user int(10) not null default 0,
  user_closed int(10) not null default 0,
  primary key (user_id)
);

CREATE TABLE `st_weeks` (
  `wk_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wk_date` date NOT NULL,
  PRIMARY KEY (`wk_id`)
); 


### Need to set up the initial levels.

As the variables are used within the code, all entries need to be added

insert into st_levels set lvl_id = null,lvl_name = 'Developer', lvl_level = 1;
insert into st_levels set lvl_id = null,lvl_name = 'Admin', lvl_level = 2;
insert into st_levels set lvl_id = null,lvl_name = 'Vice President', lvl_level = 3;
insert into st_levels set lvl_id = null,lvl_name = 'Director', lvl_level = 4;
insert into st_levels set lvl_id = null,lvl_name = 'Manager', lvl_level = 5;
insert into st_levels set lvl_id = null,lvl_name = 'Supervisor', lvl_level = 6;
insert into st_levels set lvl_id = null,lvl_name = 'Report', lvl_level = 7;
insert into st_levels set lvl_id = null,lvl_name = 'Guest', lvl_level = 8;


### Initial User

Need to create the administrator account.

insert into st_users set usr_id = null,usr_level = 2, usr_name = 'admin', usr_first = 'Administrator', usr_last = 'Status';


### Weeks Update

Also need to update the st_weeks table with the dates for the last friday of each week.

See the additional file, weeks.update for 2023.

Yes, I know. Pain in the ass. That's how it goes for now. I've been working on a different app so this'll have to do for now.


### Settings File

The settings.php file needs to be updated to reflect your hostname and to add the credentials for accessing mysql you created at the first step.


### CSS files

I'm using jQuery so you'll need to install the following files for this to work correctly.




