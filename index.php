<?php

session_start();
include('server/connection.php');

//1. noteikt lapas numuru
if(isset($_GET['page_no']) && $_GET['page_no'] != ""){

    //ja lietotājs jau ir ievadījis lapu, tad lapas numurs ir tas, ko viņš izvēlējās
    $page_no = $_GET['page_no'];
}else {

    //ja lietotājs tikko ievadīja lapu, noklusējuma lapa ir 1
    $page_no = 1;
}

//2. preces atgriešanas numurs
$stmt = $conn->prepare("SELECT COUNT(*) AS total_records FROM products");
$stmt->execute();
$stmt->bind_result($total_records);
$stmt->store_result();
$stmt->fetch();

//3. preces vienā lapā
$total_records_per_page = 4;

$offset = ($page_no - 1) * $total_records_per_page;

$previous_page = $page_no - 1;
$next_page = $page_no + 1;

$adjacents = "2";

$total_no_of_pages = ceil($total_records / $total_records_per_page);

//4. saņemt visas preces

$stmt1 = $conn->prepare("SELECT * FROM products LIMIT ?,?");
$stmt1->bind_param("ii", $offset, $total_records_per_page);
$stmt1->execute();
$products = $stmt1->get_result();

?>
<?php include('layouts/header.php'); ?>
      <!--Galvena lapa-->
      <main>
        <section id="index" class="mt-5 py-5">
            <div class="cards">

                <?php include('server/get_all_products.php');?>

                <?php while($row = $products->fetch_assoc()){ ?>

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
                </div>

                <?php } ?>

            </div>

            <?php include('layouts/pagination.php'); ?>

        </section>
        <!-- Produktu karšu režģis -->
      </main>

<?php include('layouts/footer.php'); ?>