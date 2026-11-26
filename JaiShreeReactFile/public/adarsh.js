function WhatsAppAdd() {
	var mainNumberArray = new Array(), mainAmountArray = new Array();
	var message = $('#WMsgTextArea').val();
	var Pick_Haruf_in_Whatspp= $('#Pick_Haruf_in_Whatspp').val();
	var number = 0, lastnumber = 0, value = "";
	message = message.trim();

	//message = message.replace(/(\[\d{1,2}:\d{1,2}\s?A?P?M?,\s\d{1,2}\/\d{1,2}\/\d{4}\]\s\w+.*:)/gi, "");
	message = message.replace(/\[\d{1,2}(:\d{2})?\s?(?:AM|PM)?,?\s?\d{1,2}\/\d{1,2}\/\d{4}\]\s*[^:]*:|\[\d{1,2}\/\d{1,2}\/\d{4}\s\d{1,2}:\d{2}\s?(?:AM|PM)?\]\s*[^:]*:/gi, ":");
	message = message.replace(/(\w{3}\s\d{1,2}\w{2}\s\d{2}:\d{2})/gi, "");
	message = message.replace(/(\d{2}:\d{2}\s{4})/gi, "");
	if (!(message.match(/[kr&)x}â‚¹\n]|(INTO)|(INTU)|(int)/gi)))
		message = message + "k";
	if (message.match(/[kr&)x}â‚¹\n]|(INT)/gi)) {
		var list = message.split(/[kr&)x}â‚¹\n]|(INT)/gi);
		for (var i = 0; i < list.length; i++) {
			if (list[i] != undefined) {
				str = list[i];
				str = str.replace(/[a-y]/gi, '');
				str = str.trim();

				if (str.match(/\d+/g) && str.length > 3) {
					str = str.replace("/[a-z]/i", '');
					if(Pick_Haruf_in_Whatspp==1){
						str = str.split(/[^\d+]+/);
					}
					else {
						str = str.split(/[^\d]+/);
					}
					for (var j = 0; j < str.length - 1; j++) {

						value = value + str[j];
						lastnumber = j + 1;
					}
					mainNumberArray[number] = value;
					mainAmountArray[number] = str[lastnumber];
					++number;
				}
			}
			value = "";
			lastnumber = 0;
		}
	}
	$("#WhatsappDetailMovableTbody").empty();
	$('#WhatsappDetailMovable').show();
	var table = document.getElementById("WhatsappDetailMovableTbody");
	for (var i = 0; i < mainNumberArray.length; i++) {
		mainNumberArray[i] = mainNumberArray[i].replace(/^\++|\++$/g, '+');
		var tr = document.createElement("tr");
		var td = document.createElement("td");
		var input = document.createElement("INPUT");
		input.setAttribute("type", "text");
		input.setAttribute("class", "WhatsappInput");
		input.setAttribute("value", mainNumberArray[i]);
		td.appendChild(input);
		tr.appendChild(td);
		input.onkeyup = calWhatsappLineTotal;

		td = document.createElement("td");
		amount = document.createElement("INPUT");
		amount.setAttribute("type", "text");
		amount.setAttribute("class", "WhatsappInputAmount");
		amount.getAttributeNode("class").value += " WhatsappInput";
		amount.setAttribute("value", mainAmountArray[i]);
		td.appendChild(amount);
		tr.appendChild(td);
		amount.onkeyup = calWhatsappLineTotal;
		td = document.createElement("td");
		if (mainNumberArray[i].includes('+')) {
			let plusCount = (mainNumberArray[i].match(/\+/g) || []).length;
			finalAmount = mainNumberArray[i].replace(/\+/g, '').length * plusCount * mainAmountArray[i]
		}
		else if ((mainNumberArray[i].length % 2) != 0)
			finalAmount = "Wrong Input"
		else
			finalAmount = mainNumberArray[i].length / 2 * mainAmountArray[i]
		input = document.createElement("INPUT");
		input.setAttribute("type", "text");
		input.setAttribute("class", "WhatsappInputTotal");
		input.getAttributeNode("class").value += " WhatsappInput";
		input.setAttribute("value", finalAmount);
		input.onkeyup = calWhatsappLineTotal;
		td.appendChild(input);
		tr.appendChild(td);
		table.appendChild(tr);
		$('.WhatsappInput:eq(0)').select();

	}
}
function calWhatsappLineTotal(e) {
	var $inputs = $(e.target).closest('tr').find('input');
	var $input = $(e.target);
	var $row = $input.closest('tr');
	var no = $inputs.eq(0).val();
	var tamount = $inputs.eq(1).val();
	var total = $inputs.eq(2);
	if (no.includes('+')) {
		let plusCount = (no.match(/\+/g) || []).length;
		total.val(no.replace(/\+/g, '').length * plusCount * tamount)
	}
	else if (no.length % 2 == 0) {
		total.val((no.length / 2) * tamount);
	}
	else {
		total.val('Wrong Input');
	}
	if (e.keyCode === 13) { // Enter key code
		e.preventDefault();
		var nextIndex = $inputs.index($input) + 1;
		if (nextIndex < $inputs.length) {
			$inputs.eq(nextIndex).select();
		} else {
			var $nextRow = $row.next('tr');
			if ($nextRow.length > 0) {
				$nextRow.find('input:first').focus();
			}
		}
	}

	//$('.WhatsappInput:eq(' + nextIndex+')').select();
}
function WhatsappToPurchuniTable(e) {
	$('#WhatsappDetailMovableTbody tr').has('td').each(function (index, item) {
		amount = $(item).children().eq(1).find("input").val();
		number = $(item).children('td:first').find("input").val();
		if (number.includes("+")) {
			BreakingPlusSymbol(number,Number(amount),number);
		}
		else {
			no = number.match(/.{1,2}/g);
			for (var i = no.length - 1; i >= 0; i--) {
				valueBinding(Number(amount), Number(no[i]), no);
			}
		}
	});
	ClosePopup('WhatsappMovable');
	ClosePopup('WhatsappDetailMovable');
}
function LessSaveWhatsappAmount(e) {
	var x = document.getElementsByClassName('WhatsappInputAmount');
	for (i = 0; i < x.length; i++) {
		x[i].value = x[i].value * -1;
	}
	var x = document.getElementsByClassName('WhatsappInputTotal');
	for (i = 0; i < x.length; i++) {
		x[i].value = x[i].value * -1;
	}
}

function BreakingPlusSymbol(no,amount,OriginalNumber) {
	if (no.charAt(0) == '+' && no.charAt(no.length - 1) == '+') {
		no = no.replace(/\+/g, "");
		noStr = no.match(/.{1,1}/g);
		console.log(noStr);
		for (var i = 0; i < noStr.length; i++) {
			no = noStr[i];
			no = Number(no);
			no = 'a' + no;
			valueBinding(amount, no, OriginalNumber);
		}
		for (var i = 0; i < noStr.length; i++) {
			no = noStr[i];
			no = Number(no);
			no = 'b' + no;
			valueBinding(amount, no, OriginalNumber);
		}
	}
	else if (no.charAt(0) == '+') {
		no = no.replace(/\+/g, "");
		noStr = no.match(/.{1,1}/g);
		for (i = 0; i < noStr.length; i++) {
			no = noStr[i];
			no = Number(no);
			no = 'a' + no;
			valueBinding(amount, no, OriginalNumber);
		}
	}
	else {
		no = no.replace(/\+/g, "");
		noStr = no.match(/.{1,1}/g);
		for (i = 0; i < noStr.length; i++) {
			no = noStr[i];
			no = Number(no);
			no = 'b' + no;
			valueBinding(amount, no, OriginalNumber);
		}
	}
}
$('#convertToNewLine').on('click', function() {
	// Get the value from the textarea
	var text = $('#WMsgTextArea').val();

	// Replace spaces between characters with newline characters
	var modifiedText = text.replace(/(\S)\s+(\S)/g, '$1\n$2');

	// Set the modified text back to the textarea
	$('#WMsgTextArea').val(modifiedText);
});