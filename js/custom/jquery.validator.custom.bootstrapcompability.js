/*
 * Used to Make jQuery Validation compateble with bootstrap
 * Shamelessly stolen from http://stackoverflow.com/questions/18754020/bootstrap-3-with-jquery-validation-plugin
 * //Anton Roslund
 */
$.validator.setDefaults({
	highlight: function(element) {
		$(element).closest('.form-group').addClass('has-error');
	},
	unhighlight: function(element) {
		$(element).closest('.form-group').removeClass('has-error');
	},
	errorElement: 'span',
	errorClass: 'help-block',
	errorPlacement: function(error, element) {
		if(element.parent('.input-group').length) {
			error.insertAfter(element.parent());
		} else {
			error.insertAfter(element);
		}
	}
});