<?php
// Merchant key here as provided by Payu
$MERCHANT_KEY = "giBEf6";

// Merchant Salt as provided by Payu
$SALT = "cmWiHtcw";

// End point - change to https://secure.payu.in for LIVE mode
$PAYU_BASE_URL = "https://test.payu.in";

$action = '';

$posted = array();
if(!empty($_POST)) {
    //print_r($_POST);
  foreach($_POST as $key => $value) {
     
    $posted[$key] = htmlentities($value, ENT_QUOTES);
  }
}
/*foreach ($posted as $key => $value) {
    echo "posted[".$key."]=".$value."<br>";
}*/
//echo $posted;
$formError = 0;

if(empty($posted['txnid'])) {
  // Generate random transaction id
  $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
} else {
  $txnid = $posted['txnid'];
}

$hash = '';
// Hash Sequence
$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
if(empty($posted['hash']) && sizeof($posted) > 0) {
    
  if(
          empty($posted['key'])
          || empty($posted['txnid'])
          || empty($posted['amount'])
          || empty($posted['firstname'])
          || empty($posted['email'])
          || empty($posted['phone'])
          || empty($posted['productinfo'])
          || empty($posted['surl'])
          || empty($posted['furl'])
          || empty($posted['pg'])
  ) {
    $formError = 1;
  } 
  elseif(($posted['pg']=='NB') && empty($posted['bankcode']))
  {
     $formError = 1; 
  }
  elseif((($posted['pg']=='CC')||($posted['pg']=='DC')) && (empty($posted['bankcode'])||empty($posted['ccnum'])||empty($posted['ccvv'])||empty($posted['ccexpmon'])||empty($posted['ccname'])||empty($posted['ccexpyr'])))
  {
     $formError = 1; 
  }
  else {
    $hashVarsSeq = explode('|', $hashSequence);
    $hash_string = '';
    foreach($hashVarsSeq as $hash_var) {
      $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
      $hash_string .= '|';
    }
    $hash_string .= $SALT;
    $hash = strtolower(hash('sha512', $hash_string));
    $action = $PAYU_BASE_URL . '/_payment';
  }
} elseif(!empty($posted['hash'])) {
  $hash = $posted['hash'];
  $action = $PAYU_BASE_URL . '/_payment';
}
?>

<html>
  <script>
    var hash = '<?php echo $hash ?>';
    function submitPayuForm() {
      if(hash == '') {
        return;
      }
      var payuForm = document.forms.payuForm;
      payuForm.submit();
    }
  </script>
  <body onload="submitPayuForm();" >
    <h2>PayU Form</h2>
    <br/>
    <?php if($formError) { ?>
      <span style="color:red">Please fill all mandatory fields.</span>
      <br/>
      <br/>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" name="payuForm">
      <input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />
      <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
      <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />
      <table>
        <tr>
          <td><b>Mandatory Parameters</b></td>
        </tr>
        <tr>
          <td>Amount: </td>
          <td><input name="amount" value="<?php echo (empty($posted['amount'])) ? '' : $posted['amount'] ?>" /></td>
          <td>First Name: </td>
          <td><input name="firstname" id="firstname" value="<?php echo (empty($posted['firstname'])) ? '' : $posted['firstname']; ?>" /></td>
        </tr>
        <tr>
          <td>Email: </td>
          <td><input name="email" id="email" value="<?php echo (empty($posted['email'])) ? '' : $posted['email']; ?>" /></td>
          <td>Phone: </td>
          <td><input name="phone" value="<?php echo (empty($posted['phone'])) ? '' : $posted['phone']; ?>" /></td>
        </tr>
        <tr>
          <td>Product Info: </td>
          <td colspan="3"><input name="productinfo" value="<?php echo (empty($posted['productinfo'])) ? '' : $posted['productinfo'] ?>" size="64" /></td>
        </tr>
        <tr>
          <td>Success URI: </td>
          <td colspan="3"><input name="surl" value="<?php echo (empty($posted['surl'])) ? '' : $posted['surl'] ?>" size="64" /></td>
        </tr>
        <tr>
          <td>Failure URI: </td>
          <td colspan="3"><input name="furl" value="<?php echo (empty($posted['furl'])) ? '' : $posted['furl'] ?>" size="64" /></td>
        </tr>
        <tr>
          <td>bankcode: </td>
          <td><input name="bankcode" value="<?php echo (empty($posted['bankcode'])) ? '' : $posted['bankcode']; ?>" /></td>
        </tr>
        <tr>
            <td>PG: </td>
          <td><input name="pg" value="<?php echo (empty($posted['pg'])) ? '' : $posted['pg']; ?>" /></td>
        </tr>
         <tr>
          <td>CCNAME: </td>
          <td><input name="ccname" value="<?php echo (empty($posted['ccname'])) ? '' : $posted['ccname']; ?>" /> -- Not to be used if bankcode is "NB"</td>
        </tr>
         <tr>
            <td>CCNUM: </td>
          <td><input name="ccnum" value="<?php echo (empty($posted['ccnum'])) ? '' : $posted['ccnum']; ?>" /> -- Not to be used if bankcode is "NB"</td>
        </tr>
        <tr>
            <td>CCVV: </td>
          <td><input name="ccvv" value="<?php echo (empty($posted['ccvv'])) ? '' : $posted['ccvv']; ?>" /> -- Not to be used if bankcode is "NB"</td>
        </tr>
        <tr>
            <td>CCEXPMON: </td>
          <td><input name="ccexpmon" value="<?php echo (empty($posted['ccexpmon'])) ? '' : $posted['ccexpmon']; ?>" /> -- Not to be used if bankcode is "NB"</td>
        </tr>
        
        <tr>
            <td>CCEXPYR: </td>
          <td><input name="ccexpyr" value="<?php echo (empty($posted['ccexpyr'])) ? '' : $posted['ccexpyr']; ?>" /> -- Not to be used if bankcode is "NB"</td>
        </tr>
        <tr>
         <!-- <td><b>Optional Parameters</b></td>
        </tr>
        <tr>
          <td>Last Name: </td>
          <td><input name="lastname" id="lastname" value="<?php echo (empty($posted['lastname'])) ? '' : $posted['lastname']; ?>" /></td>
          <td>Cancel URI: </td>
          <td><input name="curl" value="" /></td>
        </tr>
        <tr>
          <td>Address1: </td>
          <td><input name="address1" value="<?php echo (empty($posted['address1'])) ? '' : $posted['address1']; ?>" /></td>
          <td>Address2: </td>
          <td><input name="address2" value="<?php echo (empty($posted['address2'])) ? '' : $posted['address2']; ?>" /></td>
        </tr>
        <tr>
          <td>City: </td>
          <td><input name="city" value="<?php echo (empty($posted['city'])) ? '' : $posted['city']; ?>" /></td>
          <td>State: </td>
          <td><input name="state" value="<?php echo (empty($posted['state'])) ? '' : $posted['state']; ?>" /></td>
        </tr>
        <tr>
          <td>Country: </td>
          <td><input name="country" value="<?php echo (empty($posted['country'])) ? '' : $posted['country']; ?>" /></td>
          <td>Zipcode: </td>
          <td><input name="zipcode" value="<?php echo (empty($posted['zipcode'])) ? '' : $posted['zipcode']; ?>" /></td>
        </tr>
        <tr>
          <td>UDF1: </td>
          <td><input name="udf1" value="<?php echo (empty($posted['udf1'])) ? '' : $posted['udf1']; ?>" /></td>
          <td>UDF2: </td>
          <td><input name="udf2" value="<?php echo (empty($posted['udf2'])) ? '' : $posted['udf2']; ?>" /></td>
        </tr>
        <tr>
          <td>UDF3: </td>
          <td><input name="udf3" value="<?php echo (empty($posted['udf3'])) ? '' : $posted['udf3']; ?>" /></td>
          <td>UDF4: </td>
          <td><input name="udf4" value="<?php echo (empty($posted['udf4'])) ? '' : $posted['udf4']; ?>" /></td>
        </tr>
        <tr>
          <td>UDF5: </td>
          <td><input name="udf5" value="<?php echo (empty($posted['udf5'])) ? '' : $posted['udf5']; ?>" /></td>
          
        </tr>
	<tr>
          <td>COD URL: </td>
          <td><input name="codurl" value="<?php echo (empty($posted['codurl'])) ? '' : $posted['codurl']; ?>" /></td>
          <td>TOUT URL: </td>
          <td><input name="touturl" value="<?php echo (empty($posted['touturl'])) ? '' : $posted['touturl']; ?>" /></td>
        </tr>
	<tr>
          <td>Drop Category: </td>
          <td><input name="drop_category" value="<?php echo (empty($posted['drop_category'])) ? '' : $posted['drop_category']; ?>" /></td>
          <td>Custom Note: </td>
          <td><input name="custom_note" value="<?php echo (empty($posted['custom_note'])) ? '' : $posted['custom_note']; ?>" /></td>
        </tr>
	<tr>
          <td>Note Category: </td>
          <td><input name="note_category" value="<?php echo (empty($posted['note_category'])) ? '' : $posted['note_category']; ?>" /></td>
        </tr> -->
        

        
        <tr>
          <?php if(!$hash) { ?>
            <td colspan="4"><input type="submit" value="Submit" /></td>
          <?php } ?>
        </tr>
      </table>
    </form>

  </body>
</html>
