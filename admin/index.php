<?php include('header.php'); ?>

<?php

include('../server/connection.php');

if(!isset($_SESSION['logged_in'])){
    header('location: ../login.php');
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
$stmt = $conn->prepare("SELECT COUNT(*) AS total_records FROM orders");
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

$stmt1 = $conn->prepare("SELECT * FROM orders LIMIT $offset, $total_records_per_page");
$stmt1->execute();
$orders = $stmt1->get_result();

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
            <h2>Pasūtījumi</h2>

            <?php if(isset($_GET['order_failed'])) { ?>
            <p style="color: red;" class="text-center"><?php if(isset($_GET['order_failed'])){ echo $_GET['order_failed']; }?></p>
            <?php } ?>

            <?php if(isset($_GET['order_updated'])) { ?>
            <p style="color: green;" class="text-center"><?php if(isset($_GET['order_updated'])){ echo $_GET['order_updated']; }?></p>
            <?php } ?>

            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th scope="col">Pasūtījuma ID</th>
                            <th scope="col">Pasūtījuma status</th>
                            <th scope="col">Pasūtījuma datums</th>
                            <th scope="col">Lietotāja ID</th>
                            <th scope="col">Lietotāja talruņa numurs</th>
                            <th scope="col">Lietotāja adrese</th>
                            <th scope="col">Rediģēt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($orders as $r) {?>
                        <tr>
                            <td><?php echo $r['order_id']; ?></td>
                            <td><?php echo $r['order_status']; ?></td>
                            <td><?php echo $r['order_date']; ?></td>
                            <td><?php echo $r['user_id']; ?></td>
                            <td><?php echo $r['user_phone']; ?></td>
                            <td><?php echo $r['user_address']; ?></td>
                            <td><a class="btn btn-primary" href="edit_order.php?order_id=<?php echo $r['order_id']; ?>">Rediģēt</a></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                
                <?php include('../layouts/pagination.php'); ?>

            </div>
        </main>
    </div>
</div>
