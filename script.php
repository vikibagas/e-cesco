<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:index.php');
}
else{
	include "config/connection.php";
	include "config/library.php";
	include "config/encrypt.php";
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_user'];
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user where id_user="'.$id.'"');
	$u = mysqli_fetch_assoc($useraktif);

	// latest booking
	if($module=='script' and $act=='latestbooking'){
		echo'
		<table class="table no-margin">
			<thead>
				<tr>
					<th>Airwaybill</th>
					<th>Customer</th>
					<th>Service</th>
				</tr>
			</thead>
			<tbody>';
			$booking = mysqli_query($con,'select id_pengiriman, airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, service, x_service(service) as tipe_service from pengiriman where pickup="N" order by created_date desc limit 8');
				while($b=mysqli_fetch_assoc($booking)){
					$key = "asdf$1011";
					$stringawal = $b["id_pengiriman"];
					$id = base64_encrypt($stringawal,$key);

					if($b['service']=='1'){
						$srv = '<button class="btn btn-block btn-danger btn-xs">'.$b['tipe_service'].'</button>';
					}elseif($b['service']=='2'){
						$srv = '<button class="btn btn-block btn-warning btn-xs">'.$b['tipe_service'].'</button>';
					}else{
						$srv = '<button class="btn btn-block btn-default btn-xs">'.$b['tipe_service'].'</button>';
					}
					echo'
					<tr>
						<td><a href="?module=booking&act=update&id='.$id.'">'.$b['airwaybill_kode'].'</a></td>
						<td>'.$b['kustomer'].'</td>
						<td>'.$srv.'</td>
					</tr>';
				}
				echo'
				</tbody>
			</table>';
	
	// latest pickup
	}elseif($module=='script' and $act=='latestpickup'){
		echo'
		<table class="table no-margin">
			<thead>
				<tr>
					<th>Airwaybill</th>
					<th>Pickup By</th>
					<th>Pickup Time</th>
				</tr>
			</thead>
			<tbody>';
			$booking = mysqli_query($con,"select a.id_pengiriman, a.airwaybill_kode, b.namalengkap, date_format(a.pickup_date,'%d-%m-%Y %H:%i') as pickup_date from pengiriman a inner join user b on b.id_user=a.pickup_by where a.pickup='Y' order by a.pickup_date desc limit 4");
				while($b=mysqli_fetch_assoc($booking)){
					$key = "asdf$1011";
					$stringawal = $b["id_pengiriman"];
					$id = base64_encrypt($stringawal,$key);

					echo'
					<tr>
						<td><a href="?module=booking&act=update&id='.$id.'">'.$b['airwaybill_kode'].'</a></td>
						<td>'.strtoupper($b['namalengkap']).'</td>
						<td>'.$b['pickup_date'].'</td>
					</tr>';
				}
				echo'
				</tbody>
			</table>';

	// top urgent belum pickup intercity
	}elseif($module=='script' and $act=='intercitytopurgentpickup'){
		$sql1 = mysqli_query($con, "select * from pengiriman where pickup='N' and service='1' and timestampdiff(minute,created_date,sysdate())>=45 and x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)='Y' and status!='7' and status='1'");
		$s1 = mysqli_num_rows($sql1);

		$sql2 = mysqli_query($con, "select *,timestampdiff(hour,pickup_date,sysdate()) from pengiriman where delivery='N' and service='1' and timestampdiff(hour,pickup_date,sysdate())>=2 and x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)='Y' and status!='7' and status='5'");
		$s2 = mysqli_num_rows($sql2);
		
		$sql3 = mysqli_query($con, "select * from pengiriman where pickup='N' and service='1' and timestampdiff(minute,created_date,sysdate())>=30 and x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)!='Y'");
		$s3 = mysqli_num_rows($sql3);

		$sql4 = mysqli_query($con, "select * from pengiriman where delivery='N' and service='1' and timestampdiff(hour,pickup_date,sysdate())>=5 and x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)!='Y'");
		$s4 = mysqli_num_rows($sql4);

		$sql5 = mysqli_query($con, "select * from pengiriman where pickup='N' and service='2' and ((hour(created_date)>'15' and date(created_date)<=date(sysdate())) or (hour(created_date)<='15' and date(created_date)=date(sysdate()))) and x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)='Y'");
		$sql5 = mysqli_num_rows($sql5);

		$s = $s1+$s2+$s3+$s4+$sql5;
		echo ''.$s.'';
	
	// view notification
	}elseif($module=='script' and $act=='viewnotification'){
		//topurgent pickup
		if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='intercity'){
			$topurgentintercityp = mysqli_query($con, "select * from pengiriman where pickup='N' and service='1' and timestampdiff(minute,created_date,sysdate())>=45 and x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)='Y' and status!='7' and status='1'");
		}
		$tuip = mysqli_num_rows($topurgentintercityp);
		if($tuip>0){
			echo'
			<li>
				<a href="#">
					<i class="fa fa-truck text-yellow"></i>'.$tuip.' top urgent intercity need pickup
				</a>
			</li>';
		}
		// top urgent delivery
		if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='intercity'){
			$topurgentintercityd = mysqli_query($con, "select * from pengiriman where delivery='N' and service='1' and timestampdiff(hour,pickup_date,sysdate())>=2 and x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)='Y' and status!='7' and status='5'");
		}
		$tuid = mysqli_num_rows($topurgentintercityd);
		if($tuid>0){
			echo'
			<li>
				<a href="#">
					<i class="fa fa-paper-plane text-yellow"></i>'.$tuid.' top urgent intercity need delivery
				</a>
			</li>';
		}
		// top urgent domex pickup
		if($u['level']=='admin' || $u['level']=='operator'){
			$topurgentdomexp = mysqli_query($con, "select * from pengiriman where pickup='N' and service='1' and timestampdiff(minute,created_date,sysdate())>=30 and x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)!='Y'");
		}
		$tudp = mysqli_num_rows($topurgentdomexp);
		if($tudp>0){
			echo'
			<li>
				<a href="#">
					<i class="fa fa-truck text-yellow"></i>'.$tudp.' top urgent domex need pickup
				</a>
			</li>';
		}
		// top urgent domex delivery
		if($u['level']=='admin' || $u['level']=='operator'){
			$topurgentdomexd = mysqli_query($con, "select * from pengiriman where delivery='N' and service='1' and timestampdiff(hour,pickup_date,sysdate())>=5 and x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)!='Y'");
		}
		$tudd = mysqli_num_rows($topurgentdomexd);
		if($tudd>0){
			echo'
			<li>
				<a href="#">
					<i class="fa fa-paper-plane text-yellow"></i>'.$tudd.' top urgent domex need delivery
				</a>
			</li>';
		}
		// urgent intercity pickup
		if($u['level']=='admin' || $u['level']=='operator'){
			$urgentintercityp = mysqli_query($con, "select * from pengiriman where pickup='N' and service='2' and ((hour(created_date)>'15' and date(created_date)<=date(sysdate())) or (hour(created_date)<='15' and date(created_date)=date(sysdate()))) and x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)='Y'");
		}
		$uip = mysqli_num_rows($urgentintercityp);
		if($uip>0){
			echo'
			<li>
				<a href="#">
					<i class="fa fa-truck text-yellow"></i>'.$uip.' urgent intercity need pickup
				</a>
			</li>';
		}

	// delete
	}elseif($module=='airwaybill' and $act=='cancel'){
		$key = "asdf$1011";
		$encrypt = "$_GET[id]";
		$id = base64_decrypt($encrypt,$key);
		mysqli_query($con, "update pengiriman set status='5' where id_pengiriman='$id'");
		header('location:../../main.php?module='.$module);
	
	
	// notification pickup top urgent intercity
	}elseif($module=='notif' and $act=='pickuptopurgentintercity'){
		if($u['level']=='operator' || $u['level']=='intercity'){
			$topurgentintercityp = mysqli_query($con, "select * from pengiriman where pickup='N' and service='1' and x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)='Y' and status!='7' and status='1'");
			$tuip = mysqli_num_rows($topurgentintercityp);
			if($tuip>0){
				echo''.$tuip.' top urgent intercity need pickup';
			}
		}
	// notification pickup top urgent domex
	}elseif($module=='notif' and $act=='pickuptopurgentdomex'){
		if($u['level']=='operator' || $u['level']=='intercity'){
			$topurgentintercityp = mysqli_query($con, "select * from pengiriman where pickup='N' and service='1' and x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)!='Y' and status!='7' and status='1'");
			$tuip = mysqli_num_rows($topurgentintercityp);
			if($tuip>0){
				echo''.$tuip.' top urgent domex need pickup';
			}
		}
	// notification pickup top urgent network
	}elseif($module=='notif' and $act=='pickuptopurgentnetwork'){
		//if($u['level']=='admin'){
			//$topurgentintercityp = mysqli_query($con, "select * from pengiriman where pickup='N' and service='1' and x_intercity(lokasi_asal)!='Y' and x_intercity(lokasi_tujuan)!='Y' and status!='7' and status='1'");
			//$tuip = mysqli_num_rows($topurgentintercityp);
			//if($tuip>0){
				//echo''.$tuip.' top urgent network need pickup';
			//}
		//}else
		if($u['level']=='network'){
			$topurgentintercityp = mysqli_query($con, "select * from pengiriman where pickup='N' and service='1' and x_intercity(lokasi_asal)!='Y' and x_intercity(lokasi_tujuan)!='Y' and lokasi_asal in(".$u['area'].") and status!='7' and status='1'");
			$tuip = mysqli_num_rows($topurgentintercityp);
			if($tuip>0){
				echo''.$tuip.' top urgent network need pickup';
			}
		}
	// notification pickup urgent intercity
	}elseif($module=='notif' and $act=='pickupurgentintercity'){
		if($u['level']=='operator' || $u['level']=='intercity'){
			$topurgentintercityp = mysqli_query($con, "select * from pengiriman where pickup='N' and service='2' and x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)='Y' and status!='7' and status='1' and date(created_date)=curdate() and time(created_date)<'16:00:00'");
			$tuip = mysqli_num_rows($topurgentintercityp);
			if($tuip>0){
				echo''.$tuip.' urgent intercity need pickup';
			}
		}
	// notification pickup urgent domex
	}elseif($module=='notif' and $act=='pickupurgentdomex'){
		if($u['level']=='operator' || $u['level']=='intercity'){
			$topurgentintercityp = mysqli_query($con, "select * from pengiriman where pickup='N' and service='2' and x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)!='Y' and status!='7' and status='1' and date(created_date)=curdate() and time(created_date)<'16:00:00'");
			$tuip = mysqli_num_rows($topurgentintercityp);
			if($tuip>0){
				echo''.$tuip.' urgent domex need pickup';
			}
		}
	// notification pickup urgent network
	}elseif($module=='notif' and $act=='pickupurgentnetwork'){
		//if($u['level']=='admin'){/////
			//$topurgentintercityp = mysqli_query($con, "select * from pengiriman where pickup='N' and service='2' and x_intercity(lokasi_asal)!='Y' and x_intercity(lokasi_tujuan)!='Y' and status!='7' and status='1' and date(created_date)=curdate() and time(created_date)<'16:00:00'");
			//$tuip = mysqli_num_rows($topurgentintercityp);
			//if($tuip>0){
				//echo''.$tuip.' urgent network need pickup';
			//}
		//}else
		if($u['level']=='network'){
			$topurgentintercityp = mysqli_query($con, "select * from pengiriman where pickup='N' and service='2' and x_intercity(lokasi_asal)!='Y' and x_intercity(lokasi_tujuan)!='Y' and lokasi_asal in('".$u['area']."') and status!='7' and status='1' and date(created_date)=curdate() and time(created_date)<'16:00:00'");
			$tuip = mysqli_num_rows($topurgentintercityp);
			if($tuip>0){
				echo''.$tuip.' urgent network need pickup';
			}
		}
	// notification pickup reguler intercity
	}elseif($module=='notif' and $act=='pickupregulerintercity'){
		if($u['level']=='operator' || $u['level']=='intercity'){
			$topurgentintercityp = mysqli_query($con, "select * from pengiriman where pickup='N' and service='3' and x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)='Y' and status!='7' and status='1' and date(created_date)=curdate() and time(created_date)<'16:00:00'");
			$tuip = mysqli_num_rows($topurgentintercityp);
			if($tuip>0){
				echo''.$tuip.' reguler intercity need pickup';
			}
		}
	// notification pickup reguler domex
	}elseif($module=='notif' and $act=='pickupregulerdomex'){
		if($u['level']=='operator' || $u['level']=='intercity'){
			$topurgentintercityp = mysqli_query($con, "select * from pengiriman where pickup='N' and service='3' and x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)!='Y' and status!='7' and status='1' and date(created_date)=curdate() and time(created_date)<'16:00:00'");
			$tuip = mysqli_num_rows($topurgentintercityp);
			if($tuip>0){
				echo''.$tuip.' reguler domex need pickup';
			}
		}
	// notification pickup reguler network
	}elseif($module=='notif' and $act=='pickupregulernetwork'){
		//if($u['level']=='admin'){
			//$topurgentintercityp = mysqli_query($con, "select * from pengiriman where pickup='N' and service='3' and x_intercity(lokasi_asal)!='Y' and x_intercity(lokasi_tujuan)!='Y' and status!='7' and status='1' and date(created_date)=curdate() and time(created_date)<'16:00:00'");
			//$tuip = mysqli_num_rows($topurgentintercityp);
			//if($tuip>0){
				//echo''.$tuip.' reguler network need pickup';
			//}
		//}else
		if($u['level']=='network'){
			$topurgentintercityp = mysqli_query($con, "select * from pengiriman where pickup='N' and service='3' and x_intercity(lokasi_asal)!='Y' and x_intercity(lokasi_tujuan)!='Y' and lokasi_asal in(".$u['area'].") and status!='7' and status='1' and date(created_date)=curdate() and time(created_date)<'16:00:00'");
			$tuip = mysqli_num_rows($topurgentintercityp);
			if($tuip>0){
				echo''.$tuip.' reguler network need pickup';
			}
		}
	// notification delivery
	}elseif($module=='notif' and $act=='delivery'){
		//if($u['level']=='admin'){
			//$topurgentintercityp = mysqli_query($con, "select * from pengiriman a inner join kota b on b.id_kota=a.lokasi_tujuan where a.pickup='Y' and a.delivery='N' and a.status!='7' and case when x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)='Y' then status='2' when x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)!='Y' then status='4' when x_intercity(lokasi_asal)!='Y' and x_intercity(lokasi_tujuan)='Y' then status='4' end and case when x_intercity(a.lokasi_asal)='Y' and x_intercity(a.lokasi_tujuan)='Y' then datediff(current_date(), pickup_date)>=1 when x_intercity(a.lokasi_asal)='Y' and x_intercity(a.lokasi_tujuan)!='Y' and b.id_kota=b.hub then datediff(current_date(), pickup_date)>=1 when x_intercity(a.lokasi_asal)!='Y' and x_intercity(a.lokasi_tujuan)='Y' and b.id_kota=b.hub then datediff(current_date(), pickup_date)>=1 when x_intercity(a.lokasi_asal)!='Y' and x_intercity(a.lokasi_tujuan)!='Y' and b.id_kota=b.hub then datediff(current_date(), pickup_date)>=1 when x_intercity(a.lokasi_asal)!='Y' and x_intercity(a.lokasi_tujuan)!='Y' and b.id_kota!=b.hub then datediff(current_date(), pickup_date)>=2 end");
			//$tuip = mysqli_num_rows($topurgentintercityp);
			//if($tuip>0){
				//echo''.$tuip.' airwaybill need delivery';
			//}
		//}else
		if($u['level']=='operator' || $u['level']=='intercity'){
			$topurgentintercityp = mysqli_query($con, "select * from pengiriman a where a.pickup='Y' and a.delivery='N' and a.status!='7' and case when x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)='Y' then status='2' when x_intercity(lokasi_asal)!='Y' and x_intercity(lokasi_tujuan)='Y' then status='4' end and case when x_intercity(a.lokasi_asal)='Y' and x_intercity(a.lokasi_tujuan)='Y' then datediff(current_date(), pickup_date)>=1 when x_intercity(a.lokasi_asal)!='Y' and x_intercity(a.lokasi_tujuan)='Y' then datediff(current_date(), pickup_date)>=1 end");
			$tuip = mysqli_num_rows($topurgentintercityp);
			if($tuip>0){
				echo''.$tuip.' airwaybill need delivery';
			}
		}elseif($u['level']=='network'){
			$topurgentintercityp = mysqli_query($con, "select * from pengiriman a inner join kota b on b.id_kota=a.lokasi_tujuan where lokasi_tujuan in (".$u['delivery_area'].") and pickup='Y' and delivery='N' and status!='7' and case when b.id_kota=b.hub then datediff(current_date(), a.pickup_date)>=1 when b.id_kota!=b.hub then datediff(current_date(), pickup_date)>=2 end");
			$tuip = mysqli_num_rows($topurgentintercityp);
			if($tuip>0){
				echo''.$tuip.' airwaybill need delivery';
			}
		}
	// notification delivery top urgent intercity
	}elseif($module=='notif' and $act=='deliverytopurgentintercity'){
		if($u['level']=='operator' || $u['level']=='intercity'){
			$topurgentintercityd = mysqli_query($con, "select * from pengiriman where delivery='N' and service='1' and timestampdiff(hour,pickup_date,sysdate())>=2 and x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)='Y' and status!='7' and status='5'");
			$tuid = mysqli_num_rows($topurgentintercityd);
			if($tuid>0){
				echo''.$tuid.' top urgent intercity need delivery';
			}
		}
	}
	

}
?>