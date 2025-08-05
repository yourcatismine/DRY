<?php
session_start();

require __DIR__ . '\ADdatabase\ADdatabase.php';

$error_message = "";
$success_message = "";
$redirect_success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if (empty($username) || empty($password)) {
        $error_message = "Username and password are required.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT username, email, password, role FROM login WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                
                if ($user['password'] === $password) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $username;
                    $_SESSION['last_activity'] = time();
                    $_SESSION['role'] = $user['role']; 
                    
                    header("Location: ../ADpage/ADdashboard.php");
                    exit();
                } else {
                    $error_message = "Invalid credentials";
                }
            } else {
                $error_message = "Invalid credentials";
            }
            
            $stmt->close();
            
        } catch (Exception $e) {
            error_log("Database error: " . $e->getMessage());
            $error_message = "System error occurred. Please try again later.";
        }
    }
}

// Handle Registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    require_once __DIR__ . '\ADdatabase\ADregisterdata.php';
    
    $reg_username = trim($_POST['reg_username']);
    $reg_password = trim($_POST['reg_password']);
    $reg_email = filter_var(trim($_POST['reg_email']), FILTER_VALIDATE_EMAIL);
    
    if (empty($reg_username) || empty($reg_password) || empty($reg_email)) {
        $error_message = "All fields are required for registration.";
    } elseif (!$reg_email) {
        $error_message = "Invalid email format";
    } elseif (strlen($reg_password) < 8) {
        $error_message = "Password must be at least 8 characters";
    } else {
        try {
            $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
            $stmt->bind_param("s", $reg_username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error_message = "Username already exists";
                $stmt->close();
            } else {
                $stmt->close();
                
                $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
                $stmt->bind_param("s", $reg_email);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $error_message = "Email already registered";
                    $stmt->close();
                } else {
                    $stmt->close();
                    
                    $hashed_password = password_hash($reg_password, PASSWORD_DEFAULT);
                    $reg_role = 'user';
                    
                    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $reg_username, $reg_email, $hashed_password, $reg_role);
                    
                    if ($stmt->execute()) {
                        $success_message = "Account created successfully! You can now sign in.";
                        $_SESSION['temp_success'] = $success_message;
                        header("Location: {$_SERVER['PHP_SELF']}");
                        exit();
                    } else {
                        $error_message = "Registration failed. Please try again.";
                    }
                }
            }
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            $error_message = "System error during registration";
        }
    }
}

if (isset($_SESSION['temp_success'])) {
    $success_message = $_SESSION['temp_success'];
    unset($_SESSION['temp_success']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../ADlogin/ADstyles/registation.css">
    <script type="module" src="../ADlogin/ADjavascripts/loginpage.js" defer></script>
    <title>Login</title>
</head>
<body>

    <div class="button-container">
        <button class="glass-button" type="button" onclick="window.location.href='../../frontend/pages/frontpage.php'">
            <i class="bi bi-house"></i> Home Page
        </button>
    </div>

    <div class="floating-particles">
        <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
        <div class="particle" style="left: 20%; animation-delay: -2s;"></div>
        <div class="particle" style="left: 30%; animation-delay: -4s;"></div>
        <div class="particle" style="left: 40%; animation-delay: -6s;"></div>
        <div class="particle" style="left: 50%; animation-delay: -8s;"></div>
        <div class="particle" style="left: 60%; animation-delay: -10s;"></div>
        <div class="particle" style="left: 70%; animation-delay: -12s;"></div>
        <div class="particle" style="left: 80%; animation-delay: -14s;"></div>
        <div class="particle" style="left: 90%; animation-delay: -16s;"></div>
    </div>

    <div class="container" id="container">
        <?php if (!empty($success_message)): ?>
            <div class="message-success-top">
                <div class="success-message">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="message-error">
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Sign Up Form -->
        <div class="form-container sign-up">
            <form method="POST" action="">
                <h1>Create Account</h1>
                <input type="text" placeholder="Username" name="reg_username" required>
                <input type="email" placeholder="Email" name="reg_email" required>
                <input type="password" placeholder="Password (min. 8 characters)" name="reg_password" required minlength="8">
                <button type="submit" name="register">Sign Up</button>
            </form>
        </div>

        <!-- Sign In Form -->
        <div class="form-container sign-in">
            <form method="POST" action="">
                <h1>Sign In</h1>
                <input type="text" placeholder="Username" name="username" required>
                <input type="password" placeholder="Password" name="password" required>
                <button type="submit" name="login">Log in</button>
                <p class="register-link">New? <a href="#" id="register-toggle">Create Account</a></p>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <div class="logo">
                        <img src="./images/logo.png" alt="Logo">
                    </div>
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <div class="logo">
                        <img src="./images/logo.png" alt="Logo">
                    </div>
                    <h1>Hello there!</h1>
                    <p>Sign in to continue to the Admin menu.</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2025 Dryer Information Tech.</p>
        <p class="spacecreator">Created By Diego Burgos</p>
    </div>
</body>
</html>