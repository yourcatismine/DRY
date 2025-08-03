<?php
session_start();

require __DIR__ . '\ADdatabase\ADdatabase.php';

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'], $_POST['password'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if (empty($username) || empty($password)) {
        $error_message = "Username and password are required.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT username, password, role FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                
                if ($user['password'] === $password) {
                    $_SESSION['username'] = $username;
                    $_SESSION['last_activity'] = time();
                    $_SESSION['role'] = $user['role']; 
                    
                    header("Location: ../ADpage/ADdashboard.php");
                    exit(); // Important: Stop script execution after redirect
                } else {
                    $error_message = "Invalid password";
                }
            } else {
                $error_message = "Username not found";
            }
            
            $stmt->close();
            
        } catch (Exception $e) {
            $error_message = "Database error occurred";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../ADlogin/ADstyles/login.css">
    <script type="module" src="../ADlogin/ADjavascripts/login.js"></script>
    <title>Admin Panel</title>
</head>

<body>

    <div class="container" id="container">
        <?php if (!empty($error_message)): ?>
            <div class="message-error">
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="form-container sign-in">
            <form method="POST" action="">
                <h1>Sign In</h1>
                <input type="text" placeholder="Username" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                <input type="password" placeholder="Password" id="password" name="password" required>
                <button type="submit">Log in</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-right">
                    <div class="logo">
                    <img src="./images/logo.png" alt="Logo">
                    </div>
                    <h1>Hello there!</h1>
                    <p>Sign in to continue to the Admin menu.</p>
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