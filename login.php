<?php
session_start();
 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.html");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            
                            $cookie_name = "first_time_sync_required";
                            $cookie_value = true;
                            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");

                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            header("location: index.html");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body{ 
            margin: 0;
            font: 14px sans-serif;
            background: #141414; 
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            flex-direction: column;
        }

        #sign_in_form{
            padding:10px;
            width: fit-content;
            color: white;
            background: #4d4d4d;
            border: none;
            border-radius: 1rem;
        }
        
        
        #sign_in_form{
            display: flex;
            flex-direction: column;
        }
        
        #sign_in_form label{
            font-size: 2rem;
        }
        
        #sign_in_form input{
            background-color: rgb(20, 20, 20);
            /*border: 2px solid rgb(255, 255, 255);*/
            color: white;
            font-size: 2rem;
            width: 250px;
        }
        
        #sign_in_form a{
            background-color: rgb(20, 20, 20);
            color: white;
            font-size: 1.5rem;
            text-align:center;
            margin-top:10px;
            margin-bottom:10px;
            padding: 5px;
            width: 45%;
        }

        .alert{
            text-align: center;
            background-color: red;
            color: white;
            border-radius: 1rem;
            font-size: 1.2rem;
        }




    </style>
</head>
<body>

<div>
<?php 
    if(!empty($login_err)){
        echo '<div class="alert alert-danger">' . $login_err . '</div>';
    }        
    if(!empty($username_err)){
        echo '<div class="alert alert-danger">' . $username_err . '</div>';
    }
    if(!empty($password_err)){
        echo '<div class="alert alert-danger">' . $password_err . '</div>';
    }        
?>
    <form id="sign_in_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" >
        <label for="username">Username:</label>
        <input type="text" name="username" value="<?php echo $username; ?>" >
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" value="<?php echo $password; ?>">
        <br>
        <input type="submit" value="Log in">
        <div style="display: flex;justify-content: space-between;" >
            <a href="../" >Home</a>
            <a href="register.php">Register</a>
        </div>
    </form> 

</div>
</body>

</html>