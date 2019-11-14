<?php
    //Checkout Page
    include 'database.php';
    session_start();        //Starting a session

    //redirect user to login page if not logged in
    if(!isset($_SESSION["user_id"])){
        header('Location: login.php');
    }
    //Fetch all items from cart
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id=?");
    $stmt->execute([$_SESSION["user_id"]]);
    $items = $stmt->fetchAll();

    //Stop user accessing a page directly
    if(sizeof($items) == 0){
        unset($_SESSION["checkedOut"]);
        header('Location: index.php');
        
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
                margin: 20px 1%;
            }
            
        </style>
        <script>
            $(document).ready(function(){
                //calculate the final total
                $.ajax({
                    url: "rating.php",
                    type: "POST",
                    data: {act: "summary", user_id: <?php echo $_SESSION["user_id"];?>},
                    success: function(data){
                        var taxes = (data * 13) / 100;
                        data = Math.round(data * 100) / 100;
                        taxes = Math.round(taxes * 100) / 100;
                        var total = data + taxes;
                        total = Math.round(total * 100) / 100;
                        $("#mainPrice").html(data);
                        $("#taxes").html(taxes);
                        $("#total").html(total);
                    }
                });
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
                //Finish purchase and redirect user to home page
                $("#checkoutButton").click(function(){
                    $.ajax({
                        url: "rating.php",
                        type: "POST",
                        data: {act: "checkout", user_id: <?php echo $_SESSION["user_id"];?>},
                        success: function(data){
                                window.location.href = 'index.php';
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
            <div class="row">
                <div class="col-sm-9 col-md-9 col-xs-12" id="details">
                    <h3>Review your order</h3>
                    <hr/>
                    <?php
                        //Show products from cart in review section
                        foreach($items as $item){
                            $stmt = $conn->prepare("SELECT * FROM products WHERE product_id=?");
                            $stmt->execute([$item["product_id"]]);
                            $product = $stmt->fetchAll();

                            echo '<div class="row">';
                            echo "<div class='col-md-4 col-sm-4 col-xs-12'><a href='product.php?product_id=".$product[0]["product_id"]."'><img class='img-responsive' src='seller/img/".$product[0]["product_img1"]."'/></a></div>";
                            echo "<div class='col-md-8 col-sm-8 col-xs-12'><div><a href='product.php?product_id=".$product[0]["product_id"]."'>".$product[0]["product_title"]."</a></div><br/><div class='row'  style='margin-right: 1%;'><div class='col-md-6 col-sm-6 col-xs-6' style='color: #ff6700;'><strong>CAD $".$product[0]["product_price"]."</strong></div><div class='col-md-6 col-sm-6 col-xs-6 input-group''><div class='input-group-addon'>Quantity</div><input type='number' value='".$item["product_count"]."' class='form-control productQuantity' min='1' step='1' style='text-align: center;' id='".$product[0]["product_id"]."' disabled></div></div></div>";
                            echo '</div><hr/>';
                        }
                    ?>
                </div>
                <div class="col-sm-3 col-md-3 col-xs-12">
                <!--Order Summary-->
                    <h3>Summary</h3>
                    <hr/>
                    <div class="row" id="summary">
                        <div class="col-sm-8 col-md-8 col-xs-8">Items:</div>
                        <div class="col-sm-4 col-md-4 col-xs-4">$ <span id="mainPrice"></span></div>
                        
                        <div class="col-sm-8 col-md-8 col-xs-8">Taxes:</div>
                        <div class="col-sm-4 col-md-4 col-xs-4">$ <span id="taxes"></span></div>
                     
                        <div class="col-sm-8 col-md-8 col-xs-8"><strong>Order Total:</strong></div>
                        <div class="col-sm-4 col-md-4 col-xs-4"><strong>$ <span id="total"></span></strong></div>

                        <button type="button" class="btn btn-success" id="checkoutButton">Checkout</button>
                        
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
