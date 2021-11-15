<?php

  header('Content-Type: text/javascript');

  function clean($input, $maxlength)
  {
    $input = substr($input, 0, $maxlength);
    $input = escapeshellcmd($input);
    return ($input);
  }

  $formVars['loop']     = trim(clean($_GET['loop'], 10));

  $output .= "<div style=\"overflow: hidden;\" id=\"game" . $formVars['loop'] . "\" class=\"hide_me\">";
  $output .= "";
  $output .= "  <div class=textcontent>";
  $output .= "    <table width=100%>";
  $output .= "    <tr>";
  $output .= "      <th><a href=\"#\" onClick=\"javascript:attach_file(\'index.puzzle.php?loop=" . ($formVars['loop'] + 1) . "\'); slide(\'game" . ($formVars['loop'] + 1) . "\',100); return false;\" class=\"trigger\">Play magic piano</a></th>";
  $output .= "      <th><a href=\"#\" onClick=\"javascript:attach_file(\'index.puzzle.php?loop=" . ($formVars['loop'] + 1) . "\'); slide(\'game" . ($formVars['loop'] + 1) . "\',100); return false;\" class=\"trigger\">Find a heart shaped key</a></th>";
  $output .= "      <th><a href=\"#\" onClick=\"javascript:attach_file(\'index.puzzle.php?loop=" . ($formVars['loop'] + 1) . "\'); slide(\'game" . ($formVars['loop'] + 1) . "\',100); return false;\" class=\"trigger\">Win a deadly game of chess</a></th>";
  $output .= "      <th><a href=\"#\" onClick=\"javascript:attach_file(\'index.puzzle.php?loop=" . ($formVars['loop'] + 1) . "\'); slide(\'game" . ($formVars['loop'] + 1) . "\',100); return false;\" class=\"trigger\">Read a diary</a></th>";
  $output .= "      <th><a href=\"#\" onclick=\"slide(\'runfight\',100); slide(\'nowwhat\',100); return false;\" class=\"trigger\">Move bookshelf</a></th>";
  $output .= "    </tr>";
  $output .= "    </table>";
  $output .= "  </div>";
  $output .= "";
  $output .= "</div>";
  $output .= "";
  $output .= "<span id=\"puzzleloop" . ($formVars['loop'] + 1) . "\"></span>";

?>

document.getElementById('puzzleloop<?php print $formVars['loop']; ?>').innerHTML = '<?php echo $output; ?>';

