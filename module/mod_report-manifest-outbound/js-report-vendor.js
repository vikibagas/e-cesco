$(function () {

	$('#tambahkoli_plus').click(function () {
				

				var collie   = $('#collie').val();
				
				

				if (collie == 10){
					alert ('maaf sudah maksimal');
				}
				else{

					
					$('#barisdetail').val(total);


					
					for (i = 2; i < 11; i++) {
					  $('#barisdetail'+i).hide();
					//  alert(i);

					}
					//var koli = 0;
					//var jumlah_detailbarang =  parseInt($('#jumlah_detailbarang').val())+1;

					koli = collie+1;

					//alert(collie+'koli ='+koli);

					for (i = 2; i <= koli; i++) {
					  $('#barisdetail'+i).show();
					//  alert(i);

					}

				//$('.d_koli_ke').attr('max',collie);


				}
				
			});

});