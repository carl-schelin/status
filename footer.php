<br>

<?php
  if (isset($_SESSION['username'])) {
    $q_string  = "select grp_name ";
    $q_string .= "from st_grouplist ";
    $q_string .= "left join st_groups on st_groups.grp_id = st_grouplist.gpl_group ";
    $q_string .= "where gpl_user = " . $_SESSION['uid'];
    $q_st_grouplist = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    if (mysqli_num_rows($q_st_grouplist) > 0) {
      print "<p style=\"text-align: center;\">You are currently a member of the following groups: ";
      $comma = "";
      while ($a_st_grouplist = mysqli_fetch_array($q_st_grouplist)) {
        print $comma . "<u>" . $a_st_grouplist['grp_name'] . "</u>";
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
