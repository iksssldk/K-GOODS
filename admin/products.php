<?php include('header.php'); ?>

<?php

include('../server/connection.php');

if(!isset($_SESSION['admin_logged_in'])){
    header('location: login.php');
    exit;
}

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
$total_records_per_page = 10;

$offset = ($page_no - 1) * $total_records_per_page;

$previous_page = $page_no - 1;
$next_page = $page_no + 1;

$adjacents = "2";

$total_no_of_pages = ceil($total_records / $total_records_per_page);

//4. saņemt visas preces

$stmt1 = $conn->prepare("SELECT * FROM products LIMIT $offset, $total_records_per_page");
$stmt1->execute();
$products = $stmt1->get_result();

?>

<div class="container-fluid">
    <div class="row" style="min-height: 1000px;">

        <?php include('sidemenu.php'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 ">
                <h2 class="h2">Mērinstrumentu panelis</h2>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                    </div>
                </div>
            </div>
            <h2>Preces</h2>

            <?php if(isset($_GET['edit_error'])) { ?>
            <p style="color: red;" class="text-center"><?php if(isset($_GET['edit_error'])){ echo $_GET['edit_error']; }?></p>
            <?php } ?>

            <?php if(isset($_GET['edit_message'])) { ?>
            <p style="color: green;" class="text-center"><?php if(isset($_GET['edit_message'])){ echo $_GET['edit_message']; }?></p>
            <?php } ?>

            <?php if(isset($_GET['deleted_failure'])) { ?>
            <p style="color: red;" class="text-center"><?php if(isset($_GET['deleted_failure'])){ echo $_GET['deleted_failure']; }?></p>
            <?php } ?>

            <?php if(isset($_GET['deleted_successfully'])) { ?>
            <p style="color: green;" class="text-center"><?php if(isset($_GET['deleted_successfully'])){ echo $_GET['deleted_successfully']; }?></p>
            <?php } ?>

            <?php if(isset($_GET['product_failed'])) { ?>
            <p style="color: red;" class="text-center"><?php if(isset($_GET['product_failed'])){ echo $_GET['product_failed']; }?></p>
            <?php } ?>

            <?php if(isset($_GET['product_created'])) { ?>
            <p style="color: green;" class="text-center"><?php if(isset($_GET['product_created'])){ echo $_GET['product_created']; }?></p>
            <?php } ?>

            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th scope="col">Prece ID</th>
                            <th scope="col">Preces foto</th>
                            <th scope="col">Nosaukums</th>
                            <th scope="col">Cena</th>
                            <th scope="col">Preces kategorija</th>
                            <th scope="col">Krāsa</th>
                            <th scope="col">Preces apraksts</th>
                            <th scope="col">Preču pieejamība</th>
                            <th scope="col">Rediģēt</th>
                            <th scope="col">Izdzēst</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($products as $product) {?>
                        <tr>
                            <td><?php echo $product['product_id']; ?></td>
                            <td><img src="<?php echo "../assets/imgs/".$product['product_image']; ?>" style="width: 70px;"></td>
                            <td><?php echo $product['product_name']; ?></td>
                            <td><?php echo $product['product_price']."€"; ?></td>
                            <td><?php echo $product['product_category']; ?></td>
                            <td><?php echo $product['product_color']; ?></td>
                            <td><?php echo $product['product_description']; ?></td>
                            <td><?php echo $product['availability']; ?></td>
                            <td><a class="btn btn-primary" href="edit_product.php?product_id=<?php echo $product['product_id']; ?>">Rediģēt</a></td>
                            <td><a class="btn btn-danger" href="delete_product.php?product_id=<?php echo $product['product_id']; ?>">Izdzēst</a></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <?php include('../layouts/pagination.php'); ?>

            </div>
        </main>
    </div>
</div>
