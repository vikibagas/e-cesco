
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
										{ "data": "Customer" },
										{ "data": "Cek" }
									], 
									"scrollX": true,
									"lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]]
									});
					
 
				compareSubmit++;
                
				$('#processing-modal').modal('hide');
            }
        });

        e.preventDefault();
    });