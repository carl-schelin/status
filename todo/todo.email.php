<?php
# Script: todo.email.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'no';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($AL_User);

  $package = "todo.email.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

  $formVars['user']       = clean($_GET['user'], 10);
  $formVars['startweek']  = clean($_GET['startweek'], 4);
  $formVars['group']      = clean($_GET['group'], 4);


  if ($formVars['startweek'] == 0) {
    $formVars['startweek'] = 118;
  }

  if ($formVars['user'] == 0 && $formVars['group'] == 0) {
    $formVars['user'] = 1;
  }

  logaccess($_SESSION['username'], "todo.email.php", "Sending e-mail todo message: week=" . $formVars['startweek'] . " user=" . $formVars['user']);

  $q_string  = "select usr_id,usr_group ";
  $q_string .= "from users ";
  $q_string .= "where usr_name = \"" . $_SESSION['username'] . "\"";
  $q_users = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  $a_users = mysql_fetch_array($q_users);

  $formVars['id'] = $a_users['usr_id'];

  if ($formVars['user'] != $formVars['id']) {
    logaccess($_SESSION['username'], "email.php", "Escalated privileged access to " . $formVars['id']);
    check_login($AL_Supervisor);
  }

  $subject = "Todo Report";

#######
# Retrieve information for the user
#######

  $q_string  = "select usr_first,usr_last,usr_email,usr_group ";
  $q_string .= "from users ";
  $q_string .= "where usr_id = " . $formVars['user'];
  $q_users = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  $a_users = mysql_fetch_array($q_users);

  $userval = $a_users['usr_first'] . " " . $a_users['usr_last'];
  $usermail = $a_users['usr_email'];
  $usergroup = $a_users['usr_group'];

#######
# Retrieve information for the group
#######

#  $q_string  = "select grp_week ";
#  $q_string .= "from groups ";
#  $q_string .= "where grp_id = $usergroup";
#  $q_groups = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
#  $a_groups = mysql_fetch_array($q_groups);
#
#  $groupstatus = $a_groups['grp_week'];

#######
# Retrieve all the weeks into the weekval array
#######

  $q_string  = "select wk_id,wk_date ";
  $q_string .= "from weeks";
  $q_weeks = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

  while ( $a_weeks = mysql_fetch_array($q_weeks) ) {
    $weekval[$a_weeks['wk_id']] = $a_weeks['wk_date'];
  }

#######
# Retrieve all the classifications into the classval array
#######

  $class = 0;
  $q_string  = "select cls_id,cls_name,cls_project ";
  $q_string .= "from class";
  $q_class = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

  while ( $a_class = mysql_fetch_array($q_class) ) {
    $classval[$a_class['cls_id']] = $a_class['cls_name'];
    $classprj[$a_class['cls_id']] = $a_class['cls_project'];
  }

#######
# Retrieve all the projects into the projval array
#######

  $project = 0;
  $q_string  = "select prj_id,prj_desc ";
  $q_string .= "from project";
  $q_project = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

  while ( $a_project = mysql_fetch_array($q_project) ) {
    $projval[$a_project['prj_id']] = $a_project['prj_desc'];
  }

#######
# Now process the todo reports
#######

###
# Logic:
#  if group > 0
#   if user level = manager, get all the users that usr_manager = usr_id
#   if user level = supervisor, get all the users where usr_group = $formVars['group']
###

  if ($formVars['group'] != 0) {
# so has to be designated as a manager _and_ looking at the management group
    if (check_userlevel($AL_Supervisor) && $formVars['group'] == 3) {
      $prtor = "";
      $u_string = "";

      $q_string  = "select usr_id ";
      $q_string .= "from users ";
      $q_string .= "where usr_manager = " . $formVars['id'];
      $q_users = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      while ($a_users = mysql_fetch_array($q_users)) {
        $u_string .= $prtor . "strp_name = " . $a_users['usr_id'];
        if ($prtor == "") {
            $prtor = " or ";
        }
      }
      if ($u_string == "") {
        $u_string = "strp_name = " . $formVars['user'];
      }
    } else {
      $prtor = "";
      $u_string = "";

      $q_string  = "select usr_id ";
      $q_string .= "from users ";
      $q_string .= "where usr_group = " . $formVars['group'];
      $q_users = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      while ($a_users = mysql_fetch_array($q_users)) {
        $u_string .= $prtor . "todo_user = " . $a_users['usr_id'];
        if ($prtor == "") {
          $prtor = " or ";
        }
      }
      if ($u_string == "") {
        $u_string = "todo_user = " . $formVars['user'];
      }
    }
  } else {
    $u_string = "todo_user = " . $formVars['user'];
  }

  $class = 0;
  $linefeed = "";
  $first = 0;

  $q_string  =  "select todo_id,todo_due,todo_user,todo_class,todo_project,todo_name,todo_priority,todo_status ";
  $q_string .= "from todo ";
  $q_string .= "where ($u_string) ";
#  $q_string .= "and todo_due <= " . ($formVars['startweek'] + 1) . " ";
  $q_string .= "and todo_completed = 0 and todo_save = 1 ";
  $q_string .= "order by todo_class,todo_project,todo_due,todo_status,todo_priority,todo_name ";
  $q_todo = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ( $a_todo = mysql_fetch_array($q_todo) ) {

    if ($first++ == 0) {
      $body .= "Tasks due as of : " . $weekval[$formVars['startweek'] + 1] . "\n";
    }

    if ($class != $a_todo['todo_class']) {
      $class = $a_todo['todo_class'];
      if ($class > 0) {
        $body .= "\n" . $classval[$a_todo['todo_class']] . "\n";
      }
    }

    $pre = "";
    if ($class > 0) {
      if ($classprj[$class] == 1) {
        $pre = "    - ";
        if ($project != $a_todo['todo_project']) {
            $project = $a_todo['todo_project'];
            if ($project > 0) {
                $body .= $linefeed . "* " . $projval[$a_todo['todo_project']] . "\n";
            }
            $linefeed = "\n";
        }
      } else {
        $pre = "  - ";
      }
    }

    $body .= $pre;
    if ($a_todo['todo_status']) {
      $body .= "Desired - ";
    } else {
      $body .= "Required - ";
    }
    $body .= $a_todo['todo_priority'] . " - " . $a_todo['todo_name'] . "\n";
  }

  echo "<meta http-equiv=\"REFRESH\" content=\"5; url=" . $Siteroot . "/\">\n";

  if (mail($usermail, $subject, $body)) {
     echo("<p>Message successfully sent!</p>");
  } else {
     echo("<p>Message delivery failed...</p>");
  }

?>
