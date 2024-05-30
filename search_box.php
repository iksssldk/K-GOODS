<?php

include('server/connection.php');

if (isset($_POST['action']) && $_POST['action'] == 'searchRecord'){
    $product_name = $_POST['product_name'];

    if (!empty($product_name)){
        $stmt = $conn->prepare("SELECT * FROM products
                                WHERE LOWER (product_name) LIKE LOWER (?)
                                ORDER BY product_id DESC ");
        $search_term = "%$product_name%";
        $stmt->bind_param('s', $search_term);
        $stmt->execute();
        $result = $stmt->get_result();

        getData($result);
    } else {
        echo "<p>Lūdzu, ievadiet preces nosaukumu</p>";
    }
}

function getData($result){
    $output = "";
    if ($result->num_rows > 0 ){
        $output .= '<section id="index"> <div class="cards">';

        while ($row = $result->fetch_assoc()){

            $output .= '
                <div class="card">
                    <div class="card_top">
                        <a href="#" class="card_image">
                            <img src="assets/imgs/'.$row['product_image'].'"/>
                        </a>
                        <!-- <div class="card__label">-10%</div> -->
                        </div>
                        <div class="card_bottom">
                            <h5 class="card_title">'.$row['product_name'].'</h5>
                            <div class="card_prices">
                        <!-- <div class="card__price card__price--discount">135 000</div> -->
                            <div class="card_price card_price--common">'.$row['product_price'].'</div>
                        </div>
                        <a href="single_product.php?product_id='.$row['product_id'].'" style="text-decoration: none;">
                            <button class="card_add"> Pievienot grozā</button>
                        </a>
                    </div>
                </div>
            ';
        }
        $output .= '</section> </div>';
    } else {

        $output = "<div class='no-results'><p>Prece nav atrasta</p></div>";
    }

    echo $output;

}