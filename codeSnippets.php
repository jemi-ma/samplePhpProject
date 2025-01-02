//login page logic
<?php
        require("connection.php");
        if(isset($_POST['submit'])){
            $email = $_POST['email'];
            $password = $_POST['password'];

            $sql = "SELECT *FROM login WHERE email='$email'AND password='$password'";
            $result = mysqli_query($conn,$sql);
            $num = mysqli_num_rows($result);

            if($num>0){
                header("location:mainpage.php");
            }
            elseif($num==0){
                $sql = "SELECT *FROM admins WHERE email='$email'AND password='$password'";
                $result = mysqli_query($conn,$sql);
                $num = mysqli_num_rows($result);

                if($num>0){
                    header("location:adminspage.php");
                
                }
                else{
                    echo '<script>alert("Email and Password is not Matching!!");</script>';
                }   
            }
        }
?> 

// reg page locale_get_script
<?php
        require("connection.php");
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
?>\


<script type="text/javascript">
		 		function redirectToAnotherPage() {
    				window.location.href = 'login.php';
				}
			</script>