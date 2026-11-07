//turn to inline mode
$.fn.editable.defaults.mode = 'popup';
		
$('.berat_aktual').editable({
	url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?act=berat_aktual',
	title: 'Masukkan berat aktual'
});
		
$('.biaya_packing').editable({
	url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=biaya_packing',
	title: 'Masukkan biaya packing',
	success: function(res) {
    	location.reload();
    }
});
$('.biaya_packing_in').editable({
    url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=biaya_packing_in',
    title: 'Masukkan biaya packing',
    success: function(res) {
        location.reload();
    }
});

$('.biaya_tambahan').editable({
	url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=biaya_tambahan',
	title: 'Masukkan biaya tambahan',
	success: function(res) {
    	location.reload();
    }
});

$('.edit-remark').editable({
    url: 'module/mod_airwaybill/airwaybill_action.php?module=airwaybill&act=edit-remark',
    title: 'Edit Remark'
});

$('.edit-koli-ke-asus').editable({
    url: 'module/mod_airwaybill/airwaybill_action.php?module=airwaybill&act=asus-edit-koli-ke',
    title: 'Edit koli ke'
});


$('.biaya_tambahan_in').editable({
    url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=biaya_tambahan_in',
    title: 'Masukkan biaya tambahan',
    success: function(res) {
        location.reload();
    }
});

$('.biaya_tambahan_transit').editable({
    url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=biaya_tambahan_transit',
    title: 'Masukkan biaya tambahan',
    success: function(res) {
        //location.reload();
    }
});

$('.keterangan_biaya').editable({
	url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=keterangan_biaya',
	title: 'Masukkan keterangan biaya'
});
$('.keterangan_biaya_in').editable({
    url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=keterangan_biaya_in',
    title: 'Masukkan keterangan biaya'
});

$('.berat_agent').editable({
	url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=berat-agent',
	title: 'Edit Berat Agent'
});

$('.rate_agent').editable({
	url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=rateout-update',
	title: 'Update Harga'
});
$('.rate_agent_in').editable({
	url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=ratein-update',
	title: 'Update Harga'
});
$('.rate_agent_cross').editable({
	url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=ratecross-update',
	title: 'Update Harga'
});

//min kg
$('.min_kg_agent').editable({
	url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=kg-update',
	title: 'Update MIN KG'
});
$('.min_kg_agent_in').editable({
	url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=kgin-update',
	title: 'Update MIN KG'
});
$('.min_kg_agent_cross').editable({
	url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=kgcross-update',
	title: 'Update MIN KG'
});
$('.min_kg_agent_transit').editable({
	url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=kgtransit-update',
	title: 'Update MIN KG'
});


$('.rate_agent_create').editable({
	url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=rate-new',
	title: 'Masukkkan harga'
});

$('.min_kg').editable({
	url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?act=min-kg',
	title: 'Masukkkan kg'
});
$('.cust_packing').editable({
	url: 'module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=packing',
	title: 'Masukkkan biaya packing'
});
$('.cust_asuransi').editable({
	url: 'module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=asuransi',
	title: 'Masukkkan biaya asuransi'
});
$('.cust_tambahan').editable({
	url: 'module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=tambahan',
	title: 'Masukkkan biaya tambahan'
});

$('.tambahan_manual').editable({
    url: 'module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=tambahan_manual',
    title: 'Masukkkan Biaya Tambahan',
    success: function(res) {
        location.reload();
    }
});

$('.update-sn').editable({
    url: 'module/mod_warehouse-barang/barang_action.php?module=barang&act=update-sn',
    title: 'Edit Serial Number'
});

$('.ket_biaya').editable({
	url: 'module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=ket_biaya',
	title: 'Masukkkan Keterangan'
});

$('.remarks').editable({
    url: 'report-manifest-outbound_action.php?module=outbound&act=update-remarks',
    title: 'remarks'
});

$('.manifest-remarks').editable({
    url: 'module/mod_report-manifest-outbound/report-manifest-outbound_action.php?module=outbound&act=update-manifest-remarks',
    title: 'remarks'
});

$('.berat-smu').editable({
    url: 'module/mod_report-manifest-outbound/report-manifest-outbound_action.php?module=outbound&act=update-berat-smu',
    title: 'Berat'
});
$('.collie-smu').editable({
    url: 'module/mod_report-manifest-outbound/report-manifest-outbound_action.php?module=outbound&act=update-collie-smu',
    title: 'Collie'
});

$('.type_pengiriman').editable({
	url: 'module/mod_report-airwaybill-by-customer/report-airwaybill-by-customer_action.php?act=inbound',
	title: 'Select type',
	value: 'Y',
	source: [
		{value: 'Y', text: 'INBOUND'},
		{value: 'N', text: 'OUTBOUND'}
	]
});

$('.expired_action').editable({
    url: 'module/mod_warehouse-report/report_action.php?module=barang&act=update-expired-action',
    title: 'Select',
    mode: 'inline',
    source: [
        {value: 'keep', text: 'Keep'},
        {value: 'destroy', text: 'Destroy'}
    ]
});
$('.expired_datechange').editable({
    url: 'module/mod_warehouse-report/report_action.php?module=barang&act=update-expired-date',
    title: 'Expired date (YEAR-MONTH-DATE)'
});


$('.diskon').editable({
    url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=diskon',
    title: 'Masukkan diskon (%)',
    success: function(res) {

    	var data = JSON.parse(res);
        var total = data.total-((data.diskon/100)*data.total);
        total = total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    	$('#totAfterDisc'+data.id_pengiriman).text(total);
        location.reload();
    }
});

$('.diskon-pickup').editable({
    url: 'module/mod_costing-pickup/costing-pickup-intercity_action.php?module=costing-pickup-intercity&act=diskon',
    title: 'Masukkan diskon (%)',
    success: function(res) {

    	var data = JSON.parse(res);
        var total = data.total-((data.diskon/100)*data.total);
        total = total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    	$('#totAfterDisc'+data.id_pengiriman).text(total);
        location.reload();
    }
});

$('.diskon-delivery').editable({
    url: 'module/mod_costing-delivery/costing-delivery-intercity_action.php?module=costing-delivery-intercity&act=diskon',
    title: 'Masukkan diskon (%)',
    success: function(res) {

    	var data = JSON.parse(res);
        var total = data.total-((data.diskon/100)*data.total);
        total = total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    	$('#totAfterDisc'+data.id_pengiriman).text(total);
        location.reload();
    }
});

$('.biaya-packing-delivery').editable({
    url: 'module/mod_costing-delivery/costing-delivery-intercity_action.php?module=costing-delivery-intercity&act=packing',
    title: 'Masukkan Biaya Packing',
    success: function(res) {
        var data = JSON.parse(res);
    	var id_pengiriman = data.id_pengiriman;
    	var biaya_packing = data.biaya_packing;
    	$('#spPacking'+id_pengiriman).text('');
		location.reload();
    }
});

$('.biaya-delivery').editable({
    url: 'module/mod_costing-delivery/costing-delivery-intercity_action.php?module=costing-delivery-intercity&act=biaya-delivery-manual',
    title: 'Masukkan Biaya Delivery',
    success: function(res) {
        var data = JSON.parse(res);
        var id_pengiriman = data.id_pengiriman;
        var costing_delivery_fee = data.costing_delivery_fee;
        $('#spBiayadelivery'+id_pengiriman).text('');
        location.reload();
    }
});

$('.biaya-packing-pickup').editable({
    url: 'module/mod_costing-pickup/costing-pickup-intercity_action.php?module=costing-pickup-intercity&act=packing',
    title: 'Masukkan Biaya Packing',
    success: function(res) {
        var data = JSON.parse(res);
        var id_pengiriman = data.id_pengiriman;
        var biaya_packing = data.biaya_packing;
        $('#spPacking'+id_pengiriman).text('');
        location.reload();
    }
});

$('.biaya-handling-outbound').editable({
    url: 'module/mod_costing-outbound/costing-outbound_action.php?module=costing-outbound&act=handling',
    title: 'Masukkan Biaya Handling',
    success: function(res) {
        var data = JSON.parse(res);
        var id_pengiriman = data.id_pengiriman;
        var biaya_handling = data.costing_outbound_handling;
        $('#spHandling'+id_pengiriman).text('');
        location.reload();
    }
});

$('.biaya-smu-outbound').editable({
    url: 'module/mod_costing-outbound/costing-outbound_action.php?module=costing-outbound&act=smu',
    title: 'Masukkan Biaya SMU',
    success: function(res) {
        var data = JSON.parse(res);
        var id_pengiriman = data.id_pengiriman;
        var biaya_smu = data.costing_outbound_smu;
        $('#spSmu'+id_pengiriman).text('');
        location.reload();
    }
});

$('.biaya-insurance-outbound').editable({
    url: 'module/mod_costing-outbound/costing-outbound_action.php?module=costing-outbound&act=insurance',
    title: 'Masukkan Biaya Insurance',
    success: function(res) {
        var data = JSON.parse(res);
        var id_pengiriman = data.id_pengiriman;
        var biaya_insurance = data.costing_outbound_insurance;
        $('#spInsurance'+id_pengiriman).text('');
        location.reload();
    }
});

$('.inv-first').editable({
    url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=update-inv-first',
    title: 'Invoice First'
});
$('.inv-end').editable({
    url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=update-inv-end',
    title: 'Invoice End'
});
$('.nomor-inv').editable({
    url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=update-nomor-invoice',
    title: 'Nomor Invoice'
});
$('.edit_pickupdate').editable({
    url: 'module/mod_airwaybill/airwaybill_action.php?module=airwaybill&act=update-pickup-date',
    title: 'Pickup date'
});

