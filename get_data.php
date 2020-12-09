<?php
// required headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
  
include_once 'Calculation.php'; 

$data = json_decode(file_get_contents('php://input'));

$aLat = trim($data->aLat);
$aLong = trim($data->aLong);
$bLat = trim($data->bLat);
$bLong = trim($data->bLong);

$ok = true;
if (!is_numeric($aLat) || ($aLat < -90 || $aLat > 90)) $ok = false;
if (!is_numeric($bLat) || ($bLat < -90 || $bLat > 90)) $ok = false;
if (!is_numeric($aLong) || ($aLong < -180 || $aLong > 180)) $ok = false;
if (!is_numeric($bLong) || ($bLong < -180 || $bLong > 180)) $ok = false;

if (!$ok) {
	$data_error = 'Latitude must be between -90 and 90 and longitude must be between -180 and 180.';
	$data_arr = array(
		'error' =>  $data_error
	);	

	http_response_code(200);
	echo json_encode($data_arr);
	
} else {

	$calc = new Calculation($aLat, $aLong, $bLat, $bLong);
	$calc->calcCost();
	
	$data_arr = array(
		'ok' => 'ok',
		'cLat' => $calc->get_cLat(),
		'cLong' => $calc->get_cLong(),
		'dLat' => $calc->get_dLat(),
		'dLong' => $calc->get_dLong(),
		'perimeter' => $calc->get_perimeter(),
		'area' => $calc->get_area(),
		'cost' => $calc->get_cost()
	);

	http_response_code(200);
	echo json_encode($data_arr);
}

?>