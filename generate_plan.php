<!DOCTYPE html>
<html lang="en">

<?PHP include "include/head.php"?>


<body>
    
    <div class="page-wrapper">
        <?PHP include "include/header.php"?>

        <!-- START -->

        <?php
            // Debugging - Check if parameters are passed correctly
            // Uncomment if needed for debugging
            // var_dump($_GET);

            // Get form data from URL parameters with default values
            $name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : 'Guest';
            $email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : 'Not provided';
            $location = isset($_GET['location']) ? htmlspecialchars($_GET['location']) : 'Unknown';
            $latitude = isset($_GET['latitude']) ? htmlspecialchars($_GET['latitude']) : '0';
            $longitude = isset($_GET['longitude']) ? htmlspecialchars($_GET['longitude']) : '0';
            $skin_type = isset($_GET['skin_type']) ? htmlspecialchars($_GET['skin_type']) : 'Unknown';
            $language = isset($_GET['language']) ? htmlspecialchars($_GET['language']) : 'English';

            // Ensure only valid languages are used
            $valid_languages = ["English", "Hindi", "Urdu", "Chinese"];
            if (!in_array($language, $valid_languages)) {
                $language = "English";
            }


            $city_coordinates = [
                "Sydney" => ["lat" => "-33.8688", "lon" => "151.2093"],
                "Melbourne" => ["lat" => "-37.8136", "lon" => "144.9631"],
                "Brisbane" => ["lat" => "-27.4698", "lon" => "153.0251"],
                "Perth" => ["lat" => "-31.9505", "lon" => "115.8605"],
                "Adelaide" => ["lat" => "-34.9285", "lon" => "138.6007"],
                "Gold Coast" => ["lat" => "-28.0167", "lon" => "153.4000"],
                "Canberra" => ["lat" => "-35.2809", "lon" => "149.1300"],
                "Hobart" => ["lat" => "-42.8821", "lon" => "147.3272"],
                "Darwin" => ["lat" => "-12.4634", "lon" => "130.8456"],
                "Newcastle" => ["lat" => "-32.9283", "lon" => "151.7817"],
                "Wollongong" => ["lat" => "-34.4240", "lon" => "150.8931"],
                "Geelong" => ["lat" => "-38.1499", "lon" => "144.3617"],
                "Cairns" => ["lat" => "-16.9203", "lon" => "145.7710"],
                "Townsville" => ["lat" => "-19.2589", "lon" => "146.8169"],
                "Toowoomba" => ["lat" => "-27.5598", "lon" => "151.9507"]
            ];
            
            // Get the selected location and find lat/lon
            if (isset($_GET['location']) && array_key_exists($_GET['location'], $city_coordinates)) {
                $latitude = $city_coordinates[$_GET['location']]['lat'];
                $longitude = $city_coordinates[$_GET['location']]['lon'];
            } else {
                $latitude = '0';
                $longitude = '0';
            }

            // OpenWeather API for UV Index
            $env = parse_ini_file(__DIR__ . "/.env");

            $apiKey = $env['API_KEY'] ?? null;

            if (!$apiKey) {
                die("⚠️ API Key is missing! Check .env file.");
            }
            $uvIndex = 0;
           
            $apiUrl = "https://api.weatherapi.com/v1/current.json?key=$apiKey&q=$latitude,$longitude";
            $response = file_get_contents($apiUrl);
            if ($response !== false) {
                $uvData = json_decode($response, true);

                // Corrected UV Index extraction from "current" object
                if (isset($uvData['current']['uv'])) {
                    $uvIndex = $uvData['current']['uv'];
                }
            }

            

            // UV Index Categories and Recommendations
            $uvRecommendations = [
                "Low" => ["SPF 15-30", "Reapply every 4 hours"],
                "Moderate" => ["SPF 30+", "Reapply every 2 hours"],
                "High" => ["SPF 50+", "Reapply every 60 minutes"]
            ];

            // Determine SPF & Reapplication Time
            if ($uvIndex < 3) {
                $uvLevel = "Low";
            } elseif ($uvIndex >= 3 && $uvIndex < 6) {
                $uvLevel = "Moderate";
            } else {
                $uvLevel = "High";
            }

            $spf_recommendation = $uvRecommendations[$uvLevel][0];
            $reapply_time = $uvRecommendations[$uvLevel][1];

            // Translations for Multi-language Support
            $translations = [
                "English" => [
                    "recommended_sunscreen" => "Recommended Sunscreen",
                    "reapply_reminder" => "Reapply Reminder",
                    "sun_safety_tips" => "Sun Safety Tips",
                    "spf_value" => $spf_recommendation,
                    "reapply_time" => "$reapply_time",
                    "safety_tips" => [
                        "Wear sunglasses and a hat for extra protection.",
                        "Seek shade between 10 AM - 4 PM.",
                        "Stay hydrated and apply sunscreen generously."
                    ]
                ],
                "Hindi" => [
                    "recommended_sunscreen" => "अनुशंसित सनस्क्रीन",
                    "reapply_reminder" => "पुनः आवेदन अनुस्मारक",
                    "sun_safety_tips" => "सूर्य सुरक्षा टिप्स",
                    "spf_value" => $spf_recommendation,
                    "reapply_time" => "हर $reapply_time में पुनः लगाएं",
                    "safety_tips" => [
                        "अतिरिक्त सुरक्षा के लिए धूप का चश्मा और टोपी पहनें।",
                        "सुबह 10 बजे से शाम 4 बजे के बीच छाया में रहें।",
                        "हाइड्रेटेड रहें और सनस्क्रीन उदारतापूर्वक लगाएं।"
                    ]
                ],
                "Urdu" => [
                    "recommended_sunscreen" => "تجویز کردہ سن اسکرین",
                    "reapply_reminder" => "دوبارہ لگانے کی یاد دہانی",
                    "sun_safety_tips" => "سورج سے حفاظت کے نکات",
                    "spf_value" => $spf_recommendation,
                    "reapply_time" => "ہر $reapply_time میں دوبارہ لگائیں",
                    "safety_tips" => [
                        "اضافی تحفظ کے لیے دھوپ کا چشمہ اور ٹوپی پہنیں۔",
                        "صبح 10 بجے سے شام 4 بجے کے درمیان سایہ میں رہیں۔",
                        "ہائیڈریٹ رہیں اور سن اسکرین کو اچھی طرح لگائیں۔"
                    ]
                ],
                "Chinese" => [
                    "recommended_sunscreen" => "推荐的防晒霜",
                    "reapply_reminder" => "重新涂抹提醒",
                    "sun_safety_tips" => "防晒提示",
                    "spf_value" => $spf_recommendation,
                    "reapply_time" => "每 $reapply_time 重新涂抹",
                    "safety_tips" => [
                        "戴上太阳镜和帽子以获得额外保护。",
                        "上午10点到下午4点之间寻找阴凉处。",
                        "保持水分充足，并充分涂抹防晒霜。"
                    ]
                ],
                "Russian" => [
                    "recommended_sunscreen" => "Рекомендуемый солнцезащитный крем",
                    "reapply_reminder" => "Напоминание о повторном применении",
                    "sun_safety_tips" => "Советы по защите от солнца",
                    "spf_value" => $spf_recommendation,
                    "reapply_time" => "Повторно наносите каждые $reapply_time",
                    "safety_tips" => [
                        "Носите солнцезащитные очки и шляпу для дополнительной защиты.",
                        "Находитесь в тени с 10:00 до 16:00.",
                        "Пейте много воды и наносите солнцезащитный крем обильно."
                    ]
                ],
                "Mandarin" => [
                    "recommended_sunscreen" => "推荐的防晒霜",
                    "reapply_reminder" => "重新涂抹提醒",
                    "sun_safety_tips" => "防晒安全提示",
                    "spf_value" => $spf_recommendation,
                    "reapply_time" => "每 $reapply_time 重新涂抹",
                    "safety_tips" => [
                        "佩戴太阳镜和帽子以提供额外保护。",
                        "上午10点到下午4点之间寻找阴凉处。",
                        "保持充足水分，并慷慨地涂抹防晒霜。"
                    ]
                ]
            ];


            // Get Translated Texts
            $translated_texts = $translations[$language];

            ?>


            <main class="main order">
                <div class="page-content pt-10 pb-10">
                    <div class="step-by pt-2 pb-2 pr-4 pl-4">
                        <h3 class="title title-simple title-step visited">
                            <a href="personalized_link.php">1. Fill Form</a>
                        </h3>
                        <h3 class="title title-simple title-step active">
                            <a href="generate_plan.php">2. Your Personalized Plan</a>
                        </h3>
                    </div>

                    <div class="container mt-8">
                        <div class="order-message">
                            <i class="fas fa-sun"></i> 
                            Thank you, <?php echo htmlspecialchars($name); ?>. Here is your personalized Sun Protection Plan.
                        </div>

                        <div class="order-results pt-8 pb-8">
                            <div class="overview-item">
                                <span>Location</span>
                                <strong><?php echo htmlspecialchars($location); ?></strong>
                            </div>
                            <div class="overview-item">
                                <span>UV Index</span>
                                <strong><?php echo htmlspecialchars($uvIndex); ?> (<?php echo htmlspecialchars($uvLevel); ?>)</strong>
                            </div>
                            <div class="overview-item">
                                <span>Skin Type</span>
                                <strong><?php echo htmlspecialchars($skin_type); ?></strong>
                            </div>
                            <div class="overview-item">
                                <span>Preferred Language</span>
                                <strong><?php echo htmlspecialchars($language); ?></strong>
                            </div>
                        </div>

                       
                        

                        <section class="mt-7">
                            <div class="row">
                                <!-- Left Side: Recommended Sunscreen & Reapply Reminder -->
                                <div class="col-md-6">
                                    <blockquote class="text-bd-left sun-blockquote">
                                        <h4>🌞 Recommended Sunscreen</h4>
                                        <p><strong><?php echo htmlspecialchars($translations[$language]["spf_value"] ?? $spf_recommendation); ?></strong></p>

                                        <h4>🔄 Reapply Reminder</h4>
                                        <p><strong><?php echo htmlspecialchars($translations[$language]["reapply_time"] ?? "Reapply every $reapply_time"); ?></strong></p>
                                    </blockquote>
                                </div>

                                <!-- Right Side: Sun Safety Tips -->
                                <div class="col-md-6">
                                    <blockquote class="text-bd-left sun-blockquote">
                                        <h4>☀️ Sun Safety Tips</h4>
                                        <ul class="sun-safety-list">
                                            <?php 
                                            $safety_tips = $translations[$language]["safety_tips"] ?? [];
                                            foreach ($safety_tips as $tip) {
                                                echo "<li>✅ " . htmlspecialchars($tip) . "</li>";
                                            }
                                            ?>
                                        </ul>
                                    </blockquote>
                                </div>
                            </div>
                        </section>


                        <br>


                        <!-- Copy Link Button -->
                        <a class="btn btn-primary btn-block btn-icon-right" href="#" id="copyButton">
                            Copy & Share Your Personalized Link <i class="menu-icon"></i>
                        </a>

                        <script>
                            document.getElementById("copyButton").addEventListener("click", function (event) {
                                event.preventDefault(); // Prevent default link behavior

                                // Construct the link dynamically
                                var link = window.location.href; // Get the current page URL

                                // Copy the link to clipboard
                                navigator.clipboard.writeText(link).then(function () {
                                    alert("🎉 Success! Your personalized link is copied and ready to share.");
                                }).catch(function (err) {
                                    console.error("Failed to copy: ", err);
                                });
                            });
                        </script>

                        <br><br>

                        <!-- Back to Form -->
                        <a href="personalized_link.php" class="btn btn-icon-left btn-back btn-md mb-4">
                            <i class="d-icon-arrow-left"></i> Back to Form
                        </a>
                    </div>
                </div>
            </main>


        <!-- END -->


        <?PHP include "include/footer.php"?>
    </div>
    
    <?PHP include "include/mobilemenu.php"?>
    <?PHP include "include/script.php"?>

    
</body>
</html>