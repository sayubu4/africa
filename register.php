<?php
session_start();
include 'db_config.php';

// Validate password complexity
function validatePassword($password) {
    $uppercase = preg_match('/[A-Z]/', $password);
    $lowercase = preg_match('/[a-z]/', $password);
    $number = preg_match('/[0-9]/', $password);
    $specialChar = preg_match('/[^a-zA-Z0-9]/', $password);
    $length = strlen($password) >= 8;

    return ($uppercase && $lowercase && $number && $specialChar && $length);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Database connection
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $username = trim($_POST['username']);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'];

        // Validate inputs
        $errors = [];

        if (empty($username)) {
            $errors[] = "Username is required";
        }

        if (!$email) {
            $errors[] = "Invalid email format";
        }

        if (!validatePassword($password)) {
            $errors[] = "Password must be at least 8 characters long and include uppercase, lowercase, numbers, and symbols";
        }

        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->rowCount() > 0) {
            $errors[] = "Username or email already exists";
        }

        // If no errors, proceed with registration
        if (empty($errors)) {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert user into database
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password]);
            
            // Redirect to login page after successful registration
            header("Location: login.php");
            exit();
        }
    } catch(PDOException $e) {
        $errors[] = "Registration failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>East African Hub - Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F5E6D3;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .registration-container {
            background-color: #8B4513;
            color: #F5E6D3;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .registration-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #F5E6D3;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #F5E6D3;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #D2691E;
            border-radius: 5px;
            background-color: #F4A460;
            color: #2F1B14;
        }
        .error-message {
            color: #FF4500;
            background-color: #FFE4E1;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .success-message {
            color: #2E8B57;
            background-color: #F0FFF0;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .submit-btn {
            width: 100%;
            padding: 10px;
            background-color: #FFD700;
            color: #2F1B14;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-link {
            text-align: center;
            margin-top: 15px;
            color: #F5E6D3;
        }
        .login-link a {
            color: #FFD700;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <h2>East African Hub Registration</h2>
        
        <?php
        // Display errors if any
        if (!empty($errors)) {
            echo '<div class="error-message">';
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
            echo '</div>';
        }
        ?>

        <form action="" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" required 
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" required
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="submit-btn">Register</button>
            <div class="login-link">
                Already have an account? <a href="login.php">Log in here</a>
            </div>
        </form>
    </div>
</body>
</html>