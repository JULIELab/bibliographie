var bibliographie_charmap_field = null;
var bibliographie_charmap_last_selection = null;

function bibliographie_charmap_insert_char (substituteChar) {
	var firstPart = $(bibliographie_charmap_field).val().slice(0, bibliographie_charmap_field.selectionStart);
	var secondPart = $(bibliographie_charmap_field).val().slice(bibliographie_charmap_field.selectionEnd);
	$(bibliographie_charmap_field).val(firstPart+substituteChar+secondPart).focus();
}

(function($){
	$.fn.charmap = function () {
		if(bibliographie_charmap_last_selection != null)
			$(bibliographie_charmap_last_selection).unbind('focus click keyup');

		var element = $(this).first()[0];
		$(element.form).prev().before('<a href="javascript:;" style="float: right;" onclick="$(\'#bibliographie_charmap\').show(); $(\'#'+$(element).attr('id')+'\').focus();"><span class="silk-icon silk-icon-keyboard"></span> Show charmap</a>');

		$(this).bind('focus', function (event) {
			if($(event.target).is('input[type=submit]') == false
				&& $(event.target).is('input[type=checkbox]') == false
				&& $('#bibliographie_charmap_pinner').is(':checked') == false
				&& $('#bibliographie_charmap').is(':visible') == true){
				var offsetParent = event.target;
				var offsetLeft = event.target.offsetLeft;
				var offsetTop = event.target.offsetTop;

				if(offsetParent.offsetParent != document.getElementsByTagName('body')[0]){
					do {
						offsetParent = offsetParent.offsetParent;
						offsetLeft = offsetLeft + offsetParent.offsetLeft;
						offsetTop = offsetTop + offsetParent.offsetTop;
					} while (document.getElementsByTagName('body')[0] != offsetParent.offsetParent);
				}

				bibliographie_charmap_field = event.target;
				$('#bibliographie_charmap')
					.animate({'top': offsetTop + event.target.offsetHeight + 10, 'left': offsetLeft + Math.ceil(event.target.offsetWidth / 2)});
			}
			$('#bibliographie_charmap').dodge();
	  });
	  bibliographie_charmap_last_selection = this;

	  return this;
  };
})(jQuery);