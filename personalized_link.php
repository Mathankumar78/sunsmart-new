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
                                    <div class="col-md-6 mb-5">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Your Name *" required />
                                    </div>
                                    <!-- Email -->
                                    <div class="col-md-6 mb-5">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Your Email"/>
                                    </div>
                                    
                                
                                    <div class="col-md-6 mb-5">
                                        <select class="form-control" id="location" name="location" onchange="updateCoordinates()" required>
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
                                        <input type="hidden" id="latitude" name="latitude">
                                        <input type="hidden" id="longitude" name="longitude">
                                    </div>
                                    <!-- Gender -->
                                    <div class="col-md-6 mb-5">
                                        <select class="form-control" id="gender" name="gender" required>
                                            <option value="" disabled selected>Select Gender *</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <!-- Skin Type -->
                                    <div class="col-md-6 mb-5">
                                        <select class="form-control" id="skin_type" name="skin_type" required>
                                            <option value="" disabled selected>Select Your Skin Type *</option>
                                            <option value="Fair">Fair</option>
                                            <option value="Medium">Medium</option>
                                            <option value="Dark">Dark</option>
                                            <option value="Sensitive">Sensitive</option>
                                        </select>
                                    </div>
                                    <!-- Preferred Language -->
                                    <div class="col-md-6 mb-5">
                                        <select class="form-control" id="language" name="language" required>
                                            <option value="" disabled selected>Select Preferred Language *</option>
                                            <option value="English">English</option>
                                            <option value="Chinese">Chinese</option>
                                            <option value="Urdu">Urdu</option>
                                            <option value="Hindi">Hindi</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-md">
                                    GENERATE MY SUN PROTECTION PLAN <i class="d-icon-arrow-right"></i>
                                </button>
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
                            <!-- End Form Section -->
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
</body>
</html>
