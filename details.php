<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Деталі</title>
</head>
<body>
<div class="m-auto d-flex flex-column">
    <?php
    include($_SERVER['DOCUMENT_ROOT'] . '/_header.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/options/connection_database.php');

    session_start();
    $Id = $_SESSION['Id'];
    $sql = "SELECT id,name,image,price,description FROM tbl_products WHERE id=$Id;";
    $id = "";
    $name = "";
    $image = "";
    $images = "";
    $price = "";
    $description = "";
    foreach ($dbh->query($sql) as $row) {
        $id = $row['id'];
        $name = $row['name'];
        $image = $row['image'];
        $image = rtrim($image);
        $images = explode(" ", $image);
        $price = $row['price'];
        $description = $row['description'];
    }
    $row = $dbh->query($sql);


    ?>

    <div class="col-md-6 col-lg-4 mb-4 mb-md-0 mt-5 m-auto">
        <div>

            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <?php
                    $counter = 0;
                    foreach ($images as $img) {
                        if ($counter == 0) {
                            echo '<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to=' . $counter . '
                                class="active" aria-current="true" aria-label="Slide 1"></button>';
                        } else {
                            echo '<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to=' . $counter . ' aria-label="Slide 1"></button>';
                        }
                        $counter++;
                    }
                    ?>

                </div>
                <div class="carousel-inner">

                    <?php
                    $counter2 = 0;
                    foreach ($images as $img) {
                        if ($counter2 == 0) {
                            echo '<div class="carousel-item active">
                                               <img src="images/' . $img . '" class="d-block w-100" alt="pizza">
                                          </div>';
                        } else {
                            echo '<div class="carousel-item">
                                                <img src="images/' . $img . '" class="d-block w-100" alt="pizza">
                                          </div>';
                        }
                        $counter2++;
                    }
                    ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                        data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                        data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>


        </div>
        <?php
        echo '<div>
                      <h5 class="mb-0 mt-3">' . $name . '</h5>
                      <hr>
                      <h5 class="text-dark mb-0">' . $price . '</h5>   
                      <hr> 
                      <p>' . $description . '</p>       
                      <hr>         
                    </div>';
        ?>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
