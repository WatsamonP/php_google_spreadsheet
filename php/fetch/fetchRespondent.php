<?php

function getRespondentList($array)
{
  $List = [];
  if (empty($array)) {
    echo "<p>No data Found</p>";
  } else {
  }
  foreach ($array as $row) {
    foreach ($row as $i => $item) {
      $dimen = $row[0];
      $subgroup = $row[2];
      $id = $row[3];
      $name = $row[4];
      if (startsWithNumber($item)) {
        $List[$dimen][$subgroup][$id]['score']['Q' . (int) ($i - 4)] = $item;
      } else {
        $List[$dimen][$subgroup][$id]['id'] = $id;
        $List[$dimen][$subgroup][$id]['name'] = $name;
      }
    }
  }
  return $List;
}

function getAverageForeachQuestion($RespondentList)
{
  $avgList = [];
  $length = sizeof($RespondentList);
  foreach ($RespondentList as $st) {
    foreach ($st['score'] as $q => $value) {
      if (!isset($avgList[$q])) {
        $avgList[$q] = $value / $length;
      } else {
        $avgList[$q] += $value / $length;
      }
    }
  }
  return $avgList;
}
