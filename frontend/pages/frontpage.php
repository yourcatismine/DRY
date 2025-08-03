<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../styles/dryfront.css">
    <script type="module" src="../javascripts/front.js"></script>
    <script type="module" src="../javascripts/LoadingBar.js"></script>
    <title>DRY</title>
</head>
<body>

    <div class="loading-bar-container">
        <div class="loading-bar" id="loadingBar"></div>
    </div>


    <div class="login-button-container">
        <a href="../../backend/ADlogin/login.php" class="btn login-btn">
            <i class="bi bi-person-circle me-2"></i>
            Login
        </a>
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

    <div class="container">
        <div class="WELCOME">
            <h1>DRY</h1>
            <p>Every piece of knowledge must have a single, unambiguous, authoritative representation within a system.</p>
        </div>

        <div class="github-writer">
            <div class="terminal-header">
                <div class="terminal-dots">
                    <div class="dot red"></div>
                    <div class="dot yellow"></div>
                    <div class="dot green"></div>
                </div>
            </div>
            <div class="command-line">
                <span class="prompt">$</span>
                <span class="cmd-git">git</span>
                <span id="command" class="cmd-clone">clone</span>
                <span id="typewriter" class="typewriter"></span>
                <span id="cursor" class="cursor"></span>
            </div>
        </div>

        <div class="footer">
        <div class="copyright-text">
            <div class="text-content">
                <span class="text-dry">© 2025 DRY</span>
                <span class="text-creator">Created by Diego Burgos</span>
            </div>
        </div>
    </div>
    </div>
</body>
</html>