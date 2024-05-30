<?php 
/* if(isset($_POST['search'])){
    $category = $_POST['category'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("SELECT * FROM products
                            WHERE product_category = ? AND product_price <= ?");
    $stmt->bind_param('si', $category, $price);
    $stmt->execute();
    $products = $stmt->get_result();

} else{

    //1. noteikt lapas numuru
    if(isset($_GET['page_no']) && $_GET['page_no'] != ""){
        //ja lietotājs jau ir ievadījis lapu, tad lapas numurs ir tas, ko viņš izvēlējās
        $page_no = $_GET['page_no'];
    }else {
        //ja lietotājs tikko ievadīja lapu, noklusējuma lapa ir 1
        $page_no = 1;
    }

    //2. preces atgriešanas numurs
    $stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM products");
    $stmt1->execute();
    $stmt1->bind_result($total_records);
    $stmt1->store_result();
    $stmt1->fetch();

    //3. preces vienā lapā
    $total_records_per_page = 8;

    $offset = ($page_no-1) * $total_records_per_page;

    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;

    $adjacents = "2";

    $total_no_of_pages = ceil($total_records/$total_records_per_page);

    //4. saņemt visas preces

    $stmt2 = $conn->prepare("SELECT * FROM products LIMIT $offset, $total_records_per_page");
    $stmt2->execute();
    $products = $stmt2->get_result();

}*/