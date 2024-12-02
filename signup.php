<?php
include('conex.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    extract($_POST);
    $errors = [];
    if (!isset($username) || empty($username)) {
        $errors["username"] = "Please enter a username!";
    }
    if (!isset($password) || empty($password)) {
        $errors["password"] = "Please enter a password!";
    }
    if (!isset($confirm_password) || empty($confirm_password)) {
        $errors["confirm_password"] = "Please confirm your password!";
    }
    if (!isset($first_name) || empty($first_name)) {
        $errors["first_name"] = "Please enter your first name!";
    }
    if (!isset($last_name) || empty($last_name)) {
        $errors["last_name"] = "Please enter your last name!";
    }
    if ($password !== $confirm_password) {
        $errors["password_match"] = "Passwords do not match!";
    }
    if (empty($errors)) {
        try {
            $check_query = $con->prepare("SELECT id FROM users WHERE username = ?");
            $check_query->execute([$username]);
            if ($check_query->rowCount() > 0) {
                $errors["username"] = "This username is already taken!";
            }
        } catch (PDOException $e) {
            $errors["database"] = "Database error: " . $e->getMessage();
        }
    }
    if (empty($errors)) {
        try {
            $query = $con->prepare("INSERT INTO users (username, password, first_name, last_name, role) VALUES (?, ?, ?, ?, 'client')");
            $query->execute([$username, $password, $first_name, $last_name]);
            
            session_start();
            $_SESSION['message'] = "Registration successful! Please login.";
            header("Location: login.php");
            exit;
        } catch (PDOException $e) {
            $errors["database"] = "Registration error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | E-Commerce Platform</title>
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

        .signup-container {
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

        .signup-image {
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

        .signup-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("https://i.pinimg.com/736x/11/82/5b/11825b1e6deedc1013a5c6b2a2729659.jpg");
            opacity: 0.1;
        }

        .signup-image h2 {
            font-size: 2.75rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
            position: relative;
        }

        .signup-image p {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
        }

        .signup-form {
            padding: 3rem;
            background: white;
            overflow-y: auto;
            max-height: 90vh;
        }

        .form-header {
            margin-bottom: 2rem;
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
            margin-bottom: 1.5rem;
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

        .signup-button {
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
            margin-top: 1rem;
        }

        .signup-button:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
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

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-light);
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            color: var(--primary-hover);
        }

        @media (max-width: 768px) {
            .signup-container {
                grid-template-columns: 1fr;
                margin: 1rem;
            }

            .signup-image {
                display: none;
            }

            .signup-form {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="signup-image">
            <h2>Create Account</h2>
            <p>Join our community and start your shopping journey.</p>
        </div>
        <div class="signup-form">
            <div class="form-header">
                <h1>Sign Up</h1>
                <p>Please fill in your information to create an account.</p>
            </div>
            <form method="POST">
                <?php
                if (!empty($errors)) {
                    foreach ($errors as $error) {
                        echo "<div class='error-message'><i class='fas fa-exclamation-circle'></i>" . htmlspecialchars($error) . "</div>";
                    }
                }
                ?>
                <div class="input-group">
                    <label for="first_name">First Name</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" id="first_name" name="first_name" value="<?= isset($first_name) ? htmlspecialchars($first_name) : '' ?>" required>
                    </div>
                </div>
                <div class="input-group">
                    <label for="last_name">Last Name</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" id="last_name" name="last_name" value="<?= isset($last_name) ? htmlspecialchars($last_name) : '' ?>" required>
                    </div>
                </div>
                <div class="input-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <i class="fas fa-at"></i>
                        <input type="text" id="username" name="username" value="<?= isset($username) ? htmlspecialchars($username) : '' ?>" required>
                    </div>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" required>
                    </div>
                </div>
                <div class="input-group">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                <button type="submit" class="signup-button">Create Account</button>
                <div class="login-link">
                    Already have an account? <a href="login.php">Sign In</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>