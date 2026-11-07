<section class="content-header">
	<h1>Tracking courier</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Master data</a></li>
		
	</ol>
</section>
		
	<div class="box box-primary">
		<div class="box-header with-border">
			
					<div class="row">
									<div class="col-md-6">
										&nbsp;
									</div>
									<div class="col-md-6">
										<div class="btn-group pull-right">
											<button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i></button>
											<ul class="dropdown-menu pull-right">
												<li><a href="main.php?module=tracking-report"><i class="fa fa-download"></i> Download Report </a></li>
												
											</ul>
										</div>										
									</div>
								</div>
			<h3 class="box-title">Tracking Courier</h3>
			
		</div><!-- /.box-header -->
		<div class="box-body">
			<div class="row">


				
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-3 control-label full-right">Kurir</label>
						<div class="col-sm-8">
							<select name="id_user" id="id_user" onchange="trackingcourier()" class="form-control select2">
								<option value="">-- SELECT --</option>';
							<?php
							
								$query=mysqli_query($con,"select namalengkap,id_user from user where (level = 'kurir'  or level = 'driver' or level = 'agent' or level = 'kurir_agent') and blokir != 'Y' and tampil_tracking_kurir = 'Y' order by namalengkap");
							
							while($r=mysqli_fetch_assoc($query)){
								echo'
								<option value="'.$r['id_user'].'">'.strtoupper($r['namalengkap']).'</option>';
							}

							?>
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-3 control-label full-right">Waktu Tracking</label>
						<div class="col-sm-8">
							<select name="id_location_history" id="id_location_history" onchange="" class="form-control select2">
							<?php
							
								

							?>
							</select>
						</div>
					</div>
				</div>
			</div><!-- /.row -->
		</div><!-- /.box-body -->
        <div class="box-footer">
			 <button class="btn btn-primary pull-right" onclick="viewTracking()">🔍 View</button>
		</div>
	</div><!-- /.box -->

	<div id="map" style="height: 500px; margin-top: 20px; border-radius: 8px;"></div>
	


<script>
	let map, marker;
function trackingcourier() {
    let id_user = $('#id_user').val();
    if (id_user === '') return;

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: 'module/mod_tracking-courier/tracking_action.php?module=tracking&act=tampil_history',
        data: { id_user: id_user },
        success: function(result) {
            $('#id_location_history').html('<option value="">-- SELECT WAKTU --</option>');
            if(result.success) {
                $.each(result.data, function(i, item){
                    $('#id_location_history').append('<option value="'+item.id+'">'+item.timestamp+'</option>');
                });
            } else {
                alert('Tidak ada data tracking untuk kurir ini.');
            }
        }
    });
}
function viewTracking() {
    let id = $('#id_location_history').val();
    if (id === '') {
        alert('Pilih waktu tracking terlebih dahulu!');
        return;
    }

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: 'module/mod_tracking-courier/tracking_action.php?module=tracking&act=view_tracking',
        data: { id_location_history: id },
        success: function(result) {
            if (result.success) {
                const lat = parseFloat(result.data.lat);
                const lng = parseFloat(result.data.lng);

                if (!map) {
                    map = L.map('map').setView([lat, lng], 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(map);
                } else {
                    map.setView([lat, lng], 15);
                }

                if (marker) {
                    map.removeLayer(marker);
                }

                marker = L.marker([lat, lng]).addTo(map)
                    .bindPopup(`<b>Kurir:</b> ${result.data.nama}<br>
                                <b>Waktu:</b> ${result.data.timestamp}<br>
                                <b>Lat:</b> ${lat}<br>
                                <b>Lng:</b> ${lng}<br>
                                <b>Detail Lokasi:</b> ${result.data.detail_alamat}`)
                    .openPopup();
            } else {
                alert('Data lokasi tidak ditemukan.');
            }
        }
    });
}
</script>





<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</section><!-- /.content -->


