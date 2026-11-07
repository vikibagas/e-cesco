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

	$('#tanggal-diambil').daterangepicker({
        singleDatePicker: true,
		showDropdowns: false,
		format: 'DD-MM-YYYY',
		maxDate: new Date
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
	$('#confirm-delete').on('show.bs.modal', function(e) {
		$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
	});
	
	//confirm blocked
	$('#confirm-blocked').on('show.bs.modal', function(e) {
		$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
	});
	
	//iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
		checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });
	
	//uppercase for code
	$('#inputID').keyup(function(){
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
		highlight: function(element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function(error, element) {
			if(element.parent('.input-group').length) {
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
				remote : "This code is already taken! Try another."
			}
		},
		highlight: function(element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function(error, element) {
			if(element.hasClass('select2')) {
				error.insertAfter(element.parent().find('.val-message'));
			} else if(element.parent('.input-group').length) {
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
				remote : "This code is already taken! Try another."
			}
		},
		highlight: function(element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function(error, element) {
			if(element.hasClass('select2')) {
				error.insertAfter(element.parent().find('.val-message'));
			} else if(element.parent('.input-group').length) {
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
		highlight: function(element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function(error, element) {
			if(element.hasClass('select2')) {
				error.insertAfter(element.parent().find('.val-message'));
			}else if(element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			}else{
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
				remote : "This code is already taken! Try another."
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
		highlight: function(element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function(error, element) {
			if(element.hasClass('select2')) {
				error.insertAfter(element.parent().find('.val-message'));
			} else if(element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		}
	});

	// autofill customer
	$('#id_kustomer').on('change', function(){
		id_kustomer = $('#id_kustomer option:selected').val();
		$.ajax({
			type :'POST',
			dataType:'json',
			data : { id_kustomer : id_kustomer },
			url : 'module/mod_booking/booking_action.php?module=booking&act=kustomer',
			success : function(result){
				$('#alamat_pengirim').val(result['alamat']);
				$('#id_kota').val(result['kota']); 
				$('#id_cabang').val(result['id_cabang']);
				$('#intercityasal').val(result['intercityasal']);
				if (result.loc_code == 'Y'){
					$('#loc_code').show();
				}else{
					$('#loc_code').hide();
				}
			}
		});	
	});

	// autocomplete penerima
	$( "#nama_penerima" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: 'module/mod_booking/booking_action.php?module=booking&act=penerima',
				dataType: "json",
				data: {
					term : request.term,
					id_kustomer : $("#id_kustomer").val()
				},
				success: function(data) {
					response(data);
				}
			});
		},
		min_length: 3,
		delay: 300,
		select: function( event, ui ) {
			$("#nama_penerima").val( ui.item.value );
			$("#alamat_penerima").val( ui.item.alamat_penerima );
			$("#hp_penerima").val( ui.item.hp_penerima );
			$("#perusahaan_penerima").val( ui.item.perusahaan_penerima );
			$("#intercitytujuan").val( ui.item.intercitytujuan );
			//$("#lokasi_tujuan").val(ui.item.lokasi_tujuan);
			var option, $select = jQuery( '#lokasi_tujuan' );
			option = $select.find( 'option[value="' + ui.item.lokasi_tujuan + '"]' )[0];
			if ( 'undefined' !== typeof option ) {
				$select.select2('val', ui.item.lokasi_tujuan);
			}
		}
	});
	
	// lokasi tujuan
	$('#lokasi_tujuan').on('change', function(){
		lokasi_tujuan = $('#lokasi_tujuan option:selected').val();
		$.ajax({
		    type :'POST',
			dataType:'json',
			data : { lokasi_tujuan : lokasi_tujuan },
			url : 'module/mod_booking/booking_action.php?module=booking&act=intercity',
			success : function(result){
				$('#intercitytujuan').val(result['intercity']);
			}
		});
	});

	// komoditi	
	$('#yesCheck1').click(function() {
        var yesCheck1 = $(this).val();
		if (yesCheck1 == '1'){
			$('#ifYes').show();
		}
    });
	$('#yesCheck2').click(function() {
        var yesCheck2 = $(this).val();
		if (yesCheck2 == '2'){
			$('#ifYes').hide();
		}
    });

	// asuransi	
	$('#yesAsuransi').click(function() {
        var yesAsuransi = $(this).val();
		if (yesAsuransi == 'Y'){
			$('#ifAsuransi').show();
		}
    });
	$('#noAsuransi').click(function() {
        var noAsuransi = $(this).val();
		if (noAsuransi == 'N'){
			$('#ifAsuransi').hide();
		}
    });

	// pembayaran	
	$('#pengirim').click(function() {
        var pengirim = $(this).val();
		if (pengirim == '1'){
			$('#ifYes2').hide();
		}
    });
	$('#penerima').click(function() {
        var penerima = $(this).val();
		if (penerima == '2'){
			$('#ifYes2').hide();
		}
    });
	$('#pihak3').click(function() {
        var pihak3 = $(this).val();
		if (pihak3 == '3'){
			$('#ifYes2').show();
		}
    });
	$('#tunai').click(function() {
        var tunai = $(this).val();
		if (tunai == '4'){
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
			lokasi_tujuan: {
				required: true
			},
			lokasi_asal: {
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
		highlight: function(element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function(error, element) {
			if(element.hasClass('select2')) {
				error.insertAfter(element.parent().find('.val-message'));
			} else if(element.parent('.input-group').length) {
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
		highlight: function(element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function(error, element) {
			if(element.hasClass('select2')) {
				error.insertAfter(element.parent().find('.val-message'));
			} else if(element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		}
	});
	
	// kustomer loc code
	$('#id_kustomerin').on('change', function(){
		id_kustomer = $('#id_kustomerin option:selected').val();
		$.ajax({
			type :'POST',
			dataType:'json',
			data : { id_kustomer : id_kustomer },
			url : 'module/mod_bookingin/bookingin_action.php?module=bookingin&act=kustomer',
			success : function(result){
				$('#id_cabang').val(result['id_cabang']);
				if (result.loc_code == 'Y'){
					$('#loc_code').show();
				}else{
					$('#loc_code').hide();
				}
			}
		});	
	});
	
	// cek outbound
	$('#outbound').bind('change', function(){
		id_kustomer = $('#id_kustomerin option:selected').val();
		if($(this).is(':checked')){
			$.ajax({
				type :'POST',
				dataType:'json',
				data : { id_kustomer : id_kustomer },
				url : 'module/mod_bookingin/bookingin_action.php?module=bookingin&act=kustomer',
				success : function(result){
					$('#alamat_pengirim').val(result['alamat']);
					$('#perusahaan_pengirim').val(result['nama_kustomer']);
					$('#lokasi_asal').val(result['kota']).trigger("change");
					$('#intercityasal').val(result['intercityasal']);
				}
			});			
		}else{
			$('#alamat_pengirim').val('');
			$('#perusahaan_pengirim').val('');
			$('#lokasi_asal').val('').trigger("change");
		}
	});
	
	// autocomplete pengirim
	$( "#nama_pengirim" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: 'module/mod_bookingin/bookingin_action.php?module=bookingin&act=pengirim',
				dataType: "json",
				data: {
					term : request.term,
					id_kustomer : $("#id_kustomerin").val(),
					outbound: $("#outbound").val()
				},
				success: function(data) {
					response(data);
				}
			});
		},
		min_length: 3,
		delay: 300,
		select: function( event, ui ) {
			$("#nama_pengirim").val( ui.item.value );
			$("#alamat_pengirim").val( ui.item.alamat_pengirim );
			$("#hp_pengirim").val( ui.item.hp_penerima );
			$("#perusahaan_pengirim").val( ui.item.perusahaan_pengirim );
			$("#intercityasal").val( ui.item.intercityasal );
			var option, $select = jQuery( '#lokasi_asal' );
			option = $select.find( 'option[value="' + ui.item.lokasi_asal + '"]' )[0];
			if ( 'undefined' !== typeof option ) {
				$select.select2('val', ui.item.lokasi_asal);
			}
		}
	});
	
	// lokasi asal
	$('#lokasi_asal').on('change', function(){
		lokasi_asal = $('#lokasi_asal option:selected').val();
		$.ajax({
		    type :'POST',
			dataType:'json',
			data : { lokasi_asal : lokasi_asal },
			url : 'module/mod_bookingin/bookingin_action.php?module=bookingin&act=intercity',
			success : function(result){
				$('#intercityasal').val(result['intercity']);
			}
		});
	});
	
	// autocomplete penerima inbound
	$( "#nama_penerimain" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: 'module/mod_bookingin/bookingin_action.php?module=bookingin&act=penerima',
				dataType: "json",
				data: {
					term : request.term,
					id_kustomer : $("#id_kustomerin").val()
				},
				success: function(data) {
					response(data);
				}
			});
		},
		min_length: 3,
		delay: 300,
		select: function( event, ui ) {
			$("#nama_penerimain").val( ui.item.value );
			$("#alamat_penerima").val( ui.item.alamat_penerima );
			$("#hp_penerima").val( ui.item.hp_penerima );
			$("#perusahaan_penerima").val( ui.item.perusahaan_penerima );
			$("#intercitytujuan").val( ui.item.intercitytujuan );
			//$("#lokasi_tujuan").val(ui.item.lokasi_tujuan);
			var option, $select = jQuery( '#lokasi_tujuan' );
			option = $select.find( 'option[value="' + ui.item.lokasi_tujuan + '"]' )[0];
			if ( 'undefined' !== typeof option ) {
				$select.select2('val', ui.item.lokasi_tujuan);
			}
		}
	});
	
	// autocomplete pembayaran
	$( "#nama_pembayaran" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: 'module/mod_booking/booking_action.php?module=booking&act=pembayaran',
				dataType: "json",
				data: {
					term : request.term,
					id_kustomer : $("#id_kustomer").val()
				},
				success: function(data) {
					response(data);
				}
			});
		},
		min_length: 3,
		delay: 300,
		select: function( event, ui ) {
			$("#nama_pembayaran").val( ui.item.value );
			$("#alamat_pembayaran").val( ui.item.alamat_pembayaran );
			$("#cp_pembayaran").val( ui.item.cp_pembayaran );
			$("#telp_pembayaran").val( ui.item.telp_pembayaran );
		}
	});
	
	// autocomplete pembayaran inbound
	$( "#nama_pembayaranin" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: 'module/mod_bookingin/bookingin_action.php?module=bookingin&act=pembayaran',
				dataType: "json",
				data: {
					term : request.term,
					id_kustomer : $("#id_kustomerin").val()
				},
				success: function(data) {
					response(data);
				}
			});
		},
		min_length: 3,
		delay: 300,
		select: function( event, ui ) {
			$("#nama_pembayaranin").val( ui.item.value );
			$("#alamat_pembayaranin").val( ui.item.alamat_pembayaran );
			$("#cp_pembayaranin").val( ui.item.cp_pembayaran );
			$("#telp_pembayaranin").val( ui.item.telp_pembayaran );
		}
	});
	
	// number format
	$('input.number').keyup(function(event) {
		if(event.which >= 37 && event.which <= 40){
			event.preventDefault();
		}
	
		$(this).val(function(index, value) {
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
		highlight: function(element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorElement: 'span',
		errorClass: 'help-block',
		errorPlacement: function(error, element) {
			if(element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		}
	});
	
	// delivery
	$(document).on('click','.open_modal',function(e){
		e.preventDefault();
		$("#confirm-delivery").modal('show');
		$.post('module/mod_delivery/delivery_action.php?module=delivery&act=detail',
			{id:$(this).attr('data-id')},
			function(html){
				$(".modal-body").html(html);
			}
		);
	});
	
	// switch
	$(document).on('click','.open_switch',function(e){
		e.preventDefault();
		$("#switch-courier").modal('show');
		$.post('module/mod_delivery/delivery_action.php?module=delivery&act=switch',
			{id:$(this).attr('data-id')},
			function(html){
				$(".modal-body").html(html);
			}
		);
	});
	
	// switch pickup
	$(document).on('click','.open_switch_pickup',function(e){
		e.preventDefault();
		$("#switch-courier-pickup").modal('show');
		$.post('module/mod_pickup/pickup_action.php?module=pickup&act=switch',
			{id:$(this).attr('data-id')},
			function(html){
				$(".modal-body").html(html);
			}
		);
	});
	
	// cancel airwaybill
	$(document).on('click','.open_cancel',function(e){
		e.preventDefault();
		$("#cancel-airwaybill").modal('show');
		$.post('module/mod_airwaybill/airwaybill_action.php?module=airwaybill&act=cancel',
			{id:$(this).attr('data-id')},
			function(html){
				$(".modal-body").html(html);
			}
		);
	});
	
	// pickup assignment
	$(document).on('click','.open_assignment',function(e){
		e.preventDefault();
		$("#pickup-assignment").modal('show');
		$.post('module/mod_manifest-pickup/manifest-pickup_action.php?module=manifest-pickup&act=assignment',
			{id:$(this).attr('data-id')},
			function(html){
				$(".modal-body").html(html);
			}
		);
	});
	
	// outbound assignment
	$(document).on('click','.open_outbound-assignment',function(e){
		e.preventDefault();
		$("#outbound-assignment").modal('show');
		$.post('module/mod_manifest-outbound/manifest-outbound_action.php?module=manifest-outbound&act=assignment',
			{id:$(this).attr('data-id')},
			function(html){
				$(".modal-body").html(html);
			}
		);
	});
	
	// inbound assignment
	$(document).on('click','.open_inbound-received',function(e){
		e.preventDefault();
		$("#inbound-received").modal('show');
		$.post('module/mod_manifest-inbound/manifest-inbound_action.php?module=manifest-inbound&act=received',
			{id:$(this).attr('data-id')},
			function(html){
				$(".modal-body").html(html);
			}
		);
	});
	
	// manifest
	$(document).on('click','.open_manifest-delivery',function(e){
		e.preventDefault();
		$("#manifest-delivery").modal('show');
		$.post('module/mod_manifest/manifest_action.php?module=manifest&act=assignment',
			{id:$(this).attr('data-id')},
			function(html){
				$(".modal-body").html(html);
			}
		);
	});
	
	// import penerima
	$(document).on('click','.open_import-penerima',function(e){
		e.preventDefault();
		$("#import-penerima").modal('show');
		$.post('module/mod_airwaybill/airwaybill_action.php?module=airwaybill&act=importpenerima',
			{id:$(this).attr('data-id')},
			function(html){
				$(".modal-body").html(html);
			}
		);
	});
	
	// latest booking
	function latestbooking(){
		$('#latest-booking').load('script.php?module=script&act=latestbooking',function () {
			$(this).html();
		});
	}
	latestbooking(); // This will run on page load
	setInterval(function(){
		latestbooking() // this will run after every 5 seconds
	}, 30000);
	
	// latest pickup
	function latestpickup(){
		$('#latest-pickup').load('script.php?module=script&act=latestpickup',function () {
			$(this).html();
		});
	}
	latestpickup(); // This will run on page load
	setInterval(function(){
		latestpickup() // this will run after every 5 seconds
	}, 30000);
 
	// autocomplete pengirim
	$( "#kode_rekening" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: 'module/mod_finance_jurnal-umum/jurnal-umum_action.php?module=jurnal-umum&act=rekening',
				dataType: "json",
				data: {
					term : request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		min_length: 3,
		delay: 300,
		select: function( event, ui ) {
			$("#kode_rekening").val( ui.item.value );
			$("#nama_rekening").val( ui.item.nama_rekening );
		}
	});
	
});