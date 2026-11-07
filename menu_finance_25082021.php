<?php
if($u['level']=='admin' || $u['level']=='finance'  || $u['level']=='domex' || $u['level']=='agent' and $u['id_kustomer'] < 1 )
{
if ($u['menu_pengurangan'] != 'finance')
{
	if($_GET['module']=='tarif-luarnegeri' || $_GET['module']=='tarif-urgent' || $_GET['module']=='tarif-regular' || $_GET['module']=='tarif-intercity' || $_GET['module']=='costing-pickup-intercity' || $_GET['module']=='invoice-customer' || $_GET['module']=='profit-loss' || $_GET['module']=='invoice-agent' || $_GET['module']=='costing-delivery-intercity' || $_GET['module']=='costing-outbound' || $_GET['module']=='costing-inbound' || $_GET['module']=='awb-compare' || $_GET['module']=='awb-belum-tertagih'){
	echo'
	<li class="treeview active">
		<a href="#"><i class="fa fa-money"></i> <span>Finance</span> <i class="fa fa-angle-left pull-right"></i></a>';
		if($_GET['module']=='tarif-luarnegeri' || $_GET['module']=='tarif-urgent' || $_GET['module']=='tarif-regular' || $_GET['module']=='tarif-intercity' || $_GET['module']=='costing-pickup-intercity' || $_GET['module']=='invoice-customer' || $_GET['module']=='profit-loss' ||  $_GET['module']=='invoice-agent' || $_GET['module']=='costing-delivery-intercity' || $_GET['module']=='costing-outbound' || $_GET['module']=='costing-inbound' || $_GET['module']=='awb-compare' || $_GET['module']=='awb-belum-tertagih')
		{
			echo'
			<ul class="treeview-menu active">';
			if($u['level']=='finance' || $u['level']=='admin' || $u['level']=='agent' || $u['tambahan_admin']==1)
			{
				
				
				echo'
				<li class="treeview ';if($_GET['module']=='costing-pickup-intercity'  || $_GET['module']=='invoice-agent'  || $_GET['module']=='costing-delivery-intercity' || $_GET['module']=='costing-outbound' || $_GET['module']=='costing-inbound'){echo'active';}echo'"><a href="#"><i class="fa fa-circle-o"></i> Airwaybill Costing <i class="fa fa-angle-left pull-right"></i></a>
					<ul class="treeview-menu">
						';
						if($u['level']=='finance' || $u['level']=='admin' || $u['tambahan_admin']==1 || ($u['level']=='agent' && $u['cabang_cesco'] == 'Y') ){
							echo '
							<li ';if($_GET['module']=='costing-pickup-intercity'){echo'class="active"';}echo'><a href="?module=costing-pickup-intercity"><i class="fa fa-circle-o"></i> Pickup</a></li>
							<li ';if($_GET['module']=='costing-delivery-intercity'){echo'class="active"';}echo'><a href="?module=costing-delivery-intercity"><i class="fa fa-circle-o"></i> Delivery</a></li>
							<li ';if($_GET['module']=='costing-outbound'){echo'class="active"';}echo'><a href="?module=costing-outbound"><i class="fa fa-circle-o"></i> Outbound</a></li>
							<li ';if($_GET['module']=='costing-inbound'){echo'class="active"';}echo'><a href="?module=costing-inbound"><i class="fa fa-circle-o"></i> Inbound</a></li> 
							';

						}

					    if($u['level']=='admin' || $u['tambahan_admin']==1 || $u['level']=='finance' || $u['level']=='network' || $u['level']=='domex' || ($u['level']=='agent' && $u['cabang_cesco'] == 'N'))
						{
						echo'							
							<li ';if($_GET['module']=='invoice-agent'){echo'class="active"';}echo'><a href="?module=invoice-agent"><i class="fa fa-circle-o"></i> Invoice Agent</a></li>

							';
						} echo '

					</ul>
				</li>';
				if($u['level']=='finance' || $u['level']=='admin' || $u['level']=='domex' || $u['tambahan_admin']==1){
					echo'
					<li class="treeview ';if($_GET['module']=='invoice-customer' || $_GET['module']=='profit-loss' || $_GET['module']=='awb-compare'){echo'active';}echo'"><a href="#"><i class="fa fa-circle-o"></i> Invoice <i class="fa fa-angle-left pull-right"></i></a>
						<ul class="treeview-menu">
							<li ';if($_GET['module']=='invoice-customer'){echo'class="active"';}echo'><a href="?module=invoice-customer"><i class="fa fa-circle-o"></i> Customer</a></li>
							<li ';if($_GET['module']=='awb-compare'){echo'class="active"';}echo'><a href="?module=awb-compare"><i class="fa fa-circle-o"></i> AWB Compare</a></li>
							';
							if ($u['id_user'] == 3 || $u['id_user'] == 4){
								echo '<li ';if($_GET['module']=='profit-loss'){echo'class="active"';}echo'><a href="?module=profit-loss"><i class="fa fa-circle-o"></i> Profit & Loss</a></li>
							';
							}
							

	//						<li><a href="?module=invoice-agent"><i class="fa fa-circle-o"></i> Agent</a></li> 
							echo '
						</ul>
					</li>';
					
					echo'
					<li ';if($_GET['module']=='awb-belum-tertagih'){echo'class="active"';}echo'><a href="?module=awb-belum-tertagih"><i class="fa fa-circle-o"></i> AWB Belum Tertagih</a></li>';
				}
			}
			
			echo'
			</ul>';
		}
		echo'
	</li>';
	}else{
		echo'
		<li class="treeview">
			<a href="#"><i class="fa fa-money"></i> <span>Finance</span> <i class="fa fa-angle-left pull-right"></i></a>
			<ul class="treeview-menu">';
			if($u['level']=='finance' || $u['level']=='admin' || $u['level']=='domex' || $u['level']=='network' || $u['level']=='agent' )
			{ 
				if($u['level']=='finance' || $u['level']=='admin' || $u['tambahan_admin']==1 || ($u['level']=='agent' && $u['cabang_cesco'] == 'Y')){
				echo'
				
				<li class="treeview"><a href="#"><i class="fa fa-circle-o"></i> Airwaybill Costing <i class="fa fa-angle-left pull-right"></i></a>
					<ul class="treeview-menu">
						<li><a href="?module=costing-pickup-intercity"><i class="fa fa-circle-o"></i> Pickup</a></li>
						<li><a href="?module=costing-delivery-intercity"><i class="fa fa-circle-o"></i> Delivery</a></li>
						<li><a href="?module=costing-outbound"><i class="fa fa-circle-o"></i> Outbound</a></li>
						<li><a href="?module=costing-inbound"><i class="fa fa-circle-o"></i> Inbound</a></li>
						<li><a href="?module=invoice-agent"><i class="fa fa-circle-o"></i> Invoice Agent</a></li>
						';
				}
				else{
					echo '<li class="treeview"><a href="#"><i class="fa fa-circle-o"></i> Airwaybill Costing <i class="fa fa-angle-left pull-right"></i></a>
					<ul class="treeview-menu">						
						<li><a href="?module=invoice-agent"><i class="fa fa-circle-o"></i> Invoice Agent</a></li>
						';
				}
						echo '
					</ul>
				</li>
				';
				if($u['level']=='finance' || $u['level']=='admin' || $u['level']=='domex'){
				echo '

				<li class="treeview"><a href="#"><i class="fa fa-circle-o"></i> Invoice <i class="fa fa-angle-left pull-right"></i></a>
					<ul class="treeview-menu">
						<li><a href="?module=invoice-customer"><i class="fa fa-circle-o"></i> Customer</a></li>
						<li><a href="?module=awb-compare"><i class="fa fa-circle-o"></i> AWB Compare</a></li>
						';
						if ($u['id_user'] == 3 || $u['id_user'] == 4){
						echo '	<li><a href="?module=profit-loss"><i class="fa fa-circle-o"></i> Profit & Loss</a></li>';
						}
					echo '
					</ul>
				</li>';
				echo'
					<li ';if($_GET['module']=='awb-belum-tertagih'){echo'class="active"';}echo'><a href="?module=awb-belum-tertagih"><i class="fa fa-circle-o"></i> AWB Belum Tertagih</a></li>';
				}
			}
			
			echo'
			</ul>
		</li>';
	}
}
}
?>