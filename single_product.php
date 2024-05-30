<?php
session_start();
include('server/connection.php');

if(isset($_GET['product_id'])){

  $product_id = $_GET['product_id'];

    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ? ");
    $stmt->bind_param("i", $product_id);

    $stmt->execute();
    
    $product = $stmt->get_result();

} else{

  header('location:index.php');
}

?>
  <?php include('layouts/header.php'); ?>

      <section class="single-product pb-4" style="display: flex;">
        <div class="row mt-5" style="justify-content: center;">

          <?php while($row = $product->fetch_assoc()){ ?>

              <div class="col-lg-5 col-md-6 col-sm-12">
                  <img class="img-fluid w-100 pb-2" src="assets/imgs/<?php echo $row ['product_image']; ?>"/>
              </div>
              <div class="col-lg-6 col-md-12 col-12">
                  <h2 class="py-2"><?php echo $row ['product_name']; ?></h2>
                  <h4><?php echo $row ['product_price']; ?>€</h4>

                  <form method="POST" action="cart.php">
                    <input type="hidden" name="product_id" value="<?php echo $row ['product_id']; ?>">
                    <input type="hidden" name="product_image" value="<?php echo $row ['product_image']; ?>">
                    <input type="hidden" name="product_name" value="<?php echo $row ['product_name']; ?>">
                    <input type="hidden" name="product_price" value="<?php echo $row ['product_price']; ?>">
                    <input type="number" name="product_quantity"value="1"/>
                    <?php if($row['availability']) { ?>

                    <button class="buy-btn mt-3" type="submit" name="add_to_cart">Pievienot grozā</button>

                    <?php } else { ?>

                    <p class="text-danger">Nav pieejāms</p>

                    <?php } ?>
                                  
                  </form>

                  <h4 class="mt-5">Produkta informācija</h4>
                  <span><?php echo $row ['product_description']; ?></span>
              </div>
            </form>

          <?php } ?>

        </div>
      </section>

<?php include('layouts/footer.php'); ?>