<?php

# add a space at the end as the company will be inserted into strings.
$Sitecompany		= '[company name ]';
# by default, disable debugging
$Sitedebug              = 'YES';

# Set the environment here so other places in the code can be tested without changing code.
$hostname = php_uname('n');

#############################################################
# Production servers
#############################################################

  $Siteenv              = "PROD";
  $Sitedebug		= 'YES';
  $Sitedebug		= 'ALL';
  $Sitedebug		= 'NO';

# Set site specific variables
  $Sitehttp             = "[website]";
  $Siteurl              = "http://" . $Sitehttp;

# Header graphic
  $Siteheader           = "titlegraphic.gif";

# Path details
  $Sitedir              = "/var/www/html/status";
  $Siteinstall          = "";

# Who to contact
  $Siteadmins           = ",[email]";
  $Sitedev              = "[email]";
  $EmergencyContact     = "[email]";

# MySQL specific settings
  $DBtype               = "mysql";
  $DBserver             = "localhost";
  $DBname               = "status";
  $DBuser               = "[account]";
  $DBpassword           = "[password]";
  $DBprefix             = "";

# enable debugging

if ( $Sitedebug == 'YES' || $Sitedebug == 'ALL' ) {
# set ini variables to manage error handling
  ini_set('error_reporting', E_ALL | E_STRICT);
  if ($Sitedebug == 'ALL') {
    ini_set('display_errors', 'on');
  } else {
    ini_set('display_errors', 'off');
  }
  ini_set('log_errors', 'On');
  ini_set('error_log', '/var/tmp/status.log');
}


# site details
$Sitename		= "Status Management";
$Sitefooter		= "";

# Root directory for the Inventory Program
$Sitepath		= $Sitedir . $Siteinstall;
$Siteroot		= $Siteurl . $Siteinstall;

#######
##  Application and Utility specific locations
##  Sitepath is the prefix for OS level files such as include() or fopen()
##  Siteroot is the prefix for URL based files
#######

## Admin Tools
$Adminpath		= $Sitepath . "/admin";
$Adminroot		= $Siteroot . "/admin";

## Bugs
$Bugpath		= $Sitepath . "/bugs";
$Bugroot		= $Siteroot . "/bugs";

## FAQ
$FAQpath		= $Sitepath . "/faq";
$FAQroot		= $Siteroot . "/faq";

## Jira
$Jirapath		= $Sitepath . "/jira";
$Jiraroot		= $Siteroot . "/jira";

## Login
$Loginpath		= $Sitepath . "/login";
$Loginroot		= $Siteroot . "/login";

## Morning Report
$Morningpath		= $Sitepath . "/morning";
$Morningroot		= $Siteroot . "/morning";

## Project Management
$Projectpath		= $Sitepath . "/projects";
$Projectroot		= $Siteroot . "/projects";

## Resource Allocation Spreadsheet
$RASpath		= $Sitepath . "/ras";
$RASroot		= $Siteroot . "/ras";

## Reports
$Reportpath		= $Sitepath . "/reports";
$Reportroot		= $Siteroot . "/reports";

## Special Requests
$Requestspath		= $Sitepath . "/requests";
$Requestsroot		= $Siteroot . "/requests";

## Status Pages
$Statuspath		= $Sitepath . "/status";
$Statusroot		= $Siteroot . "/status";

## Todo Pages
$Todopath		= $Sitepath . "/todo";
$Todoroot		= $Siteroot . "/todo";

# disable access to the site and print a maintenance message
$Sitemaintenance	= "1";
$Sitecopyright		= "";

# Access levels
$AL_Developer		= 1;
$AL_Admin		= 2;
$AL_VicePresident	= 3;
$AL_Director		= 4;
$AL_Manager		= 5;
$AL_Supervisor		= 6;
$AL_User		= 7;
$AL_Guest		= 8;

# Set a default theme for users not logged in.
if (!isset($_SESSION['theme'])) {
  $_SESSION['theme']	= 'sunny';
}

?>
