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
				url :"module/mod_pickup/pickup_action.php?module=pickup&act=data"
			},
			"order": [
				[ 0, "desc" ],[ 1, "desc" ]
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
				"targets": [0,3,6,7],
				"orderable": false,
				"searchable": false,
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
				"targets": [2,5,6,7,8],
				"orderable": false,
				"searchable": false,
			}]
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
				"targets": [2,5,6],
				"orderable": false,
				"searchable": false,
			}]
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
			"order": [
				[ 0, "asc" ],[ 1, "asc" ]
			],
			"scrollX": true,
			"columnDefs": [{
				"targets": [2],
				
			}],
		});
		/*table = new table.dataTable.FixedColumns(table,{
			leftColumns: 2
		});*/		
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
		table = new table.dataTable.FixedColumns(table,{
			leftColumns: 2
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
			]
		});
	}

	var initInvoiceAgent = function(){
		var table = $("#InvoiceAgent");
		table.DataTable({
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"ajax":{
				url :"module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=read"
			},
			"order": [
				[ 0, "desc" ]
			]
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
				"targets": [2,3,4,6],
				"orderable": false,
				"searchable": false,
			}]
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
				"targets": [2,3,4,5,7],
				"orderable": false,
				"searchable": false,
			}]
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
				"targets": [1,3,4,5,6,7,9],
				"orderable": false,
				"searchable": false,
			}],
			"scrollX": true
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
				"targets": [2,4,5,6,8,9],
				"orderable": false,
				"searchable": false,
			}],
			"scrollX": true
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
	
    return {

        //main function to initiate the module
        init: function() {
            if (!jQuery().dataTable) {
                return;
            }
            initAirwaybillSrc();
			initNegara();
            initKota();
			initAgen();
			initKustomer();
			initUser();
			initUserClient();
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
			initManifestOutboundList();
			initManifestOutbound();
			initManifestInboundList();
			initManifestInbound();
			initManifestInboundDetail();
			initDelivery();
			initReport();
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
			initCostingOutDetail();
			initAirwaybill();
			initAirwaybillSearch();
			initAirwaybillCust();
        }

    };

}();