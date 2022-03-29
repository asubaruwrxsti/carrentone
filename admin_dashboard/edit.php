<?php

$car_name = $_GET['car_name'];
$info_dir = "../assets/car_info/info.txt";

function findLine($car_name, $info_dir)
{
    $info_array = file($info_dir);
    $line_num = 0;
    foreach ($info_array as $line) {
        if (strpos($line, $car_name) !== false) {
            return $line_num;
        }
        $line_num++;
    }
    return $line_num;
}

function editTextarea($car_name, $info_dir)
{
    $line_num = findLine($car_name, $info_dir);
    $info_array = file($info_dir);
    $car_info = explode(";", $info_array[$line_num]);
    echo "<form action='' method=\"post\">";
    echo "<textarea name='car_info' rows='10' cols='50'>";
    echo $car_info[0] . ";" . $car_info[1] . ";" . $car_info[2] . ";" . $car_info[3];
    echo "</textarea>";
    echo "<input type=\"submit\" name =\"save\" value=\"save changes\">";
    echo "</form>";
}

function deleteLine($car_name, $info_dir)
{
    $line_num = findLine($car_name, $info_dir);
    $info_array = file($info_dir);
    $info_array[$line_num] = "";
    $info_array = array_filter($info_array);
    $info_array = array_values($info_array);
    $info_array = implode("", $info_array);
    file_put_contents($info_dir, $info_array);
}

editTextarea($car_name, $info_dir);

if (isset($_POST['save'])) {
    deleteLine($car_name, $info_dir);
    $car_info = $_POST['car_info'];
    $car_info_array = explode(";", $car_info);
    $car_name = $car_info_array[0];
    $car_price = $car_info_array[1];
    $car_desc = $car_info_array[2];
    $car_availability = $car_info_array[3];
    $line_num = findLine($car_name, $info_dir);
    $info_array = file($info_dir);
    $info_array[$line_num] = $car_name . ";" . $car_price . ";" . $car_desc . ";" . $car_availability;
    $info_file = fopen($info_dir, "w");
    foreach ($info_array as $line) {
        fwrite($info_file, $line);
    }
    fclose($info_file);

    $html_index_dir = "../assets/car_pages/" . $car_name . "/index.html";
    $template_dir = "../assets/car_info/template.txt";

    $html_index_file = fopen($html_index_dir, "w");
    $template_file = fopen($template_dir, "r");

    while (!feof($template_file)) {
        $line = fgets($template_file);
        $line = str_replace("{car_name}", $car_name, $line);
        $line = str_replace("{car_price}", $car_price, $line);
        $line = str_replace("{car_desc}", $car_desc, $line);
        $line = str_replace("{car_availability}", $car_availability, $line);
        fwrite($html_index_file, $line);
    }
    fclose($html_index_file);
    fclose($template_file);

    header("Location: /admin_dashboard/");
}
