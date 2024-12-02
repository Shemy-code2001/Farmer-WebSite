<?php
include('conex.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['customer'])) {
        $_SESSION['user_type'] = 'customer';
        header("Location: landingpage.php");
        exit();
    } 
    elseif (isset($_POST['farmer'])) {
        $_SESSION['user_type'] = 'farmer';
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm to Home</title>
    <style>
        :root {
            --primary-color: #006400;
            --secondary-color: #004d00;
            --light-bg: #f4f4f9;
            --text-color: #333;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: "Arial", sans-serif;
            background-color: var(--light-bg);
        }
        
        .homepage-container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url("https://klinegroup.com/wp-content/uploads/Fruit-vegetable-blog-banner.jpg") no-repeat center/cover;
            position: relative;
        
            &::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 0;
            }
        
            .homepage-content {
            position: relative;
            z-index: 1;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.85);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1.5s ease-in-out;
        
            h1 {
                font-size: 3.5rem;
                color: var(--primary-color);
                margin-bottom: 20px;
                text-shadow: 2px 2px var(--secondary-color);
            }
        
            p {
                font-size: 1.6rem;
                color: var(--text-color);
                margin-bottom: 30px;
            }
        
            .btn-group {
                display: flex;
                justify-content: center;
                gap: 20px;
        
                a {
                background-color: var(--primary-color);
                color: white;
                padding: 15px 30px;
                text-decoration: none;
                border-radius: 8px;
                font-size: 1.2rem;
                transition: transform 0.3s, background-color 0.3s;
        
                &:hover {
                    background-color: var(--secondary-color);
                    transform: scale(1.05);
                }
                }
            }
            }
        }
        
        .login-container {
            display: none;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.9));
        
            .login-box {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
            animation: slideIn 0.8s ease;
        
            h2 {
                margin-bottom: 20px;
                color: var(--primary-color);
            }
        
            input,
            select {
                width: 100%;
                padding: 12px;
                margin: 10px 0;
                border: 1px solid #ccc;
                border-radius: 6px;
                box-sizing: border-box;
                transition: border-color 0.3s;
        
                &:focus {
                border-color: var(--primary-color);
                }
            }
        
            input[type="submit"] {
                background-color: var(--primary-color);
                color: white;
                cursor: pointer;
                transition: background-color 0.3s;
        
                &:hover {
                background-color: var(--secondary-color);
                }
            }
        
            .signup-link {
                margin-top: 15px;
                display: block;
                color: var(--primary-color);
                font-weight: bold;
                text-decoration: none;
        
                &:hover {
                text-decoration: underline;
                }
            }
            }
        }
        
        @keyframes fadeIn {
            from {
            opacity: 0;
            transform: scale(0.9);
            }
            to {
            opacity: 1;
            transform: scale(1);
            }
        }
        
        @keyframes slideIn {
            from {
            opacity: 0;
            transform: translateY(-20px);
            }
            to {
            opacity: 1;
            transform: translateY(0);
            }
        }
        .link-button {
            background: none;
            border: none;
            color: blue;
            text-decoration: underline;
            cursor: pointer;
            padding: 0;
            font: inherit;
        }
        .link-button:hover {
            text-decoration: none;
        }
        .btn {
            background-color: var(--primary-color); 
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.2rem;
            border: none;
            cursor: pointer;
            transition: transform 0.3s, background-color 0.3s;
        }

        .btn:hover {
            background-color: var(--secondary-color);
            transform: scale(1.05);
        }
        #customerForm, #farmerForm {
            display: inline-block;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="homepage-container" id="homepage">
        <div class="homepage-content">
            <h1>Farm to Home</h1>
            <p>Are you a customer or a farmer?</p>
            <div class="btn-group">
            <form method="POST" id="customerForm">
                <button type="submit" name="customer" class="btn">I am a Customer</button>
            </form>

            <form method="POST" id="farmerForm">
                <button type="submit" name="farmer" class="btn">I am a Farmer</button>
            </form>
            </div>
        </div>
    </div>


    <script>
        function showLogin(userType) {
        const homepage = document.getElementById('homepage');
        const loginPage = document.getElementById('login-page');
        homepage.style.opacity = '0';
        setTimeout(() => {
            homepage.style.display = 'none';
            loginPage.style.display = 'flex';
            loginPage.style.opacity = '0';
            setTimeout(() => (loginPage.style.opacity = '1'), 100);
        }, 300);
        document.getElementById('user-type').value = userType;
        updateForm();
        }

    </script>


</body>
</html>






















