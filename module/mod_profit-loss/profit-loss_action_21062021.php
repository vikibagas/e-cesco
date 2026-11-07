<?php
if (!isset($_SESSION))
{
	session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password']))
{
	header('location:../../application/index.php');
}
else
{
	include "../../config/connection.php";
	require_once('../../config/db_connect.php');
	require_once('../../config/library.php');
	require_once('../../config/encrypt.php');

	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';

	$aksi="module/mod_profit-loss/profit-loss_action.php";

	// List paket
	if ($module=='profit-loss' and $act=='read')
	{
		$table =
<<<EOT
	(
		SELECT
			coalesce(a.nomor,a.status) as nomor,
			a.tanggal,
			b.nama_kustomer,
			case when a.method=1 then concat(date_format(a.dari,"%d-%m-%Y"),' s/d ',date_format(a.sampai,"%d-%m-%Y")) else '-' end as tanggalairwaybill,
			/*case when a.method=1 then LEFT(a.loc_code,16) else '-' end as loc_code,*/
			a.id_kustomer,
			a.id_invoice as id_invoice,
			case when a.method=1 then 'range tanggal' else 'pilih pengiiman tertentu' end as metode,
			s.id as send, s.confirm_by as confirm
		FROM invoice a
		left JOIN kustomer b ON b.id_kustomer=a.id_kustomer
		left outer JOIN invoice_send s ON s.invoice_id=a.id_invoice and s.invoice_type = 'CUSTOMER'
		where a.status = 'final'
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
			//array( 'db' => 'loc_code', 'dt' => 5 ),
			array( 'db' => 'id_kustomer', 'dt' => 5, 'formatter' => function($d,$row) {
				$aksi	= "reports/PHPExcel/excel_profit-loss.php?id_kustomer=".$row['id_kustomer']."&idinvoice=".$row['id_invoice']."&stts=loss";

				return '<td><center>
				<div class="btn-group">
					<button class="btn btn-primary pull-right" onclick="window.location.href = \''.$aksi.'\';"><i class="fa fa-file-excel-o"></i> Excel</button>
				</div></center></td>';
			}),
            array( 'db' => 'send', 'dt' => 7 ),
            array( 'db' => 'confirm', 'dt' => 8 ),
			array( 'db' => 'id_invoice', 'dt' => 6, 'formatter' => function($d,$row) {
				$aksi	= "module/mod_invoice-customer/invoice-customer_action.php";
				$key	= "asdf$1011";
				$nomor = 'nomor';

				return '<td><center>
				<div class="btn-group">
					<button class="btn btn-xs btn-default" onclick="window.location.href = \'?module=profit-loss&act=detail&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-file text-yellow"></i></button>
				</div></center></td>';
			}),
		);

		require('../../config/ssp.class.php');
		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);


	}

}
?>
