<?php include('header.php'); ?>

<?php

include('../server/connection.php');

if(!isset($_SESSION['admin_logged_in'])){
    header('location: login.php');
    exit;
}

if(isset($_GET['product_id'])){
    $product_id = $_GET['product_id'];

    $stmt = $conn->prepare("SELECT * FROM products
                            WHERE product_id = ? ");
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $products = $stmt->get_result();

}else if(isset($_POST['edit_product'])){

    $product_id = $_POST['product_id'];
    $title = $_POST['title'];
    $color = $_POST['color'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $availability = $_POST['availability'] ? 1 : 0;

    $stmt = $conn->prepare("UPDATE products SET product_name = ?, product_color = ?, product_description = ?, product_price = ?, product_category = ?, availability = ?
                            WHERE product_id = ?");

        $stmt->bind_param('sssssii', $title, $color, $description, $price, $category, $availability, $product_id);

        if($stmt->execute()){
            header('location: products.php?edit_message=Prece ir veiksmīgi atjaunināta!');

        }else {
            header('location: products.php?edit_error=Nevarēja atjaunināt preci!');
        }

}else {
    header('location: products.php');
    exit;
}

?>

<div class="container-fluid">
    <div class="row" style="min-height: 1000px;">

        <?php include('sidemenu.php'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2>Rediģēt preci</h2>
            <div class="table-responsive">
                <div class="mx-auto container">
                        <form id="edit-product-form" method="POST" action="edit_product.php">
                            
                            <?php foreach($products as $product){ ?>
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <div class="form-group">
                                <label>Nosaukums</label>
                                <input type="text" class="form-control" id="product-name" value="<?php echo $product['product_name']; ?>" name="title" placeholder="Nosaukums" required>
                            </div>
                            <div class="form-group mt-2">
                                <label>Krāsa</label>
                                <input type="text" class="form-control" id="product-color" value="<?php echo $product['product_color']; ?>" name="color" placeholder="Krāsa">
                            </div>
                            <div class="form-group mt-2">
                                <label>Apraksts</label>
                                <input type="text" class="form-control" id="product-description" value="<?php echo $product['product_description']; ?>" name="description" placeholder="Apraksts">
                            </div>
                            <div class="form-group mt-2">
                                <label>Cena</label>
                                <input type="text" class="form-control" id="product-price" value="<?php echo $product['product_price']; ?>" name="price" placeholder="Cena" required>
                            </div>
                            <div class="form-group mt-2">
                                <label>Kategorija</label>
                                <select class="form-select" name="category" required>
                                <option value="Uzstāšanās kostīmi">Uzstāšanās kostīmi</option>
                                    <option value="T-krekli">T-krekli</option>
                                    <option value="Bikses">Bikses</option>
                                    <option value="Apavi">Apavi</option>
                                    <option value="Zobeni">Zobeni</option>
                                    <option value="Šķēpi">Šķēpi</option>
                                    <option value="Eksotiskie ieroči">Eksotiskie ieroči</option>
                                    <option value="Paklāji">Paklāji</option>
                                    <option value="Dekorācijas">Dekorācijas</option>
                                    <option value="Papilds">Papilds</option>
                                </select>
                            </div>
                            <div class="form-group mt-2">
                                <label>Pieejamība</label>
                                <select class="form-select" name="availability" required>
                                    <option value="1">Pieejamība</option>
                                    <option value="0">Nav pieejamība</option>
                                </select>
                            </div>
                            <div class="form-group mt-3">
                                <input type="submit" class="btn btn-primary" id="edit_product" name="edit_product" value="Rediģēt">
                            </div>

                            <?php } ?>

                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
