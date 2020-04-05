<?php

function getWeightedValue($FINAL_SCORE, $WEIGHT)
{
  $MUL_SETS = [];
  foreach ($FINAL_SCORE as $sKey => $data) {
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

function getScore($data, $threshold, $keys = null)
{
  $score = [];
  if ($threshold == "LOW_VALUE_HIGH_SCORE") {
    if (gettype($data) == 'array') {
      foreach ($data as $key => $value) {
        $score[$key] = LOW_VALUE_HIGH_SCORE($value, $keys);
      }
    } else {
      return LOW_VALUE_HIGH_SCORE($data, $keys);
    }
  } else if ($threshold == "HIGH_VALUE_HIGH_SCORE") {
    if (gettype($data) == 'array') {
      foreach ($data as $key => $value) {
        $score[$key] = HIGH_VALUE_HIGH_SCORE($value, $keys);
      }
    } else {
      return HIGH_VALUE_HIGH_SCORE($data, $keys);
    }
  } else if ($threshold == "LOW_VALUE_HIGH_SCORE_EQ") {
    if (gettype($data) == 'array') {
      foreach ($data as $key => $value) {
        $score[$key] = LOW_VALUE_HIGH_SCORE_EQ($value, $keys);
      }
    } else {
      return LOW_VALUE_HIGH_SCORE_EQ($data, $keys);
    }
  }
  return $score;
}

function LOW_VALUE_HIGH_SCORE($value, $keys)
{
  if ($value < $keys[0]) {
    return 5;
  } else if ($value < $keys[1]) {
    return 4;
  } else if ($value < $keys[2]) {
    return 3;
  } else if ($value < $keys[3]) {
    return 2;
  } else if ($value >= $keys[3]) {
    return 1;
  }
}

function HIGH_VALUE_HIGH_SCORE($value, $keys)
{
  if ($value < $keys[0]) {
    return 1;
  } else if ($value < $keys[1]) {
    return 2;
  } else if ($value < $keys[2]) {
    return 3;
  } else if ($value < $keys[3]) {
    return 4;
  } else if ($value >= $keys[3]) {
    return 5;
  }
}

function LOW_VALUE_HIGH_SCORE_EQ($value, $keys)
{
  if ($value <= $keys[0]) {
    return 5;
  } else if ($value <= $keys[1]) {
    return 4;
  } else if ($value <= $keys[2]) {
    return 3;
  } else if ($value <= $keys[3]) {
    return 2;
  } else if ($value > $keys[3]) {
    return 1;
  }
}
