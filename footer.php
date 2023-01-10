<br>

<?php
  if (isset($_SESSION['username'])) {
    $q_string  = "select grp_name ";
    $q_string .= "from grouplist ";
    $q_string .= "left join groups on groups.grp_id = grouplist.gpl_group ";
    $q_string .= "where gpl_user = " . $_SESSION['uid'];
    $q_grouplist = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    if (mysqli_num_rows($q_grouplist) > 0) {
      print "<p style=\"text-align: center;\">You are currently a member of the following groups: ";
      $comma = "";
      while ($a_grouplist = mysqli_fetch_array($q_grouplist)) {
        print $comma . "<u>" . $a_grouplist['grp_name'] . "</u>";
        $comma = ", ";
      }
      print "</p>\n";
    } else {
      print "<p style=\"text-align: center;\">You are not the member of any group.</p>\n";
    }
  } else {
    print "<p style=\"text-align: center;\">You are not currently logged in.</p>\n";
  }
?>
