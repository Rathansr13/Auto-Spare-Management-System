<?php
include '../includes/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    
    $date = $_POST['date'];
    $customer = $_POST['customer']; 
    $total = $_POST['total'];
    $cash = $_POST['cash'];

   
    if ($customer == 0) {
        die("Error: No customer selected.");
    }

   
    $transaction_id = date("mdGis");

  
    $lessvat = ($total / 1.12) * 0.12;
    $netvat = ($total / 1.12);
    $addvat = ($total / 1.12) * 0.12;
    $countID = count($_SESSION['pointofsale']); // Number of items
    $emp = "hrushikesh";
    $role = "cashier";

    if (!empty($_SESSION['pointofsale']))
     {
      foreach ($_SESSION['pointofsale'] as $key => $product) {
          $product_name = $product['name'];
          $quantity = $product['quantity'];
          $price = $product['price'];

          $details_query = "INSERT INTO `transaction_details`
                            (`ID`, `TRANS_D_ID`, `PRODUCTS`, `QTY`, `PRICE`, `EMPLOYEE`, `ROLE`)
                            VALUES (NULL, '$transaction_id', '$product_name', '$quantity', '$price', '$emp', '$role')";

          mysqli_query($db, $details_query) or die('Error inserting details: ' . mysqli_error($db));
      }
    }
    
    $transaction_query = "INSERT INTO `transaction`
                          (`TRANS_ID`, `CUST_ID`, `NUMOFITEMS`, `SUBTOTAL`, `LESSVAT`, `NETVAT`, `ADDVAT`, `GRANDTOTAL`, `CASH`, `DATE`, `TRANS_D_ID`)
                          VALUES ('$transaction_id', '$customer', '$countID', '$netvat', '$lessvat', '$netvat', '$addvat', '$total', '$cash', '$date', '$transaction_id')";

    if (mysqli_query($db, $transaction_query)) {
    
        unset($_SESSION['pointofsale']);

        echo "<script>
                alert('Transaction Successful.');
                window.location.href = 'pos.php';
              </script>";
    } else {
        echo "Error inserting transaction: " . mysqli_error($db);
    }
}
?>
