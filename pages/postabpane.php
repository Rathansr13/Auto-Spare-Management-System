<?php
include '../includes/connection.php';


if(isset($_POST['category_id'])){
  $category_id = $_POST['category_id'];
}
else{
  $category_id = 1;
}
$query = "SELECT PRODUCT_CODE, MAX(PRODUCT_ID) AS PRODUCT_ID, MAX(NAME) AS NAME, MAX(PRICE) AS PRICE 
          FROM product 
          WHERE CATEGORY_ID = ? 
          GROUP BY PRODUCT_CODE 
          ORDER BY PRODUCT_CODE ASC";

// Prepare the statement
$stmt = $db->prepare($query);

if ($stmt === false) {
    die('Error in query preparation: ' . $db->error);
}

// Bind the parameter to the prepared statement (in this case, category ID)
$stmt->bind_param('i', $category_id);

// Execute the query
$stmt->execute();

// Get the result set from the executed query
$result = $stmt->get_result();

if ($result === false) {
    die('Error in query execution: ' . $stmt->error);
}

// Fetch the data and output the products
if ($result->num_rows > 0) {
    while ($product = $result->fetch_assoc()) {
        ?>
        <div class="col-sm-4 col-md-2">
            <form method="post" action="pos.php?action=add&id=<?php echo $product['PRODUCT_ID']; ?>">
                <div class="products">
                    <h6 class="text-info"><?php echo $product['NAME']; ?></h6>
                    <h6>Rs. <?php echo $product['PRICE']; ?></h6>
                    <input type="text" name="quantity" class="form-control" value="1" />
                    <input type="hidden" name="name" value="<?php echo $product['NAME']; ?>" />
                    <input type="hidden" name="price" value="<?php echo $product['PRICE']; ?>" />
                    <input type="submit" name="addpos" style="margin-top:5px;" class="btn btn-info" value="Add" />
                </div>
            </form>
        </div>
        <?php
    }
} else {
    echo "No products found.";
}

?>
