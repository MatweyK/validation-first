<?php

$postdata = file_get_contents("php://input");
// $request = json_decode($postdata, true);
if (isset($postdata) && !empty($postdata)) {
  $request = json_decode($postdata, TRUE);
}
//////////////////////////////////////////////
//  VALIDATE YEARS
$yearDataArray = [];

foreach ($request as $value) {
  foreach ($value as $key => $value2) {
    if ($key == 'rows') {
      $newArr = [];
      foreach ($value2 as $key3 => $value3) {
        $newArr[] = $value3['currentYear'];
      }
      $yearDataArray[] = $newArr;
    }
  }
}

foreach ($yearDataArray as $value) {
  if (count($value) > 1) {
    $arrayLength = count($value);
    $arrayLastIndex = $value[$arrayLength - 1];
    $rangedArr = range(2019, $arrayLastIndex);

    if ($value != $rangedArr) {
      return http_response_code(400);////???????????????????
    }
  }
}
/////////////////////////////////////
//VALIDATE ALL ROWS GAP TOGETHER
$rowsDataArray = [];

foreach ($request as $value) {
  foreach ($value as $key1 => $value1) {
    if ($key1 == 'rows') {
      $reversedValue1 = array_reverse($value1);
      $newArr = [];
      foreach ($reversedValue1 as $key2 => $value2) {
        foreach ($value2 as $key3 => $value3) {
          if (($key3 == 'currentYear') || ($key3 == 'q1') || ($key3 == 'q2') || ($key3 == 'q3') || ($key3 == 'q4') || ($key3 == 'ytd')) {
            continue;
          }
          $newArr[] = $value3;
        }
      }
      $rowsDataArray[] = $newArr;
    }
  }
}

foreach ($rowsDataArray as $value) {
  $start = FALSE;
  $finish = FALSE;;
  foreach ($value as $k => $v) {
    if ($v != "") {
      $start = TRUE;
      if ($finish) {
        return http_response_code(400);
      }
    }
    else {
      if (!$start) {
        $start = FALSE;
      }
      else {
        $finish = TRUE;
      }
    }
  }
}
////////////////////////////////////////////////////
//VALIDATE TABLES DIFF
$startPos = [];
$endPos = [];
foreach ($rowsDataArray as $key => $value) {

  $f = array_filter($value);

  $r = array_keys($f);
  $startPos[] = $r[0];
  $endPos[] = $r[count($r) - 1];
}

function isEqual(array $arr) {
  $a = NULL;
  foreach ($arr as $value) {
    if ($a == NULL) {
      $a = $value;
      continue;
    }
    if ($a != $value) {
      return FALSE;
    }
  }
  return TRUE;
}

if (isEqual($startPos) && isEqual($endPos)) {
  http_response_code(200);
  echo json_encode($request);
}
else {
  http_response_code(400);
}
