<?php
# Script: email.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description:

  include('settings.php');
  $called = 'no';
  include($Sitepath . '/function.php');
  include($Loginpath . '/check.php');

# connect to the database
  $db = db_connect($DBserver, $DBname, $DBuser, $DBpassword);

  check_login($db, $AL_User);

  $package = "email.php";
  $debug = 'no';
  $debug = 'yes';

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

  $formVars['user']      = clean($_GET['user'], 10);
  $formVars['startweek'] = clean($_GET['startweek'], 4);
  $formVars['endweek']   = clean($_GET['endweek'], 4);
  $formVars['group']     = clean($_GET['group'], 4);

  if ($formVars['startweek'] == 0) {
    $formVars['startweek'] = 118;
  }

  if ($formVars['endweek'] == '') {
    $formVars['endweek'] = 118;
  }

  if ($formVars['startweek'] == $formVars['endweek']) {
    $formVars['endweek'] = $formVars['startweek'] + 1;
  }

  if ($formVars['user'] == 0 && $formVars['group'] == 0) {
    $formVars['user'] = 1;
  }

  $logfile = "email.php";

  if ( $debug == 'yes' ) {
    print "<pre>User: " . $formVars['user'] . ", Startweek: " . $formVars['startweek'] . ", Endweek: " . $formVars['endweek'] . ", Group: " . $formVars['group'] . "</pre>\n";
  }

  logaccess($db, $_SESSION['username'], $logfile, "Sending e-mail status message: week=" . $formVars['startweek'] . " user=" . $formVars['user']);

  $q_string  = "select usr_id,usr_group ";
  $q_string .= "from st_users ";
  $q_string .= "where usr_name = \"" . $_SESSION['username'] . "\"";
  $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_st_users = mysqli_fetch_array($q_st_users);

  $formVars['id'] = $a_st_users['usr_id'];

  if ($formVars['user'] != $formVars['id']) {
    check_login($db, $AL_Supervisor);
    logaccess($db, $_SESSION['username'], $logfile, "Escalated privileged access to " . $formVars['id']);
  }

  $subject = "Status Report";

  $doweek[0] = "Sunday";
  $doweek[1] = "Monday";
  $doweek[2] = "Tuesday";
  $doweek[3] = "Wednesday";
  $doweek[4] = "Thursday";
  $doweek[5] = "Friday";
  $doweek[6] = "Saturday";

#######
# Retrieve information for the user
#######

  $q_string  = "select usr_first,usr_last,usr_email,usr_group ";
  $q_string .= "from st_users ";
  $q_string .= "where usr_id = " . $formVars['user'];
  $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_st_users = mysqli_fetch_array($q_st_users);

  $userval = $a_st_users['usr_first'] . " " . $a_st_users['usr_last'];
  $usermail = $a_st_users['usr_email'];
  $usergroup = $a_st_users['usr_group'];

  if ($debug == 'yes') {
    print "<pre>User: " . $userval . ", Email: " . $usermail . ", Group: " . $usergroup . "</pre>\n";
  }

#######
# Retrieve information for the group
#######

  $q_string  = "select grp_day ";
  $q_string .= "from st_groups ";
  $q_string .= "where grp_id = $usergroup";
  $q_st_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_st_groups = mysqli_fetch_array($q_st_groups);

  $startday = $a_st_groups['grp_day'];

  if ($debug == 'yes') {
    print "<pre>Start Day: " . $startday . "</pre>\n";
# start on Sunday; can select a different day for reports to start but for testing, use Sunday
    $startday = 0;
  }

#######
# Retrieve all the weeks into the weekval array
#######

  $q_string  = "select wk_id,wk_date ";
  $q_string .= "from st_weeks ";
  $q_st_weeks = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  while ( $a_st_weeks = mysqli_fetch_array($q_st_weeks) ) {
    $weekval[$a_st_weeks['wk_id']] = $a_st_weeks['wk_date'];
  }

#######
# Retrieve all the classifications into the classval array
#######

  $class = 0;
  $q_string  = "select cls_id,cls_name,cls_project ";
  $q_string .= "from st_class";
  $q_st_class = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  while ( $a_st_class = mysqli_fetch_array($q_st_class) ) {
    $classval[$a_st_class['cls_id']] = $a_st_class['cls_name'];
    $classprj[$a_st_class['cls_id']] = $a_st_class['cls_project'];
  }

#######
# Retrieve all the projects into the projval array
#######

  $project = 0;
  $q_string  = "select prj_id,prj_desc ";
  $q_string .= "from st_project";
  $q_st_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  while ( $a_st_project = mysqli_fetch_array($q_st_project) ) {
    $projval[$a_st_project['prj_id']] = $a_st_project['prj_desc'];
  }

#######
# Retrieve all the progress into the progval array
#######

  $progress = 0;
  $q_string  = "select pro_id,pro_name ";
  $q_string .= "from st_progress";
  $q_st_progress = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ( $a_st_progress = mysqli_fetch_array($q_st_progress) ) {
    $progval[$a_st_progress['pro_id']] = $a_st_progress['pro_name'];
  }

#######
# Now process the status reports
#######

###
# Logic:
#  if group > 0
#   if user level = manager, get all the users that usr_manager = usr_id
#   if user level = supervisor, get all the users where usr_group = $formVars['group']
###

  if ($formVars['group'] != 0) {
# so has to be designated as a manager _and_ looking at the management group
    if (check_userlevel($db, $AL_Supervisor)) {
      $q_string = "select usr_id from st_users where usr_supervisor = " . $formVars['user'];
    }
    if (check_userlevel($db, $AL_Manager)) {
      $q_string = "select usr_id from st_users where usr_manager = " . $formVars['user'];
    }
    if (check_userlevel($db, $AL_Director)) {
      $q_string = "select usr_id from st_users where usr_director = " . $formVars['user'];
    }
    if (check_userlevel($db, $AL_VicePresident)) {
      $q_string = "select usr_id from st_users where usr_vicepresident = " . $formVars['user'];
    }

# restrict to group if looking at something other than the Management group
    if ($formVars['group'] != 3 && $formVars['group'] != -1) {
      $q_string .= " and usr_group = " . $formVars['group'];
    }

    if ($DEBUG == 1) {
      logaccess($db, $_SESSION['username'], $logfile, $q_string);
    }
# now build th euser string this will have all the users that fit the above criteria
    $prtor = "";
    $u_string = "";

    $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    while ($a_st_users = mysqli_fetch_array($q_st_users)) {
      $u_string .= $prtor . "strp_name = " . $a_st_users['usr_id'];
      if ($prtor == "") {
        $prtor = " or ";
      }
    }
# if no users were found, empty group for instance, present just the user's data
    if ($u_string == "") {
      $u_string = "strp_name = " . $formVars['user'];
    }
  } else {
    $u_string = "strp_name = " . $formVars['user'];
  }

  $holdweek = '';
  $class = 0;
  $linefeed = "";
  $first = 0;
  $body = '';

  $q_string  =  "select strp_id,strp_week,strp_name,strp_ticket,strp_jira,strp_class,strp_project,strp_progress,strp_task,strp_day ";
  $q_string .= "from st_status ";
  $q_string .= "where ($u_string) ";
  $q_string .= "and strp_week >= " . $formVars['startweek'] . " and strp_save = 1 ";
  $q_string .= "order by strp_week,strp_class,strp_project,strp_day";

  $q_st_status = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  while ( $a_st_status = mysqli_fetch_array($q_st_status) ) {

    if ($holdweek != $a_st_status['strp_week']) {
      $holdweek = $a_st_status['strp_week'];
      if ($a_st_status['strp_week'] == $formVars['startweek']) {
        $body .= "* Starting on " . $doweek[$startday] . " of Week " . $weekval[$a_st_status['strp_week']] . "\n";
      }
      if ($a_st_status['strp_week'] == $formVars['endweek'] && $startday != 0) {
        $body .= "\n* Ending on " . $doweek[$startday - 1] . " of Week " . $weekval[$a_st_status['strp_week']] . "\n";
      }
    }

    if (($a_st_status['strp_week'] == $formVars['startweek'] && $a_st_status['strp_day'] >= $startday) || ($a_st_status['strp_week'] == $formVars['endweek'] && $a_st_status['strp_day'] < $startday)) {
      if ($class != $a_st_status['strp_class']) {
        $class = $a_st_status['strp_class'];
        if ($class > 0) {
          $body .= "\n" . $classval[$a_st_status['strp_class']] . "\n";
        }
      }
   
      $pre = "";
      if ($class > 0) {
        if ($classprj[$class] == 1) {
          $pre = "    - ";
          if ($project != $a_st_status['strp_project']) {
            $project = $a_st_status['strp_project'];
            if ($project > 0) {
              $body .= $linefeed . "* " . $projval[$a_st_status['strp_project']] . "\n";
            }
            $linefeed = "\n";
          }
        } else {
          $pre = "  - ";
        }
      }

      $body .= $pre;
      if ($a_st_status['strp_progress'] > 0) {
        $body .= $progval[$a_st_status['strp_progress']] . ": ";
      }

      $q_string  = "select user_jira ";
      $q_string .= "from st_userstories ";
      $q_string .= "where user_id = " . $a_st_status['strp_jira'] . " ";
      $q_st_userstories = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_st_userstories) > 0) {
        $a_st_userstories = mysqli_fetch_array($q_st_userstories);
        $jira = $a_st_userstories['user_jira'] . ": ";
      } else {
        $jira = '';
      }

      $q_string  = "select tik_number ";
      $q_string .= "from st_tickets ";
      $q_string .= "where tik_id = " . $a_st_status['strp_ticket'] . " ";
      $q_st_tickets = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_st_tickets) > 0) {
        $a_st_tickets = mysqli_fetch_array($q_st_tickets);
        $ticket = $a_st_tickets['tik_number'] . ": ";
      } else {
        $ticket = '';
      }

      $body .= $jira . $ticket . $a_st_status['strp_task'] . "\n";
    }
  }

  if ($debug == "yes") {
    print "<pre>To: " . $usermail . "\nSubject: " . $subject . "\n\n" . $body . "</pre>";
  } else {
    echo "<meta http-equiv=\"REFRESH\" content=\"5; url=" . $Siteroot . "\">\n";
  }

  $headers  = 'MIME-Version: 1.0' ."\r\n";
  $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
  $headers .= 'From: Status management <unixsvc@oss1cotool11.orionspace.com>' . "\r\n";
  $headers .= 'Reply-To: Status management <unixsvc@oss1cotool11.orionspace.com>' . "\r\n";
  $headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";

  $headers = array(
    'From' => 'unixsvc@oss1cotool11.orionspace.com',
    'Reply-To' => 'unixsvc@oss1cotool11.orionspace.com',
    'X-Mailer' => 'PHP/' . phpversion()
  );

  $result = mail($usermail, $subject, $body, $headers);
  if ($result) {
      echo("<p>Message successfully sent! [" . $result . "]</p>");
   } else {
      echo("<p>Message delivery failed... [" . $result . "]</p>");
   }

?>
