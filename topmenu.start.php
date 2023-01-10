<div id="header">

<p><a href="<?php print $Siteroot; ?>"><img src="<?php print $Siteroot; ?>/imgs/<?php print $Siteheader; ?>"></a></p>

</div>

<div class="main">

<div class="menu">

<ul id="topmenu">
  <li id="tm_home"><a href="<?php print $Siteroot; ?>">Home</a></li>
  <li id="tm_applications"><a href="<?php print $Siteroot; ?>/index.apps.php">Applications</a>
    <ul>
<?php
  if (check_userlevel($db, $AL_Supervisor)) {
    print "  <li><a href=\"" . $Statusroot . "/managers.php\">Management View</a></li>\n";
  }
?>
      <li><a href="<?php print $Reportroot; ?>/completed.php">Show Completed Tasks</a></li>
      <li><a href="<?php print $Siteroot; ?>/search.php">Search Task Database</a></li>
    </ul>
  </li>
  <li id="tm_custom"><a href="<?php print $Siteroot; ?>/index.custom.php">Special Requests</a></li>
  <li id="tm_jira"><a href="<?php print $Siteroot; ?>/index.jira.php">Jira</a>
    <ul>
      <li><a href="<?php print $Jiraroot; ?>/epics.php">Jira Epic Topics</a></li>
      <li><a href="<?php print $Jiraroot; ?>/userstories.php">Jira User Stories</a></li>
    </ul>
  </li>
  <li id="tm_projects"><a href="<?php print $Siteroot; ?>/index.projects.php">Projects</a>
    <ul>
      <li><a href="<?php print $Projectroot; ?>/timecodes.php">Project Code Table</a></li>
      <li><a href="<?php print $Projectroot; ?>/add.project.php?group=<?php print $_SESSION['group']; ?>">Add Project Description</a></li>
      <li><a href="<?php print $Projectroot; ?>/edit.project.php?group=<?php print $_SESSION['group']; ?>">Edit Project Codes</a></li>
    </ul>
  </li>
  <li id="tm_manage"><a href="<?php print $Siteroot; ?>/index.manage.php">Database</a>
    <ul>
      <li><a href="<?php print $Siteroot; ?>/add.class.php">Classifications Table</a></li>
      <li><a href="<?php print $Siteroot; ?>/add.progress.php">Progress Table</a></li>
      <li><a href="<?php print $Siteroot; ?>/add.type.php">Task Types Table</a></li>
    </ul>
  </li>
