<?php
function getInterpretation($data)
{
  $score = [];
  if (gettype($data) == 'array') {
    foreach ($data as $key => $value) {
      $score[$key] = interpretation($value);
    }
  } else {
    return interpretation($data);
  }
  return $score;
}

function interpretation($value)
{
  if ($value < 1.5) {
    return "<strong><a class='text-danger'>Poor water Security</strong></a>";
  } else if ($value <= 2.5) {
    return "<strong><a class='text-warning'>Low water Security</strong></a>";
  } else if ($value <= 3.5) {
    return "<strong><a class='text-success'>Good water Security</strong></a>";
  } else if ($value <= 4.5) {
    return "<strong><a class='text-info'>Very Good water Security</strong></a>";
  } else if ($value > 4.5) {
    return "<strong><a class='text-primary'>Ideal water Security</strong></a>";
  }
}

function interpretationColor($value)
{
  if ($value < 1.5) {
    return "danger";
  } else if ($value <= 2.5) {
    return "warning";
  } else if ($value <= 3.5) {
    return "success";
  } else if ($value <= 4.5) {
    return "info";
  } else if ($value > 4.5) {
    return "primary";
  }
}
