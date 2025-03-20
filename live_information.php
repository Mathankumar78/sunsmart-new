<?php
// Default values for Melbourne
$default_latitude = "-37.8136";
$default_longitude = "144.9631";
$default_city = "Melbourne";

// Get selected city from the dropdown (if any)
$selected_city = isset($_GET['city']) ? $_GET['city'] : $default_city;

// City coordinates list
$australianCities = [
    "Melbourne" => ["lat" => "-37.8136", "lon" => "144.9631"],
    "Sydney" => ["lat" => "-33.8688", "lon" => "151.2093"],
    "Brisbane" => ["lat" => "-27.4698", "lon" => "153.0251"],
    "Perth" => ["lat" => "-31.9505", "lon" => "115.8605"],
    "Adelaide" => ["lat" => "-34.9285", "lon" => "138.6007"],
    "Canberra" => ["lat" => "-35.2809", "lon" => "149.1300"],
    "Hobart" => ["lat" => "-42.8821", "lon" => "147.3272"],
    "Darwin" => ["lat" => "-12.4634", "lon" => "130.8456"]
];

// Get selected city coordinates (default to Melbourne if missing)
$latitude = $australianCities[$selected_city]["lat"] ?? $default_latitude;
$longitude = $australianCities[$selected_city]["lon"] ?? $default_longitude;

// Load API Key
$apiKey = getenv("API_KEY");
if (!$apiKey) {
    $env = parse_ini_file(__DIR__ . "/.env"); // Load from .env file
    $apiKey = $env['API_KEY'] ?? null;
}
if (!$apiKey) {
    die("⚠️ API Key is missing! Check .env file.");
}

// Fetch UV Index from WeatherAPI (Ensuring we fetch the data before using it)
$uvIndex = 0;
$uvApiUrl = "https://api.weatherapi.com/v1/current.json?key=$apiKey&q=$latitude,$longitude";
$uvResponse = file_get_contents($uvApiUrl);
if ($uvResponse !== false) {
    $uvData = json_decode($uvResponse, true);
    if (isset($uvData['current']['uv'])) {
        $uvIndex = $uvData['current']['uv'];
    }
}

// Function to generate a UV trend (Mocked data since API doesn't provide historical UV data)
function getUVIndexOverTime($currentUV) {
    $uvTrends = [];
    $multipliers = [0.5, 0.8, 1.0, 1.2, 0.7, 0.6]; // Simulating UV changes

    for ($i = 0; $i < 6; $i++) {
        $variation = rand(-20, 20) / 100; // ±20% fluctuation
        $uvTrends[] = round($currentUV * (1 + $variation) * $multipliers[$i], 2);
    }
    return $uvTrends;
}

// Generate UV trend based on the current UV
$uv_values = getUVIndexOverTime(is_numeric($uvIndex) ? $uvIndex : 0);

$highest_uv = max($uv_values);
$time_slots = ["00:00 - 04:00", "04:00 - 08:00", "08:00 - 12:00", "12:00 - 16:00", "16:00 - 20:00", "20:00 - 00:00"];
$highest_uv_time = $time_slots[array_search($highest_uv, $uv_values)];

// Function to determine the UV warning message
function getUVReminderMessage($city, $currentUV) {
    if ($currentUV <= 2) {
        $riskLevel = "Low";
        $message = "Minimal risk of UV exposure. No protection needed, but wearing sunglasses and staying hydrated is always a good idea! 😎💧";
    } elseif ($currentUV <= 5) {
        $riskLevel = "Moderate";
        $message = "Moderate UV exposure. Consider wearing sunscreen (SPF 30+), sunglasses, and a hat if you're outside! 🧴🕶️";
    } elseif ($currentUV <= 7) {
        $riskLevel = "High";
        $message = "High UV exposure! Apply SPF 50+ sunscreen, seek shade, and wear sunglasses and a hat! ☀️🧴";
    } elseif ($currentUV <= 10) {
        $riskLevel = "Very High";
        $message = "Very high UV levels! Stay in the shade, wear protective clothing, and reapply sunscreen frequently. Avoid long exposure! 🌡️🔥";
    } else {
        $riskLevel = "Extreme";
        $message = "Extreme UV exposure! Stay indoors if possible. If you must go out, wear full protection and limit sun exposure! ⚠️☀️";
    }

    return [
        'title' => "🌞 UV Reminder for " . htmlspecialchars($city),
        'content' => "Based on today's UV trends in <strong>" . htmlspecialchars($city) . "</strong>, the UV index is currently <strong>$riskLevel ($currentUV)</strong>. <br><br>" . $message
    ];
}

// Now generate the correct UV Reminder Message **after fetching the UV Index**
$uvReminder = getUVReminderMessage($selected_city, $uvIndex)

?>

<!DOCTYPE html>
<html lang="en">
<?php include "include/head.php"; ?>
<body>
    <div class="page-wrapper">
        <?php include "include/header.php"; ?>
        <main class="main">
            <div class="page-header" style="background-image: url(images/page-header.jpg)">
                <h1 class="page-title" id="analytics-title">UV index</h1>
                <ul class="breadcrumb">
                    <li><a href="index.php"><i class="d-icon-home"></i></a></li>
                    <li><a href="#">UV Index</a></li>
                </ul>
            </div>
            <div class="page-content">
                <div class="container">
                    <br><br>
                    <h2 class="title">UV Index Analytics for <?PHP echo htmlspecialchars($selected_city); ?></h2>
                    <div class="uv-container">
                    <div class="uv-left">
                        <h2 class="uv-title">Check Live UV Index for Your City</h2>
                        <p class="uv-description">
                            Use this tool to check the <strong>real-time UV index </strong> for any city in Australia.  
                            Select a city from the dropdown to see the <strong>rcurrent UV levels</strong>,  
                            <strong>highest UV index of the day</strong> , and a <strong> trend chart<strong>  showing UV variations throughout the day.  
                            <br><br>
                            The <strong>UV Index</strong> measures the level of <strong>ultraviolet radiation</strong> from the sun.  
                            High UV exposure can cause <strong>skin damage, sunburn, and increase the risk of melanoma</strong>.  
                            Stay informed and take necessary precautions to protect yourself.
                        </p>
                    </div>
                        <div class="uv-right">
                            <section class="uv-section">
                                <div class="uv-content">
                                    <label for="citySelect"><strong>Choose a city:</strong></label>
                                    <select id="citySelect" class="styled-select" onchange="updateUVData()">
                                        <?php foreach ($australianCities as $city => $coords) {
                                            echo "<option value='" . $city . "' " . ($selected_city == $city ? "selected" : "") . ">" . $city . "</option>";
                                        } ?>
                                    </select>
                                    <hr>
                                    <h2 class="uv-city">🌞 <span id="city-name"><?php echo htmlspecialchars($selected_city); ?></span></h2>
                                    <p class="uv-info"><strong>Current UV Index:</strong> <span class="uv-box"><?php echo $uvIndex; ?></span></p>
                                    <!-- Reminder Button -->
                                    <button id="open-popup" class="btn-secondary">🔔 Set UV Reminder</button>
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
                

                
            </div>

            <br>
        </main>
        <?php include "include/footer.php"; ?>
        <!-- Hidden UV Reminder Popup -->
        <<!-- UV Reminder Button -->


        <!-- Popup Container -->
        <div id="popup-container" class="popup-container">
            <div class="popup-content">
                <h2>🌞 UV Reminder for Melbourne</h2>
                <p>
                    Based on today's UV trends in <strong>Melbourne</strong>, the <strong>UV index </stong> is highest between 
                    <strong>12:00 PM - 4:00 PM</strong>. Please **apply SPF 50+ sunscreen**, stay in the shade, 
                    and drink plenty of water! 💧
                </p>
                <button id="close-popup" class="close-popup">OK</button>
            </div>
        </div>
    </div>
    <?php include "include/mobilemenu.php"; ?>
    <?php include "include/script.php"; ?>


    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    function showUVReminder() {
        // Show the reminder section
        document.getElementById('uvReminderSection').style.display = "block";

        // UV Index Data for Melbourne (Example Data)
        let uvData = [3, 5, 7, 8, 6, 4]; // UV levels over time
        let uvLabels = ["00:00 - 04:00", "04:00 - 08:00", "08:00 - 12:00", "12:00 - 16:00", "16:00 - 20:00", "20:00 - 00:00"];

        // Check if chart already exists, destroy before re-creating
        if (window.uvChartInstance) {
            window.uvChartInstance.destroy();
        }

        // Create Chart
        let ctx = document.getElementById('uvReminderChart').getContext('2d');
        window.uvChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: uvLabels,
                datasets: [{
                    label: 'Melbourne UV Index',
                    data: uvData,
                    borderColor: 'orange',
                    backgroundColor: 'rgba(255, 165, 0, 0.2)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
</script>


    <script>
       document.addEventListener("DOMContentLoaded", function () {
    const popupContainer = document.getElementById("popup-container");
    const openPopupBtn = document.getElementById("open-popup");
    const closePopupBtn = document.getElementById("close-popup");

    openPopupBtn.addEventListener("click", function () {
        let selectedCity = document.getElementById("citySelect").value; // Get selected city

        // Fetch UV details dynamically from PHP variables
        let uvTitle = `<?php echo $uvReminder['title']; ?>`;
        let uvMessage = `<?php echo $uvReminder['content']; ?>`;

        // Update popup content dynamically
        document.querySelector(".popup-content h2").innerHTML = uvTitle;
        document.querySelector(".popup-content p").innerHTML = uvMessage;

        popupContainer.classList.add("active"); // Show popup
    });

    closePopupBtn.addEventListener("click", function () {
        popupContainer.classList.remove("active"); // Hide popup
    });

    popupContainer.addEventListener("click", function (event) {
        if (event.target === popupContainer) {
            popupContainer.classList.remove("active");
        }
    });
});


    </script>

    <script>
        function updateUVData() {
            let city = document.getElementById("citySelect").value;
            document.getElementById("analytics-title").innerText = "UV Index Analytics for " + city;
            document.getElementById("city-name").innerText = city;
            window.location.href = "?city=" + encodeURIComponent(city);
        }

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
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: 12 // Ensures full visibility of UV Index
                    }
                }
            }
        });
    </script>

    <style>
        .styled-select {
            width: 250px;
            padding: 10px;
            font-size: 16px;
            border: 2px solid #ff8c42;
            border-radius: 5px;
            background-color: white;
            color: #333;
            text-align: center;
            cursor: pointer;
        }

        .styled-select:focus {
            outline: none;
            border-color: #ff6b00;
            box-shadow: 0px 0px 8px rgba(255, 140, 66, 0.5);
        }

        .uv-box {
            display: inline-block;
            background-color: #ffcc00;
            color: #000;
            padding: 5px 10px;
            font-weight: bold;
            border-radius: 5px;
        }

        .chart-container {
            max-width: 700px;
            margin: auto;
        }

    /* Improve Popup Styling */
/* Full-screen overlay */
.popup-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Dark overlay */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    visibility: hidden;
    opacity: 0;
    transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
}

/* Show popup when active */
.popup-container.active {
    visibility: visible;
    opacity: 1;
}

/* Popup box */
.popup-content {
    background: white;
    padding: 25px;
    border-radius: 12px;
    text-align: center;
    width: 90%;
    max-width: 450px;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
    animation: fadeIn 0.3s ease-in-out;
}

/* Fade-in animation */
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

/* Close button */
.close-popup {
    background: #FF8C42;
    color: white;
    border: none;
    padding: 10px 18px;
    margin-top: 15px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    border-radius: 8px;
    transition: background 0.2s ease-in-out;
}

.close-popup:hover {
    background: #FF6A00;
}

    </style>

</body>
</html>
