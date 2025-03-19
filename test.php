<?PHP

$uvIndex = 0;

// WeatherAPI URL (Replace YOUR_WEATHERAPI_KEY with your actual API key)
$apiUrl = "https://api.weatherapi.com/v1/current.json?key=4f158d896f3e411b909225249251903&q=-37.8136,144.9631";

// Fetch Data
$response = file_get_contents($apiUrl);
if ($response !== false) {
    $uvData = json_decode($response, true);

    // Extract the UV Index correctly from the "current" object
    if (isset($uvData['current']['uv'])) {
        $uvIndex = $uvData['current']['uv'];
    }
}

// Display UV Index for debugging (Remove in production)
echo "Current UV Index: " . $uvIndex;


?>