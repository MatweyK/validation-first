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
  if ($value != '') {
    $filteredArr =array_filter($value);
	$arrays =$filteredArr;
	$filteredArr=array();
	$i=0;
	foreach($arrays as $k => $item){
	  $filteredArr[$i]=$item;
	  unset($arrays[$k]);
	  $i++;
	}
  }
	$KeysArr = array_keys($filteredArr);
    $startPos[] = $KeysArr[0];
    $endPos[] = $KeysArr[count($KeysArr) - 1];
}
function isEqual(array $arr) {
  $a = null;
  foreach ($arr as $key => $value) {
    if ($a == null)  {
      $a = $value;
      continue;
    }
    if ($a != $value)  {
      return false;
    }
  }
  return true;
}
//////////////////////////////////////////////
//VALIDATE QUARTALS
foreach ($request as $value) {
  foreach ($value as $key => $valueTwo) {
    if ($key == 'rows') {
      foreach ($valueTwo as $keyThree => $valueThree) {
        if ($valueThree['q1'] != 0) {
          if ((abs($valueThree['jan'] ?? 0 + $valueThree['feb'] ?? 0 + $valueThree['mar'] ?? 0)+1)/3 - $valueThree['q1'] > 0.005) {
            return http_response_code(400);
          }
        }
        if ($valueThree['q2'] != 0) {
          if ((abs($valueThree['apr'] ?? 0 + $valueThree['may'] ?? 0 + $valueThree['jun'] ?? 0)+1)/3 - $valueThree['q2'] > 0.005) {
            return http_response_code(400);
          }
        }
        if ($valueThree['q3'] != 0) {
          if ((abs($valueThree['jul'] ?? 0 + $valueThree['aug'] ?? 0 + $valueThree['sep'] ?? 0)+1)/3 - $valueThree['q3'] > 0.005) {
            return http_response_code(400);
          }
        }
        if ($valueThree['q4'] != 0) {
          if ((abs($valueThree['oct'] ?? 0 + $valueThree['nov'] ?? 0 + $valueThree['dec'] ?? 0)+1)/3 - $valueThree['q4'] > 0.005) {
            return http_response_code(400);
          }
        }
        if ($valueThree['ytd'] != 0) {
          if ((abs($valueThree['q1'] + $valueThree['q2'] + $valueThree['q3']+ $valueThree['q4'])+1)/4 - $valueThree['ytd'] > 0.005){
          return http_response_code(400);
          }
        }
        
      }
    }
  }
}
if (isEqual($startPos) && isEqual($endPos)
	// && validateYtd()
) {
  http_response_code(200);
  echo json_encode($request);
}
else {
  http_response_code(400);
}
