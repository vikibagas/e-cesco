var TableManaged = function () {
	
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
				"targets": [6],
				"orderable": false,
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
				url :"module/mod_bookingin/bookingin_action.php?module=bookingin&act=read",
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
		setInterval(function() {
			table.api().ajax.reload(null, false);
		}, 60000 );
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
				url :"module/mod_pickup/pickup_action.php?module=pickup&act=read",
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
		setInterval(function() {
			table.api().ajax.reload(null, false);
		}, 60000 );
	}

	// Manifest Pickup
	var initManifestP = function(){
		var table = $("#manifestp");
		table.DataTable({
			"rowReorder": {
				selector: "td:nth-child(2)"
			},
			"responsive": true,
			"processing": true,
			"serverSide": true,
			"ajax":{
				url :"module/mod_manifest-pickup/manifest-pickup_action.php?module=pickup&act=data"
			},
			"order": [
				[ 0, "desc" ],[ 1, "desc" ]
			],
			"columnDefs": [{
				"targets": [6],
				"orderable": false,
			}]
		});
		//setInterval(function() {
			//table.api().ajax.reload(null, false);
		//}, 60000 );
	}
	
	// Manifest Outbound List
	var initManifestOutboundList = function(){
		var table = $("#manifest-outboundlist");
		table.DataTable({
			"rowReorder": {
				selector: "td:nth-child(2)"
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
				"targets": [5],
				"orderable": false,
			}]
		});
		setInterval(function() {
			table.api().ajax.reload(null, false);
		}, 60000 );
    }
	
	// Manifest Inbound List
	var initManifestInboundList = function(){
		var table = $("#manifest-inboundlist");
		table.DataTable({
			"rowReorder": {
				selector: "td:nth-child(2)"
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
	
	// tabel manifest
	var initManifest = function() { 
		var table = $("#manifest");
		table.DataTable({
			"rowReorder": {
				selector: "td:nth-child(2)"
			},
			"responsive": true,
			"order": [
				[ 0, "desc" ]
			],
			"columnDefs": [{
				"targets": [1],
				"orderable": false,
			}],
		});
	}
	
	var initReport = function () {
        var table = $('#report');
		table = table.dataTable({
			"dom": "<'row'<'col-sm-6'l><'col-sm-3'f><'col-sm-3'T>>R" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-6'i><'col-sm-6'p>>",
			"tableTools": {
				"aButtons": [
					"print",
					"xls",
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
		//table = new table.dataTable.FixedColumns(table,{
			//leftColumns: 2,
			//rightColumns: 1
		//});
		//new $.fn.dataTable.FixedColumns( table, {
			//leftColumns: 2,
			//rightColumns: 1
		//});
    }
	
	var initAirwaybill = function () {
        var table = $('#airwaybill');
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
				"targets": [8],
				"orderable": false,
			}]
		});
		table = new table.dataTable.FixedColumns(table,{
			leftColumns: 2
		});
		setInterval(function() {
			table.api().ajax.reload(null, false);
		}, 60000 );
    }

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
				"targets": [5],
				"orderable": false,
			}]
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
			]
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
				"targets": [5],
				"orderable": false,
			}]
		});
		setInterval(function() {
			table.api().ajax.reload(null, false);
		}, 60000 );
    }

    return {

        //main function to initiate the module
        init: function() {
            if (!jQuery().dataTable) {
                return;
            }
            initKota();
			initAgen();
			initKustomer();
			initUser();
			initUserClient();
			initBooking();
			initBookingClient();
			initBookingin();
			initBookinginClient();
			initPickup();
			initManifest();
			initManifestP();
			initManifestOutboundList();
			initManifestOutbound();
			initManifestInboundList();
			initReport();
			initManifestPickup();
			initManifestPickupDetail();
			initManifestDelivery();
			initAirwaybill();
        }

    };

}();