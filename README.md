<strong>(c)2014 BITPAY, INC.</strong>

Permission is hereby granted to any person obtaining a copy of this software
and associated documentation for use and/or modification in association with
the bitpay.com service.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.


Server environment check script to determine if a merchant's server has the 
correct software installed and can communicate with the BitPay network properly.


Installation
------------
Download this Zip archive and extract the contents. Copy the envcheck.php file onto your web server.


Usage
-----
Open a web browser and navigate to the envcheck.php script on your web server.  It will perform a series of basic environment checks to include a PHP version, extension check and a communication check to determine if your server can send/receive requests to the BitPay network.  You should see a series of success messages showing that all checks completed and your server passed.  If you have any problems detected, review the error message and contact your web hosting provider.

Below is an example script output showing all checks passed:
<pre>
===============================================================
BitPay Merchant Server Environment Check v0.02
===============================================================
The following information has been compiled to help you
ensure your server is ready to use one of our code libraries
or shopping cart plugins.  Please note that the shopping
cart you choose may have additional requirements not checked
here.  Refer to the cart's documentation for those requirements.

The results of this check will also be written to a the
envcheck.log file located in the same directory as this script
if the directory is writable.
===============================================================

******* Check script run date/time: 21:22:44 04-03-2014 *******

PHP Version: 5.3.10-1ubuntu3.9 - Good!
Extensions:  Found json - Good!  Found curl - Good!
BitPay communication check: Good!
Local communication check: Good!

Success! No problems were found that could prevent you from using a BitPay plugin!
</pre>




Change Log
----------
Version 0.01, rich@bitpay.com
  - Initial version

Version 0.02, rich@bitpay.com
  - Added check for JSON extension
