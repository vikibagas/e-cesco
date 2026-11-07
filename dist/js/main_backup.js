$(function () {

	//select2
	$('.select2').select2({
		placeholder: 'Please select ...',
		allowClear: true,
		width: '100%'
	});

	//datepicker
	$('#tanggal').daterangepicker({
		singleDatePicker: true,
		showDropdowns: false,
		format: 'DD-MM-YYYY'
	});

	$('.tanggal').daterangepicker({
		singleDatePicker: true,
		showDropdowns: false,
		format: 'YYYY-MM-DD'
	});

	$('#tanggal-diambil').daterangepicker({
		singleDatePicker: true,
		showDropdowns: false,
		format: 'DD-MM-YYYY',
		maxDate: new Date
	});

	$('#tanggal-invoice').daterangepicker({
		//singleDatePicker: true,
		showDropdowns: false,
		format: 'YYYY-MM',
		//maxDate: new Date,
		viewMode: "months",
        minViewMode: "months"
	});
	$('#tanggal-compare').daterangepicker({
		singleDatePicker: true,
		showDropdowns: false,
		format: 'YYYY-MM',
		//maxDate: new Date,
		viewMode: "months",
        minViewMode: "months"
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

	//confirm delete
	$('#confirm-delete').on('show.bs.modal', function (e) {
		$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
	});

	//confirm blocked
	$('#confirm-blocked').on('show.bs.modal', function (e) {
		$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
	});

	//iCheck for checkbox and radio inputs
	$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
		checkboxClass: 'icheckbox_minimal-blue',
		radioClass: 'iradio_minimal-blue'
	});

	//uppercase for code
	$('#inputID').keyup(function () {
		this.value = this.value.toUpperCase();
	});

	// validate kota
	$("#form-kota").validate({
		rules: {
			kode_kota: {
				minlength: 3,
				maxlength: 3,
				required: true
			},
			nama_kota: {
				minlength: 3,
				required: true
			}
		},
		highlight: function (element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function (element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function (error, element) {
			if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		}
	});

	// validate branch
	$("#form-branch").validate({
		rules: {
			kode_cabang: {
				minlength: 3,
				maxlength: 3,
				required: true,
				remote: {
					url: "module/mod_branch/branch_action.php?module=branch&act=checkcode",
					type: "post"
				}
			},
			nama_cabang: {
				minlength: 5,
				required: true
			},
			alamat1: {
				required: true
			},
			alamat2: {
				required: true
			},
			kota: {
				required: true
			}
		},
		messages: {
			kode_cabang: {
				remote: "This code is already taken! Try another."
			}
		},
		highlight: function (element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function (element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function (error, element) {
			if (element.hasClass('select2')) {
				error.insertAfter(element.parent().find('.val-message'));
			} else if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		}
	});

	// validate customer
	$("#form-customer").validate({
		rules: {
			id_cabang: {
				required: true
			},
			kode_kustomer: {
				minlength: 3,
				maxlength: 3,
				required: true,
				remote: {
					url: "module/mod_customer/customer_action.php?module=customer&act=checkcode",
					type: "post"
				}
			},
			nama_kustomer: {
				required: true
			},
			alamat: {
				required: true
			},
			kota: {
				required: true
			}
		},
		messages: {
			kode_kustomer: {
				remote: "This code is already taken! Try another."
			}
		},
		highlight: function (element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function (element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function (error, element) {
			if (element.hasClass('select2')) {
				error.insertAfter(element.parent().find('.val-message'));
			} else if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		}
	});


	$("#form-barangcreate").validate({
		rules: {
			kode_barang: {
				minlength: 3,
				maxlength: 45,
				required: true,
				remote: {
					url: "module/mod_warehouse-barang/barang_action.php?module=barang&act=checkcode",
					type: "post"
				}
			},
			nama: {
				required: true
			},
			serial_number: {
				required: true
			},
			stok: {
				required: true
			},
			satuan: {
				required: true
			},
			pemiliki: {
				required: true
			},
			rak: {
				required: true
			},
			foto: {
				required: true
			}
		},
		messages: {
			kode_barang: {
				remote: "This code is already taken! Try another."
			}
		},
		highlight: function (element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function (element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function (error, element) {
			if (element.hasClass('select2')) {
				error.insertAfter(element.parent().find('.val-message'));
			} else if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		}
	});

	// validate user
	$("#form-user").validate({
		rules: {
			namalengkap: {
				required: true
			},
			email: {
				required: true,
				email: true
			},
			id_cabang: {
				required: true
			},
			level: {
				required: true
			}
		},
		highlight: function (element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function (element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function (error, element) {
			if (element.hasClass('select2')) {
				error.insertAfter(element.parent().find('.val-message'));
			} else if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		}
	});

	// validate booking outbound
	$("#form-booking").validate({
		ignore: [],
		rules: {
			id_kustomer: {
				required: true
			},
			alamat_pengirim: {
				required: true
			},
			hp_pengirim: {
				number: true
			},
			nama_penerima: {
				required: true
			},
			alamat_penerima: {
				required: true
			},
			hp_penerima: {
				number: true
			},
			lokasi_tujuan: {
				required: true
			},
			service: {
				required: true
			},
			komoditi: {
				required: true
			},
			berat_barang: {
				number: true
			},
			length: {
				number: true
			},
			width: {
				number: true
			},
			height: {
				number: true
			},
			jumlah_collie: {
				number: true
			},
			agree: {
				required: true
			}
		},
		messages: {
			kode_kustomer: {
				remote: "This code is already taken! Try another."
			},
			service: {
				required: ""
			},
			komoditi: {
				required: ""
			},
			agree: {
				required: ""
			}
		},
		highlight: function (element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function (element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function (error, element) {
			if (element.hasClass('select2')) {
				error.insertAfter(element.parent().find('.val-message'));
			} else if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		}
	});

	// autofill customer
	$('#id_kustomer').on('change', function () {
		id_kustomer = $('#id_kustomer option:selected').val();
		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: {
				id_kustomer: id_kustomer
			},
			url: 'module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=kustomer',
			success: function (result) {
				$('#alamat_pengirim').val(result['alamat']);
				$('#id_kota').val(result['kota']);
				$('#id_cabang').val(result['id_cabang']);
				$('#intercityasal').val(result['intercityasal']);
				if (result.loc_code == 'Y') {
					$('#loc_code').show();
					$('#pidkolom').show();
				} else {
					$('#loc_code').hide();
					$('#pidkolom').hide();
				}
			}
		});
	});

	// autocomplete penerima
	$("#nama_penerima").autocomplete({
		source: function (request, response) {
			$.ajax({
				url: 'module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=penerima',
				dataType: "json",
				data: {
					term: request.term,
					id_kustomer: $("#id_kustomerin").val()
				},
				success: function (data) {
					response(data);
				}
			});
		},
		min_length: 3,
		delay: 300,
		select: function (event, ui) {
			$("#nama_penerima").val(ui.item.value);
			$("#perusahaan_penerima").val(ui.item.perusahaan_penerima);
			$("#hp_penerima").val(ui.item.hp_penerima);

			$("#alamat_penerima1").val(ui.item.alamat_penerimabaru1);
			$("#alamat_penerima2").val(ui.item.alamat_penerimabaru2);



			$('#provinsi').val(ui.item.id_provinsi_tujuan).trigger('change');


			setTimeout(function() {
                $('#kota').val(ui.item.id_kota_tujuan).trigger('change');
            }, 500);

			setTimeout(function() {
                $('#kecamatan').val(ui.item.id_kecamatan_tujuan).trigger('change');
        	}, 1000);

			setTimeout(function() {
                $('#kelurahan').val(ui.item.kelurahan_tujuan).trigger('change');
            }, 1500);
		}
	});

	// lokasi tujuan
	$('#lokasi_tujuan').on('change', function () {
		lokasi_tujuan = $('#lokasi_tujuan option:selected').val();
		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: {
				lokasi_tujuan: lokasi_tujuan
			},
			url: 'module/mod_booking/booking_action.php?module=booking&act=intercity',
			success: function (result) {
				$('#intercitytujuan').val(result['intercity']);
			}
		});
	});

	$('#outbound-assignment').on('change', '#manifest_via', function () {
		manifest_via = $('#manifest_via option:selected').val();
		manifest_from = $('#manifest_from option:selected').val();
		manifest_to = $('#manifest_to option:selected').val();
		manifest_transit = $('#manifest_transit option:selected').val();
		manifest_date = $('#manifest_date option:selected').val();
		
		/*alert (manifest_from);
		alert (manifest_to);
		alert (manifest_via);
		alert (manifest_transit);*/
		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: {
				manifest_via: manifest_via,
				manifest_from: manifest_from,
				manifest_to: manifest_to,
				manifest_transit: manifest_transit,
				manifest_date: manifest_date
			},
			url: 'module/mod_manifest-outbound/manifest-outbound_action.php?module=manifest-outbound&act=cekresi',
			success: function (result) {
				//$('#intercitytujuan').val(result['intercity']);
				/*if(result['kode_manifest'] == ''){
					alert('SILAHKAN INPUT RESI PENGIRIMAN/SMU');
				}else{
					$('#resi').val(result['kode_manifest']);
				}*/
				//alert(result['kode_manifest']);
				if (!$.trim(result) || result.no_smu == '0'){
					$('#outbound-assignment #resi').prop('readonly', false);
					$('#outbound-assignment #resi').val('');
				} else{
					$('#outbound-assignment #resi').prop('readonly', true);
					$('#outbound-assignment #resi').val(result['no_smu']);
				}
				
			}
				
		});
	});

	$('#outbound-assignment').on('change', '#manifest_date', function () {
		manifest_via = $('#manifest_via option:selected').val();
		manifest_from = $('#manifest_from option:selected').val();
		manifest_to = $('#manifest_to option:selected').val();
		manifest_transit = $('#manifest_transit option:selected').val();
		manifest_date = $('#manifest_date option:selected').val();
		
		/*alert (manifest_from);
		alert (manifest_to);
		alert (manifest_via);
		alert (manifest_transit);*/
		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: {
				manifest_via: manifest_via,
				manifest_from: manifest_from,
				manifest_to: manifest_to,
				manifest_transit: manifest_transit,
				manifest_date: manifest_date
			},
			url: 'module/mod_manifest-outbound/manifest-outbound_action.php?module=manifest-outbound&act=cekresi',
			success: function (result) {
				//$('#intercitytujuan').val(result['intercity']);
				/*if(result['kode_manifest'] == ''){
					alert('SILAHKAN INPUT RESI PENGIRIMAN/SMU');
				}else{
					$('#resi').val(result['kode_manifest']);
				}*/
				//alert(result['kode_manifest']);
				if (!$.trim(result) || result.no_smu == '0'){
					$('#outbound-assignment #resi').prop('readonly', false);
					$('#outbound-assignment #resi').val('');
				} else{
					$('#outbound-assignment #resi').prop('readonly', true);
					$('#outbound-assignment #resi').val(result['no_smu']);
				}
				
			}
				
		});
	});

	$('#outbound-assignment').on('change', '#manifest_to', function () {
		manifest_via = $('#manifest_via option:selected').val();
		manifest_from = $('#manifest_from option:selected').val();
		manifest_to = $('#manifest_to option:selected').val();
		manifest_transit = $('#manifest_transit option:selected').val();
		manifest_date = $('#manifest_date option:selected').val();
		
		/*alert (manifest_from);
		alert (manifest_to);
		alert (manifest_via);
		alert (manifest_transit);*/
		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: {
				manifest_via: manifest_via,
				manifest_from: manifest_from,
				manifest_to: manifest_to,
				manifest_transit: manifest_transit,
				manifest_date: manifest_date
			},
			url: 'module/mod_manifest-outbound/manifest-outbound_action.php?module=manifest-outbound&act=cekresi',
			success: function (result) {
				//$('#intercitytujuan').val(result['intercity']);
				/*if(result['kode_manifest'] == ''){
					alert('SILAHKAN INPUT RESI PENGIRIMAN/SMU');
				}else{
					$('#resi').val(result['kode_manifest']);
				}*/
				//alert(result['kode_manifest']);
				if (!$.trim(result) || result.no_smu == '0'){
					$('#outbound-assignment #resi').prop('readonly', false);
					$('#outbound-assignment #resi').val('');
				} else{
					$('#outbound-assignment #resi').prop('readonly', true);
					$('#outbound-assignment #resi').val(result['no_smu']);
				}
				
			}
				
		});
	});

	$('#outbound-assignment').on('change', '#manifest_from', function () {
		manifest_via = $('#manifest_via option:selected').val();
		manifest_from = $('#manifest_from option:selected').val();
		manifest_to = $('#manifest_to option:selected').val();
		manifest_transit = $('#manifest_transit option:selected').val();
		manifest_date = $('#manifest_date option:selected').val();
		
		/*alert (manifest_from);
		alert (manifest_to);
		alert (manifest_via);
		alert (manifest_transit);*/
		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: {
				manifest_via: manifest_via,
				manifest_from: manifest_from,
				manifest_to: manifest_to,
				manifest_transit: manifest_transit,
				manifest_date: manifest_date
			},
			url: 'module/mod_manifest-outbound/manifest-outbound_action.php?module=manifest-outbound&act=cekresi',
			success: function (result) {
				//$('#intercitytujuan').val(result['intercity']);
				/*if(result['kode_manifest'] == ''){
					alert('SILAHKAN INPUT RESI PENGIRIMAN/SMU');
				}else{
					$('#resi').val(result['kode_manifest']);
				}*/
				//alert(result['kode_manifest']);
				if (!$.trim(result) || result.no_smu == '0'){
					$('#outbound-assignment #resi').prop('readonly', false);
					$('#outbound-assignment #resi').val('');
				} else{
					$('#outbound-assignment #resi').prop('readonly', true);
					$('#outbound-assignment #resi').val(result['no_smu']);
				}
				
			}
				
		});
	});

	$('#outbound-assignment').on('change', '#manifest_transit', function () {
		manifest_via = $('#manifest_via option:selected').val();
		manifest_from = $('#manifest_from option:selected').val();
		manifest_to = $('#manifest_to option:selected').val();
		manifest_transit = $('#manifest_transit option:selected').val();
		manifest_date = $('#manifest_date option:selected').val();
		
		/*alert (manifest_from);
		alert (manifest_to);
		alert (manifest_via);
		alert (manifest_transit);*/
		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: {
				manifest_via: manifest_via,
				manifest_from: manifest_from,
				manifest_to: manifest_to,
				manifest_transit: manifest_transit,
				manifest_date: manifest_date
			},
			url: 'module/mod_manifest-outbound/manifest-outbound_action.php?module=manifest-outbound&act=cekresi',
			success: function (result) {
				//$('#intercitytujuan').val(result['intercity']);
				/*if(result['kode_manifest'] == ''){
					alert('SILAHKAN INPUT RESI PENGIRIMAN/SMU');
				}else{
					$('#resi').val(result['kode_manifest']);
				}*/
				//alert(result['kode_manifest']);
				if (!$.trim(result) || result.no_smu == '0'){
					$('#outbound-assignment #resi').prop('readonly', false);
					$('#outbound-assignment #resi').val('');
				} else{
					$('#outbound-assignment #resi').prop('readonly', true);
					$('#outbound-assignment #resi').val(result['no_smu']);
				}
				
			}
				
		});
	});


	function addCommas(nStr)
	{
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}

	// sea freight
	$('.seafreight').click(function () {
		var seafreight = $(this).val();
		if (seafreight == '10') {
			$('#ifseafreight').show();
			$('#kotatujuan').show();
			$('#ifinternational').hide();
			$('#lokasi_tujuan').prop("disabled", false);
		} else if (seafreight == '11') {
			$('#ifseafreight').show();
			$('#kotatujuan').show();
			$('#ifinternational').hide();
			$('#lokasi_tujuan').prop("disabled", false);
		} else if (seafreight == '6') {
			$('#ifinternational').show();
			$('#ifseafreight').hide();
			$('#kotatujuan').hide();
			$('#lokasi_tujuan').prop("disabled", true);
		} else {
			$('#ifseafreight').hide();
			$('#ifinternational').hide();
			$('#kotatujuan').show();
			$('#lokasi_tujuan').prop("disabled", false);
		}
		$('#serviceshow').val(seafreight);
	});

	$('.packing').click(function () {
		//alert('check 1');
		var packing = $(this).val();
		var berat   = $('#berat_barang').val();
		if (packing == '2') {
			//alert('if packing');
			$('#val-packing').show();

			if  (berat <= 10){
				var total = addCommas(10*8500);
			}else{
				var total = addCommas(berat*8500);
			}

			$('#val-packing').text("Estimasi Biaya Packing kayu  Rp. "+ total);

		} else if (packing == '1') {
			$('#val-packing').hide();
			$('#val-packing').val("");
			//alert('if non packing');
		}
			//alert('check 2'+berat+total);
	});

	$('#detailbarang_plus').click(function () {
		var jumlah_detailbarang   = $('#jumlah_detailbarang').val();

		var collie   = $('#collie').val();
		//alert ('maaf sudah maksimal');
		var total = 0;

		if (jumlah_detailbarang == 40){
			alert ('maaf sudah maksimal');
		}
		else{

			total = parseInt(jumlah_detailbarang)+1;
			$('#jumlah_detailbarang').val(total);


			
			for (i = 2; i < 21; i++) {
			  $('#barisdetail_barang'+i).hide();
			//  alert(i);

			}
			//var koli = 0;
			var jumlah_detailbarang =  parseInt($('#jumlah_detailbarang').val())+1;

			//koli = collie+1;

			//alert(collie+'koli ='+koli);

			for (i = 2; i < jumlah_detailbarang; i++) {
			  $('#barisdetail_barang'+i).show();
			//  alert(i);

			}

		$('.d_koli_ke').attr('max',collie);


		}
		
	});



	$('#detailbarang_min').click(function () {
		var jumlah_detailbarang   = $('#jumlah_detailbarang').val();

		var total = 0;

		if (jumlah_detailbarang == 1){
			alert ('maaf sudah minimal');
		}
		else{
			total = parseInt(jumlah_detailbarang)-1;
			$('#jumlah_detailbarang').val(total);

			
			for (i = 2; i < 21; i++) {
			  $('#barisdetail_barang'+i).hide();
			//  alert(i);

			}
			//var koli = 0;
			var jumlah_detailbarang =  parseInt($('#jumlah_detailbarang').val())+1;

			//koli = collie+1;

			//alert(collie+'koli ='+koli);

			for (i = 2; i < jumlah_detailbarang; i++) {
			  $('#barisdetail_barang'+i).show();
			//  alert(i);

			}
		}
		
	});

	$('#jumlah_detailbarang').on('change', function () {
			alert('test');


		//weight

		for (i = 2; i < 21; i++) {
		  $('#barisdetail_barang'+i).hide();

		 // $('#catatanbaru'+i).hide();
		//  alert(i);

		}
		//var koli = 0;
		var barisdetail_barang =  parseInt($(this).val())+1;

		//koli = collie+1;

		//alert(collie+'koli ='+koli);

		for (i = 2; i < collie; i++) {
		  $('#barisdetail_barang'+i).show();
		 // $('#catatanbaru'+i).hide();
		//  alert(i);

		}


		

	});

	$('#berat_barang').on('change', function () {
		var berat = $(this).val();
		if  (berat <= 10){
				var total = addCommas(10*8500);
			}else{
				var total = addCommas(berat*8500);
			}

			$('#val-packing').text("Estimasi Biaya Packing kayu  Rp. "+ total);
	});

	$('#collie').on('change', function () {

		var collie_real =  $(this).val();
			//alert('test');

		for (i = 2; i < 11; i++) {
		  $('#catatan'+i).hide();
		  
		//  alert(i);

		}
		//var koli = 0;
		var collie =  parseInt($(this).val())+1;

		//koli = collie+1;

		//alert(collie+'koli ='+koli);

		for (i = 2; i < collie; i++) {
		  $('#catatan'+i).show();

		//  alert(i);

		}




		//weight

		for (i = 2; i < 41; i++) {
		  $('#detail_collie'+i).hide();

		 // $('#catatanbaru'+i).hide();
		//  alert(i);

		}
		//var koli = 0;
		var detail_collie =  parseInt($(this).val())+1;

		//koli = collie+1;

		//alert(collie+'koli ='+koli);

		for (i = 2; i < collie; i++) {
		  $('#detail_collie'+i).show();
		 // $('#catatanbaru'+i).hide();
		//  alert(i);

		}


		for (i = 2; i < 41; i++) {
		 // $('#detail_collie'+i).show();
		  $('#catatanbaru'+i).hide();
		  $('#catatanbaru'+i).prop("disabled", true);
		  $('#catatanbaru'+i).prop("required", false);
		//  alert(i);

		}

		for (i = 2; i < collie; i++) {
		 // $('#detail_collie'+i).show();
		  $('#catatanbaru'+i).show();
		  $('#catatanbaru'+i).prop("disabled", false);
		  $('#catatanbaru'+i).prop("required", true);

		//  alert(i);

		}

		$('.d_koli_ke').attr('max',collie_real);

	});

	// komoditi
	$('#yesCheck1').click(function () {
		var yesCheck1 = $(this).val();
		if (yesCheck1 == '1') {
			$('#ifYes').show();
		}
	});
	$('#yesCheck2').click(function () {
		var yesCheck2 = $(this).val();
		if (yesCheck2 == '2') {
			$('#ifYes').hide();
		}
	});

	// asuransi
	$('#yesAsuransi').click(function () {
		var yesAsuransi = $(this).val();
		$('#nilai_barang').val(100000);
		//alert('1');

		//alert('2');
		if (yesAsuransi == 'Y') {
			$('#ifAsuransi').show();
			$('#val-asu').show();

			var nilai_barang = $('#nilai_barang').val();
			total = 0.25/100*nilai_barang;

			if(total <= 50000){
				total_asu = addCommas(50000);
			}else{
				total_asu = addCommas(total);
			}


			$('#val-asu').text('Estimasi Premi Asuransi Rp '+total_asu);
		}
		//alert('3');
	});

	$('.surat_jalan').click(function () {
		var yesSurat = $(this).val();

		//alert('1'+yesSurat);

		//alert('2');
		if (yesSurat == 'Y') {
			$('#ifyessuratjalan').show();
		}
		else{
			$('#ifyessuratjalan').hide();
		}
		//alert('3');
	});



	$('#nilai_barang').on('change', function () {
		var nilai_barang = $(this).val();
		total = 0.25/100*nilai_barang;

			if(total <= 50000){
				total_asu = addCommas(50000);
			}else{
				total_asu = addCommas(total);
			}
			$('#val-asu').text('Estimasi Premi Asuransi Rp '+total_asu);
	});

	$('#noAsuransi').click(function () {
		var noAsuransi = $(this).val();
		if (noAsuransi == 'N') {
			$('#ifAsuransi').hide();
			$('#val-asu').hide();
			$('#val-asu').text('');
		}
	});

	$('#yesdetailbarang').click(function () {
		//alert('oke');
		var yesdetailbarang = $(this).val();
		

		//alert('2');
		if (yesdetailbarang == 'Y') {
			$('#ifdetailbarang').show();
			

			
		}
		//alert('3');
	});

	$('#nodetailbarang').click(function () {
		//alert('oke no');
		var nodetailbarang = $(this).val();
		if (nodetailbarang == 'N') {
			$('#ifdetailbarang').hide();
			
		}
	});

	// pembayaran
	$('#pengirim').click(function () {
		var pengirim = $(this).val();
		if (pengirim == '1') {
			$('#ifYes2').hide();
		}
	});
	$('#penerima').click(function () {
		var penerima = $(this).val();
		if (penerima == '2') {
			$('#ifYes2').hide();
		}
	});
	$('#pihak3').click(function () {
		var pihak3 = $(this).val();
		if (pihak3 == '3') {
			$('#ifYes2').show();
		}
	});
	$('#tunai').click(function () {
		var tunai = $(this).val();
		if (tunai == '4') {
			$('#ifYes2').show();
		}
	});

	// validate booking inbound
	$("#form-bookingin").validate({
		ignore: [],
		rules: {
			id_kustomer: {
				required: true
			},
			loc_code: {
				required: true
			},
			rid: {
				required: true
			},
			nama_pengirim: {
				required: true
			},
			alamat_pengirim: {
				required: true
			},
			hp_pengirim: {
				number: true
			},
			nama_penerima: {
				required: true
			},
			alamat_penerima: {
				required: true
			},
			hp_penerima: {
				number: true
			},
			lokasi_asal: {
				required: true
			},
			lokasi_tujuan: {
				required: true
			},
			service: {
				required: true
			},
			komoditi: {
				required: true
			},
			berat_barang: {
				number: true
			},
			length: {
				number: true
			},
			width: {
				number: true
			},
			height: {
				number: true
			},
			jumlah_collie: {
				number: true
			},
			agree: {
				required: true
			}
		},
		messages: {
			service: {
				required: ""
			},
			komoditi: {
				required: ""
			},
			agree: {
				required: ""
			}
		},
		highlight: function (element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function (element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function (error, element) {
			if (element.hasClass('select2')) {
				error.insertAfter(element.parent().find('.val-message'));
			} else if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		}
	});

	// validate forgot password
	$("#form-forgotpassword").validate({
		rules: {
			password: {
				required: true,
				minlength: 5
			},
			confirm_password: {
				required: true,
				minlength: 5,
				equalTo: "#password"
			},
		},
		highlight: function (element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function (element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function (error, element) {
			if (element.hasClass('select2')) {
				error.insertAfter(element.parent().find('.val-message'));
			} else if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		}
	});

	// kustomer loc code
	$('#id_kustomerin').on('change', function () {
		id_kustomer = $('#id_kustomerin option:selected').val();
		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: {
				id_kustomer: id_kustomer
			},
			url: 'module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=kustomer',
			success: function (result) {
				$('#id_cabang').val(result['id_cabang']);
				if (result.loc_code == 'Y') {
					$('#loc_code').show();
					$('#input_loccode').prop("disabled", false);
					$('#pid').prop("disabled", false);
					$('#pidkolom').show();
				} else {
					$('#loc_code').hide();
					$("#input_loccode").prop('disabled', true);
					$("#pid").prop('disabled', true);
					$('#pidkolom').hide();
				}
			}
		});
	});

	$('#booking_gudang').on('change', function () {
		id_gudang = $('#booking_gudang option:selected').val();
		collie = $('#collie').val();
		id_kustomer = $('#id_kustomerin option:selected').val();
		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: {
				id: id_gudang
			},
			url: 'module/mod_warehouse-booking/booking-warehouse_action.php?module=booking-warehouse&act=alamat-gudang',
			success: function (result) {
				//alert('oke');
				//alert(result.alamat_awal);
				$('#alamat_pengirim1').val(result.alamat_awal);
				$('#alamat_pengirim2').val(result.alamat_lanjutan);

				$('#provinsiasal').val(result.id_provinsi);
				$('#provinsiasal_text').val(result.provinsi_text);
				$('#kotaasal').val(result.id_kota_baru);
				$('#kotaasal_text').val(result.kota_text);
				$('#kecamatanasal').val(result.kecamatan);
				$('#kecamatanasal_text').val(result.kecamatan_text);

				$.ajax({
					type: 'POST',
					dataType: 'json',
					data: {
						id_kust = id_kustomer
					},
				

					url: 'module/mod_warehouse-booking/booking-warehouse_action.php?module=booking-warehouse&act=data-barang-keluar',
					success: function (result2) {
						
						var j;
						for (j = 1; j <= collie; ++j) {
							$('select#catatanbaru'+j).children('option:not(:first)').remove();
						    $.each(result2, function(i, value) {
								$('select#catatanbaru'+j).append($('<option>').text(value.nama_barang).attr('value', value.id));
							});
							//alert(result2);
						}
						
					}

				});
			}
		});
	});

	//booking_gudang

	// cek outbound
	$('#outbound').bind('change', function () {
		id_kustomer = $('#id_kustomerin option:selected').val();
		var delay = 2000;
		if ($(this).is(':checked')) {
			$.ajax({
				type: 'POST',
				dataType: 'json',
				data: {
					id_kustomer: id_kustomer
				},
				url: 'module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=kustomer',
				success: function (result) {
					$('#provinsiasal').val(result['provinsi']).trigger('change');
					$('#alamat_pengirim1').val(result['alamat1']);



					setTimeout(function() {
                            $('#kotaasal').val(result['kota']).trigger('change');
                        }, 500);

					$('#alamat_pengirim2').val(result['alamat2']);

					setTimeout(function() {
                            $('#kecamatanasal').val(result['kecamatan']).trigger("change");
                        }, 1000);


					$('#perusahaan_pengirim').val(result['nama_kustomer']);

					$('#hp_pengirim').val(result['telp']);
					$('#nama_pengirim').val(result['pic_utama']);

					setTimeout(function() {
                            $('#kelurahanasal').val(result['kelurahan']).trigger("change");
                        }, 1500);


				}
			});
		} else {
			$('#provinsiasal').val('').trigger("change");
					$('#alamat_pengirim1').val('');
					$('#kotaasal').val('');
					$('#alamat_pengirim1').val('');
					$('#kecamatanasal').val('');
					$('#perusahaan_pengirim').val(result['nama_kustomer']);

					$('#hp_pengirim').val('');
					$('#nama_pengirim').val('');
					$('#kelurahanasal').val('');
		}
	});

	// autocomplete pengirim
	$("#nama_pengirim").autocomplete({
		source: function (request, response) {
			$.ajax({
				url: 'module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pengirim',
				dataType: "json",
				data: {
					term: request.term,
					id_kustomer: $("#id_kustomerin").val(),
					outbound: $("#outbound").val()
				},
				success: function (data) {
					response(data);
				}
			});
		},
		min_length: 3,
		delay: 300,
		select: function (event, ui) {
			$("#nama_pengirim").val(ui.item.value);
			$("#perusahaan_pengirim").val(ui.item.perusahaan_pengirim);
			$("#hp_pengirim").val(ui.item.hp_pengirim);

			$("#alamat_pengirim1").val(ui.item.alamat_pengirimbaru1);
			$("#alamat_pengirim2").val(ui.item.alamat_pengirimbaru2);



			$('#provinsiasal').val(ui.item.id_provinsi_asal).trigger('change');


			setTimeout(function() {
                $('#kotaasal').val(ui.item.id_kota_asal).trigger('change');
            }, 500);

			setTimeout(function() {
                $('#kecamatanasal').val(ui.item.id_kecamatan_asal).trigger('change');
        	}, 1000);

			setTimeout(function() {
                $('#kelurahanasal').val(ui.item.kelurahan_asal).trigger('change');
            }, 1500);
		}
	});

	// lokasi asal
	$('#lokasi_asal').on('change', function () {
		lokasi_asal = $('#lokasi_asal option:selected').val();
		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: {
				lokasi_asal: lokasi_asal
			},
			url: 'module/mod_bookingin/bookingin_action.php?module=bookingin&act=intercity',
			success: function (result) {
				$('#intercityasal').val(result['intercity']);
			}
		});
	});

	// autocomplete penerima inbound
	$("#nama_penerimain").autocomplete({
		source: function (request, response) {
			$.ajax({
				url: 'module/mod_bookingin/bookingin_action.php?module=bookingin&act=penerima',
				dataType: "json",
				data: {
					term: request.term,
					id_kustomer: $("#id_kustomerin").val()
				},
				success: function (data) {
					response(data);
				}
			});
		},
		min_length: 3,
		delay: 300,
		select: function (event, ui) {
			$("#nama_penerimain").val(ui.item.value);
			$("#alamat_penerima").val(ui.item.alamat_penerima);
			$("#hp_penerima").val(ui.item.hp_penerima);
			$("#perusahaan_penerima").val(ui.item.perusahaan_penerima);
			$("#intercitytujuan").val(ui.item.intercitytujuan);
			//$("#lokasi_tujuan").val(ui.item.lokasi_tujuan);
			var option, $select = jQuery('#lokasi_tujuan');
			option = $select.find('option[value="' + ui.item.lokasi_tujuan + '"]')[0];
			if ('undefined' !== typeof option) {
				$select.select2('val', ui.item.lokasi_tujuan);
			}
		}
	});

	// autocomplete pembayaran
	$("#nama_pembayaran").autocomplete({
		source: function (request, response) {
			$.ajax({
				url: 'module/mod_booking/booking_action.php?module=booking&act=pembayaran',
				dataType: "json",
				data: {
					term: request.term,
					id_kustomer: $("#id_kustomer").val()
				},
				success: function (data) {
					response(data);
				}
			});
		},
		min_length: 3,
		delay: 300,
		select: function (event, ui) {
			$("#nama_pembayaran").val(ui.item.value);
			$("#alamat_pembayaran").val(ui.item.alamat_pembayaran);
			$("#cp_pembayaran").val(ui.item.cp_pembayaran);
			$("#telp_pembayaran").val(ui.item.telp_pembayaran);
		}
	});

	// autocomplete pembayaran inbound
	$("#nama_pembayaranin").autocomplete({
		source: function (request, response) {
			$.ajax({
				url: 'module/mod_bookingin/bookingin_action.php?module=bookingin&act=pembayaran',
				dataType: "json",
				data: {
					term: request.term,
					id_kustomer: $("#id_kustomerin").val()
				},
				success: function (data) {
					response(data);
				}
			});
		},
		min_length: 3,
		delay: 300,
		select: function (event, ui) {
			$("#nama_pembayaranin").val(ui.item.value);
			$("#alamat_pembayaranin").val(ui.item.alamat_pembayaran);
			$("#cp_pembayaranin").val(ui.item.cp_pembayaran);
			$("#telp_pembayaranin").val(ui.item.telp_pembayaran);
		}
	});

	// number format
	$('input.number').keyup(function (event) {
		if (event.which >= 37 && event.which <= 40) {
			event.preventDefault();
		}

		$(this).val(function (index, value) {
			return value
				.replace(/\D/g, "")
				.replace(/([0-9])([0-9]{0})$/, '$1')
				.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
		});
	});

	// validate pickup
	$("#form-pickup").validate({
		rules: {
			pickup_by: {
				required: true
			}
		},
		highlight: function (element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function (element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function (error, element) {
			if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		}
	});

	// delivery
	$(document).on('click', '.open_modal', function (e) {
		e.preventDefault();
		$("#confirm-delivery").modal('show');
		$.post('module/mod_delivery/delivery_action.php?module=delivery&act=detail', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	});

	// switch
	$(document).on('click', '.open_switch', function (e) {
		e.preventDefault();
		$("#switch-courier").modal('show');
		$.post('module/mod_delivery/delivery_action.php?module=delivery&act=switch', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	});

	// switch pickup
	$(document).on('click', '.open_switch_pickup', function (e) {
		e.preventDefault();
		$("#switch-courier-pickup").modal('show');
		$.post('module/mod_pickup/pickup_action.php?module=pickup&act=switch', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	});

	$(document).on('click', '.open_switch_outbound', function (e) {
		e.preventDefault();
		$("#switch-courier-outbound").modal('show');
		$.post('module/mod_report-manifest-outbound/report-manifest-outbound_action.php?module=outbound&act=switch', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	});

	$(document).on('click', '.open_switch_outbound_detail', function (e) {
		e.preventDefault();
		$("#switch-courier-outbound-detail").modal('show');
		$.post('module/mod_report-manifest-outbound/report-manifest-outbound_action.php?module=outbound&act=switch-detail', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	});

	$(document).on('click', '.open_koli_detail', function (e) {
		e.preventDefault();
		$("#koli-detail").modal('show');
		$.post('module/mod_costing-outbound/costing-outbound_action.php?module=costing-outbound&act=detail-collie', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	});

	$(document).on('click', '.open_switch_pickup', function (e) {
		e.preventDefault();
		$("#switch-courier-pickup").modal('show');
		$.post('module/mod_pickup/pickup_action.php?module=pickup&act=switch', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	});

	// cancel airwaybill
	$(document).on('click', '.open_cancel', function (e) {
		e.preventDefault();
		$("#cancel-airwaybill").modal('show');
		$.post('module/mod_airwaybill/airwaybill_action.php?module=airwaybill&act=cancel', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	});

	// pickup assignment
	$(document).on('click', '.open_assignment', function (e) {
		e.preventDefault();
		$("#pickup-assignment").modal('show');
		$.post('module/mod_manifest-pickup/manifest-pickup_action.php?module=manifest-pickup&act=assignment', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	});

	// outbound assignment
	$(document).on('click', '.open_outbound-assignment', function (e) {
		e.preventDefault();
		$("#outbound-assignment").modal('show');
		$.post('module/mod_manifest-outbound/manifest-outbound_action.php?module=manifest-outbound&act=assignment', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	});

	$(document).on('click', '.open_outbound-assignment', function (e) {
		e.preventDefault();
		$("#outbound-assignment").modal('show');
		$.post('module/mod_manifest-outbound/manifest-outbound_action.php?module=manifest-outbound&act=assignment', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	});

	$(document).on('click', '#open_serial_number', function (e) {
		e.preventDefault();
		$("#modal_sn").modal('show');
		$.post('module/mod_warehouse-barang/barang_action.php?module=barang&act=edit-sn', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	});


	$(document).on('click', '.open_outbound-smu', function (e) {
		e.preventDefault();
		$("#outbound-assignment").modal('show');
		$.post('module/mod_manifest-outbound/manifest-outbound_action.php?module=manifest-outbound&act=smu', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	});

	// inbound assignment
	$(document).on('click', '.open_inbound-received', function (e) {
		e.preventDefault();
		$("#inbound-received").modal('show');
		$.post('module/mod_manifest-inbound/manifest-inbound_action.php?module=manifest-inbound&act=received', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	});

	// manifest
	$(document).on('click', '.open_manifest-delivery', function (e) {
		e.preventDefault();
		$("#manifest-delivery").modal('show');
		$.post('module/mod_manifest/manifest_action.php?module=manifest&act=assignment', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	});

	// add loc code users client
	$(document).on('click', '#open_user_add_loc_code', function (e) {
		e.preventDefault();
		$("#user_add_loc_code_otomatis").modal('show');
		$.post('module/mod_users/users_action.php?module=users&act=new_loc_code', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	});

	// add loc code users client
	$(document).on('click', '#inv_open_add_awb', function (e) {
		e.preventDefault();
		$("#inv_add_awb").modal('show');
		$.post('module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=addawb', {
				id: $(this).attr('data-id')
			},
			function (html) {
				//$(".modal-body").html(html);
			}
		);
	});

	var inv_open_awb_not_billed = 0;
	$('#inv_open_awb_not_billed').on('click', function (e) {
		var initTable = 0;
        if (inv_open_awb_not_billed == 0) {
            inv_open_awb_not_billed = 1;
            var table = $("#awbNotBilled");
            table.DataTable({
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "ajax": {
                    url: "module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=not-billed&id="+$(this).attr('data-id')
                },
                "order": [
                    [2, "desc"]
                ],
                "columnDefs": [
                    { targets: [0,1], orderable: false }
                ],
                "drawCallback": function( settings, data ) {

                    $('#addAwbBulk').on('click', function (e) {
                        $('#processing-modal').modal('show');

                        $.ajax({
                            type: "POST",
                            dataType: "text",
                            url: $("#frmAddAwbBulk").attr('action'),
                            data: $("#frmAddAwbBulk").serialize(),
                            success: function(msg){
                                //$('#processing-modal').modal('hide');
                                location.reload();
                            }
                        });
                    });

                    $("#pengiriman_bulk_all").click(function(){
                    	$('#frmAddAwbBulk input:checkbox').not(this).prop('checked', this.checked);
                    });

                }
            });

            $('#awbNotBilled_filter input').unbind();
            $('#awbNotBilled_filter input').bind('keyup', function(e) {
                if (e.keyCode == 13) {
                    $('#awbNotBilled').dataTable().fnFilter(this.value);
                }
            });
        }else{
            //$('#awbNotBilled').DataTable().ajax.reload();
		}
	});



	// import penerima
	$(document).on('click', '.open_import-penerima', function (e) {
		e.preventDefault();
		$("#import-penerima").modal('show');
		$.post('module/mod_airwaybill/airwaybill_action.php?module=airwaybill&act=importpenerima', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	});

	// airwaybill detail
	$(document).on('click', '.airwaybill_detail', function (e) {
		e.preventDefault();
		$("#airwaybill_detail").modal('show');
		$.post('module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=airwaybill', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	});

	$(document).on('click', '.custom_price_insert', function (e) {
		e.preventDefault();
		$("#modal_price_insert").modal('show');
		$.post('module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=form-insert-price', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$("#modal_price_insert .modal-body").html(html);
			}
		);
	});




	$(document).on('click', '.custom_price_insert_agent', function (e) {
		e.preventDefault();
		$("#modal_price_insert_agent").modal('show');
		$.post('module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=form-insert-price', {
				id: $(this).attr('data-id'),
				pickup: $(this).attr('id')
			},
			function (html) {
				$("#modal_price_insert_agent .modal-body").html(html);
			}
		);
	});
	$(document).on('click', '.custom_price_edit', function (e) {
		e.preventDefault();
		$("#modal_price_edit").modal('show');
		$.post('module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=form-edit-price', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$("#modal_price_edit .modal-body").html(html);
			}
		);
	});

	$(document).on('click', '.custom_total_fix', function (e) {
		e.preventDefault();
		$("#modal_total_fix .modal-body").html('');
		$("#modal_total_fix").modal('show');
		$.post('module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=form-total-fix', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$("#modal_total_fix .modal-body").html(html);
			}
		);
	});

	$(document).on('click', '.custom_harga_kg', function (e) {
		e.preventDefault();
		$("#modal_harga_kg").modal('show');
		$.post('module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=form-harga-kg', {
				id: $(this).attr('data-id'),
				rate_id : $(this).attr('data-id-rate'),
				asal : $(this).attr('data-asal'),
				tujuan : $(this).attr('data-tujuan'),
				kustomer : $(this).attr('data-kustomer'),
				kec_asal : $(this).attr('data-kecamatan-asal'),
				kec_tujuan : $(this).attr('data-kecamatan-tujuan'),
			},
			function (html) {
				$("#modal_harga_kg .modal-body").html(html);
			}
		);
	});
	$(document).on('click', '#klikedit-duedate', function (e) {
		//alert('berhasil');
		e.preventDefault();
		datadate = $(this).attr('data-id');
		$("#modal_duedate").modal('show');
		$("#modal_duedate #tanggal").val(datadate);
	});

	$(document).on('click', '#klikedit-judulalamat', function (e) {
		//alert('berhasil');
		e.preventDefault();
		editalamat = $(this).attr('data-id');
		$("#modal_editalamat").modal('show');
		$("#modal_editalamat #judulalamat").val(editalamat);
	});

	$(document).on('click', '#klikedit-dateinv', function (e) {
		//alert('berhasil');
		e.preventDefault();
		datadate = $(this).attr('data-id');
		$("#modal_dateinv").modal('show');
		$("#modal_dateinv #tanggal").val(datadate);
	});

	$(document).on('click', '#klikedit-noinv', function (e) {
		//alert('berhasil');
		e.preventDefault();
		no = $(this).attr('data-id');
		$("#modal_noinv").modal('show');
		$("#modal_noinv #noinv").val(no);
	});

	$(document).on('click', '#edit-subtotal', function (e) {
		//alert('berhasil');
		e.preventDefault();
		datasub = $(this).attr('data-id');
		dataval = $(this).attr('data-val');
		$("#modal_subtotaljudul").modal('show');
		$("#modal_subtotaljudul #editsubtotaljudul").val(dataval);
	});

	$(document).on('click', '#edit-judulinv', function (e) {
		//alert('berhasil');
		e.preventDefault();
		datajudul = $(this).attr('data-id');
		dataval = $(this).attr('data-val');
		$("#modal_editjudulinv").modal('show');
		$("#modal_editjudulinv #editjudulinv").text(dataval);
	});

	$(document).on('click', '#edit-ppn', function (e) {
		//alert('berhasil');
		e.preventDefault();
		//datappn = $(this).attr('data-id');
		//dataval = $(this).attr('data-val');
		$("#modal_editppn").modal('show');
		//$("#modal_editjudulinv #editjudulinv").text(dataval);
	});

	$(document).on('click', '#hapus-biayatambahan', function (e) {
		//alert('berhasil');
		e.preventDefault();
		iddata = $(this).attr('data-id');
		//dataval = $(this).attr('data-val');
		$("#modal_confirm_hapusbiayatambahan").modal('show');
		$("#modal_confirm_hapusbiayatambahan #idbiayatambahan").val(iddata);
	});

	// latest booking
	/*function latestbooking() {
		$('#latest-booking').load('script.php?module=script&act=latestbooking', function () {
			$(this).html();
		});
	}
	latestbooking(); // This will run on page load
	setInterval(function () {
		latestbooking() // this will run after every 5 seconds
	}, 30000);

	// latest pickup
	function latestpickup() {
		$('#latest-pickup').load('script.php?module=script&act=latestpickup', function () {
			$(this).html();
		});
	}
	latestpickup(); // This will run on page load
	setInterval(function () {
		latestpickup() // this will run after every 5 seconds
	}, 30000);*/

	// autocomplete pengirim
	$("#kode_rekening").autocomplete({
		source: function (request, response) {
			$.ajax({
				url: 'module/mod_finance_jurnal-umum/jurnal-umum_action.php?module=jurnal-umum&act=rekening',
				dataType: "json",
				data: {
					term: request.term
				},
				success: function (data) {
					response(data);
				}
			});
		},
		min_length: 3,
		delay: 300,
		select: function (event, ui) {
			$("#kode_rekening").val(ui.item.value);
			$("#nama_rekening").val(ui.item.nama_rekening);
		}
	});

	// kurir pickup costing
	$('#kurir-pickup-costing').on('change', function () {
		kurir = $('#kurir-pickup-costing option:selected').val();
		tanggal = $('#tanggal').val();
		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: {
				tanggal: tanggal,
				kurir: kurir
			},
			url: 'module/mod_costing-pickup/costing-pickup-intercity_action.php?module=costing-pickup-intercity&act=create',
			success: function (data) {
				response(data);
				$("#table-pickup-kurir").html(html);
			}
		});
	});

	$('#save_invagent').on('click', function () {
		tanggal_dari = $('#tgl_dr').val();
		tanggal_sampai = $('#tgl_sampai').val();
		id_agent = $('#id_agent').val();
		alert('save'+tanggal_dari+tanggal_sampai+id_agent);


		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: {
				tanggal_dr: tanggal_dari,
				tangal_smp: tanggal_sampai,
				agent_id: id_agent
			},
			url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?act=save',
			success: function (data) {
				response(data);
				//$("#table-pickup-kurir").html(html);
				alert('awal');
			}
		});
	});

	/*$('#calculate-costing-pickup').on('click', function () {
		tglpickup = $('#tglpickup').val();
		costing_pickup_by = $('#costing_pickup_by').val();
		pickup_cost = $('#pickup_cost').val();
		parking_fee = $('#parking_fee').val();
		toll_fee = $('#toll_fee').val();
		$.ajax({
			type: 'POST',
			url: 'module/mod_costing-pickup/costing-pickup-intercity_action.php?module=costing-pickup-intercity&act=calculate',
			data: {
				tglpickup: tglpickup,
				costing_pickup_by: costing_pickup_by,
				pickup_cost: pickup_cost,
				parking_fee: parking_fee,
				toll_fee: toll_fee
			},
			crossDomain: true,
			cache: false,
			success: function(data){
				if (data === "success") {
					location.reload();
				} else {
					alert('Something wrong on server!!');
				}
			}
		});
		//alert(toll_fee);
		//location.reload();
	});*/

	$('#test-click').on('click', function () {
		alert('test-click');
	});

	$('.open_parkingcost').on('click', function (e) {
		e.preventDefault();
		//alert('buka');
		var id = $(this).attr('id');
		$("#modal-inputparkingcost").modal('show');
		$("#modal-inputparkingcost #idpengiriman").val(id);
		/*$("#confirm-delivery").modal('show');
		$.post('module/mod_delivery/delivery_action.php?module=delivery&act=detail', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);*/
	});

	$('.open_reject').on('click', function (e) {
		e.preventDefault();
		//alert('buka');
		var id = $(this).attr('id');
		$("#modal-rejectreason").modal('show');
		$("#modal-rejectreason #idpengiriman").val(id);
		/*$("#confirm-delivery").modal('show');
		$.post('module/mod_delivery/delivery_action.php?module=delivery&act=detail', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);*/
	});



	$(document).on('click', '.open_status', function (e) {
		e.preventDefault();
		$("#detail-status").modal('show');
		$.post('module/mod_airwaybill/airwaybill_action.php?module=airwaybill&act=status', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	});

	$(document).on('click', '.custom_price_insert_pickup', function (e) {
		e.preventDefault();
		$("#modal_price_insert_pickup").modal('show');
		$.post('module/mod_pickup/pickup_action.php?module=pickup&act=form-insert-price', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	});

	$(document).on('click', '.custom_sla_insert', function (e) {
		e.preventDefault();
		$("#modal_sla_insert").modal('show');
		$.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=form-insert-sla', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	});
	$(document).on('click', '.custom_slaoutbound_insert', function (e) {
		e.preventDefault();
		$("#modal_sla_insert").modal('show');
		$.post('module/mod_manifest-inbound/manifest-inbound_action.php?module=manifest-inbound&act=form-insert-sla', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	});


	$('.open_move').on('click', function (e) {
		e.preventDefault();
		//alert('buka');
		var id = $(this).attr('id');
		$("#modal-movecesco").modal('show');
		$("#modal-movecesco #idpengiriman").val(id);
		/*$("#confirm-delivery").modal('show');
		$.post('module/mod_delivery/delivery_action.php?module=delivery&act=detail', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);*/
	});

	$('.open_mencost').on('click', function (e) {
		e.preventDefault();
		//alert('buka');
		var id = $(this).attr('id');
		$("#modal-inputmencost").modal('show');
		$("#modal-inputmencost #idpengiriman").val(id);
		/*$("#confirm-delivery").modal('show');
		$.post('module/mod_delivery/delivery_action.php?module=delivery&act=detail', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);*/
	});

	//edit
	$('.edit_parking_cost').on('click', function (e) {
		e.preventDefault();
		//alert('buka');
		var id = $(this).attr('id');
		var val_edit = $(this).attr('data-id');
		$("#modal-editparkingcost").modal('show');
		$("#modal-editparkingcost #idpengiriman").val(id);
		$("#modal-editparkingcost #parking_fee_edit").val(val_edit);
		/*$("#confirm-delivery").modal('show');
		$.post('module/mod_delivery/delivery_action.php?module=delivery&act=detail', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);*/
	});

	$('.edit_men_cost').on('click', function (e) {
		e.preventDefault();
		//alert('buka');
		var id = $(this).attr('id');
		var val_edit = $(this).attr('data-id');
		$("#modal-editmencost").modal('show');
		$("#modal-editmencost #idpengiriman").val(id);
		$("#modal-editmencost #men_power_edit").val(val_edit);
		/*$("#confirm-delivery").modal('show');
		$.post('module/mod_delivery/delivery_action.php?module=delivery&act=detail', {
				id: $(this).attr('data-id')
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);*/
	});

	$('#open_tambah_biaya').on('click', function (e) {
		e.preventDefault();
		//alert('buka');
		//var id_inv = $(this).attr('id');
		var val_edit = $(this).attr('data-id');
		$("#modal_tambah_biaya").modal('show');
		$("#modal_tambah_biaya #idinvoice").val(val_edit);

	});



	$('#id_kustomer_inv').on('change', function () {
		id_kustomer = $('#id_kustomer_inv option:selected').val();
		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: {
				id_kustomer: id_kustomer
			},
			url: 'module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=loccode',
			success: function (result) {

				if (result.cek.loc_code == 'Y') {
					$('#loc_code').show();
					$('select#loc_code_inv').children('option:not(:first)').remove();
					$.each(result.loccode, function(i, value) {
						$('select#loc_code_inv').append($('<option>').text(value).attr('value', value));
					});
				} else {
					$('#loc_code').hide();
				}
			}
		});

		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: {
				id_kustomer: id_kustomer
			},
			url: 'module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=choose_shipment',
			success: function (result) {
				$('select#data_pengiriman_inv').empty();
				$.each(result, function(i, value) {
					$('select#data_pengiriman_inv').append($('<option>').text(value.airwaybill_kode).attr('value', value.id_pengiriman));
				});
			}
		});
	});
	$('.choose_method').click(function () {
		///alert('test');
		var method = $(this).val();
		if (method == '1') {
			$('#ifrange').show();
			$('#ifchoose').hide();
		} else if (method == '2') {
			$('#ifchoose').show();
			$('#ifrange').hide();
		}
	});
	//$('#modal_price_insert #check-nextkg').click(function () {
	$('#modal_price_insert').on('click', '#check-nextkg', function () {
		//alert('buka');
		//var aktif = $(this).val();
		if ($(this).is(":checked")){
			$('#kolom-nextkg').show();
			$('#price-nextkg').prop("disabled", false);
		}
		else{
			$('#kolom-nextkg').hide();
			$('#price-nextkg').prop("disabled", true);
		}
	});
	$('#switch-courier-outbound-detail').on('click', '#checkswitchnew', function () {
		//alert('buka');
		//var aktif = $(this).val();
		if ($(this).is(":checked")){
			$('.switch-new').show();
			$('.switch-section').hide();

			//$('#price-nextkg').prop("disabled", false);
		}
		else{
			$('.switch-new').hide();
			$('.switch-section').show();
		}
	});
	$("#btnsearch").click(function() {
		var strcari = $("#searchawb").val();
		//alert(strcari);
		$("#btnsearch").prop("disabled", true);
		$("#load").show();
		$.ajax({
			type: 'POST',


			data: {
				q: strcari
			},
			url: 'module/mod_airwaybill/airwaybill_action.php?module=airwaybill&act=searchawb',
			success: function(result) {
				$("#load").hide();
				//alert('berhasil');
				$('tr#hasil').html(result);
				//alert('berhasil2');

				//$('#hasildisini').html(result);
				//alert('berhasil3');
				$("#btnsearch").prop("disabled", false);
			}
		});

	});

	$("#btnsearchcosting").click(function() {
		//alert('test1');
		var strcari = $("#searchawb").val();
		var modul = $(this).attr('name');
		var folder = $(this).attr('data-id');
		//alert(strcari);
		$("#btnsearch").prop("disabled", true);
		$("#load").show();
		//alert('test2');
		$.ajax({
			type: 'POST',


			data: {
				q: strcari
			},
			url: 'module/mod_'+folder+'/'+modul+'_action.php?module='+modul+'&act=searchawb',
			success: function(result) {
				$("#load").hide();
				//alert('berhasil');
				$('tr#hasil').html(result);
				//alert('berhasil2');

				//$('#hasildisini').html(result);
				//alert('berhasil3');
				$("#btnsearch").prop("disabled", false);
			}
		});
	});
	//klik check ll
	$("#checkall").click(function() {
		//alert('test1');

		//$("#btnsearch").prop("disabled", true);

		if ($('#report .checkdeleteawb').is(':checked') ==  false)
	    {
	    //	alert('test if');
	        $('#report .checkdeleteawb').prop('checked', true);
	        $("#report .checkdeleteawb").attr("checked", true);
	        $(".testchecked").attr("checked", true);
	    }
	    else
	    {
	    //	alert('test else');
	        $('#report .checkdeleteawb').prop('checked', false);
	    }
	});

	// end check all

	$('.tagihkan_baru').change(function () {
		var total = 0;
		//alert('cek');
		//var vall = $(this).attr('data-id');
		//var preview = $(".tablebawah #preview").text();
		//var tpreview = $(".tablebawah #testpreview").val();
		 $('.tagihkan_baru:checked').each(function(){
		 	total += isNaN(parseInt($(this).attr('data-id'))) ? 0 : parseInt($(this).attr('data-id'));
		 });


		//alert(total);
		//alert(vall);
		$(".tablebawah #preview").text(total);
		//$(".tablebawah #testpreview").val(total);
		//alert('test2');

	});

	$("#provinsi").change(function(){
      // $("img#load1").show();
     // alert('sada');
       var id_provinces = $(this).val();

       var text_provinces = $("#provinsi option:selected").text();
       //alert (id_provinces+text_provinces);
       		$("#provinsi_text").val(text_provinces);

       $.ajax({
          type: "POST",
          dataType: "html",
          url: 'module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=data-wilayah&jenis=kota',
          data: "id_provinces="+id_provinces,
          success: function(msg){
             $("select#kota").html(msg);
           //  $("img#load1").hide();
             getAjaxKota();
          }
       });
     });

 //saat pilihan kota di pilih, maka akan mengambil data kecamatan
 //di data-wilayah.php menggunakan ajax
 $("#kota").change(getAjaxKota);
     function getAjaxKota(){
        //  $("img#load2").show();
          var id_regencies = $("#kota").val();
          var text_regencies = $("#kota option:selected").text();
       //alert (id_provinces+text_provinces);
       		$("#kota_text").val(text_regencies);

          $.ajax({
             type: "POST",
             dataType: "html",
             url: 'module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=data-wilayah&jenis=kecamatan',
             data: "id_regencies="+id_regencies,
             success: function(msg){
                $("select#kecamatan").html(msg);
             //   $("img#load2").hide();
               getAjaxKecamatan();
             }
          });

     }


     //saat pilihan kecamatan di pilih, maka akan mengambil data kelurahan
 //di data-wilayah.php menggunakan ajax
     $("#kecamatan").change(getAjaxKecamatan);
     function getAjaxKecamatan(){
          //$("img#load3").show();
          var id_district = $("#kecamatan").val();

          var text_district = $("#kecamatan option:selected").text();
       //alert (id_provinces+text_provinces);
       		$("#kecamatan_text").val(text_district);
          $.ajax({
             type: "POST",
             dataType: "html",
             url: 'module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=data-wilayah&jenis=kelurahan',
             data: "id_district="+id_district,
             success: function(msg){
                $("select#kelurahan").html(msg);
            //    $("img#load3").hide();
             }
          });
     }

     //kota asal

     $("#provinsiasal").change(function(){
      // $("img#load1").show();
     // alert('sada');
       var id_provinces = $(this).val();
       var text_provinces = $("#provinsiasal option:selected").text();
       //alert (id_provinces+text_provinces);
       $("#provinsiasal_text").val(text_provinces);

       $.ajax({
          type: "POST",
          dataType: "html",
          url: 'module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=data-wilayah&jenis=kota',
          data: "id_provinces="+id_provinces,
          success: function(msg){
             $("select#kotaasal").html(msg);
           //  $("img#load1").hide();
             getAjaxKotaAsal();
          }
       });
     });

 //saat pilihan kota di pilih, maka akan mengambil data kecamatan
 //di data-wilayah.php menggunakan ajax
 $("#kotaasal").change(getAjaxKotaAsal);
     function getAjaxKotaAsal(){
        //  $("img#load2").show();
          var id_regencies = $("#kotaasal").val();
          var text_regencies = $("#kotaasal option:selected").text();

      	  $("#kotaasal_text").val(text_regencies);

          $.ajax({
             type: "POST",
             dataType: "html",
             url: 'module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=data-wilayah&jenis=kecamatan',
             data: "id_regencies="+id_regencies,
             success: function(msg){
                $("select#kecamatanasal").html(msg);
             //   $("img#load2").hide();
               getAjaxKecamatanAsal();
             }
          });
     }



     //saat pilihan kecamatan di pilih, maka akan mengambil data kelurahan
 //di data-wilayah.php menggunakan ajax
     $("#kecamatanasal").change(getAjaxKecamatanAsal);
     function getAjaxKecamatanAsal(){
          //$("img#load3").show();
          var id_district = $("#kecamatanasal").val();
          var text_district = $("#kecamatanasal option:selected").text();

      	  $("#kecamatanasal_text").val(text_district);
          $.ajax({
             type: "POST",
             dataType: "html",
             url: 'module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=data-wilayah&jenis=kelurahan',
             data: "id_district="+id_district,
             success: function(msg){
                $("select#kelurahanasal").html(msg);
            //    $("img#load3").hide();
             }
          });
     }

     $('#kotaasal').on('change', function () {
		kota = $('#kotaasal option:selected').val();
		var data_agent = $(this).attr('data');
		if(data_agent = '2'){
			urll="client/module/";
		}else{
			urll="module/";
		}
		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: {
				kota: kota
			},
			
			url: urll+'module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=intercity',
			
			
			success: function (result) {
				$('#intercityasal').val(result['intercity']);
			}
		});
	});

 	$('#kota').on('change', function () {
		kota = $('#kota option:selected').val();
		$.ajax({
			type: 'POST',
			dataType: 'json',
			data: {
				kota: kota
			},
			url: 'module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=intercity',
			success: function (result) {
				$('#intercitytujuan').val(result['intercity']);
			}
		});
	});

	$("#negara_tujuan").on('change', function () {
        //  $("img#load2").show();
          
          var negara_text = $("#negara_tujuan option:selected").text();
       
       		$("#kota_text").val(negara_text);

          

    });


     $('#submitDueDate').click(function () {
         $('#form_duedate').submit();
     })

	$('#submitPaidDate').click(function () {
         $('#form_paiddate').submit();
     })

    $('#form_duedate').on('submit', function(e) {
        $('#processing-modal').modal('show');
        $.ajax({
            type: "POST",
            dataType: "text",
            url: $("#form_duedate").attr('action'),
            data: $("#form_duedate").serialize(),
            success: function(msg){
            	//$('#InvoiceAgent').DataTable().ajax.reload();
				var idAgen = $("#modal-due-date #id_agen").val();
                var invDate = $("#modal-due-date #invoice_date").val();
                var dueDate = $("#modal-due-date #tanggal").val();

                $('#dueDate'+idAgen+''+invDate).text(dueDate);
                $('#dueDate'+idAgen+''+invDate).attr('data-pk', idAgen+'~'+invDate+'~'+dueDate+'~'+msg);
                $('#dueDate'+idAgen+''+invDate).removeClass('editable-empty');
                $('#dueDate'+idAgen+''+invDate).addClass('editable');

                var paidDate = $('#paidDate'+idAgen+''+invDate).text();
                paidDate = (paidDate == 'Empty') ? '' : paidDate;
                $('#paidDate'+idAgen+''+invDate).attr('data-pk', idAgen+'~'+invDate+'~'+paidDate+'~'+msg);

                $('#processing-modal').modal('hide');
        		$("#modal-due-date").modal('hide');
            }
        });

        e.preventDefault();
    });

    $('#form_paiddate').on('submit', function(e) {
        $('#processing-modal').modal('show');
        $.ajax({
            type: "POST",
            dataType: "text",
            url: $("#form_paiddate").attr('action'),
            data: $("#form_paiddate").serialize(),
            success: function(msg){
                //$('#InvoiceAgent').DataTable().ajax.reload();
                var idAgen = $("#modal-paid-date #id_agen").val();
                var invDate = $("#modal-paid-date #invoice_date").val();
                var paidDate = $("#modal-paid-date #tanggal").val();

                $('#paidDate'+idAgen+''+invDate).text(paidDate);
                $('#paidDate'+idAgen+''+invDate).attr('data-pk', idAgen+'~'+invDate+'~'+paidDate+'~'+msg);
                $('#paidDate'+idAgen+''+invDate).removeClass('editable-empty');
                $('#paidDate'+idAgen+''+invDate).addClass('editable');

                var dueDate = $('#dueDate'+idAgen+''+invDate).text();
                dueDate = (dueDate == 'Empty') ? '' : dueDate;
                $('#dueDate'+idAgen+''+invDate).attr('data-pk', idAgen+'~'+invDate+'~'+dueDate+'~'+msg);

                $('#processing-modal').modal('hide');
                $("#modal-paid-date").modal('hide');
            }
        });

        e.preventDefault();
    });

    $('.open_diskon').on('click', function (e) {
        e.preventDefault();
        //alert('buka');
        var id = $(this).attr('data-pk');
        $("#modal-opendiskon").modal('show');
        $("#modal-opendiskon#idpengiriman").val(id);
		/*$("#confirm-delivery").modal('show');
		 $.post('module/mod_delivery/delivery_action.php?module=delivery&act=detail', {
		 id: $(this).attr('data-id')
		 },
		 function (html) {
		 $(".modal-body").html(html);
		 }
		 );*/
    });

	$('#open_biaya_admin').on('click', function (e) {
		e.preventDefault();
		//alert('buka');
		//var id_inv = $(this).attr('id');
		var val_agent = $(this).attr('data-id');
		var val_month = $(this).attr('data-month');
		var val_biaya = $(this).attr('data-biaya');
		$("#modal_biaya_admin").modal('show');
		$("#modal_biaya_admin #idAgent").val(val_agent);
		$("#modal_biaya_admin #month").val(val_month);
		$("#modal_biaya_admin #biaya").val(val_biaya);

	});

	$('#open_nomor').on('click', function (e) {
		e.preventDefault();
		//alert('buka');
		//var id_inv = $(this).attr('id');
		var val_agent = $(this).attr('data-id');
		var val_month = $(this).attr('data-month');
		var val_nomor = $(this).attr('data-nomor');
		$("#modal_nomor").modal('show');
		$("#modal_nomor #idAgent").val(val_agent);
		$("#modal_nomor #month").val(val_month);
		$("#modal_nomor #nomor").val(val_nomor);

	});

});
