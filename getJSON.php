<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("HTTP/1.1 200 OK");

$modelName = urldecode($_REQUEST['modelName']);
$string = file_get_contents("data.json");
$json_a = json_decode(json_decode($string, true));

if (isset($_REQUEST['modelName']) && !empty($_REQUEST['modelName'])) {
    $newArray = [];
    switch ($modelName) {
        case 'all': {
            foreach ($json_a as $row) {
                array_push($newArray, $row);
            }
            break;
        }    
        default: {
            foreach ($json_a as $row) {
                if ($row->Model == $modelName) {
                    array_push($newArray, $row);
                }
            }
            break;
        }
    }

    $status = count($newArray);

    // если последний залитый цсв говно жопа взять стандартный

    if (!$status) {
        $string = file_get_contents("defaultData.json");
        $json_a = json_decode(json_decode($string, true));
        $newArray = [];
        switch ($modelName) {
            case 'all': {
                foreach ($json_a as $row) {
                    array_push($newArray, $row);
                }
                break;
            }    
            default: {
                foreach ($json_a as $row) {
                    if ($row->Model == $modelName) {
                        array_push($newArray, $row);
                    }
                }
                break;
            }
        }
        $status = count($newArray);
    }

    $data = [ 'status' => $status ? $status : 'Error' , 'response' => $status ? $newArray : 'По запросу "'.$modelName.'" ничего не найдено. Пожалуйста позвоните менеджеру для уточнения услуг и цены по вашему устройству.' ];

    echo json_encode($data);
} else {
    $data = [ 'status' => 'Error', 'response' => 'Please specify "modelName" variable' ];
    echo json_encode($data);
    exit();
}

?>