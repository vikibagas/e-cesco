$('#tanggal-excel').daterangepicker({
	singleDatePicker: true,
	showDropdowns: false,
	format: 'YYYY-MM',
	//maxDate: new Date,
	viewMode: "months",
	minViewMode: "months"
});

if( $('#report').length )
{
	if (countCostPick > 0){
		var aTextCPI = $('#thCPI').text();
		aTextCPI += ' - <button type="button" class="btn btn-xs btn-danger" title="'+countCostPick+'"><i class="fa fa-exclamation-circle"></i></button> ('+countCostPick+')';
		$('#thCPI').html(aTextCPI);
	}

	if (countCostDeliv > 0) {

		var aTextCDI = $('#thCDI').text();
		aTextCDI += ' - <button type="button" class="btn btn-xs btn-danger" title="' + countCostDeliv + '"><i class="fa fa-exclamation-circle"></i></button> (' + countCostDeliv + ')';
		$('#thCDI').html(aTextCDI);
	}

	if (countCostOut > 0) {
		var aTextCO = $('#thCO').text();
		aTextCO += ' - <button type="button" class="btn btn-xs btn-danger" title="' + countCostOut + '"><i class="fa fa-exclamation-circle"></i></button> (' + countCostOut + ')';
		$('#thCO').html(aTextCO);
	}

	if (countCostIn > 0) {
		var aTextCI = $('#thCI').text();
		aTextCI += ' - <button type="button" class="btn btn-xs btn-danger" title="' + countCostIn + '"><i class="fa fa-exclamation-circle"></i></button> (' + countCostIn + ')';
		$('#thCI').html(aTextCI);
	}

	if (countCostAgent > 0) {
		var aTextTA = $('#thTA').text();
		aTextTA += ' - <button type="button" class="btn btn-xs btn-danger" title="' + countCostAgent + '"><i class="fa fa-exclamation-circle"></i></button> (' + countCostAgent + ')';
		$('#thTA').html(aTextTA);
	}
    
}

$(document).on('click', '.frm_inv_bayar', function (e) {
	e.preventDefault();
	document.getElementById("frmBayarInv").reset();
	$("#modal_bayar .modal-body").hide();
	$("#modal_bayar #id_invoice").val($(this).attr('data-id'));
	$("#modal_bayar #no_invoice").val($(this).attr('data-invoice'));
	$("#modal_bayar").modal('show');
	//$("#modal_bayar .modal-body").show();
	$.post('module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=detail-bayar', {
			id_invoice: $(this).attr('data-id')
		},
		function (json) {
			var data = JSON.parse(json);
			if (data){
				$("#modal_bayar #tanggal").val(data.tanggal_bayar);
				$("#modal_bayar #nilai").val(data.jumlah_bayar);
				$("#modal_bayar #keterangan").val(data.ket_bayar);
			}
			$("#modal_bayar .modal-body").show();
		}
	);
});

$(document).on('submit', '#frmBayarInv', function (e) {
	$('#btnSubmitBayar').prop( "disabled", true );

});

$(document).on('click', '#excelInv', function (e) {
	if ($('#tanggal-excel').val() != '')
	location.href = 'reports/PHPExcel/excel_inv_customer.php?id_invoice='+$('#id_invoice').val()+'&periode='+$('#tanggal-excel').val();
	else alert('Periode harus diisi');

});