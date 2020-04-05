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

function getScore($data, $threshold, $scale = 1, $keys = null)
{
  $score = [];
  if ($threshold == "FalkenmarkThreshold") {
    foreach ($data as $key => $value) {
      $score[$key] = FalkenmarkThreshold($value);
    }
  } else if ($threshold == "AWDO_2016_AG_Threshold") {
    if (gettype($data) == 'array') {
      foreach ($data as $key => $value) {
        $score[$key] = AWDO_2016_AG_Threshold($value, $scale);
      }
    } else {
      return AWDO_2016_AG_Threshold($data, $scale);
    }
  } else if ($threshold == "AWDO_2016_NON_AG_Threshold") {
    if (gettype($data) == 'array') {
      foreach ($data as $key => $value) {
        $score[$key] = AWDO_2016_NON_AG_Threshold($value);
      }
    }
  } else if ($threshold == "AWDO_2016_WATER_Threshold") {
    if (gettype($data) == 'array') {
      foreach ($data as $key => $value) {
        $score[$key] = AWDO_2016_WATER_Threshold($value);
      }
    }
  } else if ($threshold == "AWDO_2016_WD_Threshold") {
    if (gettype($data) == 'array') {
      foreach ($data as $key => $value) {
        $score[$key] = AWDO_2016_WD_Threshold($value);
      }
    }
  } else if ($threshold == "LOGIC_DEDUCTION_LARGEST") {
    if (gettype($data) == 'array') {
      foreach ($data as $key => $value) {
        $score[$key] = LOGIC_DEDUCTION_LARGEST($value);
      }
    }
  } else if ($threshold == "DAMAGE_THREDSHOLD") {
    if (gettype($data) == 'array') {
      foreach ($data as $key => $value) {
        $score[$key] = DAMAGE_THREDSHOLD($value);
      }
    }
  } else if ($threshold == "ANY_MIN_HIGH") {
    if (gettype($data) == 'array') {
      foreach ($data as $key => $value) {
        $score[$key] = ANY_MIN_HIGH($value, $keys);
      }
    }
  } else if ($threshold == "ANY_MAX_HIGH") {
    if (gettype($data) == 'array') {
      foreach ($data as $key => $value) {
        $score[$key] = ANY_MAX_HIGH($value, $keys);
      }
    }
  } else if ($threshold == "COEFFICIENT_VARIATION") {
    if (gettype($data) == 'array') {
      foreach ($data as $key => $value) {
        $score[$key] = COEFFICIENT_VARIATION($value);
      }
    }
  } else if ($threshold == "DO_LEVEL") {
    if (gettype($data) == 'array') {
      foreach ($data as $key => $value) {
        $score[$key] = DO_LEVEL($value);
      }
    }
  }
  return $score;
}

function FalkenmarkThreshold($value)
{
  if ($value < 1000) {
    return 1;
  } else if ($value < 1450) {
    return 2;
  } else if ($value < 1600) {
    return 3;
  } else if ($value < 1700) {
    return 4;
  } else if ($value >= 1700) {
    return 5;
  }
}

function AWDO_2016_AG_Threshold($value, $scale = 1)
{
  if ($value < 0.1 * $scale) {
    return 1;
  } else if ($value < 0.2 * $scale) {
    return 2;
  } else if ($value < 0.35 * $scale) {
    return 3;
  } else if ($value < 1 * $scale) {
    return 4;
  } else if ($value >= 1 * $scale) {
    return 5;
  }
}

function AWDO_2016_NON_AG_Threshold($value)
{
  if ($value <= 2.1) {
    return 1;
  } else if ($value <= 5.5) {
    return 2;
  } else if ($value <= 20) {
    return 3;
  } else if ($value <= 50) {
    return 4;
  } else if ($value > 50) {
    return 5;
  }
}

function AWDO_2016_WATER_Threshold($value)
{
  if ($value < 10000) {
    return 1;
  } else if ($value < 25000) {
    return 2;
  } else if ($value < 50000) {
    return 3;
  } else if ($value < 100000) {
    return 4;
  } else if ($value >= 100000) {
    return 5;
  }
}

function LOGIC_DEDUCTION_LARGEST($value)
{
  if ($value < 0.5) {
    return 5;
  } else if ($value < 2) {
    return 4;
  } else if ($value < 10) {
    return 3;
  } else if ($value < 50) {
    return 2;
  } else if ($value >= 50) {
    return 1;
  }
}

function AWDO_2016_WD_Threshold($value)
{
  if ($value < 5) {
    return 5;
  } else if ($value <= 10) {
    return 4;
  } else if ($value <= 15) {
    return 3;
  } else if ($value <= 20) {
    return 2;
  } else if ($value > 20) {
    return 1;
  }
}

function DAMAGE_THREDSHOLD($value)
{
  if ($value < 5) {
    return 5;
  } else if ($value < 10) {
    return 4;
  } else if ($value < 20) {
    return 3;
  } else if ($value < 40) {
    return 2;
  } else if ($value >= 40) {
    return 1;
  }
}

function COEFFICIENT_VARIATION($value)
{
  if ($value < 0.06) {
    return 5;
  } else if ($value < 0.12) {
    return 4;
  } else if ($value < 0.18) {
    return 3;
  } else if ($value < 0.24) {
    return 2;
  } else if ($value >= 0.24) {
    return 1;
  }
}

function DO_LEVEL($value)
{
  if ($value < 2) {
    return 1;
  } else if ($value < 4) {
    return 2;
  } else if ($value < 5) {
    return 3;
  } else if ($value < 6) {
    return 4;
  } else if ($value >= 6) {
    return 5;
  }
}

function ANY_MIN_HIGH($value, $keys)
{
  if ($value < $keys[0]) {
    return 5;
  } else if ($value < $keys[1]) {
    return 4;
  } else if ($value < $keys[2]) {
    return 3;
  } else if ($value < $keys[3]) {
    return 2;
  } else if ($value >= $keys[4]) {
    return 1;
  }
}

function ANY_MAX_HIGH($value, $keys)
{
  if ($value < $keys[0]) {
    return 1;
  } else if ($value < $keys[1]) {
    return 2;
  } else if ($value < $keys[2]) {
    return 3;
  } else if ($value < $keys[3]) {
    return 4;
  } else if ($value >= $keys[4]) {
    return 5;
  }
}
