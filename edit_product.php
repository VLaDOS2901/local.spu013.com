<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/options/connection_database.php');
$id = $_GET['id'];
$name = '';
$price = '';
$description = '';
//Знаходить данні про продукт
$sql = '
        SELECT id,name,price,description
        FROM tbl_products 
        WHERE id=:id;
';
$stm = $dbh->prepare($sql);
$stm->execute([':id' => $id]);
if ($row = $stm->fetch()) {
    $name = $row['name'];
    $price = $row['price'];
    $description = $row['description'];
}
//Знаходить картинки, які звязані з цим продуктом
$sql = '
        SELECT pi.name
        FROM tbl_product_images pi 
        WHERE pi.product_id=:id
        ORDER BY pi.priority;
';
$stm = $dbh->prepare($sql);
$stm->execute([':id' => $id]);
$prev_images = $stm->fetchAll();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql1 = "DELETE FROM `tbl_product_images` WHERE product_id = '$id'";
    $dbh->query($sql1);

    echo "POST REQUEST SERVER";
    //Оголошення змінних
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    include_once($_SERVER['DOCUMENT_ROOT'] . '/options/connection_database.php');
    $sql = "UPDATE tbl_products SET name='$name',price='$price',description='$description' WHERE id='$id';";
    $dbh->query($sql);

    $images = $_POST['images'];
    $count = 0;

    foreach ($images as $base64) {
        $count++;
        $dir_save = 'images/';
        $image_name = uniqid() . '.jpeg';
        $uploadfile = $dir_save . $image_name;
        list(, $data) = explode(',', $base64);
        $data = base64_decode($data);
        file_put_contents($uploadfile, $data);
        $sql = 'INSERT INTO tbl_product_images (name, datecreate, priority, product_id) VALUES(:name, NOW(), :priority, :product_id);';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':name', $image_name);
        $stmt->bindParam(':priority', $count);
        $stmt->bindParam(':product_id', $id);
        $stmt->execute();

    }
    //Переадресація на головну сторінку
    header("Location: /");
    exit();

} ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Головна сторінка</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
//Підключення верхнього меню
include($_SERVER['DOCUMENT_ROOT'] . '/_header.php');
?>
<h1 class="text-center">Редагувати продукт</h1>
<form class="col-md-6 offset-md-3" enctype="multipart/form-data" method="post">
    <div class="mb-3">
        <label for="name" class="form-label">Назва</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>">
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Ціна</label>
        <input type="text" class="form-control" id="price" name="price" value="<?php echo $price; ?>">
    </div>
    <div class="mb-3">
        <div class="container">
            <div class="row" id="list_images">

            <?php $counter = 0;
                foreach ($prev_images as $image) {
                $img_id = uniqid();
                $path = 'images/'.$prev_images[$counter]['name'].'';
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                echo '
                <div class="col-md-3 item-image m-1">
                <div class="row d-flex justify-content-between">
                    <div class="col-6">
                        <div class="fs-4 ms-2">
                            <label for=' . $img_id . '>
                                <i class="fa fa-pencil" style="cursor: pointer;" aria-hidden="true"></i>
                            </label>
                            <input type="file" class="form-control d-none edit" id=' . $img_id . '>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end fs-4 text-danger me-2 remove">
                            <i class="fa fa-times" style="cursor: pointer" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <div>
                    <img src=' . $base64 . ' id="' . $img_id . '_image" width="100%">
                    <input type="hidden" id="' . $img_id . '_file" value=' . $base64 . ' name="images[]">
                </div>
                </div>
                '; $counter++;}?>


                <div class="col-md-3" id="selectImages">
                    <label for="image" style="cursor: pointer" class="form-label text-success">
                        <i class="fa fa-plus" style="font-size: 120px;" aria-hidden="true"></i>
                    </label>
                    <input type="file"
                           class="form-control d-none"
                           id="image" multiple

                    >
                </div>
            </div>
        </div>

    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Опис</label>
        <input type="text" class="form-control" id="description" name="description" value="<?php echo $description; ?>">
    </div>

    <button type="submit" class="btn btn-primary">Зберегти</button>
</form>

<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/jquery-3.6.2.min.js"></script>
<script>
    //Генерація унікального Id
    function uuidv4() {
        return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
            (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
        );
    }

    $(function () {
        const image = document.getElementById('image');
        //Показати вибрані зображення
        image.onchange = function (e) {
            const files = e.target.files;
            for (let i = 0; i < files.length; i++) {
                // Оголосили reader
                const reader = new FileReader();
                //Підключили подію
                reader.addEventListener('load', function () {
                    const base64 = reader.result;
                    console.log(base64);
                    //console.log(base64);
                    const id = uuidv4();
                    const data = `
                        <div class="row">
                            <div class="col-6">
                                <div class="fs-4 ms-2">
                                    <label for="${id}">
                                        <i class="fa fa-pencil" style="cursor: pointer;" aria-hidden="true"></i>
                                    </label>
                                    <input type="file" class="form-control d-none edit" id="${id}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-end fs-4 text-danger me-2 remove">
                                    <i class="fa fa-times" style="cursor: pointer" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                <div>
                    <img src="${base64}" id="${id}_image" width="100%">
                    <input type="hidden" id="${id}_file" value="${base64}" name="images[]">
                </div>
                `;
                    const item = document.createElement('div');
                    item.className = 'col-md-3 item-image m-1';
                    item.innerHTML = data;
                    $("#selectImages").before(item);

                });
                const file = files[i];
                // Передача файлу в reader
                reader.readAsDataURL(file);
            }
            image.value = "";
        }

        //Видалити зображення
        $("#list_images").on("click", '.remove', function () {
            $(this).closest(".item-image").remove();
        });
        //Змінити зображення в списку
        let edit_id = 0;
        const reader = new FileReader();
        reader.addEventListener("load", () => {
            const base64 = reader.result;
            document.getElementById(`${edit_id}_image`).src = base64;
            document.getElementById(`${edit_id}_file`).value = base64;
        });
        $("#list_images").on("change", '.edit', function (e) {
            edit_id = e.target.id;
            const file = e.target.files[0];
            reader.readAsDataURL(file);
            this.value = "";
        });

    });
</script>
</body>
</html>