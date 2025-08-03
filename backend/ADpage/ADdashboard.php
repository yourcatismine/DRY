<?php
session_start();

if (empty($_SESSION['username']) || empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    session_unset();
    session_destroy();
    header('Location: ../ADLogin/login.php');
    exit();
}

$timeout_duration = 1800; 
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();    
    session_destroy();
    header("Location: ../loginpage/login.php?message=session_expired");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../ADLogin/login.php");
    exit();
}

$_SESSION['last_activity'] = time();

$username = $_SESSION['username'];
?>

<!-- Include Bootstrap CSS & icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
<link rel="stylesheet" href="./ADstyles/ADdashboard.css">
<script type="module" src="./ADjavascripts/ADvisitors.js"></script>

<div class="d-flex">
  <!-- Sidebar -->
  <nav id="sidebar" class="bg-white border-end vh-100 position-fixed">
    <div class="sidebar-header p-3">
      <h4>Dashboard</h4>
      <button id="toggle-btn" class="btn d-md-none"><i class="bi bi-list"></i></button>
    </div>
    <ul class="nav flex-column">
      <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
      <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-newspaper"></i> Schedules</a></li>
      <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-flag"></i> News</a></li>
    </ul>
  </nav>

  <!-- Content wrapper -->
  <div class="flex-grow-1" style="margin-left: 250px;">
    <header class="navbar navbar-light bg-light border-bottom">
      <div class="container-fluid">
        <h1 class="navbar-text">Welcome, <?= htmlspecialchars($username) ?>!</h1>
        <div class="d-flex gap-2">
       <a href="../../frontend/pages/frontpage.php" class="btn btn-outline-primary">
         <i class="bi bi-house"></i> Home Page
       </a>
       <form method="POST" class="mb-0">
         <button name="logout" class="btn btn-outline-secondary">
           <i class="bi bi-box-arrow-right"></i> Logout
         </button>
       </form>
      </div>
    </div>
  </header>

    <main class="p-4">
      <!-- Stat cards -->
        <div class="row mb-4">
              <div class="col-md-3 mb-3">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <h5><span id="visitor-count" class="odometer">0</span></h5>
            <p>Total Views</p>
          </div>
        </div>
      </div>

              <div class="col-md-3 mb-3">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <h5 id="projects-counter">0</h5>
            <p>Total Projects</p>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <h5 id="languages-counter">0</h5>
            <p>Total Languages</p>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <h5 id="github-counter">0</h5>
            <p>GitHub Projects</p>
          </div>
        </div>
      </div>
    </div>

      <!-- Table -->
      <div class="card table-card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Visitors</h4>
  </div>
  <div class="table-responsive">
    <table class="table table-striped table-hover mb-0">
      <thead>
        <tr>
          <th>IP Address</th>
          <th>Platform</th>
          <th>Country</th>
          <th>City</th>
          <th>Region</th>
          <th>Time</th>
          <th>Browser</th>
        </tr>
      </thead>
      <tbody id="visitor-table-body">
      </tbody>
    </table>
  </div>
</div>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Toggle sidebar on mobile
  document.getElementById('toggle-btn').addEventListener('click', ()=>{
    document.getElementById('sidebar').classList.toggle('d-none');
  });
</script>
