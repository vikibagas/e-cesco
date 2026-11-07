<?php
$module = !empty($_GET['module']) ? $_GET['module'] : '';

if ($module=='home'){
	include ('home.php');
}
elseif ($module=='traffic'){
	include ('traffic.php');
}
elseif ($module=='traffic-mobile'){
	include ('traffic-mobile.php');
}
elseif($module=='country'){
	include "module/mod_country/country.php";
}
elseif($module=='destination'){
	include "module/mod_destination/destination.php";
}
elseif($module=='branch'){
	include "module/mod_branch/branch.php";
}
elseif($module=='customer'){
	include "module/mod_customer/customer.php";
}
elseif($module=='users'){
	include "module/mod_users/users.php";
}
elseif($module=='agent-outbound'){
	include "module/mod_agent-outbound/agent-outbound.php";
}
elseif($module=='agent-inbound'){
	include "module/mod_agent-inbound/agent-inbound.php";
}
elseif($module=='agent-cross'){
	include "module/mod_agent-cross/agent-cross.php";
}
elseif($module=='sla-outbound'){
	include "module/mod_sla/sla-outbound.php";
}
elseif($module=='sla-inbound'){
	include "module/mod_sla/sla-inbound.php";
}
elseif($module=='sla-cross'){
	include "module/mod_sla/sla-cross.php";
}
elseif($module=='sla-deliv'){
	include "module/mod_sla/sla-deliv.php";
}
elseif($module=='sla-delivdhl'){
	include "module/mod_sla/sla-delivdhl.php";
}
elseif($module=='leadtime'){
	include "module/mod_leadtime/leadtime.php";
}
elseif($module=='booking'){
	include "module/mod_booking/booking.php";
}
elseif($module=='bookingin'){
	include "module/mod_bookingin/bookingin.php";
}
elseif($module=='bookinginv2'){
	include "module/mod_bookinginv2/bookinginv2.php";
}
elseif($module=='pickup'){
	include "module/mod_pickup/pickup.php";
}
elseif($module=='checklist'){
	include "module/mod_checklist/checklist.php";
}
elseif($module=='manifest-pickup'){
	include "module/mod_manifest-pickup/manifest-pickup.php";
}
elseif($module=='manifest-outbound'){
	include "module/mod_manifest-outbound/manifest-outbound.php";
}
elseif($module=='manifest-inbound'){
	include "module/mod_manifest-inbound/manifest-inbound.php";
}
elseif($module=='manifest'){
	include "module/mod_manifest/manifest.php";
}
elseif($module=='mfpickup'){
	include "module/mod_mfpickup/mfpickup.php";
}
elseif($module=='intercity'){
	include "module/mod_intercity/intercity.php";
}
elseif($module=='outbound'){
	include "module/mod_outbound/outbound.php";
}
elseif($module=='inbound'){
	include "module/mod_inbound/inbound.php";
}
elseif($module=='delivery'){
	include "module/mod_delivery/delivery.php";
}
elseif($module=='airwaybill'){
	include "module/mod_airwaybill/airwaybill.php";
}
elseif($module=='user'){
	include "module/mod_user/user.php";
}
elseif($module=='kurir'){
	include "module/mod_kurir/kurir.php";
}
elseif($module=='profile'){
	include "module/mod_profile/profile.php";
}
elseif($module=='clientarea'){
	include "module/mod_clientarea/clientarea.php";
}
elseif($module=='report-airwaybill-by-date'){
	include "module/mod_report-airwaybill-by-date/report-airwaybill-by-date.php";
}
elseif($module=='report-airwaybill-by-customer'){
	include "module/mod_report-airwaybill-by-customer/report-airwaybill-by-customer.php";
}
elseif($module=='rate'){
	include "module/mod_rate/rate_customer.php";
}
elseif($module=='report-airwaybill-by-courier'){
	include "module/mod_report-airwaybill-by-courier/report-airwaybill-by-courier.php";
}
elseif($module=='reportairwaybillbycustomer'){
	include "module/mod_report/reportairwaybillbycustomer.php";
}
elseif($module=='report-manifest-pickup'){
	include "module/mod_report-manifest-pickup/report-manifest-pickup.php";
}
elseif($module=='report-manifest-outbound'){
	include "module/mod_report-manifest-outbound/report-manifest-outbound.php";
}
elseif($module=='report-manifest-inbound'){
	include "module/mod_report-manifest-inbound/report-manifest-inbound.php";
}
elseif($module=='report-manifest-delivery'){
	include "module/mod_report-manifest-delivery/report-manifest-delivery.php";
}
elseif($module=='report-delivery-agent'){
	include "module/mod_report-delivery-agent/report-delivery-agent.php";
}
elseif($module=='report-pickup-agent'){
	include "module/mod_report-pickup-agent/report-pickup-agent.php";
}

// Finance
elseif($module=='tarif-luarnegeri'){
	include "module/mod_tarif-luarnegeri/tarif-luarnegeri.php";
}
elseif($module=='tarif-urgent'){
	include "module/mod_tarif-urgent/tarif-urgent.php";
}
elseif($module=='tarif-regular'){
	include "module/mod_tarif-regular/tarif-regular.php";
}
elseif($module=='tarif-intercity'){
	include "module/mod_tarif-intercity/tarif-intercity.php";
}
elseif($module=='costing-pickup-intercity'){
	include "module/mod_costing-pickup/costing-pickup-intercity.php";
}
elseif($module=='costing-delivery-intercity'){
	include "module/mod_costing-delivery/costing-delivery-intercity.php";
}
elseif($module=='costing-inbound'){
	include "module/mod_costing-inbound/costing-inbound.php";
}
elseif($module=='costing-outbound'){
	include "module/mod_costing-outbound/costing-outbound.php";
}
elseif($module=='invoice-customer'){
	include "module/mod_invoice-customer/invoice-customer.php";
}
elseif($module=='profit-loss'){
	include "module/mod_profit-loss/profit-loss.php";
}
elseif($module=='invoice-agent'){
	include "module/mod_finance_invoice-agent/invoice-agent.php";
}
elseif($module=='perkiraan'){
	include "module/mod_finance_perkiraan/perkiraan.php";
}
elseif($module=='jurnal-umum'){
	include "module/mod_finance_jurnal-umum/jurnal-umum.php";
}
elseif($module=='jurnal-kas-keluar'){
	include "module/mod_finance_jurnal-kas-keluar/jurnal-kas-keluar.php";
}
elseif($module=='mail'){
	include "module/mod_mail/mail.php";
}
elseif($module=='awb-compare'){
	include "module/mod_awb-compare/awb-compare.php";
}elseif($module=='awb-belum-tertagih'){
	include "module/mod_finance-awb-customer-belum-tertagih/finance-awb-customer-belum-tertagih.php";
}
elseif($module=='invoice-agent-compare') {
    include "module/mod_invoice-agent-compare/invoice-agent-compare.php";
}
elseif($module=='costing-crosscity') {
    include "module/mod_costing-crosscity/costing-crosscity.php";
}
elseif($module=='vendor') {
    include "module/mod_vendor/vendor.php";
}
elseif($module=='invoice-or') {
    include "module/mod_invoice-or/invoice-or.php";
}
elseif($module=='rak') {
    include "module/mod_warehouse-rak/rak.php";
}
elseif($module=='pemakaian-rak') {
    include "module/mod_warehouse-report/pemakaian-rak.php";
}
elseif($module=='barang') {
    include "module/mod_warehouse-barang/barang.php";
}
elseif($module=='barang-nonaktif') {
    include "module/mod_warehouse-barang/barang-nonaktif.php";
}
elseif($module=='report-warehouse') {
    include "module/mod_warehouse-report/report.php";
}
elseif($module=='keluar-barang-belum-tercatat') {
    include "module/mod_warehouse-report/keluar-barang-belum-tercatat.php";
}
elseif($module=='warehouse-history') {
    include "module/mod_warehouse-report/history.php";
}
elseif($module=='booking-warehouse') {
    include "module/mod_warehouse-booking/booking-warehouse.php";
}
elseif($module=='kpi-karyawan') {
    include "module/mod_kpi/kpi_karyawan.php";
}
elseif($module=='kpi-cabang') {
    include "module/mod_kpi/kpi-cabang.php";
}
elseif($module=='kpi') {
    include "module/mod_kpi/kpi.php";
}
elseif($module=='kpi-distribution-team') {
    include "module/mod_kpi/kpi-distribution-team.php";
}
elseif($module=='kpi-customer') {
    include "module/mod_kpi/kpi-customer.php";
}
elseif($module=='kpi-vendor') {
    include "module/mod_kpi/kpi-vendor.php";
}
elseif($module=='vechile') {
    include "module/mod_vechile/vechile.php";
}
elseif($module=='tracking-courier') {
    include "module/mod_tracking-courier/tracking.php";
}
elseif($module=='tracking-report') {
    include "module/mod_tracking-courier/tracking_report.php";
}
?>