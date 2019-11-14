<?php
    session_start();        //Starting session
    include 'database.php';

    //redirect user to home page if logged in
    if(isset($_SESSION["user_id"])){
        header('Location: index.php');
    }
    //Prevent users for accessing this page directly
    if(isset($_SESSION["forget_password"])){
        $email = $_SESSION["forget_password"];

        //Get user data
        $stmt = $conn->prepare("SELECT * FROM users WHERE email='$email';");
        $stmt->execute([$_SESSION["forget_password"]]);
        $user = $stmt->fetch();
        //Security Questions
        $question1 = $user["question1"];
        $question2 = $user["question2"];
        $question3 = $user["question3"];
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(!isset($_SESSION["forget_password"])){
            $email = $_POST["email"];
            //Array of errors
            $errors = array();
            $stmt = $conn->prepare("SELECT * FROM users WHERE email='$email';");
            $stmt->execute();
            $user = $stmt->fetch();
            if($user){
                $question1 = $user["question1"];
                $question2 = $user["question2"];
                $question3 = $user["question3"];
                $_SESSION["forget_password"] = $email;
            }else{
                array_push($errors, "Email address doesn't exist.");
            }
        }else{


            $password = $_POST["password"];

            //Answers entered by user
            $answer1 = $_POST["answer1"];
            $answer2 = $_POST["answer2"];
            $answer3 = $_POST["answer3"];

            $flag = true;
            $errors = array();

            //Check new password length
            if(strlen($password) < 8 || strlen($password) > 20){
                array_push($errors, "Password length should be between 8 and 20.");
                $flag = false;
            }

            if ($flag) {
                $password = md5($password);//encrypt the password before saving in the database

                //encrypt the answers before saving in the database
                $answer1 = md5($answer1);
                $answer2 = md5($answer2);
                $answer3 = md5($answer3);
                //Check if security answers matches
                if($answer3 == $user["answer3"]){
                    echo "matched";
                }
                if($answer1 == $user["answer1"] && $answer2 == $user["answer2"] && $answer3 == $user["answer3"]){
                    $stmt = $conn->prepare("UPDATE users SET password = ? where user_id = ?");
                    $stmt->execute([$password, $user["user_id"]]);
                    session_unset();																//unset the session

                    session_destroy();																//destroy the session
                    header('Location: login.php');
                }else{
                    array_push($errors, "Your answers doesn't match, please contact support.");
                }
            }        
        }
    
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Technoholic</title>
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
        <!--CSS-->
        <link rel="stylesheet" href="css/style.css">
        <style>
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

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home <span class="sr-only">(current)</span></a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Categories<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">All Products</a></li>
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM Categories");
                            $stmt->execute();
                            $cats = $stmt->fetchAll();
                            

                            foreach($cats as $row){
                                echo "<li><a href='index.php?".$row['cat_id']."'>".$row['cat_title']."</a></li>";                   
                            }?>                        
                    </ul>
                </li>
                <li><a href="cart.php">Shopping Cart</a></li>
                <li><a href="favourites.php">Favourites</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <li><a href="about.php">About</a></li>
            </ul>
       
            <ul class="nav navbar-nav navbar-right">
                <li><a href="login.php">Login</a></li>
            </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
        </nav>
        <div class="container">
            <form id="register_form" class="well form-horizontal" action="password_reset.php" method="post">
            <?php
                if(isset($errors)){
                    foreach($errors as $row){
                        ?><p style="color: red;"><strong><?php echo $row;?></strong></p><?
                    }
                }

                if(!isset($_SESSION["forget_password"])){?>
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
                <?php }else{?>
                
                

                <div class="form-group" id="password_div">
                    <label class="col-md-4 control-label" for="password">New Password</label> 
                    <div class="col-md-4 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input name="password" id="password" class="form-control"  type="password" required>
                        </div>
                        <span id="password_error" class="help-block"></span>
                    </div>
                </div>

                <fieldset>
                    <legend>Security Questions</legend>
                    <div class="form-group" id="question1_div">
                        <label for="question1" class="col-md-4 control-label">Question-1</label>
                        <div class="col-md-4 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-question-sign"></i></span>
                                <input  id="question1" name="question1" class="form-control" value="<?php echo $question1;?>"  type="text" required disabled>
                            
                            </div>
                            <span id="question1_error" class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group" id="answer1_div">
                        <label for="answer1" class="col-md-4 control-label">Answer-1</label>
                        <div class="col-md-4 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input  id="answer1" name="answer1" class="form-control"  type="text" required>
                            
                            </div>
                            <span id="answer1_error" class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group" id="question2_div">
                        <label for="question2" class="col-md-4 control-label">Question-2</label>
                        <div class="col-md-4 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-question-sign"></i></span>
                                <input  id="question2" name="question2" class="form-control" value="<?php echo $question2;?>"  type="text" required disabled>
                            
                            </div>
                            <span id="question2_error" class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group" id="answer2_div">
                        <label for="answer2" class="col-md-4 control-label">Answer-2</label>
                        <div class="col-md-4 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input  id="answer2" name="answer2" class="form-control"  type="text" required      >
                            
                            </div>
                            <span id="answer2_error" class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group" id="question3_div">
                        <label for="question3" class="col-md-4 control-label">Question-3</label>
                        <div class="col-md-4 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-question-sign"></i></span>
                                <input  id="question3" name="question3" class="form-control" value="<?php echo $question3;?>"  type="text" required disabled>
                            
                            </div>
                            <span id="question3_error" class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group" id="answer3_div">
                        <label for="answer3" class="col-md-4 control-label">Answer-3</label>
                        <div class="col-md-4 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input  id="answer3" name="answer3" class="form-control"  type="text" required>
                            
                            </div>
                            <span id="answer3_error" class="help-block"></span>
                        </div>
                    </div>
                </fieldset>
                <?php } ?>
                <button class="btn btn-lg btn-primary btn-block text-uppercase" id="register" type="submit">Change Password</button>
            </form>
            
        </div>
    </body>
</html>