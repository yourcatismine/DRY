<?php
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

loadEnv(__DIR__ . '../../../../private/.env');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

echo json_encode([
    'apiKey' => getenv('APIKEY'),
    'authDomain' => getenv('DOMAIN'),
    'databaseURL' => getenv('DATABASEURL'),
    'projectId' => getenv('PROJECTID'),
    'storageBucket' => getenv('BUCKET'),
    'messagingSenderId' => getenv('SENDERID'),
    'appId' => getenv('APPID'),
    'measurementId' => getenv('MEASUREMENT')
]);
?>