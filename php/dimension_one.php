<?php
require_once __DIR__ . "./../config.php";
require_once __DIR__ . "./../php/overview.php";
require_once __DIR__ . "./../constants/keys.php";
require_once __DIR__ . "./../constants/word.php";
// function
require_once __DIR__ . "./../php/calculation/utils.php";
require_once __DIR__ . "./../php/calculation/getCalculationWA.php";
require_once __DIR__ . "./../php/calculation/getScore.php";
//
require_once __DIR__ . "./../php/fetch/fetchWX.php";
require_once __DIR__ . "./../php/fetch/fetchWeightKey.php";
require_once __DIR__ . "./../php/fetch/fetchSpecificInputYears.php";
require_once __DIR__ . "./../php/fetch/fetchSpecificInput.php";

/************************************ */
// CALCULATE RATIO AND SUM BASIN AREA //
/************************************ */
$ratio = getRatio($provincesData, $string_provinceArea, $string_basinArea);
$sumBasinArea = getSumBasinArea($provincesData, $string_basinArea);

/***************************** */
// Pull Data for WA from sheet //
/***************************** */
$range = $WA_SHEET;
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$array = $response->getValues();
$WA_SET = getWxData($array, $ratio)["SET"];
$YEAR_RANGE = getWxData($array, $ratio)["YEAR_RANGE"];

/*********************************** */
// GET SPECIAL INPUT FOR CALCULATION //
/*********************************** */
$rangeSI = $SPECIFIC_INPUT_YEARS_SHEET;
$responseSI = $service->spreadsheets_values->get($spreadsheetId, $rangeSI);
$arraySI = $responseSI->getValues();
$SpecificInputYears = getSpecificInputYears($arraySI);
///////////////////
$rangeVS = $SPECIFIC_INPUT_SHEET;
$responseVS = $service->spreadsheets_values->get($spreadsheetId, $rangeVS);
$arrayVS = $responseVS->getValues();
$SpecificInput = getSpecificInput($arrayVS);


//////////////////////////////////////////////// 
// █▀▀ ▄▀█ █░░ █▀▀ █░█ █░░ ▄▀█ ▀█▀ █ █▀█ █▄░█ //
// █▄▄ █▀█ █▄▄ █▄▄ █▄█ █▄▄ █▀█ ░█░ █ █▄█ █░▀█ //
////////////////////////////////////////////////

/********************** */
// SCORE TABLE FOR WA11 //
/********************** */
$surfaceRunoff = getSurfaceRunoff($SpecificInputYears['WA1']['WA11']['runoffCoeff']['table'], $sumBasinArea);
$population = sumColumnCal($WA_SET, 'WA1', 'WA11');
$waterAvailabilityWA1 = getWaterAvailability($surfaceRunoff, $population);

$WA11_TB = array(
  "runoffCoeff" => array('key' => "Runoff Coeff.", 'table' => $SpecificInputYears['WA1']['WA11']['runoffCoeff']['table']),
  // "basinArea" => array('key' => 'Basin Area', 'table' => array_fill(0, sizeof($YEAR_RANGE), $sumBasinArea)),
  // "surfaceRunoff" => array('key' => 'Surface Runoff', 'table' => $surfaceRunoff),
  // "population" => array('key' => "Population", 'table' => $population),
  // "waterAvailabilityWA1" => array('key' => 'Water Availability (WA1)', 'table' => $waterAvailabilityWA1),
  "score" => array('key' => "Score", 'table' => getScore($waterAvailabilityWA1, $FalkenmarkThreshold))
);

/********************** */
// SCORE TABLE FOR WA12 //
/********************** */
$groundwaterConsumption = sumColumnCal($WA_SET, 'WA1', "WA12");
$groundwaterAvailabilityWA2 = getGroundwaterAvailability($groundwaterConsumption, $population);

$WA12_TB = array(
  // "groundwaterConsumption" => array('key' => "Groundwater Consumption", 'table' => $groundwaterConsumption),
  // "population" => array('key' => 'Population', 'table' => $population),
  // "groundwaterAvailabilityWA2" => array('key' => 'Groundwater Availability (WA2)', 'table' => $groundwaterAvailabilityWA2),
  "score" => array('key' => "Score", 'table' => getScore($groundwaterAvailabilityWA2, $FalkenmarkThreshold))
);

/********************** */
// SCORE TABLE FOR WA21 //
/********************** */
$totalWaterWithdrawalThroughIBT = sumColumnCal($WA_SET, 'WA2', "WA21_A");
$totalWaterWithdrawals =  sumColumnCal($WA_SET, 'WA2', "WA21_B");
$_WA21 = divideTwoArray($totalWaterWithdrawalThroughIBT, $totalWaterWithdrawals);
$WA21_TB = array(
  // "totalWaterWithdrawalThroughIBT" => array('key' => "Total water withdrawal through IBT", 'table' => $totalWaterWithdrawalThroughIBT),
  // "totalWaterWithdrawals" => array('key' => "Total water withdrawals", 'table' => $totalWaterWithdrawals),
  // "WA21" => array('key' => "WA21", 'table' => $_WA21),
  "score" => array('key' => "Score", 'table' => getScore($_WA21, $FalkenmarkThreshold))
);

/********************** */
// SCORE TABLE FOR WA31 //
/********************** */
$totalTreatedWastewaterVolume = sumColumnCal($WA_SET, 'WA3', "WA31_A");
$totalWaterDemand = sumColumnCal($WA_SET, 'WA3', "WA31_B");
$_WA31 = divideTwoArray($totalTreatedWastewaterVolume, $totalWaterDemand);
$WA31_TB = array(
  // "totalTreatedWastewaterVolume" => array('key' => "Total treated wastewater volume", 'table' => $totalTreatedWastewaterVolume),
  // "totalWaterDemand" => array('key' => "Total water demand", 'table' => $totalWaterDemand),
  // "WA31" => array('key' => "WA31", 'table' => $_WA31),
  "score" => array('key' => "Score", 'table' => getScore($_WA31, $FalkenmarkThreshold))
);

/********************** */
// SCORE TABLE FOR WA41 //
/********************** */
$totalFreshwaterWithdrawal = sumColumnCal($WA_SET, 'WA4', "WA41_A");
$totalAvailableFreshwater = sumColumnCal($WA_SET, 'WA4', "WA41_B");
$_WA41 = divideTwoArray($totalFreshwaterWithdrawal, $totalAvailableFreshwater);
$WA41_TB = array(
  // "totalFreshwaterWithdrawal" => array('key' => "Total Freshwater withdrawal", 'table' => $totalFreshwaterWithdrawal),
  // "totalAvailableFreshwater" => array('key' => "Total available freshwater", 'table' => $totalAvailableFreshwater),
  // "WA41" => array('key' => "WA41", 'table' => $_WA41),
  "score" => array('key' => "Score", 'table' => getScore($_WA41, $FalkenmarkThreshold))
);

/********************** */
// SCORE TABLE FOR WA51 //
/********************** */
$totalImportedWaterVolume = sumColumnCal($WA_SET, 'WA5', "WA51");
// $totalWaterDemand already define at WA21 
$_WA51 = divideTwoArray($totalImportedWaterVolume, $totalWaterDemand);
$WA51_TB = array(
  // "totalImportedWaterVolume" => array('key' => "Total imported water volume", 'table' => $totalImportedWaterVolume),
  // "totalWaterDemand" => array('key' => "Total water demand", 'table' => $totalWaterDemand),
  // "WA51" => array('key' => "WA51", 'table' => $_WA51),
  "score" => array('key' => "Score", 'table' => getScore($_WA51, $FalkenmarkThreshold))
);

/********************** */
// SCORE TABLE FOR WA61 //
/********************** */
$totalHarvestedRainwaterInTheBasin = sumColumnCal($WA_SET, 'WA6', "WA61");
// $totalWaterDemand already define at WA21 
$_WA61 = divideTwoArray($totalHarvestedRainwaterInTheBasin, $totalWaterDemand);
$WA61_TB = array(
  // "totalHarvestedRainwaterInTheBasin" => array('key' => "Total harvested rainwater in the basin", 'table' => $totalHarvestedRainwaterInTheBasin),
  // "totalWaterDemand" => array('key' => "Total water demand", 'table' => $totalWaterDemand),
  // "WA61" => array('key' => "WA61", 'table' => $_WA61),
  "score" => array('key' => "Score", 'table' => getScore($_WA61, $FalkenmarkThreshold))
);

/********************** */
// SCORE TABLE FOR WA62 //
/********************** */
// NOTE THAT sumColumnCal() cannot be use here 
$_WA62_SUM = 0;
foreach ($SpecificInput['WA6']['WA62'] as $key => $items) {
  if ($key == 'reservoirs') {
    foreach ($items as $reservoir) {
      $_WA62_SUM += $reservoir['value'];
    }
  }
}
$reservoirCapacityPerArea = $_WA62_SUM / $sumBasinArea;
$WA62_TB = array(
  // "reservoirCapacityPerArea" => array('key' => "Reservoir Capacity per area", 'table' => $reservoirCapacityPerArea),
  "score" => array('key' => "Score", 'table' => getScore($reservoirCapacityPerArea, $AWDO_2016_AG_Threshold))
);

/****************************** */
// PULL DATA from WEIGHT SHEET //
/****************************** */
$rangeWeightKeys = $WEIGHT_KEY_SHEET;
$responseSK = $service->spreadsheets_values->get($spreadsheetId, $rangeWeightKeys);
$arraySK = $responseSK->getValues();
$WeightKeysData = getWeightKey($arraySK);
$WA_SET = combineWeightKey($WA_SET, $WeightKeysData);

/************************************ */
// CONSTRUCT FINAL SCORE (below page) //
/************************************ */
// NOTE THAT
// Use calTwoSubGroup() in case of you have (WA11*weight11 WA12*weight12)
$FIRST_YEAR = array_slice($YEAR_RANGE, 0, 1)[0];
$TEMP_WA62_SCORE = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), $WA62_TB['score']['table']);
////////////////////////
$FINAL_SCORE_WA = array(
  'WA1' => calTwoSubGroup(['WA11' => $WA11_TB, 'WA12' => $WA12_TB], $WA_SET['WA1'], $WeightKeysData),
  'WA2' => getWeightedValue(['WA21' => $WA21_TB['score']['table']], $WeightKeysData),
  'WA3' => getWeightedValue(['WA21' => $WA31_TB['score']['table']], $WeightKeysData),
  'WA4' => getWeightedValue(['WA21' => $WA41_TB['score']['table']], $WeightKeysData),
  'WA5' => getWeightedValue(['WA21' => $WA51_TB['score']['table']], $WeightKeysData),
  'WA6' => getWeightedValue(['WA61' => $WA61_TB['score']['table'], 'WA62' => $TEMP_WA62_SCORE], $WeightKeysData)
);

$FINAL_INDICATOR_WA = getWeightedValue($FINAL_SCORE_WA, $WeightKeysData);
// print_r($SpecificInputYears);
