<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "esp_32_gasleak";

// Membuat koneksi ke database
$conn = new mysqli($hostname, $username, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Database connection is OK!<br>";

if(isset($_POST["GasLevel"])){
    $GasLevel = intval ($_POST["GasLevel"]);

    if (!empty($GasLevel)) {
    // Masukkan data ke dalam database
    $sql = "INSERT INTO sensorgas (GasLevel) VALUES ('$GasLevel')";
    if ($conn->query($sql) === TRUE) {
        echo "Data inserted successfully!";
        } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        }
        } else {
    echo "Error: GasLevel is empty!";
    }
} else {
echo "Error: GasLevel not received!";
}
