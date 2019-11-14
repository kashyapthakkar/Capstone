<?php
//A controller file to handle all the ajax requests

//redirect to home page if someone tries to access it directly
if(isset($_POST["act"])){
    session_start();    //starting a session
    include 'database.php';
    if($_POST["act"] == "rating"){
        /*
        Check if user has already rated the product, and then add a rating according to that (One rating per one user).
        */
        $rating = $_POST["rating"];
        $feedback = $_POST["feedback"];
        $user_id = $_POST["user_id"];
        $product_id = $_POST["product_id"];
        
        $stmt = $conn->prepare("SELECT * FROM reviews WHERE product_id = ?");
        $stmt->execute([$product_id]);
        $reviews = $stmt->fetchAll();
        
        $product_rating = 0;
        $flag = true;
        $userCount = 0;
        foreach($reviews as $row){
            if($row["user_id"] == $user_id){
                $flag = false;
                echo 0;                                                           //Send 0 if user has already reviewed this product
            }
            $product_rating += $row["ratings"]; 
            $userCount++;
        }

        if($flag){
            $product_rating += $rating;
            $userCount++;

            $updated_rating = round($product_rating/$userCount, 0);
            
            $stmt = $conn->prepare("UPDATE products SET ratings = ? WHERE product_id = ?");
            $stmt->execute([$updated_rating, $product_id]);

            $stmt = $conn->prepare("INSERT INTO reviews (user_id, product_id, ratings, feedback) VALUES (?,?,?,?);");
            $stmt->execute([$user_id, $product_id, $rating, $feedback]);
            echo 1;             //Send 1 if reviews are inserted
        }
    }else if($_POST["act"] == "favourites"){
        $user_id = $_POST["user_id"];
        $product_id = $_POST["product_id"];
        if($_POST["added"] == "yes"){
            $stmt = $conn->prepare("DELETE FROM favourites WHERE user_id = ? AND product_id = ?;");
            $stmt->execute([$user_id, $product_id]);
            echo 0;
        }else{
            $stmt = $conn->prepare("INSERT INTO favourites (user_id, product_id) VALUES (?,?);");
            $stmt->execute([$user_id, $product_id]);
            echo 1;                                                         //Send 1 to verify that rating has added in database
        }
            
        
    }else if($_POST["act"] == "cart"){
        /*
        Add product to cart, and if the product is already in the databse then invrease a quantity count for product
        */
        $user_id = $_POST["user_id"];
        $product_id = $_POST["product_id"];

        $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? and product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $item = $stmt->fetchAll();

        if(sizeof($item) == 0){
            $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, product_count) VALUES (?,?,?);");
            $stmt->execute([$user_id, $product_id, 1]);
        }else{
            $itemCount = $item[0]["product_count"] + 1;
            $stmt = $conn->prepare("UPDATE cart SET product_count = ? WHERE user_id=? AND product_id = ?");
            $stmt->execute([$itemCount, $user_id, $product_id]);
        }
        echo 1;                                                         //Send 1 to verify that request completed successfully
        
    }else if($_POST["act"] == "cartCount"){
        /*
        Check hoe many items currently in the cart and then return the count
        */
        $user_id = $_POST["user_id"];

        $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $item = $stmt->fetchAll();
        $count = 0;
        foreach($item as $row){
            $count += $row["product_count"];
        }

        echo $count;                                            //Send number of items in shoping cart
        
    }else if($_POST["act"] == "removeCartItem"){
        /*
            Remove product from cart
        */
        $user_id = $_POST["user_id"];
        $product_id = $_POST["product_id"];

        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?;");
        $stmt->execute([$user_id, $product_id]);

        echo 1;                                                 //Send 1 to verify that request completed successfully
        
    }else if($_POST["act"] == "quantityChange"){
        /*
            Updates the quantity of item in cart
        */
        $user_id = $_POST["user_id"];
        $product_id = $_POST["product_id"];
        $quantity = $_POST["quantity"];

        if(is_numeric($quantity) && $quantity != 0){
            $stmt = $conn->prepare("UPDATE cart SET product_count=? WHERE user_id = ? AND product_id = ?;");
            $stmt->execute([$quantity, $user_id, $product_id]);
            echo 1;                                       //Send 1 to verify that request completed successfully
        }else{
            echo 0;                                        //Send 0 to warn user that entered data is not numeric
        }
        
        
    }else if($_POST["act"] == "summary"){
        /*
            Calculate the total of all the products in the cart to show on checkout page
        */
        $user_id = $_POST["user_id"];
        $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $item = $stmt->fetchAll();

        $basePrice = 0;

        foreach($item as $row){
            $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
            $stmt->execute([$row["product_id"]]);
            $product = $stmt->fetchAll();
            $basePrice += ($row["product_count"] * $product[0]["product_price"]);
        }

        echo $basePrice;                            //Send the total of all items
    }else if($_POST["act"] == "checkout"){
        /*
            Add products in the history for user, and remove those products from the cart
        */
        $user_id = $_POST["user_id"];
        
        $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $item = $stmt->fetchAll();

            

        foreach($item as $row){
            $stmt = $conn->prepare("INSERT INTO history (user_id, product_id, date) VALUES (?,?,?);");
            $stmt->execute([$user_id, $row["product_id"], date("Y-m-d")]);
        }

        $stmt = $conn->prepare("DELETE from cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $_SESSION["checkedOut"] = true;                                         //To show a message on home page that user has checked out successfully
        echo 1;                                                                 //Send 1 to verify that request completed successfully
    }else if($_POST["act"] == "updateProfile"){
        /*
            Updates the user information
        */
        $user_id = $_POST["user_id"];
        $fullname = $_POST["fullname"];
        $username = $_POST["username"];
      
        $stmt = $conn->prepare("UPDATE users SET fullname=?, username=? WHERE user_id = ?");
        $stmt->execute([$fullname, $username, $user_id]);
        echo 1;                                                                 //Send 1 to verify that request completed successfully

    }else if($_POST["act"] == "changePassword"){
        /*
            Change the password of user
        */
        $user_id = $_POST["user_id"];
        $current = md5($_POST["current"]);
        $new = md5($_POST["new"]);                                          //Encrypt the user password
      
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetchAll();

        if($user[0]["password"] != $current){
            echo 0;                                                         //send 0 if current password is entered wrong
        }else if(strlen($_POST["new"]) < 8 || strlen($_POST["new"]) > 20){
            echo 1;                                                         //send 1 password length os not in range
        }else{
            $stmt = $conn->prepare("UPDATE users SET password=? WHERE user_id = ?");
            $stmt->execute([$new, $user_id]);
            echo 2;                                                         //Send 2 to verify that request completed successfully
        }

    }else if($_POST["act"] == "deleteUser"){
        /*
            Deletes the user
        */
        $email = $_POST["email"];
        //Get the user id
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetchAll();
        //Delete user from user table
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?;");
        $stmt->execute([$user[0]["user_id"]]);
        //Delete user's reviews 
        $stmt = $conn->prepare("DELETE FROM reviews WHERE user_id = ?;");
        $stmt->execute([$user[0]["user_id"]]);
        //Delete user's items from cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?;");
        $stmt->execute([$user[0]["user_id"]]);
        //Delete user's favourites'
        $stmt = $conn->prepare("DELETE FROM favourites WHERE user_id = ?;");
        $stmt->execute([$user[0]["user_id"]]);
        //Delete ite,=ms from history
        $stmt = $conn->prepare("DELETE FROM history WHERE user_id = ?;");
        $stmt->execute([$user[0]["user_id"]]);
        

        echo 1;                                                               //Send 2 to verify that request completed successfully

    }else if($_POST["act"] == "deleteSeller"){
        /*
            Deletes the user
        */
        $email = $_POST["email"];
        //Get the seller id
        $stmt = $conn->prepare("SELECT * FROM sellers WHERE email = ?");
        $stmt->execute([$email]);
        $seller = $stmt->fetchAll();
        //Delete products added by seller
        $stmt = $conn->prepare("SELECT * FROM products WHERE seller_id = ?");
        $stmt->execute([$seller[0]["seller_id"]]);
        $products = $stmt->fetchAll();
        
        //delete products from history, cart, favourites, and reviews

        foreach($products as $row){
            $stmt = $conn->prepare("DELETE FROM reviews WHERE product_id = ?;");
            $stmt->execute([$row["product_id"]]);

            $stmt = $conn->prepare("DELETE FROM cart WHERE product_id = ?;");
            $stmt->execute([$row["product_id"]]);

            $stmt = $conn->prepare("DELETE FROM favourites WHERE product_id = ?;");
            $stmt->execute([$row["product_id"]]);

            $stmt = $conn->prepare("DELETE FROM history WHERE product_id = ?;");
            $stmt->execute([$row["product_id"]]);
        }

        $stmt = $conn->prepare("DELETE FROM sellers WHERE seller_id = ?;");
        $stmt->execute([$seller[0]["seller_id"]]);

        $stmt = $conn->prepare("DELETE FROM products WHERE seller_id = ?;");
        $stmt->execute([$seller[0]["seller_id"]]);


        echo 1;                                                              //Send 2 to verify that request completed successfully

    }else if($_POST["act"] == "addUser"){
        //Add a User by verifying all the fields
        $email = $_POST["email"];
        $username = $_POST["username"];
        $fullname = $_POST["fullname"];
        $password = md5($_POST["password"]);
        $question1 = $_POST["que1"];
        $question2 = $_POST["que2"];
        $question3 = $_POST["que3"];
        $answer1 = md5($_POST["ans1"]);
        $answer2 = md5($_POST["ans2"]);
        $answer3 = md5($_POST["ans3"]);

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetchAll();

        if($user){
            echo 1;                                                         //Send 1 if email address already exist
        }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            echo 2;                                                         //Send 2 if email address is invalid
        }else if(strlen($_POST["password"]) < 8 || strlen($_POST["password"]) > 20){
            echo 3;                                                         //Send 3 if password is not in range
        }else{
            $stmt = $conn->prepare("INSERT INTO users " .
            "(fullname,username,email,password,question1,question2,question3,answer1,answer2,answer3) VALUES " .
            "(?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$fullname, $username, $email, $password, $question1, $question2, $question3, $answer1, $answer2, $answer3]);
            echo 4;                                                        //Send 4 to verify that request completed successfully
        }
    }else if($_POST["act"] == "addSeller"){
        //Add a User by verifying all the fields
        $email = $_POST["email"];
        $username = $_POST["username"];
        $password = md5($_POST["password"]);

        $stmt = $conn->prepare("SELECT * FROM sellers WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetchAll();

        if($user){
            echo 1;                                                                             //Send 1 if email address already exist
        }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            echo 2;                                                                             //Send 2 if email address is invalid
        }else if(strlen($_POST["password"]) < 8 || strlen($_POST["password"]) > 20){
            echo 3;                                                                              //Send 3 if password is not in range
        }else{
            $stmt = $conn->prepare("INSERT INTO sellers " .
            "(username,email,password) VALUES " .
            "(?,?,?)");
            $stmt->execute([$username, $email, $password]);
            echo 4;                                                                             //Send 4 to verify that request completed successfully
        }
    }

    
    
}else{
    header('Location: index.php');                                                              
}
