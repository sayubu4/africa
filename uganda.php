<?php
require_once 'db_config.php';
require_once 'session_check.php';
$country_name = "Uganda";
$hero_image = "uganda.jpg";
$cultural_insights = array(
    "Customs" => array(
    "description" => "Ugandan culture is diverse and vibrant, with various traditional customs and rituals. These include the Baganda's Kabaka coronation ceremonies and the traditional dance performances that highlight the country's cultural richness.",
    "foods" => array(
        "Matoke" => "A staple dish made from steamed green bananas, often served with a meat or vegetable sauce.",
        "Luwombo" => "A traditional dish of meat or ground nuts steamed in banana leaves, offering a unique flavor.",
        "Posho" => "A type of maize flour porridge, commonly served with beans or stew."
    )
),
"Cuisine" => array(
    "description" => "Ugandan cuisine utilizes a variety of local ingredients such as bananas, groundnuts, and beans. Popular dishes include Eshabwe (a ghee sauce) and Rolex (a chapati rolled with eggs and vegetables).",
    "foods" => array(
        "Eshabwe" => "A traditional sauce made from ghee, often served with millet bread or matoke.",
        "Rolex" => "A popular street food consisting of a chapati rolled with eggs and vegetables.",
        "Malewa" => "Bamboo shoots, typically boiled and mixed with groundnut sauce."
    )
),
"Clothing" => array(
    "description" => "The traditional attire of Uganda includes the Gomesi for women and the Kanzu for men. These garments reflect the country's cultural diversity and heritage.",
    "traditional_items" => array(
        "Gomesi" => "A colorful traditional dress worn by women, featuring a sash around the waist and puffed sleeves.",
        "Kanzu" => "A traditional white or cream robe worn by men, often during formal occasions.",
        "Barkcloth" => "A traditional fabric made from the bark of the Mutuba tree, used for various ceremonial purposes."
    )
)
);

$tourist_attractions = array(
    array(
        "image" => "bwindi_park.jpg",
        "title" => "Bwindi Impenetrable National Park",
        "description" => "Bwindi Impenetrable National Park is a UNESCO World Heritage Site known for its mountain gorillas and dense rainforest.",
        "full_description" => "Bwindi Impenetrable National Park, located in southwestern Uganda, is famous for being one of the last places in the world to see endangered mountain gorillas in their natural habitat. Visitors can go on guided treks to observe these magnificent creatures while exploring the dense forest. The park is also home to various bird species, primates, and diverse flora, making it a paradise for nature lovers and wildlife enthusiasts.",
        "location" => "Southwestern Uganda",
        "best_time_to_visit" => "June to September (dry season)"
    ),
    array(
        "image" => "rwenzori.jpg",
        "title" => "Rwenzori Mountains",
        "description" => "The Rwenzori Mountains are known for their stunning landscapes, glaciers, and biodiversity, often referred to as the 'Mountains of the Moon.'",
        "full_description" => "The Rwenzori Mountains, located on the border between Uganda and the Democratic Republic of Congo, are a UNESCO World Heritage Site and offer some of the most scenic trekking routes in Africa. With towering peaks, glaciers, and unique flora and fauna, the mountains provide an extraordinary adventure for trekkers. The Rwenzori Mountains National Park is home to diverse species, including endemic plants and wildlife.",
        "location" => "Western Uganda",
        "best_time_to_visit" => "June to September (dry season)"
    ),
    array(
        "image" => "kampala.jpg",
        "title" => "Kampala City",
        "description" => "Kampala, the capital city of Uganda, is a vibrant hub of culture, history, and modern urban life.",
        "full_description" => "Kampala is the capital and largest city of Uganda, located near Lake Victoria. It is a city of contrasts, with a blend of traditional Ugandan culture and modernity. Visitors can explore a variety of attractions, including the Uganda National Museum, the Kasubi Tombs (a UNESCO World Heritage Site), and the bustling markets. Kampala also offers vibrant nightlife, parks, and a growing food scene.",
        "location" => "Central Uganda",
        "best_time_to_visit" => "June to September"
    )
);

$user_experiences = array ();
$upcoming_events = array (
  0 => 
  array (

    'title' => ' Rolex Festival',
    'date' => 'August 20, 2024',
    'description' => '
     A culinary event celebrating Uganda\'s popular street food the "Rolex" (a rolled chapati with eggs) with cooking competitions tastings.',
  ),
  1 => 
  array (

    'title' => 'Nyege Nyege Festival',
    'date' => 'September 6-9, 2024',
    'description' => 'An international music and arts festival held near the Nile River, featuring diverse musical genres and artists from around the world.',
  ),
  2 => 
  array (

    'title' => 'Kampala City Festival',
    'date' => 'October 5, 2024',
    'description' => 'A vibrant street festival with parades, music, dance, and food, celebrating the culture and diversity of Kampala',
  ),

);
function loadUserExperiences() {
    $filename = 'burundi_experiences.txt';
    if (file_exists($filename)) {
        $experiences = json_decode(file_get_contents($filename), true);
        return $experiences ? $experiences : array();
    }
    return array();
}

$user_experiences = loadUserExperiences();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Burundi - East African Cultures</title>
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

        .hero {
            height: 60vh;
            background-image: url('images/<?php echo $hero_image; ?>');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #F5E6D3;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .hero h1 {
            font-size: 3em;
            margin-bottom: 0.2em;
        }

        .content {
            padding: 4em 0;
        }

        .content .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            color: #8B4513;
            font-size: 2em;
            margin-bottom: 1em;
        }

        .cultural-insights,
        .tourist-attractions,
        .user-experiences,
        .upcoming-events {
            margin-bottom: 4em;
        }

        /* Cultural Insights Styling */
        .cultural-insights-grid,
        .tourist-attractions-grid,
        .user-experiences-grid,
        .upcoming-events-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2em;
        }

        .cultural-insights-item,
        .tourist-attractions-item,
        .upcoming-events-item {
            background-color: #8B4513;
            color: #F5E6D3;
            padding: 2em;
            border-radius: 5px;
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .cultural-insights-item:hover,
        .tourist-attractions-item:hover {
            transform: scale(1.05);
        }

        .cultural-insights-item h3,
        .upcoming-events-item h3 {
            margin-top: 0;
            color: #FFD700;
        }

        .cultural-insights-item img,
        .tourist-attractions-item img,
        .user-experiences-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 1em;
        }

        .tourist-attractions-item h3,
        .tourist-attractions-item p {
            color: #F5E6D3;
        }

        .user-experiences-item p {
            margin-top: 1em;
        }
        .user-experiences-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2em;
    }

    .user-experience {
        background-color: #8B4513;
        color: #F5E6D3;
        padding: 2em;
        border-radius: 5px;
        transition: transform 0.3s ease;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .user-experience:hover {
        transform: scale(1.05);
    }

    .user-experience img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 5px;
        margin-bottom: 1em;
    }

    .user-experience p {
        text-align: center;
        color: #F5E6D3;
        margin: 0;
    }

        footer {
            background-color: #8B4513;
            color: #F5E6D3;
            padding: 1.5em 0;
            text-align: center;
        }

        footer .container {
            max-width: 1200px;
            margin: 0 auto;
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

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.7);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #F5E6D3;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 10px;
            color: #2F1B14;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #8B4513;
        }

        /* Button Styles */
        .btn {
            display: inline-block;
            background-color: #8B4513;
            color: #F5E6D3;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #FFA500;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Explore East African Cultures</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="rwanda.php">Rwanda</a></li>
                    <li><a href="kenya.php">Kenya</a></li>
                    <li><a href="uganda.php">Uganda</a></li>
                    <li><a href="tanzania.php">Tanzania</a></li>
                    <li><a href="south_sudan.php">South Sudan</a></li>
                    <li><a href="user_feedback.php">Share your experiences</a></li>
                    <li><a href="profile.php">See My Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
               
            </nav>
        </div>
    </header>

    <section class="hero">
        <div>
            <h1>Discover Uganda</h1>
            <p>Explore the rich cultural heritage and natural wonders of this East African gem.</p>
        </div>
    </section>

    <section class="content">
        <div class="container">
            <div class="cultural-insights">
                <h2 class="section-title">Culture And Traditions</h2>
                <div class="cultural-insights-grid">
                    <?php foreach ($cultural_insights as $title => $insight) { ?>
                        <div class="cultural-insights-item" onclick="openModal('<?php echo $title; ?>')">
                        <img src="images/uganda_<?php echo strtolower(str_replace(' ', '_', $title)) . '.jpg'; ?>" alt="<?php echo $title; ?>" />
                            <h3><?php echo $title; ?></h3>
                            <p><?php echo $insight['description']; ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="tourist-attractions">
                <h2 class="section-title">Touristic Sites</h2>
                <div class="tourist-attractions-grid">
                    <?php foreach ($tourist_attractions as $index => $attraction) { ?>
                        <div class="tourist-attractions-item" onclick="openAttractionModal(<?php echo $index; ?>)">
                            <h3><?php echo $attraction['title']; ?></h3>
                            <img src="images/<?php echo $attraction['image']; ?>" alt="<?php echo $attraction['title']; ?>">
                            <p><?php echo $attraction['description']; ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="user-experiences">
    <h2 class="section-title">Traveler Stories: Personal Experiences</h2>
    <div class="user-experiences-grid">
        <?php
        // Retrieve experiences from the database
        $sql = "SELECT 
        name,
        country,
        experience, 
        COALESCE(photos, 'default_image.jpg') AS photos 
    FROM user_experiences 
    ORDER BY id DESC 
    LIMIT 10";
        
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($experience = $result->fetch_assoc()) {
                // Determine image path
                $image = (filter_var($experience['photos'], FILTER_VALIDATE_URL)) 
                    ? $experience['photos'] 
                    : 'uploads/' . basename($experience['photos']);
        ?>
            <div class="user-experience">
                <img src="<?php echo htmlspecialchars($image); ?>" alt="User Experience">
                <p><?php echo htmlspecialchars($experience['experience']); ?></p>
            </div>
        <?php 
            }
        } else {
            echo "<p>No experiences found.</p>";
        }
        ?>
    </div>
</div>
            <div class="upcoming-events">
                <h2 class="section-title">Upcoming Events</h2>
                <div class="upcoming-events-grid">
                    <?php foreach ($upcoming_events as $event) { ?>
                        <div class="upcoming-events-item">
                            <h3><?php echo $event['title']; ?></h3>
                            <p><?php echo $event['date']; ?></p>
                            <p><?php echo $event['description']; ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Modals for Cultural Insights -->
    <?php foreach ($cultural_insights as $title => $insight) { ?>
        <div id="<?php echo $title; ?>Modal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('<?php echo $title; ?>')">&times;</span>
                <h2><?php echo $title; ?></h2>
                <p><?php echo $insight['description']; ?></p>
                
                <?php if (isset($insight['foods'])): ?>
                    <h3>Traditional Foods</h3>
                    <div>
                        <?php foreach ($insight['foods'] as $food => $description): ?>
                            <h4><?php echo $food; ?></h4>
                            <p><?php echo $description; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($insight['traditional_items'])): ?>
                    <h3>Traditional Items</h3>
                    <div>
                        <?php foreach ($insight['traditional_items'] as $item => $description): ?>
                            <h4><?php echo $item; ?></h4>
                            <p><?php echo $description; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php } ?>

    <!-- Modals for Tourist Attractions -->
    <?php foreach ($tourist_attractions as $index => $attraction) { ?>
        <div id="attraction<?php echo $index; ?>Modal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeAttractionModal(<?php echo $index; ?>)">&times;</span>
                <h2><?php echo $attraction['title']; ?></h2>
                <img src="images/<?php echo $attraction['image']; ?>" alt="<?php echo $attraction['title']; ?>" style="width:100%; max-height:400px; object-fit:cover;">
                <p><?php echo $attraction['full_description']; ?></p>
                <h3>Location</h3>
                <p><?php echo $attraction['location']; ?></p>
                <h3>Best Time to Visit</h3>
                <p><?php echo $attraction['best_time_to_visit']; ?></p>

            </div>
        </div>
    <?php } ?>

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

    <script>
        function openModal(title) {
            var modal = document.getElementById(title + 'Modal');
            modal.style.display = "block";
        }

        function closeModal(title) {
            var modal = document.getElementById(title + 'Modal');
            modal.style.display = "none";
        }

        function openAttractionModal(index) {
            var modal = document.getElementById('attraction' + index + 'Modal');
            modal.style.display = "block";
        }

        function closeAttractionModal(index) {
            var modal = document.getElementById('attraction' + index + 'Modal');
            modal.style.display = "none";
        }

        // Close modal if user clicks outside of it
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = "none";
            }
        }
    </script>
</body>
</html>