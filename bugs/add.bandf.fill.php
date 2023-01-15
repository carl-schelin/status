<?php
# Script: add.bandf.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "add.bandf.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Admin)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from st_bandf");

// Retrieve the data
      $q_string  = "select bf_id,bf_week,bf_name,bf_borf,bf_text,bf_dev,bf_status ";
      $q_string .= "from st_bandf ";
      $q_string .= "where bf_id = " . $formVars['id'];
      $q_st_bandf = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_st_bandf = mysqli_fetch_array($q_st_bandf);

      mysqli_free_result($q_st_bandf);

      $count = 1;
      $developer = 0;
      $q_string  = "select usr_id ";
      $q_string .= "from st_users ";
      $q_string .= "where usr_level = 1 ";
      $q_string .= "order by usr_last";
      $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      while ($a_st_users = mysqli_fetch_array($q_st_users)) {
        if ($a_st_users['usr_id'] == $a_st_bandf['bf_dev']) {
          $developer = $count;
        }
        $count++;
      }

      print "document.bandf.week.value = "     . $a_st_bandf['bf_week']                           . ";\n";
      print "document.bandf.username.value = " . $a_st_bandf['bf_name']                           . ";\n";
      print "document.bandf.bftext.value = '"  . mysqli_real_escape_string($db, $a_st_bandf['bf_text']) . "';\n";

      print "document.bandf.borf['"      . $a_st_bandf['bf_borf'] . "'].checked = true;\n";
      print "document.bandf.developer['" . $developer          . "'].selected = true;\n";

      if ($a_st_bandf['bf_status']) {
        print "document.bandf.completed.checked = true;\n";
      } else {
        print "document.bandf.completed.checked = false;\n";
      }

      print "document.bandf.id.value = " . $formVars['id'] . ";\n";

      print "document.bandf.update.disabled = false;\n";

    }
  }

?>
