<?php
session_start();
include 'connect.php'; // Your DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data and sanitize
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        die("Please enter both username and password.");
    }

    // Prepare SQL to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Assuming password is hashed, verify password
        if (password_verify($password, $user['password'])) {
            // Login success
            $_SESSION['admin_logged_in'] = true; // Set session variable to indicate login
            $_SESSION['admin_id'] = $user['admin_id']; // or your primary key field
            $_SESSION['admin_username'] = $user['username'];

            // Redirect to dashboard
            header("Location: admin/dashboard.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHIOJI APARTELLE - Admin Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">

    <!-- Font Awesome -->
    <!-- Google Fonts -->
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
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2a5d8a 0%, #1a2a3a 100%);
            position: relative;
            overflow: hidden;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 10;
            animation: fadeIn 0.6s ease-out;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h2 {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .login-header p {
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

        .btn-login {
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

        .btn-login:hover {
            background: #1f4a6d;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(42, 93, 138, 0.4);
        }

        .btn-login:active {
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

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }

        .divider:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 1px;
            background: #ddd;
            z-index: 1;
        }

        .divider span {
            background: white;
            padding: 0 15px;
            position: relative;
            z-index: 2;
            color: var(--gray);
            font-size: 0.9rem;
        }

        .social-login {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .social-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            transition: all 0.3s;
            text-decoration: none;
        }

        .social-btn:hover {
            transform: translateY(-3px);
        }

        .facebook {
            background: #3b5998;
        }

        .google {
            background: #dd4b39;
        }

        .twitter {
            background: #1da1f2;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.95rem;
        }

        .register-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .register-link a:hover {
            color: var(--accent);
            text-decoration: underline;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 5px;
            display: none;
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
            .login-container {
                margin: 0 20px;
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
    </style>
</head>

<body>
    <!-- Decorative background elements -->
    <div class="decoration circle-1"></div>
    <div class="decoration circle-2"></div>
    <div class="decoration triangle"></div>
    <div class="decoration square"></div>

    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <div class="logo-inner">
                    <i class="fas fa-hotel"></i>
                </div>
            </div>
            <h2>SHIOJI APARTELLE</h2>
            <p>Administrator Login</p>
        </div>




        <?php if (!empty($error)) : ?>
            <div style="color:red; margin-bottom:10px;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" id="loginForm" action="login.php" novalidate>
            <div class="form-group">
                <i class="fas fa-user input-icon"></i>
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                <div class="error-message" id="username-error" style="display:none; color:red;">Please enter your username</div>
            </div>

            <div class="form-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <div class="error-message" id="password-error" style="display:none; color:red;">Please enter your password</div>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Login to Dashboard
            </button>

            <div class="form-footer">
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>
                <a href="#" class="forgot-password">Forgot Password?</a>
            </div>
        </form>


        <!-- <div class="divider">
            <span>Or continue with</span>
        </div>

        <div class="social-login">
            <a href="#" class="social-btn facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="social-btn google">
                <i class="fab fa-google"></i>
            </a>
            <a href="#" class="social-btn twitter">
                <i class="fab fa-twitter"></i>
            </a>
        </div> -->

        <div class="register-link">
            Don't have an account? <a href="#">Request Access</a>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            // Clear previous errors
            const usernameError = document.getElementById('username-error');
            const passwordError = document.getElementById('password-error');
            usernameError.style.display = 'none';
            passwordError.style.display = 'none';

            let isValid = true;

            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();

            if (!username) {
                usernameError.style.display = 'block';
                isValid = false;
            }
            if (!password) {
                passwordError.style.display = 'block';
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault(); // Prevent form submit if invalid
            } else {
                // Optionally disable button to prevent multiple submits
                this.querySelector('.btn-login').disabled = true;
                this.querySelector('.btn-login').innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Logging in...';
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
    </script>
</body>

</html>