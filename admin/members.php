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

// Create blacklist table if it doesn't exist
$create_blacklist_table = "CREATE TABLE IF NOT EXISTS blacklist (
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    keyword VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(ID) ON DELETE CASCADE
)";
$conn->query($create_blacklist_table);

// Create contact_messages table if it doesn't exist
$create_contact_messages_table = "CREATE TABLE IF NOT EXISTS contact_messages (
    id INT(11) NOT NULL AUTO_INCREMENT,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    concern TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
)";
$conn->query($create_contact_messages_table);

// Make sure users table has is_active column
$check_is_active = "SHOW COLUMNS FROM users LIKE 'is_active'";
$result = $conn->query($check_is_active);
if ($result->num_rows == 0) {
    $add_is_active = "ALTER TABLE users ADD COLUMN is_active TINYINT(1) DEFAULT 1";
    $conn->query($add_is_active);
}

// Handle member status updates
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle activate/deactivate actions
    if (isset($_POST["action"])) {
        $member_id = intval($_POST["member_id"]);
        $action = $_POST["action"];
        
        if ($action === "activate" || $action === "deactivate") {
            $status = ($action === "activate") ? 1 : 0;
            $update_query = "UPDATE users SET is_active = ? WHERE ID = ? AND is_admin = 0";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ii", $status, $member_id);
            
            if ($stmt->execute()) {
                $success = "Member status updated successfully!";
            } else {
                $error = "Error updating member status.";
            }
        }
    }
    
    // Handle blacklist keyword actions
    if (isset($_POST["add_blacklist"])) {
        $member_id = intval($_POST["member_id"]);
        $keyword = trim($_POST["keyword"]);
        
        if (!empty($keyword)) {
            $insert_query = "INSERT INTO blacklist (user_id, keyword) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("is", $member_id, $keyword);
            
            if ($stmt->execute()) {
                $success = "Keyword blacklisted successfully!";
            } else {
                $error = "Error blacklisting keyword.";
            }
        } else {
            $error = "Keyword cannot be empty.";
        }
    }
    
    // Handle user deletion
    if (isset($_POST["delete_user"])) {
        $member_id = intval($_POST["member_id"]);
        
        $delete_query = "DELETE FROM users WHERE ID = ? AND is_admin = 0";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $member_id);
        
        if ($stmt->execute()) {
            $success = "User deleted successfully!";
        } else {
            $error = "Error deleting user.";
        }
    }
}

// Get active tab
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'users';

// Fetch all members (non-admin users)
$members_query = "SELECT * FROM users WHERE is_admin = 0 ORDER BY ID DESC";
$members_result = $conn->query($members_query);

// Fetch all concerns from contact_messages
$concerns_query = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$concerns_result = $conn->query($concerns_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members | Future Business Teachers' Organization</title>
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
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
        }
        .status-active {
            background-color: #28a745;
            color: white;
        }
        .status-inactive {
            background-color: #dc3545;
            color: white;
        }
        .member-actions {
            display: flex;
            gap: 10px;
        }
        .btn-action {
            padding: 5px 10px;
            font-size: 0.9em;
        }
        .tab-content {
            padding: 20px 0;
        }
        .badge-blacklist {
            background-color: #6c757d;
            color: white;
            margin: 2px;
            display: inline-block;
        }
        .nav-tabs .nav-link.active {
            border-bottom: 3px solid #2c3e50;
        }
        .blacklist-keywords {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 5px;
        }
        .blacklist-keyword {
            background-color: #f0f0f0;
            border-radius: 15px;
            padding: 2px 10px;
            font-size: 0.8em;
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
            <a href="donations.php" class="nav-link">
                <i class="fas fa-hand-holding-heart me-2"></i>Donations
            </a>
            <a href="members.php" class="nav-link active">
                <i class="fas fa-users me-2"></i>Members
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h2 class="mb-4">User Management</h2>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Tabs -->
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link <?php echo $active_tab === 'users' ? 'active' : ''; ?>" href="?tab=users">Active Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $active_tab === 'concerns' ? 'active' : ''; ?>" href="?tab=concerns">Concerns</a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Users Tab -->
                <?php if ($active_tab === 'users'): ?>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Active Users</h5>
                            <div class="d-flex gap-2">
                                <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Search members...">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="membersTable">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Birthday</th>
                                            <th>Status</th>
                                            <th>Blacklisted Keywords</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($member = $members_result->fetch_assoc()): ?>
                                            <?php 
                                            // Fetch blacklisted keywords for this user
                                            $blacklist_query = "SELECT * FROM blacklist WHERE user_id = ?";
                                            $stmt = $conn->prepare($blacklist_query);
                                            $stmt->bind_param("i", $member['ID']);
                                            $stmt->execute();
                                            $blacklist_result = $stmt->get_result();
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($member['username']); ?></td>
                                                <td><?php echo htmlspecialchars($member['email']); ?></td>
                                                <td><?php echo $member['birthday'] ? date('M d, Y', strtotime($member['birthday'])) : 'Not provided'; ?></td>
                                                <td>
                                                    <span class="status-badge status-<?php echo isset($member['is_active']) && $member['is_active'] ? 'active' : 'inactive'; ?>">
                                                        <?php echo isset($member['is_active']) && $member['is_active'] ? 'Active' : 'Inactive'; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="blacklist-keywords">
                                                        <?php while($blacklist = $blacklist_result->fetch_assoc()): ?>
                                                            <span class="blacklist-keyword"><?php echo htmlspecialchars($blacklist['keyword']); ?></span>
                                                        <?php endwhile; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                            Actions
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <form method="POST" action="" class="px-3 py-2">
                                                                    <input type="hidden" name="member_id" value="<?php echo $member['ID']; ?>">
                                                                    <div class="mb-2">
                                                                        <label class="form-label">Blacklist Keyword:</label>
                                                                        <input type="text" name="keyword" class="form-control form-control-sm" placeholder="Enter keyword">
                                                                    </div>
                                                                    <button type="submit" name="add_blacklist" class="btn btn-sm btn-warning w-100 mb-2">Blacklist</button>
                                                                </form>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <form method="POST" action="" class="px-3 py-2">
                                                                    <input type="hidden" name="member_id" value="<?php echo $member['ID']; ?>">
                                                                    <input type="hidden" name="action" value="<?php echo isset($member['is_active']) && $member['is_active'] ? 'deactivate' : 'activate'; ?>">
                                                                    <button type="submit" class="btn btn-sm <?php echo isset($member['is_active']) && $member['is_active'] ? 'btn-danger' : 'btn-success'; ?> w-100 mb-2">
                                                                        <?php echo isset($member['is_active']) && $member['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form method="POST" action="" class="px-3 py-2">
                                                                    <input type="hidden" name="member_id" value="<?php echo $member['ID']; ?>">
                                                                    <button type="submit" name="delete_user" class="btn btn-sm btn-danger w-100" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                                        Delete User
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Concerns Tab -->
                <?php if ($active_tab === 'concerns'): ?>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">User Concerns</h5>
                        </div>
                        <div class="card-body">
                            <?php if ($concerns_result && $concerns_result->num_rows > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Concern</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($concern = $concerns_result->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo date('M d, Y H:i', strtotime($concern['created_at'])); ?></td>
                                                    <td><?php echo htmlspecialchars($concern['email']); ?></td>
                                                    <td><?php echo htmlspecialchars($concern['phone']); ?></td>
                                                    <td><?php echo htmlspecialchars($concern['concern']); ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary" type="button" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#concernModal"
                                                                data-concern="<?php echo htmlspecialchars($concern['concern']); ?>"
                                                                data-email="<?php echo htmlspecialchars($concern['email']); ?>">
                                                            <i class="fas fa-eye me-1"></i> View
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">No concerns submitted yet.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Concern Modal -->
    <div class="modal fade" id="concernModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Concern</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">From:</label>
                        <div id="concernEmail"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Concern:</label>
                        <div id="concernText"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase();
            const table = document.getElementById('membersTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const nameCell = rows[i].getElementsByTagName('td')[0];
                const emailCell = rows[i].getElementsByTagName('td')[1];
                
                if (nameCell && emailCell) {
                    const name = nameCell.textContent || nameCell.innerText;
                    const email = emailCell.textContent || emailCell.innerText;
                    
                    if (name.toLowerCase().indexOf(searchText) > -1 || 
                        email.toLowerCase().indexOf(searchText) > -1) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            }
        });

        // Concern modal
        const concernModal = document.getElementById('concernModal');
        if (concernModal) {
            concernModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const concern = button.getAttribute('data-concern');
                const email = button.getAttribute('data-email');
                
                document.getElementById('concernText').textContent = concern;
                document.getElementById('concernEmail').textContent = email;
            });
        }
    </script>
</body>
</html> 