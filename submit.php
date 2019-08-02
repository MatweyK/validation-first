<?php

$postdata = file_get_contents("php://input");
// $request = json_decode($postdata, true);
if (isset($postdata) && !empty($postdata)) {
  $request = json_decode($postdata, TRUE);
}
//////////////////////////////////////////////
//VALIDATE YEARS
$yearDataArray = [];
foreach ($request as $value) {
  foreach ($value as $key => $valueTwo) {
    if ($key == 'rows') {
      $newArr = [];
      foreach ($valueTwo as $keyThree => $valueThree) {
        $newArr[] = $valueThree['currentYear'];
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
      return http_response_code(400);
    }
  }
}
// unset($yearDataArray);????
/////////////////////////////////////
//VALIDATE ROWS
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
//Check for gap in all rows
foreach ($rowsDataArray as $value) {
  $start = FALSE;
  $finish = FALSE;;
  foreach ($value as $k => $v) {
    if ($v != "") {
      $start = TRUE;
      if ($finish) {
        return http_response_code(401);
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
  if ($value != '') {
    $filteredArr = array_filter($value, function($name) { return $name === '0' ? true : !empty($name); });
	$arrays =$filteredArr;
	$filteredArr=array();
	$i=0;
	foreach($arrays as $k => $item){
	  $filteredArr[$i]=$item;
	  unset($arrays[$k]);
	  $i++;
	}
  }
	$keysArr = array_keys($filteredArr);



  if (count($keysArr) > 0) {
    $startPos[] = $keysArr[0];
    $endPos[] = $keysArr[count($keysArr) - 1];
  }
  else{
    $startPos[] = 0;
    $endPos[] = 0;
  }
    
}
// unset($rowsDataArray);
// unset($startPos);
// unset($endPos);
//////////////////////////////////////////////
//VALIDATE QUARTALS
foreach ($request as $value) {
  foreach ($value as $key => $valueTwo) {
    if ($key == 'rows') {
      foreach ($valueTwo as $keyThree => $valueThree) {
        if ($valueThree['q1'] != 0) {
          if ((abs($valueThree['jan'] ?? 0 + $valueThree['feb'] ?? 0 + $valueThree['mar'] ?? 0)+1)/3 - $valueThree['q1'] > 0.05) {
            return http_response_code(400);
          }
        }
        if ($valueThree['q2'] != 0) {
          if ((abs($valueThree['apr'] ?? 0 + $valueThree['may'] ?? 0 + $valueThree['jun'] ?? 0)+1)/3 - $valueThree['q2'] > 0.05) {
            return http_response_code(400);
          }
        }
        if ($valueThree['q3'] != 0) {
          if ((abs($valueThree['jul'] ?? 0 + $valueThree['aug'] ?? 0 + $valueThree['sep'] ?? 0)+1)/3 - $valueThree['q3'] > 0.05) {
            return http_response_code(400);
          }
        }
        if ($valueThree['q4'] != 0) {
          if ((abs($valueThree['oct'] ?? 0 + $valueThree['nov'] ?? 0 + $valueThree['dec'] ?? 0)+1)/3 - $valueThree['q4'] > 0.05) {
            return http_response_code(400);
          }
        }
        if ($valueThree['ytd'] != 0) {
          if ((abs($valueThree['q1'] + $valueThree['q2'] + $valueThree['q3']+ $valueThree['q4'])+1)/4 - $valueThree['ytd'] > 0.05){
          return http_response_code(400);
          }
        }
        
      }
    }
  }
}

if ((count(array_unique($startPos)) === 1) && (count(array_unique($endPos)) === 1)) {
  http_response_code(200);
  echo json_encode($request);
}
else {
  http_response_code(402);
}
