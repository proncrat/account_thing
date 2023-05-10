<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 2){
        $password_err = "Password must have atleast 2 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
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
    <title>Sign Up</title>
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
            font-size: 1.3rem;
            padding:5px;
            border-radius: 1rem;
            margin:5px;
        }


    </style>
</head>
<body>


    <?php 
        if(!empty($username_err)){
            echo '<div class="alert alert-danger">' . $username_err . '</div>';
        }
        if(!empty($password_err)){
            echo '<div class="alert alert-danger">' . $password_err . '</div>';
        }        
        if(!empty($confirm_password_err)){
            echo '<div class="alert alert-danger">' . $confirm_password_err . '</div>';
        }        
    ?>

    <form id="sign_in_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" >
        <label>Username</label>
        <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
        <br>
        <label>Password</label>
        <input type="password" name="password" class="form-control " value="<?php echo $password; ?>">
        <br>
        <label>Same Password</label>
        <input type="password" name="confirm_password" class="form-control " value="<?php echo $confirm_password; ?>">
        <br>
        <input type="submit" value="Submit">
        <div style="display: flex;justify-content: space-between;" >
            <a href="../" >Home</a>
            <a href="login.php">Login</a>
        </div>
    </form>  
</body>
</html>