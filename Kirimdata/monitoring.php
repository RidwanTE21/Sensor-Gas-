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

// Query untuk mendapatkan data dari tabel `sensorgas`
$sql = "SELECT nomer, GasLevel, tanggal FROM sensorgas ORDER BY nomer DESC";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gas Leak Monitor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Gas Leak Monitor</h1>
        <p>Monitoring data from the gas sensor</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Gas Level</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Loop melalui hasil query dan tampilkan dalam tabel
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['nomer'] . "</td>";
                    echo "<td>" . $row['GasLevel'] . "</td>";
                    echo "<td>" . $row['tanggal'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No data available</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
<?php
// Tutup koneksi
$conn->close();
?>

