// cek top urgent belum pickup intercity
function topurgentpui(){
	$.ajax({
		type: 'post',
		url: 'script.php?module=script&act=intercitytopurgentpickup',
		datatype: 'json',
		success: function(response){
			$("#topurgentpui").text(""+response+"");
			$("#topurgentpui1").text(""+response+"");
			timer = setTimeout("topurgentpui()",120000);
		}
	});
}

function latestnotification(){
	$('#latest-notification').load('script.php?module=script&act=viewnotification',function () {
		$(this).html();
	});
}

// make user to check notification
function cek(){
    $.ajax({
        url: "cekpesan.php",
        cache: false,
        success: function(msg){
            $("#notifikasi").html(msg);
        }
    });
    var waktu = setTimeout("cek()",120000);
}

function notifikasi(msg,url) {
	if (!Notification) {
		alert('Browsermu tidak mendukung Web Notification.');
		return;
	}
	if (Notification.permission !== "granted")
		Notification.requestPermission();
	else {
		var notifikasi = new Notification('Notification !!!', {
			icon: 'dist/img/alert.png',
			body: msg,
		});
		notifikasi.onclick = function () {
			location.href = url;
		};
		setTimeout(function(){
			notifikasi.close();
		}, 120000);
	}
};
		
function topurgentintercitypickup(){
	$.ajax({
		type: 'post',
		url: 'script.php?module=notif&act=pickuptopurgentintercity',
		datatype: 'json',
		success: function(msg){
			if (msg !== '') 
			notifikasi(""+msg+"","?module=manifest-pickup");
			timer = setTimeout("topurgentintercitypickup()",300000);
		}
	});
}

function notifikasi_pickup_topurgent_domex(msg,url) {
	if (!Notification) {
		alert('Browsermu tidak mendukung Web Notification.');
		return;
	}
	if (Notification.permission !== "granted")
		Notification.requestPermission();
	else {
		var notifikasi_pickup_topurgent_domex = new Notification('Notification !!!', {
			icon: 'dist/img/alert.png',
			body: msg,
		});
		notifikasi_pickup_topurgent_domex.onclick = function () {
			location.href = url;
		};
		setTimeout(function(){
			notifikasi_pickup_topurgent_domex.close();
		}, 120000);
	}
};
		
function topurgentdomexpickup(){
	$.ajax({
		type: 'post',
		url: 'script.php?module=notif&act=pickuptopurgentdomex',
		datatype: 'json',
		success: function(msg){
			if (msg !== '') 
			notifikasi_pickup_topurgent_domex(""+msg+"","?module=manifest-pickup");
			timer = setTimeout("topurgentdomexpickup()",300000);
		}
	});
}

function notifikasi_pickup_topurgent_network(msg,url) {
	if (!Notification) {
		alert('Browsermu tidak mendukung Web Notification.');
		return;
	}
	if (Notification.permission !== "granted")
		Notification.requestPermission();
	else {
		var notifikasi_pickup_topurgent_network = new Notification('Notification !!!', {
			icon: 'dist/img/alert.png',
			body: msg,
		});
		notifikasi_pickup_topurgent_network.onclick = function () {
			location.href = url;
		};
		setTimeout(function(){
			notifikasi_pickup_topurgent_network.close();
		}, 120000);
	}
};
		
function topurgentnetworkpickup(){
	$.ajax({
		type: 'post',
		url: 'script.php?module=notif&act=pickuptopurgentnetwork',
		datatype: 'json',
		success: function(msg){
			if (msg !== '') 
			notifikasi_pickup_topurgent_network(""+msg+"","?module=manifest-pickup");
			timer = setTimeout("topurgentnetworkpickup()",300000);
		}
	});
}

function notifikasi_urgent_intercity_pickup(msg,url) {
	if (!Notification) {
		alert('Browsermu tidak mendukung Web Notification.');
		return;
	}
	if (Notification.permission !== "granted")
		Notification.requestPermission();
	else {
		var notifikasi = new Notification('Notification !!!', {
			icon: 'dist/img/alert.png',
			body: msg,
		});
		notifikasi.onclick = function () {
			location.href = url;
		};
		setTimeout(function(){
			notifikasi.close();
		}, 120000);
	}
};
		
function urgentintercitypickup(){
	$.ajax({
		type: 'post',
		url: 'script.php?module=notif&act=pickupurgentintercity',
		datatype: 'json',
		success: function(msg){
			if (msg !== '') 
			notifikasi_urgent_intercity_pickup(""+msg+"","?module=manifest-pickup");
			timer = setTimeout("urgentintercitypickup()",300000);
		}
	});
}

function notifikasi_urgent_domex_pickup(msg,url) {
	if (!Notification) {
		alert('Browsermu tidak mendukung Web Notification.');
		return;
	}
	if (Notification.permission !== "granted")
		Notification.requestPermission();
	else {
		var notifikasi = new Notification('Notification !!!', {
			icon: 'dist/img/alert.png',
			body: msg,
		});
		notifikasi.onclick = function () {
			location.href = url;
		};
		setTimeout(function(){
			notifikasi.close();
		}, 120000);
	}
};
		
function urgentdomexpickup(){
	$.ajax({
		type: 'post',
		url: 'script.php?module=notif&act=pickupurgentdomex',
		datatype: 'json',
		success: function(msg){
			if (msg !== '') 
			notifikasi_urgent_domex_pickup(""+msg+"","?module=manifest-pickup");
			timer = setTimeout("urgentdomexpickup()",300000);
		}
	});
}

function notifikasi_urgent_network_pickup(msg,url) {
	if (!Notification) {
		alert('Browsermu tidak mendukung Web Notification.');
		return;
	}
	if (Notification.permission !== "granted")
		Notification.requestPermission();
	else {
		var notifikasi = new Notification('Notification !!!', {
			icon: 'dist/img/alert.png',
			body: msg,
		});
		notifikasi.onclick = function () {
			location.href = url;
		};
		setTimeout(function(){
			notifikasi.close();
		}, 120000);
	}
};
		
function urgentnetworkpickup(){
	$.ajax({
		type: 'post',
		url: 'script.php?module=notif&act=pickupurgentnetwork',
		datatype: 'json',
		success: function(msg){
			if (msg !== '') 
			notifikasi_urgent_domex_pickup(""+msg+"","?module=manifest-pickup");
			timer = setTimeout("urgentnetworkpickup()",300000);
		}
	});
}

function notifikasi_reguler_intercity_pickup(msg,url) {
	if (!Notification) {
		alert('Browsermu tidak mendukung Web Notification.');
		return;
	}
	if (Notification.permission !== "granted")
		Notification.requestPermission();
	else {
		var notifikasi = new Notification('Notification !!!', {
			icon: 'dist/img/alert.png',
			body: msg,
		});
		notifikasi.onclick = function () {
			location.href = url;
		};
		setTimeout(function(){
			notifikasi.close();
		}, 120000);
	}
};
		
function regulerintercitypickup(){
	$.ajax({
		type: 'post',
		url: 'script.php?module=notif&act=pickupregulerintercity',
		datatype: 'json',
		success: function(msg){
			if (msg !== '') 
			notifikasi_urgent_intercity_pickup(""+msg+"","?module=manifest-pickup");
			timer = setTimeout("regulerintercitypickup()",500000);
		}
	});
}

function notifikasi_reguler_domex_pickup(msg,url) {
	if (!Notification) {
		alert('Browsermu tidak mendukung Web Notification.');
		return;
	}
	if (Notification.permission !== "granted")
		Notification.requestPermission();
	else {
		var notifikasi = new Notification('Notification !!!', {
			icon: 'dist/img/alert.png',
			body: msg,
		});
		notifikasi.onclick = function () {
			location.href = url;
		};
		setTimeout(function(){
			notifikasi.close();
		}, 120000);
	}
};
		
function regulerdomexpickup(){
	$.ajax({
		type: 'post',
		url: 'script.php?module=notif&act=pickupregulerdomex',
		datatype: 'json',
		success: function(msg){
			if (msg !== '') 
			notifikasi_urgent_intercity_pickup(""+msg+"","?module=manifest-pickup");
			timer = setTimeout("regulerdomexpickup()",500000);
		}
	});
}

function notifikasi_reguler_network_pickup(msg,url) {
	if (!Notification) {
		alert('Browsermu tidak mendukung Web Notification.');
		return;
	}
	if (Notification.permission !== "granted")
		Notification.requestPermission();
	else {
		var notifikasi = new Notification('Notification !!!', {
			icon: 'dist/img/alert.png',
			body: msg,
		});
		notifikasi.onclick = function () {
			location.href = url;
		};
		setTimeout(function(){
			notifikasi.close();
		}, 120000);
	}
};
		
function regulernetworkpickup(){
	$.ajax({
		type: 'post',
		url: 'script.php?module=notif&act=pickupregulernetwork',
		datatype: 'json',
		success: function(msg){
			if (msg !== '') 
			notifikasi_urgent_intercity_pickup(""+msg+"","?module=manifest-pickup");
			timer = setTimeout("regulernetworkpickup()",500000);
		}
	});
}

function notifikasi_delivery(msg,url) {
	if (!Notification) {
		alert('Browsermu tidak mendukung Web Notification.');
		return;
	}
	if (Notification.permission !== "granted")
		Notification.requestPermission();
	else {
		var notifikasi = new Notification('Notification !!!', {
			icon: 'dist/img/alert.png',
			body: msg,
		});
		notifikasi.onclick = function () {
			location.href = url;
		};
		setTimeout(function(){
			notifikasi.close();
		}, 120000);
	}
};
		
function delivery(){
	$.ajax({
		type: 'post',
		url: 'script.php?module=notif&act=delivery',
		datatype: 'json',
		success: function(msg){
			if (msg !== '') 
			notifikasi_delivery(""+msg+"","?module=manifest-delivery");
			timer = setTimeout("delivery()",500000);
		}
	});
}

function notifikasi_tuid(msg,url) {
	if (!Notification) {
		alert('Browsermu tidak mendukung Web Notification.');
		return;
	}
	if (Notification.permission !== "granted")
		Notification.requestPermission();
	else {
		var notifikasi_tuid = new Notification('Notification !!!', {
			icon: 'dist/img/alert.png',
			body: msg,
		});
		notifikasi_tuid.onclick = function () {
			location.href = url;
		};
		setTimeout(function(){
			notifikasi_tuid.close();
		}, 120000);
	}
};

function topurgentintercitydelivery(){
	$.ajax({
		type: 'post',
		url: 'script.php?module=notif&act=deliverytopurgentintercity',
		datatype: 'json',
		success: function(msg){
			if (msg !== '') 
			notifikasi_tuid(""+msg+"","?module=manifest");
			timer = setTimeout("topurgentintercitydelivery()",120000);
		}
	});
}
		
$(document).ready(function(){
	//if (Notification.permission !== "granted")
	//Notification.requestPermission();
	
	topurgentpui();
	latestnotification();
	topurgentintercitypickup();
	urgentintercitypickup();
	urgentdomexpickup();
	urgentnetworkpickup();
	topurgentdomexpickup();
	topurgentnetworkpickup();
	regulerintercitypickup();
	regulerdomexpickup();
	regulernetworkpickup();
	delivery();
	//topurgentintercitydelivery();
	setInterval(function(){
		latestnotification()
	}, 120000);
	
});