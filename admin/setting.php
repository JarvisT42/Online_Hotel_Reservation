<?php
session_start();
include 'connect.php'; // your DB connection

if (!isset($_SESSION['admin_logged_in'])) {
    // Not logged in, redirect to login page
    header("Location: ../login.php");
    exit;
}

$admin_id = $_POST['admin_id'] ?? $_SESSION['admin_id'] ?? null;

if (!$admin_id) {
    die("Admin ID missing.");
}
$message = "";
$error = "";

// Update profile
if (isset($_POST['update_profile'])) {
    $admin_id = $_POST['admin_id']; // Make sure this is set or use session
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];

    $stmt = $conn->prepare("UPDATE admin SET name = ?, email = ?, username = ? WHERE admin_id = ?");
    $stmt->bind_param("sssi", $name, $email, $username, $admin_id);

    if ($stmt->execute()) {
        $message = "Profile updated successfully!";
    } else {
        $error = "Error updating profile: " . $stmt->error;
    }
    $stmt->close();
}

// Change password
if (isset($_POST['change_password'])) {
    $admin_id = $_POST['admin_id'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        $error = "New passwords do not match.";
    } else {
        $stmt = $conn->prepare("SELECT password FROM admin WHERE admin_id = ?");
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        $stmt->close();

        if (!password_verify($currentPassword, $hashedPassword)) {
            $error = "Current password is incorrect.";
        } else {
            $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE admin SET password = ? WHERE admin_id = ?");
            $stmt->bind_param("si", $newHashedPassword, $admin_id);

            if ($stmt->execute()) {
                $message = "Password updated successfully!";
            } else {
                $error = "Error updating password: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<?php include 'head.php'; ?>

<style>
    /* Settings Content Styles */
    .settings-container {
        display: grid;
        grid-template-columns: 1fr;
        gap: 25px;
        margin-top: 20px;
    }

    @media (min-width: 992px) {
        .settings-container {
            grid-template-columns: 1fr 1fr;
        }
    }

    .settings-card {
        background: white;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        padding: 25px;
        transition: var(--transition);
        height: 100%;
    }

    .settings-card:hover {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    }

    .card-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border);
    }

    .card-header i {
        font-size: 22px;
        width: 40px;
        height: 40px;
        background: rgba(67, 97, 238, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        margin-right: 15px;
    }

    .card-header h2 {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #555;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 15px;
        transition: var(--transition);
        border: 1px solid #ccc;
        /* ← Added color */

    }

    .form-control:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }

    .btn {
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 15px;
        cursor: pointer;
        transition: var(--transition);
        border: none;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--secondary);
    }

    .btn-danger {
        background: var(--danger);
        color: white;
    }

    .btn-danger:hover {
        opacity: 0.9;
    }

    .btn-outline {
        background: transparent;
        border: 1px solid var(--border);
        color: #555;
    }

    .btn-outline:hover {
        background: #f5f5f5;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: var(--success);
    }

    input:checked+.slider:before {
        transform: translateX(24px);
    }

    .security-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid var(--border);
    }

    .security-item:last-child {
        border-bottom: none;
    }

    .security-info h3 {
        font-size: 16px;
        margin-bottom: 5px;
    }

    .security-info p {
        color: #777;
        font-size: 14px;
    }

    .account-warning {
        background: #fff8e6;
        border-left: 4px solid var(--warning);
        padding: 15px;
        border-radius: 4px;
        margin-top: 20px;
    }

    .account-warning h3 {
        color: var(--warning);
        margin-bottom: 5px;
    }

    .account-warning p {
        font-size: 14px;
        color: #777;
    }

    /* Sidebar and Main Content adjustments */





    /* Breadcrumb */
    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 20px;
    }

    .breadcrumb-item a {
        text-decoration: none;
        color: var(--primary);
    }
</style>



<body>
    <!-- Toggle Button for Mobile -->
    <button class="toggle-btn" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Overlay for mobile sidebar -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>


    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="topbar-left">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0">Profile Settings</h4>
                    <div class="ms-3 text-muted d-none d-md-block">
                        <i class="fas fa-calendar me-1"></i>
                        <span id="currentDate">June 6, 2023</span>
                    </div>
                </div>
            </div>
            <div class="user-info">
                <div class="notification">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </div>
                <img src="https://randomuser.me/api/portraits/men/41.jpg" alt="Admin">
                <div>
                    <div class="fw-bold">John Doe</div>
                    <div class="text-muted small">Administrator</div>
                </div>
            </div>
        </div>

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home me-1"></i> Home</a></li>
                <li class="breadcrumb-item"><a href="#">Settings</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
            </ol>
        </nav>
        <?php if (!empty($message)): ?>
            <div class="alert alert-success fade-out" role="alert" id="successMessage">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger fade-out" role="alert" id="errorMessage">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <script>
            // Automatically hide success message after 5 seconds
            setTimeout(function() {
                const success = document.getElementById("successMessage");
                if (success) {
                    success.style.transition = "opacity 0.5s ease-out";
                    success.style.opacity = "0";
                    setTimeout(() => success.remove(), 500); // remove after fade
                }

                const error = document.getElementById("errorMessage");
                if (error) {
                    error.style.transition = "opacity 0.5s ease-out";
                    error.style.opacity = "0";
                    setTimeout(() => error.remove(), 500); // remove after fade
                }
            }, 5000); // 5 seconds
        </script>

        <!-- Settings Content -->
        <div class="settings-container">
            <!-- Account Information Card -->


            <div class="settings-card">
                <div class="card-header">
                    <i class="fas fa-user"></i>
                    <h2>Account Information</h2>
                </div>
                <form action="" method="POST">
                    <input type="hidden" name="admin_id" value="1"> <!-- or from session -->
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username">
                    </div>
                    <div class="action-buttons">
                        <button class="btn btn-primary" type="submit" name="update_profile">Update Profile</button>
                    </div>
                </form>
            </div>

            <!-- Password Card -->
            <div class="settings-card">
                <div class="card-header">
                    <i class="fas fa-key"></i>
                    <h2>Password & Security</h2>
                </div>
                <form action="" method="POST">
                    <input type="hidden" name="admin_id" value="1"> <!-- or from session -->
                    <div class="form-group">
                        <label for="current-password">Current Password</label>
                        <input type="password" class="form-control" id="current-password" name="current_password">
                    </div>
                    <div class="form-group">
                        <label for="new-password">New Password</label>
                        <input type="password" class="form-control" id="new-password" name="new_password">
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm-password" name="confirm_password">
                    </div>
                    <div class="action-buttons">
                        <button class="btn btn-primary" type="submit" name="change_password">Change Password</button>
                    </div>
                </form>
            </div>

            <!-- Security Settings Card -->

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>