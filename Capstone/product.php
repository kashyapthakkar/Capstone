<?php
    //Product Page
    session_start();        //Starting a session
    include 'database.php';
    //Fetching a product data
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$_REQUEST["product_id"]]);
    $prods = $stmt->fetchAll();
    //Seller data
    $stmt = $conn->prepare("SELECT * FROM sellers WHERE seller_id = ?");
    $stmt->execute([$prods[0]["seller_id"]]);
    $seller = $stmt->fetchAll();
    //Brand of product
    $stmt = $conn->prepare("SELECT * FROM brands WHERE brand_id = ?");
    $stmt->execute([$prods[0]["brand_id"]]);
    $brand = $stmt->fetchAll();
    //Reviews associated with product
    $stmt = $conn->prepare("SELECT * FROM reviews WHERE product_id = ?");
    $stmt->execute([$_REQUEST["product_id"]]);
    $reviews = $stmt->fetchAll();
    //Products related to current product
    $stmt = $conn->prepare("SELECT * FROM products WHERE cat_id = ?");
    $stmt->execute([$prods[0]["cat_id"]]);
    $matches = $stmt->fetchAll();
    //Check that if user has already rated a product
    if(isset($_SESSION["user_id"])){
        $stmt = $conn->prepare("SELECT * FROM favourites WHERE product_id = ? and user_id = ?");
        $stmt->execute([$_REQUEST["product_id"], $_SESSION["user_id"]]);
        $favourites = $stmt->fetchAll();
    }
    //Number of people who has rated to product
    $userCount = 0; 
    foreach($reviews as $row){ 
        $userCount++;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Technoholic - Product</title>
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
        <link rel="stylesheet" href="css/style.css">
        <style>
            .glyphicon.glyphicon-user {
                font-size: 75px;
            }
        </style>
        <script>
            $(document).ready(function(){
                //Show the number of items in cart if logged in
				<?php if(isset($_SESSION["user_id"])){?>
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
				<?php }?>
                
                //Set the color of heart by checking if user that is logged in has already rated a product or not
                <?php if(isset($_SESSION["user_id"])){?>
                    <?php if(sizeOf($favourites) == 0){?>
                        $("#heart").css("color", "black");
                    <?php }else{ ?>
                        $("#heart").css("color", "#E31B23");
                    <?php }?>    
                <?php }else{ ?>
                    $("#heart").css("color", "black");
                <?php }?>

                //event listioner to fetch items metching with search string and showing those items on current page
                $("#search_div").hide();
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
                //Set the color of stars according to number of total ratings
                var ratings = <?php echo $prods[0]["ratings"];?>;
                if(ratings == 5){
                    $("#rating_star1").css("color", "orange");
                    $("#rating_star2").css("color", "orange");
                    $("#rating_star3").css("color", "orange");
                    $("#rating_star4").css("color", "orange");
                    $("#rating_star5").css("color", "orange");
                }else if(ratings == 4){
                    $("#rating_star1").css("color", "orange");
                    $("#rating_star2").css("color", "orange");
                    $("#rating_star3").css("color", "orange");
                    $("#rating_star4").css("color", "orange");
                }else if(ratings == 3){
                    $("#rating_star1").css("color", "orange");
                    $("#rating_star2").css("color", "orange");
                    $("#rating_star3").css("color", "orange");
                }else if(ratings == 2){
                    $("#rating_star1").css("color", "orange");
                    $("#rating_star2").css("color", "orange");
                }else if(ratings == 1){
                    $("#rating_star1").css("color", "orange");
                }
            
                var final_rating = 0;       //will hold rating given by user

                //for rating a product by hovering a mouse on each stars
                $("#star1").mouseover(function(){
                    $("#star1").css("color", "orange");
                    $("#star2").css("color", "#000");
                    $("#star3").css("color", "#000");
                    $("#star4").css("color", "#000");
                    $("#star5").css("color", "#000");
                });
                $("#star1").mouseout(function(){
                    $("#star1").css("color", "#000");
                    $("#star2").css("color", "#000");
                    $("#star3").css("color", "#000");
                    $("#star4").css("color", "#000");
                    $("#star5").css("color", "#000");
                    var final_rating = 0;
                });

                $("#star2").mouseover(function(){
                    $("#star1").css("color", "orange");
                    $("#star2").css("color", "orange");
                    $("#star3").css("color", "#000");
                    $("#star4").css("color", "#000");
                    $("#star5").css("color", "#000");
                });
                $("#star2").mouseout(function(){
                    $("#star1").css("color", "#000");
                    $("#star2").css("color", "#000");
                    $("#star3").css("color", "#000");
                    $("#star4").css("color", "#000");
                    $("#star5").css("color", "#000");
                    var final_rating = 0;
                });

                $("#star3").mouseover(function(){
                    $("#star1").css("color", "orange");
                    $("#star2").css("color", "orange");
                    $("#star3").css("color", "orange");
                    $("#star4").css("color", "#000");
                    $("#star5").css("color", "#000");
                });
                $("#star3").mouseout(function(){
                    $("#star1").css("color", "#000");
                    $("#star2").css("color", "#000");
                    $("#star3").css("color", "#000");
                    $("#star4").css("color", "#000");
                    $("#star5").css("color", "#000");
                    var final_rating = 0;
                });

                $("#star4").mouseover(function(){
                    $("#star1").css("color", "orange");
                    $("#star2").css("color", "orange");
                    $("#star3").css("color", "orange");
                    $("#star4").css("color", "orange");
                    $("#star5").css("color", "#000");
                });
                $("#star4").mouseout(function(){
                    $("#star1").css("color", "#000");
                    $("#star2").css("color", "#000");
                    $("#star3").css("color", "#000");
                    $("#star4").css("color", "#000");
                    $("#star5").css("color", "#000");
                    var final_rating = 0;
                });

                $("#star5").mouseover(function(){
                    $("#star1").css("color", "orange");
                    $("#star2").css("color", "orange");
                    $("#star3").css("color", "orange");
                    $("#star4").css("color", "orange");
                    $("#star5").css("color", "orange");
                });
                $("#star5").mouseout(function(){
                    $("#star1").css("color", "#000");
                    $("#star2").css("color", "#000");
                    $("#star3").css("color", "#000");
                    $("#star4").css("color", "#000");
                    $("#star5").css("color", "#000");
                    var final_rating = 0;
                });

                $("#star5").click(function(){
                    $("#star5").off("mouseout");
                    $("#star1").css("color", "orange");
                    $("#star2").css("color", "orange");
                    $("#star3").css("color", "orange");
                    $("#star4").css("color", "orange");
                    $("#star5").css("color", "orange");
                    final_rating = 5;
                });

                $("#star4").click(function(){
                    $("#star4").off("mouseout");
                    $("#star1").css("color", "orange");
                    $("#star2").css("color", "orange");
                    $("#star3").css("color", "orange");
                    $("#star4").css("color", "orange");
                    $("#star5").css("color", "#000");
                    final_rating = 4;
                });

                $("#star3").click(function(){
                    $("#star3").off("mouseout");
                    $("#star1").css("color", "orange");
                    $("#star2").css("color", "orange");
                    $("#star3").css("color", "orange");
                    $("#star4").css("color", "#000");
                    $("#star5").css("color", "#000");
                    final_rating = 3;
                });

                $("#star2").click(function(){
                    $("#star2").off("mouseout");
                    $("#star1").css("color", "orange");
                    $("#star2").css("color", "orange");
                    $("#star3").css("color", "#000");
                    $("#star4").css("color", "#000");
                    $("#star5").css("color", "#000");
                    final_rating = 2;
                });

                $("#star1").click(function(){
                    $("#star1").off("mouseout");
                    $("#star1").css("color", "orange");
                    $("#star2").css("color", "#000");
                    $("#star3").css("color", "#000");
                    $("#star4").css("color", "#000");
                    $("#star5").css("color", "#000");
                    final_rating = 1;
                });

                //Click event listioner for favourite button
                $("#heart").click(function(){
                    //if user has already added a product in favourites then remove it from favourites
                    var added = "no";
                    if($("#heart").css("color") != "rgb(0, 0, 0)"){
                       added = "yes";
                    }
                    <?php
                    //Stop not logged in user by adding a product into favourites
                    if(!isset($_SESSION["user"])){?>
                        swal({
                            title: "Sorry!", 
                            text: "Only logged in users can add products to favourites.", 
                            icon: "error",
                            buttons: ["Cancel", "Login"]
                            })
                        .then((login) => {
                            if (login) {
                                window.location = "login.php";
                            }
                        });
                    <?php }else{?>
                        $.ajax({
                            url: "rating.php",
                            type: "POST",
                            data: {act: "favourites", added: added, product_id: <?php echo $prods[0]["product_id"];?>, user_id: <?php echo $_SESSION["user_id"];?>},
                            success: function(data){
                                if(data == 1){
                                    swal("Done", "The product has been added to your favourites.", "success");
                                    $("#heart").css("color", "#E31B23");

                                }else if(data == 0){
                                    swal("Done", "The product has been removed from your favourites.", "success");
                                    $("#heart").css("color", "black");
                                }else{
                                    swal("Error!", "Something went wrong.", "Error");
                                    window.location = "index.php";
                                }
                            }
                        });
                    <?php }?>
                    
                    
                });
                //Click event listioner for cart button
                $("#cartButton").click(function(){
                    <?php
                    //Stop not logged in user by adding product into cart
                    if(!isset($_SESSION["user"])){?>
                        swal({
                            title: "Sorry!", 
                            text: "Only logged in users can add products to cart.", 
                            icon: "error",
                            buttons: ["Cancel", "Login"]
                            })
                        .then((login) => {
                            if (login) {
                                window.location = "login.php";
                            }
                        });
                    <?php }else{?>
                  
                        $.ajax({
                            url: "rating.php",
                            type: "POST",
                            data: {act: "cart", product_id: <?php echo $prods[0]["product_id"];?>, user_id: <?php echo $_SESSION["user_id"];?>},
                            success: function(data){
                                if(data == 1){
                                    showCartCount();
                                    swal({
                                        title: "Done", 
                                        text: "The product has been added to your cart.", 
                                        icon: "success",
                                        buttons: ["Cancel", "Proceed to Cart"]
                                        }).then(okay=>{
                                            if(okay){
                                                window.location.href = 'cart.php';
                                            }
                                        });
                                        
                                }else{
                                    swal("Error!", "Something went wrong.", "Error");
                                    window.location = "index.php";
                                }
                            }
                        });
                    <?php }?>
                    
                    
                });
                //Click event listioner to rate a product
                $("#rating_button").click(function(){
                    <?php
                    //Stop not logged in user by rating product
                    if(!isset($_SESSION["user"])){?>
                        swal("Sorry!", "Only logged in users can give a feedback on products.", "error");
                        $('#myModal').modal('toggle');
                    <?php }else{?>
                        if(final_rating == 0){
                            swal("Sorry!", "Didn't recieve any review!", "error");
                        }else{
                            $('#myModal').modal('toggle');      //close a modal
                            $.ajax({
                                url: "rating.php",
                                type: "POST",
                                data: {act: "rating", rating: final_rating, feedback: $("#feedback").val(), product_id: <?php echo $prods[0]["product_id"];?>, user_id: <?php echo $_SESSION["user_id"];?>},
                                success: function(data){
                                    if(data == 0){
                                        swal("Warning!", "You have already rated this product before", "warning");
                                    }else if(data == 1){
                                        swal("Thank You!", "Your feedback is important to us.", "success");
                                    }else{
                                        swal("Error!", "Something went wrong", "Error");
                                    }
                                }
                            });
                        }
                    <?php }?>                    
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
                                    //Fetching all categories to show on navbar
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
                            //Check if users is loged in or not
                            if(isset($_SESSION["user"])){
                                echo '<li><a href="user_profile.php">'.$_SESSION["user"].'</a></li>';
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
            <div class="row average">
                <?php
                    //Check if product is available
                    if($prods[0]["product_status"] == 0){
                        ?><div class='alert alert-danger' role='alert'><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> Product is not in the stock right now. Sorry for the Inconvenience.</div><?php
                    }
                ?>
                <h1><?php echo $prods[0]["product_title"];?></h1>
                <h4>By <a href="mailto:<?php echo $seller[0]["email"];?>"><?php echo $seller[0]["username"];?></a></h4>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <span id="rating_star1" style="color: black;" class="fa fa-star"></span>
                    <span id="rating_star2" style="color: black;" class="fa fa-star"></span>
                    <span id="rating_star3" style="color: black;" class="fa fa-star"></span>
                    <span id="rating_star4" style="color: black;" class="fa fa-star"></span>
                    <span id="rating_star5" style="color: black;" class="fa fa-star"></span>
                    <span>(<?php echo $userCount;?> ratings)</span>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-2">
                    <span style="color: black;" id="heart" class="fa fa-heart" title="Add to Favourites"></span>
                </div>
                
            </div>
            <!--Images-->
            <div id="myCarousel" class="carousel slide row" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <?php if($prods[0]["product_img2"] != ""){?>
                        <li data-target="#myCarousel" data-slide-to="1"></li>
                    <?php }?>
                    <?php if($prods[0]["product_img3"] != ""){?>
                        <li data-target="#myCarousel" data-slide-to="2"></li>
                    <?php }?>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner col-md-6 col-md-offset-3">
                    <div class="item active">
                        <img src="seller/img/<?php echo $prods[0]['product_img1']?>">
                    </div>
                    <?php if($prods[0]["product_img2"] != ""){?>
                        <div class="item">
                            <img src="seller/img/<?php echo $prods[0]['product_img2']?>">
                        </div>
                        <?php }?>
                    <?php if($prods[0]["product_img3"] != ""){?>
                        <div class="item">
                            <img src="seller/img/<?php echo $prods[0]['product_img3']?>">
                        </div>
                    <?php }?>
                </div>

                <!-- Left and right controls -->
                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
                <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            <!--Product details-->
            <div class="row">
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <h4>Product Price: <strong style="color: #ff6700;">CAD $<?php echo $prods[0]["product_price"];?></strong></h4>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <h4>Brand: <strong style="color: indigo;"><?php echo $brand[0]["brand_title"];?></strong></h4>
                </div>
                <p><?php echo $prods[0]["product_desc"];?></p>
            </div>
            <?php
            //Show the cart button only if the product is available
            if($prods[0]["product_status"] == 1){
                ?><button type="button" id="cartButton" class="btn btn-success btn-lg"><span style="text-align: left;" class="glyphicon glyphicon-shopping-cart"></span> Add to cart</button><br/><?php
            }
            ?>

            <!-- Trigger the modal with a button -->
            <button type="button" id="reviewButton" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Review this product</button>
            <hr/>
            <h3>Products related to this item</h3>
            <hr/>
            <div class="row">
            <?php 
                //products related to this product
                $stop = 0;
                foreach($matches as $match){
                    if($stop < 4){
                        echo "<div class='col-xs-12 col-sm-6 col-md-3'><a href='product.php?product_id=".$match["product_id"]."'><figure><img class='img-responsive center-block' src='seller/img/".$match["product_img1"]."'/><figcaption>".$match["product_title"]."</figcaption></figure></a></div>";
                        $stop++;
                    }
                }
            ?>
            </div>
            
            <hr/>
            <!--Reviews-->
            <h1 style="text-align:center;">Reviews</h1>
            <div class="row">
                <hr class="hr"/>
                <?php
                    if(sizeof($reviews) == 0){
                        echo"<p>The product hasen't been reviewed by anyone yet.</p>";
                    }else{
                        foreach($reviews as $review){     
                            ?>
                            <div class="col-md-1 col-sm-1 col-xs-12"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></div>
                            <div class="col-md-11 col-sm-11 col-xs-12">
                                <?php        
                                $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
                                $stmt->execute([$review['user_id']]);
                                $users = $stmt->fetchAll();
                                echo"<p style='color: indigo;'>".$users[0]["fullname"]."</p>";
                                if($review["ratings"] == 1){
                                    echo "<p><span id='rating_star1' style='color: orange;' class='fa fa-star'></span><span id='rating_star2' style='color: black;' class='fa fa-star'></span><span id='rating_star3' style='color: black;' class='fa fa-star'></span><span id='rating_star4' style='color: black;' class='fa fa-star'></span><span id='rating_star5' style='color: black;' class='fa fa-star'></span></p>";
                                }else if($review["ratings"] == 2){
                                    echo "<p><span id='rating_star1' style='color: orange;' class='fa fa-star'></span><span id='rating_star2' style='color: orange;' class='fa fa-star'></span><span id='rating_star3' style='color: black;' class='fa fa-star'></span><span id='rating_star4' style='color: black;' class='fa fa-star'></span><span id='rating_star5' style='color: black;' class='fa fa-star'></span></p>";
                                }else if($review["ratings"] == 3){
                                    echo "<p><span id='rating_star1' style='color: orange;' class='fa fa-star'></span><span id='rating_star2' style='color: orange;' class='fa fa-star'></span><span id='rating_star3' style='color: orange;' class='fa fa-star'></span><span id='rating_star4' style='color: black;' class='fa fa-star'></span><span id='rating_star5' style='color: black;' class='fa fa-star'></span></p>";
                                }else if($review["ratings"] == 4){
                                    echo "<p><span id='rating_star1' style='color: orange;' class='fa fa-star'></span><span id='rating_star2' style='color: orange;' class='fa fa-star'></span><span id='rating_star3' style='color: orange;' class='fa fa-star'></span><span id='rating_star4' style='color: orange;' class='fa fa-star'></span><span id='rating_star5' style='color: black;' class='fa fa-star'></span></p>";
                                }else if($review["ratings"] == 5){
                                    echo "<p><span id='rating_star1' style='color: orange;' class='fa fa-star'></span><span id='rating_star2' style='color: orange;' class='fa fa-star'></span><span id='rating_star3' style='color: orange;' class='fa fa-star'></span><span id='rating_star4' style='color: orange;' class='fa fa-star'></span><span id='rating_star5' style='color: orange;' class='fa fa-star'></span></p>";
                                } 
                                echo"<p>".$review["feedback"]."</p>";
                                ?>
                            </div>
                            <hr class="hr"/>
                            <?php
                            
                        }

                    }
                    ?>
            </div>
            <!--Share products buttons-->
            <h1 style="text-align:center;">Share this product</h1>
            <div class="middle">
                <!--Facebook-->
                <a target="_blank" class="btn facebook" href="https://www.facebook.com/sharer/sharer.php?u=https%3A//csunix.mohawkcollege.ca/~000742712/projects/Capstone/product.php?product_id=<?php echo $_REQUEST["product_id"];?>">
                    <i class="fa fa-facebook-f"></i>
                </a>
                <!--Twitter-->
                <a target="_blank" class="btn twitter" href="https://twitter.com/intent/tweet?text=https%3A//csunix.mohawkcollege.ca/~000742712/projects/Capstone/product.php?product_id=<?php echo $_REQUEST["product_id"];?>">
                    <i class="fa fa-twitter"></i>
                </a>
            </div>
            <hr/>
            <!-- Modal -->
            <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Review this product</h4>
                        </div>
                        <div class="modal-body">
                            <span id="star1" class="fa fa-star"></span>
                            <span id="star2" class="fa fa-star"></span>
                            <span id="star3" class="fa fa-star"></span>
                            <span id="star4" class="fa fa-star"></span>
                            <span id="star5" class="fa fa-star"></span>
                            <h4><small>Share your experience by giving feedback on this product</small></h4>
                            <textarea id="feedback" name="feedback"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button id="rating_button" type="button" class="btn btn-success">Submit Review</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                
                </div>
            </div>
        </div>
        <!--Searched items will be appeared at here-->
        <div class="container-fluid" id="search_div">
            <div class="row equal" id="searchedItem">

            </div>
        </div>
        <!--Cart Button at bottom right corner-->
        <?php if(isset($_SESSION["user_id"])){?> 
            <div id="cartDiv">
                    <a href="cart.php"><button id="cartCountButton" class="btn btn-success"><span class='glyphicon glyphicon-shopping-cart'></span> <span id="cartCountSpan">0</span> Items</button></a>
            </div>
        <?php }?>  
        
    </body>
</html>