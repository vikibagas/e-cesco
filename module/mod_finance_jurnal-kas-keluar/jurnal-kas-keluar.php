<section class="content-header">
	<h1>Jurnal Kas Keluar<small></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Finance</a></li>
		<li><a href="#">Transaksi</a></li>
		<li class="active">Jurnal Kas Keluar</li>
	</ol>
</section>
<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../application/login.php');
}
else{
	//cek hak akses user
	if($u['level']=='admin' || $u['level']=='finance'){
		
		//jurnal baru. cari nomor paling besar yaitu nomor jurnal terakhir 
		$jurnal_keluar=mysqli_fetch_row(mysqli_query($con1,"SELECT max(nomor_jurnal) FROM jurnal_keluar ORDER BY tanggal_selesai DESC"));
		$nomor_jurnal=$jurnal_keluar[0]+1;
		$kode_transaksi="KK/".$nomor_jurnal;
			
		echo'
		<section class="content">
			<div class="box box-primary">
				<form method="post" action="?module=jurnal-kas-keluar" class="form-horizontal form-bordered" autocomplete="off">
					<div class="box-body">
						<div class="form-group">
							<label class="col-sm-3 control-label full-right">Nomor Bukti <span class="required">* </span></label>
							<div class="col-sm-3">
								<input type="hidden" name="kode_bukti" value="'.$kode_transaksi.'">
								<input type="text" class="form-control" value="'.$kode_transaksi.'" disabled/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label full-right">Tanggal <span class="required">* </span></label>
							<div class="col-sm-3">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control" id="tanggal" name="tanggal" value="';if(empty($_POST['tanggal_transaksi'])){ echo ''.$setdate.'';}else{ echo ''.$_POST['tanggal_transaksi'].'';} echo'" readonly>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label full-right">Keterangan Jurnal <span class="required">* </span></label>
							<div class="col-sm-6">
								<input type="text" class="form-control" value="';if(isset($_POST['keterangan_jurnal'])){ echo ''.$_POST['keterangan_jurnal'].'';} echo'" name="keterangan_jurnal"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label full-right">Nomor Rekening <span class="required">*</span></label>
							<div class="col-sm-2">
								<input type="text" class="form-control" name="kode_rekening" id="kode_rekening" required>
							</div>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="nama_rekening" id="nama_rekening" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label full-right">Keterangan Transaksi <span class="required">*</span></label>
							<div class="col-sm-6">
								<input type="text" class="form-control" value="';if(isset($_POST['keterangan_jurnal'])){ echo ''.$_POST['keterangan_jurnal'].''; }echo'" name="keterangan_transaksi">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label full-right">Jumlah <span class="required">*</span></label>
							<div class="col-md-3">
								<div class="input-group m-b">
									<span class="input-group-addon">Rp</span> <input type="text" name="jumlah_dk" class="form-control number" placeholder="0" required>
								</div>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<button type="submit" class="btn btn-primary" name="simpan">Save</button>
					</div>
				</form>
			</div>';
		
			if(isset($_POST['simpan'])){
				$kode_transaksi=$_POST['kode_bukti'];
				$tanggal_transaksi=$_POST['tanggal_transaksi'];
				$keterangan_transaksi=ucwords($_POST['keterangan_transaksi']);
				$kode_rekening=$_POST['kode_rekening'];
				$keterangan_jurnal=ucwords($_POST['keterangan_jurnal']);
				
				//untuk jurnal kas keluar, semua transaksi ada sisi DEBET
				$jumlah_dk=ucwords($_POST['jumlah_dk']);
				
				$query=mysqli_query($con1,"INSERT INTO tabel_transaksi(kode_transaksi,kode_rekening,tanggal_transaksi, jenis_transaksi, keterangan_transaksi,debet,id_admin) VALUES ('$kode_transaksi','$kode_rekening','$tanggal_transaksi','Kas Keluar','$keterangan_transaksi','$jumlah_dk','$id_admin')");
				if($query){
					//echo "berhasil";
				}else{
					echo mysqli_error();
				}
				
			}else{
				unset($_POST['simpan']);
			}
			
			//untuk menyelesaikan transaksi
			if(isset($_POST['selesai'])){
				$kode_transaksi=$_POST['kode_bukti'];
				$nomor_jurnal=$_POST['nomor_jurnal'];
				$tanggal_selesai=$_POST['tanggal_selesai'];
				$tot_debet=$_POST['tot_debet'];
				$keterangan_jurnal=$_POST['keterangan_jurnal'];
				
				//karena kas keluar lawan transaksinya adalah kas, maka kas adlah 111.01 yaitu kas unit umum yang ada di sisi KREDIT.
				$kode_rekening="111.01";
				
				$query_transaksi=mysqli_query($con1,"INSERT INTO tabel_transaksi(kode_transaksi,kode_rekening,tanggal_transaksi, jenis_transaksi, keterangan_transaksi,kredit,id_admin) VALUES ('$kode_transaksi','$kode_rekening','$tanggal_selesai','Kas Keluar','$keterangan_jurnal','$tot_debet','$id_admin')");
									
				$query_jurnal=mysqli_query($con1,"INSERT INTO jurnal_keluar(nomor_jurnal,kode_transaksi,tanggal_selesai) VALUES ('$nomor_jurnal','$kode_transaksi','$tanggal_selesai')");
									
				if($query_transaksi){
					echo'<script language="javascript">document.location.href="?module=jurnal-kas-keluar"</script>';
				}else{
					echo mysqli_error();
				}
				
				
			}else{
				unset($_POST['selesai']);
			}
			
			//untuk mendecode url yang di enrypsi
			//$var=decode($_SERVER['REQUEST_URI']);
	
			//pecahkan nilai array
			if(isset($_GET['mode'])){
				
				$mode=$_GET['mode'];
				$id_transaksi=$_GET['id_transaksi'];
				
				if($mode=='delete'){
					$query=mysqli_query($con1,"DELETE FROM tabel_transaksi where id_transaksi='$id_transaksi'");
				}else{
					echo mysqli_error();
				}
			
			}
			
			//untuk menampilkan transaksi
			echo'
			<div class="row">
				<div class="col-xs-12">
					<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title">Jurnal Kas Keluar</h3>
						</div>
						<div class="box-body table-responsive">
							<table class="table table-bordered table-striped">
								<tr>
									<th>Kode Rekening</th>
									<th>Keterangan</th>
									<th>Debet</th>
									<th>Action</th>
								</tr>';
								$tot_debet=0;
								$query=mysqli_query($con1,"SELECT * FROM tabel_transaksi WHERE kode_transaksi='$kode_transaksi' and id_admin='$u[id_user]'");
								while($row=mysqli_fetch_array($query)){
									$debet=$row['debet'];
									$id_transaksi=$row['id_transaksi'];
									$tot_debet=$tot_debet+$debet;
									echo'
									<tr>
										<td>'.$row['kode_rekening'].'</td>
										<td>'.$row['keterangan_transaksi'].'</td>
										<td align="right">';
										if($debet!=="0"){
											echo ''.number_format($debet,2,'.',',').'';
										}
										echo'</td>
										<td align="center"><a href="?module=jurnal-kas-keluar&mode=delete&id_transaksi='.$id_transaksi.'" onClick="return confirm(\'Apakah Anda yakin?\')">Cancel</a></td>
									</tr>';
								}
								echo'
								<tr>
									<td colspan="2" align="center"><b>TOTAL</b></td>
									<td align="right"><b>'.number_format($tot_debet,2,'.',',').'</b></td>
									<td align="center"></td>
								</tr>
							</table>
						</div>
						<div class="box-footer">
							<form action="?module=jurnal-kas-keluar" method="post" name="form">
								<input type="hidden" name="tanggal_selesai" value="';if(empty($_POST['tanggal_transaksi'])){ echo ''.$tanggal.'';}else{ echo ''.$_POST['tanggal_transaksi'].''; }echo'"/>
								<input type="hidden" name="kode_bukti" value="'.$kode_transaksi.'">
								<input type="hidden" name="nomor_jurnal" value="'.$nomor_jurnal.'">
								<input type="hidden" name="tot_debet" value="'.$tot_debet.'">
								<input type="hidden" name="keterangan_jurnal" value="'.$keterangan_jurnal.'">
								<button type="submit" class="btn btn-primary" onClick="return confirm(\'Apakah Anda Yakin?\')" name="selesai">Selesai</button>
							</form>
						</div>
					</div>
				</div>
			</div>';
		echo'
		</section>';
	}else{
		echo akses_salah();
	}
}
?>