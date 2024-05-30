<?php
session_start();

if(isset($_POST['add_to_cart'])){

    //ja lietotājs jau ir pievienojis preci grozam
    if(isset($_SESSION['cart'])){

        $products_array_ids = array_column($_SESSION['cart'], 'product_id');

        //ja prece jau ir pievienota grozam vai nav
        if(!in_array($_POST['product_id'], $products_array_ids)){

            $product_id = $_POST['product_id'];

                $product_array = array(
                    'product_id' => $_POST['product_id'],
                    'product_name' => $_POST['product_name'],
                    'product_price' => $_POST['product_price'],
                    'product_image' => $_POST['product_image'],
                    'product_quantity' => $_POST['product_quantity']

                );

                $_SESSION['cart'] [$product_id] = $product_array;

        //produkts jau ir pievienots
        }else {

            echo '<script>alert("Prece jau ir pievienota grozam!");</script>';
            //echo '<script>window.location="index.php";</script>';

        }

        //ja šis ir pirmais produkts
    }else {

        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_image = $_POST['product_image'];
        $product_quantity = $_POST['product_quantity'];

        $product_array = array(
                        'product_id' => $product_id,
                        'product_name' => $product_name,
                        'product_price' => $product_price,
                        'product_image' => $product_image,
                        'product_quantity' => $product_quantity

        );

        $_SESSION['cart'] [$product_id] = $product_array;
    }

    //aprēķināt kopā
    calculateTotalCart();

//izņemt preci no groza
}else if(isset($_POST['remove_product'])) {

    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
    //aprēķināt kopā
    calculateTotalCart();

}else if(isset($_POST['edit_quantity'])){

    //no veidlapas iegūstam id un daudzumu
    $product_id = $_POST['product_id'];
    $product_quantity = $_POST['product_quantity'];

    //iegūstiet preces masīvu no sesijas
    $product_array = $_SESSION['cart'] [$product_id];

    //atjaunināt preci daudzumu
    $product_array['product_quantity'] = $product_quantity;

    //atgriezt masīvu atpakaļ savā vietā
    $_SESSION['cart'] [$product_id] = $product_array;

    //aprēķināt kopā
    calculateTotalCart();

}else {
    //header('location:index.php');
}

function calculateTotalCart(){

    $total_price = 0;
    $total_quantity = 0;

    foreach ($_SESSION['cart'] as $key => $value){

        $product = $_SESSION['cart'] [$key];

        $price = $product['product_price'];
        $quantity = $product['product_quantity'];

        $total_price = $total_price + ($price * $quantity);
        $total_quantity =  $total_quantity + $quantity;
    }

    $_SESSION['total'] = $total_price;
    $_SESSION['quantity'] = $total_quantity;

}

?>

<?php include('layouts/header.php'); ?>

      <!--Cart-->
    <section class="cart container my-5 py-5">
        <div class="container nt-5">
            <h2 class="font-weight-bold">Jūsu groza</h2>
            <hr>
        </div>
        <p style="color: red;" class="text-center"><?php if(isset($_GET['message'])){ echo $_GET['message']; }?></p>
        <table class="mt-5 pt-5">
            <tr>
                <th>Produkts</th>
                <th>Daudzums</th>
                <th>Starpsumma</th>
            </tr>

            <?php if(isset($_SESSION['cart'])){ ?>


                <?php foreach($_SESSION['cart'] as $key => $value){ ?>

                <tr>
                    <td>
                        <div class="product-info">
                            <img src="assets/imgs/<?php echo $value ['product_image']; ?>">
                            <div>
                                <p><?php echo $value ['product_name'];?></p>
                                <small><?php echo $value ['product_price']."€"; ?></small>
                                <br>
                                <form method="POST" action="cart.php">
                                    <input type="hidden" name="product_id" value="<?php echo $value ['product_id']; ?>">
                                    <input type="submit" name="remove_product" class="remove-btn" value="Izņemt">
                                </form>
                            </div>
                        </div>
                    </td>

                    <td>
                        <form method="POST" action="cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $value ['product_id']; ?>">
                            <input type="number" name="product_quantity" value="<?php echo $value ['product_quantity']; ?>">
                            <input type="submit" name="edit_quantity" class="edit-btn" value="Rediģēt">
                        </form>
                    </td>

                    <td>
                        <span>€</span>
                        <span class="product-price"><?php echo $value ['product_quantity'] * $value ['product_price']; ?></span>
                    </td>
                </tr>

                <?php  } ?>
            <?php  } ?>

        </table>

        <div class="cart-total mt-3">
            <table>
                <!--<tr>
                    <td>Starpsumma</td>
                    <td>15.99€</td>
                </tr>
            -->
                <tr>
                    <td>Kopā</td>
                    <td><?php echo isset($_SESSION['total']) ? $_SESSION['total']."€": '0€' ;?></td>
                </tr>
            </table>
        </div>

        <div class="checkout-container">
            <form method="POST" action="checkout.php">
                <input type="submit" name="checkout" class="checkout-btn" value="Noformēt pasūtījumu">
            </form>
        </div>

    </section>

<?php include('layouts/footer.php'); ?>