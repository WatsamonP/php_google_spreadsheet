<?php
// Overview Words
$string_provinceName = "Name of the Province";
$string_provinceArea = "Total Area in each Province";
$string_provinceAreaUnit = "Province Area Unit";
$string_basinArea = "Area in the basin";
$string_basinAreaUnit = "Basin Area Unit";
$string_ratio = "Ratio";
$string_BasinArea = "Basin Area";

function toSup(String $str)
{
    $str = str_replace(['2', '3'], ['<sup>2</sup>', '<sup>3</sup>'], $str);
    return "<a>" . $str . "</a>";
}
