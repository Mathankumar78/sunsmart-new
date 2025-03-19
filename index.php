<?PHP 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">

<?PHP include "include/head.php"?>

<body class="home">
    <div class="page-wrapper">
        <h1 class="d-none">SunSmart</h1>

        <?PHP include "include/header.php"?>

        <main class="main">
            <div class="page-content">
                <section class="intro-section">
                    <div class="owl-carousel owl-theme row owl-dot-inner owl-dot-white intro-slider animation-slider cols-1 gutter-no"
                        data-owl-options="{
                        'nav': false,
                        'dots': true,
                        'loop': true,
                        'items': 1,
                        'autoplay': true,
                        'autoplayTimeout': 8000
                    }">
                        <div class="banner banner-fixed intro-slide1" style="background-color: #dddee0;">
                            <figure>
                                <div class="slide-effect">
                                    <img src="images/banner.png"
                                        class="slide-animate intro-slide1-img"
                                        data-animation-options="{'name': 'fadeInLeftShorter', 'duration': '1.2s', 'delay': '.5s'}"
                                        alt="intro-banner" width="1180" height="630" />
                                    <div class="float-svg-container float-svg-1 slide-animate"
                                        data-animation-options="{'name': 'fadeInRightShorter', 'duration': '1s', 'delay': '.2s'}">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" id="Layer_1" x="0px" y="0px"
                                            width="878.874px" height="6.792px" viewBox="0 -10 146.479 122.632"
                                            class="float-svg" data-float-options="{'delta':10,'speed':2,'size':6}"
                                            xml:space="preserve">
                                            <path
                                                d="M130.932,84.665c3.185,-2.144,6.110,-4.998,8.531,-8.520c10.110,-13.610,8.467,-30.072,0.332,-42.346c-6.039,-8.390,-14.489,-13.797,-22.688,-18.422C101.596,6.864,81.881,4.547,61.349,12.108C43.416,13.790,27.653,21.203,.990,33.373C4.844,46.946,-9.593,72.549,8.790,86.890c12.138,12.717,28.065,13.809,41.389,14.725c11.112,-0.221,22.039,-2.444,33.020,-6.916c13.288,-8.161,19.548,-6.340,31.060,-4.985C119.789,90.341,125.709,88.455,130.932,84.665z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="float-svg-container float-svg-2 slide-animate"
                                        data-animation-options="{'name': 'fadeInRightShorter', 'duration': '1s', 'delay': '.2s'}">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" id="Layer_2" x="0px" y="0px"
                                            width="710.678px" height="543.094px" viewBox="0 -10 165.274 146.301"
                                            class="float-svg" data-float-options="{'delta':10,'speed':1,'size':4.3}"
                                            xml:space="preserve">
                                            <path
                                                d="M93.301,135.1c-19.925,-5.702,-39.601,-19.594,-56.258,-35.144C16.933,85.792,0.546,68.421,0.0,43.508C-0.381,24.953,7.203,1.282,26.057,-7.618c4.479,-1.375,9.280,-1.435,14.057,-0.425c9.430,2.734,18.328,8.193,26.058,16.592c6.131,6.640,11.089,14.306,17.716,20.035c18.042,13.179,26.139,3.024,51.780,8.336c9.167,0.554,18.8,1.6,24.192,7.641c9.444,11.897,5.473,33.018,-3.772,49.940C142.467,120.034,116.856,135.567,93.301,135.1z" fill="#FF7F50" stroke="none">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </figure>
                            <div class="container">

                                <div class="banner-content y-50">
                                    <h4 class="banner-subtitle text-uppercase font-weight-bold ls-l mb-2">
                                        <span class="d-inline-block slide-animate"
                                            data-animation-options="{'name': 'fadeInRightShorter', 'duration': '1s', 'delay': '.2s'}">GET YOUR</span>
                                        <span class="d-inline-block label-star bg-primary text-white slide-animate"
                                            data-animation-options="{'name': 'fadeInRightShorter', 'duration': '1s', 'delay': '.4s'}">
                                            Free</span>
                                    </h4>
                                    <h3 class="banner-title text-uppercase font-weight-bold text-white lh-1 ls-l slide-animate"
                                        data-animation-options="{'name': 'fadeInUpShorter', 'duration': '1.2s', 'delay': '1s'}">
                                        Personalized Link</h3>
                                    <h3 class="font-weight-normal mb-1 slide-animate" data-animation-options="{
                                        'name': 'fadeInUpShorter',
                                        'duration': '1.2s',
                                        'delay': '1s'
                                    }">
                                        <strong>Stay SunSmart </strong> & Protect Your Skin!
                                    </h3>
                                    <p class="slide-animate ls-m text-dark font-primary mb-6"
                                        data-animation-options="{'name': 'fadeInUpShorter', 'duration': '1.2s', 'delay': '1s'}">
                                        Instant Access – No Signup Needed!</p>
                                    <a href="personalized_link.php" class="btn btn-outline btn-dark slide-animate"
                                        data-animation-options="{'name': 'fadeInUpShorter', 'duration': '1s', 'delay': '1.8s'}">GET MY LINK NOW</a>
                                </div>
                            </div>
                        </div>
                       


                    </div>

                    <div class="service-list container appear-animate">
                        <div class="owl-carousel owl-theme row cols-lg-3 cols-sm-2 cols-1" data-owl-options="{
                                'items': 3,
                                'nav': false,
                                'dots': false,
                                'margin': 20,
                                'autoplay': true,
                                'autoplayTimeout': 5000,
                                'responsive': {
                                    '0': {
                                        'items': 1
                                    },
                                    '576': {
                                        'items': 2
                                    },
                                    '992': {
                                        'items': 3,
                                        'loop': false
                                    }
                                }
                            }">
                            <a href="hindsight.php">
                            <div class="icon-box icon-box-side icon-box1 appear-animate" data-animation-options="{
                                    'name': 'fadeInRightShorter',
                                    'delay': '.3s'
                                }">
                               
                                <i class="icon-box-icon fas fa-sun"></i>
                              
                                <div class="icon-box-content">
                                    <h4 class="icon-box-title">UV Protection advice</h4>
                                    <p>Personalized sun safety tips!</p>
                                </div>
                            </div>
                            </a>

                            <a href="personalized_link.php">
                            <div class="icon-box icon-box-side icon-box2 appear-animate" data-animation-options="{
                                    'name': 'fadeInRightShorter',
                                    'delay': '.4s'
                                }">
                               
                                <i class="icon-box-icon fas fa-address-card"></i>
                                
                                <div class="icon-box-content">
                                    <h4 class="icon-box-title">Get Your Personalized Link</h4>
                                    <p>Stay SunSmart with skin protection guidance.</p>
                                </div>
                            </div>
                            </a>

                            <a href="skintype.php">
                            <div class="icon-box icon-box-side icon-box3 appear-animate" data-animation-options="{
                                    'name': 'fadeInRightShorter',
                                    'delay': '.5s'
                                }">
                                <i class="icon-box-icon fas fa-grin-beam-sweat"></i>
                                <div class="icon-box-content">
                                    <h4 class="icon-box-title">Get to know your skin type</h4>
                                    <p>Find out your skin type & stay sun-safe! 🌞</p>
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>
                </section>

                <section class="grey-section pt-10 pb-10 appear-animate" data-animation-options="{
                        'delay': '.3s'
                    }">
                    
                </section>


            </div>
        </main>
        <!-- End of Main -->

        <?PHP include "include/footer.php"?>
        
    </div>

    
    
    <?PHP include "include/mobilemenu.php"?>
    <?PHP include "include/script.php"?>
    
</body>

</html>