<ul class="sidebar-menu">
	<li class="header">MAIN NAVIGATION <?php echo $_SESSION['cabang_cesco']; ?></li>
	<li><a href="?module=home"><i class="fa fa-dashboard"></i> <span>Dashboard  </span></a></li>
	<?php if($u['level']=='admin' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='inbound' || $u['level']=='finance' || $u['level']=='intercity'){ ?>
		<li><a href="?module=vechile"><i class="fa fa-car"></i> <span>Tracking Car GPS</span></a></li>
	<?php } ?>
	<?php if($u['level']=='admin' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='inbound' || $u['level']=='finance' || $u['level']=='intercity'){ ?>
		<li><a href="?module=tracking-courier"><i class="fa fa-car"></i> <span>Tracking Courier</span></a></li>
	<?php } ?>
	<?php include ('menu_masterdata.php'); ?>
	<?php include ('menu_aplikasi.php'); ?>
	<?php include ('menu_finance.php'); ?>
	<?php include ('menu_warehouse.php'); ?>
	<?php include ('menu_kpi.php'); ?>
	<?php
	if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='intercity' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='finance' || $u['level']=='inbound' || $u['level']=='driver'  || $u['level']=='domex' || $u['level'] == 'sales' || ($u['level']=='agent' && $u['cabang_cesco'] == 'Y') and $u['id_kustomer'] < 1 ){
		if($_GET['module']=='report-airwaybill-by-date' || $_GET['module']=='report-airwaybill-by-customer' || $_GET['module']=='report-manifest-pickup' || $_GET['module']=='report-manifest-outbound' || $_GET['module']=='report-manifest-inbound' || $_GET['module']=='report-manifest-delivery' || $_GET['module']=='report-airwaybill-by-courier'){
			echo'
			<li class="treeview active">
				<a href="#"><i class="fa fa-print"></i> <span>Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">';
				if($_GET['module']=='report-airwaybill-by-date' || $_GET['module']=='report-airwaybill-by-customer'){
					if($u['level']=='admin' || $u['level']=='finance' || $u['level']=='intercity' || $u['level']=='domex' || ($u['level']=='agent' && $u['cabang_cesco'] == 'Y') || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='inbound' || $u['level'] == 'sales'){
					echo'
					<li class="treeview active">
						<a href="#"><i class="fa fa-circle-o"></i> <span>Airwaybill</span> <i class="fa fa-angle-left pull-right"></i></a>
						<ul class="treeview-menu">';
						if($_GET['module']=='report-airwaybill-by-date'){
							echo'
							<li class="active"><a href="?module=report-airwaybill-by-date"><i class="fa fa-circle-o"></i> by Date</a></li>';
						}else{
							echo'
							<li><a href="?module=report-airwaybill-by-date"><i class="fa fa-circle-o"></i> by Date</a></li>';
						}
						if($u['level']=='admin' || $u['level']=='finance' || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='inbound' || $u['level']=='sales' ){

							if($_GET['module']=='report-airwaybill-by-customer'){
								echo'
								<li class="active"><a href="?module=report-airwaybill-by-customer"><i class="fa fa-circle-o"></i> by Customer</a></li>';
							}else{
								echo'
								<li><a href="?module=report-airwaybill-by-customer"><i class="fa fa-circle-o"></i> by Customer</a></li>';
							}
						}
						echo'
						</ul>
					</li>';
					}

					if($u['level']=='admin' || $u['level']=='finance' || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='inbound'){
						echo'
						<li class="treeview">
							<a href="#"><i class="fa fa-circle-o"></i> <span>Manifest</span> <i class="fa fa-angle-left pull-right"></i></a>
							<ul class="treeview-menu">
								<li><a href="?module=report-manifest-pickup"><i class="fa fa-circle-o"></i> Pickup</a></li>
								<li><a href="?module=report-manifest-delivery"><i class="fa fa-circle-o"></i> Delivery</a></li>
							</ul>
						</li>';

						echo '				
							<li><a href="?module=report-pickup-agent"><i class="fa fa-circle-o"></i> <span>Pickup Agent</span></a></li>
							<li><a href="?module=report-delivery-agent"><i class="fa fa-circle-o"></i> <span>Delivery Agent</span></a></li>
						';
					}
				}
				if($_GET['module']=='report-manifest-pickup' || $_GET['module']=='report-manifest-outbound' || $_GET['module']=='report-manifest-inbound' || $_GET['module']=='report-manifest-inbound' || $_GET['module']=='report-manifest-delivery'){
					if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='domex' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='inbound'|| $u['level']=='finance' || $u['level'] == 'sales'){
					echo'
					<li class="treeview">
						<a href="#"><i class="fa fa-circle-o"></i> <span>Airwaybill</span> <i class="fa fa-angle-left pull-right"></i></a>
						<ul class="treeview-menu">
							<li><a href="?module=report-airwaybill-by-date"><i class="fa fa-circle-o"></i> by Date</a></li>
							<li><a href="?module=report-airwaybill-by-customer"><i class="fa fa-circle-o"></i> by Customer</a></li>
						</ul>
					</li>';
					}
					if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='domex' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='inbound'|| $u['level']=='finance' ){
						echo'
						<li class="treeview active">
							<a href="#"><i class="fa fa-circle-o"></i> <span>Manifest</span> <i class="fa fa-angle-left pull-right"></i></a>
							<ul class="treeview-menu">';
							if($_GET['module']=='report-manifest-pickup'){
								echo'
								<li class="active"><a href="?module=report-manifest-pickup"><i class="fa fa-circle-o"></i> Pickup</a></li>';
							}else{
								echo'
								<li><a href="?module=report-manifest-pickup"><i class="fa fa-circle-o"></i> Pickup</a></li>';
							}
							if($u['level']!=='kurir'){
							if($_GET['module']=='report-manifest-outbound'){
								echo'
								<li class="active"><a href="?module=report-manifest-outbound"><i class="fa fa-circle-o"></i> Outbound</a></li>';
							}else{
								echo'
								<li><a href="?module=report-manifest-outbound"><i class="fa fa-circle-o"></i> Outbound</a></li>';
							}
							}
							if($_GET['module']=='report-manifest-inbound'){
								echo'
								<li class="active"><a href="?module=report-manifest-inbound"><i class="fa fa-circle-o"></i> Inbound</a></li>';
							}else{
								echo'
								<li><a href="?module=report-manifest-inbound"><i class="fa fa-circle-o"></i> Inbound</a></li>';
							}
							if($_GET['module']=='report-manifest-delivery'){
								echo'
								<li class="active"><a href="?module=report-manifest-delivery"><i class="fa fa-circle-o"></i> Delivery</a></li>';
							}else{
								echo'
								<li><a href="?module=report-manifest-delivery"><i class="fa fa-circle-o"></i> Delivery</a></li>';
							}
							echo'
							</ul>
						</li>';
					}
				}
				echo'
				</ul>
			</li>';
		}else{
			echo'
			<li class="treeview">
				<a href="#"><i class="fa fa-print"></i> <span>Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">';
				if($u['level']=='admin' || $u['level']=='finance' || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='agent' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='inbound' || $u['level']=='sales'){
					echo'
					<li class="treeview">
						<a href="#"><i class="fa fa-circle-o"></i> <span>Airwaybill</span> <i class="fa fa-angle-left pull-right"></i></a>
						<ul class="treeview-menu">
							<li><a href="?module=report-airwaybill-by-date"><i class="fa fa-circle-o"></i> by Date</a></li>
							';if($u['level'] != 'agent'){

								echo '<li><a href="?module=report-airwaybill-by-customer"><i class="fa fa-circle-o"></i> by Customer</a></li>';
							}echo '
						</ul>
					</li>';
				}
				if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='intercity'  || $u['level']=='agent' || $u['level']=='finance' || $u['level']=='domex' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='inbound' ){
				echo'
					<li class="treeview">
						<a href="#"><i class="fa fa-circle-o"></i> <span>Manifest</span> <i class="fa fa-angle-left pull-right"></i></a>
						<ul class="treeview-menu">
							<li><a href="?module=report-manifest-pickup"><i class="fa fa-circle-o"></i> Pickup</a></li>';
							if($u['level']!=='intercity'){
								echo'
								<li><a href="?module=report-manifest-outbound"><i class="fa fa-circle-o"></i> Outbound</a></li>';
							}
							echo'
							<li><a href="?module=report-manifest-inbound"><i class="fa fa-circle-o"></i> Inbound</a></li>
							<li><a href="?module=report-manifest-delivery"><i class="fa fa-circle-o"></i> Delivery</a></li>
						</ul>
					</li>';
				
				/*if ($u['level']=='kurir'){
					echo '<li><a href="?module=report-airwaybill-by-courier"><i class="fa fa-print"></i> by Courier</a></li>';
				}*/
				echo'
					<li><a href="?module=report-pickup-agent"><i class="fa fa-circle-o"></i> Pickup Agent </a></li>
					<li><a href="?module=report-delivery-agent"><i class="fa fa-circle-o"></i> Deliery Agent </a></li>
				</ul>
			</li>

			';
			}
		}
	}
	?>
	<!--
	<li class="treeview">
		<a href="#"><i class="fa fa-gears"></i> <span>Management</span> <i class="fa fa-angle-left pull-right"></i></a>
		<ul class="treeview-menu">
			<li><a href="?module=report-manifest-pickup"><i class="fa fa-circle-o"></i> Users</a></li>
			<li><a href="?module=report-manifest-delivery"><i class="fa fa-circle-o"></i> Clients</a></li>
		</ul>
	</li>
	-->
</ul>