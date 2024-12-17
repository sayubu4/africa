<?php
require_once 'db_config.php';
session_start();


// Database configuration
$host = 'localhost';
$db_username = 'eunice.sayubu';
$db_password = 'sayubueunice';
$dbname = 'webtech_fall2024_eunice_sayubu';

// Initialize error variable
$login_error = '';

// Handle login submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Database connection
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get username/email and password from form
        $login_input = trim($_POST['login_input']);
        $password = $_POST['password'];

        // Prepare statement to find user by username or email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$login_input, $login_input]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // Check if user is admin
            if (isset($user['is_admin']) && $user['is_admin'] == 1) {
                $_SESSION['is_admin'] = true;
                // Redirect to admin dashboard
                header("Location: admin_dashboard.php");
                exit();
            } else {
                // Regular user redirects to index
                header("Location: index.php");
                exit();
            }
        } 
        else {
            // Invalid credentials
            $login_error = "Invalid username/email or password";
        }

    } catch(PDOException $e) {
        $login_error = "Login failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>East African Hub - Login</title>
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
        .login-container {
            background-color: #8B4513;
            color: #F5E6D3;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-container h2 {
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
            text-align: center;
        }
        .submit-btn {
            width: 100%;
            padding: 10px;
            background-color: #FFD700;
            color: #2F1B14;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .submit-btn:hover {
            background-color: #FFA500;
        }
        .register-link {
            text-align: center;
            margin-top: 15px;
            color: #F5E6D3;
        }
        .register-link a {
            color: #FFD700;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>East African Hub Login</h2>
        
        <?php
        // Display login error if exists
        if (!empty($login_error)) {
            echo '<div class="error-message">' . htmlspecialchars($login_error) . '</div>';
        }
        ?>

        <form action="" method="post">
            <div class="form-group">
                <label for="login_input">Username or Email:</label>
                <input type="text" name="login_input" required 
                       value="<?php echo isset($_POST['login_input']) ? htmlspecialchars($_POST['login_input']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="submit-btn">Login</button>
        </form>

        <div class="register-link">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </div>
</body>
</html>