<?php
if($_GET['module']=='booking' || $_GET['module']=='bookingin' || $_GET['module']=='bookinginv2' || $_GET['module']=='pickup' || $_GET['module']=='checklist' || $_GET['module']=='manifest-pickup' || $_GET['module']=='manifest-outbound' || $_GET['module']=='manifest-inbound' || $_GET['module']=='manifest' || $_GET['module']=='delivery' || $_GET['module']=='airwaybill' || $_GET['module']=='home'){
	echo'
	<li class="treeview active">
		<a href="#"><i class="fa fa-desktop"></i> <span>Application</span> <i class="fa fa-angle-left pull-right"></i></a>
		<ul class="treeview-menu">';
		if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='logistics' || $u['id_user']==9 || strpos($u['menu_tambahan'], 'booking') !== false || $u['level']=='finance' || $u['level']=='finance' || $u['level']=='inbound' || $u['level']=='outbound' || $u['level']=='kurir' || $u['level']=='driver' || $u['level']=='finance' || ($u['level']=='agent' && $u['cabang_cesco'] == 'Y')){
			if($_GET['module']=='booking' || $_GET['module']=='bookingin' || $_GET['module']=='bookinginv2'){
				echo'
				<li class="active">.  
				'; 
				if($u['id_user']==9999){
					echo '	<a href="?module=bookingin"><i class="fa fa-circle-o"></i> <span>booking</span></a>';				
				}
				
					echo '				
						<li><a href="?module=bookinginv2"><i class="fa fa-circle-o"></i> <span>Booking</span></a></li>
					';
				
			}else{
				echo'
				'; 
				if($u['id_user']==9999){
					echo '	<li><a href="?module=bookingin"><i class="fa fa-circle-o"></i> <span>booking</span></a></li>';				
				}
				
					echo '				
						<li><a href="?module=bookinginv2"><i class="fa fa-circle-o"></i> <span>Booking</span></a></li>
					';
				
				

			}
		}
		if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network'|| $u['level']=='admin_network' || $u['level']=='intercity' || $u['level']=='agent' || $u['level'] == 'kurir_agent' || $u['level']=='driver' || $u['level']=='kurir' || $u['level']=='domex' || $u['level']=='logistics' || $u['id_user']==9 || $u['id_user']==13 || $u['level']=='inbound' || $u['level'] == 'finance'){
			if($_GET['module']=='pickup'){
				echo'
				<li class="active"><a href="?module=pickup"><i class="fa fa-circle-o"></i> Pick Up</a></li>';
			}else{
				echo'
				<li><a href="?module=pickup"><i class="fa fa-circle-o"></i> Pick Up</a></li>';
			}
		}
		if($_GET['module']=='checklist' || $_GET['module']=='manifest-pickup' || $_GET['module']=='manifest-inbound' || $_GET['module']=='manifest-outbound' || $_GET['module']=='manifest' ){
			if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='logistics' || $u['id_user']==9 || strpos($u['menu_tambahan'], 'booking') !== false || $u['level']=='finance'  || $u['level']=='inbound' || $u['level']=='outbound' || $u['level']=='kurir' || $u['level']=='driver' || $u['level']=='finance' || $u['level']=='agent'){
			echo'
			<li class="treeview active">
				<a href="#"><i class="fa fa-circle-o"></i> <span>Manifest</span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">';
				if($u['level']=='admin'){
					if($_GET['module']=='checklist'){
						echo'
						<li class="active"><a href="?module=checklist"><i class="fa fa-circle-o"></i> Checklist</a></li>';
					}else{
						echo'
						<li><a href="?module=checklist"><i class="fa fa-circle-o"></i> Checklist</a></li>';
					}
				}
				if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='logistics' || $u['id_user']==9  || $u['level']=='finance'  || $u['level']=='inbound' || $u['level']=='outbound' || $u['level']=='kurir' || $u['level']=='driver' || $u['level']=='finance' || ($u['level']=='agent' && $_SESSION['cabang_cesco'] == 'Y')){
					if($_GET['module']=='manifest-pickup' ){
						echo'
						<li class="active"><a href="?module=manifest-pickup"><i class="fa fa-circle-o"></i> Pickup</a></li>';
					}else{
						echo'
						<li><a href="?module=manifest-pickup"><i class="fa fa-circle-o"></i> Pickup</a></li>';
					}
				}
				if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='domex' || $u['level']=='agent' || $u['level']=='kurir_agent' || $u['level']=='inbound'  || $u['level'] == 'finance'){
					if($_GET['module']=='manifest-outbound'){
						echo'<li class="active"><a href="?module=manifest-outbound"><i class="fa fa-circle-o"></i> Outbound</a></li>';
					}else{
						echo'<li><a href="?module=manifest-outbound"><i class="fa fa-circle-o"></i> Outbound</a></li>';
					}
				}
				if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='agent' || $u['level']=='kurir_agent' || $u['level']=='inbound'  || $u['level'] == 'finance'){
					if($_GET['module']=='manifest-inbound'){
						echo'<li class="active"><a href="?module=manifest-inbound"><i class="fa fa-circle-o"></i> Inbound</a></li>';
					}else{
						echo'<li><a href="?module=manifest-inbound"><i class="fa fa-circle-o"></i> Inbound</a></li>';
					}
				}
				if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='admin_network'   || $u['level']=='intercity' || ($u['level']=='agent' && $_SESSION['cabang_cesco'] == 'Y')  || $u['level']=='domex' || strpos($u['menu_tambahan'], 'manifest') !== false || $u['level']=='inbound'  || $u['level'] == 'finance'){
					if($_GET['module']=='manifest' ){
						echo'
						<li class="active"><a href="?module=manifest"><i class="fa fa-circle-o"></i> Delivery</a></li>';
					}else{
						echo'
						<li><a href="?module=manifest"><i class="fa fa-circle-o"></i> Delivery</a></li>';
					}
				}
				echo'
				</ul>
			</li>';
			}
		}else{
			if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='logistics' || $u['id_user']==9 || strpos($u['menu_tambahan'], 'booking') !== false || $u['level']=='finance' || $u['level']=='finance' || $u['level']=='inbound' || $u['level']=='outbound' || $u['level']=='kurir' || $u['level']=='driver' || $u['level']=='finance' || $u['level']=='agent'){
			echo'
			<li class="treeview">
				<a href="#"><i class="fa fa-circle-o"></i> <span>Manifest</span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">';
					if($u['level']=='admin'){
						echo'<li><a href="?module=checklist"><i class="fa fa-circle-o"></i> Checklist</a></li>';
					}
					
					if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='logistics' || $u['id_user']==9   || $u['level']=='finance' || $u['level']=='inbound' || $u['level']=='outbound' || $u['level']=='kurir' || $u['level']=='driver'  || ($u['level']=='agent' && $_SESSION['cabang_cesco'] == 'Y') || $u['level']=='inbound'){
						echo'<li><a href="?module=manifest-pickup"><i class="fa fa-circle-o"></i> Pickup</a></li>';
					}
					

					if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='domex' || $u['level']=='agent' || $u['level']=='kurir_agent' || $u['level']=='inbound'  || $u['level'] == 'finance'){
						echo'<li><a href="?module=manifest-outbound"><i class="fa fa-circle-o"></i> Outbound</a></li>';
					}
					if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='agent'  || $u['level']=='kurir_agent' || $u['level']=='inbound'  || $u['level'] == 'finance'){
						echo'<li><a href="?module=manifest-inbound"><i class="fa fa-circle-o"></i> Inbound</a></li>';
					}
					if($u['level']=='admin' || $u['level']=='operator'  || $u['level']=='network' || $u['level']=='admin_network'  || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='inbound' || strpos($u['menu_tambahan'], 'manifest') !== false  || $u['level'] == 'finance'){
						echo'<li><a href="?module=manifest"><i class="fa fa-circle-o"></i> Delivery</a></li>';
					}
					echo'
				</ul>
			</li>';
			}
		}
		if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='agent' || $u['level']=='kurir_agent' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='kurir'|| $u['level']=='driver' || $u['level']=='intercity'  || $u['level']=='domex' || $u['level']=='inbound' || $u['id_user'] == 87  || $u['level'] == 'finance'){
			if($_GET['module']=='delivery'){
				echo'
				<li class="active"><a href="?module=delivery"><i class="fa fa-circle-o"></i> Delivery</a></li>';
			}else{
				echo'
				<li><a href="?module=delivery"><i class="fa fa-circle-o"></i> Delivery</a></li>';
			}
		}

		if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='intercity' || $u['level']=='finance' || $u['level']=='domex' || $u['level']=='inbound' || $u['id_user'] == 87 || strpos($u['menu_tambahan'], 'awb') !== false || $u['level'] == 'agent' || $u['level']=='sales'){
			if($_GET['module']=='airwaybill'){
				echo'
				<li class="active"><a href="?module=airwaybill"><i class="fa fa-circle-o"></i> Airwaybill</a></li>';
			}else{
				echo'
				<li><a href="?module=airwaybill"><i class="fa fa-circle-o"></i> Airwaybill</a></li>';
			}
		}
		echo'
		</ul>
	</li>';
}else{
	echo'
	<li class="treeview">
		<a href="#"><i class="fa fa-desktop"></i> <span>Application</span> <i class="fa fa-angle-left pull-right"></i></a>
		<ul class="treeview-menu">';
		if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='logistics' || $u['id_user']==9 || strpos($u['menu_tambahan'], 'booking') !== false || $u['level']=='finance' || $u['level']=='finance' || $u['level']=='inbound' || $u['level']=='outbound' || $u['level']=='kurir' || $u['level']=='driver' || $u['level']=='finance' ||  ($u['level']=='agent' && $u['cabang_cesco'] == 'Y') ){
			echo'
			'; 
				if($u['id_user']==9999){
					echo '	<li><a href="?module=bookingin"><i class="fa fa-circle-o"></i> <span>booking</span></a></li>';				
				}
				echo '
			<li><a href="?module=bookinginv2"><i class="fa fa-circle-o"></i> Booking </a></li>';

		}
		if(($u['level']=='admin' || $u['level']=='operator'  || $u['level']=='intercity' || $u['level']=='agent' || $u['level']=='kurir_agent' || $u['level']=='driver' || $u['level']=='kurir' || $u['level']=='domex' || $u['level']=='inbound' || $u['level']=='logistics')  && $u['menu_pengurangan'] == "" || $u['id_user']==13){
			echo'
			<li><a href="?module=pickup"><i class="fa fa-circle-o"></i> Pick Up</a></li>';
		}
		if(($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='agent'|| $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='inbound' || strpos($u['menu_tambahan'], 'manifest') !== false)  ){
		echo' 
			<li class="treeview">
				<a href="#"><i class="fa fa-circle-o"></i> <span>Manifest</span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">';
					if($u['level']=='admin'){
						echo'<li><a href="?module=checklist"><i class="fa fa-circle-o"></i> Checklist</a></li>';
					}
					if($u['level']=='admin' || $u['level']=='operator' || ($u['level']=='agent' && $u['cabang_cesco'] == 'Y')  || $u['level']=='intercity' || $u['level']=='inbound' || $u['level']=='domex' || strpos($u['menu_tambahan'], 'manifest') !== false || $u['id_user']==13  || $u['level'] == 'finance'){
						echo'<li><a href="?module=manifest-pickup"><i class="fa fa-circle-o"></i> Pickup</a></li>';
					}
					if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='domex' || $u['level']=='agent'  || $u['level']=='inbound' || $u['level']=='kurir_agent'  || $u['level'] == 'finance'){
						echo'<li><a href="?module=manifest-outbound"><i class="fa fa-circle-o"></i> Outbound</a></li>';
					}
					if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='inbound' || $u['level']=='agent' || $u['level']=='kurir_agent'  || $u['level'] == 'finance'){
						echo'<li><a href="?module=manifest-inbound"><i class="fa fa-circle-o"></i> Inbound</a></li>';
					}
					if($u['level']=='admin' || $u['level']=='operator' || ($u['level']=='agent' && $u['cabang_cesco'] == 'Y')  || $u['level']=='operator' || $u['level']=='inbound' || $u['level']=='intercity' || $u['level']=='domex' || strpos($u['menu_tambahan'], 'manifest') !== false  || $u['level'] == 'finance'){
						echo'<li><a href="?module=manifest"><i class="fa fa-circle-o"></i> Delivery</a></li>';
					}
					echo'
				</ul>
			</li>';
		}
		if(($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='agent' || $u['level']=='inbound' || $u['level']=='kurir_agent' || $u['level']=='intercity' || $u['level']=='domex' || strpos($u['menu_tambahan'], 'manifest') !== false  || $u['level'] == 'finance') ){
			echo'
				<li><a href="?module=delivery"><i class="fa fa-circle-o"></i> Delivery</a></li>';
		}
		if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='intercity' || $u['level']=='inbound' || $u['level']=='domex' || $u['id_user'] == 87  || $u['level'] == 'finance' ||   strpos($u['menu_tambahan'], 'awb' || $u['level']=='agent' || $u['level']=='sales')){
			echo'
			<li><a href="?module=airwaybill"><i class="fa fa-circle-o"></i> Airwaybill</a></li>';
		}
		echo'
		</ul>
	</li>';
}
?>