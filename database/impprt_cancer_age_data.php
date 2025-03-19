<?php
$host = 'localhost';
$dbname = 'cancer_data';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    function importCSV($pdo, $filename, $table) {
        if (($handle = fopen($filename, "r")) !== FALSE) {
            fgetcsv($handle); // Skip header row

            $stmt = $pdo->prepare("INSERT INTO $table (cancer_group, year, sex, age_group, count) VALUES (?, ?, ?, ?, ?)");

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $cancer_group = trim($data[1]);  // Cancer type
                $year = (int) $data[2];          // Year
                $sex = trim($data[3]);           // Gender
                $age_group = trim($data[4]);     // Age group
                $count = (int) str_replace(',', '', $data[5]); // Convert count to integer

                $stmt->execute([$cancer_group, $year, $sex, $age_group, $count]);
            }
            fclose($handle);
            echo "✅ Data imported into $table successfully.<br>";
        } else {
            echo "❌ Error opening file: $filename<br>";
        }
    }

    importCSV($pdo, "csv/cancer_incident_age.csv", "cancer_incidence_age");
    importCSV($pdo, "csv/cancer_mortality_age.csv", "cancer_mortality_age");

} catch (PDOException $e) {
    die("❌ Database error: " . $e->getMessage());
}
?>
