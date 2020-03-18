<?php

function getAverageColumns($arrays)
{
  $resList = [];
  $length = sizeof($arrays);
  foreach ($arrays as $array) {
    foreach ($array as $key => $value) {
      if (!isset($resList[$key])) {
        $resList[$key] = $value / $length;
      } else {
        $resList[$key] += $value / $length;
      }
    }
  }
  return $resList;
}

function getRatio($provincesData, $string_provinceArea, $string_basinArea)
{
  $ratio = [];
  foreach ($provincesData as $key => $province) {
    $ratio[$key] = $province[$string_basinArea] / $province[$string_provinceArea];
  }

  return $ratio;
}

function getSumBasinArea($provincesData, $string_basinArea)
{
  $sumBasinArea = 0;
  foreach ($provincesData as $province) {
    $sumBasinArea += $province[$string_basinArea];
  }

  return $sumBasinArea;
}

///////////////////////////////////////////////////////////////////////////

function divideTwoArray($arr1, $arr2)
{
  $result = [];
  foreach ($arr1 as $i => $value1) {
    if ($arr2[$i] == 0) {
      $result[$i] = 0;
      include_once __DIR__ . "./../../templates/alert/division_by_zero.php"; // CALL ALERT
    } else {
      $result[$i] = $value1 / $arr2[$i];
    }
  }

  return $result;
}

function multTwoArray($arr1, $arr2)
{
  $result = [];
  foreach ($arr1 as $i => $value1) {
    $result[$i] = $value1 * $arr2[$i];
  }

  return $result;
}

function sumArrays($arraysSet)
{
  $result = [];
  foreach ($arraysSet as $i => $array) {
    foreach ($array as $key => $value) {
      if (!isset($result[$key])) {
        $result[$key] = $value;
      } else {
        $result[$key] += $value;
      }
    }
  }
  return $result;
}

function sumColumnCal($dataset, $dimen, $group)
{
  $data = array();
  foreach ($dataset[$dimen][$group]['data'] as $subArray) {
    foreach ($subArray['cal_table'] as $key => $value) {
      $data[$key] = isset($data[$key]) ?  $value + $data[$key] : $value;
    }
  }
  return $data;
}

function sumColumnTable($dataset, $dimen, $group)
{
  $data = array();
  foreach ($dataset[$dimen][$group]['data'] as $subArray) {
    foreach ($subArray['table'] as $key => $value) {
      $data[$key] = isset($data[$key]) ?  $value + $data[$key] : $value;
    }
  }
  return $data;
}

function startsWithNumber($string)
{
  return strlen($string) > 0 && ctype_digit(substr($string, 0, 1));
}
