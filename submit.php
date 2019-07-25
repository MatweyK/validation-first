<?php

$postdata = file_get_contents("php://input");
// $request = json_decode($postdata, true);
if (isset($postdata) && !empty($postdata)) {
  $request = json_decode($postdata, TRUE);
}
//////////////////////////////////////////////
//VALIDATE QUARTALS
$firstQuartal = [];
$secondQuartal = [];
$thirdQuartal = [];
$fourthQuartal = [];
foreach ($request as $value) {
  foreach ($value as $key => $valueTwo) {
    if ($key == 'rows') {
      
      foreach ($valueTwo as $keyThree => $valueThree) {


      	$qOne = [];
        $qTwo = [];
        $qThree = [];
        $qFour = [];
      	foreach ($valueThree as $key => $valueFour) {

      		
     	if (($key == 'jan')|| ($key == 'feb') || ($key == 'mar')){
      			$qOne[] = floatval($valueFour ?? 0);
      		}
      		if (($key == 'apr')|| ($key == 'may') || ($key == 'jun')){
      			$qTwo[] = floatval($valueFour ?? 0);
      		}
      		if (($key == 'jul')|| ($key == 'aug') || ($key == 'sep')){
      			$qThree[] = floatval($valueFour ?? 0);
      		}
      		if (($key == 'oct')|| ($key == 'nov') || ($key == 'dec')){
      			$qFour[] = floatval($valueFour ?? 0);
      		}

      	}

        $firstQuartal[] = $qOne;
        $secondQuartal[] = $qTwo;
        $thirdQuartal[] = $qThree;
        $fourthQuartal[] = $qFour;
       //  // print_r(validateQuartals($firstQuartal));
       //  // print_r($valueThree['q1']);

       //  if (abs(validateQuartals($firstQuartal) - $valueThree['q1']) < 0.05){
       //  	return http_response_code(400);
       //  }
       //  if (abs(validateQuartals($firstQuartal) - $valueThree['q2']) < 0.05){
       //  	return http_response_code(400);
       //  }
       //  if (abs(validateQuartals($firstQuartal) - $valueThree['q3']) < 0.05){
       //  	return http_response_code(400);
       //  }
       //  if (abs(validateQuartals($firstQuartal) - $valueThree['q4']) < 0.05){
       //  	return http_response_code(400);
       //  }
       //  validateYtd($valueThree['ytd']);
       
     }
    }
  }
}
print_r($firstQuartal);

// function validateQuartals(array $arr){
// 	$sum = 0.0;
// 	foreach ($arr as $key => $value) {
// 		foreach ($value as $key => $value) {
// 			$sum += $value;
// 		}
// 	}
// 	$sum += 1;
// 	return $sum != 1 ? round(($sum / 3),2) : 0;

// }
// function validateYtd($ytd){
//   if (abs((validateQuartals($firstQuartal) 
//   		+ validateQuartals($secondQuartal) 
//   		+ validateQuartals($thirdQuartal) 
//   		+ validateQuartals($fourthQuartal) 
//   		+1)/4 ?? 0 - $ytd) < 0.05)
//   {
//  	return http_response_code(400);
//   }
//   return true;
// }

//////////////////////////////////////////////
//  VALIDATE YEARS
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
      return http_response_code(400);////???????????????????
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

if (isEqual($startPos) && isEqual($endPos) 
	// && validateYtd()
) {
  http_response_code(200);
  echo json_encode($request);
}
else {
  http_response_code(400);
}
