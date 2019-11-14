<?php
    //Profile page for sellers
    session_start();
        //Reditect to login page if not logged in
        if(!isset($_SESSION["sellerid"])){
            header('Location: sellers_login.php');
        }
        
        include '../database.php';
        //To delete a product
        if(isset($_REQUEST["delete"])){
            
            $stmt = $conn->prepare("SELECT * FROM products Where product_id=?");
            $stmt->execute([$_REQUEST["id"]]);
            $products = $stmt->fetchAll();
            
            $file_path1 = "img/".$products[0]["product_img1"];
            $file_path2 = "img/".$products[0]["product_img2"];
            $file_path3 = "img/".$products[0]["product_img3"];

            unlink($file_path1);

            if($products[0]["product_img2"] != ""){
                unlink($file_path2);
            }
            
            if($products[0]["product_img3"] != ""){
                unlink($file_path3);
            }
            
            $stmt= "Delete from products where product_id = ?";
            $result = $conn->prepare($stmt);
            $result->execute([$_REQUEST["id"]]);

            echo "<div class='alert alert-success' role='alert'>Product removed successfully.</div>";

        }else{
            if(isset($_REQUEST["act"])){
                if($_REQUEST["act"] == "delete"){
                    echo "<div class='alert alert-danger' role='alert'>Please <a href='sellers_products.php?delete=1&id=".$_REQUEST["id"]."'>click here</a> to delete selected item.</div>";
                }
            }
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                //Updates a product
                $stmt= "Update products SET product_title=?, product_price=?, product_desc=?, product_status=? WHERE product_id=?";
                $result = $conn->prepare($stmt);
                $result->execute([$_POST["product_title"], $_POST["product_price"], $_POST["detail"], $_POST["status"], $_POST["product_id"]]);
                echo "<div class='alert alert-success' role='alert'>Product updated successfully.</div>";
            }
        }

        
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Technoholic | Sellers - Products</title>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Bootstrap v3.4.1 CDN links -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="editor/jquery-te-1.4.0.css"/>
        <script src="editor/jquery-te-1.4.0.min.js"></script>
        
        <style>
            body { 
                padding-top: 70px; 
            }
            
            #nav {
            background-color: indigo;
            }

            table img{
                height: 125px;
                width: 125px;
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
                    <li><a href="seller.php">Home <span class="sr-only">(current)</span></a></li>
                    <li class="active"><a href="sellers_products.php">My Products</a></li>
                    
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li><a href="sellers_products.php"><span class="glyphicon glyphicon-user"></span><?php echo " ".$_SESSION['seller']?></a></li>             
                    <li><a href="seller_logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                </ul>

                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <div class="container">
          
        <?php
        
        if(isset($_REQUEST["act"])){
            if($_REQUEST["act"] == "edit"){
                $stmt = $conn->prepare("SELECT * FROM products WHERE product_id=?");
                $stmt->execute([$_REQUEST["id"]]);
                $products = $stmt->fetchAll();
                ?>
                <form class="well form-horizontal" id="product_form" method="post" enctype="multipart/form-data" action="sellers_products.php">
                    <input type="hidden" name="product_id" value="<?php echo $_REQUEST['id']?>" />
                    <div class="form-group">
                        <label for="productName" class="col-sm-2 control-label">Product Name</label>
                        <div class="col-sm-10">
                        <input type="text" value="<?php echo $products[0]['product_title'];?>" class="form-control" name="product_title" id="productName">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="productPrice" class="col-sm-2 control-label">Product Price</label>
                        <div class="col-sm-10">
                        <input type="number" value="<?php echo $products[0]['product_price'];?>" class="form-control" name="product_price" id="productPrice">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="detail">Product description:</label>
                        <div class="col-sm-10">
                            <textarea class="editor" name="detail" id="detail" cols="10" rows="10" required="required"><?php echo $products[0]['product_desc'];?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="status">Product Status:</label>
                        <div class="col-sm-10">
                           <select name="status" class="form-control">
                            <option value=1>Available</option>
                            <option value=0>Unvailable</option>
                           </select>
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a class="btn btn-danger" href="sellers_products.php">Cancle</a>
                        </div>
                    </div>
                </form>  
            <?php }
            }?>    
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Photos</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    
                <?php
                        $stmt = $conn->prepare("SELECT * FROM products WHERE seller_id=?");
                        $stmt->execute([$_SESSION["sellerid"]]);
                        $products = $stmt->fetchAll();
                        

                        foreach($products as $row){
                            $image1_path = $row["product_img1"];
                            $status = "Available";
                            if($row["product_status"] == 0){
                                $status = "Not Unavailable";
                            }
                            echo "<tr>"
                                ."<td style='width: 45% ;'>".$row["product_title"]."</td>"
                                ."<td><img class='img-responsive' src='img/".$image1_path."'/></td>"
                                ."<td>$".$row["product_price"]."</td>"
                                ."<td>".$status."</td>"
                                ."<td><a href='sellers_products.php?act=edit&id=".$row["product_id"]."'><span class='glyphicon glyphicon-edit'></span></a></td>"
                                ."<td><a href='sellers_products.php?act=delete&id=".$row["product_id"]."'><span class='glyphicon glyphicon-trash'></span></a></td>"
                                ."</tr>";
                        }
                    ?>  
                </tbody>
            </table>     
        </div> 
    </body>
</html>

            