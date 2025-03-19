<?php
$host = 'localhost';
$dbname = 'cancer_data';
$username = 'root'; // Change if needed
$password = ''; // Change if needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    function importCSV($pdo, $filename, $table) {
        if (($handle = fopen($filename, "r")) !== FALSE) {
            fgetcsv($handle); // Skip header row

            $stmt = $pdo->prepare("INSERT INTO $table (cancer_group, year, sex, state, count) VALUES (?, ?, ?, ?, ?)");

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Remove commas and convert count to integer
                $count = isset($data[4]) ? (int)str_replace(',', '', $data[4]) : NULL;

                $stmt->execute([$data[0], $data[1], $data[2], $data[3], $count]);
            }
            fclose($handle);
            echo "Data imported into $table successfully.<br>";
        } else {
            echo "Error opening file: $filename<br>";
        }
    }

    importCSV($pdo, "csv/cancer_incidence.csv", "cancer_incidence");
    importCSV($pdo, "csv/cancer_mortality.csv", "cancer_mortality");

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
