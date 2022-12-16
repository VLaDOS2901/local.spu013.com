<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Головна сторінка</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php

include($_SERVER['DOCUMENT_ROOT'] . '/_header.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/options/connection_database.php');
?>


<h1 class="text-center">Головна сторінка</h1>

<section style="background-color: #eee;">
    <div class="container py-5">
        <div class="row">
            <?php
            session_start();

            $sql = "SELECT id,name,image,price FROM tbl_products;";
            if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST["delete"])) {
                $del_id = $_POST["delete"];
                include_once($_SERVER['DOCUMENT_ROOT'] . '/options/connection_database.php');
                $sql2 = "DELETE FROM tbl_products WHERE id = '$del_id'";
                $dbh->query($sql2);
            }

            if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST["details"])) {
                $_SESSION['Id'] = $_POST["details"];
                header("location: details.php");
            }
            foreach ($dbh->query($sql) as $row) :?>
                <?php
                $id = $row['id'];
                $name = $row['name'];
                $image = $row['image'];
                $image = rtrim($image);
                $images = explode(" ", $image);
                $price = $row['price'];
                ?>

                <div class="col-md-6 col-lg-4 mb-4 mb-md-0 mt-3">
                    <div class="card">
                        <!--                    <div class="d-flex flex-wrap">-->

                        <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">

                            <div class="carousel-inner">
                                <?php
                                $counter = 0;
                                foreach ($images as $img){
                                if ($counter == 0) {
                                    echo '<div class="carousel-item active">
                                    <img src="images/'.$img.'" class="d-block w-100" alt="pizza">
                                </div>';
                                }
                                else{
                                    echo '<div class="carousel-item">
                                    <img src="images/'.$img.'" class="d-block w-100" alt="pizza">
                                </div>';
                                }
                                $counter++;
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                        echo '<div class="card-body">

                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="mb-0">' . $name . '</h5>
                            <h5 class="text-dark mb-0">' . $price . '</h5>
                        </div>

                        <div class="mb-2 text-end">
                        <form method="post">
                            <button type="button" class="btn btn-success">Купити</button>
                            <button type="submit" class="btn btn-success" name="details" value=' . $id . '>Детально</button>
                            <button type="submit" class="btn btn-danger" name="delete" value=' . $id . '>Видалити</button>
                        </form>
                        </div>
                    </div>';
                        ?>

                    </div>
                </div>


            <?php endforeach; ?>
        </div>
    </div>
</section>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
