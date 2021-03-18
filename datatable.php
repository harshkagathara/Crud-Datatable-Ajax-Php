<?php
include ('db.php');
$query = '';
$output = array();
$query.= "SELECT * FROM product ";
if (isset($_POST["search"]["value"])) {
    $query.= 'WHERE product_name LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query.= 'OR hs_code LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query.= 'OR price LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query.= 'OR unit LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query.= 'OR igst LIKE "%' . $_POST["search"]["value"] . '%" ';
}
if (isset($_POST["order"])) {
    $query.= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query.= 'ORDER BY id DESC ';
}
if ($_POST["length"] != - 1) {
    $query.= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}
$result = mysqli_query($conn, $query);
$data = array();
$i = 1 ;
foreach ($result as $row) {
    $image = '';
    if ($row["product_image"] != '') {
        $image = '<img src="upload/' . $row["product_image"] . '" style="max-width: 48px; height: 35px;" />';
    } else {
        $image = '';
    }
    $sub_array = array();
    $sub_array[] = $i;
    $sub_array[] = $image;
    $sub_array[] = $row["product_name"];
    $sub_array[] = $row["hs_code"];
    $sub_array[] = $row["price"];
    $sub_array[] = $row["unit"];
    $sub_array[] = $row["igst"] . '<spam>%</spam>';
    $sub_array[] = '<button type="button" name="update" id="' . $row["id"] . '" class="btn btn-success btn-xs update"><i class="fa fa-pencil" aria-hidden="true"></i></button> <button type="button" name="delete" id="' . $row["id"] . '"class="btn btn-danger btn-xs delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
    $sub_array[] = '';
    $data[] = $sub_array;
    $i++;
}
$output = array("draw" => intval($_POST["draw"]),
//	"recordsTotal"		=> 	$filtered_rows,
"recordsFiltered" => get_total_all_records(), "data" => $data);
echo json_encode($output);
function get_total_all_records() {
    include ('db.php');
    $sql = "SELECT * FROM product";
    $result = mysqli_query($conn, $sql);
    return mysqli_num_rows($result);
}
?>