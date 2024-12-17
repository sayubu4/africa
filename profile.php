<?php
require_once 'db_config.php';
require_once 'session_check.php';
// Handle experience deletion
if (isset($_POST['delete_experience']) && isset($_POST['experience_id'])) {
    $experience_id = mysqli_real_escape_string($conn, $_POST['experience_id']);
    
    // First, get the photo to delete
    $photo_query = "SELECT photos FROM user_experiences WHERE id = '$experience_id'";
    $photo_result = mysqli_query($conn, $photo_query);
    
    if ($photo_row = mysqli_fetch_assoc($photo_result)) {
        // Delete associated image file
        $image_path = 'uploads/' . $photo_row['photos'];
        if (file_exists($image_path) && !is_dir($image_path)) {
            unlink($image_path);
        }
    }
    
    // Delete the experience from database
    $delete_query = "DELETE FROM user_experiences WHERE id = '$experience_id'";
    mysqli_query($conn, $delete_query);
    
    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch user experiences
// Fetch user experiences
$experiences_query = "SELECT id, country, experience, photos 
                      FROM user_experiences";
$experiences_result = mysqli_query($conn, $experiences_query);

if (!$experiences_result) {
    // This will help you debug query issues
    die("Query failed: " . mysqli_error($conn));
}
?>;

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Uploaded Experiences</title>
    <style>
        :root {
          --primary-color: #8B4513;
          --secondary-color: #FFA500;
          --text-color: #2F1B14;
          --background-color: #F5E6D3;
          --card-background: #FFFFFF;
}

        body {
            font-family: 'Arial', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .page-title {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 30px;
            padding-top: 20px; 
        }
        .back-home-btn {
            margin-bottom: 40px;  /* Override previous margin-top */
}

        .user-experiences-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .user-experience-card {
            background-color: var(--card-background);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .user-experience-card:hover {
            transform: scale(1.02);
        }

        .experience-image img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .experience-details {
            padding: 15px;
        }

        .experience-location {
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .experience-description {
            color: var(--text-color);
            margin-bottom: 15px;
        }

        .experience-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .experience-date {
            color: #666;
            font-size: 0.9em;
        }

        .delete-btn {
            background-color: #8B4513;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .delete-btn:hover {
            background-color: #FFA500;
        }

        .no-experiences {
            text-align: center;
            padding: 50px 0;
            background-color: var(--card-background);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 15px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        @media (max-width: 768px) {
            .user-experiences-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <section class="profile-experiences">
        <div class="container">
            <h1 class="page-title">My Uploaded Experiences</h1>
            <a href="index.php" class="btn-primary back-home-btn">Back to Home</a>
            
            <?php if (mysqli_num_rows($experiences_result) > 0): ?>
                <div class="user-experiences-grid">
                    <?php while($experience = mysqli_fetch_assoc($experiences_result)): ?>
                        <div class="user-experience-card">
                            <?php 
                            // Determine image path
                            $image = (filter_var($experience['photos'], FILTER_VALIDATE_URL))
                                ? $experience['photos']
                                : 'uploads/' . basename($experience['photos']);
                            ?>
                            <div class="experience-image">
                                <img src="<?php echo htmlspecialchars($image); ?>" alt="Travel Experience">
                            </div>
                            <div class="experience-details">
                                <h3 class="experience-location"><?php echo htmlspecialchars($experience['country']); ?></h3>
                                <p class="experience-description"><?php echo htmlspecialchars($experience['experience']); ?></p>
                                <div class="experience-meta">
                                    <form method="POST" class="delete-experience-form">
                                        <input type="hidden" name="experience_id" value="<?php echo $experience['id']; ?>">
                                        <button type="submit" name="delete_experience" class="delete-btn">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="no-experiences">
                    <p>No experiences uploaded yet.</p>
                    <a href="user_feedback.php" class="btn btn-primary">Upload Your First Experience</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>