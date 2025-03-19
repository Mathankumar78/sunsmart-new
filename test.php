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

if (isset($_GET['fetchData']) && isset($_GET['year']) && isset($_GET['sex'])) {
    header('Content-Type: application/json');
    ob_clean();

    try {
        $year = $_GET['year'];
        $sex = $_GET['sex'];

        $stmt = $pdo->prepare("SELECT age_group, SUM(count) as incidence_count FROM cancer_incidence_age 
                               WHERE year = :year AND sex = :sex AND age_group != 'All ages combined'
                               GROUP BY age_group 
                               ORDER BY FIELD(age_group, '00-04', '05-09', '10-14', '15-19', '20-24', 
                                                       '25-29', '30-34', '35-39', '40-44', '45-49', 
                                                       '50-54', '55-59', '60-64', '65-69', '70-74', 
                                                       '75-79', '80-84', '85-89', '90+')");
        $stmt->execute(['year' => $year, 'sex' => $sex]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['age_groups' => array_column($data, 'age_group'), 'incidence_counts' => array_column($data, 'incidence_count')]);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include "include/head.php"; ?>
<body>
    <div class="page-wrapper">
        <?php include "include/header.php"; ?>
        <main class="main">
            <div class="page-header" style="background-image: url(images/page-header.jpg)">
                <h1 class="page-title">Analytics</h1>
                <ul class="breadcrumb">
                    <li><a href="index.php"><i class="d-icon-home"></i></a></li>
                    <li><a href="#">Analytics</a></li>
                </ul>
            </div>
            
            <div class="page-content container">
                <h2 class="title">Melanoma Incidence Analytics</h2>
                <p>Use the dropdowns below to adjust for a specific year and gender.</p>
                <label for="yearSelect">Year:</label>
                <select id="yearSelect" onchange="updateChart()">
                    <?php
                    $stmt = $pdo->query("SELECT DISTINCT year FROM cancer_incidence_age ORDER BY year ASC");
                    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $year) {
                        echo "<option value='$year'>$year</option>";
                    }
                    ?>
                </select>
                <label for="sexSelect">Sex:</label>
                <select id="sexSelect" onchange="updateChart()">
                    <?php
                    $stmt = $pdo->query("SELECT DISTINCT sex FROM cancer_incidence_age");
                    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $sex) {
                        echo "<option value='$sex'>$sex</option>";
                    }
                    ?>
                </select>
                <canvas id="cancerChart"></canvas>
            </div>
        </main>
        <?php include "include/footer.php"; ?>
    </div>
    <?php include "include/script.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let ctx = document.getElementById('cancerChart').getContext('2d');
        let chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Cancer Incidence',
                    data: [],
                    backgroundColor: 'blue'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Incidence Count' }
                    },
                    x: {
                        title: { display: true, text: 'Age Groups' }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Cancer Incidence',
                        font: { size: 18 }
                    }
                }
            }
        });
        function updateChart() {
            let year = document.getElementById('yearSelect').value;
            let sex = document.getElementById('sexSelect').value;
            fetch(`?fetchData=true&year=${year}&sex=${sex}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) return alert(data.error);
                    chart.data.labels = data.age_groups;
                    chart.data.datasets[0].data = data.incidence_counts;
                    chart.options.plugins.title.text = `Cancer Incidence (${sex}, ${year})`;
                    chart.update();
                })
                .catch(error => console.error('Error:', error));
        }
        document.addEventListener('DOMContentLoaded', updateChart);
    </script>
</body>
</html>
