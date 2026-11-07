<section class="content-header">
	<h1>KPI<small></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> KPI</a></li>
		<li class="active"><a href="#">Karyawan</a></li>
		
	</ol>
</section>
<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../../application/login.php');
}
else{
	//cek hak akses user
	if($u['level']=='admin'){
		
		$aksi	= "module/mod_kpi/kpi_action.php";
		
		$act = !empty($_GET['act']) ? $_GET['act'] : '';
		
		switch($act){
			
			default:
			echo'
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						<!-- Custom Tabs -->
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab_1" data-toggle="tab">Karyawan</a></li>
								
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="tab_1">
									<div class="box-header">
										<div class="row">
											<div class="col-md-12">
												<div class="btn-group">
													
												</div>
											</div>										
										</div>
									</div>
									<table id="kpikaryawan" class="table table-bordered table-striped table-hover">
										<thead>
											<tr>
												<th>Name</th>
												
												
												<th>Jabatan</th>
												
												<th>Email</th>
												
												<th width="50px">Action</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
								
							</div><!-- /.tab-content -->
						</div><!-- nav-tabs-custom -->
					</div>
				</div>
			</section>
			
			<div class="modal fade modal-danger" id="confirm-blocked" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							Confirmation ...
						</div>
						<div class="modal-body">
							Blocked this user ...?
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
							<a class="btn btn-warning btn-ok">Ok</a>
						</div>
					</div>
				</div>
			</div>';
			
			break;
			
			case "detail":
			
			$key = "asdf$1011";
			$encrypt = "$_GET[id]";
			$id = base64_decrypt($encrypt,$key);

			 $startYear = 2025;
                      $currentDate = new DateTime();
                      $selectedDate = clone $currentDate;
                      $selectedDate->modify('-1 month');
											if (isset($_GET['tahun_bulan'])) {
											  $selectedValue = $_GET['tahun_bulan'];
											} else {
											  $selectedYear = $selectedDate->format('Y');
											  $selectedMonth = $selectedDate->format('m');
											  $selectedValue = "{$selectedYear}-{$selectedMonth}";
											}


			$query = mysqli_query($con, "select a.*, b.* from user a 
					left join jabatan b on a.id_jabatan = b.id where a.id_user='$id'
				");
			$r = mysqli_fetch_assoc($query);
									
			echo'
				<section class="content">

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRFYaUX-F0YIHXA-aRLs5PkowqXL-SwTWZI2g&s" alt="User profile picture">

              <h3 class="profile-username text-center"> '.strtoupper($r['namalengkap']).'</h3>

              <p class="text-muted text-center">'.strtoupper($r['jabatan']).'</p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Tanggal Bergabung</b> <p class="pull-right">'.$r['tanggal_bergabung'].'</p>
                </li>
                <li class="list-group-item">
                  <b>Tanggal Lahir</b> <a class="pull-right">'.$r['tanggal_lahir'].'</a>
                </li>
                
              </ul>

              <a href="#" class="btn btn-primary btn-block"><b>Edit <i class="fa fa-pencil "></i></b></a>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Tentang '.$r['namalengkap'].'</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-book margin-r-5"></i> Pendidikan</strong>

              <p class="text-muted">
                '.$r['pendidikan_terakhir'].'
              </p>

              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> Tempat Tinggal</strong>

              <p class="text-muted">'.$r['tempat_tinggal'].'</p>

              <hr>

              <strong><i class="fa fa-pencil margin-r-5"></i> Skills</strong>

              <p>
                
              </p>

              <hr>

              <strong><i class="fa fa-file-text-o margin-r-5"></i> Catatan</strong>

              <p>catatan saya</p>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <!-- mulai disini samping nya -->

        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-body">
              <h3 class="box-title">Score KPI '.$r['namalengkap'].'</h3>

              <div class="row">
                <div class="col-lg-3 col-xs-6">
                  <div class="small-box bg-aqua">
                    <div class="inner">
                      <h3>
                      	';


                      				$query_while = mysqli_query($con, "SELECT b.score,a.target,a.bobot FROM kpi a
							    	left join kpi_score b on a.id = b.id_kpi AND b.periode BETWEEN '".$selectedValue."-01' AND '".$selectedValue."-31'
							    	where id_jabatan = ".$r['id_jabatan']." ");

                  
							    	$total_score_atas = 0;

							    	while ($row_atas = mysqli_fetch_assoc($query_while)) {
								    	$score = ($row_atas['score'] > 0 ? $row_atas['score'] : 0);
								    	$score_bobot = $score/$row_atas['target'] * $row_atas['bobot'];
								     
								            $total_score_atas = $total_score_atas+$score_bobot;
								    }
							    		
							    	

                      	echo $total_score_atas. ' dari 100
                      </h3>
                      <p>SCORE KPI PERIODE '.$selectedValue.'</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-pie-graph"></i>
                    </div>
                  </div>
                </div>
              </div>

              <h3 class="box-title">Indikator Nilai</h3>

            
              <form method="GET" action="main.php">
              <input type="hidden" name="module" value="kpi-karyawan">
						  <input type="hidden" name="act" value="detail">
						  <input type="hidden" name="id" value="'.$_GET['id'].'">

                <div class="row">
                  <div class="form-group col-lg-9 col-md-9 col-xs-9">
                    <select name="tahun_bulan" class="form-control">
                      ';
                     
                      $currentYear = $currentDate->format('Y');
                      $currentMonth = (int) $currentDate->format('n'); // 1–12

                      $bulan = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                        4 => 'April', 5 => 'Mei', 6 => 'Juni',
                        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                        10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                      ];

                      for ($tahun = $startYear; $tahun <= $currentYear; $tahun++) {
                        $maxBulan = ($tahun == $currentYear) ? $currentMonth : 12;
                        for ($i = 1; $i <= $maxBulan; $i++) {
                          $bulanValue = str_pad($i, 2, '0', STR_PAD_LEFT);
                          $value = "{$tahun}-{$bulanValue}";
                          $selected = ($value === $selectedValue) ? "selected" : "";
                          echo "<option value='{$value}' {$selected}>{$value} ({$bulan[$i]})</option>";
                        }
                      }
                      echo '
                    </select>
                  </div>
                  <div class="form-group col-lg-3 col-md-3 col-xs-3">
                    <input type="submit" class="col-md-3 btn btn-primary btn-sm" />
                  </div>
                </div>
              </form>
              

              

              <table id="kpi_indicator" class="table table-bordered table-striped table-hover">
							  <thead>
							    <tr>
							      <th>KPI</th>
							      <th width="50px">Bobot</th>
							      <th>Target</th>

							      <th width="50px">Tipe</th>
							      <th width="120px">Score</th>
							      <th >Detail Score</th>
							      <th width="50px">Score Bobot</th>

							    </tr>
							  </thead>
							  <tbody>
							    ';
							    $query = mysqli_query($con, "SELECT *,a.id as id_kpi, b.id as id_score FROM kpi a
							    	left join kpi_score b on a.id = b.id_kpi AND b.periode BETWEEN '".$selectedValue."-01' AND '".$selectedValue."-31'
							    	where id_jabatan = ".$r['id_jabatan']."
							    		
							    	");

							    	$total_score = 0;
							    while ($row = mysqli_fetch_assoc($query)) {
							    	$score = ($row['score'] > 0 ? $row['score'] : 0);
							    	$score_bobot = $score/$row['target'] * $row['bobot'];
							      echo '
							      	<tr>
							              <td>' . htmlspecialchars($row['kpi']) . '</td>
							              <td>' . htmlspecialchars($row['bobot']) . ' %</td>
							              <td>' . htmlspecialchars($row['target'].' '.$row['detail_target']) . '</td>
							              <td>' . htmlspecialchars($row['type_penilaian']) . '</td>
							              <td>'.
							              ($row['score'] != '' ? 
							              	
							              	'<center>
							              	<small class="label label-success"></i> '.$row['score'].'</small>
							              	<button class="btn btn-xs btn-warning edit_nilai_kpi" data-toggle="modal" data-target="#modal_edit_nilai" data-id="'.$row['id_score'].'"  data-targetscore="'.$row['target'].'" data-score="'.$row['score'].'" data-detail="'.$row['detail_score'].'" ><i class="fa fa-pencil"> </i> </button> </center>
							              	' 
							              		
							              		: 
							              		
							              		'<button class="btn btn-xs btn-warning input_nilai_kpi" data-toggle="modal" data-target="#modal_input_nilai" data-id="'.$row['id_kpi'].'" data-targetscore="'.$row['target'].'"  >input</button>'
							              )
							               .'</td>
							              <td>'.$row['detail_score'].'</td>
							              <td>'.$score_bobot.'</td>

							            </tr>';
							            $total_score = $total_score+$score_bobot;
							    }
							    echo '
							  </tbody>
							</table>		
              
            </div>
          </div>
        </div><!-- /col-md-9 -->
      </div>
      <!-- /.row -->

    </section>

    <div id="modal_input_nilai" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Input nilai</h4>
						</div>
						<div class="modal-content">
							
							<div class="modal-body">

								<form action="'.$aksi.'?module=kpi&act=input-score" method="POST">

									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Score</label>
										<div class="col-md-8">
											<input type="number" max="" name="score" id="score_input_modal" min="0" value="" class="form-control" >
										</div>
									</div>
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Detail Score</label>
										<div class="col-md-8">
											<input type="text" name="detail_score" value="" class="form-control" >
										</div>
									</div>
									
									<input type="hidden" name="periode" id="periode_kolom" class="form-control" value="'.$selectedValue.'-01" />
									<input type="hidden" name="id_kpi" id="form_input_idkpi" class="form-control" value="" />
									<input type="hidden" name="id_user" id="" class="form-control" value="'.$r['id_user'].'" />
									<div class="modal-footer">
										<button class="btn btn-success" type="submit">insert</button>
										<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>

							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="modal_edit_nilai" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Edit nilai</h4>
						</div>
						<div class="modal-content">
							
							<div class="modal-body">

								<form action="'.$aksi.'?module=kpi&act=update-score" method="POST">

									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Score</label>
										<div class="col-md-8">
											<input type="number" max="" name="score" id="score_edit_modal" min="0" value="" class="form-control" >
										</div>
									</div>
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Detail Score</label>
										<div class="col-md-8">
											<input type="text" name="detail_score" id="detail_score_edit_modal" value="" class="form-control" >
										</div>
									</div>

									
									<input type="hidden" name="periode" id="periode_kolom" class="form-control" value="'.$selectedValue.'-01" />
									<input type="hidden" name="id_score" id="idscore_edit_modal" class="form-control" value="" />
									<input type="hidden" name="id_user" id="" class="form-control" value="'.$r['id_user'].'" />
									<div class="modal-footer">
										<button class="btn btn-success" type="submit">update</button>
										<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>

							</div>
						</div>
					</div>
				</div>
			</div>

			';
			
			break;
			
			
			

			
			break;
		}
	}else{
		echo akses_salah();
	}
}
?>