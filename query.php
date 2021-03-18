<?php
include ('db.php');
if (isset($_POST["operation"])) {
    if ($_POST["operation"] == "Add") {
        $image = '';
        if ($_FILES["product_image"]["name"] != '') {
            $image = upload_image();
        }
        $product_name = $_POST["product_name"];
        $hs_code = $_POST["hs_code"];
        $price = $_POST["price"];
        $unit = $_POST["unit"];
        $igst = $_POST["igst"];
        $description = $_POST["description"];
        $image = $image;
        $statement = " INSERT INTO `product`( `product_name`, `hs_code`, `price`, `unit`, `igst`, `product_image`, `description`) VALUES 
	('$product_name','$hs_code','$price','$unit','$igst','$image','$description')";

    echo $statement;
        if (mysqli_query($conn, $statement)) {
            echo "New record created successfully";
        }
    }
    if ($_POST["operation"] == "Edit") {
        $image = '';
        if ($_FILES["product_image"]["name"] != '') {
            $image = upload_image();
        } else {
            $image = $_POST["hidden_user_image"];
        }
        $product_name = $_POST["product_name"];
        $hs_code = $_POST["hs_code"];
        $price = $_POST["price"];
        $unit = $_POST["unit"];
        $igst = $_POST["igst"];
        $description = $_POST["description"];
        $image = $image;
        $id = $_POST['user_id'];
        $statement = "UPDATE product 
			SET product_name = '$product_name', hs_code ='$hs_code',price ='$price',
			unit='$unit',igst='$igst',description='$description',product_image='$image'
			WHERE id = $id ";
        if (mysqli_query($conn, $statement)) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    }
    if ($_POST["operation"] == "fetch_single") {
        if (isset($_POST["user_id"])) {
            $output = array();
            $sql = "SELECT * FROM product 
			WHERE id = '" . $_POST["user_id"] . "' 
			LIMIT 1";
            $result = mysqli_query($conn, $sql);
            foreach ($result as $row) {
                $output["product_name"] = $row["product_name"];
                $output["hs_code"] = $row["hs_code"];
                $output["price"] = $row["price"];
                $output["unit"] = $row["unit"];
                $output["igst"] = $row["igst"];
                $output["description"] = $row["description"];
                if ($row["product_image"] != '') {
                    $output['product_image'] = '<img src="upload/' . $row["product_image"] . '" class="img-thumbnail" width="50" height="35" /><input type="hidden" name="hidden_user_image" value="' . $row["product_image"] . '" />';
                } else {
                    $output['product_image'] = '<input type="hidden" name="hidden_user_image" value="" />';
                }
            }
            echo json_encode($output);
        }
    }
    if ($_POST["operation"] == "Delete") {
        if (isset($_POST["user_id"])) {
            $image = get_image_name($_POST["user_id"]);
            if ($image != '') {
                unlink("upload/" . $image);
            }
            $id = $_POST["user_id"];
            $sql = "DELETE FROM product WHERE id = $id";
            if (mysqli_query($conn, $sql)) {
                echo "Record deleted successfully";
            } else {
                echo "Error deleting record: " . mysqli_error($conn);
            }
        }
    }
}
function upload_image() {
    if (isset($_FILES["product_image"])) {
        $extension = explode('.', $_FILES['product_image']['name']);
        $new_name = rand() . '.' . $extension[1];
        $destination = './upload/' . $new_name;
        move_uploaded_file($_FILES['product_image']['tmp_name'], $destination);
        return $new_name;
    }
}
function get_image_name($user_id) {
    include ('db.php');
    $statement = "SELECT image FROM product WHERE id = '$user_id'";
    $result = mysqli_query($conn, $statement);
    foreach ($result as $row) {
        return $row["image"];
    }
}
