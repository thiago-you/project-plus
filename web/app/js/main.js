// eventos globais
$(document).ready(function() {	
	// criar o datetime
	timeClock();
	
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
	
	// confirm para o delete
	$('body').on('click', '.btn-delete', function(e) {
		e.preventDefault();
		this.blur();
		const href = this.href;
		
		// mensagem de confirmação
        $.confirm({
            content: 'Você deseja mesmo exclúir este item?',
            backgroundDismiss: true,
            buttons: {
				ok: { 
					action: function() {
						window.location.href = href;
                    },
				},
			},
        });
        
        return false;
	});
	
	// realiza a navegação do menu
	$('body').on('click', '.sidenav-content ul li.section-item > a', function(e) {
		e.preventDefault();
		
		if (!$(this).siblings('ul').is(':visible')) {
			$(this).addClass('active').siblings('ul').slideDown();
		} else {
			$(this).removeClass('active').siblings('ul').slideUp();
		}
		
		return false;
	});
});

// cria o relogio no rodape do menu lateral
function timeClock() {
    // instancia o objeto de data
	const today = new Date();
    
	// pega cada propriedade da data separada
    let day = today.getDate();
    let month = today.getMonth() + 1;
    const year = today.getFullYear();
    const hour = today.getHours();
    const minute = today.getMinutes();

    // valida o dia e o mes
    if (day < 10) {
    	day = '0'+day;
    }
    if (month < 10) {
    	month = '0'+month;
    }

    // seta a data
    $('.datetime-clock').text(`${day}/${month}/${year} ${hour}:${minute}`);
    
    // atualiza o datetime a cada 10 segundos
    // e chama o propio callback novamente
	setTimeout(function() {
		timeClock(); 
	}, 10000);
}