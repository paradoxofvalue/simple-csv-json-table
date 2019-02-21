<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$modelName = $_REQUEST['modelName'];
$string = file_get_contents("data.json");
$json_a = json_decode(json_decode($string, true));

$newArray = [];

foreach ($json_a as $row) {
    if ($row->Model == $modelName) {
        array_push($newArray, $row);
    }
}
// $newArray = array_filter($json_a, function ($row) use ($modelName) {
//     return $row->Model === $modelName;
// });

$status = count($newArray);

$data = [ 'status' => $status, 'response' => $newArray ];

echo json_encode($data);
?>