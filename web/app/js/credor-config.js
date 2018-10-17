$(document).ready(function() {	
    // insere a animação quando a modal for dechada
    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('.modal-body').html('<br><br><h1 class="text-center text-primary"><i class="fa fa-spinner fa-pulse"></i>&nbsp; Carregando</h1><br><br>');
    });

    // abre a modal de nova campanha
    $('body').on('click', '#btn-nova-campanha', function() {
        // seleciona a modal
        let modal = $('#modal-campanha');        

        // exibe a modal
        modal.modal('show');

        // envia o post para renderizar o form
        $.post(BASE_PATH + 'credor-campanha/create', function(response) {
            modal.find('.modal-body').html(response).find('form input#credorcampanha-id_credor').val($('#credor-id').val());
        });
    });

    // abre a modal de edicao da campanha
    $('body').on('click', '#btn-editar-campanha', function() {
        // seleciona a modal
        let modal = $('#modal-campanha');        

        // exibe a modal
        modal.modal('show');

        // envia o post para renderizar o form
        $.post(BASE_PATH + 'credor-campanha/update?id='+$('#credor-id_campanha').val(), function(response) {
            modal.find('.modal-body').html(response);
        });
    });

    // abre a modal de visualizacao da campanha
    $('body').on('click', '#btn-view-campanha', function() {
        // seleciona a modal
        let modal = $('#modal-campanha');        

        // exibe a modal
        modal.modal('show');

        // envia o post para renderizar o form
        $.post(BASE_PATH + 'credor-campanha/view?id='+$('#credor-id_campanha').val(), function(result) {
            modal.find('.modal-body').html(result);
        });
    });

    // exclui uma campanha
    $('body').on('click', '#btn-deletar-campanha', function() {
        this.blur();
        let campanhaId = $('#credor-id_campanha').val();

        // mensagem de confirmação
        $.confirm({
            content: 'Você deseja mesmo exclúir este item?',
            backgroundDismiss: true,
            buttons: {
				ok: { 
					action: function() {
                        $.post(BASE_PATH + 'credor-campanha/delete?id='+campanhaId, function(response) {
                            let data = JSON.parse(response);

                            // verifica se a campanha foi deletada
                            if (data.success == true) {
                                toastr.success('A campanha foi deletada com sucesso.');
                                $('#credor-id_campanha').val('').trigger('change').find('option[value='+campanhaId+']').remove();
                            } else {
                                toastr.error('Não foi possível excluír a campanha. Por favor, tente novamente mais tarde.');
                            }
                        }).fail(function() {
                            toastr.error('Não foi possível excluír a campanha. Por favor, tente novamente mais tarde.');
                        });
                    },
				},
			},
        });
    });

    // limita o desconto máximo para 100%
    $('body').on('change', '.max-desconto', function() {
    	if (parseFloat(this.value.replace('%', '')) > 100.0000) {
			$(this).val('100.0000%').trigger('change');
		}		
		return false;
    });
    
    // evento quando o select de campanha for alterado
    $('body').on('change', '#credor-id_campanha', function() {
        if (this.value != '') {
            $('.campanha-action-button').prop('disabled', false);
        } else {
            $('.campanha-action-button').prop('disabled', true);
        }    

        // monta os params
        let postData = {
            'id_credor': $('#credor-id').val(),
            'id_campanha': $('#credor-id_campanha').val(),
        };

        // envia a requisicao para atualizar a lista
        $('#credor-id_campanha').prop('disabled', true);
        $.post(BASE_PATH + 'credor/update-campanha', postData, function(response) {
            let data = JSON.parse(response);
            if (data.success == false) {
                toastr.error('Não foi possível atualizar o credor. Por favor, tente novamente mais tarde.');
            }

            // exibe animação de carregamento
            $('#lista-faixas').html('<br><br><h1 class="text-primary text-center"><i class="fa fa-spinner fa-spin"></i>&nbsp; Carregando...</h1><br><br>');

            // renderiza a lista de faixas
            $.get(BASE_PATH + 'credor-calculo/index?id='+$('#credor-id_campanha').val(), function(response) {
                $('#lista-faixas').html(response);
            });
        }).fail(function() {
            toastr.error('Não foi possível atualizar o credor. Por favor, tente novamente mais tarde.');
        }).always(function() {
            $('#credor-id_campanha').prop('disabled', false);
        });    
    });

    // registra a campanha por ajax
	$('body').on('submit', '#form-campanha', function(e) {
        e.preventDefault();			

        // pega a lista e o id            
        let action = $('#form-campanha').attr('action');
        let params = $('#form-campanha').serializeArray();

        // envia a requisicao para cadastrar a campanha
        $.post(action, params, function(response) {
            let data = JSON.parse(response);
            
            if (data.success == true) {
                if (data.newRecord == true) {
                    // seta a option no select
                    $('#credor-id_campanha').append('<option value="'+data.id+'">'+data.nome+'</option>').val(data.id).trigger('change');    
                } else {
                    // pega a option do select
                    let option = $('#credor-id_campanha').find('option[value='+data.id+']');
                    // altera o nome da option
                    option.text(data.nome);
                }

                // fecha a modal
                $('#modal-campanha').modal('hide');
                // exibe a mensagem de sucesso
                toastr.success('A campanha foi cadastrada com sucesso.');  
            } else {
                toastr.error('Não foi possível adicionar a campanha. Por favor, tente novamente mais tarde.');
            }
        }).fail(function() {
            toastr.error('Não foi possível adicionar a campanha. Por favor, tente novamente mais tarde.');
        });

        return false;
	});

    // cadastra uma nova faixa de calculo
    $('body').on('click', '#nova-faixa', function() {
        // busca e exibe a modal
        let modal = $('#modal-faixa-calculo'); 
        modal.modal('show');       
        
        // exibe a modal
        $.post(BASE_PATH + 'credor-calculo/create', function(response) {
            modal.find('.modal-body').html(response).find('form input#credorcalculo-id_campanha').val($('#credor-id_campanha').val());
        }).done(function() {
        	// carrega os plugins de mascara
        	$('.maskmoney-input').maskMoney({
        		'suffix': '%',
        		'decimal': '.',
        		'precision': 4,
        	});        	
        });
    });

    // registra a faixa de calculo por ajax
	$('body').on('submit', '#form-calculo', function(e) {
        e.preventDefault();			

        // pega os values de deconto para validar
        const descEncargos = $('#credorcalculo-desc_encagos_max').val() || '';
        const descPrincipal = $('#credorcalculo-desc_principal_max').val() || '';
        const descHonorario = $('#credorcalculo-desc_honorario_max').val() || '';
        
        // valida os descontos máximos
        if (descEncargos.length > 0 && parseFloat(descEncargos.replace('%', '')) > 100.0000) {
			$('#credorcalculo-desc_encagos_max').val('100.0000%').trigger('change');
		}
        if (descPrincipal.length > 0 && parseFloat(descPrincipal.replace('%', '')) > 100.0000) {
			$('#credorcalculo-desc_principal_max').val('100.0000%').trigger('change');
		}
        if (descHonorario.length > 0 && parseFloat(descHonorario.replace('%', '')) > 100.0000) {
			$('#credorcalculo-desc_honorario_max').val('100.0000%').trigger('change');
		}

        // pega os params e a action           
        let action = $('#form-calculo').attr('action');
        let params = $('#form-calculo').serializeArray();
        
        // envia a requisicao para cadastrar a campanha
        $.post(action, params, function(response) {
            let data = JSON.parse(response);
            
            if (data.success == true) {
                // fecha a modal
                $('#modal-faixa-calculo').modal('hide');

                // exibe animação de carregamento
                $('#lista-faixas').html('<br><br><h1 class="text-primary text-center"><i class="fa fa-spinner fa-spin"></i>&nbsp; Carregando...</h1><br><br>');

                // renderiza a lista de faixas
                $.get(BASE_PATH + 'credor-calculo/index?id='+$('#credor-id_campanha').val(), function(response) {
                    $('#lista-faixas').html(response);
                });
            } else {
                toastr.error('Não foi possível adicionar a faixa de cálculo. Por favor, tente novamente mais tarde.');
            }
        }).fail(function() {
            toastr.error('Não foi possível adicionar a faixa de cálculo. Por favor, tente novamente mais tarde.');
        });

        return false;
	});

    // edita uma faixa de cálculo
    $('body').on('click', '.editar-faixa', function() {
        let id = $(this).closest('tr').attr('id');

        // busca e exibe a modal
        let modal = $('#modal-faixa-calculo'); 
        modal.modal('show');       
        
        // exibe a modal
        $.post(BASE_PATH + 'credor-calculo/update?id='+id, function(response) {
            modal.find('.modal-body').html(response);
        }).done(function() {
        	// carrega os plugins de mascara
        	$('.maskmoney-input').maskMoney({
        		'suffix': '%',
        		'decimal': '.',
        		'precision': 4,
        	}).trigger('focus');        	
        });
    });

    // deleta uma faixa de cálculo
    $('body').on('click', '.excluir-faixa', function() {
        let id = $(this).closest('tr').attr('id');

        // mensagem de confirmação
        $.confirm({
            content: 'Você deseja mesmo exclúir este item?',
            backgroundDismiss: true,
            buttons: {
				ok: { 
					action: function() {
                        // deleta a faixa
                        $.post(BASE_PATH + 'credor-calculo/delete?id='+id, function(response) {
                            let data = JSON.parse(response);

                            // verifica se a campanha foi deletada
                            if (data.success == true) {
                                // renderiza a lista de faixas
                                $.get(BASE_PATH + 'credor-calculo/index?id='+$('#credor-id_campanha').val(), function(response) {
                                    $('#lista-faixas').html(response);
                                });
                            } else {
                                toastr.error('Não foi possível excluír a faixa de cálculo. Por favor, tente novamente mais tarde.');
                            }
                        }).fail(function() {
                            toastr.error('Não foi possível excluír a faixa de cálculo. Por favor, tente novamente mais tarde.');
                        });
                    },
				},
			},
        });
    });
});