<?php include('layouts/header.php'); ?>

      <!--Galvena lapa-->
      <main>
        <section id="index" class="mt-2 py-5">
            <div class="title">
                <h3>Papilds</h3>
                <hr class="mx-auto">
            </div>

                <!-- Produktu karšu režģis -->
                <div class="cards">

                    <?php include('server/get_additional.php'); ?>

                    <?php while($row = $additional_products->fetch_assoc()){ ?>

                    <div class="card">
                        <div class="card_top">
                            <a href="#" class="card_image">
                                <img src="assets/imgs/<?php echo $row ['product_image']; ?>" alt="<?php echo $row ['product_name']; ?>"/>
                            </a>
                        <!-- <div class="card__label">-10%</div> -->
                        </div>
                        <div class="card_bottom">
                            <h5 class="card_title"><?php echo $row ['product_name']; ?></h5>
                            <div class="card_prices">
                        <!-- <div class="card__price card__price--discount">135 000</div> -->
                            <div class="card_price card_price--common"><?php echo $row ['product_price']; ?></div>
                        </div>
                        <?php if($row['availability']) { ?>

                        <a href="<?php echo "single_product.php?product_id=". $row['product_id']; ?>" style="text-decoration: none;"><button class="card_add">Pievienot grozā</button></a>

                        <?php } else { ?>

                        <p class="text-danger">Nav pieejāms</p>

                        <?php } ?>

                    </div>

                    <?php } ?>

                </div>

                <?php include('layouts/pagination.php'); ?>

            </section>
      </main>

<?php include('layouts/footer.php'); ?>