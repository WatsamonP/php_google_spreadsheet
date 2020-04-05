<?php

/*********************** */
// CALCULATION FUNCTIONS //
// FOR [[ WA1 ]]         //
/*********************** */
function getSurfaceRunoff($SpecificInputYears, $sumBasinArea, $annualAverageRainfall)
{
  $surfaceRunoff = [];
  foreach ($SpecificInputYears as $i => $item) {
    $surfaceRunoff[$i] = $item * $sumBasinArea * $annualAverageRainfall[$i] * pow(10, -3);
  }
  return $surfaceRunoff;
}
