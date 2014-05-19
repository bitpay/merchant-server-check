<?php

/**
 * (c)2014 BITPAY, INC.
 *
 * Permission is hereby granted to any person obtaining a copy of this software
 * and associated documentation for use and/or modification in association with
 * the bitpay.com service.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * Server environment check script to determine if a merchant's
 * merchant's server has the correct software installed and can
 * communicate with the BitPay network properly.
 *
 * Version 0.02, rich@bitpay.com
 *
 */

date_default_timezone_set('UTC');

$timestamp = date('H:i:s m-d-Y');
$script_version = '0.02';
$check_results = array();
$phpversion = phpversion();
$extensions = get_loaded_extensions();
$logfilename = dirname(__FILE__) . '/envcheck.log';
$local_url = 'https://' . $_SERVER['SERVER_NAME'];
$bitpay_url = 'https://bitpay.com/';
$curlpresent = false;
$jsonpresent = false;
$problem_found = false;

function curlCheck($url) {
  if(!function_exists('curl_init')) {
    $problem_found = true;
    return array('error' => 'curl not available');
  }

  $curl = curl_init();

  $header = array(
                  'X-BitPay-Plugin-Info: envcheck' . $script_version,
                  );

  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_PORT, 443);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
  curl_setopt($curl, CURLOPT_TIMEOUT, 10);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
  curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);

  $responseString = curl_exec($curl);

  if($responseString == false) {
    $problem_found = true;
    $response = array('error' => curl_error($curl));
  } else {
    $response = array('success' => 'curl check worked');
  }

  curl_close($curl);
  return $response;
}

$output = '<pre>';
$output .= '===============================================================' . "\r\n";
$output .= 'BitPay Merchant Server Environment Check v' . $script_version . "\r\n";
$output .= '===============================================================' . "\r\n";
$output .= 'The following information has been compiled to help you' . "\r\n";
$output .= 'ensure your server is ready to use one of our code libraries' . "\r\n";
$output .= 'or shopping cart plugins.  Please note that the shopping' . "\r\n";
$output .= 'cart you choose may have additional requirements not checked' . "\r\n";
$output .= 'here.  Refer to the cart\'s documentation for those requirements.' . "\r\n";
$output .= "\r\n" . 'The results of this check will also be written to a the' . "\r\n";
$output .= 'envcheck.log file located in the same directory as this script' . "\r\n";
$output .= 'if the directory is writable.' . "\r\n";
$output .= '===============================================================' . "\r\n";
$output .= "\r\n" . '******* Check script run date/time: ' . $timestamp . " *******\r\n";
$output .= "\r\n" . 'PHP Version: ' . $phpversion;

$verarray = explode('.',$phpversion);

if((int)$verarray[0] >= 5)
  $output .= ' - Good!' . "\r\n";
else {
  $problem_found = true;
  $output .= ' - This version is too old. Please upgrade to a 5.x release.' . "\r\n";
}

$output .= 'Extensions: ';

foreach($extensions as $key => $value) {
  if(trim($value) == 'curl') {
    $curlpresent = true;
    $output .= ' Found curl - Good! ';
  }
  if(trim($value) == 'json') {
    $jsonpresent = true;
    $output .= ' Found json - Good! ';
  }
}

if($curlpresent) {
  $output .= "\r\n" . 'BitPay communication check: ';
  $bitpay_check = curlCheck($bitpay_url);

  if(array_search('error', $bitpay_check) === false)
    $output .= 'Good!';
  else {
    $output .= 'Problem found! Could not communicate with the BitPay network! The specific error message was: ' . $bitpay_check['error'];
    $problem_found = true;
  }

  $local_check = curlCheck($local_url);
  $output .= "\r\n" . 'Local communication check: ';

  if(array_search('error', $local_check) === false)
    $output .= 'Good!';
  else {
    $output .= 'Problem found! No secure site found locally! The specific error message was: ' . $bitpay_check['error'];
    $problem_found = true;
  }

} else {
  $problem_found = true;
  $output .= "\r\n\r\n" . 'Problem found! The curl extension is not present! Contact your web hosting provider' . "\r\n" . 'and request this extension be added to your PHP installation.';
  $output .= "\r\n" . 'Skipping communication checks...';
}

if(!$jsonpresent) {
  $problem_found = true;
  $output .= "\r\n\r\n" . 'Problem found! The json extension is not present! Contact your web hosting provider' . "\r\n" . 'and request this extension be added to your PHP installation.';
}

echo $output;

if($problem_found)
  echo "\r\n\r\n" . 'Problems were found on your server. Resolve the issues and re-run this check script to verify.' . "\r\n" . 'You will be unable to use a BitPay plugin until these issues have been resolved.';
else
  echo "\r\n\r\n" . 'Success! No problems were found that could prevent you from using a BitPay plugin!';

@file_put_contents($logfilename,$output);

/* end BitPay check script */
