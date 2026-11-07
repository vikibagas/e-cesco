
if( $('#report').length )
{
	var aTextCPI = $('#thCPI').text();
	aTextCPI += ' - <button type="button" class="btn btn-xs btn-danger" title="'+countCostPick+'"><i class="fa fa-exclamation-circle"></i></button> ('+countCostPick+')';
	$('#thCPI').html(aTextCPI);

	var aTextCDI = $('#thCDI').text();
	aTextCDI += ' - <button type="button" class="btn btn-xs btn-danger" title="'+countCostDeliv+'"><i class="fa fa-exclamation-circle"></i></button> ('+countCostDeliv+')';
	$('#thCDI').html(aTextCDI);

	var aTextCO = $('#thCO').text();
	aTextCO += ' - <button type="button" class="btn btn-xs btn-danger" title="'+countCostOut+'"><i class="fa fa-exclamation-circle"></i></button> ('+countCostOut+')';
	$('#thCO').html(aTextCO);
	
	var aTextCI = $('#thCI').text();
	aTextCI += ' - <button type="button" class="btn btn-xs btn-danger" title="'+countCostIn+'"><i class="fa fa-exclamation-circle"></i></button> ('+countCostIn+')';
	$('#thCI').html(aTextCI);

	var aTextTA = $('#thTA').text();
	aTextTA += ' - <button type="button" class="btn btn-xs btn-danger" title="'+countCostAgent+'"><i class="fa fa-exclamation-circle"></i></button> ('+countCostAgent+')';
	$('#thTA').html(aTextTA);

}

function confirmInvoice(id,idEnc){
	//window.location.href = \'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=confirm&stts=1&id='.base64_encrypt($d,$key)

	if (confirm('Konfirmasi Invoice?')) {
		$('#processing-modal').modal('show');
		$.ajax({
			type: "GET",
			dataType: "JSON",
			url: "module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=confirm&stts=1&id=" + idEnc,
			success: function (data) {

				$('#btnConfirm' + id).removeAttr('onclick');
				$('#btnConfirm' + id).attr('title', 'Invoice Berhasil Dikonfirmasi');
				$('#iConfirm' + id).removeClass('text-red');
				$('#iConfirm' + id).addClass('text-blue');
				$('#processing-modal').modal('hide');
				alert('Konfirmasi sukses.');

			}
		});
	}
}

function sendInvoice(id,idEnc){

	if (confirm('Kirim Invoice ke Agen?')) {
		$('#processing-modal').modal('show');
		$.ajax({
			type: "GET",
			dataType: "JSON",
			url: "module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=send&stts=1&id=" + idEnc,
			success: function (data) {

				$('#btnSend' + id).removeAttr('onclick');
				$('#btnSend' + id).attr('title', 'Invoice Berhasil Dikirim ke Agent');
				$('#iSend' + id).removeClass('text-red');
				$('#iSend' + id).addClass('text-green');
				$('#processing-modal').modal('hide');
				alert('Kirim Invoice sukses.');

			}
		});
	}

}
