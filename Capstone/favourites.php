<?php
    //Favorite Page
    include 'database.php';
    session_start();        //Starting a session

    //redirect user to login page if not logged in
    if(!isset($_SESSION["user_id"])){
        header('Location: login.php');
    }
    //Fetch all items from favourites
    $stmt = $conn->prepare("SELECT * FROM favourites WHERE user_id=?");
    $stmt->execute([$_SESSION["user_id"]]);
    $items = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Technoholic</title>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <!-- Bootstrap v3.4.1 CDN links -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <!-- Sweet Alert Library -->
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <!-- Font Awesome Icon Library -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!--CSS-->
        <link rel="stylesheet" href="css/style.css">
        <style>
           
            .btn{
                width: 100%;
                border-radius: 5px;
                margin: 10px 0;
            }
            
        </style>
        <script>
            $(document).ready(function(){
                 //Show number of items in cart if user is logged in
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
                //Add a product to shopping cart
                $(".cartButton").click(function(){
                    $.ajax({
                        url: "rating.php",
                        type: "POST",
                        data: {act: "cart", product_id: this.id, user_id: <?php echo $_SESSION["user_id"];?>},
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
                });
                //Remove a product from favourites
                $(".removeButton").click(function(){
                    swal({
                        title: "Are you sure?", 
                        text: "Do you want to remove this item from favourites?", 
                        buttons: ["Cancel", "Remove"]
                        }).then(okay=>{
                            if(okay){
                                $.ajax({
                                    url: "rating.php",
                                    type: "POST",
                                    data: {act: "favourites", added: "yes", product_id: this.id, user_id: <?php echo $_SESSION["user_id"];?>},
                                    success: function(data){
                                        window.location.href = 'favourites.php';
                                    }
                                });
                                
                            }
                        });
                                       
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
                        <li class="active"><a href="favourites.php">Favourites</a></li>
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
            <h1 style="text-align: center;"><span style="color: #E31B23;" id="heart" class="fa fa-heart" title="Add to Favourites"></span> Favourites</h1>
            <hr class="hr"/>
            <div  id="favourites" >
            <?php
            //Check if favourite list is empty
            if(sizeof($items) == 0){
                echo "You don't have anything in your favourite list.";
            }else{
                //Show all products from from favourites 
                foreach($items as $item){
                    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id=?");
                    $stmt->execute([$item["product_id"]]);
                    $product = $stmt->fetchAll();

                    echo '<div class="row">';
                    echo "<div class='col-md-4 col-sm-4 col-xs-12'><a href='product.php?product_id=".$product[0]["product_id"]."'><img class='img-responsive' src='seller/img/".$product[0]["product_img1"]."'/></a></div>";
                    if($product[0]["product_status"] == 1){
                        echo "<div class='col-md-8 col-sm-8 col-xs-12'><div style='color: indigo;'><a href='product.php?product_id=".$product[0]["product_id"]."'>".$product[0]["product_title"]."</a></div><br/><div style='color: #ff6700;'><strong>CAD $".$product[0]["product_price"]."</strong></div><div><button id=".$product[0]["product_id"]." type='button' class='btn btn-success cartButton'><span style='text-align: left;' class='glyphicon glyphicon-shopping-cart'></span> Add to Cart</button><button id=".$product[0]["product_id"]." type='button' class='btn btn-danger removeButton'><span style='text-align: left;' class='glyphicon glyphicon-trash'></span> Remove from Favourites</button></div></div>";
                    }else{
                        echo "<div class='col-md-8 col-sm-8 col-xs-12'><div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>  Product is not in the stock right now. Sorry for the Inconvenience.</div><div style='color: indigo;'><a href='product.php?product_id=".$product[0]["product_id"]."'>".$product[0]["product_title"]."</a></div><br/><div style='color: #ff6700;'><strong>CAD $".$product[0]["product_price"]."</strong></div><div><button id=".$product[0]["product_id"]." type='button' class='btn btn-danger removeButton'><span style='text-align: left;' class='glyphicon glyphicon-trash'></span> Remove from Favourites</button></div></div>";
                    }
                    
                    echo '</div><hr class="hr"/>';
                }
            }
                
            ?>
            </div>
                
        </div>
        <!--Searched items will be appeared at here-->
        <div class="fluid-container" id="search_div">
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
