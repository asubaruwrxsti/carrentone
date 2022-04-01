<!DOCTYPE html>

<style>
    input[type=text],
    select {
        width: 50%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type=submit] {
        width: 50%;
        background-color: #4CAF50;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type=submit]:hover {
        background-color: #45a049;
    }

    div {
        border-radius: 5px;
        background-color: #f2f2f2;
        padding: 20px;
    }
</style>

<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
}

?>

<form action="add.php" method="post" style="text-align: center;">
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