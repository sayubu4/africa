
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore East African Cultures</title>
    <!-- Include a CSS library for the carousel -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F5E6D3;
            color: #2F1B14;
        }

        header {
            background-color: #8B4513;
            color: #F5E6D3;
            text-align: center;
            padding: 0.2em 0; /* Reduced padding to make the header smaller */
            font-size: 1em; /* Adjusted font size */
        }

        nav ul {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            background-color: #8B4513;
        }

        nav ul li {
            margin: 0 0.8em; /* Reduced margin between links */
        }

        nav ul li a {
            text-decoration: none;
            color: #FFD700;
            padding: 0.3em 0.8em; /* Reduced padding for smaller links */
            display: block;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        nav ul li a:hover {
            background-color: #FFA500;
        }

        .hero-carousel {
            position: relative;
            width: 100%;
            height: 60vh;
            overflow: hidden;
            background-color: #2F1B14;
        }

        .hero-carousel img {
            width: 100%;
            height: 60vh;
            object-fit: cover;
            cursor: pointer;
        }

        .about {
            padding: 3em 0;
            text-align: center;
            background-color: #F5E6D3;
        }

        .about .container {
            max-width: 800px;
            margin: 0 auto;
        }

        footer {
            background-color: #8B4513;
            color: #F5E6D3;
            text-align: center;
            padding: 1em 0;
        }

        footer .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        footer .social-media {
            display: flex;
            gap: 1em;
        }

        footer .social-media img {
            width: 24px;
            height: 24px;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Explore East African Cultures</h1>
            <nav>
                <ul>
                    <li><a href="burundi.php">Burundi</a></li>
                    <li><a href="rwanda.php">Rwanda</a></li>
                    <li><a href="kenya.php">Kenya</a></li>
                    <li><a href="uganda.php">Uganda</a></li>
                    <li><a href="tanzania.php">Tanzania</a></li>
                    <li><a href="south_sudan.php">South Sudan</a></li>
                    <li><a href="profile.php">See My Profile</a></li>
                    <li><a href="user_feedback.php">Share Your Experiences</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <section class="hero-carousel">
        <div class="carousel">
            <div><img src="images/burundi.jpg" alt="Burundi" onclick="location.href='burundi.php'"></div>
            <div><img src="images/rwanda.jpg" alt="Rwanda" onclick="location.href='rwanda.php'"></div>
            <div><img src="images/kenya.jpg" alt="Kenya" onclick="location.href='kenya.php'"></div>
            <div><img src="images/uganda.jpg" alt="Uganda" onclick="location.href='uganda.php'"></div>
            <div><img src="images/tanzania.jpg" alt="Tanzania" onclick="location.href='tanzania.php'"></div>
            <div><img src="images/south_sudan.jpg" alt="South Sudan" onclick="location.href='south_sudan.php'"></div>
        </div>
    </section>
    <section id="about" class="about">
        <div class="container">
            <h2>About Our Journey</h2>
            <p>Explore East African Cultures is dedicated to bringing you closer to the heart of Africa. From the Maasai warriors of Kenya to the Swahili coast of Tanzania, learn about the diverse cultural landscapes that make East Africa unique.</p>
        </div>
    </section>
    <footer>
        <div class="container">
            <p>&copy; 2024 Explore East African Cultures | <a href="contact.php">Contact Us</a> | <a href="privacy.php">Privacy Policy</a></p>
            <div class="social-media">
                <a href="#"><img src="images/facebook.png" alt="Facebook"></a>
                <a href="#"><img src="images/twitter.png" alt="Twitter"></a>
                <a href="#"><img src="images/instagram.png" alt="Instagram"></a>
            </div>
        </div>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.carousel').slick({
                autoplay: true,
                autoplaySpeed: 2000,
                dots: true,
                arrows: false,
                fade: true,
                cssEase: 'linear'
            });
        });
    </script>
</body>
</html>
