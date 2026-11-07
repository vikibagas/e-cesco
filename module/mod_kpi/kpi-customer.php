<section class="content-header">
	<h1>KPI Customer<small></small></h1>
	<ol class="breadcrumb">		
		<li><a href="#">KPI</a></li>
		<li class="active">Customer</li>
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
	if($u['level']=='admin' || $u['level']=='domex' || $u['level']=='finance'){

		$aksi	= "module/mod_branch/branch_action.php";
		
		$act = !empty($_GET['act']) ? $_GET['act'] : '';
		
		switch($act){
			
			default:
			echo'
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-primary">
							<div class="box-header">
								<div class="row">
									<div class="col-md-6">
										<div class="btn-group">
											<button class="btn btn-sm btn-primary" onclick="window.location.href = \'?module=branch&act=create\';">Add New <i class="fa fa-plus"></i></button>
										</div>
									</div>
									<div class="col-md-6">
										<div class="btn-group pull-right">
											<button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i></button>
											<ul class="dropdown-menu pull-right">
												<li><a href="javascript:;">Print </a></li>
												<li><a href="javascript:;">Save as PDF </a></li>
												<li><a href="javascript:;">Export to Excel </a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="box-body">
								<table class="table table-striped table-bordered table-hover" id="kpicustomer">
									<thead>
										<tr>											
											<th>Kustomer</th>
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

			$query = mysqli_query($con, "select * from kustomer where id_kustomer='$id'
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

              <h3 class="profile-username text-center"> '.strtoupper($r['nama_kustomer']).'</h3>

              

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Tanggal Bergabung</b> <p class="pull-right"></p>
                </li>
                <li class="list-group-item">
                  <b>Nama PIC</b> <a class="pull-right">'.strtoupper($r['pic_utama']).'</a>
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
              <h3 class="box-title">TENTANG '.$r['nama_kustomer'].'</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             

              <strong><i class="fa fa-map-marker margin-r-5"></i> Lokasi </strong>

              <p class="text-muted">'.strtoupper($r['alamat']).'</p>

              <hr>

             

              <strong><i class="fa fa-file-text-o margin-r-5"></i> Catatan</strong>

              <p></p>
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
              <h3 class="box-title">Score KPI '.$r['nama_kustomer'].'</h3>

              

              <h3 class="box-title">Indikator Nilai</h3>

            
              <form method="GET" action="main.php">
              <input type="hidden" name="module" value="kpi-customer">
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
                            <td>Kiriman di pickup sukses dan tepat waktu</td>
                            <td>50 </td>
                            <td>100 PERSEN</td>
                            
                            <td>
                            ';
                                $q_pickup = mysqli_query($con, "SELECT 
                                  count(pickup_date) as pickup_sukses,
                                  count(case when terlambat_pickup > 0 THEN 1 end) as pickup_terlambat ,
                                  SUM(CASE WHEN photo_pickup_sesuai IS NOT NULL THEN 1 ELSE 0 END) AS pickup_sesuai


                                  
                                  from pengiriman where id_kustomer=".$r['id_kustomer']." and tanggal BETWEEN '".$selectedValue."-01' AND '".$selectedValue."-31' and status in (6,8)
                                    group by id_kustomer
                                    HAVING 
                                    pickup_sukses > 0
                                  ");
                                $r_pickup = mysqli_fetch_assoc($q_pickup);
                                
                                if ($r_pickup && $r_pickup['pickup_sukses'] > 0) {
                                  $pengurangan_score_pickup = $r_pickup['pickup_terlambat']/$r_pickup['pickup_sukses']*100;
                                  $score_pickup = round(100-$pengurangan_score_pickup);
                                   $score_bobot_pickup = $score_pickup/100 * 50;
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

                            echo '
                            </td>
                          <td> 
                            <div class="row"><a href="module/mod_kpi/report_terlambat_pickup_customer.php?id_kustomer='.$_GET['id'].'&tanggal='.$selectedValue.'" target="_blank" class="btn btn-xs btn-success"> <i class="fa fa-print"></i> </a>
                            <a href="config/phpspreadsheet/report_pickup_terlambat_customer.php?id_kustomer='.$_GET['id'].'&tanggal='.$selectedValue.'&type=excel" target="_blank" class="btn btn-xs btn-success">excel </a>
                            </div>
                          
                          </td>

                      </tr>

                     

                      <tr>
                            <td>Kiriman Delivery sukses dan tidak lebih dari SLA</td>
                            <td>100 </td>
                            <td>100 PERSEN</td>
                            
                            <td>
                            ';
                               
                               $result = mysqli_query($con,"select  b.lead_time, delivery_date, DATE_FORMAT(pickup_date, '%Y-%m-%d') as tanggal_pickup,created_date,DATEDIFF(delivery_date, pickup_date) AS hari_ditempuh

                                 from pengiriman a 
                                 left join rate_customer b on a.id_kustomer = b.id_kustomer and a.id_kota_asal = b.id_lokasi_asal and a.id_kota_tujuan=b.id_lokasi_tujuan and a.service = b.id_pelayanan



                                 where a.id_kustomer='".$r['id_kustomer']."' and tanggal between '".$selectedValue."-01' and '".$selectedValue."-31'
                                 
                                 and status in (6,8)  group by id_pengiriman

                                 ");

                                $liburNasional = [];
                                $qLibur = mysqli_query($con, "SELECT tanggal FROM libur_nasional");

                                while ($row = mysqli_fetch_assoc($qLibur)) {
                                    $liburNasional[] = $row['tanggal'];
                                }

                                $no_terlambat = 0;
                                $no_kiriman = 0;
                                while($t=mysqli_fetch_assoc($result)){
                                    $workdays = 0;

                                    if (!empty($t['tanggal_pickup']) && !empty($t['delivery_date'])) {
                                        $pickup   = new DateTime($t['tanggal_pickup']);
                                        $delivery = new DateTime($t['delivery_date']);
                                        $pickup->modify('+1 day'); // kalau tidak mau hitung pickup-nya

                                        $interval  = new DateInterval('P1D');
                                        $delivery->modify('+1 day'); 
                                        $daterange = new DatePeriod($pickup, $interval, $delivery);

                                        foreach ($daterange as $date) {
                                            $tgl     = $date->format('Y-m-d');
                                            $hariKe  = $date->format('N'); // 1 = Senin, ..., 7 = Minggu

                                            if ($hariKe < 6 && !in_array($tgl, $liburNasional)) {
                                                $workdays++;
                                            }
                                        }
                                    }
                                    $no_kiriman++;

                                    if($workdays > $t['lead_time']){
                                      $no_terlambat++;
                                    }
                                }







                                if ($no_kiriman > 0) {
                                  $pengurangan_score_deliv = $no_terlambat/$no_kiriman*100;
                                  $score_deliv = round(100-$pengurangan_score_deliv);
                                   $score_bobot_deliv = $score_deliv/100 * 50;
                                }else{
                                  $score_deliv = 0;
                                  $score_bobot_deliv = 0; 
                                }
                            
                                echo '<small class="label label-success"></i> '.$score_deliv.'</small>';

                            echo '
                            </td>
                            
                            <td>';

                              

                             

                              

                              echo  $no_terlambat.' kiriman lebih dari SLA, dari '.$no_kiriman.' kiriman terdelivery dengan sukses  ';


                            echo 
                            '</td>
                            <td>';
                              
                              echo $score_bobot_deliv;
                            

                            echo '</td>
                            <td> 
                              <div class="row">
                                <a href="module/mod_kpi/report/report_shipment_customer.php?id_kustomer='.$_GET['id'].'&tanggal='.$selectedValue.'" target="_blank" class="btn btn-xs btn-success"><i class="fa fa-print"></i> </a>
                               
                              </div>
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
                      $total_score = $score_bobot_deliv+$score_bobot_pickup;



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