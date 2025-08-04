<?php
if (!function_exists('loadEnv')) {
    function loadEnv($path) {
        if (!file_exists($path)) {
            throw new Exception('.env file not found at: ' . $path);
        }
        
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue; // Skip comments
            }
            
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            
            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

loadEnv(__DIR__ . '../../../../private/.env'); 

$servername = getenv('DB_HOST_REGISTER');
$username = getenv('DB_USER_REGISTER');
$password = getenv('DB_PASS_REGISTER');
$database = getenv('DB_NAME');

$reg_conn = new mysqli($servername, $username, $password, $database);
if ($reg_conn->connect_error) {
    die("Connection failed: " . $reg_conn->connect_error);
}
$reg_conn->set_charset("utf8");
?>