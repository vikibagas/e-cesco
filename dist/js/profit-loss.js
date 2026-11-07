
if( $('#profitLoss').length )
{
     var table = $("#profitLoss");
	table.DataTable({
		"processing": true,
		"serverSide": true,
		"responsive": true,
		"ajax":{
			url :"module/mod_profit-loss/profit-loss_action.php?module=profit-loss&act=read"
		},
		"order": [
			[ 0, "desc" ]
		]
	});
}
	

$("#checkallProfitLoss").click(function() {
		//alert('test1');
		
		//$("#btnsearch").prop("disabled", true);

		if ($('#report .printawb').is(':checked') ==  false)
	    {
	    //	alert('test if');
	        $('#report .printawb').prop('checked', true);
	        $("#report .printawb").attr("checked", true);
	    }
	    else
	    {
	    //	alert('test else');
	        $('#report .printawb').prop('checked', false);
	    }
	});
