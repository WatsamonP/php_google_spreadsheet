<?php

/*********************** */
// CALCULATION FUNCTIONS //
// FOR [[ WA1 ]]         //
/*********************** */
function getSurfaceRunoff($SpecificInputYears, $sumBasinArea)
{
  $surfaceRunoff = [];
  foreach ($SpecificInputYears as $i => $item) {
    $surfaceRunoff[$i] = $item * $sumBasinArea * $SpecificInputYears[$i] * pow(10, -3);
  }
  return $surfaceRunoff;
}

function getWaterAvailability($surfaceRunoff, $population)
{
  $waterAvailability = [];
  foreach ($surfaceRunoff as $key => $item) {
    if ($population[$key] == 0) {
      $waterAvailability[$key] = 0;
      $LOCATION = "'population' variable";
      include_once __DIR__ . "./../../templates/alert/division_by_zero.php"; // CALL ALERT
    } else {
      $waterAvailability[$key] = $item * pow(10, 6) / $population[$key];  // <<= CALCULATION
    }
  }
  return $waterAvailability;
}

function getGroundwaterAvailability($groundwaterConsumption, $population)
{
  $groundwaterAvailability = [];
  foreach ($groundwaterConsumption as $key => $item) {
    if ($population[$key] == 0) {
      $groundwaterAvailability[$key] = 0;
      $LOCATION = "'population' variable";
      include_once __DIR__ . "./../../templates/alert/division_by_zero.php"; // CALL ALERT
    } else {
      $groundwaterAvailability[$key] = $item / $population[$key]; // <<= CALCULATION
    }
  }
  return $groundwaterAvailability;
}
