$(function () {
		
	//datepicker
	$('#tanggal').daterangepicker({
        singleDatePicker: true,
		showDropdowns: false,
		format: 'DD-MM-YYYY'
	});
	
	//select2
	$(".select2").select2({
		placeholder: "Please select ...",
		allowClear: true
	});
	
	//Timepicker
	$(".timepicker").timepicker({
		showMeridian: false,
		showInputs: false
	});
	
	//Date range picker
	$('#daterange').daterangepicker({
		format: 'DD-MM-YYYY'
	});
		
	//destination
	$("#destination").select2({
		placeholder: "Please select ...",
		allowClear: true,
		ajax: {
			type: 'post',
			url: 'module/mod_airwaybill/airwaybill_action.php?module=airwaybill&act=destination',
			dataType: 'json',
			processResults: function (data) {
				return {
					results: $.map(data, function(obj) {
						return { id: obj.idnegara, text: obj.namanegara };
					})
				};
			}
		}
	});
	
	//destination
	$("#idkustomerx").select2({
		placeholder: "Please select ...",
		allowClear: true,
		ajax: {
			type: 'post',
			url: 'module/mod_airwaybill/airwaybill_action.php?module=airwaybill&act=customer',
			dataType: 'json',
			processResults: function (data) {
				return {
					results: $.map(data, function(obj) {
						return { id: obj.idkustomer, text: obj.nama };
					})
				};
			}
		}
	});
	
	//auto input customer
	$('#idkustomer').on('change', function(){
		idkustomer = $('#idkustomer option:selected').val(); // the dropdown item selected value
		
		$.ajax({
			type :'POST',
			dataType:'json',
			data : { idkustomer : idkustomer },
			url : 'module/mod_airwaybill/airwaybill_action.php?module=airwaybill&act=customerread',
			success : function(result){
				$('#alamat_kustomer').val(result['alamat']);// json result
				$('#telp_kustomer').val(result['telp']);// json result
				$('#fax_kustomer').val(result['fax']);// json result
				$('#cp_kustomer').val(result['contactperson']);// json result 
			}
		});
	});
	
	
});