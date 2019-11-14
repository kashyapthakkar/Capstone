<?php
    //Login page 
    session_start();    //Starting a session
    include 'database.php';

    //redirect user to home page if logged in
    if(isset($_SESSION["user_id"])){
        header('Location: index.php');
    }

    //if user has submitted a form
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email = $_POST["email"];         
        $password = $_POST["password"];
        //Array to keep track of errors
        $errors = array();

        //check if user exist and then match password 
        $stmt = $conn->prepare("SELECT * FROM users WHERE Email='$email';");
        $stmt->execute();
        $user = $stmt->fetch();

        if($user){
           if($user['password'] == md5($password)){
                $_SESSION["user"] = $user['username'];
                $_SESSION["user_id"] = $user['user_id'];
                $_SESSION["admin_access"] = $user['admin_access'];
                header('Location: index.php');
           }else{
                array_push($errors, "Check Email/Password.");       
           }
        }else{
            array_push($errors, "Check Email/Password.");
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
        <!--CSS-->
        <link rel="stylesheet" href="css/style.css">
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
        <style>
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
                                    $stmt = $conn->prepare("SELECT * FROM Categories");
                                    $stmt->execute();
                                    $cats = $stmt->fetchAll();
                                    
                                    foreach($cats as $row){
                                        echo "<li><a href='category.php?cat_id=".$row['cat_id']."'>".$row['cat_title']."</a></li>";                   
                                    }?>                        
                            </ul>
                        </li>
                        <li><a href="cart.php">Shopping Cart</a></li>
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
                            if(isset($_SESSION["user"])){
                                echo '<li><a href="#">'.$_SESSION["user"].'</a></li>';
                                echo '<li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>';
                            }else{
                                echo '<li><a href="register.php">Sign-up</a></li>';
                            }
                        ?> 

                        
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <div class="container" id="main">
            <h1 style="text-align: center;">User-Login</h1>
            <form id="login_form" class="well form-horizontal" action="login.php" method="post">
            <?php
                //show errors
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
                <br/>
                <p>Don't have an account?</p>
                <a class="btn btn-lg btn-success btn-block text-uppercase" href="register.php">Register</a>
                <a href="password_reset.php"><p style="text-align: right;">Forgot Password?</p></a>
                <a href="seller/sellers_login.php"><p style="text-align: right;">Seller Panel</p></a>
            </form>
            
        </div>
        <div class="fluid-container" id="search_div">
            <div class="row equal" id="searchedItem">

            </div>
        </div>  
    </body>
</html>