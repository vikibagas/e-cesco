/*jslint browser: true*/
/*global $, jQuery, alert*/

var TableManaged = function () {

	// tabel negara
	var initNegara = function () {
        var table = $('#negara');
		table.dataTable({
			"processing": true,
			"serverSide": true,
			"ajax": {
				url : "module/mod_country/country_action.php?module=country&act=read",
				type: "post"
			}
        });
    };

	// tabel kota
	var initKota = function() {
        var table = $('#kota');
		table.dataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_destination/destination_action.php?module=destination&act=read",
				type: "post"
			}
        });
		setInterval(function() {
			table.api().ajax.reload(null, false);
		}, 60000 );
    }

	// tabel agen
	var initAgen = function() {
		var table = $("#branch");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_branch/branch_action.php?module=branch&act=data"
			},
			"columnDefs": [{
				"targets": [3],
				"orderable": false,
			}]
		});
	}
	var initVendor = function() {
		var table = $("#vendor");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_vendor/vendor_action.php?module=vendor&act=data"
			}
		});
	}
	var initRak = function() {
		var table = $("#rak");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_warehouse-rak/rak_action.php?module=rak&act=data"
			}
		});
	}
	
	var initBarang = function(){
		var table = $("#barang");
		table.DataTable({
			"rowReorder": {
				selector: "td:nth-child(2)"
			},
			//"responsive": true,
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_warehouse-barang/barang_action.php?module=barang&act=data"
			},
			"order": [
				[ 0, "desc" ],[ 1, "desc" ]
			],
			"scrollX": true,
			"columnDefs": [{
				"targets": [3,6],
				"orderable": false,
				"searchable": false,
			}]
		});



		var slField = '&nbsp;<select id="slField" class="form-control input-sm" aria-controls="barang">' +
			'<option value="nama_barang">Nama Barang</option>'+
			'<option value="call_name">Pelanggan Pemilik</option>' +
			'<option value="kode_barang">Kode Barang</option>' +
			'<option value="rak">Rak</option>' +
			'<option value="Nama Cabang">namacabang</option>' +
			'<option value="nama_departement">Nama Departement</option>' +
			'<option value="created_date">Tanggal Masuk</option>' +
			'<option value="expired">expired</option>' +
			'</select>';

		$('#barang_filter input').before(slField);
        $('#barang_filter input').unbind();
        $('#barang_filter input').bind('keyup', function(e) {
            if (e.keyCode == 13) {
            	var col = $('#slField').val();
                $('#barang').dataTable().fnFilter(col+'~'+this.value);
            }
        });
		
	}


	var initBarangnonaktifadmin = function(){
		var table = $("#barang-nonaktif");
		table.DataTable({
			"rowReorder": {
				selector: "td:nth-child(2)"
			},
			//"responsive": true,
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_warehouse-barang/barang_action.php?module=barang-nonaktif&act=data"
			},
			"order": [
				[ 0, "desc" ],[ 1, "desc" ]
			],
			"scrollX": true,
			"columnDefs": [{
				"targets": [3,6],
				"orderable": false,
				"searchable": false,
			}]
		});



		var slField = '&nbsp;<select id="slField" class="form-control input-sm" aria-controls="barang-nonaktif">' +
			'<option value="nama_barang">Nama Barang</option>'+
			'<option value="call_name">Pelanggan Pemilik</option>' +
			'<option value="kode_barang">Kode Barang</option>' +
			'<option value="rak">Rak</option>' +
			'<option value="Nama Cabang">namacabang</option>' +
			'<option value="nama_departement">Nama Departement</option>' +
			'<option value="created_date">Tanggal Masuk</option>' +
			'<option value="expired">expired</option>' +
			'</select>';

		$('#barang-nonaktif_filter input').before(slField);
        $('#barang-nonaktif_filter input').unbind();
        $('#barang-nonaktif_filter input').bind('keyup', function(e) {
            if (e.keyCode == 13) {
            	var col = $('#slField').val();
                $('#barang-nonaktif').dataTable().fnFilter(col+'~'+this.value);
            }
        });
		
	}

	// tabel kustomer
	var initKustomer = function() {
		var table = $("#customer");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_customer/customer_action.php?module=customer&act=read",
				type: "post"
			},
			"columnDefs": [{
				"targets": [3],
				"orderable": false,
			}]
		});
	}

	// tabel user
	var initUser = function() {
		var table = $("#user");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_users/users_action.php?module=users&act=readuser"
			},
			"order": [
				[ 0, "asc" ]
			],
			"columnDefs": [{
				"targets": [6],
				"orderable": false,
			}]
		});
	}

	// tabel user client
	var initUserClient = function() {
		var table = $("#user_client");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_users/users_action.php?module=users&act=readuserclient"
			},
			"order": [
				[ 0, "asc" ]
			],
			"scrollX": true,
			"columnDefs": [{
				"targets": [6],
				"orderable": false,
			}]
		});
	}

	// tabel pricelist agent outbound
	var initAgentOutbound = function() {
		var table = $("#agent_outbound");
		table.DataTable({
			"order": [
				[ 0, "asc" ]
			],
			"columnDefs": [{
				"targets": [5],
				"orderable": false,
			}]
		});
	}
	var initSla = function() {
		var table = $("#sla");
		table.DataTable({
			"columnDefs": [{
				"targets": [4],
				"orderable": false,
			}]
		});
	}

	// tabel pricelist agent outbound
	var initAgentInbound = function() {
		var table = $("#agent_inbound");
		table.DataTable({
			"order": [
				[ 0, "asc" ]
			],
			"columnDefs": [{
				"targets": [4],
				"orderable": false,
			}]
		});
	}

	// var tabel booking
	var initBooking = function(){
		var table = $("#booking");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_booking/booking_action.php?module=booking&act=read",
				type: "post"
			},
			"order": [
				[ 0, "desc" ],[ 1, "desc" ]
			],
			"columnDefs": [
				{
					"targets": [6],
					"orderable": false,
				}
			]
		});
		setInterval(function() {
			table.api().ajax.reload(null, false);
		}, 60000 );
	}

	// var tabel booking client
	var initBookingClient = function(){
		var table = $("#bookingclient");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_booking/booking_action.php?module=booking&act=read",
				type: "post"
			},
			"order": [
				[ 0, "desc" ],[ 1, "desc" ]
			],
			"columnDefs": [
				{
					"targets": [6],
					"orderable": false,
				}
			]
		});
		setInterval(function() {
			table.api().ajax.reload(null, false);
		}, 60000 );
	}

	// var tabel bookingin
	var initBookingin = function(){
		var table = $("#bookingin");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_bookingin/bookingin_action.php?module=bookingin&act=data"
			},
			"order": [
				[ 1, "desc" ]
			],
			"columnDefs": [{
				"targets": [2,5,6],
				"orderable": false,
				"searchable": false,
			}]
		});
		setInterval(function() {
			table.api().ajax.reload(null, false);
		}, 60000 );
	}

	// var tabel bookingin
	var initBookinginv2 = function(){
		var table = $("#bookinginv2");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=data"
			},
			"order": [
				[ 1, "desc" ]
			],
			"columnDefs": [{
				"targets": [2,5,6],
				"orderable": false,
				"searchable": false,
			}]
		});
		setInterval(function() {
			table.api().ajax.reload(null, false);
		}, 60000 );
	}
	var initBookingw = function(){
		var table = $("#booking-warehouse");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_warehouse-booking/booking-warehouse_action.php?module=booking-warehouse&act=data"
			},
			"order": [
				[ 1, "desc" ]
			],
			"columnDefs": [{
				"targets": [2,5,6],
				"orderable": false,
				"searchable": false,
			}]
		});
		setInterval(function() {
			table.api().ajax.reload(null, false);
		}, 60000 );
	}

	// var tabel bookingin client
	var initBookinginClient = function(){
		var table = $("#bookinginclient");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=read",
				type: "post"
			},
			"order": [
				[ 0, "desc" ],[ 1, "desc" ]
			],
			"columnDefs": [{
				"targets": [6],
				"orderable": false,
			}]
		});
		
	}

	// var tabel pickup
	var initPickup = function(){
		var table = $("#pickup");
		table.DataTable({
			"rowReorder": {
				selector: "td:nth-child(2)"
			},
			"responsive": true,
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_pickup/pickup_action.php?module=pickup&act=data"
			},
			"order": [
				[ 0, "desc" ],[ 1, "desc" ]
			],
			"scrollX": true,
			"columnDefs": [{
				"targets": [2,4,5,6],
				"orderable": false,
				"searchable": false,
			}]
		});



		var slField = '&nbsp;<select id="slField" class="form-control input-sm" aria-controls="pickup">' +
			'<option value="airwaybill_kode">Airwaybill</option>'+
			'<option value="nama_kustomer">Customer</option>' +
			'<option value="kota_asal">kota asal</option>' +
			'</select>';

		$('#pickup_filter input').before(slField);
        $('#pickup_filter input').unbind();
        $('#pickup_filter input').bind('keyup', function(e) {
            if (e.keyCode == 13) {
            	var col = $('#slField').val();
                $('#pickup').dataTable().fnFilter(col+'~'+this.value);
            }
        });
		
	}

	// Manifest Pickup
	var initManifestP = function(){
		var table = $("#manifestp");
		table.DataTable({
			"responsive": true,
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_manifest-pickup/manifest-pickup_action.php?module=manifest-pickup&act=data"
			},
			"order": [
				[ 1, "desc" ]
			],
			"columnDefs": [{
				"targets": [0,3,5,6,7],
				"orderable": false,
				"searchable": false,
			}]
		});
		var slFieldd = '&nbsp;<select id="slFieldd" class="form-control input-sm" aria-controls="manifestp">' +
			'<option value="airwaybill_kode">Airwaybill</option>'+
			'<option value="nama_kustomer">Customer</option>' +
			'<option value="kota_asal">kota asal</option>' +
			'</select>';

		$('#manifestp_filter input').before(slFieldd);
        $('#manifestp_filter input').unbind();
        $('#manifestp_filter input').bind('keyup', function(e) {
            if (e.keyCode == 13) {
            	var col = $('#slFieldd').val();
                $('#manifestp').dataTable().fnFilter(col+'~'+this.value);
            }
        });

		//setInterval(function() {
			//table.api().ajax.reload(null, false);
		//}, 60000 );
	}



	var initManifestOutboundList = function(){
		var table = $("#manifest-outboundlist");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_manifest-outbound/manifest-outbound_action.php?module=manifest-outbound&act=data"
			},
			"order": [
				[ 0, "desc" ]
			],
			"columnDefs": [{
				"targets": [2,5,6,7],
				"orderable": false,
				"searchable": false,
			}]
		});

        var slField = '&nbsp;<select id="slField" class="form-control input-sm" aria-controls="manifest-outboundlist">' +
            '<option value="airwaybill_kode">Airwaybill</option>' +
            '<option value="created_date">Booking Date - ETA</option>' +
            '<option value="nama_kustomer">Customer</option>' +
            '<option value="kota_asal">Kota Asal</option>' +
            '<option value="kota_tujuan">Kota Tujuan</option>' +
            '<option value="service">Service</option>' +
			'<option value="hub">Hub</option>' +
            '</select>';

        $('#manifest-outboundlist_filter input').before(slField);
        $('#manifest-outboundlist_filter input').unbind();
        $('#manifest-outboundlist_filter input').bind('keyup', function(e) {
            if (e.keyCode == 13) {
                var col = $('#slField').val();
                $('#manifest-outboundlist').dataTable().fnFilter(col+'~'+this.value);
            }
        });

		setInterval(function() {
			table.api().ajax.reload(null, false);
		}, 60000 );
	}

	// Manifest Outbound
	var initManifestOutbound = function () {
        var table = $('#manifest-outbound');
		table = table.dataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_report-manifest-outbound/report-manifest-outbound_action.php?module=outbound&act=data"
			},
			"order": [
				[ 0, "desc" ]
			],
			"columnDefs": [{
				"targets": [2,5,6,7,8],
				"orderable": false,
				"searchable": false,
			}]
		});

		var slField = '&nbsp;<select id="slField" class="form-control input-sm" aria-controls="manifest-outbound">' +
            '<option value="manifest_to"> manifest to</option>' +
            '<option value="manifest_from">manifest from</option>' +
            '<option value="manifest_via">via</option>' +
            '</select>';

        $('#manifest-outbound_filter input').before(slField);
        $('#manifest-outbound_filter input').unbind();
        $('#manifest-outbound_filter input').bind('keyup', function(e) {
            if (e.keyCode == 13) {
                var col = $('#slField').val();
                $('#manifest-outbound').dataTable().fnFilter(col+'~'+this.value);
            }
        });

		setInterval(function() {
			table.api().ajax.reload(null, false);
		}, 600000 );
    }

	// Manifest Inbound List
	var initManifestInboundList = function(){
		var table = $("#manifest-inboundlist");
		table.DataTable({
			"rowReorder": {
				selector: "td:nth-child(2)"
			},
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_manifest-inbound/manifest-inbound_action.php?module=manifest-inbound&act=data"
			},
			"order": [
				[ 0, "desc" ]
			],
			"columnDefs": [{
				"targets": [2,3,4,5,6,7,8],
				"orderable": false,
				"searchable": false,
			}]
		});


		var slField = '&nbsp;<select id="slField" class="form-control input-sm" aria-controls="manifest-inboundlist">' +
			'<option value="airwaybill_kode">Airwaybill</option>' +
			'<option value="tanggal">Manifest Date - ETA</option>' +
			'<option value="no_smu">SMU / Resi No.</option>' +
			'<option value="nama_kustomer">Customer</option>' +
			'<option value="kota_asal">Kota Asal</option>' +
			'<option value="kota_tujuan">Kota Tujuan</option>' +
			'<option value="service">Service</option>' +
			'<option value="transit">Transit</option>' +
			'</select>';

		$('#manifest-inboundlist_filter input').before(slField);
        $('#manifest-inboundlist_filter input').unbind();
        $('#manifest-inboundlist_filter input').bind('keyup', function(e) {
            if (e.keyCode == 13) {
            	var col = $('#slField').val();
                $('#manifest-inboundlist').dataTable().fnFilter(col+'~'+this.value);
            }
        });

	}

	// Manifest Inbound
	var initManifestInbound = function () {
        var table = $('#manifest-inbound');
		table = table.dataTable({
			"processing": true,
			"serverSide": true,
			"ajax": $.fn.dataTable.pipeline( {
				url: "module/mod_report-manifest-inbound/report-manifest-inbound_action.php?module=inbound&act=data",
				pages: 5 // number of pages to cache
			}),
			//"ajax":{
				//url :"module/mod_report-manifest-inbound/report-manifest-inbound_action.php?module=inbound&act=data",
				//pages: 5
			//},
			"order": [
				[ 0, "desc" ]
			],
			"columnDefs": [{
				"targets": [1,2,4,5,6,7],
				"orderable": false,
				"searchable": false,
			}]
		});
		setInterval(function() {
			table.api().ajax.reload(null, false);
		}, 60000 );
    }

	var initManifestInboundDetail = function () {
        var table = $('#manifest-inbound-detail');
		table = table.dataTable({
			"order": [
				[ 0, "asc" ]
			],
			"scrollX": true,
			"columnDefs": [{
				"targets": [11],
				"orderable": false,
			}]
		});
    }

	// tabel manifest
	var initManifest = function() {
		var table = $("#manifest");
		table.DataTable({
			"rowReorder": {
				selector: "td:nth-child(2)"
			},
			"responsive": true,
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_manifest/manifest_action.php?module=manifest&act=data"
			},
			"order": [
				[ 0, "desc" ]
			],
			"columnDefs": [{
				"targets": [2,3,5,6],
				"orderable": false,
				"searchable": false,
			}]
		});

		var slField = '&nbsp;<select id="slField" class="form-control input-sm" aria-controls="manifest">' +
			'<option value="airwaybill_kode">Airwaybill</option>'+
			'<option value="nama_kustomer">Customer</option>' +
			'<option value="kota_tujuan">kota tujuan</option>' +
			'</select>';

		$('#manifest_filter input').before(slField);
        $('#manifest_filter input').unbind();
        $('#manifest_filter input').bind('keyup', function(e) {
            if (e.keyCode == 13) {
            	var col = $('#slField').val();
                $('#manifest').dataTable().fnFilter(col+'~'+this.value);
            }
        });
	}

	// Delivery
	var initDelivery = function(){
		var table = $("#delivery");
		table.DataTable({
			"rowReorder": {
				selector: "td:nth-child(2)"
			},
			"responsive": true,
			"order": [
				[ 0, "desc" ],[ 1, "desc" ]
			],
			"columnDefs": [{
				"targets": [6],
				"orderable": false,
			}]
		});
	}

	// reportairwaybill
	var initReport = function () {
        var table = $('#report');
		table = table.dataTable({
			"stateSave": true,
			"lengthMenu": [[10, 25,50,100 , -1], [10, 25,50,100, "All"]],
			"order": [
				[ 0, "asc" ],[ 1, "asc" ]
			],
			"scrollX": true,
			"columnDefs": [{
				"targets": [2],

			}],
			"initComplete": function( settings, data ) {
				if (typeof cekKunci === 'function') {
					cekKunci();
				}

				if (typeof cekKirimAgent === 'function') {
                    cekKirimAgent();
				}

				if (typeof cekAWB === 'function') {
					cekAWB();
				}
            }
		});

		//On klik page
		$('#report').on( 'page.dt', function () {
			if (typeof cekKunci === 'function') {
				cekKunci();
			}

			if (typeof cekKirimAgent === 'function') {
                cekKirimAgent();
			}

		});

		/*table = new table.dataTable.FixedColumns(table,{
			leftColumns: 2
		});*/
    }

    var initReportwarehouse = function () {
        var table = $('#reportwarehouse');
		table = table.dataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
			    url: "module/mod_warehouse-report/report_action.php",
			    type: "GET",
			    data: function (d) {
			        d.module = "report-warehouse";
			        d.act = "data";
			        d.id_kustomer = $('#id_kustomer').val(); // ambil dari hidden field atau select
			        d.gudang = $('#gudang').val(); // ambil dari hidden field atau select
			    }
			},
			"stateSave": true,
			"lengthMenu": [[10, 25,50,100 , -1], [10, 25,50,100, "All"]],
			"order": [
				[ 0, "asc" ],[ 1, "asc" ]
			],
			"scrollX": true,
			"columnDefs": [{
				"targets": [2],

			}]
		});
			
    }
    var initKeluarBarangBelumTercatat = function () {
        var table = $('#keluarbarangbelumtercatat');
		table = table.dataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
			    url: "module/mod_warehouse-report/report_action.php",
			    type: "GET",
			    data: function (d) {
			        d.module = "report-warehouse";
			        d.act = "belum-tercatat";
			        d.id_kustomer = $('#id_kustomer').val(); // ambil dari hidden field atau select			       
			    }
			},
			"stateSave": true,
			"lengthMenu": [[10, 25,50,100 , -1], [10, 25,50,100, "All"]],
			"order": [
				[ 0, "asc" ],[ 1, "asc" ]
			],
			"scrollX": true,
			"columnDefs": [{
				"targets": [2],

			}]
		});
			
    }
    var initPemakaianRak = function () {
        var table = $('#pemakaian-rak');
		table = table.dataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
			    url: "module/mod_warehouse-report/report_action.php",
			    type: "GET",
			    data: function (d) {
			        d.module = "pemakaian-rak";
			        d.act = "data";
			        d.id_kustomer = $('#id_kustomer').val(); // ambil dari hidden field atau select
			        d.gudang = $('#gudang').val(); // ambil dari hidden field atau select
			    }
			},
			"stateSave": true,
			"lengthMenu": [[10, 25,50,100 , -1], [10, 25,50,100, "All"]],
			"order": [
				[ 0, "asc" ],[ 1, "asc" ]
			],
			"scrollX": true,
		});
			
    }

	var initInvoice = function () {
        var table = $('#invoice');
		table = table.dataTable({
			"dom": "<'row'<'col-sm-6'l><'col-sm-3'f><'col-sm-3'T>>R" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-6'i><'col-sm-6'p>>",
			"tableTools": {
				"aButtons": [
					"print",
					"pdf"
				],
				"sSwfPath": "plugins/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
			},
			"order": [
				[ 1, "asc" ]
			],
			"scrollX": true,
			"columnDefs": [{
				"targets": [1],
				"orderable": false,
			}],
		});
    }

	var initAirwaybill = function () {
        var table = $('#airwaybill');
		table = table.dataTable({
			"processing": true,
			"serverSide": true,
			"fixedColumns":   true,

			"ajax":{
				url :"module/mod_airwaybill/airwaybill_action.php?module=airwaybill&act=data"
			},
			"order": [
				[ 1, "desc" ]
			],
			"scrollX": true,
			"columnDefs": [{
				"targets": [2,3,4,5,6,8,9,10,11,12,13],
				"orderable": false,
				"searchable": false,
			}]
		});

		setInterval(function() {
			table.api().ajax.reload(null, false);
		}, 60000 );
    }

	var initAirwaybillClient = function () {
        var table = $('#airwaybillclient');
		table = table.dataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_airwaybill/airwaybill_action.php?module=airwaybill&act=data"
			},
			"order": [
				[ 1, "desc" ]
			],
			"scrollX": true,
			"columnDefs": [{
				"targets": [2,3,4,5,6,7,8,9,10,11,12,13],
				"orderable": false,
				"searchable": false,
			}]
		});

		setInterval(function() {
			table.api().ajax.reload(null, false);
		}, 60000 );
    }

    var initBarangnonaktif = function () {
        var table = $('#barangnonaktif');
		table = table.dataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_warehouse-report/report_action.php?module=barang&act=nonaktif"
			},
			"order": [
				[ 1, "desc" ]
			],
			"scrollX": true,
			"columnDefs": [{
				"targets": [0],
				"orderable": false,
				"searchable": false,
			}]
		});

		setInterval(function() {
			table.api().ajax.reload(null, false);
		}, 60000 );
    }

    var initAirwaybillSearch = function () {
        var table = $('#airwaybillsearch');
		table = table.dataTable({
			"scrollX": true,
			"columnDefs": [{
				"targets": [13],
				"orderable": false,
			}]
		});
		/*table = new table.dataTable.FixedColumns(table,{
			leftColumns: 2
		});*/
    }

    /*var initAirwaybillCust = function () {
        var table = $('#airwaybill-cust');
		table = table.dataTable({
			"ajax": "airwaybill.php",
			"dom": "<'row'<'col-sm-6'l><'col-sm-3'f><'col-sm-3'T>>R" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-6'i><'col-sm-6'p>>",
			"tableTools": {
				"aButtons": [
					"xls",
					"print"
				],
				"sSwfPath": "plugins/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
			},
			"order": [
				[ 1, "asc" ]
			],
			"scrollX": true,
			"columnDefs": [{
				"targets": [1],
				"orderable": false,
			}],
		});
	}*/


	var initManifestPickup = function () {
        var table = $('#manifest-pickup');
		table = table.dataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_report-manifest-pickup/report-manifest-pickup_action.php?module=pickup&act=data"
			},
			"order": [
				[ 0, "desc" ],[ 1, "asc" ]
			],
			"columnDefs": [{
				"targets": [2,3,4,5],
				"orderable": false,
				"searchable": false,
			}]
		});

		var slField = '&nbsp;<select id="slField" class="form-control input-sm" aria-controls="manifest-pickup">' +
           	 '<option value="namalengkap">kurir</option>' +
            '<option value="pickup"> tanggal</option>' +
           
            
            '</select>';

        $('#manifest-pickup_filter input').before(slField);
        $('#manifest-pickup_filter input').unbind();
        $('#manifest-pickup_filter input').bind('keyup', function(e) {
            if (e.keyCode == 13) {
                var col = $('#slField').val();
                $('#manifest-pickup').dataTable().fnFilter(col+'~'+this.value);
            }
        });
		setInterval(function() {
			table.api().ajax.reload(null, false);
		}, 60000 );
    }

	var initManifestPickupDetail = function () {
        var table = $('#manifest-pickup-detail');
		table = table.dataTable({
			"order": [
				[ 0, "asc" ],[ 1, "asc" ]
			],
			"scrollX": true
		});
    }
    var initMailbox = function () {
        var table = $('#mailbox');
		table = table.dataTable({
			"order": [
				[ 0, "desc" ], [ 1, "asc" ]
			],
			"scrollX": true
		});

    }

	var initManifestDelivery = function () {
        var table = $('#manifest-delivery');
		table = table.dataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_report-manifest-delivery/report-manifest-delivery_action.php?module=delivery&act=data"
			},
			"order": [
				[ 0, "desc" ],[ 1, "asc" ]
			],
			"columnDefs": [{
				"targets": [2,3,4,5],
				"orderable": false,
				"searchable": false,
			}]
		});

		var slField = '&nbsp;<select id="slField" class="form-control input-sm" aria-controls="manifest-delivery">' +
           	 '<option value="namalengkap">kurir</option>' +
            '<option value="delivery"> tanggal</option>' +
           
            
            '</select>';

        $('#manifest-delivery_filter input').before(slField);
        $('#manifest-delivery_filter input').unbind();
        $('#manifest-delivery_filter input').bind('keyup', function(e) {
            if (e.keyCode == 13) {
                var col = $('#slField').val();
                $('#manifest-delivery').dataTable().fnFilter(col+'~'+this.value);
            }
        });

		setInterval(function() {
			table.api().ajax.reload(null, false);
		}, 60000 );
    }


    var initReportAgentDeliv = function () {
        var table = $('#report-delivery-agent');
		table = table.dataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_report-delivery-agent/report-delivery-agent_action.php?module=delivery&act=data"
			},
			"order": [
				[ 0, "desc" ],[ 1, "asc" ]
			],
			"columnDefs": [{
				"targets": [2,3,4,5],
				"orderable": false,
				"searchable": false,
			}]
		});

		var slField = '&nbsp;<select id="slField" class="form-control input-sm" aria-controls="report-delivery-agent">' +
           	 '<option value="namalengkap">Agent</option>' +
            '<option value="delivery"> tanggal</option>' +
           
            
            '</select>';

        $('#report-delivery-agent_filter input').before(slField);
        $('#report-delivery-agent_filter input').unbind();
        $('#report-delivery-agent_filter input').bind('keyup', function(e) {
            if (e.keyCode == 13) {
                var col = $('#slField').val();
                $('#report-delivery-agent').dataTable().fnFilter(col+'~'+this.value);
            }
        });

		setInterval(function() {
			table.api().ajax.reload(null, false);
		}, 60000 );
    }

    var initReportAgentPickup = function () {
        var table = $('#report-pickup-agent');
		table = table.dataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_report-pickup-agent/report-pickup-agent_action.php?module=pickup&act=data"
			},
			"order": [
				[ 0, "desc" ],[ 1, "asc" ]
			],
			"columnDefs": [{
				"targets": [2,3,4,5],
				"orderable": false,
				"searchable": false,
			}]
		});

		var slField = '&nbsp;<select id="slField" class="form-control input-sm" aria-controls="report-pickup-agent">' +
           	 '<option value="namalengkap">Agent</option>' +
            '<option value="pickup"> tanggal</option>' +
           
            
            '</select>';

        $('#report-pickup-agent_filter input').before(slField);
        $('#report-pickup-agent_filter input').unbind();
        $('#report-pickup-agent_filter input').bind('keyup', function(e) {
            if (e.keyCode == 13) {
                var col = $('#slField').val();
                $('#report-pickup-agent').dataTable().fnFilter(col+'~'+this.value);
            }
        });

		setInterval(function() {
			table.api().ajax.reload(null, false);
		}, 60000 );
    }


	// var tabel tarif kiriman luar negeri paket
	var initTarifKirimanLuarNegeriPaket = function(){
		var table = $("#TarifKirimanLuarNegeriPaket");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_tarif-luarnegeri/tarif-luarnegeri_action.php?module=tarif-luarnegeri&act=readpaket"
			},
			"order": [
				[ 0, "asc" ]
			]
		});
	}

	// var tabel tarif kiriman luar negeri dokumen
	var initTarifKirimanLuarNegeriDokumen = function(){
		var table = $("#TarifKirimanLuarNegeriDokumen");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_tarif-luarnegeri/tarif-luarnegeri_action.php?module=tarif-luarnegeri&act=readdokumen"
			},
			"order": [
				[ 0, "asc" ]
			]
		});
	}

	// var tabel tarif kiriman top urgent
	var initTarifKirimanTopUrgent = function(){
		var table = $("#TarifKirimanTopUrgent");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_tarif-urgent/tarif-urgent_action.php?module=tarif-urgent&act=readtop"
			},
			"order": [
				[ 0, "asc" ], [ 1, "desc" ]
			]
		});
	}

	// tabel tarif kiriman urgent
	var initTarifKirimanUrgent = function(){
		var table = $("#TarifKirimanUrgent");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_tarif-urgent/tarif-urgent_action.php?module=tarif-urgent&act=readurgent"
			},
			"order": [
				[ 0, "asc" ], [ 1, "desc" ]
			]
		});
	}

	// tabel tarif kiriman regular outbound
	var initTarifKirimanRegularOutbound = function(){
		var table = $("#TarifKirimanRegularOutbound");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_tarif-regular/tarif-regular_action.php?module=tarif-regular&act=readoutbound"
			},
			"order": [
				[ 0, "asc" ], [ 1, "desc" ]
			]
		});
	}

	// tabel tarif kiriman regular inbound
	var initTarifKirimanRegularInbound = function(){
		var table = $("#TarifKirimanRegularInbound");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_tarif-regular/tarif-regular_action.php?module=tarif-regular&act=readinbound"
			},
			"order": [
				[ 0, "asc" ], [ 1, "desc" ]
			]
		});
	}

	// tabel tarif kiriman regular inbound
	var initTarifKirimanIntercity = function(){
		var table = $("#TarifKirimanIntercity");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_tarif-intercity/tarif-intercity_action.php?module=tarif-intercity&act=read"
			},
			"order": [
				[ 0, "asc" ], [ 1, "desc" ]
			]
		});
	}

	// tabel invoice customer
	var initInvoiceCustomer = function(){
		var table = $("#InvoiceCustomer");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=read"
			},
			"order": [
				[ 0, "desc" ]
			],
			"columnDefs": [{
				"targets": [2,4,5,6],
				"orderable": false,
				"searchable": false,
			}],
		});
	}
	var initInvoiceAgentnew = function(){
		var table = $("#InvoiceAgentnew");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=read"
			},
			"order": [
				[ 0, "desc" ]
			],
			"columnDefs": [{
				"targets": [2],
				"orderable": false,
				"searchable": false,
			}],
			"drawCallback": function( settings, data ) {

                $('.open_due_date').on('click', function (e) {
                    e.preventDefault();
                    //alert('buka');
                    var id = $(this).attr('data-pk');
					var data = id.split('~');
                    $("#modal-due-date").modal('show');
                    $("#modal-due-date #id_agen").val(data[0]);
                    $("#modal-due-date #invoice_date").val(data[1]);
                    $("#modal-due-date #tanggal").val(data[2]);
                    $("#modal-due-date #id_invoicepaid").val(data[3]);
                });

                $('.open_paid_date').on('click', function (e) {
                    e.preventDefault();
                    //alert('buka');
                    var id = $(this).attr('data-pk');
                    var data = id.split('~');
                    $("#modal-paid-date").modal('show');
                    $("#modal-paid-date #id_agen").val(data[0]);
                    $("#modal-paid-date #invoice_date").val(data[1]);
                    $("#modal-paid-date #tanggal").val(data[2]);
                    $("#modal-paid-date #id_invoicepaid").val(data[3]);
                });
            }
		});
	}
	var initRateCustomer = function(){
		var table = $("#ratecustomer");
		var idkustomer = $("#id_kustomer_select").val();
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_rate/rate_customer_action.php?module=rate&act=read&id_kustomer="+idkustomer,

			},
			"order": [
				[ 0, "desc" ]
			],
			"columnDefs": [{
				"targets": [4,5,6,7],
				"orderable": false,
				"searchable": false,
			}],
		});
	}

	var initInvoiceAgent = function(){
		var table = $("#InvoiceAgent");
		table.DataTable({

			/*"processing": true,
			"serverSide": true,
			"responsive": true,*/
			/*"ajax":{
				url :"module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=read"
			},*/
			"order": [
				[ 0, "DESC" ]
			],
            "drawCallback": function( settings, data ) {

                $('.open_due_date').on('click', function (e) {
                    e.preventDefault();
                    //alert('buka');
                    var id = $(this).attr('data-pk');
					var data = id.split('~');
                    $("#modal-due-date").modal('show');
                    $("#modal-due-date #id_agen").val(data[0]);
                    $("#modal-due-date #invoice_date").val(data[1]);
                    $("#modal-due-date #tanggal").val(data[2]);
                    $("#modal-due-date #id_stts").val(data[3]);
                });

                $('.open_paid_date').on('click', function (e) {
                    e.preventDefault();
                    //alert('buka');
                    var id = $(this).attr('data-pk');
                    var data = id.split('~');
                    $("#modal-paid-date").modal('show');
                    $("#modal-paid-date #id_agen").val(data[0]);
                    $("#modal-paid-date #invoice_date").val(data[1]);
                    $("#modal-paid-date #tanggal").val(data[2]);
                    $("#modal-paid-date #id_stts").val(data[3]);
                });
            }
		});
	}

	// tabel costing pickup intercity
	var initCostingPickupIntercity = function(){
		var table = $("#costing-pickup-intercity");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_costing-pickup/costing-pickup-intercity_action.php?module=costing-pickup-intercity&act=data"
			},
			"order": [
				[ 0, "desc" ], [ 1, "asc" ]
			],
			"columnDefs": [{
				"targets": [2,3,4,5,6],
				"orderable": false,
				"searchable": false,
			}],
            "initComplete": function( settings, data ) {
				var cnt0 = JSON.stringify(data['total0']);
				cnt0 = cnt0.replace(/"/g, '');
				if(parseInt(cnt0) == 0){
				    $('.alert-notif').addClass('hide-notif');
				}
            }
		});

		var slField = '&nbsp;<select id="slField" class="form-control input-sm" aria-controls="costing-pickup-intercity">' +
           	 '<option value="namalengkap">kurir</option>' +
            '<option value="pickup"> tanggal</option>' +
           
            
            '</select>';

        $('#costing-pickup-intercity_filter input').before(slField);
        $('#costing-pickup-intercity_filter input').unbind();
        $('#costing-pickup-intercity_filter input').bind('keyup', function(e) {
            if (e.keyCode == 13) {
                var col = $('#slField').val();
                $('#costing-pickup-intercity').dataTable().fnFilter(col+'~'+this.value);
            }
        });
	}

	var initCostingD = function(){
		var table = $("#costing-delivery-intercity");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_costing-delivery/costing-delivery-intercity_action.php?module=costing-delivery-intercity&act=data"
			},
			"order": [
				[ 0, "desc" ], [ 1, "asc" ]
			],
			"columnDefs": [{
				"targets": [2,3,4,5,6],
				"orderable": false,
				"searchable": false,
			}],
            "initComplete": function( settings, data ) {
							  var cnt0 = JSON.stringify(data['total0']);
                cnt0 = cnt0.replace(/"/g, '');
                if(parseInt(cnt0) == 0){
                    $('.alert-notif').addClass('hide-notif');
                }

            }
		});
		
		var slField = '&nbsp;<select id="slField" class="form-control input-sm" aria-controls="costing-delivery-intercity">' +
           	 '<option value="namalengkap">kurir</option>' +
            '<option value="pickup"> tanggal</option>' +
           
            
            '</select>';

        $('#costing-delivery-intercity_filter input').before(slField);
        $('#costing-delivery-intercity_filter input').unbind();
        $('#costing-delivery-intercity_filter input').bind('keyup', function(e) {
            if (e.keyCode == 13) {
                var col = $('#slField').val();
                $('#costing-delivery-intercity').dataTable().fnFilter(col+'~'+this.value);
            }
        });
	}
	var initCostingDDetail = function () {
        var table = $('#costing-delivery-detail');
		table = table.dataTable({
			"order": [
				[ 0, "asc" ],[ 1, "asc" ]
			],
			"scrollX": true
		});
    }
    var initCostingIn = function(){
		var table = $("#costing-inbound");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_costing-inbound/costing-inbound_action.php?module=costing-inbound&act=data"
			},
			"order": [
				[ 0, "desc" ], [ 1, "asc" ]
			],
			"columnDefs": [{
				"targets": [1,3,5,6,7,9],
				"orderable": false,
				"searchable": false,
			}],
			"scrollX": true,
            "initComplete": function( settings, data ) {
                var cnt0 = JSON.stringify(data['total0']);
                cnt0 = cnt0.replace(/"/g, '');
                if(parseInt(cnt0) == 0){
                    $('.alert-notif').addClass('hide-notif');
                }
            }
		});
	}

    var initReportVendor = function () {
        var table = $('#reportvendor');
		table = table.dataTable({
			"order": [
				[ 0, "asc" ]
			],
			"scrollX": true,
			"paging": false,
		});
    }
    var initCostingInboundDetail = function () {
        var table = $('#CostingInDetail');
		table = table.dataTable({
			"order": [
				[ 0, "asc" ]
			],
			"scrollX": true
		});
    }

	var initAirwaybillInvoice = function () {
        var table = $('#airwaybillinvoice');
		table = table.dataTable({
			"order": [
				[ 1, "desc" ]
			],
			"scrollX": true
		});
		//table = table.dataTable.FixedColumns(table,{
			//leftColumns: 2
		//});
    }

    var initAirwaybillSrc = function () {
        var table = $('#airwaybillsrc');
		table = table.dataTable({
			"scrollX": true,
			"searching": false,




		});
		//table = table.dataTable.FixedColumns(table,{
			//leftColumns: 2
		//});
    }



    var initCostingOut = function(){
		var table = $("#costing-outbound");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_costing-outbound/costing-outbound_action.php?module=costing-outbound&act=data"
			},
			"order": [
				[ 0, "desc" ], [ 1, "asc" ]
			],
			"columnDefs": [{
				"targets": [2,5,6,8,9],
				"orderable": false,
				"searchable": false,
			}],
			"scrollX": true,
            "initComplete": function( settings, data ) {
				var cnt0 = JSON.stringify(data['total0']);
				cnt0 = cnt0.replace(/"/g, '');
				if(parseInt(cnt0) == 0){
					$('.alert-notif').addClass('hide-notif');
				}
			}
		});
	}

    var initCostingOutDetail = function () {
        var table = $('#costing-outbound-detail');
		table = table.dataTable({
			"order": [
				[ 0, "asc" ]
			],
			"scrollX": true
		});
    }


	var initCostingCross = function(){
		var table = $("#costing-crosscity");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_costing-crosscity/costing-crosscity_action.php?module=costing-crosscity&act=data"
			},
			"order": [
				[ 0, "desc" ], [ 1, "asc" ]
			],
			"columnDefs": [{
				"targets": [2,5,6,8,9],
				"orderable": false,
				"searchable": false,
			}],
			"scrollX": true,
			"initComplete": function( settings, data ) {
				var cnt0 = JSON.stringify(data['total0']);
				cnt0 = cnt0.replace(/"/g, '');
				if(parseInt(cnt0) == 0){
					$('.alert-notif').addClass('hide-notif');
				}
			}
		});
	}

	var initKpikaryawan = function() {
		var table = $("#kpikaryawan");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_kpi/kpi_action.php?module=kpi&act=readkaryawan"
			},
			"order": [
				[ 0, "asc" ]
			],
			"columnDefs": [{
				"targets": [3],
				"orderable": false,
			}]
		});
	}
	var initKpiDistribution = function() {
		var table = $("#kpidistribution");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_kpi/kpi_action.php?module=kpi&act=readdistribution"
			},
			"order": [
				[ 0, "asc" ]
			],
			"columnDefs": [{
				"targets": [3],
				"orderable": false,
			}]
		});
	}
	var initKpicabang = function() {
		var table = $("#kpicabang");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_kpi/kpi_action.php?module=kpi&act=readcabang"
			},
			"order": [
				[ 0, "asc" ]
			],
			"columnDefs": [{
				"targets": [3],
				"orderable": false,
			}]
		});
	}
	var initKpivendor = function() {
		var table = $("#kpivendor");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_kpi/kpi_action.php?module=kpi&act=readvendor"
			},
			"order": [
				[ 0, "asc" ]
			],
			"columnDefs": [{
				"targets": [1],
				"orderable": false,
			}]
		});
	}
	var initKpicustomer = function() {
		var table = $("#kpicustomer");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_kpi/kpi_action.php?module=kpi&act=readcustomer"
			},
			"order": [
				[ 0, "asc" ]
			],
			"columnDefs": [{
				"targets": [1,2],
				"orderable": false,
			}]
		});
	}

	var initCostingCrossDetail = function () {
		var table = $('#costing-crosscity-detail');
		table = table.dataTable({
			"order": [
				[ 0, "asc" ]
			],
			"scrollX": true
		});
	}

    return {

        //main function to initiate the module
        init: function() {
            if (!jQuery().dataTable) {
                return;
            }
            initMailbox();
            initAirwaybillSrc();
			initNegara();
            initKota();
			initAgen();
			initVendor();
			initKustomer();
			initUser();
			initUserClient();
			initSla();
			initAgentOutbound();
			initAgentInbound();
			initBooking();
			initBookingClient();
			initBookingin();
			initBookinginv2();
			initBookinginClient();
			initPickup();
			initManifest();
			initManifestP();
			initReportAgentDeliv();
			initReportAgentPickup();
			initManifestOutbound();
			initManifestInboundList();
			initManifestInbound();
			initManifestInboundDetail();
			initDelivery();
			initReport();
			initReportwarehouse();
			initPemakaianRak();
			initRateCustomer();
			initInvoice();
			initManifestPickup();
			initManifestPickupDetail();
			initManifestDelivery();
			initTarifKirimanLuarNegeriPaket();
			initTarifKirimanLuarNegeriDokumen();
			initTarifKirimanTopUrgent();
			initTarifKirimanUrgent();
			initTarifKirimanRegularOutbound();
			initTarifKirimanRegularInbound();
			initTarifKirimanIntercity();
			initInvoiceCustomer();
			initInvoiceAgent();
			initAirwaybillInvoice();
			initCostingPickupIntercity();
			initCostingD();
			initCostingDDetail();
			initCostingIn();
			initCostingInboundDetail();
			initCostingOut();
			initBarang();
			initBarangnonaktifadmin();
			initCostingOutDetail();
			initAirwaybillSearch();
			initAirwaybill();
			initInvoiceAgentnew();
			initAirwaybillClient();
			initBarangnonaktif();
			initManifestOutboundList();
			initRak();
			initCostingCross();
			initCostingCrossDetail();
			initReportVendor();
			initBookingw();
			initKpikaryawan();
			initKpicabang();
			initKpiDistribution();
			initKpicustomer();
			initKpivendor();
			initKeluarBarangBelumTercatat();
        }

    };

}();
