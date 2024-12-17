<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share Your East African Experience</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F5E6D3;
            color: #2F1B14;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background-color: #8B4513;
            color: #F5E6D3;
            text-align: center;
            padding: 1em 0;
        }

        .form-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #2F1B14;
            font-weight: bold;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            border: 2px solid #8B4513;
            border-radius: 5px;
            background-color: #FFF4E6;
            color: #2F1B14;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #FFA500;
            box-shadow: 0 0 5px rgba(255,165,0,0.5);
        }

        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #8B4513;
            color: #FFD700;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #FFA500;
        }

        .message-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
            margin-top: 20px;
        }

        .return-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background-color: #FFA500;
            color: #2F1B14;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .return-btn:hover {
            background-color: #FFD700;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }

        .file-input-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
        }

        .file-input-wrapper .btn-file-custom {
            border: 2px dashed #8B4513;
            background-color: #FFF4E6;
            color: #8B4513;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php
require_once 'session_check.php';
// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    // Connect to the database
    $host = 'localhost';
    $db_username = 'eunice.sayubu';
    $db_password = 'sayubueunice';
    $dbname = 'webtech_fall2024_eunice_sayubu';
    
    
    // Create connection
    $conn = new mysqli($host, $db_username, $db_password, $dbname);


// Create connection

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    

    function updateExperiencesInFile($country, $experienceData) {
        $filename = strtolower($country) . '.php';
        
        if (file_exists($filename)) {
            // Read the file content
            $fileContent = file_get_contents($filename);
            
            // Find the user_experiences array
            preg_match('/\$user_experiences\s*=\s*array\((.+?)\);/s', $fileContent, $matches);
            
            if (!empty($matches[1])) {
                // Parse existing experiences
                $existingExperiences = eval('return array(' . $matches[1] . ');');
                
                // Add new experience
                $newExperience = array(
                    'name' => $experienceData['name'],
                    'email' => $experienceData['email'],
                    'experience' => $experienceData['experience'],
                    'photos' => $experienceData['photos'],
                    'timestamp' => date('Y-m-d H:i:s')
                );
                
                // Add the new experience to the beginning of the array
                array_unshift($existingExperiences, $newExperience);
                
                // Limit experiences to prevent the array from growing too large
                $existingExperiences = array_slice($existingExperiences, 0, 20);
                
                // Reconstruct the array
                $newExperiencesArray = var_export($existingExperiences, true);
                
                // Replace the old array with the new one
                $updatedContent = preg_replace(
                    '/\$user_experiences\s*=\s*array\((.+?)\);/s', 
                    '$user_experiences = ' . $newExperiencesArray . ';', 
                    $fileContent
                );
                
                // Write back to file
                file_put_contents($filename, $updatedContent);
            }
        }
    }

    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $country = $_POST['country'];
    $experience = $_POST['experience'];

    // Handle file upload (optional)
   // Handle file upload (optional)
$photos = array();
if (isset($_FILES['photos'])) {
    $files = $_FILES['photos'];
    $uploadDir = 'uploads/';
    
    // Create uploads directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    foreach ($files['name'] as $key => $value) {
        if ($files['error'][$key] == UPLOAD_ERR_OK) {
            $fileType = strtolower(pathinfo($files['name'][$key], PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($fileType, $allowedTypes)) {
                // Use a more reliable unique filename
                $fileName = date('YmdHis') . '_' . uniqid() . '.' . $fileType;
                $uploadFile = $uploadDir . $fileName;

                if (move_uploaded_file($files['tmp_name'][$key], $uploadFile)) {
                    // Store the relative path from the root of the website
                    $photos[] = $uploadFile;
                }
            }
        }
    }
}

$photos_str = implode(',', $photos);

    // Prepare experience data for file update
    $experienceData = [
        'name' => $name,
        'email' => $email,
        'experience' => $experience,
        'photos' => $photos_str
    ];

    // Insert the data into the database
    $sql = "INSERT INTO user_experiences (name, email, country, experience, photos)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $country, $experience, $photos_str);

    if ($stmt->execute()) {
        // Update experiences in country-specific file
        updateExperiencesInFile($country, $experienceData);
?>
    <div class="container">
        <div class="message-container">
            <h2>Thank You for Sharing!</h2>
            <p>Your East African experience has been successfully submitted.</p>
            <p>Your story will help others discover the beauty of this incredible region.</p>
            <a href="index.php" class="return-btn">Return to Home Page</a>
        </div>
    </div>
<?php
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
    exit();
} else {
    // Rest of the form code remains the same...
}
?>
    <header>
        <h1>Share Your East African Experience</h1>
    </header>
    <div class="container">
        <div class="form-container">
            <form id="user-experience-form" action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="name" required placeholder="Enter your full name">
                </div>
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <label for="country">Country Visited:</label>
                    <select id="country" name="country" required>
                        <option value="">Select a Country</option>
                        <option value="burundi">Burundi</option>
                        <option value="rwanda">Rwanda</option>
                        <option value="kenya">Kenya</option>
                        <option value="uganda">Uganda</option>
                        <option value="tanzania">Tanzania</option>
                        <option value="south_sudan">South Sudan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="experience">Share Your Experience:</label>
                    <textarea id="experience" name="experience" rows="5" required placeholder="Tell us about your journey..."></textarea>
                </div>
                <div class="form-group">
                    <label>Upload Photos (Optional):</label>
                    <div class="file-input-wrapper">
                        <button class="btn-file-custom">Choose Files</button>
                        <input type="file" id="photos" name="photos[]" multiple accept="image/*">
                    </div>
                </div>
                <button type="submit" class="btn">Submit My Experience</button>
            </form>
        </div>
    </div>
    <?php
    ?>
</body>
</html>