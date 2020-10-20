<script type="text/javascript">
	//KEYGEN
	function generate_key_listener_r() {
		var response = JSON.parse(this.responseText);
		document.getElementById("r_privkey").value = response["private_key"];
		document.getElementById("r_pubkey").value = response["public_key"];
		document.getElementById("r_pubkey_sig").value = response["public_key_sig"];
	}

	function generate_key_listener_s() {
                var response = JSON.parse(this.responseText);
		document.getElementById("s_privkey").value = response["private_key"];
                document.getElementById("s_pubkey").value = response["public_key"];
                document.getElementById("s_pubkey_sig").value = response["public_key_sig"];
        }

	function generate_key(type) {
		var req = new XMLHttpRequest();
		if (type == "recipient") {
			req.addEventListener("load", generate_key_listener_r);
		} else {
			req.addEventListener("load", generate_key_listener_s);
		}
		req.open("GET", "https://mur1.de/mur1/Ecm/?keygen=" + type + "&keysize=" + document.getElementById("keysize").value);
		req.send();
	}

	//KEY Verification
	function verify_key_r() {
		if (this.response === "false" || this.response === "error") {
			document.getElementById("r_pubkey").style.backgroundColor = "#ff0000";
			document.getElementById("r_pubkey_sig").style.backgroundColor = "#ff0000";
		} else if (this.response === "true") {
			document.getElementById("r_pubkey").style.backgroundColor = "#00ff00";
                        document.getElementById("r_pubkey_sig").style.backgroundColor = "#00ff00";
		}
	}

	function verify_key_s() {
		if (this.response === "false" || this.response === "error") {
                        document.getElementById("s_pubkey").style.backgroundColor = "#ff0000";
                        document.getElementById("s_pubkey_sig").style.backgroundColor = "#ff0000";
                } else if (this.response === "true") {
                        document.getElementById("s_pubkey").style.backgroundColor = "#00ff00";
                        document.getElementById("s_pubkey_sig").style.backgroundColor = "#00ff00";
                }
	}

	function verify_key(type) {
		var req = new XMLHttpRequest();
		req.open("POST", "https://mur1.de/mur1/Ecm/?verify=1");
		req.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
		var data = null;
		if (type == "recipient") {
			req.addEventListener("load", verify_key_r);
			data = {
				data	: document.getElementById("r_pubkey").value,
				sig	: document.getElementById("r_pubkey_sig").value,
				pubkey  : document.getElementById("r_pubkey").value
			};
		} else {
			req.addEventListener("load", verify_key_s);
			data = {
                                data   : document.getElementById("s_pubkey").value,
                                sig    : document.getElementById("s_pubkey_sig").value,
                                pubkey : document.getElementById("s_pubkey").value
                        };
		}
		req.send(JSON.stringify(data));
	}

	//Sign Message
	function sign_message_res() {
		document.getElementById("message_sig").value = this.response;
	}

	function sign_message() {
		var req = new XMLHttpRequest();
                req.open("POST", "https://mur1.de/mur1/Ecm/?sign=1");
                req.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                req.addEventListener("load", sign_message_res);
                data = {
                                data     : document.getElementById("message").value,
                                privkey  : document.getElementById("s_privkey").value
                        };
                req.send(JSON.stringify(data));
	}

	//Send Message
	function send_message() {
		document.getElementById("received_message").value = document.getElementById("message").value;
                document.getElementById("received_message_sig").value = document.getElementById("message_sig").value;
	}

	//Verify Message and Sender
	function verify_message_and_sender_res() {
		if (this.response === "false" || this.response === "error") {
                        document.getElementById("received_message").style.backgroundColor = "#ff0000";
                        document.getElementById("received_message_sig").style.backgroundColor = "#ff0000";
                } else if (this.response === "true") {
                        document.getElementById("received_message").style.backgroundColor = "#00ff00";
                        document.getElementById("received_message_sig").style.backgroundColor = "#00ff00";
                }
	}

	function verify_message_and_sender() {
		var req = new XMLHttpRequest();
		req.open("POST", "https://mur1.de/mur1/Ecm/?verify=1");
		req.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                req.addEventListener("load", verify_message_and_sender_res);
		data = {
			data   : document.getElementById("received_message").value,
			sig    : document.getElementById("received_message_sig").value,
			pubkey : document.getElementById("s_pubkey").value
		};
		req.send(JSON.stringify(data));
	}

	//Receiver Sign Message
	function sign_received_message_res() {
		document.getElementById("message_received_sig").value = this.response;
	}

	function sign_received_message() {
		var req = new XMLHttpRequest();
		req.open("POST", "https://mur1.de/mur1/Ecm/?sign=1");
		req.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                req.addEventListener("load", sign_received_message_res);
                data = {
                                data     : document.getElementById("received_message").value,
                                privkey  : document.getElementById("r_privkey").value
                        };
                req.send(JSON.stringify(data));
	}

	//Send Receipt
	function send_receipt() {
		document.getElementById("receipt").value = document.getElementById("message_received_sig").value;
	}

	//Verify Message Received Sig
	function verify_message_received_sig_res() {
		if (this.response === "false" || this.response === "error") {
			document.getElementById("received_message").style.backgroundColor = "#ff0000";
                        document.getElementById("receipt").style.backgroundColor = "#ff0000";
                } else if (this.response === "true") {
			document.getElementById("received_message").style.backgroundColor = "#00ff00";
                        document.getElementById("receipt").style.backgroundColor = "#00ff00";
                }
	}

	function verify_message_received_sig() {
                var req = new XMLHttpRequest();
                req.open("POST", "https://mur1.de/mur1/Ecm/?verify=1");
                req.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                req.addEventListener("load", verify_message_received_sig_res);
                data = {
                        data   : document.getElementById("message").value,
                        sig    : document.getElementById("receipt").value,
                        pubkey : document.getElementById("r_pubkey").value
                };
                req.send(JSON.stringify(data));
	}
</script>

<div class="content">
<div class="headline">ECM - electronic certified mail</div>
<div class="privacy">
Keysize:
	<input type="text" id="keysize" value=512 /><br>

<table border="1" width="100%">
	<tr>
		<td><b>Recipient</b></td>
		<td><b>Sender</b></td>
	</tr>
	<tr>
		<td colspan=2 style="font-size:10px"><center>Private Keys (not published)</center></td>
	<tr>
		<td><button onclick="javascript:generate_key('recipient')">Create Key</button><br><textarea rows=5 style="width:300px;" id="r_privkey"></textarea></td>
		<td><button onclick="javascript:generate_key('sender')">Create Key</button><br><textarea rows=5 style="width:300px;" id="s_privkey"></textarea></td>
	</tr>
	<tr>
		<td colspan=2 style="font-size:10px"><center>Public Keys & Signature (published/set from respective party)</center></td>
	</tr>
	<tr>
		<td>
			<textarea rows=5 style="width:300px;" id="r_pubkey"></textarea><br>
			<textarea rows=5 style="width:300px;" id="r_pubkey_sig"></textarea>
		</td>
		<td>
			<textarea rows=5 style="width:300px;" id="s_pubkey"></textarea><br>
			<textarea rows=5 style="width:300px;" id="s_pubkey_sig"></textarea>
		</td>
	</tr>
	<tr>
		<td><center><button onclick="javascript:verify_key('sender')">Verify Sender Key &#8599;</button></center></td>
		<td><center><button onclick="javascript:verify_key('recipient')">&#8598; Verify Recipient Key</button></center></td>
	</tr>
	<tr>
		<td></td>
		<td>
			<center>
				<b>Message</b><br>
				<textarea rows=5 style="width:300px;" id="message"></textarea>
			</center>
		</td>
	</tr>
	<tr>
		<td></td>
		<td><button onclick="javascript:sign_message()">Sign Message</button><input id="message_sig" /></td>
	</tr>
	<tr>
		<td><b>Received Message</b><br><textarea rows=5 style="width:300px;" id="received_message"></textarea><br><input id="received_message_sig"</td>
		<td><button onclick="javascript:send_message()">&#8592; Send message and signature to Recipient</button></td>
	</tr>
	<tr>
		<td><button onclick="javascript:verify_message_and_sender()">Verify Message & Sender</button></td>
		<td></td>
	</tr>
	<tr>
		<td><button onclick="javascript:sign_received_message()">Sign Received Message</button><input id="message_received_sig"></td>
		<td></td>
	</tr>
	<tr>
		<td><button onclick="javascript:send_receipt()">Send "Message Received Signature" to Message Sender</button></td>
		<td><input id="receipt"></td>
	</tr>
	<tr>
		<td></td>
		<td>
			<button onclick="javascript:verify_message_received_sig()">Verify "Message Reiceived Signature" is from Receiver</button>
		</td>
	</tr>
</table>

</div>
</div>
</div>
