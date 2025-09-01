<?php
session_start();
require_once "connect.php";

// Check if user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];
$user_id = $_SESSION["user_id"];

// Fetch user's saved articles
$saved_articles_query = "SELECT * FROM saved_articles WHERE user_id = ? ORDER BY saved_date DESC LIMIT 5";
$stmt = $conn->prepare($saved_articles_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$saved_articles = $stmt->get_result();

// Fetch upcoming events
$events_query = "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 3";
$events_result = $conn->query($events_query);

// Fetch active donation campaigns
$campaigns_query = "SELECT * FROM donation_campaigns WHERE end_date >= CURDATE() ORDER BY end_date ASC LIMIT 3";
$campaigns_result = $conn->query($campaigns_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Future Business Teachers' Organization</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background-color: #2c3e50;
            padding: 1rem;
        }
        .navbar-brand {
            font-weight: 600;
            color: white !important;
        }
        .sidebar {
            background: white;
            border-right: 1px solid #dee2e6;
            height: calc(100vh - 56px);
            position: fixed;
            padding: 20px;
            width: 250px;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: white;
            border-bottom: 1px solid #eee;
            font-weight: 600;
            padding: 15px;
        }
        .stat-card {
            background: linear-gradient(45deg, #2c3e50, #3498db);
            color: white;
        }
        .nav-link {
            color: #2c3e50;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 5px;
        }
        .nav-link:hover {
            background-color: #f8f9fa;
        }
        .nav-link.active {
            background-color: #e9ecef;
            color: #2c3e50;
            font-weight: 600;
        }
        .progress {
            height: 10px;
            border-radius: 5px;
        }
        .event-date {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            width: 80px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap me-2"></i>
                FBTO Dashboard
            </a>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-link text-white dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i><?php echo htmlspecialchars($username); ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="nav flex-column">
            <a href="dashboard.php" class="nav-link active">
                <i class="fas fa-home me-2"></i>Dashboard
            </a>
            <a href="events.php" class="nav-link">
                <i class="fas fa-calendar me-2"></i>Events
            </a>
            <a href="donations.php" class="nav-link">
                <i class="fas fa-hand-holding-heart me-2"></i>Donations
            </a>
            <a href="resources.php" class="nav-link">
                <i class="fas fa-book me-2"></i>Resources
            </a>
            <a href="members.php" class="nav-link">
                <i class="fas fa-users me-2"></i>Members
            </a>
            <a href="news.php" class="nav-link">
                <i class="fas fa-newspaper me-2"></i>News
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Stats Row -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <h6 class="card-title">Active Members</h6>
                            <h3 class="mb-0">245</h3>
                            <small>+12% this month</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <h6 class="card-title">Upcoming Events</h6>
                            <h3 class="mb-0">8</h3>
                            <small>Next event in 3 days</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <h6 class="card-title">Total Donations</h6>
                            <h3 class="mb-0">$12,450</h3>
                            <small>This year</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <h6 class="card-title">Resources Shared</h6>
                            <h3 class="mb-0">156</h3>
                            <small>+23 this week</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Row -->
            <div class="row">
                <!-- Upcoming Events -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Upcoming Events</span>
                            <a href="events.php" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <?php if ($events_result && $events_result->num_rows > 0): ?>
                                <?php while($event = $events_result->fetch_assoc()): ?>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="event-date me-3">
                                            <div class="fw-bold"><?php echo date('M', strtotime($event['event_date'])); ?></div>
                                            <div class="h4 mb-0"><?php echo date('d', strtotime($event['event_date'])); ?></div>
                                        </div>
                                        <div>
                                            <h6 class="mb-1"><?php echo htmlspecialchars($event['title']); ?></h6>
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                <?php echo htmlspecialchars($event['location']); ?>
                                            </small>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p class="text-muted">No upcoming events</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Active Donation Campaigns -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Active Donation Campaigns</span>
                            <a href="donations.php" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <?php if ($campaigns_result && $campaigns_result->num_rows > 0): ?>
                                <?php while($campaign = $campaigns_result->fetch_assoc()): ?>
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0"><?php echo htmlspecialchars($campaign['title']); ?></h6>
                                            <span class="badge bg-success">Active</span>
                                        </div>
                                        <div class="progress mb-2">
                                            <?php 
                                            $percentage = ($campaign['current_amount'] / $campaign['goal_amount']) * 100;
                                            ?>
                                            <div class="progress-bar" role="progressbar" style="width: <?php echo $percentage; ?>%"></div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Raised: $<?php echo number_format($campaign['current_amount']); ?></small>
                                            <small class="text-muted">Goal: $<?php echo number_format($campaign['goal_amount']); ?></small>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p class="text-muted">No active donation campaigns</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            Recent Activity
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <!-- Add timeline items here -->
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        <div class="bg-light rounded-circle p-2">
                                            <i class="fas fa-user-plus"></i>
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-1">New member joined</h6>
                                        <small class="text-muted">2 hours ago</small>
                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        <div class="bg-light rounded-circle p-2">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-1">New resource uploaded</h6>
                                        <small class="text-muted">5 hours ago</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
