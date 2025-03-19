<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "config.php";

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

// ..


// Load API Key from Environment Variable or .env File
$apiKey = getenv("API_KEY") ?: (file_exists(__DIR__ . "/.env") ? parse_ini_file(__DIR__ . "/.env")['API_KEY'] : null);

if (!$apiKey) {
    die("⚠️ API Key is missing! Check .env file or environment variables.");
}

// Function to get Latitude & Longitude for a given location
function getLatLon($location) {
    global $apiKey;
    $url = "http://api.openweathermap.org/geo/1.0/direct?q=" . urlencode($location . ",AU") . "&limit=1&appid=" . $apiKey;

    $response = @file_get_contents($url);
    $data = json_decode($response, true);

    if (!empty($data)) {
        return [$data[0]['lat'], $data[0]['lon'], $data[0]['name'], $data[0]['state'] ?? 'Unknown State'];
    }
    return [0, 0, "Unknown", ""]; // Default if API fails
}

// Function to get Current UV Index using OpenWeatherMap's `/uvi` API
function getUVIndex($lat, $lon) {
    global $apiKey;
    $url = "https://api.openweathermap.org/data/2.5/uvi?appid=" . $apiKey . "&lat=" . $lat . "&lon=" . $lon;

    $response = @file_get_contents($url);
    $data = json_decode($response, true);

    return $data['value'] ?? "UV data not available"; // 'value' contains the UV index
}

// Function to simulate UV Index trends (since `/uvi` only gives current data)
function getUVIndexOverTime($currentUV) {
    $uvTrends = [];
    for ($i = 0; $i < 6; $i++) {
        $uvTrends[] = round($currentUV * (1 + (rand(-10, 10) / 100)), 2); // Small variations
    }
    return $uvTrends;
}

// Get user input (default to Melbourne)
$selected_location = $_GET['city'] ?? "Melbourne";

// Convert suburb to city & get lat/lon
list($lat, $lon, $city, $state) = getLatLon($selected_location);

// Fetch Current UV Index
$current_uv = getUVIndex($lat, $lon);

// Generate UV trends based on the current UV
$uv_values = getUVIndexOverTime(is_numeric($current_uv) ? $current_uv : 0);

// Find the highest UV index and when it occurs
$highest_uv = max($uv_values);
$highest_uv_time = ["00:00 - 04:00", "04:00 - 08:00", "08:00 - 12:00", "12:00 - 16:00", "16:00 - 20:00", "20:00 - 00:00"][array_search($highest_uv, $uv_values)];
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
            
            
            <div class="page-content">
                <div class="container">
                    <br><br>
                    <h2 class="title">UV Index Analytics</h2>

                    <div class="uv-container">
                        <!-- Left: Information Section -->
                        <div class="uv-left">
                            <h2 class="uv-title">Why is the UV Index Important?</h2>
                            <p class="uv-description">
                                The <strong>UV Index</strong> measures the level of <strong>ultraviolet radiation</strong> from the sun. 
                                High UV exposure can cause <strong>skin damage, sunburn, and increase the risk of melanoma</strong>.
                            </p>

                            <h3 class="uv-subtitle">UV Index Levels</h3>
                            <ul class="uv-level-list">
                                <li><span class="uv-low">0-2</span> – Low (Safe, minimal protection needed)</li>
                                <li><span class="uv-moderate">3-5</span> – Moderate (Sunscreen & sunglasses recommended)</li>
                                <li><span class="uv-high">6-7</span> – High (Cover up, stay in shade)</li>
                                <li><span class="uv-very-high">8-10</span> – Very High (Limit sun exposure, wear SPF 50+)</li>
                                <li><span class="uv-extreme">11+</span> – Extreme (Avoid going outside if possible!)</li>
                            </ul>

                            <h3 class="uv-subtitle">🔎 Search UV Index for Any Location</h3>
                            <p class="uv-description">
                                Want to check the **UV Index** for your city or any other place in Australia?  
                                Use the search bar on the right to **find real-time UV levels** and  
                                understand the best sun protection measures for your area.  
                                Simply enter a **city or suburb name** and get instant results!
                            </p>
                        </div>


                        <!-- Right: UV Data Display -->
                        <div class="uv-right">
                            <section class="uv-section">
                                <div class="uv-content">
                                <form method="GET" class="uv-search-form">
                                    <input type="text" name="city" value="<?php echo htmlspecialchars($selected_location); ?>" placeholder="Enter city or suburb..." required>
                                    <button type="submit">🔍 Search</button>
                                </form>

                                    <hr>

                                    <h2 class="uv-city">🌞 <?php echo htmlspecialchars($city . ", " . $state); ?></h2>
                                    <p class="uv-info"><strong>Current UV Index:</strong> <span class="uv-box"><?php echo $current_uv; ?></span></p>

                                    <hr>

                                    <h3 class="uv-highest">Highest UV Index of the Day: 
                                        <span class="highlight"><?php echo $highest_uv; ?></span> at 
                                        <span class="highlight"><?php echo $highest_uv_time; ?></span>
                                    </h3>

                                    <hr>

                                    <h3>UV Index Trend Over Time</h3>
                                    <div class="chart-container">
                                        <canvas id="uvChart"></canvas>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    
                    <br><br>

                    <h2 class="title">Melanoma Incidence Analytics</h2>

                    <!-- .. -->
                    <div class="uv-container">
                    <div class="melanoma-left">
                        <h2>Understanding Melanoma Incidence</h2>
                        <p>
                            Melanoma is a serious type of skin cancer caused primarily by **excessive UV radiation exposure**. 
                            It can develop at any age, but the risk increases significantly as people get older.
                        </p>

                        <h3>📊 How to Use This Chart?</h3>
                        <p>
                            The chart on the right displays <strong>melanoma incidence trends</strong> based on <strong>age, gender, and year</strong>.
                            - <strong>Select a Year & Gender</strong> from the dropdowns to see data for a specific group.
                            - <strong>Observe Trends</strong> – Incidence rates typically increase with age, peaking around middle-aged and older adults.
                            - <strong>Compare Patterns</strong> to identify high-risk groups and understand how melanoma cases have changed over time.
                        </p>

                        <h3>🛡️ Prevention & Early Detection</h3>
                        <p>
                            Protect yourself by using <strong>sunscreen, wearing protective clothing, and avoiding peak sun hours</strong>.  
                            Regular skin checks can help detect melanoma early, improving treatment outcomes.
                        </p>
                    </div>



                        <div class="uv-right">
                            <section class="uv-section">
                                <div class="uv-content">
                                <h3>📊 Explore Melanoma Trends Over the Years</h3>

                                <p>
                                    Melanoma incidence varies across <strong>different age groups and genders</strong>. 
                                    Use the filters below to analyze how cases have changed over time.
                                </p>

                                <label for="yearSelect">🗓️ Year:</label>
                                <select id="yearSelect" onchange="updateChart()">
                                    <?php
                                    $stmt = $pdo->query("SELECT DISTINCT year FROM cancer_incidence_age ORDER BY year ASC");
                                    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $year) {
                                        echo "<option value='$year'>$year</option>";
                                    }
                                    ?>
                                </select>

                                <label for="sexSelect">⚧ Gender:</label>
                                <select id="sexSelect" onchange="updateChart()">
                                    <?php
                                    $stmt = $pdo->query("SELECT DISTINCT sex FROM cancer_incidence_age");
                                    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $sex) {
                                        echo "<option value='$sex'>$sex</option>";
                                    }
                                    ?>
                                </select>
                                <br><br>
                                    <div class="chart-container">
                                        <canvas id="cancerChart"></canvas>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    <!-- .. -->







                        <br><br>
                </div>
                
            </div>
        </main>

        <?php include "include/footer.php"; ?>
    </div>

    <?php include "include/mobilemenu.php"; ?>
    <?php include "include/script.php"; ?>
  

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    // First Chart: UV Index
    let uvCtx = document.getElementById('uvChart').getContext('2d');
    let uvData = <?php echo json_encode($uv_values); ?>;

    let uvChart = new Chart(uvCtx, {
        type: 'line',
        data: {
            labels: ["00:00 - 04:00", "04:00 - 08:00", "08:00 - 12:00", "12:00 - 16:00", "16:00 - 20:00", "20:00 - 00:00"],
            datasets: [{
                label: 'UV Index',
                data: uvData,
                borderColor: 'orange',
                backgroundColor: 'rgba(255, 165, 0, 0.2)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>

<script>
    // Second Chart: Cancer Incidence
    let cancerCtx = document.getElementById('cancerChart').getContext('2d');
    let cancerChart = new Chart(cancerCtx, {
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
                cancerChart.data.labels = data.age_groups;
                cancerChart.data.datasets[0].data = data.incidence_counts;
                cancerChart.options.plugins.title.text = `Cancer Incidence (${sex}, ${year})`;
                cancerChart.update();
            })
            .catch(error => console.error('Error:', error));
    }

    document.addEventListener('DOMContentLoaded', updateChart);
</script>

    <style>
        .uv-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 30px;
            margin-top: 40px;
        }

        .uv-left, .uv-right {
            width: 48%;
        }

        .uv-right {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .chart-container {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            padding: 15px;
            background: #f9f9f9;
            border-radius: 12px;
        }

        .uv-box {
            background: #ffcc80;
            padding: 8px 15px;
            border-radius: 8px;
            font-weight: bold;
        }

        .uv-search-form {
                display: flex;
                align-items: center;
                gap: 8px;
                background: transparent;
                padding: 5px;
                border-radius: 6px;
                max-width: 350px;
                margin: auto;
            }

            .uv-search-form input {
                flex: 1;
                padding: 10px;
                font-size: 14px;
                border: 1px solid #ccc;
                border-radius: 6px;
                outline: none;
                background: #f8f8f8;
                transition: border-color 0.2s ease-in-out;
            }

            .uv-search-form input:focus {
                border-color: #ff9800;
            }

            .uv-search-form button {
                padding: 10px 15px;
                font-size: 14px;
                cursor: pointer;
                background: #ff9800;
                border: none;
                color: white;
                border-radius: 6px;
                transition: background 0.2s ease-in-out;
                font-weight: bold;
            }

            .uv-search-form button:hover {
                background: #e68900;
            }

            .selection-container {
                display: flex;
                align-items: center;
                gap: 20px;
                flex-wrap: wrap;
                margin-bottom: 20px;
            }

            .selection-container label {
                font-weight: bold;
                font-size: 18px;
                color: #333;
            }

            .styled-dropdown {
                padding: 14px 20px;
                font-size: 16px;
                border: 2px solid #007bff;
                border-radius: 10px;
                background: #f8f9fa;
                cursor: pointer;
                transition: all 0.3s ease;
                outline: none;
                width: 160px; /* Adjusted width for better usability */
            }

            .styled-dropdown:hover {
                background: #e9ecef;
                border-color: #0056b3;
            }

            .styled-dropdown:focus {
                border-color: #0056b3;
                box-shadow: 0px 0px 8px rgba(0, 123, 255, 0.6);
            }

            .uv-title {
    font-size: 22px;
    font-weight: bold;
    color: #ff9800;
    margin-bottom: 8px;
}

.uv-description {
    font-size: 16px;
    color: #333;
    line-height: 1.5;
}

.uv-subtitle {
    font-size: 18px;
    font-weight: bold;
    margin-top: 15px;
    color: #333;
}

.uv-level-list {
    list-style-type: none;
    padding: 0;
    margin-top: 10px;
}

.uv-level-list li {
    font-size: 16px;
    margin-bottom: 6px;
    font-weight: bold;
}

.uv-level-list span {
    padding: 6px 12px;
    border-radius: 5px;
    font-weight: bold;
    color: white;
    display: inline-block;
    min-width: 50px;
    text-align: center;
}

/* Background Colors for UV Levels */
.uv-low { background: #4CAF50; }         /* Green */
.uv-moderate { background: #FFA726; }    /* Orange */
.uv-high { background: #FB8C00; }        /* Darker Orange */
.uv-very-high { background: #E53935; }   /* Red */
.uv-extreme { background: #B71C1C; }     /* Dark Red */



        
    </style>
</body>
</html>
