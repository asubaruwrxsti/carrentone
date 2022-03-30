<!DOCTYPE html>

<form action="add.php" method="post">
    <input type="text" name="car_name" placeholder="Car Name">
    <input type="text" name="car_price" placeholder="Car Price">
    <input type="text" name="car_desc" placeholder="Car Description">
    <input type="text" name="car_availability" placeholder="Car Availability">
    <input type="text" name="car_transmission" placeholder="Car Transmission">
    <input type="submit" value="Submit">
</form>
<?php
if (isset($_POST['car_name'])) {
    $car_name = $_POST['car_name'];
    $car_price = $_POST['car_price'];
    $car_desc = $_POST['car_desc'];
    $car_availability = $_POST['car_availability'];
    $car_transmission = $_POST['car_transmission'];
    $info_dir = "../assets/car_info/info.txt";
    $info_file = fopen($info_dir, "a");

    fwrite($info_file, $car_name . ";" . $car_price . ";" . $car_desc . ";" . $car_availability . ";" . $car_transmission . ";");
    fclose($info_file);

    $car_dir = "../images/car_images/" . $car_name;
    mkdir($car_dir);

    $car_dir = "../assets/car_pages/" . $car_name;
    mkdir($car_dir);

    header("Location: /admin_dashboard/index.php");
}
?>

</html>