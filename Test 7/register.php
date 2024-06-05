<?php 
include 'loginconnect.php';

if(isset($_POST['signUp'])){
    $username=$_POST['username'];
    $password=$_POST['password'];
    $password=md5($password);

     $checkUsername="SELECT * From users where username='$username'";
     $result=$conn->query($checkUsername);
     if($result->num_rows>0){
        echo "Username Already Exists !";
     }
     else{
        $insertQuery="INSERT INTO users(username, password)
                       VALUES ('$username','$password')";
            if($conn->query($insertQuery)==TRUE){
                header("location: login.php");
            }
            else{
                echo "Error:".$conn->error;
            }
     }
}

if(isset($_POST['signIn'])){
    $username=$_POST['username'];
    $password=$_POST['password'];
    $hashedPassword=md5($password);
   
    $sql="SELECT * FROM users WHERE username='$username'";
    $result=$conn->query($sql);
    if($result->num_rows > 0){
        $user = $result->fetch_assoc();
        if ($user['password'] === $hashedPassword) {
            // Password is correct, set up session and redirect to dashboard
            session_start();
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            // Password is incorrect, redirect back to login page with an error parameter
            header("Location: login.php?error=invalid_password");
            exit();
        }
    } else {
        // Username is incorrect, check if password is incorrect as well
        $sql="SELECT * FROM users WHERE password='$hashedPassword'";
        $result=$conn->query($sql);
        if($result->num_rows > 0){
            // Username is incorrect, but password is correct
            header("Location: login.php?error=invalid_username");
            exit();
        } else {
            // Both username and password are incorrect
            header("Location: login.php?error=invalid_both");
            exit();
        }
    }
}

?>