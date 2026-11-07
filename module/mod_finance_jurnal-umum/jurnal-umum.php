<section class="content-header">
	<h1>Jurnal Umum<small></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Finance</a></li>
		<li><a href="#">Transaksi</a></li>
		<li class="active">Jurnal Umum</li>
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
		
		$jurnal_umum=mysqli_fetch_row(mysqli_query($con1,"SELECT max(nomor_jurnal) FROM jurnal_umum ORDER BY tanggal_selesai DESC"));
		$nomor_jurnal=$jurnal_umum[0]+1;
		$kode_transaksi="BU/".$nomor_jurnal;
?>
		<section class="content">
	
			<div class="box box-primary">
				<form method="post" action="" class="form-horizontal form-bordered" autocomplete="off">
					<div class="box-body">
						<div class="form-group">
							<label class="col-sm-3 control-label full-right">Nomor Bukti <span class="required">* </span></label>
							<div class="col-sm-3">
								<input type="hidden" name="kode_bukti" value="<?php echo $kode_transaksi;?>">
								<input type="text" class="form-control" disabled="disabled" value="<?php echo $kode_transaksi;?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label full-right">Tanggal <span class="required">* </span></label>
							<div class="col-sm-3">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control" id="tanggal" name="tanggal" value="<?php echo''.$setdate.''; ?>" readonly>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label full-right">Keterangan <span class="required">*</span></label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="keterangan_transaksi" value="<?php if(isset($_POST['keterangan_transaksi'])){ echo $_POST['keterangan_transaksi']; }?>" required>
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
							<label class="col-sm-3 control-label full-right">Posisi <span class="required">* </span></label>
							<div class="col-md-3">
								<select name="posisi" data-placeholder="Posisi..." class="form-control select2">
									<option value="debet">Debet</option>
									<option value="kredit">Kredit</option>
								</select>
								<div class="val-message"></div>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<button type="submit" class="btn btn-primary">Save</button>
					</div><!-- /.box-footer -->
				</form>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title">Jurnal Umum</h3>
						</div>
						<div class="box-body table-responsive">
							<table class="table table-bordered table-striped">
								<tr>
									<th>Kode Rekening</th>
									<th>Keterangan</th>
									<th>Debet</th>
									<th>Kredit</th>
									<th>Action</th>
								</tr>
								<?php
								$tot_debet=0;
								$tot_kredit=0;
								
								$query=mysqli_query($con1,"select * from tabel_transaksi where kode_transaksi='$kode_transaksi' and id_admin='$u[id_user]'");
								while($row=mysqli_fetch_assoc($query)){
									$debet=$row['debet'];
									$kredit=$row['kredit'];
									$id_transaksi=$row['id_transaksi'];
									$tot_debet=$tot_debet+$debet;
									$tot_kredit=$tot_kredit+$kredit;
									?>
									<tr>
										<td><?php echo $row['kode_rekening'];?></td>
										<td><?php echo $row['keterangan_transaksi'];?></td>
										<td align="right"><?php if($debet!=="0"){echo number_format($debet,2,'.',',');}; ?></td>
										<td align="right"><?php if($kredit!=="0"){echo number_format($kredit,2,'.',',');}; ?></td>
										<td align="center"><a href="?page=./transaksi/umum&mode=delete&id_transaksi='<?php echo $id_transaksi; ?>" onClick="return confirm('Apakah Anda yakin?')">Cancel</a></td>
									</tr>
								<?php
								}
								?>
								<tr>
									<td colspan="2" align="center"><b>TOTAL</b></td>
									<td align="right"><b><?php if(!empty($tot_debet)){ echo number_format($tot_debet,2,'.',','); } ?></b></td>
									<td align="right"><b><?php if(!empty($tot_kredit)){ echo number_format($tot_kredit,2,'.',','); }?></b></td>
									<td align="center">
									<?php
									//untuk menghitung balance
									if(!empty($tot_debet) || !empty($tot_kredit)){
										if($tot_debet==$tot_kredit){
											echo "<font color='#0033FF'>Balance</font>";
										}else{
											echo "<font color=red>Not Balance : ".abs($tot_debet-$tot_kredit)."</font>";
										}
									}
									?>
									</td>
								</tr>
							</table>
						</div>
						<div class="box-footer">
							<form action="?page=./transaksi/umum" method="post" name="form">
								<input type="hidden" name="tanggal_selesai" size="15" value="<?php if(empty($_POST['tanggal_transaksi'])){ echo $tanggal;}else{ echo $_POST['tanggal_transaksi']; }?>"/>
								<input type="hidden" name="kode_bukti" value="<?php echo $kode_transaksi;?>">
								<input type="hidden" name="nomor_jurnal" value="<?php echo $nomor_jurnal;?>">
								<button type="submit" class="btn btn-primary" onClick="return confirm('Apakah Anda Yakin?')" name="selesai">Selesai</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
		//untuk menyimpan transaksi
		if(isset($_POST['simpan'])){
		
			$kode_transaksi=$_POST['kode_bukti'];
			$tanggal_transaksi=$_POST['tanggal_transaksi'];
			$keterangan_transaksi=ucwords($_POST['keterangan_transaksi']);
			$kode_rekening=$_POST['kode_rekening'];
				
			$posisi=$_POST['posisi'];
			$jumlah_dk=ucwords($_POST['jumlah_dk']);
				
			if($posisi=='debet'){
				$dk='debet';
			}else{
				$dk='kredit';
			}
				
			$query=mysqli_query($con1,"insert into tabel_transaksi(kode_transaksi,kode_rekening,tanggal_transaksi, jenis_transaksi, keterangan_transaksi,".$dk.",id_admin) values ('$kode_transaksi','$kode_rekening','$tanggal_transaksi','Bukti Umum','$keterangan_transaksi','$jumlah_dk','$u[id_user]')");
			
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
				
				
			$query=mysqli_query($con1, "insert into jurnal_umum(nomor_jurnal,kode_transaksi,tanggal_selesai) values('$nomor_jurnal','$kode_transaksi','$tanggal_selesai')");
									
			if($query){
				?><script language="javascript">document.location.href="?module=jurnal-umum"</script><?php
			}else{
				echo mysqli_error();
			}
				
		}else{
			unset($_POST['selesai']);
		}
			
			
		//untuk mendecode url yang di enrypsi
		//$var=decode($_SERVER['REQUEST_URI']);
	
		if(isset($_GET['mode']) && isset($_GET['id_transaksi'])){
			
			//pecahkan nilai array
			$mode=$_GET['mode'];
			$id_transaksi=$_GET['id_transaksi'];
				
			if($mode=='delete'){
				$query=mysqli_query($con,"delete from tabel_transaksi where id_transaksi='$id_transaksi'");
			}
				
		}	
		?>
<?php 
	}else{
		echo akses_salah();
	}
}
?>