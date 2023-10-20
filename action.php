<?php
    require 'config.php';
    if (isset($_POST['pid']))
    {
        $pid = $_POST['pid'];
        $pname = $_POST['pname'];
        $pprice = $_POST['pprice'];
        $pimage = $_POST['pimage'];
        $pcode = $_POST['pcode'];
        $pqty = 1;

        $stmt = $conn->prepare("SELECT product_code FROM cart WHERE product_code=?");
        $stmt->bind_param("s",$pcode);
        $stmt->execute();
        $res = $stmt->get_result();
        $r = $res->fetch_assoc();
        if(isset($r['product_code']))
            $code = $r['product_code'];
        else
            $code = null;

        if(!$code)
        {
            $query = $conn->prepare("INSERT INTO cart (product_name,product_price,product_image,qty, total_price,product_code) VALUES (?,?,?,?,?,?)");
            $query->bind_param("sssiss", $pname, $pprice, $pimage, $pqty, $pprice, $pcode) ;
            $query->execute();

            /*B4 Closing Alerts https: //uww.w3schools.con/bootstrapa/bootstrap_alerts.asp */

            echo '<div class="alert alert-success alert-dismissible mt-3">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Item added to your cart!</strong>
                </div>';
        }
        else{
            echo '<div class="alert alert-danger alert-dismissible mt-3">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Item already added to your cart!</strong>
                </div>';
        }

    }


    if(isset($_GET['cartItem']) && isset($_GET['cartItem']) == 'cart_item'){
            $stmt = $conn->prepare("SELECT * FROM cart");
            $stmt->execute();
            $res = $stmt->get_result();
            $pocet = $res -> num_rows;

            echo $pocet;
    }

    if(isset($_GET['remove'])){
            $id = $_GET['remove'];

            $stmt = $conn->prepare("DELETE FROM cart WHERE $id = id");
            $stmt -> execute();

            $_SESSION['showAlert'] = 'block';
            $_SESSION['message'] = 'An item has been removed';
            header("Location: cart.php");
    }

    if(isset($_GET['clear'])){
            $stmt = $conn->prepare("TRUNCATE TABLE cart;");
            $stmt -> execute();
            header("Location: cart.php");
    }
    
    if(isset($_POST['qty'])){
        $qty = $_POST['qty'];
        $pid = $_POST['pid'];
        $pprice = $_POST['pprice'];

        $tprice = $qty*$pprice;
        
        $stmt = $conn->prepare("UPDATE cart SET qty=?, total_price=? WHERE id=?");
        $stmt->bind_param("isi",$qty,$tprice,$pid);
        $stmt->execute();


    }
    if(isset($_POST['action']) == "order"){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $pmode = $_POST['pmode'];
        $product = $_POST['products'];
        $products = explode(",",$product);
        $grand_total = $_POST['grand_total'];

        $stmt = $conn->prepare("INSERT INTO orders(name,email,phone,address,pmode,products,amount_paid) VALUES ('$name','$email','$phone','$address','$pmode','$product','$grand_total');");
        $stmt->execute();   
        $stmt = $conn->prepare("TRUNCATE TABLE cart;");
            $stmt -> execute();
        
        echo '
        <div class="container">
        <div class="row justify-content-center my-4">
            <div class="col-lg-12 px-0 pb-4" id="order">
                <div class="alert alert-success alert-dismissible mt-3">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Order was successfull!</strong>
                </div>
                <div class="jumbotron p-3 mb-2">
                    <h6 class="lead">
                        <strong>Meno: </strong>',$name,'<br>
                        <strong>Email: </strong>',$email,'<br>
                        <strong>Phone: </strong>',$phone,'<br>
                        
                    </h6>
                    <h6 class="lead">
                        <strong>Address: </strong>',$address,'<br>
                    </h6>
                    <h6 class="lead">
                        <strong>Payment: </strong>',$pmode,'<br>
                    </h6>
                    <h6 class="lead">
                        <strong>Product(s): </strong><br>
                    <ul>                   
                    ';
                        
                    foreach($products as $p){
                        echo '<li>',$p,'</li>';                        
                    }
                    echo '
                    </ul>
                    </h6>
                    <h5><strong>Amount Paid: </strong>',$grand_total/100,'&euro;<br></h5>
                </div>
                <a href="index.php" class="btn btn-success"><i class="fas fa-cart-plus"></i>Continue shopping</a>
                
            </div>
        </div>
    </div>
            
            ';
    
    }
?>



