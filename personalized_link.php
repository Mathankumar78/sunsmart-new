<!DOCTYPE html>
<html lang="en">

<?PHP include "include/head.php"?>


<body>
    
    <div class="page-wrapper">
        <?PHP include "include/header.php"?>

        <main class="main">
            <div class="page-header" style="background-image: url(images/page-header.jpg)">
                <h1 class="page-title">GET YOUR PERSONALIZED SUN PROTECTION PLAN</h1>
            </div>
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><a href="index.php"><i class="d-icon-home"></i></a></li>
                        <li>Personalized Link</li>
                    </ul>
                </div>
            </nav>
            <div class="page-content mt-6">
            <div class="container">
                <div class="row gutter-lg">
                    <div class="col-lg-9">
                        <div class="reply">
                            <div class="title-wrapper text-left">
                                <h3 class="title title-simple text-left text-normal">
                                    Get Your Personalized Sun Protection Plan
                                </h3>
                                <p>Answer a few quick questions and get tailored sunscreen recommendations just for you.</p>
                            </div>
                            <form action="generate_plan.php" method="GET">
                                <div class="row">
                                    <!-- Name -->
                                    <div class="col-md-6 mb-4">
                                        <input type="text" class="form-control input-custom" id="name" name="name" placeholder="Your Name *" required />
                                    </div>
                                    <!-- Email -->
                                    <div class="col-md-6 mb-4">
                                        <input type="email" class="form-control input-custom" id="email" name="email" placeholder="Your Email" />
                                    </div>

                                    <!-- Select City -->
                                    <div class="col-md-6 mb-4">
                                        <div class="select-wrapper">
                                            <select class="form-control input-custom" id="location" name="location" onchange="updateCoordinates()" required>
                                                <option value="" data-lat="" data-lon="">-- Select City --</option>
                                                <option value="Sydney" data-lat="-33.8688" data-lon="151.2093">Sydney</option>
                                                <option value="Melbourne" data-lat="-37.8136" data-lon="144.9631">Melbourne</option>
                                                <option value="Brisbane" data-lat="-27.4698" data-lon="153.0251">Brisbane</option>
                                                <option value="Perth" data-lat="-31.9505" data-lon="115.8605">Perth</option>
                                                <option value="Adelaide" data-lat="-34.9285" data-lon="138.6007">Adelaide</option>
                                                <option value="Gold Coast" data-lat="-28.0167" data-lon="153.4000">Gold Coast</option>
                                                <option value="Canberra" data-lat="-35.2809" data-lon="149.1300">Canberra</option>
                                                <option value="Hobart" data-lat="-42.8821" data-lon="147.3272">Hobart</option>
                                                <option value="Darwin" data-lat="-12.4634" data-lon="130.8456">Darwin</option>
                                                <option value="Newcastle" data-lat="-32.9283" data-lon="151.7817">Newcastle</option>
                                                <option value="Wollongong" data-lat="-34.4240" data-lon="150.8931">Wollongong</option>
                                                <option value="Geelong" data-lat="-38.1499" data-lon="144.3617">Geelong</option>
                                                <option value="Cairns" data-lat="-16.9203" data-lon="145.7710">Cairns</option>
                                                <option value="Townsville" data-lat="-19.2589" data-lon="146.8169">Townsville</option>
                                                <option value="Toowoomba" data-lat="-27.5598" data-lon="151.9507">Toowoomba</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Gender -->
                                    <div class="col-md-6 mb-4">
                                        <div class="select-wrapper">
                                            <select class="form-control input-custom" id="gender" name="gender" required>
                                                <option value="" disabled selected>Select Gender *</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Skin Type -->
                                    <div class="col-md-6 mb-4">
                                        <div class="select-wrapper">
                                            <select class="form-control input-custom" id="skin_type" name="skin_type" required>
                                                <option value="" disabled selected>Select Your Skin Type *</option>
                                                <option value="Type 1 - Light, Pale White">Type 1 - Light, Pale White</option>
                                                <option value="Type 2 - White, Fair">Type 2 - White, Fair</option>
                                                <option value="Type 3 - Medium White to Olive">Type 3 - Medium White to Olive</option>
                                                <option value="Type 4 - Olive">Type 4 - Olive</option>
                                                <option value="Type 5 - Light Brown">Type 5 - Light Brown</option>
                                                <option value="Type 6 - Dark Brown">Type 6 - Dark Brown</option>
                                            </select>
                                        </div>
                                        <small><a href="skintype.php" class="help-link">Having trouble identifying your skin type?</a></small>
                                    </div>

                                    <!-- Preferred Language -->
                                    <div class="col-md-6 mb-4">
                                        <div class="select-wrapper">
                                            <select class="form-control input-custom" id="language" name="language" required>
                                                <option value="" disabled selected>Select Preferred Language *</option>
                                                <option value="English">English</option>
                                                <option value="Chinese">Chinese</option>
                                                <option value="Urdu">Urdu</option>
                                                <option value="Hindi">Hindi</option>
                                                <option value="Russian">Russian</option>
                                                <option value="Mandarin">Mandarin</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Button -->
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-md custom-btn">
                                        GENERATE MY SUN PROTECTION PLAN <i class="d-icon-arrow-right"></i>
                                    </button>
                                </div>
                            </form>

                            <script>
                                function updateCoordinates() {
                                    let locationSelect = document.getElementById("location");
                                    let selectedOption = locationSelect.options[locationSelect.selectedIndex];

                                    document.getElementById("latitude").value = selectedOption.getAttribute("data-lat");
                                    document.getElementById("longitude").value = selectedOption.getAttribute("data-lon");
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- End Main -->


        <?PHP include "include/footer.php"?>
    </div>
    
    <?PHP include "include/mobilemenu.php"?>
    <?PHP include "include/script.php"?>
    <style>
    /* Styling Input Fields */
    .input-custom {
        font-size: 16px;
        padding: 12px;
        border: 2px solid #ccc;
        border-radius: 8px;
        background-color: #fff;
        transition: all 0.3s ease-in-out;
    }

    .input-custom:focus {
        border-color: #ff8c42;
        box-shadow: 0px 0px 8px rgba(255, 140, 66, 0.5);
    }

    /* Styling Select Fields */
    .select-wrapper {
        position: relative;
    }

    .select-wrapper select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background: white url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black"><path d="M7 10l5 5 5-5z"/></svg>') no-repeat right 10px center;
        background-size: 18px;
        padding-right: 35px;
    }

    /* Help Link */
    .help-link {
        font-size: 14px;
        color: #ff8c42;
        text-decoration: none;
        margin-top: 5px;
        display: inline-block;
    }

    .help-link:hover {
        text-decoration: underline;
    }
</style>
</body>
</html>
