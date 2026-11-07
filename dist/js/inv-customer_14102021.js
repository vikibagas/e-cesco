
if( $('#report').length )
{
	if (countCostPick > 0){
		var aTextCPI = $('#thCPI').text();
		aTextCPI += ' - <button type="button" class="btn btn-xs btn-danger" title="'+countCostPick+'"><i class="fa fa-exclamation-circle"></i></button> ('+countCostPick+')';
		$('#thCPI').html(aTextCPI);
	}

	if (countCostDeliv > 0) {

		var aTextCDI = $('#thCDI').text();
		aTextCDI += ' - <button type="button" class="btn btn-xs btn-danger" title="' + countCostDeliv + '"><i class="fa fa-exclamation-circle"></i></button> (' + countCostDeliv + ')';
		$('#thCDI').html(aTextCDI);
	}

	if (countCostOut > 0) {
		var aTextCO = $('#thCO').text();
		aTextCO += ' - <button type="button" class="btn btn-xs btn-danger" title="' + countCostOut + '"><i class="fa fa-exclamation-circle"></i></button> (' + countCostOut + ')';
		$('#thCO').html(aTextCO);
	}

	if (countCostIn > 0) {
		var aTextCI = $('#thCI').text();
		aTextCI += ' - <button type="button" class="btn btn-xs btn-danger" title="' + countCostIn + '"><i class="fa fa-exclamation-circle"></i></button> (' + countCostIn + ')';
		$('#thCI').html(aTextCI);
	}

	if (countCostAgent > 0) {
		var aTextTA = $('#thTA').text();
		aTextTA += ' - <button type="button" class="btn btn-xs btn-danger" title="' + countCostAgent + '"><i class="fa fa-exclamation-circle"></i></button> (' + countCostAgent + ')';
		$('#thTA').html(aTextTA);
	}
    
}