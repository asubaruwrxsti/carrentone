<!DOCTYPE html>

<body>

    <?php
    $dir = "../assets/car_pages/";
    $info_dir = "../assets/car_info/info.txt";

    function getDirFilenames($dir)
    {
        $files = array();
        $dir = opendir($dir);
        while (($file = readdir($dir)) !== false) {
            if ($file != '.' && $file != '..') {
                $files[] = $file;
            }
        }
        closedir($dir);
        return $files;
    }

    function getCarInfo($info)
    {
        $info_array = file($info);
        $car_names = array();
        $car_price = array();
        $car_desc = array();
        $car_availability = array();
        foreach ($info_array as $line) {
            $car_names[] = explode(";", $line)[0];
            $car_price[] = explode(";", $line)[1];
            $car_desc[] = explode(";", $line)[2];
            $car_availability[] = explode(";", $line)[3];
        }

        $car_info = array();
        for ($i = 0; $i < count($car_names); $i++) {
            $car_info[$i][0] = $car_names[$i];
            $car_info[$i][1] = $car_price[$i];
            $car_info[$i][2] = $car_desc[$i];
            $car_info[$i][3] = $car_availability[$i];
        }
        return $car_info;
    }
    ?>

    <table border="1">
        <tr>
            <th>Car Name</th>
            <th>Price</th>
            <th>Description</th>
            <th>Availability</th>
            <th>Edit</th>
        </tr>

        <?php
        $car_info = getCarInfo($info_dir);
        for ($i = 0; $i < count($car_info); $i++) {
            echo "<tr>";
            echo "<td><a  target='_blank' rel='noopener noreferrer' href='" . $dir . $car_info[$i][0] . "'>" . $car_info[$i][0] . " </a></td>";
            echo "<td>" . $car_info[$i][1] . "</td>";
            echo "<td>" . $car_info[$i][2] . "</td>";
            echo "<td>" . $car_info[$i][3] . "</td>";
            echo "<td>" . "<a href='edit.php?car_name=" . $car_info[$i][0] . "'><button> Edit </button> </a>" . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

</body>

</html>