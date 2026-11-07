
if( $('#report').length )
{	
	var aTextCPI = $('#thCPI').text();
	aTextCPI += ' - <button type="button" class="btn btn-xs btn-danger" title="'+countCostPick+'"><i class="fa fa-exclamation-circle"></i></button> ('+countCostPick+')';
	$('#thCPI').html(aTextCPI);
	
	var aTextCDI = $('#thCDI').text();
	aTextCDI += ' - <button type="button" class="btn btn-xs btn-danger" title="'+countCostDeliv+'"><i class="fa fa-exclamation-circle"></i></button> ('+countCostDeliv+')';
	$('#thCDI').html(aTextCDI);
	
	var aTextCO = $('#thCO').text();
	aTextCO += ' - <button type="button" class="btn btn-xs btn-danger" title="'+countCostOut+'"><i class="fa fa-exclamation-circle"></i></button> ('+countCostOut+')';
	$('#thCO').html(aTextCO);
	
	var aTextCI = $('#thCI').text();
	aTextCI += ' - <button type="button" class="btn btn-xs btn-danger" title="'+countCostIn+'"><i class="fa fa-exclamation-circle"></i></button> ('+countCostIn+')';
	$('#thCI').html(aTextCI);
	
	var aTextTA = $('#thTA').text();
	aTextTA += ' - <button type="button" class="btn btn-xs btn-danger" title="'+countCostAgent+'"><i class="fa fa-exclamation-circle"></i></button> ('+countCostAgent+')';
	$('#thTA').html(aTextTA);
    
}