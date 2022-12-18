<?php
$error = "";
//Створення нового користувача
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $phone_number = $_POST['phone_number'];
        $country = $_POST['country'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $image = $_FILES['image']['tmp_name'];
        $dir_save = 'images/';
        $image_name = uniqid() . '.jpg';
        $uploadfile = $dir_save . $image_name;
        if (move_uploaded_file($image, $uploadfile)) {

            include_once($_SERVER['DOCUMENT_ROOT'] . '/options/connection_database.php');
            $sql = 'INSERT INTO tbl_users (name, surname, image, phone_number, country, email, password) VALUES (:name, :surname, :image, :phone_number, :country, :email, :password);';
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':surname', $surname);
            $stmt->bindParam(':phone_number', $phone_number);
            $stmt->bindParam(':image', $image_name);
            $stmt->bindParam(':country', $country);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            header("Location: welcome.php");
            exit();
        }
    } catch
    (ErrorException $e) {

    };
    $error = "Заповніть повністю всі поля";
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
<!--Підключення верхнього меню-->
<?php include($_SERVER['DOCUMENT_ROOT'] . '/_header.php'); ?>


<form class="col-md-6 offset-md-3 mt-5" enctype="multipart/form-data" method="post">
    <h1 class="text-center">Реєстрація на сайті</h1>
    <div class="mb-3">
        <label for="name" class="form-label">Ім'я</label>
        <input type="text" class="form-control" id="name" name="name">
    </div>
    <div class="mb-3">
        <label for="surname" class="form-label">Прізвище</label>
        <input type="text" class="form-control" id="surname" name="surname">
    </div>
    <div class="mb-3">
        <label for="phone_number" class="form-label text-nowrap">Номер телефону</label>
        <input type="text" class="form-control" id="phone_number" name="phone_number">
    </div>
    <div class="mb-3">
        <label for="country" class="form-label">Країна</label>
        <input type="text" class="form-control" id="country" name="country">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="text" class="form-control" id="email" name="email">
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Фото</label>
        <input type="file" class="form-control" id="image" name="image">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Пароль</label>
        <input type="text" class="form-control" id="password" name="password">
    </div>
    <?php echo '<div class="text-danger mb-3">' . $error . ' </div>'; ?>
    <button type="submit" class="btn btn-primary">Додати</button>
</form>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
