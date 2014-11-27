$(function(){
	$('body').on('click', 'form.ajax :submit', function(event) {
		event.preventDefault();
		$(this).ajaxSubmit();
		return false;
	});
	
	$('body').on('click', 'a.ajax', function(event) {
			event.preventDefault();
			if ($.active) return;

			$.ajax({
					type: 'POST',
					url: $.nette.href = this.href,
					success: $.nette.success
			});
	});

});