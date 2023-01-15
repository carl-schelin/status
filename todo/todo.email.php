<?php
# Script: todo.email.php
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

  $package = "todo.email.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

  $formVars['user']       = clean($_GET['user'], 10);
  $formVars['startweek']  = clean($_GET['startweek'], 4);
  $formVars['group']      = clean($_GET['group'], 4);


  if ($formVars['startweek'] == 0) {
    $formVars['startweek'] = 118;
  }

  if ($formVars['user'] == 0 && $formVars['group'] == 0) {
    $formVars['user'] = 1;
  }

  logaccess($db, $_SESSION['username'], "todo.email.php", "Sending e-mail todo message: week=" . $formVars['startweek'] . " user=" . $formVars['user']);

  $q_string  = "select usr_id,usr_group ";
  $q_string .= "from st_users ";
  $q_string .= "where usr_name = \"" . $_SESSION['username'] . "\"";
  $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_st_users = mysqli_fetch_array($q_st_users);

  $formVars['id'] = $a_st_users['usr_id'];

  if ($formVars['user'] != $formVars['id']) {
    logaccess($db, $_SESSION['username'], "email.php", "Escalated privileged access to " . $formVars['id']);
    check_login($db, $AL_Supervisor);
  }

  $subject = "Todo Report";

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

#######
# Retrieve information for the group
#######

#  $q_string  = "select grp_week ";
#  $q_string .= "from st_groups ";
#  $q_string .= "where grp_id = $usergroup";
#  $q_st_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
#  $a_st_groups = mysqli_fetch_array($q_st_groups);
#
#  $groupstatus = $a_st_groups['grp_week'];

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
    if (check_userlevel($db, $AL_Supervisor) && $formVars['group'] == 3) {
      $prtor = "";
      $u_string = "";

      $q_string  = "select usr_id ";
      $q_string .= "from st_users ";
      $q_string .= "where usr_manager = " . $formVars['id'];
      $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      while ($a_st_users = mysqli_fetch_array($q_st_users)) {
        $u_string .= $prtor . "strp_name = " . $a_st_users['usr_id'];
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
      $q_string .= "from st_users ";
      $q_string .= "where usr_group = " . $formVars['group'];
      $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      while ($a_st_users = mysqli_fetch_array($q_st_users)) {
        $u_string .= $prtor . "todo_user = " . $a_st_users['usr_id'];
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
  $q_string .= "from st_todo ";
  $q_string .= "where ($u_string) ";
#  $q_string .= "and todo_due <= " . ($formVars['startweek'] + 1) . " ";
  $q_string .= "and todo_completed = 0 and todo_save = 1 ";
  $q_string .= "order by todo_class,todo_project,todo_due,todo_status,todo_priority,todo_name ";
  $q_st_todo = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ( $a_st_todo = mysqli_fetch_array($q_st_todo) ) {

    if ($first++ == 0) {
      $body .= "Tasks due as of : " . $weekval[$formVars['startweek'] + 1] . "\n";
    }

    if ($class != $a_st_todo['todo_class']) {
      $class = $a_st_todo['todo_class'];
      if ($class > 0) {
        $body .= "\n" . $classval[$a_st_todo['todo_class']] . "\n";
      }
    }

    $pre = "";
    if ($class > 0) {
      if ($classprj[$class] == 1) {
        $pre = "    - ";
        if ($project != $a_st_todo['todo_project']) {
            $project = $a_st_todo['todo_project'];
            if ($project > 0) {
                $body .= $linefeed . "* " . $projval[$a_st_todo['todo_project']] . "\n";
            }
            $linefeed = "\n";
        }
      } else {
        $pre = "  - ";
      }
    }

    $body .= $pre;
    if ($a_st_todo['todo_status']) {
      $body .= "Desired - ";
    } else {
      $body .= "Required - ";
    }
    $body .= $a_st_todo['todo_priority'] . " - " . $a_st_todo['todo_name'] . "\n";
  }

  echo "<meta http-equiv=\"REFRESH\" content=\"5; url=" . $Siteroot . "/\">\n";

  if (mail($usermail, $subject, $body)) {
     echo("<p>Message successfully sent!</p>");
  } else {
     echo("<p>Message delivery failed...</p>");
  }

?>
