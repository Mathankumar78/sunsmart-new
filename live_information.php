<?php
// Default values for Melbourne
$default_latitude = "-37.8136";
$default_longitude = "144.9631";
$default_city = "Melbourne";

// Get selected city from the dropdown (if any)
$selected_city = isset($_GET['city']) ? $_GET['city'] : $default_city;

// City coordinates list
$city_coordinates = [
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
$latitude = $city_coordinates[$selected_city]["lat"] ?? $default_latitude;
$longitude = $city_coordinates[$selected_city]["lon"] ?? $default_longitude;

// Load API Key
$apiKey = getenv("API_KEY");  
if (!$apiKey) {
    $env = parse_ini_file(__DIR__ . "/.env"); // Load from .env file
    $apiKey = $env['API_KEY'] ?? null;
}
if (!$apiKey) {
    die("⚠️ API Key is missing! Check .env file.");
}

// Fetch UV Index from OpenWeather API
$uvIndex = 0;
$uvApiUrl = "https://api.openweathermap.org/data/2.5/uvi?appid=$apiKey&lat=$latitude&lon=$longitude";
$uvResponse = file_get_contents($uvApiUrl);
if ($uvResponse !== false) {
    $uvData = json_decode($uvResponse, true);
    if (isset($uvData['value'])) {
        $uvIndex = $uvData['value'];
    }
}

// Determine Sun Protection Advice
$uvAdvice = "";
if ($uvIndex < 3) {
    $uvAdvice = "UV is low, minimal protection needed.";
} elseif ($uvIndex >= 3 && $uvIndex < 6) {
    $uvAdvice = "Moderate UV, wear SPF 30+ sunscreen and sunglasses.";
} else {
    $uvAdvice = "High UV! Apply SPF 50+, wear protective clothing.";
}

// Sun Protection Tips
$sun_tips = "Avoid direct sun exposure between 10 AM - 4 PM. Stay in shade, wear sunglasses, and drink plenty of water.";

$city_images = array_keys($city_coordinates);
shuffle($city_images);
$popular_cities = array_slice($city_images, 0, 3);


// City image URLs
$city_images = [
    "Melbourne" => "https://upload.wikimedia.org/wikipedia/commons/4/46/Melburnian_Skyline_b.jpg",
    "Sydney" => "https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Sydney_Opera_House_and_Harbour_Bridge_Dusk_%282%29_2019-06-21.jpg/2880px-Sydney_Opera_House_and_Harbour_Bridge_Dusk_%282%29_2019-06-21.jpg",
    "Brisbane" => "https://upload.wikimedia.org/wikipedia/commons/thumb/8/83/Brisbane_CBD_from_Southbank_in_July_2024.jpg/2560px-Brisbane_CBD_from_Southbank_in_July_2024.jpg",
    "Perth" => "https://upload.wikimedia.org/wikipedia/commons/thumb/3/3e/Perth_CBD_skyline_from_State_War_Memorial_Lookout%2C_2023%2C_04_b.jpg/2880px-Perth_CBD_skyline_from_State_War_Memorial_Lookout%2C_2023%2C_04_b.jpg",
    "Adelaide" => "https://upload.wikimedia.org/wikipedia/commons/9/9b/Adelaide_skyline%2C_December_2022_b.jpg",
    "Canberra" => "https://upload.wikimedia.org/wikipedia/commons/thumb/c/cc/Canberra_panorama_from_Mount_Ainslie.jpg/2880px-Canberra_panorama_from_Mount_Ainslie.jpg",
    "Hobart" => "https://upload.wikimedia.org/wikipedia/commons/d/d1/Franklin_Wharf_2015_b.jpg",
    "Darwin" => "https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/DarwinOct172024_02.jpg/2560px-DarwinOct172024_02.jpg"
];

// Ensure image exists, fallback to Melbourne
$city_image = $city_images[$selected_city] ?? $city_images["Melbourne"];
?>

<!DOCTYPE html>
<html lang="en">

<?PHP include "include/head.php"?>


<body>
    
    <div class="page-wrapper">
        <?PHP include "include/header.php"?>

        <main class="main">
            <div class="page-header" style="background-image: url(images/page-header.jpg)">
                <h1 class="page-title">Live Information</h1>
            </div>
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><a href="index.php"><i class="d-icon-home"></i></a></li>
                        <li><a href="#" class="active">Live information</a></li>
                        
                    </ul>
                </div>
            </nav>
            <div class="page-content mt-6">
                <div class="container">
                    <div class="row gutter-lg">
                        <div class="col-lg-9">
                            <article class="post-single">
                                <figure class="post-media">
                                    <img src="<?php echo $city_image; ?>" width="870" height="420" alt="<?php echo $selected_city; ?> Image" />
                                </figure>
                                <div class="post-details">
                                    <h4 class="post-title">Live UV Information for <?php echo $selected_city; ?></h4>
                                    <div class="post-body mb-7">
                                        <h3>Current UV Index: <?php echo $uvIndex; ?></h3>
                                        <p><?php echo $uvAdvice; ?></p>
                                        <h3>Sun Protection Tips:</h3>
                                        <p><?php echo $sun_tips; ?></p>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="col-lg-3">
                            <!-- <div class="sidebar-content"> -->
                                <div class="sticky-sidebar">
                                    <div class="shipping-address pb-4">
                                        <label>Select City:</label>
                                        <div class="select-box" style="width: 100%;">
                                            <select id="city" name="city" class="form-control" onchange="updateCity()">
                                                <?php
                                                foreach ($city_coordinates as $city => $coords) {
                                                    echo "<option value='$city' " . ($city == $selected_city ? "selected" : "") . ">$city</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <br><br><br>
                                    </div>

                                    
                                    <div class="widget widget-posts mb-5">
                                        <h3 class="widget-title">Popular Cities</h3>
                                        <div class="widget-body">
                                            <?php foreach ($popular_cities as $city) { ?>
                                                <div class="post post-list-sm">
                                                    <figure class="post-media">
                                                        <a href="live_information.php?city=<?php echo $city; ?>">
                                                            <img src="<?php echo $city_images[$city] ?? ''; ?>" width="100" height="100" alt="<?php echo $city; ?> Image" />
                                                        </a>
                                                    </figure>
                                                    <div class="post-details">
                                                        
                                                        <h4 class="post-title">
                                                            <a href="live_information.php?city=<?php echo $city; ?>"> <?php echo $city; ?> </a>
                                                        </h4>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>


                                    </div>
                                </div>
                            <!-- </div> -->
                        </div>
                    </div>
                </div>

                <hr>
            </div>
            <script>
                function updateCity() {
                    let city = document.getElementById("city").value;
                    window.location.href = "live_information.php?city=" + city;
                }
            </script>
        </main>


        <?PHP include "include/footer.php"?>
    </div>
    
    <?PHP include "include/mobilemenu.php"?>
    <?PHP include "include/script.php"?>
</body>
</html>
