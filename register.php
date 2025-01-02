<?php
        //require("connection.php");
        if(isset($_POST['submit'])){
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmpassword = $_POST['confirm-password'];

            if($password!=$confirmpassword){
                echo '<script>alert("passwords doesnot match!!");</script>';
            }

            $sql = "SELECT *FROM signup WHERE email='$email'";
            $result = mysqli_query($conn,$sql);
            $num = mysqli_num_rows($result);

            if($num>0){
                echo '<script>alert("User Already Exists!!");</script>';
            }
            else{
                $signupinsert = "INSERT INTO signup (username,email,password) VALUES ('$username','$email','$password')";
                mysqli_query($conn,$signupinsert);
                $logininsert = "INSERT INTO login (email,password) VALUES ('$email','$password')";
                mysqli_query($conn,$logininsert);
                header("location:login.php");
            }
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="register.css">
  <title>Share recipies</title>
</head>
<body>
    <section>
        <div class="form-box">
            <div class="form-value">
                <form action="" method="POST">
                    <h2>Register</h2>
                    <div class="inputbox">
                        <input type="text" name="username" required>
                        <label for="">Username</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" name="email" required>
                        <label for="">Email</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="password" required>
                        <label for="">Password</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="confirm-password" required>
                        <label for="">Confirm password</label>
                    </div>
                    
                    <div class="button">
                    <input type="submit" value="Register" name="submit">
                    </div>
                    <div class="register">
                        <p>Already have an account? <a href="login.php">Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
