<?php

function getWeightedValue($FINAL_SCORE_WA, $WEIGHT)
{
  $MUL_SETS = [];
  foreach ($FINAL_SCORE_WA as $sKey => $data) {
    $MUL_SETS[$sKey] = [];
    foreach ($data as $year => $value) {
      if (isset($WEIGHT[$sKey])) {
        $THIS_WEIGHT = $WEIGHT[$sKey]['weight'];
        $SCORE = $value * $THIS_WEIGHT;
        // print_r($sKey . "|" . $year . " = " . $value . " * " . $THIS_WEIGHT . "\n");
        $MUL_SETS[$sKey][$year] = $SCORE;
      }
    }
  }

  $PLUS_SET = [];
  foreach ($MUL_SETS as $year => $MUL_SET) {
    foreach ($MUL_SET as $year => $value) {
      if (!(isset($PLUS_SET[$year]))) {
        $PLUS_SET[$year] = 0;
        $PLUS_SET[$year] = $value;
      } else {
        $PLUS_SET[$year] += $value;
      }
    }
  }

  return $PLUS_SET;
}

function calTwoSubGroup($WA_TB_SETS, $WA_X_SET, $WEIGHT)
{
  foreach ($WA_X_SET as $sKey => $SUB_SET) { // sKey = WA11, WA12
    $MUL_SETS[$sKey] = [];
    foreach ($WA_TB_SETS[$sKey]['score']['table'] as $year => $value) {
      if (isset($WEIGHT[$sKey])) {
        $THIS_WEIGHT = $WEIGHT[$sKey]['weight'];
        $SCORE = $value * $THIS_WEIGHT;
        // print_r($sKey . "|" . $year . " = " . $value . " * " . $THIS_WEIGHT . "\n");
        $MUL_SETS[$sKey][$year] = $SCORE;
      }
    }
  }

  $PLUS_SET = [];
  foreach ($MUL_SETS as $MUL_SET) {
    foreach ($MUL_SET as $year => $value) {
      if (!(isset($PLUS_SET[$year]))) {
        $PLUS_SET[$year] = 0;
        $PLUS_SET[$year] = $value;
      } else {
        $PLUS_SET[$year] += $value;
      }
    }
  }
  return $PLUS_SET;
}

function getScore($data, $threshold)
{
  $score = [];
  if ($threshold == "FalkenmarkThreshold") {
    foreach ($data as $key => $value) {
      $score[$key] = FalkenmarkThreshold($value);
    }
  } else if ($threshold == "AWDO_2016_Threshold") {
    if (gettype($data) == 'array') {
      foreach ($data as $key => $value) {
        $score[$key] = AWDO2016Threshold($value);
      }
    } else {
      return AWDO2016Threshold($data);
    }
  }
  return $score;
}

function FalkenmarkThreshold($value)
{
  if ($value < 500) {
    return 1;
  } else if ($value < 800) {
    return 2;
  } else if ($value < 1000) {
    return 3;
  } else if ($value < 1700) {
    return 4;
  } else if ($value >= 1700) {
    return 5;
  }
}

function AWDO2016Threshold($value)
{
  if ($value < 0.1) {
    return 1;
  } else if ($value < 0.2) {
    return 2;
  } else if ($value < 0.35) {
    return 3;
  } else if ($value < 1) {
    return 4;
  } else if ($value >= 1) {
    return 5;
  }
}
