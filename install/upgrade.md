### Upgrade docs

If you already have the status management app, you'll need to make the following changes.

### Jira Module

For some reason, the epic and userstories tables didn't make it into the database although they are in the code. This fixes that.

alter table status add column strp_jira int(10) not null default 0 after strp_name;

create table epics (
  epic_id int(10) not null auto_increment,
  epic_jira char(60) not null default '',
  epic_title char(255) not null default '',
  epic_user int(10) not null default 0,
  epic_closed int(10) not null default 0,
  primary key (epic_id)
);

create table userstories (
  user_id int(10) not null auto_increment,
  user_epic int(10) not null default 0,
  user_jira char(60) not null default '',
  user_task char(255) not null default '',
  user_user int(10) not null default 0,
  user_closed int(10) not null default 0,
  primary key (user_id)
);

### Table Rename

Time to rename the tables in the status management app. Unfortunately mysql updates have caused problems. This is fixed by renaming the tables to add a prefix of st_

rename table bandf to st_bandf;
rename table cande to st_cande;
rename table class to st_class;
rename table epics to st_epics;
rename table events to st_events;
rename table grouplist to st_grouplist;
rename table groups to st_groups;
rename table levels to st_levels;
rename table log to st_log;
rename table polls to st_polls;
rename table progress to st_progress;
rename table project to st_project;
rename table ras to st_ras;
rename table report to st_report;
rename table status to st_status;
rename table themes to st_themes;
rename table titles to st_titles;
rename table todo to st_todo;
rename table type to st_type;
rename table users to st_users;
rename table userstories to st_userstories;
rename table weeks to st_weeks;

