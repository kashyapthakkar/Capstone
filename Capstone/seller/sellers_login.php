<?php
    //Login page for sellers
    session_start();            //Starting a session
    //Redirect logged in sellers to home page   
    if(isset($_SESSION["sellerid"])){
        header('Location: seller.php');
    }
    include '../database.php';
    //If login for is submitted run the following code
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email = $_POST["email"];
        $password = $_POST["password"];
        
        $flag = true;
        $errors = array();                                                  //An error to keep track of errors
        $stmt = $conn->prepare("SELECT * FROM sellers WHERE Email='$email';");
        $stmt->execute();
        $user = $stmt->fetch();

        if($user){
           if($user['password'] == md5($password)){
               //Make a session variable with seller'seller's username and id
            $_SESSION["seller"] = $user['username'];    
            $_SESSION["sellerid"] = $user['seller_id'];
            header('Location: seller.php');            //Redirect seller to home page
           }
        }else{
            array_push($errors, "Check Email/Password.");
        }


    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Shopaholic-Sellers Login</title>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Bootstrap v3.4.1 CDN links -->
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

            #login_form{
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

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          
            <form class="navbar-form navbar-right">
                <div class="form-group">
                <input type="text" class="form-control" placeholder="Search">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="sellers_register.php">Signup</a></li>
            </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
        </nav>
        <div class="container">
        <h1 style="text-align: center;">Seller-Login</h1>
            <form id="login_form" class="well form-horizontal" action="sellers_login.php" method="post">
            <?php
                if(isset($errors)){
                    foreach($errors as $row){
                        ?><p style="color: red;"><strong><?php echo $row;?></strong></p><?
                    }
                }
                ?>

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

                <button class="btn btn-lg btn-primary btn-block text-uppercase" id="register" type="submit">Login</button>
                <a href="../login.php"><p style="text-align: right;">User Panel</p></a>
            </form>
            
        </div>
    </body>
</html>