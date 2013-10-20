$(document).ready(function() {

	$("#stars-wrapper1").stars();

	var i = 0;
	
	$("#stars-wrapper1").stars({
	    callback: function(ui, type, value){
		
			var listing_id = $('#listing_id').val();
		
			$.post('http://muses-success.info/api/submit-rating', { listing_id: listing_id, value: value }, function(data) 
			{
					if (i == 0)
					{
						i = 1;
						$("#stars-wrapper1").before('<p class="rating_success">' + data + '</p>');
					} else {
						$(".rating_success").text(data);						
					}
				});
	    }
	});	
	
})