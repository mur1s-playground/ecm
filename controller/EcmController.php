<?php
class EcmController extends Controller {
	function __construct() {
		parent::__construct();
	}

	function viewController() {
		if ($_GET['keygen']) {
			$config = array(
    				"digest_alg" => "sha512",
			    	"private_key_bits" => intval($_GET["keysize"]),
				"private_key_type" => OPENSSL_KEYTYPE_RSA,
			);

			$key = openssl_pkey_new($config);

			$priv_key = "";
			openssl_pkey_export($key, $priv_key);


			$details = openssl_pkey_get_details($key);
			$pub_key = $details["key"];

			$key_signature = "";
			openssl_sign($pub_key, $key_signature , $priv_key);

			exit(json_encode(array(
				"private_key"	=>	$priv_key,
				"public_key"	=>	$pub_key,
				"public_key_sig"=>	base64_encode($key_signature)
			)));
		} else if ($_GET["verify"]) {
			$php_input = file_get_contents("php://input");
			$_POST = json_decode($php_input, true);
			$verified = openssl_verify($_POST['data'], base64_decode($_POST['sig']), $_POST['pubkey']);
			if ($verified == 1) {
				$verified = true;
			} else if ($verified == 0) {
				$verified = false;
			} else {
				$verified = "error";
			}
			exit(json_encode($verified));
		} else if ($_GET["sign"]) {
			$php_input = file_get_contents("php://input");
                        $_POST = json_decode($php_input, true);
			$signature = "";
			openssl_sign($_POST['data'], $signature, $_POST['privkey']);
			exit(base64_encode($signature));
		}
	}
}

?>
