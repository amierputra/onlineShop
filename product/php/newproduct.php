<?php

if (isset($_POST["submit"])) {
    include_once("dbconnect.php");
    $name = $_POST["name"];
    $number = $_POST["number"];
    $description = addslashes($_POST["description"]);
    $ingredients = addslashes($_POST["ingredients"]);
    $price = $_POST["price"];

    $sqlinsert = "INSERT INTO `tbl_product`(`prod_name`, `prod_num`, `prod_desc`, `prod_ing`, `prod_price`) VALUES ('$name', '$number', '$description', '$ingredients', '$price')";

    try {
        $conn->exec($sqlinsert);
        if (file_exists($_FILES["fileToUpload"]["tmp_name"]) || is_uploaded_file($_FILES["fileToUpload"]["tmp_name"])) {
            uploadImage($number);
        }
        echo "<script>alert('Registration successful')</script>";
        echo "<script>window.location.replace('index.php')</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Registration failed')</script>";
        echo "<script>window.location.replace('newproduct.php')</script>";
    }
}

function uploadImage($id)
{
    $target_dir = "../images/products/";
    $target_file = $target_dir . $id . ".jpg";
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
}

?>


<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="preconnect" href="https://fonts.googleapis.com/css?family=Montserrat|Poppins|Roboto">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <script src="../jscript/script.js"></script>
    <title>Add Product</title>
</head>

<style>
    @media screen and (min-width: 601px) {
        .w3-resize {
            max-width: 800px;
            margin: auto;
        }
    }
</style>

<body>

    <div class="w3-padding-24">
        <div class="w3-bar w3-bottombar w3-cell-middle w3-top w3-white w3-border-red w3-padding-12">
            <a href="newproduct.php" class="w3-bar-item w3-large w3-wide pointer" style="font-family: Poppins; text-decoration:none;"><b>HOME</b></a>
            <a href="adminlog.php" class="w3-bar-item w3-large w3-right " style="font-family: Poppins; text-decoration:none;">Login</a>
            <a href="adminreg.php" class="w3-bar-item w3-large w3-right " style="font-family: Poppins; text-decoration:none;">New Admin</a>
        </div>
    </div>

    <header class="w3-container w3-center w3-padding-32 w3-white">
        <h1 class="font"><b>TEMYRACLE</b></h1>
        <h4 class="w3-large">Invest your skin a little <span class="w3-tag w3-red">Love!</span></h4>
    </header>

    <div class="w3-padding-32 w3-container w3-white">
        <div class="w3-resize">
            <form class="w3-container w3-pale-red w3-border w3-padding-24 w3-round-large formco" name="insertForm" action="newproduct.php" method="post" onsubmit="return confirmDialog()" enctype="multipart/form-data">
                <div class="w3-margin-left w3-margin-right">
                    <h3 class="w3-center" style="font-family: Montserrat; font-weight: 600; color: white; background-color: red">
                        NEW PRODUCT
                    </h3>
                    <div class="w3-container w3-border w3-center w3-padding">
                        <img class="w3-image w3-round w3-margin" src="../images/addimages.jpg" style="height:100%;width:100%;max-width:100px"><br>
                        <input type="file" onchange="previewFile()" name="fileToUpload" id="fileToUpload"><br>
                    </div>
                    <p>
                        <label class="w3-text-black">Product Name</label>
                        <input class="w3-input w3-white w3-border w3-border-red w3-round-large" name="name" id="pname" type="text" required>
                    </p>
                    <p>
                        <label class="w3-text-black">Product Number</label>
                        <input class="w3-input w3-white w3-border w3-border-red w3-round-large" name="number" id="pnumber" type="text" required>
                    </p>
                    <p>
                        <label class="w3-text-black">Description</label>
                        <textarea class="w3-input w3-white w3-border w3-border-red w3-round-large" name="description" id="pdesecription" rows="4" cols="50" width="100%" placeholder="Product Description" required></textarea>
                    </p>
                    <p>
                        <label class="w3-text-black">Ingredients</label>
                        <textarea class="w3-input w3-white w3-border w3-border-red w3-round-large" name="ingredients" id="pingredients" rows="4" cols="50" width="100%" placeholder="Ingredients" required></textarea>
                    </p>
                    <p>
                        <label class="w3-text-black">Price</label>
                        <input class="w3-input w3-white w3-border w3-border-red w3-round-large" name="price" id="pprice" type="number" step="any" required>
                    </p>
                    <br>
                    <div class="w3-center">
                        <div class="btn">
                            <button class="pointer w3-input w3-text-white w3-border w3-border-red w3-block w3-round w3-red w3-hover-yellow" name="submit">SUBMIT</button>
                        </div>
                    </div>
                </div>
            </form> 
        </div>
    </div>

</body>

<footer class="w3-center w3-padding w3-red">
    <p>
        &copy; Designed by
        <a href="https://amierputra.work" style="color: yellow; text-decoration:none;">Amier Putra</a>.<br>All Rights Reserved.
    </p>
</footer>

</html>
