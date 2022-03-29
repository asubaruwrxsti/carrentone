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

//form to upload an image to images/car_pages/car_name
function uploadImage()
{
    echo "<form action='' method=\"post\" enctype=\"multipart/form-data\">";
    echo "<input type=\"file\" name=\"fileToUpload\" id=\"fileToUpload\">";
    echo "<input type=\"submit\" value=\"Upload Image\" name=\"submit\">";
    echo "</form>";
}

editTextarea($car_name, $info_dir);
uploadImage($car_name);

if (isset($_POST['submit'])) {
    //upload image
    if (isset($_FILES['fileToUpload'])) {
        $target_dir = "../images/car_images/" . $car_name . "/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
}

if (isset($_POST['save'])) {
    //save changes
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

    //count images in images/car_images/car_name
    $image_dir = "../images/car_images/" . $car_name . "/";
    $image_array = glob($image_dir . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
    $image_count = count($image_array);

    $car_reference = "";
    foreach ($image_array as $image) {
        $car_reference .= '				
        <div class="col-sm-12 col-md-4 col-lg-4">
            <h3>{car_name}</h3>
            <a class="lightbox" href="/images/car_images/' . $car_name . "/" . basename($image) . '" >
                <img class="img-fluid" src="/images/car_images/' . $car_name . "/" . basename($image) . '">
            </a>
        </div>';
    }

    while (!feof($template_file)) {
        $line = fgets($template_file);
        $line = str_replace("{car_reference}", $car_reference, $line);
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
