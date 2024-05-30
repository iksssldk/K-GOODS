<?php include('header.php'); ?>

<?php 

include('../server/connection.php');

if(!isset($_SESSION['admin_logged_in'])){
    header('location: login.php');
    exit;
}

if(isset($_POST['create_product'])){
    $product_name = $_POST['name'];
    $product_description = $_POST['description'];
    $product_price = $_POST['price'];
    $product_color = $_POST['color'];
    $product_category = $_POST['category'];

    $image = $_FILES['image']['tmp_name'];
    $unique_id = uniqid();
    $image_name = $product_name.".jpeg";
    $image_name = $product_name . "_" . $unique_id . ".jpeg";
    //augšupielādēt attēlu
    move_uploaded_file($image,"../assets/imgs/".$image_name);

    //pievienot jaunu preci
    $stmt = $conn->prepare("INSERT INTO products (product_name, product_description, product_price, product_color, product_category, product_image)
                            VALUES (?,?,?,?,?,?) ");
    $stmt->bind_param('ssssss', $product_name, $product_description, $product_price, $product_color, $product_category, $image_name);
    
    if($stmt->execute()){

        header('location: products.php?product_created=Prece ir veiksmīgi pievinots!!');

    }else {
        header('location: products.php?product_failed=Radās kļūda, mēģiniet vēlreiz!');
    }
}

?>

<div class="container-fluid">
    <div class="row" style="min-height: 1000px;">

        <?php include('sidemenu.php'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2>Pievienot jaunu preci</h2>
            <div class="table-responsive">
                <div class="mx-auto container">
                        <form id="add-product-form" enctype="multipart/form-data" method="POST" action="add_product.php">
                            <div class="form-group">
                                <label>Nosaukums</label>
                                <input type="text" class="form-control" id="product-name" name="name" placeholder="Nosaukums" required>
                            </div>
                            <div class="form-group mt-2">
                                <label>Krāsa</label>
                                <input type="text" class="form-control" id="product-color" name="color" placeholder="Krāsa">
                            </div>
                            <div class="form-group mt-2">
                                <label>Apraksts</label>
                                <input type="text" class="form-control" id="product-description" name="description" placeholder="Apraksts">
                            </div>
                            <div class="form-group mt-2">
                                <label>Cena</label>
                                <input type="text" class="form-control" id="product-price" name="price" placeholder="Cena" required>
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
                                <label>Attēls</label>
                                <input type="file" class="form-control" id="product-image" name="image" placeholder="Attēls" required>
                            </div>
                            <div class="form-group mt-3">
                                <input type="submit" class="btn btn-primary" id="create-product" name="create_product" value="Pievienot">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
