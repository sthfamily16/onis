/**
 * Show custom errors with bootstrap toast
 * 
 * @method fn_showError
 * @param string strMsg Error message 
 *
 * @author Hugo Roldan
 * @since 2022-10-21
 */
function fn_showError(strMsg)
{
	var intId = 't' +Date.now();

	$str = '<div id="'+ intId +'" class="toast" role="alert" aria-live="assertive" aria-atomic="true">'+
		'<div class="toast-header">'+
			'<img src="" class="rounded me-2" alt="">'+
			'<strong class="me-auto">Bootstrap</strong>'+
			'<small class="text-muted">just now</small>'+
			'<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>'+
		'</div>'+
		'<div class="toast-body">'+ strMsg +'</div>'+
	'</div>';

	$('.toast-container').append($str);

	new bootstrap.Toast($('#'+intId));
}
