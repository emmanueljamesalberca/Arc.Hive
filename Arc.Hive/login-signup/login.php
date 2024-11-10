<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busy Bee Inventory System - Login</title>
    <link rel="stylesheet" href="includes/css/style.css">
</head>
<body>
    
    <div class="container">
        <div class="box form-box">
            <?php
            include("config.php");
            session_start();  // Start session to store user data

            if(isset($_POST['submit'])){
                $email = mysqli_real_escape_string($con, $_POST['email']);
                $password = mysqli_real_escape_string($con, $_POST['password']);
                
                // Check if email exists in the user table
                $result = mysqli_query($con, "SELECT * FROM user WHERE Email='$email'") or die("Select Error");
                $row = mysqli_fetch_assoc($result);

                if($row && password_verify($password, $row['Password'])){  // Verify hashed password
                    // Set session variables
                    $_SESSION['valid'] = $row['Email'];
                    $_SESSION['username'] = $row['Username'];
                    $_SESSION['age'] = $row['Age'];
                    $_SESSION['id'] = $row['UserID'];
                    $_SESSION['status'] = $row['Status'];

                    // Redirect to the dashboard after successful login
                    header("Location: ../laundry-items.php");
                } else {
                    echo "<div class='message'><p>Wrong Email or Password</p></div>";
                }
            }
            ?>

            <header>Login</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>
                </div>

                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <div class="field">
                    <input type="submit" name="submit" value="Login">
                </div>
                <div class="links">
                    Don't have an account? 
                    <!-- Link to the registration page -->
                    <a href="register.php">Register here</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
