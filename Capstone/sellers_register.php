<?php
    include 'database.php';
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $seller_name = $_POST["seller_name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $password2 = $_POST["confirm_password"];
        
        $flag = true;
        $errors = array();
        $stmt = $conn->prepare("SELECT * FROM sellers WHERE Email='$email';");
        $stmt->execute();
        $user = $stmt->fetch();



        if($user){
            array_push($errors, "Email address already exist.");
            $flag = false;
        }

        if(strlen($password) < 8 || strlen($password) > 20){
            array_push($errors, "Password length should be between 8 and 20.");
            $flag = false;
        }

        //if email is invalid then show an error
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Invalid email address.");
            $flag = false;
        }

        if ($password != $password2) {
            array_push($errors, "Passwords doesn't match.");
            $flag = false;
        }



        if ($flag) {
            $password = md5($password);//encrypt the password before saving in the database
            $stmt = $conn->prepare("INSERT INTO sellers " .
                                    "(username,email,password) VALUES " .
                                    "(?, ?, ?)");
            $stmt->execute([$seller_name, $email, $password]);
            
        }

    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Shopaholic- Sellers Register</title>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <script>
                $(document).ready(function() {
                    
                });
        </script>
        <style>
            body { 
                padding-top: 70px;      
            }
            
            #nav {
            background-color: indigo;
            }

            #register_form{
                background-color: #aaa1c8;
            }


        </style>
    </head>
    <body>
    <nav id="nav" class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            </div>

            
            <form class="navbar-form navbar-right">
                <div class="form-group">
                <input type="text" class="form-control" placeholder="Search">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="seller/sellers_login.php">Login</a></li>
            </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
        </nav>
        <div class="container">
            <form id="register_form" class="well form-horizontal" action="sellers_register.php" method="post">
            <?php
                if(isset($errors)){
                    foreach($errors as $row){
                        ?><p style="color: red;"><strong><?php echo $row;?></strong></p><?
                    }
                }
                ?>
                <div class="form-group" id="seller_div">
                    <label for="seller_name" class="col-md-4 control-label">Seller Name</label>
                    <div class="col-md-4 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input  id="seller_name" name="seller_name" class="form-control"  type="text" required>
                           
                        </div>
                        <span id="seller_name_error" class="help-block"></span>
                    </div>
                </div>

                <div class="form-group" id="email_div">
                    <label class="col-md-4 control-label" for="email">E-Mail</label>  
                    <div class="col-md-4 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                            <input name="email" id="email" class="form-control"  type="email" required>
                        </div>
                        <span id="email_error" class="help-block"></span>
                    </div>
                </div>

                <div class="form-group" id="password_div">
                    <label class="col-md-4 control-label" for="password">Password</label> 
                    <div class="col-md-4 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input name="password" id="password" class="form-control"  type="password" required>
                        </div>
                        <span id="password_error" class="help-block"></span>
                    </div>
                </div>

                <div class="form-group" id="confirm_password_div">
                    <label class="col-md-4 control-label" for="confirm_password" >Confirm Password</label> 
                    <div class="col-md-4 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input name="confirm_password" id="confirm_password" class="form-control"  type="password" required>
                        </div>
                        <span id="confirm_password_error" class="help-block"></span>
                    </div>
                </div>

                <button class="btn btn-lg btn-primary btn-block text-uppercase" id="register" type="submit">Register</button>
                <a href="/login.php"><p style="text-align: right;">User Panel</p></a>
            </form>
            
        </div>
    </body>
</html>