<section class="content-header">
	<h1>Vechile</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Master data</a></li>
		
	</ol>
</section>


	
	
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Tracking your vechile</h3>
			<div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
			</div>
		</div><!-- /.box-header -->
		<div class="box-body">
			<div class="row">
				
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-3 control-label full-right">Vechile</label>
						<div class="col-sm-8">
							<select name="vechile" id="imei-vechile" class="form-control select2">
							<?php
							if($u['id_kustomer']> 0){
								$query=mysqli_query($con,'select plat_nomor,imei_gps from vechile  order by plat_nomor');
								
							}else{
								$query=mysqli_query($con,'select plat_nomor,imei_gps from vechile  order by plat_nomor');	
							}
							while($r=mysqli_fetch_assoc($query)){
								echo'
								<option value="'.$r['imei_gps'].'">'.$r['plat_nomor'].'</option>';
							}

							?>
							</select>
						</div>
					</div>
				</div>
			</div><!-- /.row -->
		</div><!-- /.box-body -->
        <div class="box-footer">
			<button class="btn btn-primary pull-right" onclick="trackingvechile()"> <i class="fa fa-search"></i> View</button>
		</div>
	</div><!-- /.box -->

	<iframe src="" width="100%" id="viewmaps" height="600" style="border:0;" allowfullscreen></iframe>
	


<script>
function trackingvechile() {
	var imeivechile = $("#imei-vechile").val();
  	//alert(imeivechile);


  	$.ajax({
				type: 'POST',
				dataType: 'json',
				data: {
					imei: imeivechile,
				},
				url: 'module/mod_vechile/vechile_actionnew.php',
				success: function (result) {
					//alert('asda');
					//$('#intercityasal').val(result['intercity']);
					if(result.success > 0){
						alert (result.message);
						$("#viewmaps").attr("src", result.url);
					}else{

						alert (result.message);
						

					}

						

						//$('#inputawb').prop("disabled", false);

					//$('#intercityasal').val(result['intercity']);
					//alert(result.kota_asal);

				}
			});


}
</script>
	
</section><!-- /.content -->


