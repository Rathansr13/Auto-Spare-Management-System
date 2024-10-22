<!-- SIDE PART NA SUMMARY -->
        <div class="card-body col-md-3">
        <?php   
        if(!empty($_SESSION['pointofsale'])):  
            
             $total = 0;  
        
             foreach($_SESSION['pointofsale'] as $key => $product): 
        ?>  
        <?php  
                  $total = $total + ($product['quantity'] * $product['price']);
                  $lessvat = ($total / 1.12) * 0.12;
                  $netvat = ($total / 1.12);
                  $addvat = ($total / 1.12) * 0.12;

             endforeach;

//DROPDOWN FOR CUSTOMER
$sql = "SELECT CUST_ID, FIRST_NAME, LAST_NAME
        FROM customer
        order by FIRST_NAME asc";
$res = mysqli_query($db, $sql) or die ("Error SQL: $sql");

$opt = "<select class='form-control'  style='border-radius: 0px;' name='customer' required>
        <option value='' disabled selected hidden>Select Customer</option>";
  while ($row = mysqli_fetch_assoc($res)) {
    $opt .= "<option value='".$row['CUST_ID']."'>".$row['FIRST_NAME'].' '.$row['LAST_NAME']."</option>";
  }
$opt .= "</select>";
// END OF DROP DOWN
        ?>  

          <input type="hidden" name="date" value="<?php echo $today; ?>">
          <div class="form-group row text-left mb-3">
            <div class="col-sm-12 text-primary btn-group">
            <?php echo $opt; ?>
            <a  href="#" data-toggle="modal" data-target="#poscustomerModal" type="button" class="btn btn-primary bg-gradient-primary" style="border-radius: 0px;"><i class="fas fa-fw fa-plus"></i></a>
            </div>

          </div>
          
          
          <div class="form-group row text-left mb-2">

            <div class="col-sm-5 text-primary">
              <h6 class="font-weight-bold py-2">
                Total
              </h6>
            </div>

            <div class="col-sm-7">
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text">Rs.</span>
                </div>
                <input type="text" class="form-control text-right " value="<?php echo number_format($total, 2); ?>" readonly name="total">
              </div>
            </div>

          </div>
<?php endif; ?>       
          <button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#posMODAL">SUBMIT</button>


        <!-- FORM FOR TRANSACTION SUBMISSION -->
<form method="POST" action="pos_transac.php">

<!-- Modal for payment -->
<div class="modal fade" id="posMODAL" tabindex="-1" role="dialog" aria-labelledby="POS" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-body">
        <div class="form-group row text-left mb-2">
          <div class="col-sm-12 text-center">
            <h3 class="font-weight-bold py-3 bg-light">
              Rs. <?php echo number_format($total, 2); ?>
            </h3>
          </div>
        </div>

        <div class="col-sm-12 mb-2">
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <span class="input-group-text">Rs.</span>
            </div>
            <input class="form-control text-right" id="txtNumber" onkeypress="return isNumberKey(event)" type="text" name="cash" placeholder="Enter Amount to pay" required>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        
<!-- Hidden fields to pass necessary data -->
<input type="hidden" name="total" value="<?php echo $total; ?>">
<input type="hidden" name="customer" id="hiddenCustomer" value="">
<input type="hidden" name="date" value="<?php echo date("Y-m-d H:i:s"); ?>">

        <button type="submit" class="btn btn-primary btn-block">PROCEED TO PAYMENT</button>
      </div>
    </div>
  </div>
</div>
</form>

      </div> <!-- END OF CARD BODY -->

     </div>
<script>
  // Listen for changes in the customer dropdown and update the hidden input
  document.querySelector('select[name="customer"]').addEventListener('change', function() {
    document.getElementById('hiddenCustomer').value = this.value;
  });
</script>
