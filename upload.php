<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["csv"]["name"]);

// Check if image file is a actual image or fake image
if(isset($_FILES)) {
    // Allow certain file formats
    $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
    
    if(in_array($_FILES['csv']['type'], $mimes)){
        if (move_uploaded_file($_FILES["csv"]["tmp_name"], $target_file)) {
            $data = getJsonFromCsv($target_file, "r+");
            saveAsJson($data);
            // echo "The file ". basename( $_FILES["csv"]["name"]). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        die("Попробуйте другой файл");
    }
    
} else {
    die("Вернитесь на страницу назад");
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
        die("Файл загруженый вами неверного формата!");
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
            $arr[$i][$j] = $lineArray[$j]; 
        } 
        $i++; 
        } 
        fclose($handle); 
    } 
    return $arr; 
} 

function saveAsJson($data) {
    $fp = fopen('data.json', 'w');
    fwrite($fp, json_encode($data));
    fclose($fp);
    echo "Файл загружен, успешно!";
}
?>