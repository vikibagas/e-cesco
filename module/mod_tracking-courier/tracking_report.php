<?php
// ✅ PASTIKAN FILE INI ADA
include_once "config/encrypt.php"; // berisi base64_encrypt & base64_decrypt
?>
<section class="content-header">
  <h1>Tracking courier</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-home"></i> Master data</a></li>
  </ol>
</section>

<div class="box box-primary">
  <div class="box-header with-border">
    <div class="row">
      <div class="col-md-6">&nbsp;</div>
      <div class="col-md-6">
        <div class="btn-group pull-right">
          <button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">
            Tools <i class="fa fa-angle-down"></i>
          </button>
          <ul class="dropdown-menu pull-right">
            <li>
              <a href="javascript:;" onclick="downloadReport()">
                <i class="fa fa-download"></i> Download Report
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <h3 class="box-title">Tracking Courier</h3>
  </div><!-- /.box-header -->

  <div class="box-body">
    <div class="row">
      <!-- KURIR -->
      <div class="col-md-6">
        <div class="form-group">
          <label class="col-sm-3 control-label full-right">Kurir</label>
          <div class="col-sm-8">
            <select name="id_user" id="id_user" onchange="trackingcourier()" class="form-control select2">
              <option value="">-- SELECT --</option>
              <?php
                $key = "asdf$1011";

                $query = mysqli_query($con, "SELECT namalengkap, id_user FROM user 
                    WHERE (level='kurir' OR level='driver') 
                    AND blokir!='Y' AND tampil_tracking_kurir='Y' 
                    ORDER BY namalengkap");

                while ($r = mysqli_fetch_assoc($query)) {
                  // ✅ Gunakan fungsi yang sudah ada di encrypt.php
                  $encrypted_id = base64_encrypt($r['id_user'], $key);
                  echo '<option value="'.$encrypted_id.'">'.strtoupper($r['namalengkap']).'</option>';
                }
              ?>
            </select>
          </div>
        </div>
      </div>

      <!-- BULAN TAHUN -->
      <div class="col-md-6">
        <div class="form-group">
          <label class="col-sm-3 control-label full-right">Bulan tahun</label>
          <div class="col-sm-8">
            <div class="form-group col-lg-9 col-md-9 col-xs-9">
              <select name="tahun_bulan" id="tahun_bulan" class="form-control select2">
                <?php
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

                  $currentYear = $currentDate->format('Y');
                  $currentMonth = (int) $currentDate->format('n');

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
                ?>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /.row -->
  </div><!-- /.box-body -->

  <div class="box-footer">
    <button class="btn btn-success pull-right" onclick="downloadReport()">Download report</button>
  </div>
</div><!-- /.box -->

<!-- ✅ SCRIPT DOWNLOAD -->
<script>
function downloadReport() {
  var id_team = document.getElementById("id_user").value;
  var bulan_tahun = document.getElementById("tahun_bulan").value;

  if (!id_team) {
    alert("Silakan pilih kurir terlebih dahulu!");
    return;
  }
  if (!bulan_tahun) {
    alert("Silakan pilih bulan dan tahun!");
    return;
  }

  var url = "config/phpspreadsheet/report_login_distribution.php"
            + "?id_team=" + encodeURIComponent(id_team)
            + "&tanggal=" + encodeURIComponent(bulan_tahun)
            + "&type=excel";

  window.location.href = url;
}
</script>
