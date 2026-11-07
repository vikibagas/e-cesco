<section class="content-header">
	<h1>KPI<small></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> KPI</a></li>
		<li class="active"><a href="#">Distribution team</a></li>
		
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
	if($u['level']=='admin' || $u['id_user'] == 289){
		
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
									<table id="kpidistribution" class="table table-bordered table-striped table-hover">
										<thead>
											<tr>
												<th>Name</th>
												
												
												<th>Jabatan</th>
												
												<th>Username</th>
												
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
              <h3 class="box-title">Score KPI '.strtoupper($r['namalengkap']).'</h3>

              

              <h3 class="box-title">Indikator Nilai</h3>

            
              <form method="GET" action="main.php">
              <input type="hidden" name="module" value="kpi-distribution-team">
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

                   
                    <th width="120px">Score</th>
                    <th >Detail Score</th>
                    <th width="50px">Score Bobot</th>
                    <th width="50px">Detail</th>

                  </tr>
                </thead>
                <tbody>
                  ';
                 

                    //$total_score = 0;
                 
                    //$score = ($row['score'] > 0 ? $row['score'] : 0);
                    //$score_bobot = $score/$row['target'] * $row['bobot'];
                    echo '
                      <tr>
                            <td>Pickup sukses dan tepat waktu</td>
                            <td>40 </td>
                            <td>100 PERSEN</td>
                            
                            <td>
                            ';
                                $q_pickup = mysqli_query($con, "SELECT 
                                  count(pickup_by_agent) as pickup_sukses,
                                  count(case when terlambat_pickup > 0 THEN 1 end) as pickup_terlambat ,
                                  SUM(CASE WHEN photo_pickup_sesuai IS NOT NULL THEN 1 ELSE 0 END) AS pickup_sesuai


                                  
                                  from pengiriman where pickup_by=".$r['id_user']." and pickup_date BETWEEN '".$selectedValue."-01' AND '".$selectedValue."-31' 
                                    group by pickup_by
                                    HAVING 
                                    pickup_sukses > 0
                                  ");
                                $r_pickup = mysqli_fetch_assoc($q_pickup);

                                if ($r_pickup && $r_pickup['pickup_sukses'] > 0) {
                                  $pengurangan_score_pickup = $r_pickup['pickup_terlambat']/$r_pickup['pickup_sukses']*100;
                                  $score_pickup = round(100-$pengurangan_score_pickup);
                                   $score_bobot_pickup = $score_pickup/100 * 40;
                                }else{
                                  $score_pickup = 0;
                                  $score_bobot_pickup = 0; 
                                }
                                
                                

                                echo '<small class="label label-success"></i> '.$score_pickup.'</small>';

                            echo '
                            </td>
                            
                            <td>';

                              

                             

                             

                              echo ($r_pickup['pickup_terlambat'] > 0 ? $r_pickup['pickup_terlambat'] : 0).' pickup terlambat dari '. ($r_pickup['pickup_sukses'] > 0 ? $r_pickup['pickup_sukses'] : 0 ).' pickup sukses  ';


                            echo 
                            '</td>
                            <td>';
                             
                              echo $score_bobot_pickup;

                            echo '</td>
                             <td> 
                            <div class="row"><a href="module/mod_kpi/report_terlambat_pickup_distribution.php?id_team='.$_GET['id'].'&tanggal='.$selectedValue.'" target="_blank" class="btn btn-xs btn-success"> <i class="fa fa-print"></i> </a>
                            <a href="config/phpspreadsheet/report_pickup_terlambat_distribution.php?id_team='.$_GET['id'].'&tanggal='.$selectedValue.'&type=excel" target="_blank" class="btn btn-xs btn-success">excel </a>
                            </div>
                      </tr>
                      <tr>
                            <td>Deliv sukses dan tepat waktu</td>
                            <td>40 </td>
                            <td>100 PERSEN</td>
                            
                            <td>
                            ';
                                $q_deliv = mysqli_query($con, "SELECT count(delivery_by_agent) as deliv_sukses, count(case when terlambat_delivery > 0 THEN 1 end) as deliv_terlambat , SUM(CASE WHEN photo_delivery_sesuai IS NOT NULL THEN 1 ELSE 0 END) AS deliv_sesuai 

                                  from pengiriman where delivery_by=".$r['id_user']." and delivery_date BETWEEN '".$selectedValue."-01' AND '".$selectedValue."-31' 

                                  group by delivery_by HAVING deliv_sukses > 0
                                  ");
                                $r_deliv = mysqli_fetch_assoc($q_deliv);

                                if ($r_deliv && $r_deliv['deliv_sukses'] > 0) {
                                  $pengurangan_score_deliv = $r_deliv['deliv_terlambat']/$r_deliv['deliv_sukses']*100;
                                  $score_deliv = round(100-$pengurangan_score_deliv);
                                   $score_bobot_deliv = $score_deliv/100 * 40;
                                }else{
                                  $score_deliv = 0;
                                  $score_bobot_deliv = 0; 
                                }
                                
                                echo '<small class="label label-success"></i> '.$score_deliv.'</small>';

                               

                               
                            echo '
                            </td>
                            
                            <td>';

                              

                             

                              

                              echo ($r_deliv['deliv_terlambat'] > 0 ? $r_deliv['deliv_terlambat'] : 0) .' delivery terlambat dari '. ($r_deliv['deliv_sukses'] > 0 ? $r_deliv['deliv_sukses'] : 0).' delivery sukses  ';


                            echo 
                            '</td>
                            <td>';
                             
                              echo $score_bobot_deliv;

                            echo '</td>
                             <td> 
                            <div class="row"><a href="module/mod_kpi/report_terlambat_delivery_distribution.php?id_team='.$_GET['id'].'&tanggal='.$selectedValue.'" target="_blank" class="btn btn-xs btn-success"> <i class="fa fa-print"></i> </a>
                            <a href="config/phpspreadsheet/report_delivery_terlambat_distribution.php?id_team='.$_GET['id'].'&tanggal='.$selectedValue.'&type=excel" target="_blank" class="btn btn-xs btn-success">excel </a>
                            </div>
                      </tr>
                       
                            
                            
                       <tr>
                            
                            <td>Foto deliv sesuai</td>
                            <td>10 </td>
                            <td>100 PERSEN</td>
                            
                            <td>';
                              

                                if ($r_deliv['deliv_sesuai'] > 0) {
                                    $score_deliv_sesuai = round($r_deliv['deliv_sesuai'] / $r_deliv['deliv_sukses'] * 100);
                                    
                                    $score_bobot_deliv_sesuai = $score_deliv_sesuai / 100 * 10;

                                    //$score_deliv = round(100-$pengurangan_score_deliv);
                                    //$score_bobot_deliv = $score_deliv/100 * 40;
                                } else {
                                    $score_deliv_sesuai = 0;
                                    $score_bobot_deliv_sesuai = 0;
                                }


                                echo  '<small class="label label-success"></i> '.$score_deliv_sesuai.'</small>';


                            echo '</td>
                            
                            <td>';
                               echo ($r_deliv['deliv_sesuai'] > 0 ? $r_deliv['deliv_sesuai'] : 0).' foto sesuai dari '. ($r_deliv['deliv_sukses'] > 0 ? $r_deliv['deliv_sukses'] : 0).' delivery sukses  ';
                            echo '
                            </td>
                            <td>';
                             
                              echo $score_bobot_deliv_sesuai;

                            echo '</td>
                             <td> 
                            
                            </div>
                      </tr>


                      <tr>
                        <td>Login Aplikasi</td>
                        <td>10 </td>
                        <td>100 PERSEN</td>
                        <td>';
                        
                            $bulan_tahun = $selectedValue; // format: YYYY-MM
                            $tahun = date('Y', strtotime($bulan_tahun . '-01'));
                            $bulan = date('m', strtotime($bulan_tahun . '-01'));

                            // hitung jumlah hari kerja (Senin–Sabtu)
                            $total_hari_kerja = 0;
                            $tanggal_awal = new DateTime("$tahun-$bulan-01");
                            $tanggal_akhir = new DateTime("$tahun-$bulan-" . $tanggal_awal->format('t'));

                            while ($tanggal_awal <= $tanggal_akhir) {
                                $hari = $tanggal_awal->format('N'); // 1=Senin, 7=Minggu
                                if ($hari < 7) { // exclude Minggu
                                    $total_hari_kerja++;
                                }
                                $tanggal_awal->modify('+1 day');
                            }

                            // ambil jumlah hari login unik
                            $q_login = mysqli_query($con, "
                                SELECT COUNT(DISTINCT DATE(timestamp)) AS total_login
                                FROM location_history
                                WHERE user_id = ".$r['id_user']."
                                AND DATE(timestamp) BETWEEN '".$selectedValue."-01' AND '".$selectedValue."-31'
                            ");
                            $r_login = mysqli_fetch_assoc($q_login);
                            $total_login = $r_login ? $r_login['total_login'] : 0;

                            // hitung score login
                            if ($total_hari_kerja > 0) {
                                $score_login = round(($total_login / $total_hari_kerja) * 100);
                                if ($score_login > 100) $score_login = 100;
                                $score_bobot_login = $score_login / 100 * 10;
                            } else {
                                $score_login = 0;
                                $score_bobot_login = 0;
                            }

                            echo  '<small class="label label-success"></i> '.$score_login.'</small>';

                        echo '</td>
                        <td>';
                            echo ($total_login > 0 ? $total_login : 0).' hari login dari '.($total_hari_kerja > 0 ? $total_hari_kerja : 0).' hari kerja ';
                        echo '
                        </td>
                        <td>';
                             echo $score_bobot_login;

                            echo '</td>
                             <td> 
                            <a href="config/phpspreadsheet/report_login_distribution.php?id_team='.$_GET['id'].'&tanggal='.$selectedValue.'&type=excel" target="_blank" class="btn btn-xs btn-success">excel </a>
                            </div>
                    </tr>
                      ';
                  
                  
                  echo '
                </tbody>
              </table>  
              <br>

              <div class="row">
                <div class="col-lg-4 col-xs-6">
                  <div class="small-box bg-aqua">
                    <div class="inner">
                      <h3>
                       ';
                      $total_score = $score_bobot_deliv+$score_bobot_pickup+$score_bobot_deliv_sesuai;



                        echo $total_score. ' dari 100
                      </h3>
                      <p>SCORE KPI PERIODE '.$selectedValue.'</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-pie-graph"></i>
                    </div>
                  </div>
                </div>
              </div>  
              
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