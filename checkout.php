<?php
require 'config.php';
$grand_total=0;
$allItems='';
$items=array();

$sql="SELECT CONCAT(product_name, '(',qty,')') AS ItemQty, total_price FROM cart";
$stmt=$conn->prepare($sql);
$stmt->execute();
$result=$stmt->get_result();
while($row=$result->fetch_assoc())
{
    $grand_total+=$row['total_price'];
    $items[]=$row['ItemQty'];

}
$allItems=implode(", ",$items);
?>

<!DOCTYPE html>
<html lang="en"></html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="Description" content="Enter your description here"/>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
<script src="https://kit.fontawesome.com/a076d05399.js" ></script>
<title>Shopping cart system</title>
</head>
<body>

<nav class="navbar navbar-expand-md bg-dark navbar-dark">
  <!-- Brand -->
  <a class="navbar-brand" href="#index.php"><i class="fas fa-mobile-alt"></i> Mobile Store</a>

  <!-- Toggler/collapsibe Button -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Navbar links -->
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link " href="index.php">Products</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Categories</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="#">Checkout</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="cart.php">
            <i class="fas fa-shopping-cart">
                <span id="cart-item" class="badge badge-danger"></span>
            </i>
        </a>
      </li>
    </ul>
  </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 px-4 pb-4" id="order">
            <h4 class="text-center text-info p-2">
                Complete your order!
            </h4>
            <div class="jumbotron p-3 mb-2 text-center">
                <h6 class="lead">
                    <strong>Product(s): </strong><?= $allItems;?>
                </h6>
                <h6 class="lead"><strong>Delivery Charge:</strong>Free :-) </h6>
                <h5><strong>Amount Payable:</strong><?= number_format($grand_total,2) ?> </h5>
            </div>

            <form action="" method="post" id="placeOrder">
                <input type="hidden" name="products" value="<?= $allItems;?>">
                <input type="hidden" name="grand_total" value="<?= $grand_total;?>">
                <div class="form-group">
                    <input type="text" name="name" class="fomr-control" placeholder="Enter name" required>
                </div>
                <div class="form-group">
                    <input type="email" name="name" class="fomr-control" placeholder="Enter email" required>
                </div>
                <div class="form-group">
                    <input type="tel" name="name" class="fomr-control" placeholder="Enter phone" required>
                </div>
                <div class="form-group">
                    <textarea name="address" class="fomr-control" row="3" cols="10" placeholder="Enter delivery address here...." required></textarea>
                </div>
                <h6 class="text-center lead">Select Payment Method</h6>
                <div class="form-group">
                    <select name="pmode" class="form-control">
                        <option value="" selected disabled>-Select Payment Method-</option>
                        <option value="cod" >Cash on delivery</option>
                        <option value="netbanking"  >Net banking</option>
                        <option value="cards"  >debit/credit card</option>
                    </select>
                </div>

                <div class="form-group">
                    <input type="submit" name="submit" class="btn btn-danger btn-block" value="Place order">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Latest compiled and minified CSS -->

<!--<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

<script type="text/javascript">
    $(document).ready(function()
    {
      
      $('#placeOrder').submit(function(e){
            e.preventDefault();
            $.ajax({
                url: 'action.php',
                method: 'post',
                data: $('form').serialize()+"&action=order",
                success: function(response){
                    load_cart_item_number();
                    $("#order").html(response);                    
                }
            })
      });

      load_cart_item_number();

      function load_cart_item_number(){
        $.ajax({
            url:'action.php',
            method:'get',
            data: {cartItem: "cart_item"},
            success:function(response)
            {
              $("#cart-item").html(response);
            }
          });
      }
    });

</script>

</body>
</html>