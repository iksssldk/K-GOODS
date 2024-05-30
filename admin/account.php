<?php include('header.php'); ?>

<?php

if(!isset($_SESSION['admin_logged_in'])){
    header('location: login.php');
    exit;
}
?>

<div class="container-fluid">
    <div class="row" style="min-height: 1000px;">

        <?php include('sidemenu.php'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 ">
                <h2 class="h2">Administratora konts</h2>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">

                    </div>
                </div>
            </div>
            
            <div class="container">
                <p>ID: <?php echo $_SESSION['admin_id']; ?></p>
                <p>Vārds: <?php echo $_SESSION['admin_name']; ?></p>
                <p>E-pasts: <?php echo $_SESSION['admin_email']; ?></p>
            </div>

            <?php if(isset($_GET['order_failed'])) { ?>
            <p style="color: red;" class="text-center"><?php if(isset($_GET['order_failed'])){ echo $_GET['order_failed']; }?></p>
            <?php } ?>

            <?php if(isset($_GET['order_updated'])) { ?>
            <p style="color: green;" class="text-center"><?php if(isset($_GET['order_updated'])){ echo $_GET['order_updated']; }?></p>
            <?php } ?>


            </div>
        </main>
    </div>
</div>