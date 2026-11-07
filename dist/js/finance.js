$(function () {



	$('#cost_outbond_manifest_via').on('change', function () {

		if (confirm("Ganti Vandor Manifest Via? (Hanya bisa 1 kali ganti)")){

			var manifest_via_asal = $('#manifest_via_asal').val();
			var manifest_via = $('#cost_outbond_manifest_via').val();
			var id_manifest = $('#id_manifest').val();
			$.ajax({
				type: 'POST',
				dataType: 'json',
				data: {
					manifest_via_asal: manifest_via_asal,
					manifest_via: manifest_via,
					id_manifest: id_manifest
				},
				url: 'module/mod_costing-outbound/costing-outbound_action.php?module=costing-outbound&act=ganti_manifest_via',
				success: function (result) {
					$('#manifest_via_asal').val(manifest_via);
					alert('Ganti Vendor Manifest Via Sukses');
					$('#cost_outbond_manifest_via').attr("disabled", "true");
				}
			});
		}
	});

	$('#cost_inbound_manifest_via').on('change', function () {

		if (confirm("Ganti Vandor Manifest Via? (Hanya bisa 1 kali ganti)")){

			var manifest_via_asal = $('#manifest_via_asal').val();
			var manifest_via = $('#cost_inbound_manifest_via').val();
			var id_manifest = $('#id_manifest').val();
			$.ajax({
				type: 'POST',
				dataType: 'json',
				data: {
					manifest_via_asal: manifest_via_asal,
					manifest_via: manifest_via,
					id_manifest: id_manifest
				},
				url: 'module/mod_costing-inbound/costing-inbound_action.php?module=costing-inbound&act=ganti_manifest_via',
				success: function (result) {
					$('#manifest_via_asal').val(manifest_via);
					alert('Ganti Vendor Manifest Via Sukses');
					$('#cost_inbound_manifest_via').attr("disabled", "true");
				}
			});
		}
	});

});
