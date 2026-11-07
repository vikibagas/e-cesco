<section class="content-header">
	<h1>Perkiraan<small></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Finance</a></li>
		<li class="active">Perkiraan</li>
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
?>
		<section class="content">
			<div class="box box-primary">
				<form method="post" action="" class="form-horizontal form-bordered" autocomplete="off">
					<div class="box-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-sm-5 control-label full-right">Kode Rekening <span class="required">* </span></label>
									<div class="col-md-7">
										<input type="text" class="form-control" id="kode_rekening" name="kode_rekening"/>
									</div>
								</div>
							</div>
							<div class="col-md-8">
								<div class="form-group">
									<label class="col-md-3 control-label">Nama Rekening <span class="required">* </span></label>
									<div class="col-md-9">
										<input type="text" class="form-control" id="nama_rekening" name="nama_rekening"/>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-sm-5 control-label full-right">Normal Balance <span class="required">* </span></label>
									<div class="col-md-7">
										<select id="normal" name="normal" data-placeholder="Normal Balance..." class="form-control select2">
											<option value="debet">Debet</option>
											<option value="kredit">Kredit</option>
										</select>
										<div class="val-message"></div>
									</div>
								</div>
							</div>
							<div class="col-md-8">
								<div class="form-group">
									<label class="col-sm-3 control-label">Posisi <span class="required">* </span></label>
									<div class="col-md-4">
										<select id="normal" name="posisi" data-placeholder="Posisi..." class="form-control select2">
											<option value="neraca">Neraca</option>
											<option value="rugi-laba">Rugi Laba</option>
										</select>
										<div class="val-message"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-sm-5 control-label full-right">Saldo Awal Debet <span class="required">* </span></label>
									<div class="col-md-7">
										<input type="text" name="awal_debet" id="awal_debet" placeholder="0" class="form-control number">
									</div>
								</div>
							</div>
							<div class="col-md-8">
								<div class="form-group">
									<label class="col-sm-3 control-label">Saldo Awal Kredit <span class="required">* </span></label>
									<div class="col-md-4">
										<input type="text" name="awal_kredit" id="awal_kredit" placeholder="0" class="form-control number">
									</div>
								</div>
							</div>
						</div>
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="submit" class="btn btn-primary">Save</button>
					</div><!-- /.box-footer -->
				</form>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title">Perkiraan</h3>
						</div>
						<div class="box-body table-responsive">
							<table class="table table-bordered table-striped table-hover">
								<tr>
									<th>Kode Rekening</th>
									<th>Nama Rekening</th>
									<th>Awal Debet</th>
									<th>Awal Kredit</th>
									<th>Posisi</th>
									<th>Normal</th>
								</tr>
								<?php
								$total	= $mysqlf->query("select sum(awal_debet) as tot_awal_debet,sum(awal_kredit) as tot_awal_kredit from tabel_master order by kode_rekening asc");
								$t		= $total->fetch_assoc();
								$query = $mysqlf->query("select * from tabel_master order by kode_rekening asc");
								while($row=$query->fetch_assoc()){
									?>
									<tr>
									<td align="center"><?php echo $row['kode_rekening'];?></td><td><?php echo $row['nama_rekening'];?></td>
									<td align="right"><?php echo number_format($row['awal_debet'],2,'.',',');?></td>
									<td align="right"><?php echo number_format($row['awal_kredit'],2,'.',',');?></td>
									<td><?php echo $row['posisi'];?></td>
									<td><?php echo $row['normal'];?></td>
									</tr>
									<?php
								}
								?>
								<tr>
									<td colspan="2" align="center"><strong>JUMLAH</strong></td>
									<td align="right"><strong><?php echo number_format($t['tot_awal_debet'],2,'.',','); ?></strong></td>
									<td align="right"><strong><?php echo number_format($t['tot_awal_kredit'],2,'.',','); ?></strong></td>
									<td colspan="2" align="center"><center>
									<?php
									//untuk menghitung balance
									if(!empty($t['tot_awal_debet']) || !empty($t['tot_awal_kredit'])){
										if($t['tot_awal_debet']==$t['tot_awal_kredit']){
											echo "<strong><font color='#0033FF'>Balance</font></strong>";
										}else{
											echo "<strong><font color=red>Not Balance : ".number_format(abs($t['tot_awal_debet']-$t['tot_awal_kredit']),2,'.',',')."</font></strong>";
										}
									}
									?>
									</center></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</section>
		
		<?php
		if(isset($_POST['kode_rekening'])){
			$kode_rekening=$_POST['kode_rekening'];
			$nama_rekening=ucwords($_POST['nama_rekening']);
			$normal=$_POST['normal'];
			$posisi=$_POST['posisi'];
			//$awal_debet=$_POST['awal_debet'];
			//$awal_kredit=$_POST['awal_kredit'];
			$str_debet = $mysqlf->real_escape_string($_POST['awal_debet']);
			$awal_debet = preg_replace('#(\d),(\d)#','$1$2',$str_debet);
			$str_kredit = $mysqlf->real_escape_string($_POST['awal_kredit']);
			$awal_kredit = preg_replace('#(\d),(\d)#','$1$2',$str_kredit);
		
			$query	= $mysqlf->query("insert into tabel_master(kode_rekening,nama_rekening,tanggal_awal,awal_debet,awal_kredit,posisi,normal) values ('$kode_rekening','$nama_rekening','$tgl_sekarang','$awal_debet','$awal_kredit','$posisi','$normal')");
			
			if($query){
			?>
				<script language="javascript">alert("Data sudah tersimpan")</script>
				<script language="javascript">document.location.href="?module=perkiraan"</script>
			<?php
			}else{
				echo mysqli_error();
			}
			echo "masuk";
		}else{
			unset($_POST['kode_rekening']);
		}
		?>
	<?php 
	}else{
		echo akses_salah();
	}
}
?>