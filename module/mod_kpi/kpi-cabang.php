<section class="content-header">
	<h1>Branch/Agent<small></small></h1>
	<ol class="breadcrumb">		
		<li><a href="#">KPI</a></li>
		<li class="active">Branch/Agent</li>
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
	if($u['level'] == 'admin' || $u['level'] == 'inbound' || $u['level'] == 'network' || $u['level'] == 'admin_network'  || $u['id_user'] == 8 ){

		$aksi	= "module/mod_branch/branch_action.php";
		
		$act = !empty($_GET['act']) ? $_GET['act'] : '';
		
		switch($act){
			
			default:
			echo'
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-primary">
							
							<div class="box-body">
								<table class="table table-striped table-bordered table-hover" id="kpicabang">
									<thead>
										<tr>
											<th>Kode</th>
											<th>Branch/Agent</th>
											<th>Alamat</th>
											
											<th width="50px">Action</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</section>';
			
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

			$query = mysqli_query($con, "select * from cabang where id_cabang='$id'
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

              <h3 class="profile-username text-center"> '.strtoupper($r['nama_cabang']).'</h3>

              <p class="text-muted text-center">'.strtoupper($r['kode_cabang']).'</p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Tanggal Bergabung</b> <p class="pull-right"></p>
                </li>
                <li class="list-group-item">
                  <b>Nama PIC</b> <a class="pull-right">'.strtoupper($r['pic']).'</a>
                </li>
                <li class="list-group-item">
                  <b>No HP PIC</b> <a class="pull-right">-</a>
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
              <h3 class="box-title">TENTANG '.$r['nama_cabang'].'</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             

              <strong><i class="fa fa-map-marker margin-r-5"></i> Lokasi </strong>

              <p class="text-muted">'.strtoupper($r['alamat1']).' '.strtoupper($r['alamat2']).'</p>

              <hr>

             

              <strong><i class="fa fa-file-text-o margin-r-5"></i> Catatan</strong>

              <p>-</p>
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
              <h3 class="box-title">Score KPI '.$r['nama_cabang'].'</h3>

              

              <h3 class="box-title">Indikator Nilai</h3>

            
              <form method="GET" action="main.php">
              <input type="hidden" name="module" value="kpi-cabang">
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
                    <th width="50px">BOBOT</th>
                    <th>TARGET</th>

                   
                    <th width="120px">SCORE</th>
                    <th >DETAIL SCORE</th>
                    <th width="50px">SCORE BOBOT</th>
                    <th width="50px">DETAIL</th>

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
                            <td>30 </td>
                            <td>100 persen</td>
                            
                            <td>
                            ';
                                $q_pickup = mysqli_query($con, "SELECT 
                                  count(pickup_by_agent) as pickup_sukses,
                                  count(case when terlambat_pickup > 0 THEN 1 end) as pickup_terlambat ,
                                  SUM(CASE WHEN photo_pickup_sesuai IS NOT NULL THEN 1 ELSE 0 END) AS pickup_sesuai


                                  
                                  from pengiriman where pickup_by_agent=".$r['id_cabang']." and pickup_date BETWEEN '".$selectedValue."-01' AND '".$selectedValue."-31' and  status in (6,8)
                                    group by pickup_by_agent
                                    HAVING 
                                    pickup_sukses > 0
                                  ");
                                $r_pickup = mysqli_fetch_assoc($q_pickup);

                                if ($r_pickup && $r_pickup['pickup_sukses'] > 0) {
                                  $pengurangan_score_pickup = $r_pickup['pickup_terlambat']/$r_pickup['pickup_sukses']*100;
                                  $score_pickup = round(100-$pengurangan_score_pickup);
                                   $score_bobot_pickup = $score_pickup/100 * 30;
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
                              <div class="row"><a href="module/mod_kpi/report_terlambat_pickup_cabang.php?id_cabang='.$_GET['id'].'&tanggal='.$selectedValue.'&type=print" target="_blank" class="btn btn-xs btn-success"> <i class="fa fa-print"></i> </a>
                              <a href="config/phpspreadsheet/report_pickup_terlambat_cabang.php?id_cabang='.$_GET['id'].'&tanggal='.$selectedValue.'&type=excel" target="_blank" class="btn btn-xs btn-success">excel </a>
                              </div>
                            </td>
                      </tr>
                      <tr>
                            <td>Deliv sukses dan tepat waktu</td>
                            <td>30 </td>
                            <td>100 persen</td>
                            
                            <td>
                            ';
                                $q_deliv = mysqli_query($con, "SELECT 
                                  count(delivery_by_agent) as deliv_sukses,
                                  count(case when terlambat_delivery > 0 THEN 1 end) as deliv_terlambat  ,
                                  SUM(CASE WHEN photo_delivery_sesuai IS NOT NULL THEN 1 ELSE 0 END) AS deliv_sesuai

                                  
                                  from pengiriman where delivery_by_agent=".$r['id_cabang']." and delivery_date BETWEEN '".$selectedValue."-01' AND '".$selectedValue."-31' and status in (6,8)
                                    group by delivery_by_agent
                                  ");
                                $r_deliv = mysqli_fetch_assoc($q_deliv);

                                if ($r_deliv && $r_deliv['deliv_sukses'] > 0) {
                                  $pengurangan_score_deliv = $r_deliv['deliv_terlambat']/$r_deliv['deliv_sukses']*100;
                                  $score_deliv = round(100-$pengurangan_score_deliv);
                                   $score_bobot_deliv = $score_deliv/100 * 30;
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
                              <div class="row"><a href="module/mod_kpi/report_terlambat_deliv_cabang.php?id_cabang='.$_GET['id'].'&tanggal='.$selectedValue.'&type=print" target="_blank" class="btn btn-xs btn-success"> <i class="fa fa-print"></i> </a>
                              <a href="config/phpspreadsheet/report_delivery_terlambat_cabang.php?id_cabang='.$_GET['id'].'&tanggal='.$selectedValue.'&type=excel" target="_blank" class="btn btn-xs btn-success">excel </a>
                              </div>
                          
                            </td>
                      </tr>
                       <tr>
                            
                            <td>Mengirim ke vendor tepat waktu</td>
                            <td>20 </td>
                            <td>100 persen</td>
                            ';

                            $q_out = mysqli_query($con, "SELECT 
                                  count(id_manifest_detail) as outbound_sukses,
                                  count(case when a.diskon_outbound > 0 THEN 1 end) as outbound_terlambat 
                                  
                                
                                  
                                  from manifest_detail a 
                                  inner join manifest b on a.id_manifest = b.id_manifest 
                                  INNER JOIN pengiriman c on a.id_pengiriman = c.id_pengiriman

                                  where manifest_from=".$r['id_cabang']." and b.tanggal BETWEEN '".$selectedValue."-01' AND '".$selectedValue."-31' and c.status !=7
                                    group by b.manifest_from
                                  ");
                                $r_out = mysqli_fetch_assoc($q_out);

                               
                                  

                                if ($r_out && $r_out['outbound_sukses'] > 0) {
                                  $pengurangan_score_outbound = $r_out['outbound_terlambat']/$r_out['outbound_sukses']*100;
                                  $score_outbound = round(100-$pengurangan_score_outbound);
                                   $score_bobot_outbound = $score_outbound/100 * 20;
                                }else{
                                  $score_outbound = 0;
                                  $score_bobot_outbound = 0; 
                                }

                            echo '
                            
                            <td>';

                                echo '<small class="label label-success"></i> '.$score_outbound.'</small>';
                            echo '</td>
                            
                            <td>';

                              echo ($r_out['outbound_terlambat'] > 0 ? $r_out['outbound_terlambat'] : 0) .' Mengirim ke vendor terlambat dari '. ($r_out['outbound_sukses'] > 0 ? $r_out['outbound_sukses'] : 0).'  sukses  ';

                            echo '
                            </td>
                            <td>'.$score_bobot_outbound.'</td>

                            <td> 
                              <div class="row"><a href="module/mod_kpi/report_terlambat_outbound_cabang.php?id_cabang='.$_GET['id'].'&tanggal='.$selectedValue.'&type=print" target="_blank" class="btn btn-xs btn-success"> <i class="fa fa-print"></i> </a>
                              <a href="config/phpspreadsheet/report_outbound_terlambat_cabang.php?id_cabang='.$_GET['id'].'&tanggal='.$selectedValue.'&type=excel" target="_blank" class="btn btn-xs btn-success">excel </a>
                              </div>
                            </td>
                      </tr>
                       <tr>
                            
                            <td>Foto pickup sesuai</td>
                            <td>10 </td>
                            <td>100 persen</td>
                            
                            <td>';
                              //$r_pickup = mysqli_fetch_assoc($q_pickup);

                                if ($r_pickup['pickup_sesuai'] > 0) {
                                     $score_pickup_sesuai = round($r_pickup['pickup_sesuai'] / $r_pickup['pickup_sukses'] * 100);
                                    
                                    $score_bobot_pickup_sesuai = $score_pickup_sesuai / 100 * 10;
                                } else {
                                    $score_pickup_sesuai = 0;
                                    $score_bobot_pickup_sesuai = 0;
                                }


                                echo '<small class="label label-success"></i> '.$score_pickup_sesuai.'</small>';


                            echo '</td>
                            
                            <td>';
                               echo $r_pickup['pickup_sesuai'].' foto sesuai dari '. $r_pickup['pickup_sukses'].' pickup sukses  ';
                            echo '
                            </td>
                            <td>';
                             
                              echo $score_bobot_pickup_sesuai;

                            echo '</td>

                            <td> ';
                              /*<div class="row"><a href="module/mod_kpi/report_foto_pickup_cabang.php?id_cabang='.$_GET['id'].'&tanggal='.$selectedValue.'" target="_blank" class="btn btn-xs btn-success"> <i class="fa fa-print"></i> </a>
                              <a href="module/mod_kpi/report_foto_pickup_cabang.php?id_cabang='.$_GET['id'].'&tanggal='.$selectedValue.'" target="_blank" class="btn btn-xs btn-success">excel </a>
                              </div>*/
                              echo '
                            </td>
                      </tr>
                     
                       <tr>
                            
                            <td>Foto deliv sesuai</td>
                            <td>10 </td>
                            <td>100 persen</td>
                            
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

                            echo 
                            '</td>
                            <td> ';
                            /*
                              <div class="row"><a href="module/mod_kpi/report_foto_pickup_cabang.php?id_cabang='.$_GET['id'].'&tanggal='.$selectedValue.'" target="_blank" class="btn btn-xs btn-success"> <i class="fa fa-print"></i> </a>
                              <a href="module/mod_kpi/report_foto_delivery_cabang.php?id_cabang='.$_GET['id'].'&tanggal='.$selectedValue.'" target="_blank" class="btn btn-xs btn-success">excel </a>
                              </div>*/
                          
                            echo '
                            </td>
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
                      $total_score = $score_bobot_deliv+$score_bobot_pickup+$score_bobot_pickup_sesuai+$score_bobot_deliv_sesuai+$score_bobot_outbound;



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

   

			';
			
			break;
			
		}
	}else{
		echo akses_salah();
	}
}
?>