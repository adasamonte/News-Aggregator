<?php
session_start();
require_once "../connect.php";

// Check if user is logged in and is admin
if (!isset($_SESSION["username"]) || !isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] != 1) {
    header("Location: ../auth/login.php");
    exit();
}

// If we get here, user is logged in and is admin
$username = $_SESSION["username"];
$user_id = $_SESSION["user_id"];

// Handle donation submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = floatval($_POST["amount"]);
    $donor_id = intval($_POST["donor_id"]);
    $campaign_id = !empty($_POST["campaign_id"]) ? intval($_POST["campaign_id"]) : null;
    $transaction_id = uniqid('DON_');

    $insert_query = "INSERT INTO donations (campaign_id, donor_id, amount, transaction_id, status) 
                    VALUES (?, ?, ?, ?, 'completed')";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iids", $campaign_id, $donor_id, $amount, $transaction_id);
    
    if ($stmt->execute()) {
        $success = "Donation recorded successfully!";
    } else {
        $error = "Error recording donation.";
    }
}

// Fetch all donations with donor information
$donations_query = "SELECT d.*, u.username, u.email 
                   FROM donations d 
                   LEFT JOIN users u ON d.donor_id = u.id 
                   ORDER BY d.donation_date DESC";
$donations_result = $conn->query($donations_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donations - Future Business Teachers' Organization</title>
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
        .donation-form {
            max-width: 500px;
            margin: 0 auto;
        }
        .donation-history {
            margin-top: 40px;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
        }
        .status-pending {
            background-color: #ffc107;
            color: #000;
        }
        .status-completed {
            background-color: #28a745;
            color: white;
        }
        .status-failed {
            background-color: #dc3545;
            color: white;
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
                        <li><a class="dropdown-item" href="../landing.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="nav flex-column">
            <a href="dashboard.php" class="nav-link">
                <i class="fas fa-home me-2"></i>Dashboard
            </a>
            <a href="donations.php" class="nav-link active">
                <i class="fas fa-hand-holding-heart me-2"></i>Donations
            </a>
            <a href="members.php" class="nav-link">
                <i class="fas fa-users me-2"></i>Members
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h2 class="mb-4">Donations Management</h2>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Donation Form -->
            <div class="card donation-form">
                <div class="card-header">
                    <h5 class="mb-0">Record New Donation</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="donor_id" class="form-label">Donor ID</label>
                            <input type="number" class="form-control" id="donor_id" name="donor_id" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="campaign_id" class="form-label">Campaign ID (Optional)</label>
                            <input type="number" class="form-control" id="campaign_id" name="campaign_id">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Record Donation</button>
                    </form>
                </div>
            </div>

            <!-- Donation History -->
            <div class="donation-history">
                <h3 class="mb-4">Donation History</h3>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Donor</th>
                                <th>Amount</th>
                                <th>Transaction ID</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($donation = $donations_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo date('M d, Y H:i', strtotime($donation['donation_date'])); ?></td>
                                <td>
                                    <?php 
                                    if ($donation['username']) {
                                        echo htmlspecialchars($donation['username']);
                                    } else {
                                        echo 'Anonymous';
                                    }
                                    ?>
                                </td>
                                <td>$<?php echo number_format($donation['amount'], 2); ?></td>
                                <td><?php echo htmlspecialchars($donation['transaction_id']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $donation['status']; ?>">
                                        <?php echo ucfirst($donation['status']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 