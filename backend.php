<?php
  # Set your secret key in the variable below
  # Your secret key is provided by Contact State
  $secret_key = 'REPLACE_ME_WITH_YOUR_SECRET_KEY';

  # Claim URL is passed in from the form submission
  $claim_url = $_POST['contact_state_claim_cert_url'];

  if(isset($claim_url) && $claim_url != '') {
    $content = json_encode(array('secret_key' => $secret_key));

    $curl = curl_init($claim_url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

    # Make a POST request to claim the certificate
    $json_response = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($status != 201) {
      die("Error: call to URL $claim_url failed");
    }
    curl_close($curl);

    # Get the certificate URL from the response
    # This should be stored and passed along to the buyer
    $response = json_decode($json_response, true);
    $certificate_url = $response['cert_url'];
    echo "The certificate URL is $certificate_url";
  } else {
    # If we get here something has gone wrong with the javascript embed
    die('Claim URL not set');
  }
?>