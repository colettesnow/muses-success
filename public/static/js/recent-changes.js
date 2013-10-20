function vote(change_id, option)
{
	$('#vote-' + change_id).html('<img src="/static/images/loading.gif" height="16" width="16" alt="Rating..." />');	
	
	$.post('/recent-changes/vote', { cid: change_id, opt: option }, function(data) {
			$('#vote-' + change_id).html('Voted');				
		}
	)	
}
