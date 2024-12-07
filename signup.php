<?php 
include 'db.php';  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_type = $_POST['user_type'];

    $sql = "INSERT INTO upwork_user (username, email, password, user_type) VALUES ('$username', '$email', '$password', '$user_type')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Signup successful!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProFreelance - Signup</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #2ecc71;
            --background-color: #f7f9fc;
            --card-background: #ffffff;
            --text-dark: #2c3e50;
            --text-light: #7f8c8d;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .signup-container {
            background-color: var(--card-background);
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            text-align: center;
        }

        .signup-header {
            margin-bottom: 30px;
        }

        .signup-header h2 {
            color: var(--primary-color);
            font-size: 24px;
            margin-bottom: 10px;
        }

        .signup-header p {
            color: var(--text-light);
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 15px;
            color: var(--text-light);
        }

        .form-control {
            width: 100%;
            padding: 15px 15px 15px 40px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--secondary-color);
        }

        .user-type {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .user-type label {
            margin: 0 15px;
            cursor: pointer;
        }

        .user-type input {
            display: none;
        }

        .user-type label span {
            display: inline-block;
            padding: 10px 20px;
            background-color: #f1f3f5;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .user-type input:checked + span {
            background-color: var(--secondary-color);
            color: white;
        }

        .signup-btn {
            width: 100%;
            padding: 15px;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .signup-btn:hover {
            background-color: var(--primary-color);
        }

        .login-link {
            margin-top: 20px;
            color: var(--text-light);
        }

        .login-link a {
            color: var(--secondary-color);
            text-decoration: none;
        }

        @media (max-width: 480px) {
            .signup-container {
                width: 95%;
                padding: 20px;
            }

            .user-type {
                flex-direction: column;
            }

            .user-type label {
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="signup-header">
            <h2>Create Your Account</h2>
            <p>Join ProFreelance and start your journey</p>
        </div>
        
        <form method="POST">
            <div class="form-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            
            <div class="form-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
            </div>
            
            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            
            <div class="user-type">
                <label>
                    <input type="radio" name="user_type" value="client" required>
                    <span>Client</span>
                </label>
                <label>
                    <input type="radio" name="user_type" value="freelancer" required>
                    <span>Freelancer</span>
                </label>
            </div>
            
            <button type="submit" class="signup-btn">Sign Up</button>
        </form>
        
        <div class="login-link">
            Already have an account? <a href="login.php">Log In</a>
        </div>
    </div>

    <script>
        // Optional: Add form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.querySelector('input[name="password"]');
            if (password.value.length < 3) {
                e.preventDefault();
                alert('Password must be at least 3 characters long');
            }
        });
    </script>
</body>
</html>