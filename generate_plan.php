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

            // OpenWeather API for UV Index
            $env = parse_ini_file(__DIR__ . "/.env");

            $apiKey = $env['API_KEY'] ?? null;

            if (!$apiKey) {
                die("⚠️ API Key is missing! Check .env file.");
            }
            $uvIndex = 0;
            $apiUrl = "https://api.openweathermap.org/data/2.5/uvi?appid=$apiKey&lat=$latitude&lon=$longitude";

            $response = file_get_contents($apiUrl);
            if ($response !== false) {
                $uvData = json_decode($response, true);
                if (isset($uvData['value'])) {
                    $uvIndex = $uvData['value'];
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
                    "reapply_time" => "Reapply every $reapply_time",
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

                        <!-- Protection Recommendations -->
                        <h2 class="title title-simple text-left pt-3">
                            <?php echo htmlspecialchars($translations[$language]["sun_safety_tips"] ?? "Sun Safety Tips"); ?>
                        </h2>

                        <div class="order-details mb-1">
                            <table class="order-details-table">
                                <thead>
                                    <tr class="summary-subtotal">
                                        <td>
                                            <h3 class="summary-subtitle">
                                                <?php echo htmlspecialchars($translations[$language]["recommended_sunscreen"] ?? "Recommended Sunscreen"); ?>
                                            </h3>
                                        </td>
                                        <td>
                                            <h3 class="summary-subtitle">
                                                <?php echo htmlspecialchars($translations[$language]["spf_value"] ?? $spf_recommendation); ?>
                                            </h3>
                                        </td>      
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="product-name">
                                            <?php echo htmlspecialchars($translations[$language]["reapply_reminder"] ?? "Reapply Reminder"); ?>
                                        </td>
                                        <td class="product-price">
                                            <?php echo htmlspecialchars($translations[$language]["reapply_time"] ?? "Reapply every $reapply_time"); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="product-name">
                                            <?php echo htmlspecialchars($translations[$language]["sun_safety_tips"] ?? "Sun Safety Tips"); ?>
                                        </td>
                                        <td class="product-price">
                                            <ul>
                                                <?php 
                                                $safety_tips = $translations[$language]["safety_tips"] ?? [];
                                                foreach ($safety_tips as $tip) {
                                                    echo "<li>" . htmlspecialchars($tip) . "</li>";
                                                }
                                                ?>
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

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