/**
 * Write table body with custom values (dynamic)
 * 
 * @method fn_wrRsTblBody
 * @param object obj With the data has been grouped
 * @param array arrKeys Array with headers
 * @return string strHtml Data in HTML format
 *
 * @author Hugo Roldan
 * @since 2022-10-21
 */
function fn_wrRsTblBody(obj, arrKeys)
{
	var strHtml = '';

	for(var idx in arrKeys)
	{
		var strKey = arrKeys[idx];

		strHtml += '<td>'+ obj[strKey] +'</td>';
	}

	return strHtml;
}

/**
 * Write table headers with custom values (dynamic)
 * 
 * @method fn_wrRsTblHeaders
 * @param array arrKeys Array with headers
 * @return string strHtml Data in HTML format
 *
 * @author Hugo Roldan
 * @since 2022-10-21
 */
function fn_wrRsTblHeaders(arrKeys)
{
	var strHtml = '';

	for(var idx in arrKeys) strHtml += '<th>'+ arrKeys[idx] +'</th>';

	return strHtml;
}

/**
 * Write table headers with custom values (dynamic)
 * 
 * @method getRsTblHeaders
 * @param object obj Data file object
 * @return array arrKeys Array with headers
 *
 * @author Hugo Roldan
 * @since 2022-10-21
 */
function getRsTblHeaders(obj)
{
	var arrKeys = [];

	for(var idx in obj)
	{
		arrKeys = Object.keys(obj[idx]);

		break;
	}

	return arrKeys;
}

/**
 * Write data table
 * 
 * @method fn_wrRsTbl
 * @param object obj Data file object
 *
 * @author Hugo Roldan
 * @since 2022-10-21
 */
function fn_wrRsTbl(obj)
{
	var strHtml = '<h5>Results</h5>';

	for(var idx in obj)
	{
		var arrKeys = getRsTblHeaders(obj[idx]);

		strHtml += '<table class="table table-striped table-hover table-sm table-responsive">'+
		'<thead>'+
			'<tr>'+
				'<th>'+ idx +'</th>'+
				fn_wrRsTblHeaders(arrKeys) +
			'</tr>'+
		'</thead>'+
		'<tbody class="table-group-divider">';

		for(idj in obj[idx])
		{
			strHtml += '<tr>'+
				'<td>'+ idj +'</td>'+
				fn_wrRsTblBody(obj[idx][idj], arrKeys) +
			'</tr>';
		}

		strHtml += '</tbody>'+
		'</table>';
	}
	
	$('#data-table').html(strHtml);
}

// Documents ready
$(function(){
	
	/**
	 * Upload file to be process
	 *
	 * @author Hugo Roldan
	 * @since 2022-10-21
	 */
	$('#btn-process').click(function(){

		var strFileName = $('#file-data').val().trim().toLowerCase();

		if(strFileName.indexOf('.csv') == -1)
		{
			fn_showError('Invalid file');

			return;
		}

		var objForm = new FormData();
        objForm.append('file', $('#file-data')[0].files[0]);
        objForm.append('type', $('#slt-type').val());

		$.ajax({
			url: "/home/process",
			type: "post",
			dataType: "json",
			data: objForm,
			cache: false,
			contentType: false,
			processData: false
		})
		.done(function(objRs){
			
			if(objRs.error != '') fn_showError(objRs.error);
			else fn_wrRsTbl(objRs.data);

		}).fail( function(){
		    fn_showError('Has been an error on request, try again please');
		});
	});
});