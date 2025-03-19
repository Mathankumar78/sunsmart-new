<!DOCTYPE html>
<html lang="en">

<?php include "include/head.php"; ?>

<body>
    <div class="page-wrapper">
        <?php include "include/header.php"; ?>

        <main class="main">
            <div class="page-header" style="background-image: url(images/page-header.jpg)">
                <h1 class="page-title">Get to Know your Skin Type</h1>
                <ul class="breadcrumb">
                    <li><a href="index.php"><i class="d-icon-home"></i></a></li>
                    <li><a href="#">Skintype</a></li>
                </ul>
            </div>

            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><a href="index.php"><i class="d-icon-home"></i></a></li>
                        <li><a href="#" class="active">Get to know your skin type</a></li>
                    </ul>
                </div>
            </nav>
            
            
            <div class="page-content mt-6">
                <div class="container">
                    <div class="row gutter-lg">
                        <div class="col-lg-9">
                            <article class="post-single">
                                
                                <div class="post-details">
                                    
                                    <h4 class="post-title"><a href="">Find Your Skin Type & Stay Protected</a></h4>
                                
                                    <br>
                                    <figure class="post-media">
                                        <a href="#">
                                            <img src="images/Skin_type.png" width="870" height="420" alt="post" />
                                        </a>
                                    </figure>
                                
                                    <br><br>
                                
                                    <div class="post-body">
                                        <p>Understanding your skin type is essential for choosing the right sun protection measures. The Fitzpatrick Skin Type classification is widely used to determine how different skin tones react to sun exposure, helping individuals take the right precautions to prevent sun damage and reduce the risk of skin cancer.</p>
                                
                                        <p>There are six skin types, ranging from very fair (Type 1) to deeply pigmented (Type 6). The way your skin reacts to sun exposure—whether it burns easily or tans—determines your classification. Identifying your type can help you tailor your skincare routine and sun safety habits accordingly.</p>
                                
                                        <div class="with-img">
                                            <figure>
                                                <img src="images/skin_tone.png" alt="Different Skin Types">
                                            </figure>
                                            <div>
                                                <h4>How to Determine Your Skin Type</h4>
                                                <ul class="list-type-check">
                                                    <li><strong>Type 1 (Light, Pale White):</strong> Always burns, never tans. Extremely sensitive to UV radiation.</li>
                                                    <li><strong>Type 2 (White, Fair):</strong> Usually burns, tans with difficulty. Requires high SPF protection.</li>
                                                    <li><strong>Type 3 (Medium White to Olive):</strong> Sometimes mild burns, tans to a light olive shade.</li>
                                                    <li><strong>Type 4 (Olive):</strong> Rarely burns, tans with ease to moderate brown.</li>
                                                    <li><strong>Type 5 (Light Brown):</strong> Very rarely burns, tans easily and deeply.</li>
                                                    <li><strong>Type 6 (Dark Brown):</strong> Never burns, deeply pigmented, naturally high UV protection.</li>
                                                </ul>
                                            </div>
                                        </div>
                                
                                        <div class="highlight-box">
                                            🌞 Regardless of skin type, everyone is at risk of UV damage.  
                                            Using broad-spectrum sunscreen, wearing protective clothing, and limiting sun exposure during peak hours are key preventive measures.
                                        </div>

                                        <br>
                                
                                        <p>For a personalized skin type analysis, click the button below to take our quick skin type assessment.</p>

                                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                            <a href="personalized_link.php" class="btn btn-primary ">Take the Skin Type</a>
                                        </div>

                                        <br><br>
                                       
                                       
                                    </div>
                                    
                                </div>
                            </article>
                            
                            
                        </div>

                        
                        
                    </div>
                </div>
            </div>
        </main>

        <?php include "include/footer.php"; ?>
    </div>

    <?php include "include/mobilemenu.php"; ?>
    <?php include "include/script.php"; ?>
  

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
    /* .post-details {
        background: #f9f9f9;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    } */

    .post-title {
        font-size: 28px;
        font-weight: bold;
        text-align: center;
        color: #333;
    }

    .post-title a {
        text-decoration: none;
        color: #333;
        transition: color 0.3s ease-in-out;
    }

    .post-title a:hover {
        color: #e67e22;
    }

    .post-media {
        text-align: center;
        margin-bottom: 20px;
    }

    .post-media img {
        border-radius: 8px;
        transition: transform 0.3s ease-in-out;
    }

    .post-media img:hover {
        transform: scale(1.05);
    }

    .post-body {
        font-size: 18px;
        color: #555;
        line-height: 1.6;
        margin-top: 20px;
    }

    .post-body p {
        margin-bottom: 20px;
    }

    .with-img {
        display: flex;
        align-items: center;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        margin: 30px 0;
    }

    .with-img figure {
        flex: 1;
        text-align: center;
    }

    .with-img img {
        max-width: 100%;
        border-radius: 8px;
    }

    .with-img div {
        flex: 2;
        padding: 20px;
    }

    .with-img h4 {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }

    .list-type-check {
        list-style: none;
        padding-left: 0;
    }

    .list-type-check li {
        font-size: 18px;
        margin: 10px 0;
        padding-left: 25px;
        position: relative;
        font-weight: bold;
        color: #444;
    }

    .list-type-check li::before {
        content: "✔";
        color: #e67e22;
        font-size: 20px;
        position: absolute;
        left: 0;
        top: 0;
    }

    .highlight-box {
        background: #ffe6cc;
        padding: 20px;
        border-left: 5px solid #e67e22;
        border-radius: 8px;
        margin-top: 20px;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        color: #333;
    }

    .button-container {
    display: flex;
    justify-content: center;
    margin-top: 20px;
    }

    .btn-custom {
        background: #e67e22;
        color: white;
        font-size: 18px;
        font-weight: bold;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none; /* Removes underline */
        transition: background 0.3s ease-in-out;
        display: inline-block;
        text-align: center;
    }

    .btn-custom:hover {
        background: #d35400;
    }


   
</style>

</body>
</html>
