<?php
session_start();

if(!empty($_SESSION['cart'])){

 //nosūtīt lietotāju uz grozu
}else {
  header('location: cart.php?message=Jūsu grozs ir tukšs');
}

?>
<?php include('layouts/header.php'); ?>
      <!--Izrakstīšanās-->

      <section class="my-5 py-5">
        <div class="container text-center mt-3 pt-5">
            <h2 class="form-weight-bold">Pasūtījuma noformēšana</h2>
            <hr class="mx-auto">
        </div>

        <div class="mx-auto container">
            <form id="checkout-form" method="POST" action="server/place_order.php">
                <p style="color: red;" class="text-center">
                    <?php if(isset($_GET['message'])){ echo $_GET['message']; }?>
                    <?php if(isset($_GET['message'])){ ?>
                        <a href="register.php" id="register-url">Reģistrēties</a> /
                        <a href="login.php" id="login-url">Pieslēgties</a>
                    <?php } ?>
                </p>
                
                <div class="form-group checkout-small-element">
                    <label>Vārds</label>
                    <input type="text" class="form-control" id="checkout-name" name="name" placeholder="Vārds" required>
                </div>
                <div class="form-group checkout-small-element">
                    <label>Uzvārds</label>
                    <input type="text" class="form-control" id="checkout-surname" name="surname" placeholder="Uzvārds" required>
                </div>
                <div class="form-group checkout-small-element">
                    <label>Tālrunis</label>
                    <input type="tel" class="form-control" id="checkout-phone" name="phone" placeholder="Tālrunis" required>
                </div>
                <div class="form-group checkout-small-element">
                    <label>E-pasts</label>
                    <input type="email" class="form-control" id="checkout-email" name="email" placeholder="E-pasts" required>
                </div>
                <div class="form-group checkout-small-element">
                    <label>Valsts</label>
                    <input type="text" class="form-control" id="checkout-country" name="country" placeholder="Valsts" required>
                </div>
                <div class="form-group checkout-small-element">
                    <label>Adrese</label>
                    <input type="text" class="form-control" id="checkout-address" name="address" placeholder="Adrese" required>
                </div>
                <div class="form-group checkout-btn-container">
                    <p>Kopējā summa: <?php echo $_SESSION['total']; ?>€</p>
                    <input type="submit" class="btn" id="checkout-btn" name="place_order" value="Izrakstīšanās">
                </div>
                
            </form>
        </div>
      </section>

<?php include('layouts/footer.php'); ?>