<?php
    //Profile page for user and admin
    include 'database.php';
    session_start();    //Starting a session

    //redirect user to login page if not logged in
    if(!isset($_SESSION["user_id"])){
        header('Location: login.php');
    }

    //Fetch all the items that user has purchased in past
    $stmt = $conn->prepare("SELECT * FROM history WHERE user_id=?");
    $stmt->execute([$_SESSION["user_id"]]);
    $items = $stmt->fetchAll();

    //Fetch user information
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id=?");
    $stmt->execute([$_SESSION["user_id"]]);
    $user = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Technoholic-User Profile</title>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Bootstrap v3.4.1 CDN links -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <!-- Sweet Alert Library -->
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <!-- Font Awesome Icon Library -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!--CSS-->
        <link rel="stylesheet" href="css/style.css">
        <style>
            
            .glyphicon-edit:hover{
                cursor: pointer;
            }
            .glyphicon-trash:hover{
                cursor: pointer;
            }
            #userButton, #sellerButton{
                width: 100%;
                margin: 5px;
            }
            
        </style>
        <script>
            $(document).ready(function(){
                 //Show number of items in cart if user is logged in
                showCartCount();
                function showCartCount(){
                    $.ajax({
                        url: "rating.php",
                        type: "POST",
                        data: {act: "cartCount", user_id: <?php echo $_SESSION["user_id"];?>},
                        success: function(data){
                            $("#cartCountSpan").html(data);
                        }
                    });  
                }
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
                $("#editDiv").hide();
                $("#passwordDiv").hide();
                $("#sellersDiv").hide();
                //Show history div whenever clicked
                $("#historyNav").click(function(){
                    $("#historyNav").removeClass().addClass("active");
                    $("#editNav").removeClass();
                    $("#passwordNav").removeClass();
                    $("#editDiv").hide();
                    $("#passwordDiv").hide();
                    $("#historyDiv").show(500);
                });
                //Show edit div whenever clicked
                $("#editNav").click(function(){
                    $("#editNav").removeClass().addClass("active");
                    $("#historyNav").removeClass();
                    $("#passwordNav").removeClass();
                    $("#historyDiv").hide();
                    $("#passwordDiv").hide();
                    $("#editDiv").show(500);

                });
                //Show password div whenever clicked
                $("#passwordNav").click(function(){
                    $("#passwordNav").removeClass().addClass("active");
                    $("#editNav").removeClass();
                    $("#historyNav").removeClass();
                    $("#historyDiv").hide();
                    $("#editDiv").hide();
                    $("#passwordDiv").show(500);
                });
                //Show user div whenever clicked
                $("#usersNav").click(function(){
                    $("#usersNav").removeClass().addClass("active");
                    $("#sellersNav").removeClass();
                    $("#adminPasswordNav").removeClass();
                    $("#sellersDiv").hide();
                    $("#usersDiv").show(500);
                });

                //Show seller div whenever clicked
                $("#sellersNav").click(function(){
                    $("#sellersNav").removeClass().addClass("active");
                    $("#usersNav").removeClass();
                    $("#adminPasswordNav").removeClass();
                    $("#usersDiv").hide();
                    $("#sellersDiv").show(500);
                });

               
                //Update user information
                $("#update").click(function(){
                    event.preventDefault();
                    var fullname = $("#full_name").val();
                    var username = $("#user_name").val();
                  
                    $.ajax({
                        url: "rating.php",
                        type: "POST",
                        data: {act: "updateProfile", fullname: fullname, username: username, user_id: <?php echo $_SESSION["user_id"];?>},
                        success: function(data){
                            swal("Done", "Successfully updated", "success");
                            $("#username").html(username);          
                        }
                    });
                });

                //Validate and change the password
                $("#change_password").click(function(){
                    event.preventDefault();
                    var currentPassword = $("#current_password").val();
                    var newPassword = $("#new_password").val();
                  
                    $.ajax({
                        url: "rating.php",
                        type: "POST",
                        data: {act: "changePassword", current: currentPassword, new: newPassword, user_id: <?php echo $_SESSION["user_id"];?>},
                        success: function(data){
                            if(data == 0){
                                swal("error", "wrong password!", "error");
                            }else if(data == 1){
                                swal("error", "Password length should be between 8 and 20 characters.", "error");
                            }else if(data == 2){
                                swal("Done", "Password successfully changed", "success");
                            }

                        }
                    });
                });
                //Remove user
                $(".glyphicon-trash").click(function(){
                    swal({
                        title: "Are you sure?", 
                        text: "Do you want to remove this user?", 
                        buttons: ["Cancel", "Remove"]
                        }).then(okay=>{
                            if(okay){
                                $.ajax({
                                    url: "rating.php",
                                    type: "POST",
                                    data: {act: "deleteUser", email: this.id},
                                    success: function(data){
                                        window.location.href = 'user_profile.php';
                                    }
                                });
                            }
                        });
                });
                //Remove Seller
                $(".sellerRemove").click(function(){
                    swal({
                        title: "Are you sure?", 
                        text: "Do you want to remove this seller?", 
                        buttons: ["Cancel", "Remove"]
                        }).then(okay=>{
                            if(okay){
                                $.ajax({
                                    url: "rating.php",
                                    type: "POST",
                                    data: {act: "deleteSeller", email: this.id},
                                    success: function(data){
                                        window.location.href = 'user_profile.php';
                                    }
                                });
                            }
                        });
                });

                //Add User
                $("#addUserButton").click(function(){
                    var fullname = $("#userModalFullname").val();
                    var username = $("#userModalUsername").val();
                    var email = $("#userModalEmail").val();
                    var password = $("#userModalPassword").val();
                    var que1 = $("#userModalQuestion1").val();
                    var que2 = $("#userModalQuestion2").val();
                    var que3 = $("#userModalQuestion3").val();
                    var ans1 = $("#userModalAnswer1").val();
                    var ans2 = $("#userModalAnswer2").val();
                    var ans3 = $("#userModalAnswer3").val();

                    if(fullname == "" || username == "" || email == "" || password == "" || que1 == "" || que2 == "" || que3 == "" || ans1 == "" || ans2 == "" || ans3 == ""){
                        swal("Error", "Every fields are required", "error");
                    }else{
                        $.ajax({
                            url: "rating.php",
                            type: "POST",
                            data: {act: "addUser", fullname: fullname, username: username, email: email, password: password, que1: que1, que2: que2, que3: que3, ans1: ans1, ans2: ans2, ans3: ans3},
                            success: function(data){
                                if(data == 4){
                                    $('#myModal').modal('toggle');
                                    window.location.href = 'user_profile.php';
                                }else if(data == 1){
                                    swal("Error", "Email address already exist.", "error");
                                }else if(data == 2){
                                    swal("Error", "Email address not valid.", "error");
                                }else if(data == 3){    
                                    swal("Error", "Password length shold be in between 8 and 20 characters.", "error");
                                }
                                
                            }
                        });
                    }
                });
                //Add Seller
                $("#addSellerButton").click(function(){
                    var username = $("#sellerModalUsername").val();
                    var email = $("#sellerModalEmail").val();
                    var password = $("#sellerModalPassword").val();
                    
                    if(username == "" || email == "" || password == ""){
                        swal("Error", "Every fields are required", "error");
                    }else{
                        $.ajax({
                            url: "rating.php",
                            type: "POST",
                            data: {act: "addSeller", username: username, email: email, password: password},
                            success: function(data){
                                if(data == 4){
                                    $('#myModal').modal('toggle');
                                    window.location.href = 'user_profile.php';
                                }else if(data == 1){
                                    swal("Error", "Email address already exist.", "error");
                                }else if(data == 2){
                                    swal("Error", "Email address not valid.", "error");
                                }else if(data == 3){    
                                    swal("Error", "Password length shold be in between 8 and 20 characters.", "error");
                                }
                                
                            }
                        });
                    }
                });
            });
        </script>
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
                                    //Get all categories to show in navbar
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
                            //Check if user is logged in or not
                            if(isset($_SESSION["user"])){
                                echo '<li><a id="username" class="active" href="user_profile.php">'.$user[0]["username"].'</a></li>';
                                echo '<li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>';
                            }else{
                                echo '<li><a href="login.php">Login</a></li>';
                            }
                        ?>

                        
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <div class="container-fluid" id="main">
                
            <?php 
            //Check if user has an admin level access
            if($_SESSION["admin_access"] == 1){?>
                <h1 style="text-align: center;">Welcome to Admin Panel <?php echo $user[0]["fullname"];?></h1>
                <hr/>
            <?php }else{?>
                <h1 style="text-align: center;">Welcome <?php echo $user[0]["fullname"];?></h1>
                <hr/>
            <?php }?>
            <?php if($_SESSION["admin_access"] == 1){?>
                
                <ul class="nav nav-pills nav-justified">
                    <li role="presentation" class="active" id="usersNav"><a href="#">Users</a></li>
                    <li role="presentation" id="sellersNav"><a href="#">Sellers</a></li>
                </ul>
                <div id="usersDiv">
                    <button class="btn btn-danger" id="userButton" data-toggle="modal" data-target="#myModal">Add a new user</button>
                    <div class="row equal">
                    <?php
                    //All users
                        $stmt = $conn->prepare("SELECT * FROM users");
                        $stmt->execute();
                        $allUsers = $stmt->fetchAll();
                    ?>
                    
                    <table class="table table-hover">
                        <tr>
                            <th>User ID</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Admin Access</th>
                            <th>Remove</th>
                        </tr>
                        <?php
                        foreach($allUsers as $row){
                            $admin = "No";
                            if($row["admin_access"]==1){
                                $admin = "Yes";
                            }
                            echo "<tr>";
                            echo "<td>".$row["user_id"]."</td>";
                            echo "<td>".$row["fullname"]."</td>";
                            echo "<td>".$row["username"]."</td>";
                            echo "<td>".$row["email"]."</td>";
                            echo "<td>".$admin."</td>";
                            //Admin level user can't be removed
                            if($admin == "Yes"){
                                echo '<td><span style="color: indigo; disabled: disabled;" class=" glyphicon glyphicon-trash" aria-hidden="true"></span></td>';
                            }else{
                                echo '<td><span id="'.$row["email"].'" style="color: red;" class=" glyphicon glyphicon-trash" aria-hidden="true"></span></td>';
                            }
                            
                            echo "</tr>";
                        }
                        ?>
                        
                    </table>
                    
                    </div>
                </div>
                <div id="sellersDiv">
                <button class="btn btn-danger" id="sellerButton" data-toggle="modal" data-target="#myModal2">Add a new seller</button>
                    <div class="row equal">
                        <?php
                        //All sellers
                            $stmt = $conn->prepare("SELECT * FROM sellers");
                            $stmt->execute();
                            $sellers = $stmt->fetchAll();
                        ?>
                        <table class="table table-hover">
                            <tr>
                                <th>Seller ID</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Remove</th>
                            </tr>
                            <?php
                            foreach($sellers as $row){
                                
                                echo "<tr>";
                                echo "<td>".$row["seller_id"]."</td>";
                                echo "<td>".$row["username"]."</td>";
                                echo "<td>".$row["email"]."</td>";
                                echo '<td><span id="'.$row["email"].'" style="color: red;" class=" glyphicon glyphicon-trash sellerRemove" aria-hidden="true"></span></td>';
                                echo "</tr>";
                            }
                            ?>
                        </table>
                    </div>
                </div>    
                
            <?php }?>
                    
                    <ul class="nav nav-pills nav-justified">
                        <li role="presentation" class="active" id="historyNav"><a href="#">History</a></li>
                        <li role="presentation" id="editNav"><a href="#">Edit Profile</a></li>
                        <li role="presentation" id="passwordNav"><a href="#">Change Password</a></li>
                    </ul>
                    <div id="historyDiv">
                        <div class="row equal">
                        <?php
                            foreach($items as $item){
                                $stmt = $conn->prepare("SELECT * FROM products WHERE product_id=?");
                                $stmt->execute([$item["product_id"]]);
                                $prods = $stmt->fetchAll();
                                
                                echo "<div  class='col-xs-12 col-sm-6 col-md-3'><a href='product.php?product_id=".$prods[0]["product_id"]."'><figure><a href='product.php?product_id=".$prods[0]["product_id"]."'><img class='img-responsive center-block' src='seller/img/".$prods[0]["product_img1"]."'/></a><figcaption><a href='product.php?product_id=".$prods[0]["product_id"]."'>".$prods[0]["product_title"]."</figcaption></figure></a></div>";
                            }
                        ?>
                        </div>
                    </div>
                    <div id="editDiv">
                    <form id="update_form" class="well form-horizontal">
                            <div class="form-group" id="full_name_div">
                                <label for="full_name" class="col-md-4 control-label">Full Name</label>
                                <div class="col-md-4 inputGroupContainer">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input  id="full_name" name="full_name" value="<?php echo $user[0]["fullname"];?>" class="form-control"  type="text" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="username_div">
                                <label for="user_name" class="col-md-4 control-label">Username</label>
                                <div class="col-md-4 inputGroupContainer">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input  id="user_name" value="<?php echo $user[0]["username"];?>" name="user_name" class="form-control"  type="text" required>
                                    
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-lg btn-primary btn-block text-uppercase" id="update">Update Profile</button>
                        </form>
                    </div>
                    <div id="passwordDiv">
                        <form id="update_form" class="well form-horizontal">
                            <div class="form-group">
                                <label for="current_password" class="col-md-4 control-label">Current password</label>
                                <div class="col-md-4 inputGroupContainer">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input  id="current_password" class="form-control"  type="password" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="new_password" class="col-md-4 control-label">New password</label>
                                <div class="col-md-4 inputGroupContainer">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input  id="new_password" class="form-control"  type="password" required>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-lg btn-primary btn-block text-uppercase" id="change_password">Change Password</button>
                        </form>
                    </div>
                    <!-- Modal for add a new user -->
                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Add a new user</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="well form-horizontal">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Full Name</label>
                                            <div class="col-md-4 inputGroupContainer">
                                                <div class="input-group">
                                                    <input  id="userModalFullname" class="form-control"  type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Username</label>
                                            <div class="col-md-4 inputGroupContainer">
                                                <div class="input-group">
                                                    <input  id="userModalUsername" class="form-control"  type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Email</label>
                                            <div class="col-md-4 inputGroupContainer">
                                                <div class="input-group">
                                                    <input  id="userModalEmail" class="form-control"  type="email">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Password</label>
                                            <div class="col-md-4 inputGroupContainer">
                                                <div class="input-group">
                                                    <input  id="userModalPassword" class="form-control"  type="password">
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Question 1</label>
                                            <div class="col-md-4 inputGroupContainer">
                                                <div class="input-group">
                                                    <input  id="userModalQuestion1" class="form-control"  type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Answer 1</label>
                                            <div class="col-md-4 inputGroupContainer">
                                                <div class="input-group">
                                                    <input  id="userModalAnswer1" class="form-control"  type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Question 2</label>
                                            <div class="col-md-4 inputGroupContainer">
                                                <div class="input-group">
                                                    <input  id="userModalQuestion2" class="form-control"  type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Answer 2</label>
                                            <div class="col-md-4 inputGroupContainer">
                                                <div class="input-group">
                                                    <input  id="userModalAnswer2" class="form-control"  type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Question 3</label>
                                            <div class="col-md-4 inputGroupContainer">
                                                <div class="input-group">
                                                    <input  id="userModalQuestion3" class="form-control"  type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Answer 3</label>
                                            <div class="col-md-4 inputGroupContainer">
                                                <div class="input-group">
                                                    <input  id="userModalAnswer3" class="form-control"  type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button id="addUserButton" type="button" class="btn btn-success">Add Seller</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        
                        </div>
                    </div>
                    <!-- Modal to add a new seller-->
                    <div class="modal fade" id="myModal2" role="dialog">
                        <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Add a new seller</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="well form-horizontal">
                                        
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Username</label>
                                            <div class="col-md-4 inputGroupContainer">
                                                <div class="input-group">
                                                    <input  id="sellerModalUsername" class="form-control"  type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Email</label>
                                            <div class="col-md-4 inputGroupContainer">
                                                <div class="input-group">
                                                    <input  id="sellerModalEmail" class="form-control"  type="email">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Password</label>
                                            <div class="col-md-4 inputGroupContainer">
                                                <div class="input-group">
                                                    <input  id="sellerModalPassword" class="form-control"  type="password">
                                                </div>
                                            </div>
                                        </div> 
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button id="addSellerButton" type="button" class="btn btn-success">Add Seller</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        
                        </div>
                    </div>   
        </div>
         <!--Searched items will be appeared at here-->
        <div class="fluid-container" id="search_div">
            <div class="row equal" id="searchedItem">

            </div>
        </div>  
                          
    </body>
</html>