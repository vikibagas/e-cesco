
if( $('#belum-tertagih').length )
{
     var table = $("#belum-tertagih");
	table.DataTable({
		"processing": true,
		"serverSide": true,
		"fixedColumns":   true,
		"ajax":{
			url :"module/mod_finance-awb-customer-belum-tertagih/finance-awb-customer-belum-tertagih_action.php?module=belum-tertagih&act=data"
		},
		"order": [
			[ 1, "desc" ]
		],
		"scrollX": true,
		"columnDefs": [{
			//"targets": [2,3,4,5,6,8,9,10,11,12,13],
			"orderable": false,
			"searchable": false,
		}]
	});
}
	
$(document).on('click', '.frm_sudah_bayar', function (e) {
		e.preventDefault();
		document.getElementById("frmBayar").reset();
		$("#modal_bayar .modal-body").hide();
		$("#modal_bayar #id_pengiriman").val($(this).attr('data-id'));
		$("#modal_bayar #awb").val($(this).attr('data-awb'));
		$("#modal_bayar").modal('show');
		$.post('module/mod_finance-awb-customer-belum-tertagih/finance-awb-customer-belum-tertagih_action.php?module=belum-tertagih&act=detail-bayar', {
				id_pengiriman: $(this).attr('data-id')				
			},
			function (json) {
				var data = JSON.parse(json);
				if (data){
					$("#modal_bayar #tanggal").val(data.tanggal_bayar);
					$("#modal_bayar #nilai").val(data.jumlah_bayar);
					$("#modal_bayar #penerima").val(data.diterima_oleh);
					$("#modal_bayar #id_bayar").val(data.id_pembayaran);
				}
				$("#modal_bayar .modal-body").show();
			}
		);
	});
	
$(document).on('submit', '#frmBayar', function (e) {
		$('#btnSubmitBayar').prop( "disabled", true );
		
	});
	
$(document).on('click', '#excel', function (e) {
		location.href = 'reports/PHPExcel/excel_awb_belum_tertagih.php?id_kustomer='+$('#kustomer').val()+'&custname='+$("#kustomer option:selected").text();
		
	});