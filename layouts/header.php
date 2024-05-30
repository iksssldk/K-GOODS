<?php

ob_start();

$current_page = basename($_SERVER['PHP_SELF']);
$excluded_pages = array('login.php','register.php','order_details.php','checkout.php','payment.php','account.php', 'cart.php');

$show_search_form = !in_array($current_page, $excluded_pages);

?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets\css\style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <header>
        <div>
            <img id="logo" src="assets/imgs/logo.png" alt="Logo">
        </div>
    </header>

    <!--Navbar-->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Sākumlapa</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Apģērbs</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="suits.php">Uzstāšanās kostīmi</a></li>
                            <li><a class="dropdown-item" href="shirts.php">T-krekli</a></li>
                            <li><a class="dropdown-item" href="pants.php">Bikses</a></li>
                            <li><a class="dropdown-item" href="shoes.php">Apavi</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Sporta ieroči</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="sword.php">Zobeni</a></li>
                            <li><a class="dropdown-item" href="exotic_weapons.php">Eksotiskie ieroči</a></li>
                            <li><a class="dropdown-item" href="spear.php">Šķēpi</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Aprīkojums</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="carpets.php">Paklāji</a></li>
                            <li><a class="dropdown-item" href="decorations.php">Dekorācijas</a></li>
                            <li><a class="dropdown-item" href="additional.php">Papilds</a></li>
                        </ul>
                    </li>
                </ul>

                <?php if ($show_search_form) { ?>
                <form class="d-flex" role="search" id="searchForm" method="POST">
                    <input class="form-control me-2" type="search" id="product_name" placeholder="Meklēt..." aria-label="Search">
                    <button class="btn btn-outline-success" id="searchButton" type="submit">Meklēt</button>
                </form>
                <?php } ?>

                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
                            <i class="fas fa-shopping-cart"></i>
                            <?php if(isset($_SESSION['quantity']) && $_SESSION['quantity'] != 0 ){ ?>
                                <span class="badge bg-danger"><?php echo $_SESSION['quantity']; ?></span>
                            <?php } ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="account.php">
                            <i class="fas fa-user"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

  <script>
      $(document).ready(function(){
          // Обработчик для отправки формы поиска
          $(document).on('submit', '#searchForm', function(event){
              event.preventDefault();
              var product_name = $('#product_name').val();
              if (product_name){
                  $.ajax({
                      url: "search_box.php",
                      method: "POST",
                      data: { action: 'searchRecord', product_name: product_name },
                      success: function(data){
                          $('#index').html(data);
                
                      }
                  });
              }
          });
      });
  </script>
  <?php ob_end_flush(); ?>