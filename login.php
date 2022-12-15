<?php
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];

    include_once($_SERVER['DOCUMENT_ROOT'] . '/options/connection_database.php');
    $sql = "SELECT * FROM tbl_users WHERE phone_number = '$phone_number' and password = '$password'";
    foreach ($dbh->query($sql) as $row) {
        header("location: welcome.php");
    }
    $error = "Ваш номер телефону чи логін невірні";

}
?>
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
<?php include($_SERVER['DOCUMENT_ROOT'] . '/_header.php'); ?>

<form class="col-md-6 offset-md-3 mt-5" enctype="multipart/form-data" method="post">
    <h3 class="text-center">Вхід</h3>
    <div class="mb-3">
        <label for="phone_number" class="form-label text-nowrap">Номер телефону:</label>
        <input type="text" class="form-control" id="phone_number" name="phone_number">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Пароль:</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
    <?php echo '<div class="text-danger mb-3">'. $error .' </div>';?>

    <button type="submit" class="btn btn-dark w-100">Увійти</button>
</form>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
