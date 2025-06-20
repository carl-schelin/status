<table class="ui-styled-table">
<tr>
  <th class="ui-state-default">Group Form</th>
</tr>
<tr>
  <td class="ui-widget-content">Group Name: <input type="text" name="grp_name" size="40"></td>
</tr>
<tr>
  <td class="ui-widget-content">Group Report Order: <input type="text" name="grp_report" size="10"></td>
</tr>
<tr>
  <td class="ui-widget-content">Group E-Mail: <input type="text" name="grp_email" size="40"></td>
</tr>
<tr>
  <td class="ui-widget-content">Group Manager: <select name="grp_manager">
<option value="0">Unassigned</option>
<?php
  $q_string  = "select usr_id,usr_last,usr_first ";
  $q_string .= "from st_users ";
  $q_string .= "where usr_disabled = 0 ";
  $q_string .= "order by usr_last,usr_first ";
  $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_st_users = mysqli_fetch_array($q_st_users)) {
    print "<option value=\"" . $a_st_users['usr_id'] . "\">" . $a_st_users['usr_last'] . ", " . $a_st_users['usr_first'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Group Status <select name="grp_disabled">
<option value="0">Enabled</option>
<option value="1">Disabled</option>
</select></td>
</tr>
</table>
