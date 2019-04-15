<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("HTTP/1.1 200 OK");

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["csv"]["name"]);

$path = $_FILES['file']['name']; 
$ext = pathinfo($path, PATHINFO_EXTENSION);
$target_file = "uploads/" . basename($_FILES["csv"]["name"]) . '-' . time() . '.' . $ext;

$response = [];

if(isset($_POST) && isset($_FILES)) {
    // Allow certain file formats
    $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
    
    if(in_array($_FILES['csv']['type'], $mimes)){

        if (move_uploaded_file($_FILES["csv"]["tmp_name"], $target_file)) {

            $data = getJsonFromCsv($target_file, "r+");

            if (saveAsJson($data)) {
                $response = ['status' => "Success", 'message' => "Файл успешно загружен!"];
                echo json_encode($response);
                exit();
            } else {
                $response = ['status' => "Error", 'message' => "Не удалось сохранить файл. Попробуйте еще раз немного позже."];
                echo json_encode($response);
                exit();
            }
        } else {
            $response = ['status' => "Error", 'message' => "Во время загрузки файла, произошла ошибка. Попробуйте еще раз!"];
            echo json_encode($response);
            exit();
        }
    } else {
        $response = ['status' => "Error", 'message' => "Попробуйте другой файл"];
        echo json_encode($response);
        exit();
    }
    
} else {
    $response = ['status' => "Error", 'message' => "Извините, вы что-то хотели?"];
    echo json_encode($response);
    exit();
}

function getJsonFromCsv($feed) {
    $keys = array();
    $newArray = array();

    // Do it
    $data = csvToArray($feed, ',');
    // Set number of elements (minus 1 because we shift off the first row)
    $count = count($data) - 1;
    
    //Use first row for names  
    $labels = array_shift($data);
    if (isValidCsv($labels)) {
        foreach ($labels as $label) {
            $keys[] = $label;
        }
        // Add Ids, just in case we want them later
        $keys[] = 'id';
        for ($i = 0; $i < $count; $i++) {
            $data[$i][] = $i;
        }
        
        // Bring it all together
        for ($j = 0; $j < $count; $j++) {
            $d = array_combine($keys, $data[$j]);
            $newArray[$j] = $d;
        }
        // Print it out as JSON
        // echo json_encode($newArray);
        return json_encode($newArray);
    } else {
        $response = ['status' => "Error", 'message' => "Файл загруженый вами, неверного формата!"];
        echo json_encode($response);
        exit();
    }
}

function isValidCsv($labels) {
    return $labels == ['Model', 'Service', 'Price', 'Warranty'];
}

// Function to convert CSV into associative array
function csvToArray($file, $delimiter) {
    $arr = [];
    if (($handle = fopen($file, 'r')) !== FALSE) { 
        $i = 0; 
        while (($lineArray = fgetcsv($handle, 4000, $delimiter, '"')) !== FALSE) { 
            for ($j = 0; $j < count($lineArray); $j++) { 
                $arr[$i][$j] = trim($lineArray[$j]); 
            } 
            $i++; 
        } 
        fclose($handle); 
    } 
    return $arr; 
} 

function saveAsJson($data) {
    try {
        $fp = fopen('data.json', 'w');
        fwrite($fp, json_encode($data));
        fclose($fp);
        return true;
    } catch (\Throwable $th) {
        return false;
    }   
}
?>
