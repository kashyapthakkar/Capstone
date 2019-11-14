<?php 
    //Homepage for seller
    date_default_timezone_set("Canada/Eastern");
    session_start();                                //Starting a session
    //Redirect to seller login page if someone tries to access dorectly
    if(!isset($_SESSION["sellerid"])){
        header('Location: sellers_login.php');
    }
    
    include '../database.php';
    //if product form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        /*
            Add product information in database with images
        */
        $seller_id = $_SESSION["sellerid"];
        $product_title = $_POST['title'];
        $product_category = $_POST['category'];
        $product_brand = $_POST['brand'];
        $product_price = $_POST['price'];
        $product_desc = $_POST['detail'];
        $product_keywords = $_POST['keywords'];

        //Make a uniq name for images 
        $img1_name = $_FILES['img1']['name'];
        $img1_name = preg_replace('/\s+/','',$img1_name);
        $img1_name = $seller_id . $img1_name;

        $img2_name = $_FILES['img2']['name'];
        $img2_name = preg_replace('/\s+/','',$img2_name);
        $img2_name = $seller_id . $img2_name;

        $img3_name = $_FILES['img3']['name'];
        $img3_name = preg_replace('/\s+/','',$img3_name);
        $img3_name = $seller_id . $img3_name;

        //Decide a target to add the images
        $target1 = "img/" . basename($img1_name);
        $target2 = "img/" . basename($img2_name);
        $target3 = "img/" . basename($img3_name);

        //Upload the images
        move_uploaded_file($_FILES['img3']['tmp_name'],$target3 );
        $status = true;

        $today = date('Y-m-d G:i:s');

        if(move_uploaded_file($_FILES['img1']['tmp_name'],$target1 )){
            
            if($_FILES['img2']['name'] != ""){
                move_uploaded_file($_FILES['img2']['tmp_name'],$target2 );
            }else{
                $img2_name = "";
            }

            if($_FILES['img3']['name'] != ""){
                move_uploaded_file($_FILES['img3']['tmp_name'],$target3 );
            }else{
                $img3_name = "";
            }

            $stmt = $conn->prepare("INSERT INTO products " .
                                    "(cat_id, seller_id, brand_id, date, product_title, product_img1, product_img2, product_img3, product_price, product_desc, product_status, product_keywords, ratings) VALUES " .
                                    "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$product_category, $seller_id, $product_brand, $today, $product_title, $img1_name, $img2_name, $img3_name, $product_price, $product_desc, $status, $product_keywords, 0]);

        }else{
            echo "<script>alert('Please insert at least 1 image to place your product in the market!')</script>";
            
        }
        

   
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Technoholic | Sellers</title>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="editor/jquery-te-1.4.0.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <script src="editor/jquery-te-1.4.0.min.js"></script>
        <style>
            body { 
                padding-top: 40px; 
            }
            
            #nav {
            background-color: indigo;
            }

            #product_form{
                background-color: #aaa1c8;
            }

           

        </style>
        <script>
            $(document).ready(function(){
                $(".editor").jqte();
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
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Home <span class="sr-only">(current)</span></a></li>
                    <li><a href="sellers_products.php">My Products</a></li>
                    
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li><a href="sellers_products.php"><span class="glyphicon glyphicon-user"></span><?php echo " ".$_SESSION['seller']?></a></li>             
                    <li><a href="seller_logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                </ul>

                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <div class="container">
            <h1 class="text-center">Add a product</h1>
            <form id="product_form" class="well form-horizontal" method="post" enctype="multipart/form-data" action="seller.php">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="title">Product Title:</label>
                    <div class="col-sm-10">
                        <input type="text" name="title" class="form-control" id="title" minlength="2" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="category">Product Category:</label>
                    <div class="col-sm-10">
                    <select class="form-control" id="category" name="category">
                    <?php
                            $stmt = $conn->prepare("SELECT * FROM Categories");
                            $stmt->execute();
                            $cats = $stmt->fetchAll();
                            

                            foreach($cats as $row){
                                echo "<option value=".$row['cat_id'].">".$row['cat_title']."</option>";                   
                            }?>
                       
                    </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="brand">Product Brand:</label>
                    <div class="col-sm-10">
                    <select class="form-control" id="brand" name="brand">
                    <?php
                            $stmt = $conn->prepare("SELECT * FROM brands");
                            $stmt->execute();
                            $cats = $stmt->fetchAll();
                            

                            foreach($cats as $row){
                                echo "<option value=".$row['brand_id'].">".$row['brand_title']."</option>";                   
                            }?>
                       
                    </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="img1">Select Image-1:</label>
                    <div class="col-sm-10">
                        <input type="file" name="img1" id="img1" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="img2">Select Image-2:</label>
                    <div class="col-sm-10">
                        <input type="file" name="img2" id="img2">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="img3">Select Image-3:</label>
                    <div class="col-sm-10">
                        <input type="file" name="img3" id="img3">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="price">Product price:</label>
                    <div class="col-sm-10">
                        <input type="number" step="0.01" name="price" id="price" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="detail">Product description:</label>
                    <div class="col-sm-10">
                        <textarea class="editor"  name="detail" id="detail" cols="10" rows="10" required="required"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="keywords">Product keywords:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="keywords" id="keywords">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Submit</button>
                    </div>
                </div>
            </form>
        </div> 
    </body>
</html>

    