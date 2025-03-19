<?php
$host = 'localhost';
$dbname = 'cancer_data';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// ✅ Handle JSON requests for cancer mortality by state
if (isset($_GET['fetchMortality']) && isset($_GET['year'])) {
    header('Content-Type: application/json');
    ob_clean(); // Clears any previous output

    try {
        $year = $_GET['year'];

        $stmt = $pdo->prepare("SELECT state, SUM(count) as mortality_count FROM cancer_mortality 
                               WHERE year = :year 
                               GROUP BY state 
                               ORDER BY state ASC");
        $stmt->execute(['year' => $year]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $states = array_column($data, 'state');
        $mortality_counts = array_column($data, 'mortality_count');

        echo json_encode(['states' => $states, 'mortality_counts' => $mortality_counts]);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['error' => '❌ Database error: ' . $e->getMessage()]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancer Mortality by State</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.0.1/chart.umd.min.js"></script>
    <script>
        function updateMortalityChart() {
            let year = document.getElementById('mortalityYearSelect').value;

            fetch(window.location.pathname + '?fetchMortality=true&year=' + year)
                .then(response => response.json())  
                .then(data => {
                    console.log("📊 Mortality Data:", data);  

                    if (data.error) {
                        console.error("⚠ Server Error:", data.error);
                        alert("⚠ Error: " + data.error);
                        return;
                    }

                    mortalityDoughnutChart.data.labels = data.states;
                    mortalityDoughnutChart.data.datasets[0].data = data.mortality_counts;
                    mortalityDoughnutChart.options.plugins.title.text = 'Cancer Mortality by State (' + year + ')';
                    mortalityDoughnutChart.update();
                })
                .catch(error => console.error("❌ Fetch Error:", error));
        }

        document.addEventListener("DOMContentLoaded", updateMortalityChart);
    </script>
</head>
<body>
    <h2>📊 Cancer Mortality by State</h2>

    <h3>🔹 Cancer Mortality by State (Doughnut Chart)</h3>
    <label for="mortalityYearSelect">Year:</label>
    <select id="mortalityYearSelect" onchange="updateMortalityChart()">
        <?php
        // Fetch distinct years for mortality chart
        $stmt = $pdo->query("SELECT DISTINCT year FROM cancer_mortality ORDER BY year ASC");
        $years = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($years as $year) echo "<option value='$year'>$year</option>"; 
        ?>
    </select>

    <canvas id="mortalityDoughnutChart"></canvas>

    <script>
        let ctxMortalityDoughnut = document.getElementById('mortalityDoughnutChart').getContext('2d');
        let mortalityDoughnutChart = new Chart(ctxMortalityDoughnut, {
            type: 'doughnut',
            data: {
                labels: [],
                datasets: [{
                    label: 'Cancer Mortality',
                    data: [],
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                        '#FF9F40', '#2C3E50', '#1ABC9C', '#E74C3C', '#9B59B6'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Cancer Mortality by State',
                        font: {
                            size: 18
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
