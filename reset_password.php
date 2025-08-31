<?php
include 'connect.php';

$email = "";
$token = "";
$error = "";
$success = "";

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    $email = $_POST['email'];
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Validate token
        $stmt = $conn->prepare("SELECT * FROM password_reset_tokens WHERE email = ? AND token = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $error = "Invalid or expired token.";
        } else {
            // Hash new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update guest password
            $stmt = $conn->prepare("UPDATE guests SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashed_password, $email);
            $stmt->execute();

            // Delete used token
            $stmt = $conn->prepare("DELETE FROM password_reset_tokens WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $success = "Password has been reset successfully. <a href='login.php'>Login here</a>.";
        }
    }
}
// If user came from reset link
elseif (isset($_GET['token']) && isset($_GET['email'])) {
    $token = $_GET['token'];
    $email = $_GET['email'];

    // Validate token
    $stmt = $conn->prepare("SELECT * FROM password_reset_tokens WHERE email = ? AND token = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $error = "Invalid or expired token.";
    }
} else {
    $error = "Invalid request.";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="container py-5">

    <h3>Reset Your Password</h3>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php else: ?>
        <?php if (!empty($email) && !empty($token)): ?>
            <form method="POST" action="">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                <div class="mb-3">
                    <label>New Password</label>
                    <input type="password" name="new_password" required class="form-control">
                </div>
                <div class="mb-3">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required class="form-control">
                </div>
                <button type="submit" name="reset_password" class="btn btn-success">Reset Password</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>

</body>

</html>