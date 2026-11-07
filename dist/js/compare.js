
var compareSubmit = 0;
$('#frmCompare').on('submit', function(e) {
	
		var formData = new FormData($('#frmCompare')[0]);//create empty FormData object

        formData.append("xlsFile", 0);
				
        $('#processing-modal').modal('show');
        $.ajax({
            type: "POST",
            dataType: "JSON",
            url: $("#frmCompare").attr('action'),
			processData: false,//important
            contentType: false,//important
            data: formData,
            success: function(data){
				
				if (compareSubmit > 0){
					$("#tblCompare").DataTable().destroy();
				}
            	
				var table = $('#tblCompare').dataTable({
									"data": data,
									"columns": [
										{ "data": "AWB" },
										{ "data": "KG" },
										{ "data": "P" },
										{ "data": "L" },
										{ "data": "T" },
										{ "data": "Service" },
										{ "data": "Asal" },
										{ "data": "Tujuan" },
										{ "data": "Cek" }
									], 
									"lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]]
									});
					
 
				compareSubmit++;
                
				$('#processing-modal').modal('hide');
            }
        });

        e.preventDefault();
    });

var compareInvAgentSubmit = 0;
$('#frmInvAgentCompare').on('submit', function(e) {

	var formData = new FormData($('#frmInvAgentCompare')[0]);//create empty FormData object

	formData.append("xlsFile", 0);

	$('#processing-modal').modal('show');
	$.ajax({
		type: "POST",
		dataType: "JSON",
		url: $("#frmInvAgentCompare").attr('action'),
		processData: false,//important
		contentType: false,//important
		data: formData,
		success: function(data){

			if (compareInvAgentSubmit > 0){
				$("#tblCompare").DataTable().destroy();
			}

			var table = $('#tblCompare').dataTable({
				"data": data,
				"columns": [
					{ "data": "AWB" },
					{ "data": "SERVICE" },
					{ "data": "KOTA_ASAL" },
					{ "data": "KOTA_TUJUAN" },
					{ "data": "BERAT" },
					{ "data": "HARGA" },
					{ "data": "TOTAL" },
					{ "data": "HARGA_ECESCO" },
					{ "data": "Cek" }
				],
				dom: 'lBfrtip',
				buttons: [{
					extend:     'excel',
					text:       'Export Excel',
					filename: "export_excel_compare_invoice_agent"

				}],
				"lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
				"initComplete": function( settings, json ) {

					$("div.dt-buttons").appendTo("#tblCompare_wrapper");
					$("#tblCompare_info").addClass("col-sm-5");
					$("#InvoiceAgent_paginate").addClass("col-sm-7");
					$("#tblCompare_length").addClass("col-sm-6");
				}
			});



			compareInvAgentSubmit++;

			$('#processing-modal').modal('hide');
		}
	});

	e.preventDefault();
});