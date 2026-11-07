//turn to inline mode
$.fn.editable.defaults.mode = 'popup';
		
$('.berat_aktual').editable({
	url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?act=berat_aktual',
	title: 'Masukkan berat aktual'
});
		
$('.biaya_packing').editable({
	url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=biaya_packing',
	title: 'Masukkan biaya packing'
});

$('.biaya_tambahan').editable({
	url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=biaya_tambahan',
	title: 'Masukkan biaya tambahan'
});

$('.keterangan_biaya').editable({
	url: 'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=keterangan_biaya',
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

$('.type_pengiriman').editable({
	url: 'module/mod_report-airwaybill-by-customer/report-airwaybill-by-customer_action.php?act=inbound',
	title: 'Select type',
	value: 'Y',
	source: [
		{value: 'Y', text: 'INBOUND'},
		{value: 'N', text: 'OUTBOUND'}
	]
});