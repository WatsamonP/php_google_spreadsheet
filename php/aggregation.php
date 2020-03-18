<?php
require_once __DIR__ . "./../php/calculation/utils.php";
require_once __DIR__ . "./../php/calculation/getInterpretation.php";
require_once __DIR__ . "./../php/dimension_one.php";
require_once __DIR__ . "./../php/dimension_two.php";
require_once __DIR__ . "./../php/dimension_three.php";
require_once __DIR__ . "./../php/dimension_four.php";
require_once __DIR__ . "./../php/dimension_five.php";

$overallWSI = getAverageColumns(array(
  'Dimension 1' => $FINAL_INDICATOR_WA,
  'Dimension 2' => $FINAL_INDICATOR_WP,
  'Dimension 3' => $FINAL_INDICATOR_WD,
  'Dimension 4' => $FINAL_INDICATOR_WH,
  'Dimension 5' => $FINAL_INDICATOR_WG
));

$averageOverall = array_sum($overallWSI)/count($overallWSI);
$overallWSI_TEXT = getInterpretation($overallWSI);
$averageOverall_TEXT = getInterpretation($averageOverall);