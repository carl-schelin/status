<?php
# Script: edit.status.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'no';
  include($Sitepath . '/function.php');
  include($Loginpath . '/check.php');

# connect to the database
  $db = db_connect($DBserver, $DBname, $DBuser, $DBpassword);

  check_login($db, $AL_User);

  $package = "edit.status.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

  $formVars['user']      = clean($_GET['user'], 10);
  $formVars['startweek'] = clean($_GET['startweek'], 4);

  if ($formVars['user'] == 0) {
    $formVars['user'] = 1;
  }

  if ($formVars['startweek'] == 0) {
    $formVars['startweek'] = 1;
  }

  logaccess($db, $_SESSION['username'], "edit.status.php", "Editing status detail records: week=" . $formVars['startweek'] . " user=" . $formVars['user']);

  $q_string  = "select usr_id,usr_name ";
  $q_string .= "from st_users";
  $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ( $a_st_users = mysqli_fetch_array($q_st_users) ) {
    if ($_SESSION['username'] == $a_st_users['usr_name']) {
      $formVars['id'] = $a_st_users['usr_id'];
    }
  }

  if ($formVars['user'] != $formVars['id']) {
    check_login($db, $AL_Supervisor);
    logaccess($db, $_SESSION['username'], "edit.status.php", "Escalated privileged access to " . $formVars['id']);
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Edit Weekly Status Report</title>

<?php include($Sitepath . "/head.php"); ?>

<script type="text/javascript">

function attach_file( p_script_url ) {
  // create new script element, set its relative URL, and load it
  script = document.createElement('script');
  script.src = p_script_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function delete_line( p_script_url ) {
  var answer = confirm("Delete this line?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
  }
}

</script>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<?php


#######
# Retrieve the group info from the user
#######

  $q_string  = "select usr_group ";
  $q_string .= "from st_users ";
  $q_string .= "where usr_id = " . $formVars['user'] . " ";
  $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $usergroup = mysqli_fetch_array($q_st_users);

#######
# Retrieve all the type into the typeval array
#######

  $q_string  = "select typ_id,typ_name ";
  $q_string .= "from st_type";
  $q_st_type = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  while ( $a_st_type = mysqli_fetch_array($q_st_type) ) {
    $typeval[$a_st_type['typ_id']] = $a_st_type['typ_name'];
  }
  $typetot = count($typeval);

#######
# Retrieve all the weeks into the weekval array
#######

  $q_string  = "select wk_id,wk_date ";
  $q_string .= "from st_weeks";
  $q_st_weeks = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ( $a_st_weeks = mysqli_fetch_array($q_st_weeks) ) {
    $weekval[$a_st_weeks['wk_id']] = $a_st_weeks['wk_date'];
  }
  $weektot = count($weekval);

#######
# Retrieve all the classifications into the classval array
#######

  $q_string  = "select cls_id,cls_name ";
  $q_string .= "from st_class ";
  $q_st_class = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  $class = 0;
  while ( $a_st_class = mysqli_fetch_array($q_st_class) ) {
    $classval[$a_st_class['cls_id']] = $a_st_class['cls_name'];
  }
  $clastot = count($classval);

#######
# Retrieve all the projects into the projval array
#######

  $project = 0;
  $q_string  = "select prj_id,prj_task ";
  $q_string .= "from st_project ";
  $q_string .= "where prj_group = " . $usergroup['usr_group'] . " ";
  $q_string .= "order by prj_name";
  $q_st_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ( $a_st_project = mysqli_fetch_array($q_st_project) ) {
    $projval[$project][0] = $a_st_project['prj_id'];
    $projval[$project++][1] = $a_st_project['prj_task'];
  }
  $projtot = count($projval);


#######
# Retrieve all the progress into the progval array
#######

  $q_string  = "select pro_id,pro_name ";
  $q_string .= "from st_progress ";
  $q_st_progress = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  $progress = 0;
  while ( $a_st_progress = mysqli_fetch_array($q_st_progress) ) {
    $progval[$a_st_progress['pro_id']] = $a_st_progress['pro_name'];
  }
  $progtot = count($progval);

#######
# Set up the weekday values
#######

  $dayval[0] = "Sun";
  $dayval[1] = "Mon";
  $dayval[2] = "Tues";
  $dayval[3] = "Wed";
  $dayval[4] = "Thurs";
  $dayval[5] = "Fri";
  $dayval[6] = "Sat";
  $daytot = 7;

#######
# Now process the status reports
#######

  print "<form name=\"update\">\n";

  print "<table class=\"ui-widget-content\">\n";
  print "<tr>\n";
  print "  <th class=\"ui-state-default\">Date</th>\n";
  print "  <th class=\"ui-state-default\">Classification</th>\n";
  print "  <th class=\"ui-state-default\">Description</th>\n";
  print "  <th class=\"ui-state-default\">Save</th>\n";
  print "  <th class=\"ui-state-default\">Delete</th>\n";
  print "</tr>\n";

  $q_string  = "select strp_id,strp_week,strp_class,strp_task ";
  $q_string .= "from st_status ";
  $q_string .= "where strp_name = " . $formVars['user'] . " and strp_week = " . $formVars['startweek'] . " ";
  $q_string .= "order by strp_class,strp_project,strp_task";
  $q_st_status = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ( $a_st_status = mysqli_fetch_array($q_st_status) ) {

    print "<tr>\n";

    print "  <td class=\"ui-widget-content\" id=\"week_" . $a_st_status['strp_id'] . "\"><select name=\"week_" . $a_st_status['strp_id'] . "\">\n";
    for ($i = 0; $i <= $weektot; $i++) {
      if ($a_st_status['strp_week'] == $i) {
        $selected = " selected";
      } else {
        $selected = "";
      }
      print "<option" . $selected . " value=\"" . $i . "\">" . $weekval[$i] . "\n";
    }
    print "</select></td>\n";

    print "  <td class=\"ui-widget-content\" id=\"clas_" . $a_st_status['strp_id'] . "\"><select name=\"clas_" . $a_st_status['strp_id'] . "\">\n";
    for ($i = 1; $i <= $clastot; $i++) {
      if ($a_st_status['strp_class'] == $i) {
        $selected = " selected";
      } else {
        $selected = "";
      }
      print "<option" . $selected . " value=\"" . $i . "\">" . $classval[$i] . "\n";
    }
    print "</select></td>\n";

    print "  <td class=\"ui-widget-content\" id=\"task_" . $a_st_status['strp_id'] . "\"><input type=\"text\" name=\"task_" . $a_st_status['strp_id'] . "\" size=\"107\" value=\"" . $a_st_status['strp_task'] . "\"></td>\n";

    print "  <td class=\"ui-widget-content delete\" id=\"save_" . $a_st_status['strp_id'] . "\"><input type=\"button\" value=\"Save\" onClick=\"javascript:attach_file('edit.status.mysql.php?id=" . $a_st_status['strp_id'] . "&week=' + week_" . $a_st_status['strp_id'] . ".value + '&class=' + clas_" . $a_st_status['strp_id'] . ".value + '&task=' + task_" . $a_st_status['strp_id'] . ".value);\"></td>\n";
    print "  <td class=\"ui-widget-content delete\" id=\"del_" . $a_st_status['strp_id'] . "\"><input type=\"button\" value=\"Del\" onClick=\"javascript:delete_line('del.status.mysql.php?id=" . $a_st_status['strp_id'] . "');\"></td>\n";

  }

?>
</table>

</form>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
