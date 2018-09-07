// eventos globais
$(document).ready(function() {
	// incializa os plugins
	$(function () {
		// inicializa o confirm
		jconfirm.defaults = {
			title: '<b class="text-danger"><i class="fa fa-exclamation-triangle"></i>&nbsp; Atenção<b>',
			typeAnimated: true,
			draggable: true,
			content: '',
			buttons: {},
			defaultButtons: {},
			buttons: {
				ok: { 
					text: '<i class="fa fa-check fa-fw"></i>',
					btnClass: 'btn-primary btn-flat',
				},
				close: { 
					text: '<i class="fa fa-times fa-fw"></i>',
					btnClass: 'btn-danger btn-flat',
				},
			},
		};
		
		// inicializa o tooltip
		  $('[data-toggle="tooltip"]').tooltip();
	});
});