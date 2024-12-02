<?php
include('conex.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    extract($_POST);
    $errors = [];

    if (!isset($username) || empty($username)) {
        $errors["username"] = "Please enter your username!";
    } 
    if (!isset($password) || empty($password)) {
        $errors["password"] = "Please enter your password!";
    }

    if (empty($errors)) {
        $username = htmlspecialchars($username);
        $password = htmlspecialchars($password);

        try {
            $query = $con->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
            $query->execute([$username, $password]);
            $user = $query->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if (isset($remember_me) && $remember_me === 'on') {
                    setcookie("username", $username, time() + 3600 * 24 * 12 * 4);
                    setcookie("password", $password, time() + 3600 * 24 * 12 * 4);
                } else {
                    setcookie("username", "", time() - 1);
                    setcookie("password", "", time() - 1);
                }

                session_start();
                $_SESSION['user_id'] = $user['id']; 
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $user['role'];

                switch (strtolower($user['role'])) {
                    case 'admin':
                        header("Location: dashboard.php");
                        exit;
                    case 'client':
                        header("Location: home.php");
                        exit;
                }
            } else {
                $errors["login"] = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            echo "Authentication error: " . $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | E-Commerce Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
       @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
        :root {
            --primary-color: #2D5A27;  
            --primary-hover: #1A4314;  
            --secondary-color: #A8C69F; 
            --accent-color: #E6B17E;   
            --bg-color: #F7F4F0;      
            --error-color: #D64545;
            --text-color: #2C3E2D;
            --text-light: #5C715E;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-color);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--text-color);
            background-image: 
                linear-gradient(120deg, rgba(168, 198, 159, 0.1) 0%, rgba(45, 90, 39, 0.05) 100%);
        }

        .login-container {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            max-width: 1100px;
            margin: 2rem;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 
                0 20px 40px rgba(45, 90, 39, 0.05),
                0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .login-image {
            position: relative;
            background: linear-gradient(
                145deg,
                rgba(45, 90, 39, 0.8),
                rgba(45, 90, 39, 0.9)
            ),
            url('path-to-your-nature-image.jpg');
            background-size: cover;
            background-position: center;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            color: white;
        }

        .login-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("https://i.pinimg.com/736x/11/82/5b/11825b1e6deedc1013a5c6b2a2729659.jpg");
            opacity: 0.1;
        }

        .login-image h2 {
            font-size: 2.75rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
            position: relative;
        }

        .login-image p {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
        }

        .login-form {
            padding: 3.5rem;
            background: white;
        }

        .form-header {
            margin-bottom: 2.5rem;
        }

        .form-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 0.75rem;
        }

        .form-header p {
            color: var(--text-light);
            font-size: 1.1rem;
        }

        .input-group {
            margin-bottom: 1.75rem;
        }

        .input-group label {
            display: block;
            font-size: 0.925rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--text-color);
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 1.1rem;
        }

        .input-wrapper input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 1.5px solid #E2E8F0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
            color: var(--text-color);
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(45, 90, 39, 0.1);
        }

        .input-wrapper input::placeholder {
            color: #94A3B8;
        }

        .forgot-password {
            display: block;
            text-align: right;
            color: var(--primary-color);
            font-size: 0.925rem;
            text-decoration: none;
            margin: -0.5rem 0 2rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: var(--primary-hover);
        }

        .login-button {
            width: 100%;
            padding: 1rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
        }

        .login-button:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .error-message {
            background-color: #FEF2F2;
            border: 1px solid #FEE2E2;
            color: var(--error-color);
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.925rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
                margin: 1rem;
            }

            .login-image {
                display: none;
            }

            .login-form {
                padding: 2rem;
            }

            .form-header h1 {
                font-size: 1.75rem;
            }
        }

        @media (max-width: 480px) {
            .login-form {
                padding: 1.5rem;
            }
            
            .form-header {
                margin-bottom: 2rem;
            }
        }
        html {
            scroll-behavior: smooth;
        }
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-color);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--secondary-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-image">
            <h2>Welcome Back</h2>
            <p>Access your account and manage your shopping experience.</p>
        </div>
        <div class="login-form">
            <div class="form-header">
                <h1>Sign In</h1>
                <p>Please enter your credentials to continue.</p>
            </div>
            <form method="POST">
                <?php
                if (!empty($errors)) {
                    foreach ($errors as $error) {
                        echo "<div class='error-message'>" . htmlspecialchars($error) . "</div>";
                    }
                }
                ?>
                <div class="input-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" required>
                    </div>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" required>
                    </div>
                </div>
                <button type="submit" class="login-button">Sign In</button>
            </form>
        </div>
    </div>
</body>
</html>