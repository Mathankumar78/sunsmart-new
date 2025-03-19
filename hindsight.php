<?php
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
                            <h2>Why is UV Index Important?</h2>
                            <p>The **UV Index** measures the level of **ultraviolet radiation** from the sun. 
                                High UV exposure can cause **skin damage, sunburn, and increase the risk of melanoma**.
                            </p>

                            <h3>UV Index Levels</h3>
                            <ul>
                                <li><span class="low">0-2:</span> Low (Safe, minimal protection needed)</li>
                                <li><span class="moderate">3-5:</span> Moderate (Sunscreen & sunglasses recommended)</li>
                                <li><span class="high">6-7:</span> High (Cover up, stay in shade)</li>
                                <li><span class="very-high">8-10:</span> Very High (Limit sun exposure, wear SPF 50+)</li>
                                <li><span class="extreme">11+:</span> Extreme (Avoid going outside if possible!)</li>
                            </ul>

                            <h3>🔎 Search UV Index for Any Location</h3>
                            <p>Want to check the **UV Index** for your city or any other place in Australia?  
                                Use the search bar on the right to **find real-time UV levels** and  
                                understand the best sun protection measures for your area.  
                                Simply enter a **city or suburb name** and get instant results!</p>
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
                </div>

                <br><br>

                
            </div>
        </main>

        <?php include "include/footer.php"; ?>
    </div>

    <?php include "include/mobilemenu.php"; ?>
    <?php include "include/script.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let ctx = document.getElementById('uvChart').getContext('2d');
        let uvData = <?php echo json_encode($uv_values); ?>;

        let uvChart = new Chart(ctx, {
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
            options: { responsive: true, maintainAspectRatio: false }
        });
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


        
    </style>
</body>
</html>
