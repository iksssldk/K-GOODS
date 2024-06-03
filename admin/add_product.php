<?php include('header.php'); ?>

<?php 

include('../server/connection.php');

if(!isset($_SESSION['logged_in'])){
    header('location: ../login.php');
    exit;
}

// Fetch categories from database
$categories = [];
$stmt = $conn->prepare("SELECT category_id, category_name FROM categories");
if ($stmt->execute()) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

if(isset($_POST['create_product'])){
    $product_name = $_POST['name'];
    $product_description = $_POST['description'];
    $product_price = $_POST['price'];
    $product_color = $_POST['color'];
    $category_id = $_POST['category'];

    $image = $_FILES['image']['tmp_name'];
    $unique_id = uniqid();
    $image_name = $product_name.".jpeg";
    $image_name = $product_name . "_" . $unique_id . ".jpeg";
    //augšupielādēt attēlu
    move_uploaded_file($image,"../assets/imgs/".$image_name);

    //pievienot jaunu preci
    $stmt = $conn->prepare("INSERT INTO products (category_id, product_name, product_description, product_price, product_color, product_category, product_image)
                            VALUES (?,?,?,?,?,?) ");
    $stmt->bind_param('issssss', $category_id, $product_name, $product_description, $product_price, $product_color, $product_category, $image_name);
    
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
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['category_id']; ?>">
                                        <?php echo htmlspecialchars($category['category_name']); ?>
                                    </option>
                                <?php endforeach; ?>
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
