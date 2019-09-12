$(document).ready(function() {
	grecaptcha.ready(function() {
	$("#stars-wrapper1").stars();

	var i = 0;
	var gReToken;

	$("#stars-wrapper1").stars({
	    callback: function(ui, type, value){
		
			var listing_id = $('#listing_id').val();
	
			grecaptcha.execute('6Lc2T7cUAAAAADHta_Pz6Bixv16GxBM6AbDpM0tO', {action: 'rating'}).then(function(token) {
				$.post('https://muses-success.info/api/submit-rating', { listing_id: listing_id, value: value, token: token }, function(data) 
				{
					if (i == 0)
					{
						i = 1;
						$("#stars-wrapper1").before('<p class="rating_success">' + data + '</p>');
					} else {
						$(".rating_success").text(data);						
					}
				});
				console.log(token);
			 });
			

	    }
	});	
});
})