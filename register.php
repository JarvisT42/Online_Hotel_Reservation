<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

include 'connect.php'; // DB connection

$error = '';
$success = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validation
    if (empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        // Check if email already exists in guest table
        $stmt = $conn->prepare("SELECT * FROM guests WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Email exists
            $guest = $result->fetch_assoc();
            if (empty($guest['password'])) {
                // Update password if not set
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $update = $conn->prepare("UPDATE guests SET password = ? WHERE email = ?");
                $update->bind_param("ss", $hashed_password, $email);
                if ($update->execute()) {
                    $_SESSION['guest_logged_in'] = true;
                    $_SESSION['guest_id'] = $guest['guest_id'];
                    $_SESSION['guest_email'] = $guest['email']; // ✅ store email

                    header("Location: guest/dashboard.php");
                    exit;
                } else {
                    $error = "Something went wrong. Please try again.";
                }
            } else {
                $error = "Email is already registered.";
            }
        } else {
            // Email does not exist → Insert new guest
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO guests (email, password) VALUES (?, ?)");
            $insert->bind_param("ss", $email, $hashed_password);

            if ($insert->execute()) {
                $_SESSION['guest_logged_in'] = true;
                $_SESSION['guest_id'] = $conn->insert_id;  // ✅ get last inserted guest_id
                $_SESSION['guest_email'] = $email;         // ✅ store email

                header("Location: guest/dashboard.php");
                exit;
            } else {
                $error = "Error creating account: " . $insert->error;
            }
        }
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHIOJI APARTELLE - Admin Registration</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css"> <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2a5d8a;
            --secondary: #e9b44c;
            --accent: #d74e09;
            --light: #f8f9fa;
            --dark: #1a2a3a;
            --gray: #6c757d;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2a5d8a 0%, #1a2a3a 100%);
            position: relative;
            overflow: auto;
            padding: 20px;
        }

        .registration-container {
            width: 100%;
            max-width: 450px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 10;
            animation: fadeIn 0.6s ease-out;
        }

        .registration-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .registration-header h2 {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .registration-header p {
            color: var(--gray);
            font-size: 0.95rem;
        }

        .logo {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .logo-inner {
            background: var(--primary);
            color: white;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            box-shadow: 0 5px 15px rgba(42, 93, 138, 0.4);
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-control {
            padding: 14px 20px 14px 45px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 0.95rem;
            transition: all 0.3s;
            height: auto;
            width: 100%;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(42, 93, 138, 0.2);
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            font-size: 1.1rem;
        }

        .btn-register {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            font-size: 1rem;
            margin-top: 10px;
        }

        .btn-register:hover {
            background: #1f4a6d;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(42, 93, 138, 0.4);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            font-size: 0.9rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input {
            margin-right: 8px;
        }

        .forgot-password {
            color: var(--primary);
            text-decoration: none;
            transition: all 0.3s;
        }

        .forgot-password:hover {
            color: var(--accent);
            text-decoration: underline;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.95rem;
        }

        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .login-link a:hover {
            color: var(--accent);
            text-decoration: underline;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 5px;
            display: none;
        }

        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .error {
            background-color: #ffebee;
            color: #dc3545;
            border: 1px solid #f5c6cb;
        }

        .success {
            background-color: #e8f5e9;
            color: #28a745;
            border: 1px solid #c3e6cb;
        }

        .decoration {
            position: absolute;
            z-index: 1;
        }

        .circle-1 {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(233, 180, 76, 0.1);
            top: 10%;
            left: 10%;
            animation: float 6s infinite ease-in-out;
        }

        .circle-2 {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: rgba(215, 78, 9, 0.1);
            bottom: 10%;
            right: 15%;
            animation: float 8s infinite ease-in-out;
        }

        .triangle {
            width: 0;
            height: 0;
            border-left: 80px solid transparent;
            border-right: 80px solid transparent;
            border-bottom: 140px solid rgba(42, 93, 138, 0.1);
            top: 20%;
            right: 10%;
            animation: rotate 20s infinite linear;
        }

        .square {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.05);
            bottom: 20%;
            left: 15%;
            transform: rotate(45deg);
            animation: pulse 4s infinite ease-in-out;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @keyframes pulse {
            0% {
                transform: rotate(45deg) scale(1);
            }

            50% {
                transform: rotate(45deg) scale(1.1);
            }

            100% {
                transform: rotate(45deg) scale(1);
            }
        }

        @media (max-width: 576px) {
            .registration-container {
                margin: 0 10px;
                padding: 25px;
            }

            .form-footer {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .circle-1,
            .circle-2,
            .triangle,
            .square {
                display: none;
            }
        }

        .login-navbar {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .brand-logo {
            font-weight: 700;
            font-size: 1.5rem;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .brand-logo i {
            margin-right: 10px;
            color: var(--secondary);
        }

        .nav-button {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 8px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }

        .nav-button i {
            margin-right: 8px;
        }

        .nav-button:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <nav class="login-navbar">
        <a href="index.php" class="brand-logo">
            SHIOJI APARTELLE
        </a>
        <a href="index.php" class="nav-button">
            <i class="fas fa-external-link-alt"></i> Home
        </a>
    </nav>

    <!-- Decorative background elements -->
    <div class="decoration circle-1"></div>
    <div class="decoration circle-2"></div>
    <div class="decoration triangle"></div>
    <div class="decoration square"></div>

    <div class="registration-container">
        <div class="registration-header">
            <div class="logo">
                <div class="logo-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                </div>
            </div>
            <h2>SHIOJI APARTELLE</h2>
            <p>Create Administrator Account</p>
        </div>

        <?php if (!empty($error)) : ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($success)) : ?>
            <div class="message success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" id="registrationForm" action="" novalidate>
            <div class="form-group">
                <i class="fas fa-envelope input-icon"></i>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email Address"
                    value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                <div class="error-message" id="email-error">Please enter a valid email address</div>
            </div>

            <div class="form-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <div class="error-message" id="password-error">Password must be at least 8 characters</div>
            </div>

            <div class="form-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                <div class="error-message" id="confirm-password-error">Passwords do not match</div>
            </div>

            <button type="submit" class="btn-register">
                <i class="fas fa-user-plus me-2"></i>Register Account
            </button>
        </form>



        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>

    <script>
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            // Clear previous errors
            const errorElements = document.querySelectorAll('.error-message');
            errorElements.forEach(el => el.style.display = 'none');

            let isValid = true;

            const name = document.getElementById('name').value.trim();
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            const confirmPassword = document.getElementById('confirm_password').value.trim();

            // Validate name
            if (!name) {
                document.getElementById('name-error').style.display = 'block';
                isValid = false;
            }

            // Validate username
            if (!username) {
                document.getElementById('username-error').style.display = 'block';
                isValid = false;
            }

            // Validate password
            if (!password || password.length < 8) {
                document.getElementById('password-error').style.display = 'block';
                isValid = false;
            }

            // Validate confirm password
            if (password !== confirmPassword) {
                document.getElementById('confirm-password-error').style.display = 'block';
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault(); // Prevent form submit if invalid
            } else {
                // Optionally disable button to prevent multiple submits
                this.querySelector('.btn-register').disabled = true;
                this.querySelector('.btn-register').innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Registering...';
            }
        });

        // Add focus effects to form inputs
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('.input-icon').style.color = '#d74e09';
            });

            input.addEventListener('blur', function() {
                this.parentElement.querySelector('.input-icon').style.color = '#2a5d8a';
            });
        });

        // Real-time password confirmation validation
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm_password');

        confirmPasswordInput.addEventListener('input', function() {
            if (passwordInput.value !== this.value) {
                document.getElementById('confirm-password-error').style.display = 'block';
            } else {
                document.getElementById('confirm-password-error').style.display = 'none';
            }
        });
    </script>
</body>

</html>