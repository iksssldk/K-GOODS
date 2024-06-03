<?php include('header.php'); ?>

<?php

include('../server/connection.php');

if(!isset($_SESSION['logged_in'])){
    header('location: ../login.php');
    exit;
}

if(isset($_GET['order_id'])){
    $order_id = $_GET['order_id'];

    $stmt = $conn->prepare("SELECT * FROM orders
                            WHERE order_id = ? ");
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $orders = $stmt->get_result();

}else if(isset($_POST['edit_order'])){

    $order_id = $_POST['order_id'];
    $order_status = $_POST['order_status'];

    $stmt = $conn->prepare("UPDATE orders SET order_status = ?
                            WHERE order_id = ?");

        $stmt->bind_param('si', $order_status, $order_id);

        if($stmt->execute()){
            header('location: index.php?order_updated=Pasūtījums ir veiksmīgi atjaunināts!');

        }else {
            header('location: index.php?order_failed=Radās kļūda, mēģiniet vēlreiz!');
        }

}else {
    header('location: index.php');
    exit;
}

?>

<div class="container-fluid">
    <div class="row" style="min-height: 1000px;">

        <?php include('sidemenu.php'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2>Rediģēt pasūtījumu</h2>
            <div class="table-responsive">
                <div class="mx-auto container">
                        <form id="edit-order-form" method="POST" action="edit_order.php">
                            
                            <?php foreach($orders as $r){ ?>

                            <input type="hidden" name="order_id" value="<?php echo $r['order_id']; ?>">
                            <div class="form-group">
                                <label>Pasūtījuma ID</label>
                                <p class="my-4"><?php echo $r['order_id']; ?></p>
                            </div>
                            <div class="form-group mt-2">
                                <label>Pasūtījuma cena</label>
                                <p class="my-4"><?php echo $r['order_cost']; ?></p>
                            </div>
                            <div class="form-group mt-2">
                                <label>Pasūtījuma status</label>
                                <select class="form-select" name="order_status" required>
                                    <option value="Apmaksāts" <?php if($r ['order_status'] == 'Apmaksāts'){ echo "selected"; } ?>>Apmaksāts</option>
                                    <option value="Nav samaksāts" <?php if($r ['order_status'] == 'Nav samaksāts'){ echo "selected"; } ?>>Nav samaksāts</option>
                                    <option value="Nosūtīts" <?php if($r ['order_status'] == 'Nosūtīts'){ echo "selected"; } ?>>Nosūtīts</option>
                                    <option value="Piegādāts" <?php if($r ['order_status'] == 'Piegādāts'){ echo "selected"; } ?>>Piegādāts</option>
                                </select>
                            </div>
                            <div class="form-group mt-2">
                                <label>Datums</label>
                                <p class="my-4"><?php echo $r['order_date']; ?></p>
                            </div>
                            <div class="form-group mt-3">
                                <input type="submit" class="btn btn-primary" id="edit_order" name="edit_order" value="Rediģēt">
                            </div>

                            <?php } ?>

                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
