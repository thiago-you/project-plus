// lista de estados
const listaEstados = ''
+ '<option value="1">Acre</option>'
+ '<option value="2">Alagoas</option>'
+ '<option value="3">Amazonas</option>'
+ '<option value="4">Amapá</option>'
+ '<option value="5">Bahia</option>'
+ '<option value="6">Ceará</option>'
+ '<option value="7">Distrito Federal</option>'
+ '<option value="8">Espírito Santo</option>'
+ '<option value="9">Goiás</option>'
+ '<option value="10">Maranhão</option>'
+ '<option value="11">Minas Gerais</option>'
+ '<option value="12">Mato Grosso do Sul</option>'
+ '<option value="13">Mato Grosso</option>'
+ '<option value="14">Pará</option>'
+ '<option value="15">Paraíba</option>'
+ '<option value="16">Pernambuco</option>'
+ '<option value="17">Piauí</option>'
+ '<option value="18">Paraná</option>'
+ '<option value="19">Rio de Janeiro</option>'
+ '<option value="20">Rio Grande do Norte</option>'
+ '<option value="21">Rondônia</option>'
+ '<option value="22">Roraima</option>'
+ '<option value="23">Rio Grande do Sul</option>'
+ '<option value="24">Santa Catarina</option>'
+ '<option value="25">Sergipe</option>'
+ '<option value="26">São Paulo</option>'
+ '<option value="27">Tocantins</option>'; 

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
});