var bibliographie_request_timeouts = Array();
var bibliographie_loading = 0;

var bibliographie_request_delay = 500;
var bibliographie_ajax_timeout = null;

var bibliographie_editor_is_dirty = false;

function delayRequest (functionName, params) {
	if(bibliographie_request_timeouts[functionName] != null){
		// clear existing timeout to prevent not wanted queries
		clearTimeout(bibliographie_request_timeouts[functionName]);
		bibliographie_request_timeouts[functionName] = null;
	}

	var call = functionName+'(';
	for(var i = 0; i <= params.length - 1; i++){
		if(i != 0)
			call += ', ';

		call += '"'+params[i]+'"';
	}
	call += ')';

	bibliographie_request_timeouts[functionName] = setTimeout(call, bibliographie_request_delay);
}

function setLoading (selector) {
	$(selector).html('<span><img src="'+bibliographie_web_root+'/resources/images/loading.gif" alt="loading"> loading</span>')
}

function bibliographie_ajax_block_ui () {
	$.blockUI({'message': '<img src="'+bibliographie_web_root+'/resources/images/loading.gif" /> <strong>Server seems to be busy.</strong><br /><em>Please give it a moment and wait for the request to finish!</em>'});
}