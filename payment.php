<?php
session_start();

if(isset($_POST['order_pay_btn'])){
    $order_status = $_POST['order_status'];
    $order_total_price = $_POST['order_total_price'];
    
}

?>

<?php include('layouts/header.php'); ?>

      <!--Maksājums-->

      <section class="my-5 py-5">
        <div class="container text-center">
            <h2 class="form-weight-bold">Maksājums</h2>
            <hr class="mx-auto">
        </div>

        <div class="mx-auto container text-center">

            <p><?php if(isset($_POST['order_status'])){ echo $_POST['order_status']; }?></P>

            <?php if(isset($_POST['order_status']) && $_POST['order_status'] == "Nav apmaksāts"){ ?>
                <?php $amount = strval($_POST['order_total_price']); ?>
                <?php $order_id = $_POST['order_id']; ?>
                <p>Kopējā summa: <?php echo $_POST['order_total_price']; ?>€</p>
                <div class="container text-center">
                    <div id="paypal-button-container"></div>
                </div>

            <!--<input type="submit" class="btn btn-primary" id="payment-btn" name="place_order" value="Maksāt">-->
            <?php } else if (isset($_SESSION['total']) && $_SESSION['total'] != 0){ ?>
                <?php $amount = strval($_SESSION['total']); ?>
                <?php $order_id = $_SESSION['order_id']; ?>
                <p>Kopējā summa: <?php if(isset($_SESSION['total'])){ echo $_SESSION['total']; }?>€</p>
                <div class="container text-center">
                    <div id="paypal-button-container"></div>
                </div>
                <!--<input type="submit" class="btn btn-primary" id="payment-btn" name="place_order" value="Maksāt">-->
                
            <?php }else { ?>
                <p>Jūsu grozs ir tukšs</p>
            <?php } ?>

        </div>
      </section>

      <script src="https://www.paypal.com/sdk/js?client-id=AcF4MO7gt7y3eh5oFcgKlOn3wmNq_kHbeDhCCfpKMxWpi9nuG7HnAqk1jAI3tf0UJGgLD1Bz7e0VBAtN&currency=EUR"></script>

      <script>
            paypal.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: '<?php echo $amount; ?>',
                                currency: "EUR" // Установите сумму, которую вы хотите получить
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(orderData) {
                        // Successful capture! For dev/demo purposes:
                        console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                        var transaction = orderData.purchase_units[0].payments.captures[0];
                        alert('Transaction ' + transaction.status + ': ' + transaction.id + '\n\nSee console for all available details');
                        // Здесь вы можете выполнить какие-либо дополнительные действия после успешной транзакции

                        window.location.href = "server/complete_payment.php?transaction_id=" + transaction.id + "&order_id=<?php echo $order_id; ?>";
                    });
                }
            }).render('#paypal-button-container');
        </script>


      
<?php include('layouts/footer.php'); ?>