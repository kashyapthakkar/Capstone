<?php
    //Registration page for users
    session_start();                    //Starting a session
    include 'database.php';
    //Redirect logged in users to home page
    if(isset($_SESSION["user_id"])){
        header('Location: index.php');
    }
    //if for is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //Getting post data submitted in form
        $user_name = $_POST["user_name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $password2 = $_POST["confirm_password"];
        $fullname = $_POST["full_name"];
        $question1 = $_POST["question1"];
        $question2 = $_POST["question2"];
        $question3 = $_POST["question3"];
        $answer1 = $_POST["answer1"];
        $answer2 = $_POST["answer2"];
        $answer3 = $_POST["answer3"];
        
        $flag = true;
        //An array to keep trak of errors
        $errors = array();
        //Check if email address already exist
        $stmt = $conn->prepare("SELECT * FROM users WHERE email='$email';");
        $stmt->execute();
        $user = $stmt->fetch();
        if($user){
            array_push($errors, "Email address already exist.");
            $flag = false;
        }
        //Check if password is in range
        if(strlen($password) < 8 || strlen($password) > 20){
            array_push($errors, "Password length should be between 8 and 20.");
            $flag = false;
        }

        //if email is invalid then show an error
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Invalid email address.");
            $flag = false;
        }
        //check if both the passwords matches
        if ($password != $password2) {
            array_push($errors, "Passwords doesn't match.");
            $flag = false;
        }
        //if there are no errors then add seller to database
        if ($flag) {
            $password = md5($password);//encrypt the password before saving in the database

            //encrypt the answers before saving in the database
            $answer1 = md5($answer1);
            $answer2 = md5($answer2);
            $answer3 = md5($answer3);
            $stmt = $conn->prepare("INSERT INTO users " .
                                    "(fullname,username,email,password,question1,question2,question3,answer1,answer2,answer3) VALUES " .
                                    "(?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$fullname, $user_name, $email, $password, $question1, $question2, $question3, $answer1, $answer2, $answer3]);
            header('Location: login.php');
        }
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Technoholic-User Registraion</title>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Bootstrap v3.4.1 CDN links -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <script>
                $(document).ready(function() {
                    //event listioner to fetch items metching with search string and showing those items on current page
                    $("#search_bar").keyup(function(){
                        $("#searchedItem").html("");
                        var search_string = $("#search_bar").val();
                        if(search_string != ""){
                            $("#main").hide();
                            $("#search_div").show();
                            <?php
                                $stmt = $conn->prepare("SELECT * FROM products");
                                $stmt->execute();
                                $products = $stmt->fetchAll();
                            ?>
                            var products = <?php print_r(json_encode($products));?>;
                            var reviews = 0;
                            for(var i=0; i<products.length; i++){
                                if(products[i]["product_title"].toLowerCase().includes(search_string.toLowerCase()) || products[i]["product_desc"].toLowerCase().includes(search_string.toLowerCase()) || products[i]["product_keywords"].toLowerCase().includes(search_string.toLowerCase())){
                                    if(products[i]["ratings"] == 0){
                                        $("#searchedItem").append("<div class='col-xs-12 col-sm-6 col-md-3'><a href='product.php?product_id=" + products[i]["product_id"] + "'><figure><img class='img-responsive center-block' src='seller/img/" + products[i]["product_img1"] + "'/><figcaption>" + products[i]["product_title"] + "<p><span id='rating_star1' style='color: black;' class='fa fa-star'></span><span id='rating_star2' style='color: black;' class='fa fa-star'></span><span id='rating_star3' style='color: black;' class='fa fa-star'></span><span id='rating_star4' style='color: black;' class='fa fa-star'></span><span id='rating_star5' style='color: black;' class='fa fa-star'></span></p></figcaption></figure></a></div>");
                                        
                                    }else if(products[i]["ratings"] == 1){
                                        $("#searchedItem").append("<div class='col-xs-12 col-sm-6 col-md-3'><a href='product.php?product_id=" + products[i]["product_id"] + "'><figure><img class='img-responsive center-block' src='seller/img/" + products[i]["product_img1"] + "'/><figcaption>" + products[i]["product_title"] + "<p><span id='rating_star1' style='color: orange;' class='fa fa-star'></span><span id='rating_star2' style='color: black;' class='fa fa-star'></span><span id='rating_star3' style='color: black;' class='fa fa-star'></span><span id='rating_star4' style='color: black;' class='fa fa-star'></span><span id='rating_star5' style='color: black;' class='fa fa-star'></span></p></figcaption></figure></a></div>");
                                    }else if(products[i]["ratings"] == 2){
                                        $("#searchedItem").append("<div class='col-xs-12 col-sm-6 col-md-3'><a href='product.php?product_id=" + products[i]["product_id"] + "'><figure><img class='img-responsive center-block' src='seller/img/" + products[i]["product_img1"] + "'/><figcaption>" + products[i]["product_title"] + "<p><span id='rating_star1' style='color: orange;' class='fa fa-star'></span><span id='rating_star2' style='color: orange;' class='fa fa-star'></span><span id='rating_star3' style='color: black;' class='fa fa-star'></span><span id='rating_star4' style='color: black;' class='fa fa-star'></span><span id='rating_star5' style='color: black;' class='fa fa-star'></span></p></figcaption></figure></a></div>");
                                    }else if(products[i]["ratings"] == 3){
                                        $("#searchedItem").append("<div class='col-xs-12 col-sm-6 col-md-3'><a href='product.php?product_id=" + products[i]["product_id"] + "'><figure><img class='img-responsive center-block' src='seller/img/" + products[i]["product_img1"] + "'/><figcaption>" + products[i]["product_title"] + "<p><span id='rating_star1' style='color: orange;' class='fa fa-star'></span><span id='rating_star2' style='color: orange;' class='fa fa-star'></span><span id='rating_star3' style='color: orange;' class='fa fa-star'></span><span id='rating_star4' style='color: black;' class='fa fa-star'></span><span id='rating_star5' style='color: black;' class='fa fa-star'></span></p></figcaption></figure></a></div>");
                                    }else if(products[i]["ratings"] == 4){
                                        $("#searchedItem").append("<div class='col-xs-12 col-sm-6 col-md-3'><a href='product.php?product_id=" + products[i]["product_id"] + "'><figure><img class='img-responsive center-block' src='seller/img/" + products[i]["product_img1"] + "'/><figcaption>" + products[i]["product_title"] + "<p><span id='rating_star1' style='color: orange;' class='fa fa-star'></span><span id='rating_star2' style='color: orange;' class='fa fa-star'></span><span id='rating_star3' style='color: orange;' class='fa fa-star'></span><span id='rating_star4' style='color: orange;' class='fa fa-star'></span><span id='rating_star5' style='color: black;' class='fa fa-star'></span></p></figcaption></figure></a></div>");
                                    }else if(products[i]["ratings"] == 5){
                                        $("#searchedItem").append("<div class='col-xs-12 col-sm-6 col-md-3'><a href='product.php?product_id=" + products[i]["product_id"] + "'><figure><img class='img-responsive center-block' src='seller/img/" + products[i]["product_img1"] + "'/><figcaption>" + products[i]["product_title"] + "<p><span id='rating_star1' style='color: orange;' class='fa fa-star'></span><span id='rating_star2' style='color: orange;' class='fa fa-star'></span><span id='rating_star3' style='color: orange;' class='fa fa-star'></span><span id='rating_star4' style='color: orange;' class='fa fa-star'></span><span id='rating_star5' style='color: orange;' class='fa fa-star'></span></p></figcaption></figure></a></div>");
                                    }
                                }
                            }
                        }else{
                            $("#search_div").hide();
                            $("#main").show();
                        }

                    });
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
                    <a class="navbar-brand" href="index.php">Technoholic</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="index.php">Home <span class="sr-only">(current)</span></a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Categories<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">All Products</a></li>
                                <?php
                                    //Fetch categories to show in navbar
                                    $stmt = $conn->prepare("SELECT * FROM Categories");
                                    $stmt->execute();
                                    $cats = $stmt->fetchAll();
                                
                                    foreach($cats as $row){
                                        echo "<li><a href='category.php?cat_id=".$row['cat_id']."'>".$row['cat_title']."</a></li>";                   
                                    }?>                        
                            </ul>
                        </li>
                        <li><a href="#">Shopping Cart</a></li>
                        <li><a href="favourites.php">Favourites</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                        <li><a href="about.php">About</a></li>
                    </ul>
                    <form class="navbar-form navbar-right">
                        <div class="form-group">
                            <input type="text" id="search_bar" class="form-control" placeholder="Search">
                        </div>
                    </form>
                    <ul class="nav navbar-nav navbar-right">
                        <?php
                            //Check if user is logged in
                            if(isset($_SESSION["user"])){
                                echo '<li><a href="#">'.$_SESSION["user"].'</a></li>';
                                echo '<li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>';
                            }else{
                                echo '<li><a href="login.php">Login</a></li>';
                            }
                        ?> 

                        
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <div class="container" id="main">
            <h1 style="text-align: center;">User-Register</h1>
            <form id="register_form" class="well form-horizontal" action="register.php" method="post">
            <?php
                //Show errors
                if(isset($errors)){
                    foreach($errors as $row){
                        ?><p style="color: red;"><strong><?php echo $row;?></strong></p><?
                    }
                }
                ?>
                <div class="form-group" id="full_name_div">
                    <label for="full_name" class="col-md-4 control-label">Full Name</label>
                    <div class="col-md-4 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input  id="full_name" name="full_name" class="form-control"  type="text" required>
                           
                        </div>
                        <span id="full_name_error" class="help-block"></span>
                    </div>
                </div>
                <div class="form-group" id="username_div">
                    <label for="user_name" class="col-md-4 control-label">Username</label>
                    <div class="col-md-4 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input  id="user_name" name="user_name" class="form-control"  type="text" required>
                           
                        </div>
                        <span id="user_name_error" class="help-block"></span>
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
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input name="password" id="password" class="form-control"  type="password" required>
                        </div>
                        <span id="password_error" class="help-block"></span>
                    </div>
                </div>

                <div class="form-group" id="confirm_password_div">
                    <label class="col-md-4 control-label" for="confirm_password" >Confirm Password</label> 
                    <div class="col-md-4 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input name="confirm_password" id="confirm_password" class="form-control"  type="password" required>
                        </div>
                        <span id="confirm_password_error" class="help-block"></span>
                    </div>
                </div>

                <fieldset>
                    <legend>Security Questions(In case if you forget or want to change your password!)</legend>
                    <div class="form-group" id="question1_div">
                        <label for="question1" class="col-md-4 control-label">Question-1</label>
                        <div class="col-md-4 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-question-sign"></i></span>
                                <input  id="question1" name="question1" class="form-control"  type="text" required>
                            
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
                                <input  id="question2" name="question2" class="form-control"  type="text" required>
                            
                            </div>
                            <span id="question2_error" class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group" id="answer2_div">
                        <label for="answer2" class="col-md-4 control-label">Answer-2</label>
                        <div class="col-md-4 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input  id="answer2" name="answer2" class="form-control"  type="text" required>
                            
                            </div>
                            <span id="answer2_error" class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group" id="question3_div">
                        <label for="question3" class="col-md-4 control-label">Question-3</label>
                        <div class="col-md-4 inputGroupContainer">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-question-sign"></i></span>
                                <input  id="question3" name="question3" class="form-control"  type="text" required>
                            
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

                <button class="btn btn-lg btn-primary btn-block text-uppercase" id="register" type="submit">Register</button>
            </form>
            
        </div>
         <!--Searched items will be appeared at here-->
        <div class="fluid-container" id="search_div">
            <div class="row equal" id="searchedItem">

            </div>
        </div>  
    </body>
</html>