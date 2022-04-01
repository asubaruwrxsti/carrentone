<?php

session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
}

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

if (isset($_GET['car_name'])) {
    $car_name = $_GET['car_name'];
    $info_dir = "../assets/car_info/info.txt";
    $info_array = file($info_dir);
    $line_num = findLine($car_name, $info_dir);
    $info_array[$line_num] = "";
    $info_array = array_filter($info_array);
    $info_array = array_values($info_array);
    $info_array = implode("", $info_array);
    file_put_contents($info_dir, $info_array);

    $car_dir = "../assets/car_pages/" . $car_name;
    $car_dir_array = scandir($car_dir);
    foreach ($car_dir_array as $file) {
        if ($file != "." && $file != "..") {
            unlink($car_dir . "/" . $file);
        }
    }
    rmdir($car_dir);

    $car_image_dir = "../images/car_images/" . $car_name;
    $car_image_dir_array = scandir($car_image_dir);
    foreach ($car_image_dir_array as $file) {
        if ($file != "." && $file != "..") {
            unlink($car_image_dir . "/" . $file);
        }
    }
    rmdir($car_image_dir);


    header("Location: /admin_dashboard/index.php");
}
