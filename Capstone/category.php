<?php
    //Category page to show individual items in category
    include 'database.php';
    session_start();        //Starting a session
    //fetching all the categories
    $stmt = $conn->prepare("SELECT * FROM Categories WHERE cat_id = ?");
    $stmt->execute([$_REQUEST["cat_id"]]);
    $cats = $stmt->fetchAll();
    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Technoholic - <?php echo $cats[0]["cat_title"]?></title>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Bootstrap v3.4.1 CDN links -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <!-- Font Awesome Icon Library -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!--CSS-->
        <link rel="stylesheet" href="css/style.css">
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
                        <li class="dropdown active">
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
        <div class="fluid-container" id="main">
            <?php
                $stmt = $conn->prepare("SELECT * FROM Categories WHERE cat_id = ?");
                $stmt->execute([$_REQUEST["cat_id"]]);
                $cats = $stmt->fetchAll();
            ?>
            <div class="row">
                <?php
                //Fetch all the products matching with category, and show them on page
                $stmt = $conn->prepare("SELECT * FROM products where cat_id = ?");
                $stmt->execute([$cats[0]["cat_id"]]);
                $products = $stmt->fetchAll();

                foreach($products as $prods){
                    //Fetch all reviews of the product to show their average (in star)
                    $stmt = $conn->prepare("SELECT * FROM reviews where product_id = ?");
                    $stmt->execute([$prods["product_id"]]);
                    $reviews = $stmt->fetchAll();

                    if($prods["ratings"] == 0){
                        echo "<div class='col-xs-12 col-sm-3 col-md-3'><a href='product.php?product_id=".$prods["product_id"]."'><figure><img class='img-responsive center-block' src='seller/img/".$prods["product_img1"]."'/><figcaption>".$prods["product_title"]."<p><span id='rating_star1' style='color: black;' class='fa fa-star'></span><span id='rating_star2' style='color: black;' class='fa fa-star'></span><span id='rating_star3' style='color: black;' class='fa fa-star'></span><span id='rating_star4' style='color: black;' class='fa fa-star'></span><span id='rating_star5' style='color: black;' class='fa fa-star'></span> (".count($reviews).")</p></figcaption></figure></a></div>";
                    }

                    if($prods["ratings"] == 1){
                        echo "<div  class='col-xs-12 col-sm-3 col-md-3'><a href='product.php?product_id=".$prods["product_id"]."'><figure><img class='img-responsive center-block' src='seller/img/".$prods["product_img1"]."'/><figcaption>".$prods["product_title"]."<p><span id='rating_star1' style='color: orange;' class='fa fa-star'></span><span id='rating_star2' style='color: black;' class='fa fa-star'></span><span id='rating_star3' style='color: black;' class='fa fa-star'></span><span id='rating_star4' style='color: black;' class='fa fa-star'></span><span id='rating_star5' style='color: black;' class='fa fa-star'></span> (".count($reviews).")</p></figcaption></figure></a></div>";
                    }

                    if($prods["ratings"] == 2){
                        echo "<div  class='col-xs-12 col-sm-3 col-md-3'><a href='product.php?product_id=".$prods["product_id"]."'><figure><img class='img-responsive center-block' src='seller/img/".$prods["product_img1"]."'/><figcaption>".$prods["product_title"]."<p><span id='rating_star1' style='color: orange;' class='fa fa-star'></span><span id='rating_star2' style='color: orange;' class='fa fa-star'></span><span id='rating_star3' style='color: black;' class='fa fa-star'></span><span id='rating_star4' style='color: black;' class='fa fa-star'></span><span id='rating_star5' style='color: black;' class='fa fa-star'></span> (".count($reviews).")</p></figcaption></figure></a></div>";
                    }

                    if($prods["ratings"] == 3){
                        echo "<div  class='col-xs-12 col-sm-3 col-md-3'><a href='product.php?product_id=".$prods["product_id"]."'><figure><img class='img-responsive center-block' src='seller/img/".$prods["product_img1"]."'/><figcaption>".$prods["product_title"]."<p><span id='rating_star1' style='color: orange;' class='fa fa-star'></span><span id='rating_star2' style='color: orange;' class='fa fa-star'></span><span id='rating_star3' style='color: orange;' class='fa fa-star'></span><span id='rating_star4' style='color: black;' class='fa fa-star'></span><span id='rating_star5' style='color: black;' class='fa fa-star'></span> (".count($reviews).")</p></figcaption></figure></a></div>";
                    }

                    if($prods["ratings"] == 4){
                        echo "<div class='col-xs-12 col-sm-3 col-md-3'><a href='product.php?product_id=".$prods["product_id"]."'><figure><img class='img-responsive center-block' src='seller/img/".$prods["product_img1"]."'/><figcaption>".$prods["product_title"]."<p><span id='rating_star1' style='color: orange;' class='fa fa-star'></span><span id='rating_star2' style='color: orange;' class='fa fa-star'></span><span id='rating_star3' style='color: orange;' class='fa fa-star'></span><span id='rating_star4' style='color: orange;' class='fa fa-star'></span><span id='rating_star5' style='color: black;' class='fa fa-star'></span> (".count($reviews).")</p></figcaption></figure></a></div>";
                    }

                    if($prods["ratings"] == 5){
                        echo "<div class='col-xs-12 col-sm-3 col-md-3'><a href='product.php?product_id=".$prods["product_id"]."'><figure><img class='img-responsive center-block' src='seller/img/".$prods["product_img1"]."'/><figcaption>".$prods["product_title"]."<p><span id='rating_star1' style='color: orange;' class='fa fa-star'></span><span id='rating_star2' style='color: orange;' class='fa fa-star'></span><span id='rating_star3' style='color: orange;' class='fa fa-star'></span><span id='rating_star4' style='color: orange;' class='fa fa-star'></span><span id='rating_star5' style='color: orange;' class='fa fa-star'></span> (".count($reviews).")</p></figcaption></figure></a></div>";
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