<?php
if (!isset($_SESSION))
{
	session_start();
}


if (empty($_SESSION['usernamex']) AND empty($_SESSION['passwordx'])){
    header('location:../../../index.php');
}
else
{
    include "../../../config/connection.php";
    require_once('../../../config/db_connect.php');
    include "../../../config/library.php";
    include "../../../config/encrypt.php";
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_invoice-customer/invoice-customer_action.php";

    $key						= 'asdf$1011';
	$idCust = $_SESSION['id_kustomerx'];
	$idCust =  base64_decrypt($idCust,$key);

	// List paket
	if ($module=='invoice-customer' and $act=='read')
	{
		$table = 
<<<EOT
	(
		SELECT			
			coalesce(a.nomor,a.status) as nomor, 
			a.tanggal, 
			b.nama_kustomer, 
			case when a.method=1 then concat(date_format(a.dari,"%d-%m-%Y"),' s/d ',date_format(a.sampai,"%d-%m-%Y")) else '-' end as tanggalairwaybill, 
			case when a.method=1 then LEFT(a.loc_code,16) else '-' end as loc_code,
			a.id_invoice as id_invoice,
			case when a.method=1 then 'range tanggal' else 'pilih pengiiman tertentu' end as metode
					
		FROM invoice a 
		
		left JOIN kustomer b ON b.id_kustomer=a.id_kustomer 
		
				where a.id_kustomer = $idCust
				AND a.tanggal >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
				and a.status = 'final'
		group by a.id_invoice
	) temp
EOT;
		$primaryKey = 'id_invoice';		
		$columns = array(
			array( 'db' => 'nomor', 'dt' => 0, 'formatter' => function($d,$row) {
				return	'<td><center>'.strtoupper($d).'</center></td>';
			}),
			array( 'db' => 'tanggal', 'dt' => 1 ),
			array( 'db' => 'metode', 'dt' => 2 ),
			array( 'db' => 'nama_kustomer', 'dt' => 3 ),
			array( 'db' => 'tanggalairwaybill', 'dt' => 4 ),			
			array( 'db' => 'loc_code', 'dt' => 5 ),
			
			array( 'db' => 'id_invoice', 'dt' => 6, 'formatter' => function($d,$row) {
				$aksi	= "module/mod_invoice-customer/invoice-customer_action.php";
				$key	= "asdf$1011";
				
				
				
				
				

				return '<td>
					<center>
					<div class="btn-group">					
					<a  class="btn btn-xs btn-default" title="Print Invoice" href="reportnew/invoice.php?idinvoice='.base64_encrypt($d,$key).'" target="_blank" ><i class="fa fa-file-text-o text-yellow"></i></a>
					<a  class="btn btn-xs btn-default" title="Print Lampiran AWB" href="reportnew/airwaybill_per_inv.php?idinvoice='.base64_encrypt($d,$key).'" target="_blank" ><i class="fa fa-print text-yellow"></i></a>
					<a  class="btn btn-xs btn-default" title="Print Lampiran Excel" href="reports/PHPExcel/invoice_lampiran.php?idinvoice='.base64_encrypt($d,$key).'" target="_blank" ><i class="fa fa-file-excel-o text-green"></i></a>
					</div>
					</center>
					</td>';
			}),
		);

		require('../../../config/ssp.class.php');
		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);

	}elseif($module=='invoice-customer' AND $act=='confirm'){
		$id = $_GET['id'];
		$stts = $_GET['stts'];

		$key 		= "asdf$1011";
        $user 		= $_SESSION['id_userx'];
        $id_user 	= base64_decrypt($user,$key);
        $id_invoice = base64_decrypt($id,$key);

        $sql = "update invoice_send set confirm_by = $id_user, confirm_dtm = now() where invoice_id = $id_invoice and invoice_type = 'CUSTOMER'";
        
        $mysqli->query($sql);
        header('Location: ' . $_SERVER['HTTP_REFERER']);

	}
	
}
?>