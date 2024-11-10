<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busy Bee Inventory System</title>
    <link rel="stylesheet" href="includes/css/style.css">
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <?php
            include("config.php");

            if(isset($_POST['submit'])){
                $username = mysqli_real_escape_string($con, $_POST['username']);
                $email = mysqli_real_escape_string($con, $_POST['email']);
                $password = mysqli_real_escape_string($con, $_POST['password']);
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);  // Encrypt password
                $age = mysqli_real_escape_string($con, $_POST['age']);
                $status = 'active';  // Set default status

                // Check if the email already exists
                $check_email_query = "SELECT * FROM user WHERE Email='$email'";
                $check_email_result = mysqli_query($con, $check_email_query);
                
                if(mysqli_num_rows($check_email_result) > 0){
                    // Email already exists
                    echo "<div class='message'><p>Email already exists. Please use a different email.</p></div>";
                } else {
                    // If email doesn't exist, proceed with registration
                    $sql = "INSERT INTO user (Username, Email, Password, Age, Status) 
                            VALUES ('$username', '$email', '$hashed_password', '$age', '$status')";
                
                    if(mysqli_query($con, $sql)){
                        echo "<div class='message'><p>Registration successful!</p></div>";
                    } else {
                        echo "<div class='message'><p>Error: " . mysqli_error($con) . "</p></div>";
                    }
                }
            }
            ?>
            <header><Register</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="field input">
                    <label for="age">Age</label>
                    <input type="number" name="age" id="age" required>
                </div>
                <br>
                <div class="field">
                    <input type="submit" name="submit" value="Register">
                </div>
            </form>
            <!-- Button to redirect to the login page -->
            <div class="field">
                <button onclick="redirectToLogin()">Back to Login</button>
            </div>
        </div>
    </div>

    <script>
        function redirectToLogin() {
            window.location.href = 'login.php'; // Replace 'login.php' with the correct path to your login page
        }
    </script>
</body>
</html>
