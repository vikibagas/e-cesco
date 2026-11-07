<section class="content-header">
  <h1>Vendor<small></small></h1>
  <ol class="breadcrumb">   
    <li><a href="#">KPI</a></li>
    <li class="active">Vendor</li>
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

    $aksi = "module/mod_kpi/kpi_action.php";
    
    $act = !empty($_GET['act']) ? $_GET['act'] : '';
    
    switch($act){
      
      default:
      echo'
      <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box box-primary">
              
              <div class="box-body">
                <table class="table table-striped table-bordered table-hover" id="kpivendor">
                  <thead>
                    <tr>
                      <th>Kode</th>
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

      $query = mysqli_query($con, "select * from manifest_vendor where id='$id'
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

              <h3 class="profile-username text-center"> '.strtoupper($r['name']).'</h3>

             

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Tanggal Bergabung</b> <p class="pull-right"></p>
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
              <h3 class="box-title">TENTANG </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             

              <strong><i class="fa fa-map-marker margin-r-5"></i> Lokasi </strong>

              

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
              <h3 class="box-title">Score KPI '.$r['name'].'</h3>

              

              <h3 class="box-title">Indikator Nilai</h3>

            
              <form method="GET" action="main.php">
              <input type="hidden" name="module" value="kpi-vendor">
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
                            <td>Sampai di agen tujuan tepat waktu</td>
                            <td>100 </td>
                            <td>100 persen</td>
                            
                            <td>
                            ';
                                $q_kiriman = mysqli_query($con,"
                                  SELECT 
                                    COUNT(DISTINCT b.id_pengiriman) AS kiriman_sukses,
                                    SUM(CASE WHEN a.diskon_inbound > 0 THEN 1 ELSE 0 END) AS kiriman_terlambat
                                  FROM manifest_detail a
                                  INNER JOIN pengiriman b ON a.id_pengiriman=b.id_pengiriman
                                  INNER JOIN manifest c ON a.id_manifest=c.id_manifest
                                  WHERE c.manifest_via='".$r['name']."'
                                  AND c.tanggal BETWEEN '".$selectedValue."-01' AND '".$selectedValue."-31'
                                  AND b.status IN (6,8)
                                ");

                              
                                $r_kiriman = mysqli_fetch_assoc($q_kiriman);

                               if ($r_kiriman && $r_kiriman['kiriman_sukses']>0){
                                  $pengurangan = $r_kiriman['kiriman_terlambat']/$r_kiriman['kiriman_sukses']*100;
                                  $score = round(100-$pengurangan);
                                  $score_bobot = $score;
                                }else{
                                  $score = 0;
                                  $score_bobot = 0;
                                }

                                echo '<small class="label label-success"></i> '.$score.'</small>';

                            echo '
                            </td>
                            
                            <td>';

                              echo ($r_kiriman['kiriman_terlambat'] > 0 ? $r_kiriman['kiriman_terlambat'] : 0).' kiriman terlambat ke cabang tujuan '. ($r_kiriman['kiriman_sukses'] > 0 ? $r_kiriman['kiriman_sukses'] : 0 ).' kiriman sukses  ';


                            echo 
                            '</td>
                            <td>';
                             
                              echo $score_bobot;

                            echo '</td>
                            <td> 
                              <div class="row">
                              <a href="module/mod_kpi/report_terlambat_vendor.php?id_vendor='.$_GET['id'].'&tanggal='.$selectedValue.'" 
                           target="_blank" 
                           class="btn btn-xs btn-primary">
                          <i class="fa fa-print"></i></a>
                          
                          <a href="module/mod_kpi/report_terlambat_vendor.php?id_vendor='.$_GET['id'].'&tanggal='.$selectedValue.'&type=excel" 
                           target="_blank" 
                           class="btn btn-xs btn-success">
                         excel</a>
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
                    



                        echo $score_bobot. ' dari 100
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