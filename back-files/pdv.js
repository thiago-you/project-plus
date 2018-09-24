/**
 * JS para a tela de PDV /pdv/new/<id>
 * 
 * <funcoes-globais-utilizadas>
 * app/main.js functions => [
 * 	   validarDocumento(docuemnto, tipoDoc) => valida um documento cpf/cnpj
 *     getHtmlSenhaMaster(buttonId) => renderiza o html para pedir a senha master
 * ]
 */
$(document).ready(function() {
	/**
	 * Navegação ágil do PDV
	 */
	$('body').on('keypress keydown', function(e) {
		// captura e altera o comportamento das teclas F1, F2 e F3
		if (e.keyCode >= 112 && e.keyCode <= 114) {
			e.preventDefault();
			
			// seta a funcionalidade de cada tecla
			// as funcionalidades são diferentes se a modal de pagamento estiver aberta
			switch (e.keyCode) {
				case 112: // F1
					// se a modal estiver aberta ativa o click para concluir a venda
					// se nao, ativa o click para abrir a modal de pagamento
					if ($('#mbPagamento').is(':visible')) {
						if (!$('#btnConcluirVenda').is(':disabled')) {
							$('#btnConcluirVenda').trigger('click');
						}		
					} else {						
						$('#btnPagamento').trigger('click');
					}
					break;
				case 113: // F2
					// se a modal estiver aberta ativa ou seta o foco no botao de forma de pagamento
					// senao, seta o focu no campo de cpf do cliente
					if ($('#mbPagamento').is(':visible')) {
						if ($('.btn-forma-pagamento').length == 1) {
							$('.btn-forma-pagamento').trigger('click');
						} else {						
							$('.btn-forma-pagamento:first').focus();
						}
					} else {						
						$('#produto_pesquisa').focus();
					}
					break;
				case 114: // F3
					// se a modal estiver aberta seta o foco no campo de desconto
					// senao, retorna o foco para o campo de pesquisa
					if ($('#mbPagamento').is(':visible')) {
						$('#pagamento-desconto').focus();
					} else {						
						$('#pedido-cpf').focus();
					}
					break;
			}
						
			return false;
		}
	});
	
	// quandor apertar tab dentro do campo de desconto
	// seta o foco no campo do tipo de desconto
	$('body').on('keypress', '#pagamento-desconto', function(e) {
		if (e.keyCode == 9) {
			e.preventDefault();
			$('#pedido-tipo-desconto').focus();
			return false;
		}
	});
	
	// quandor apertar tab dentro do campo CPF/CNPJ
	// seta o foco no campo do tipo do cliente
	$('body').on('keypress', '#pedido-cpf', function(e) {
		if (e.keyCode == 9) {
			e.preventDefault();
			$('#cprf').select2('focus');
			return false;
		}
	});
	
	// realiza a navegação entre as formas de pagamento
	// pelas setas do teclado
	$('body').on('keypress', '.btn-forma-pagamento', function(e) {		
		if (e.keyCode >= 37 && e.keyCode <= 40) {
			e.preventDefault();
			// busca o elemento
			let element = $(this).closest('div');
			let posicao = parseInt(element.data('posicao')) | 0;
			
			switch (e.keyCode) {
				case 37: // left		
					element.closest('.row').find('div[data-posicao="'+(posicao-1)+'"] button').focus();
					break;
				case 38: // up
					element.closest('.row').find('div[data-posicao="'+(posicao-2)+'"] button').focus();
					break;
				case 39: // right
					element.closest('.row').find('div[data-posicao="'+(posicao+1)+'"] button').focus();
					break;
				case 40: // down
					element.closest('.row').find('div[data-posicao="'+(posicao+2)+'"] button').focus();
					break;
			}
				
			return false;
		}
	});
	
	// Quando a modal for fechada seta o focus na barra de pesquisa
	$('#mbPagamento').on('hidden.bs.modal', function() {
		$('#produto_pesquisa').focus();
	});
	
	// -------------------------------------------------
	// CADASTRO DE CLIENTE
	// -------------------------------------------------
	// Abre e fecha modal de cadastro de cliente
    $('#cadastrarCliente').on('click', function() {
    	$.ajax({
			method: 'GET',
			url: BASE_PATH + 'clientes/create-basic?tipo=basico',
			beforeSend: function() {
				$('#modalCadastrarCliente').find('.modalContent').html('<div class="box box-primary"><div class="box-body"><h3 class="text-center text-primary"><i class="fa fa-spinner fa-spin"></i> Aguarde ...</h3></div></div>');
				$('#modalCadastrarCliente').modal('show');
			},
			success: function(data) {
				$('#modalCadastrarCliente').find('.modalContent').html(data);
			},
    	});
    });

    $('#btnConfirmarCadastroCliente').on('click', function() {
    	var cliente;
    	if($('#clientes-tipo').val() == 1) {
    		setMaskClienteDoc(0, false)
    		$('#pedido-cpf').val($('#clientes-cpf').val());
    		cliente = {
				Clientes:{
					cpf : $('#clientes-cpf').val(),
					rg : $('#clientes-rg').val(),
					nome : $('#clientes_nome').val(),
					apelido : $('#clientes-apelido').val(),
					dtanascto : $('#clientes-dtanascto').val(),
					sexo : $('#clientes-sexo').val(),
					endereco : $('#clientes-endereco').val(),
					cep : $('#clientes-cep').val(),
					numero : $('#clientes-numero').val(),
					id_estado : $('#clientes-id_estado').val(),
					id_cidade : $('#clientes-id_cidade').val(),
					complemento : $('#clientes-complemento').val(),
					bairro : $('#clientes-bairro').val(),
					fone : $('#clientes-fone').val(),
					fone_celular : $('#clientes-fone_celular').val(),
					email : $('#clientes-email').val(),
					tipo : $('#clientes-tipo').val(),
					ativo : 1,
				}
    		}
    	} else if ($('#clientes-tipo').val() == 2) {
    		setMaskClienteDoc(1, false)
    		$('#pedido-cpf').val($('#clientes-cnpj').val());
    		cliente = {
				Clientes:{
					cnpj : $('#clientes-cnpj').val(),
					iest : $('#clientes-iest').val(),
					indie : $('#clientes-indie').val(),
					nome : $('#clientes-razao_social').val(),
					apelido : $('#clientes-fantasia').val(),
					endereco : $('#clientes-endereco').val(),
					cep : $('#clientes-cep').val(),
					numero : $('#clientes-numero').val(),
					id_estado : $('#clientes-id_estado').val(),
					id_cidade : $('#clientes-id_cidade').val(),
					complemento : $('#clientes-complemento').val(),
					bairro : $('#clientes-bairro').val(),
					fone : $('#clientes-fone').val(),
					fone_celular : $('#clientes-fone_celular').val(),
					email : $('#clientes-email').val(),
					tipo : $('#clientes-tipo').val(),
					ativo : 1,
				}
    		}
    	}

    	cadastrarCliente(cliente);
    });
	//---------------------------------------------------------------------
	// Fim - Cadastrar Cliente
	// --------------------------------------------------------------------
    //---------------------------------------------------------------------
    // Selecao de CPF/CNPJ
    //---------------------------------------------------------------------
    //onLoad
    setMaskClienteDoc($('#cprf').val(), false);
    
    //Combo Tipo do Cliente (PF/PJ)
    $('#cprf').on('change', function() {
    	setMaskClienteDoc($(this).val(), true);
    });
    //---------------------------------------------------------------------
    // Fim - Selecao de CPF/CNPJ
    //---------------------------------------------------------------------    
    
	//Set Defaults
    toastr.options = {
		'progressBar': false,
		'timeOut': '2000',
		'preventDuplicates': true,
	};
    
    //maskMoney
    $("#produto-total").maskMoney({prefix:'R$', allowNegative: false, thousands:'.', decimal:',', affixesStay: true, precision: 2});
    $("#produto-valor").maskMoney({prefix:'R$', allowNegative: false, thousands:'.', decimal:',', affixesStay: true, precision: 2});
    $("#produto-desconto").maskMoney({prefix:'', allowNegative: false, thousands:'.', decimal:',', affixesStay: true, precision: 2});
    $('#pagamento-desconto').maskMoney({prefix:'', allowNegative: false, thousands:'.', decimal:',', affixesStay: true, precision: 2});

    //AutoNumeric
    $('#produto-quantidade').autoNumeric('init', {aSep: '.', aDec: ',', mDec: '3'});

    // ---------------------------------------------------------------------
    // Inicío - Form Produto
    // ---------------------------------------------------------------------

    //Seleciona o valor do campo ao setar o foco sobre ele
    $('#produto-valor').on('focus', function() {
    	$(this).select();
    });
    $('#produto-quantidade').on('focus', function() {
    	$(this).select();
    });
    $('#produto-desconto').on('focus', function() {
    	$(this).select();
    });

    //Seleciona o valor do campo ao clicar sobre ele
    $('#produto-valor').on('click', function() {
    	$(this).select();
    });
    $('#produto-quantidade').on('click', function() {
    	$(this).select();
    });
    $('#produto-desconto').on('click', function() {
    	$(this).select();
    });

    //Calcula os totais ao alterar os campos
    $('#produto-valor').on('change', function() {
    	Produto_calculaTotaisForm();
    });
    $('#produto-quantidade').on('change', function() {
    	Produto_calculaTotaisForm();
    });
    $('#produto-desconto').on('change', function() {
    	Produto_calculaTotaisForm();
    });
    $('#produto-tipo-desconto').on('change', function() {
    	Produto_calculaTotaisForm();
    });

    // utiliza o ENTER como TAB
    $('#produto-valor').keypress(function(e) {
    	if (e.which == 13) {
    		$('#produto-quantidade').focus();
    	}
    });
    $('#produto-quantidade').keypress(function(e) {
    	if(e.which == 13) {
    		$('#produto-desconto').focus();
    	}
    });
    $('#produto-desconto').keypress(function(e) {
    	$('#produto-desconto').trigger('change');

    	if(e.which == 13) {
    		if ($('#btnProdutoIncluir').css('display') != 'none'){
    			$('#btnProdutoIncluir').trigger('click');
    		}else {
    			$('#btnProdutoAlterar').trigger('click');
    		}
    	}
    });

    // ---------------------------------------------------------------------
    // Inicío - Listar Produtos
    // ---------------------------------------------------------------------

    // carrega a lista de subgrupos e lista todos os produtos do grupo escolhido
	$('body').on('change', '#grupo', function() {

		var grupoId = $(this).val();
		var subgrupo = $('#subgrupo');
		subgrupo.html('').attr('disabled', 'disabled');

		carregarProdutos();

		$.ajax({
			method: 'get',
			url: BASE_PATH + 'produto-sub-grupo/listar?id=' + grupoId,
			success: function(response) {

				var retorno = JSON.parse(response);

				if(retorno.success == 1) {
					subgrupo.html(retorno.data.subgrupos);
				}
			},
			complete: function() {
				subgrupo.removeAttr('disabled');
			}
		});
	});

	// pega o codigo ean do produto escolhido
	$('body').on('click', '.produtoRapido', function() {
		inserirProduto($(this));
	});

	// carrega os produtos do subgrupo escolhido
	$('body').on('change', '#subgrupo', function() {
		carregarProdutos();
	});

	// ordena a lista de produtos
	$('body').on('change', '#listaOrder', function() {
		carregarProdutos();
	});

	// carrega uma lista especifica de produtos
	$('body').on('change', '#listaProdutos', function() {
		carregarProdutos();
	});

	// função que carrega a lista de produtos
	function carregarProdutos() {

		var grupoId = $('#grupo').val();
		var subgrupoId = $('#subgrupo').val();
		var listaOrder = $('#listaOrder').val();
		var listaProdutos = $('#listaProdutos').val();

		$.ajax({
			method: 'post',
			url: BASE_PATH + 'pdv/listar',
			data: {
				'grupo': grupoId,
				'subgrupo': subgrupoId,
				'listaOrder': listaOrder,
				'listaProdutos': listaProdutos,
			},
			beforeSend: function() {
				$('#produtosList').html('<br/><br/><h3 class="text-center text-primary"><i class="fa fa-spinner fa-spin"></i> Carregando...</h3>');
			},
			success: function(response) {

				var retorno = JSON.parse(response);

				if(retorno.success == 1) {

					$('#produtosList').html(retorno.data);
					$('.countProdutos').html(retorno.count + ' Produtos Encontrados');

				}else if(retorno.success == 0) {

					toastr.warning(retorno.message);
					$('#produtosList').html('<br/><br/><h3 class="text-center text-default"><i class="fa fa-exclamation-triangle fa-3x"></i><br/></h3>');
					$('.countProdutos').html('');

				}
			}
		});
	}

	// utiliza a mesma função de adicionar já criada para a busca normal
	// enviando o ean como pesquisa
	function inserirProduto($btn) {

		var ean = $('#ean-' + $btn.attr('id')).val();
		var btn = $btn.html();

		$.ajax({
			method: 'get',
			url: BASE_PATH + 'produto/busca-ajax?q=' + ean,
			beforeSend: function() {
				$btn.html('<b class="text-center"><i class="fa fa-spinner fa-spin"></i> Processando...</b>');
			},
			success: function(response) {

				$btn.html(btn);

				var data = JSON.parse(response);

				if(data[0]) {
					Produto_setarNoForm(data[0], true);
				}else {
					toastr.error('Erro 500 - Se o erro persistir contate o administrador.');
				}
			}
		});
	}
	// ---------------------------------------------------------------------
    // FIM - Listar Produtos
    // ---------------------------------------------------------------------

    // ---------------------------------------------------------------------
    // AÇÕES - Form Produto
    // ---------------------------------------------------------------------
	$("#btnProdutoCancelar").click(function() {
		$("#pdv-form-produto").hide();
		$("#pdv-form-produto-vazio").show();

		//Seta o foco para a pesquisa
		$("#produto_pesquisa").focus();
	});

	$("#btnProdutoIncluir").click(function() {

		var dados = {
			id: $("#produto-id").val(),
			ean: $("#produto-ean").html(),
			descricao: $("#produto-descricao").html(),
			valor: $("#produto-valor").val(),
			quantidade: $("#produto-quantidade").val(),
			desconto: $("#produto-desconto").val(),
			subtotal: $("#produto-subtotal").html(),
			total: $("#produto-total").html(),
			tipoDesconto: $("#produto-tipo-desconto").val(),
		};

		if($('#senha-master_senha').length == 1) {
			dados.senha_master = $('#senha-master_senha').val();

			//Remove o confirm da tela
			$('#senha-master_senha').parent().parent().parent().parent().parent().parent().parent().parent().parent().parent().parent().remove();
		}

		Produto_setarNaVenda(dados, 0);
	});

	$("#btnProdutoAlterar").click(function() {

		var dados = {
			item_id: $("#pedido-item-id").val(),
			id: $("#produto-id").val(),
			ean: $("#produto-ean").html(),
			descricao: $("#produto-descricao").html(),
			valor: $("#produto-valor").val(),
			quantidade: $("#produto-quantidade").val(),
			desconto: $("#produto-desconto").val(),
			subtotal: $("#produto-subtotal").html(),
			total: $("#produto-total").html(),
			tipoDesconto: $("#produto-tipo-desconto").val(),
		};

		if($('#senha-master_senha').length == 1) {
			dados.senha_master = $('#senha-master_senha').val();

			//Remove o confirm da tela
			$('#senha-master_senha').parent().parent().parent().parent().parent().parent().parent().parent().parent().parent().parent().remove();
		}

		Produto_setarNaVenda(dados, $('#venda-item-num').val());
	});


	//Senha Master do Produto
    $('body').on('click', '#btnSenhaMasterProduto', function(e) {

		//Reenvia novamente o form agora com a senha master
    	if($('#btnProdutoIncluir').css('display') != 'none') {
    		$('#btnProdutoIncluir').trigger('click');
    	}else {
    		$('#btnProdutoAlterar').trigger('click');
    	}
    });
    // ---------------------------------------------------------------------
	// Fim - Form Produto
	// ---------------------------------------------------------------------

    // ---------------------------------------------------------------------
	// Início - Venda Itens
    // ---------------------------------------------------------------------
	$('.pdv-panel-itens').on('click', '.btnProdutoAlterar', function() {

		$('#linkBuscaProduto').tab('show');

		try {
			var objLinha = $(this).parent().parent();

			var dados = {
				numRow: objLinha.find(".item").html(),
				item_id: objLinha.find(".pedido-item-id").val(),
				id: objLinha.find(".venda-produto-id").val(),
				ean: objLinha.find(".venda-produto-ean").html(),
				descricao: objLinha.find(".venda-produto-descricao").html(),
				valor: objLinha.find(".venda-produto-valor").html(),
				quantidade: objLinha.find(".venda-produto-quantidade").html(),
				desconto: objLinha.find(".venda-produto-desconto").html(),
				subtotal: objLinha.find(".venda-produto-subtotal").val(),
				total: objLinha.find(".venda-produto-total").html(),
			};

			dados.desconto = accounting.formatMoney(accounting.unformat(dados.desconto, ','), '', 2, '.', ',');
			Produto_setarNoForm(dados, false);

		}catch(e) {
			toastr.error('Ocorreu um erro ao realizar a operação. ' + e);
		}
	});

	$('.pdv-panel-itens').on('click', '.btnProdutoRemover', function() {
		var objLinha = $(this).parent().parent();

		$.confirm({
			keyboardEnabled: true,
			title: '<b class="text-danger"><i class="fa fa-exclamation-triangle"></i>&nbsp; Atenção<b>',
			content: 'Deseja realmente remover este item?',
			confirmButton: 'Sim',
			cancelButton: 'Não',
			confirm: function() {

				try {
					//Exclui o produto do pedido e retorna o estoque reservado para o mesmo
	            	var dadosPost = {
            			Pedido: {id: $('#pedido-id').val()},
            			PedidoItens: {id: objLinha.find('.pedido-item-id').val()}
	            	};
	            	$.ajax({
	    				method: 'post',
	    				url: BASE_PATH + 'pedido/produto-remover',
	    				data: dadosPost,
	    				beforeSend: function() {
	    					toastr.warning('<i class="fa fa-spinner fa-pulse"></i> Removendo item. Por favor aguarde...');
	    				},
	    				success: function(data) {

	    					try {
	    						toastr.remove();

	    						var retorno = JSON.parse(data);
	    						if(retorno.success == 1) {
	    							Produto_removerDaVenda(objLinha);

	    							//Salva parcialmente o pedido, atualizando os valores
	    							var dadosPedido = {
    									Pedido: {
    										id: $('#pedido-id').val(),
    										itens_quantidade: $('#venda-total-itens').html(),
    										pedido_subtotal: accounting.unformat($('#venda-valor-subtotal').html(), ','),
    										pedido_tipo_desconto: 0, //%(1) ou R$(0)
    										pedido_desconto_valor: 0,
    										pedido_total: accounting.unformat($('#venda-total-geral').html(), ','),
    									}
	    							};

	    							Venda_salvarParcial(dadosPedido)
	    							.done(function(e) {

	    								try {
	    									var retorno = JSON.parse(e);
	    									if(retorno.success == 1) {
	    										toastr.success('PDV salvo com sucesso!');
	    									}else {
	    										throw (retorno.message != '' ? retorno.message : 'Erro interno do servidor');
	    									}

	    								}catch(error) {
	    									$.confirm({
	    										title: '<b class="text-danger"><i class="fa fa-exclamation-triangle"></i>&nbsp; Atenção<b>',
	    										content: 'Ocorreu um erro durante o processo: ' + error,
	    										confirmButton: 'Ok',
	    										cancelButton: false,
	    										animation: 'scale',
	    										backgroundDismiss: false,
	    										closeIcon: false,
	    									});
	    								}
	    				    		});

	    						}else {

	    							$.confirm({
	    								title: 'Não foi possível remover o produto',
	    								content: retorno.message,
	    								confirmButton: 'Ok',
	    								cancelButton: false,
	    								animation: 'scale',
	    								backgroundDismiss: false,
	    								closeIcon: false,
	    							});
	    						}

	    					}catch(e) {
	    						$.confirm({
	    							title: 'Não foi possível remover o produto',
	    							content: 'Erro interno no servidor',
	    							confirmButton: 'Ok',
	    							cancelButton: false,
	    							animation: 'scale',
	    							backgroundDismiss: false,
	    							closeIcon: false,
	    						});
	    					}
	    				}
	    			}).fail(function() {
	    				toastr.remove();

	    				$.confirm({
	    					title: 'Não foi possível remover o produto',
	    					content: 'Erro interno no servidor',
	    					confirmButton: 'Ok',
	    					cancelButton: false,
	    					animation: 'scale',
	    					backgroundDismiss: false,
	    					closeIcon: false,
	    				});
	    			});

				}catch(e) {
					toastr.error('Ocorreu um erro ao realizar a operação. ' + e);
				}
			}
		});
	});
	// ---------------------------------------------------------------------
	// Fim - Venda Itens
	// ---------------------------------------------------------------------

	// ---------------------------------------------------------------------
	// Início - Adiciona Cliente ao PDV
	// ---------------------------------------------------------------------

	//lista os clientes via Ajax
    $('#addCliente').click(function() {
		this.blur();
    	
		$.ajax({
			url: BASE_PATH + 'pdv/listar-clientes',
			success: function(response) {

			var retorno = $.parseJSON(response);

			if ($("#clientes").find('option').length) {

				$("#clientes").select2("destroy");
				$("#clientes").find('option').remove().end();

			}

			if(retorno.length > 0) {
				$("#clientes").select2({
					data: retorno,
					id: 'clientes',
					width: '100%',
					theme: 'krajee',
					name: 'clientes',
					placeholder: 'Selecione um cliente',
			        allowClear: true,
				});
				}

				// seta o cliente ja selecionado no select2
				$("#clientes").val($('.clientePDV').attr('id')).change();

				$('#modalClientes').fadeIn();
				$('#modalClientes').modal({backdrop: true, keyboard: true});
			}
	   });
    });
    
    //Botão de remover o cliente
    $('#removeCliente').on('click', function() {
    	this.blur();
    	toastr.remove();
    	var pedidoId = $('#pedido-id').val();
    	$.ajax({
			method: 'POST',
			url: BASE_PATH + 'pedido/adicionar-cliente',
			data: {
				remove: true,
				pedido: pedidoId,
			},
			success: function(data) {
				var retorno = JSON.parse(data);
				if(retorno.success == 0) {
					toastr.error(retorno.message);
					if (retorno.data != undefined && retorno.data.limparCampo != undefined && retorno.data.limparCampo == 'S'){
						$('#pedido-cpf').val('').focus();
					}
				}
				else {
					toastr.success("Cliente removido com sucesso. Adicionado Cliente CONSUMIDOR");
					$('#cprf').val(0).trigger('change');
					$('.clientePDV').text(retorno.data.cliente).attr('id', retorno.data.id);
					$('#pedido-cpf').val(retorno.data.cpf);
				}
			}
    	});
    });

	// Adiciona um cliente com base no select
	$('body').on('click', '#selectCliente' ,function() {

		toastr.remove();

		var clienteId = $('#clientes').val();
		var pedidoId = $('#pedido-id').val();

		if(clienteId == null || clienteId == '')
			clienteId = 1;

		$.ajax({
			method: 'POST',
			url: BASE_PATH + 'pedido/adicionar-cliente',
			data: {
				add: true,
				id: clienteId,
				pedido: pedidoId,
			},
			success: function(data) {
				var retorno = JSON.parse(data);
				if(retorno.success == 0) {
					toastr.error(retorno.message);
					if (retorno.data != undefined && retorno.data.limparCampo != undefined && retorno.data.limparCampo == 'S'){
						$('#pedido-cpf').val('').focus();
					}
				}
				if(retorno.data.tipo == '2') {
					$('#cprf').val(1).trigger('change');
					$('.clientePDV').text(retorno.data.cliente).attr('id', retorno.data.id);
					$('#pedido-cpf').val(retorno.data.cnpj);
				}else {
					$('#cprf').val(0).trigger('change');
					$('.clientePDV').text(retorno.data.cliente).attr('id', retorno.data.id);
					$('#pedido-cpf').val(retorno.data.cpf);					
				}

				if(retorno.data.id != 1) {
					toastr.success(retorno.message);
				}else {
					toastr.success("Cliente removido com sucesso.");
				}
			}
    	});
	});

	// Adiciona um cliente com base no cpf inserido
	$('#pedido-cpf').on('focusin', function() {
		// salva o CPF antigo para setar novamente caso não encontre um cliente
	    $(this).data('identi', $(this).val());
	}).change(function() {
		toastr.options = {'preventDuplicates': true};

		let identi = $('#pedido-cpf').val();
		let pedidoId = $('#pedido-id').val();
		let tipoDoc = identi.length == 14 ? 'cpf' : 'cnpj';
		
		if(validarDocumento(identi, tipoDoc)) {
			$.ajax({
				method: 'POST',
				url: BASE_PATH + 'pedido/adicionar-cliente',
				data: {
					identi: identi,
					pedido: pedidoId,
				},
				success: function(data) {
					let retorno = JSON.parse(data);
					if(retorno.success == 1) {
						// adiciona ou remove o cliente do PDV	
						$('.clientePDV').text(retorno.data.cliente).attr('id', retorno.data.id);
						toastr.success('Cliente adicionado com sucesso.');
					}
				}
	    	});
		}else {
			toastr.warning('O "<b>' + tipoDoc + '</b>" digitado não é valido.');
			$('#pedido-cpf').val($('#pedido-cpf').data('identi'));
		}
	});
	// ---------------------------------------------------------------------
	// Fim - Adiciona Cliente ao PDV
	// ---------------------------------------------------------------------

	// ---------------------------------------------------------------------
    // Inicío - Cadastrar Cliente
    // ---------------------------------------------------------------------

	// verifica e valida o CPF
    $('body').on('change', '#clientes-cpf', function() {

    	cpf = $(this).val();

    	if(cpf == '' || cpf == null) {
    		return true;
    	}

    	return true;
    })

    //Abre e fecha modal de cadastro de cliente
    $('#cadastrarCliente').on('click', function() {
    	this.blur();
    	$.ajax({
			method: 'GET',
			url: BASE_PATH + 'clientes/create-basic?tipo=basico',
			beforeSend: function() {
				$('#modalCadastrarCliente').find('.modalContent').html('<div class="box box-primary"><div class="box-body"><h3 class="text-center text-primary"><i class="fa fa-spinner fa-spin"></i> Aguarde ...</h3></div></div>');
				$('#modalCadastrarCliente').modal('show');
			},
			success: function(data) {
				$('#modalCadastrarCliente').find('.modalContent').html(data);
			}
    	});
    });

    $('#btnCloseModal').on('click', function() {
    	$('.modalCadastrarCliente').modal('hide');
    });

    //Verifica o tipo de cliente que irá cadastrar
    $('#clientes-tipo').on('change', function() {
    	//Condição para cliente tipo pj
    	if($(this).val() == 2) {
    		$('.pf').val('');
    		$('.div_fisica').hide();
    		$('.div_juridica').show(1500);
    	}else {
    		//Condição para cliente tipo pf
    		$('.pj').val('');
    		$('.div_juridica').hide();
    		$('.div_fisica').show(1500);
    	}
    });

    $('#btnConfirmarCadastroCliente').on('click', function() {

    	var cliente;

    	if($('#clientes-tipo').val() == 1) {
    		cliente = {
				Clientes:{
					cpf : $('#clientes-cpf').val(),
					rg : $('#clientes-rg').val(),
					nome : $('#clientes_nome').val(),
					apelido : $('#clientes-apelido').val(),
					dtanascto : $('#clientes-dtanascto').val(),
					sexo : $('#clientes-sexo').val(),
					endereco : $('#clientes-endereco').val(),
					cep : $('#clientes-cep').val(),
					numero : $('#clientes-numero').val(),
					id_estado : $('#clientes-id_estado').val(),
					id_cidade : $('#clientes-id_cidade').val(),
					complemento : $('#clientes-complemento').val(),
					bairro : $('#clientes-bairro').val(),
					fone : $('#clientes-fone').val(),
					fone_celular : $('#clientes-fone_celular').val(),
					email : $('#clientes-email').val(),
					tipo : $('#clientes-tipo').val(),
					ativo : 1,
				}
    		}

    	}else if($('#clientes-tipo').val() == 2) {
    		cliente = {
				Clientes:{
					cnpj : $('#clientes-cnpj').val(),
					iest : $('#clientes-iest').val(),
					indie : $('#clientes-indie').val(),
					nome : $('#clientes-razao_social').val(),
					apelido : $('#clientes-fantasia').val(),
					endereco : $('#clientes-endereco').val(),
					cep : $('#clientes-cep').val(),
					numero : $('#clientes-numero').val(),
					id_estado : $('#clientes-id_estado').val(),
					id_cidade : $('#clientes-id_cidade').val(),
					complemento : $('#clientes-complemento').val(),
					bairro : $('#clientes-bairro').val(),
					fone : $('#clientes-fone').val(),
					fone_celular : $('#clientes-fone_celular').val(),
					email : $('#clientes-email').val(),
					tipo : $('#clientes-tipo').val(),
					ativo : 1,
				}
    		}
    	}

    	cadastrarCliente(cliente);
    });
    // ---------------------------------------------------------------------
    // Fim - Cadastrar Cliente
    // ---------------------------------------------------------------------

    // ---------------------------------------------------------------------
	// Início - DESCONTO DO PEDIDO
	// ---------------------------------------------------------------------
    //onChange - Tipo Desconto/Valor Desconto
    $('body').on('change', '#pedido-tipo-desconto, #pagamento-desconto',function() {
    	if (!getDescontoGeral()) {
			return false;
		}
    	
    	Pedido_calculaTotal();
	});
    
    // fecha a modal de senha master e reseta o desconto
    $('body').on('click', '.btnFechaSenhaMaster', function() {
    	$('#pagamento-desconto').val('R$ 0,00');
    });

    // foca no valor do desconto
    $('body').on('click', '#pagamento-desconto', function() {
    	$(this).select().focus();
    });
	// ---------------------------------------------------------------------
	// Fim - DESCONTO DO PEDIDO
	// ---------------------------------------------------------------------

	// ---------------------------------------------------------------------
	// Início - PAGAMENTOS
	// ---------------------------------------------------------------------
	$('#btnPagamento').click(function() {
		if (!$(this).hasClass('disabled')) {
	        $.ajax({
				method: 'GET',
				url: BASE_PATH + 'pdv/pagamento/' + $('#pedido-id').val(),
				beforeSend: function() {
					$('#mbPagamento').find('.modalContent').html('<div class="box box-primary"><div class="box-body"><h3 class="text-center text-primary">Aguarde...</h3></div></div>');
					$('#mbPagamento').modal('show');
				},
				success: function(data) {
					$('#mbPagamento').find('.modalContent').html(data);
					$("#pagamento-valor").maskMoney({prefix:'R$', allowNegative: false, thousands:'.', decimal:',', affixesStay: true, precision: 2});
					$("#pagamento-desconto").maskMoney({prefix:'', allowNegative: false, thousands:'.', decimal:',', affixesStay: true, precision: 2}).attr('maxlength', 15);
				},
				complete: function() {
					verificarCredito();
					
					// verifica se há apenas uma forma de pagamento
					// se sim ativa o click na forma de pagamento
					// senao, seta o focus na primeira
					if ($('.btn-forma-pagamento').length == 1) {
						$('.btn-forma-pagamento').trigger('click');
					} else {						
						$('.btn-forma-pagamento:first').focus();
					}
				}
	    	});
		}

        return false;
    });

	// forma de pagamento comum 
	$('#mbPagamento').on('click', '.btnFormaPagamento', function() {
		var dados = {
			id: $(this).data('pagamento'),
			descricao: $(this).html(),
			parcelas: $(this).data('parcelas')
		};

		Pagamento_setarNoForm(dados);
		$('#pagamento-valor').focus().trigger('select');
	});
	
	// forma de pagamento com credito do cliente
	$('#mbPagamento').on('click', '.btnFormaPagamentoCredito', function() {
		verificarCredito($(this));
	});
		
	// esconde a mensagem de carregamento (quando a modal estiver pronta)
	// e foca automaticamente no campo de valor
	$('#mbCredito').on('shown.bs.modal', function() {
		$(this).find('.modalContent')
		.find('.modalLoading, .modalConteudo').toggleClass('hidden')
		.find('#_creditoValor-disp').focus();
	});
	
	// mostra novamente a mensagem de loading
	$('#mbCredito').on('hidden.bs.modal', function() {
		$(this).find('.modalContent').find('.modalLoading, .modalConteudo').toggleClass('hidden');
	});
	
	$('body').on('click', '#_confirmCredito', function() {
		$('#pagamento-id').val($('#_creditoPagamento').val());
		$('#pagamento-valor').val($('#_creditoValor-disp').val());
		$('#pagamento-parcelas').val('1');
		$('#mbCredito').modal('hide');
		inserirPagamento();
	});
	
	// utiliza o ENTER como TAB
	$('#mbPagamento').on('keypress', '#pagamento-parcelas', function(e) {
    	if (e.which == 13) {
    		$('#pagamento-valor').focus();
    	}
    });

	$('#mbPagamento').on('keypress', '#pagamento-valor', function(e) {
    	if (e.which == 13) {
    		$('#btnIncluirPagamento').trigger('click');
    	}
    });

    // GRAVAR O PAGAMENTO
    $('#mbPagamento').on('click', '#btnIncluirPagamento', function() {
    	// valida se a forma de pagamento e o pagamento foram inseridos
    	if($('#pagamento-descricao').html().length <= 0 || accounting.unformat($('#pagamento-valor').val(), ',') == 0) {
    		toastr.warning('Nenhuma forma de pagamento foi escolhida ou o pagamento está com o valor zerado!');
    		return false;
    	}

    	// metodo para inserir o pagamento ao pdv
		inserirPagamento();
    });

    // REMOVER PAGAMENTO DA GRID
    $('#mbPagamento').on('click', '.btnRemoverPagamento', function() {
        var idPedidoPagamento = $(this).data('id');

        $.confirm({
        	keyboardEnabled: true,
        	title: '<b class="text-danger"><i class="fa fa-exclamation-triangle"></i>&nbsp; Atenção<b>',
            content: 'Deseja realmente remover o pagamento?',
            confirmButton: 'Sim',
            confirm: function() {
            	$('#btnConcluirVenda').prop('disabled', true);
            	
                $.ajax({
    				method: 'post',
    				url: BASE_PATH + 'pedido-pagamento/remover',
    				data: {id: idPedidoPagamento},
    				success: function(data) {
    					try {
                            var retorno = JSON.parse(data);
                            if(retorno.success == 1) {
                                toastr.success('Pagamento removido com sucesso!');

                                //Se foram removidos todos os pagamentos
                                if (retorno.data.totalRecebido == 0) {
                                	// habilita o campo do desconto do pedido
                                	$('#pedido-tipo-desconto').prop('disabled', false);
                                	$('#pagamento-desconto').prop('disabled', false);
                                	$('#hintDesconto').hide();
                                }

                                if (retorno.data != null && retorno.data.idpedido != null) {
                                	// atualiza a grid de pagamentos
                                	$.pjax({
                                	    type: 'POST',
                                	    url: BASE_PATH + 'pedido-pagamento/list',
                                	    container: '#PedidoPagamentoGrid-pjax',
                                	    data: {pedido_id: retorno.data.idpedido},
                                	    push: false,
                                	}).always(function() {
                                		//Atualiza a tabela de valores
                                        Pagamento_atualizaValores(retorno.data.totalPedido, retorno.data.totalRecebido, retorno.data.saldoPagar, retorno.data.troco);
                                	});

                                } else {
                                	throw 'Falha ao atualizar os pagamentos. Tente recarregar a página.';
                                }
                            }else {
                            	throw 'Não foi possível remover o pagamento.';
                            }

                        }catch(error) {
                        	// valida o status do button de conclusao
                        	valida_button_conclusao();
                        	
                            $.confirm({
            					title: '<span class="text-danger"><i class="fa fa-exclamation-triangle"></i>&nbsp; Atenção</span>',
            					content: error,
            					confirmButton: 'Ok',
            					cancelButton: false,
            					animation: 'scale',
            					backgroundDismiss: false,
            					closeIcon: false,
            				});
                        }
                    }

                }).fail(function() {
                	// valida o status do button de conclusao
                	valida_button_conclusao();
                	
    				$.confirm({
    					title: '<span class="text-danger"><i class="fa fa-exclamation-triangle"></i>&nbsp; Atenção</span>',
    					content: 'Erro inesperado. Por favor, contate o administrador.',
    					confirmButton: 'Ok',
    					cancelButton: false,
    					animation: 'scale',
    					backgroundDismiss: false,
    					closeIcon: false,
    				});
    			});
            }
        });
    });

	// CANCELAR O PAGAMENTO
    $('#mbPagamento').on('click', '#btnCancelarPagamento', function() {
    	Pagamento_limparForm();
    });

    // Salva a observacao inserida
    $('#obs').change(function() {
    	var obs = $('#obs').val();
    	var id = $('#pedido-id').val();

    	$.ajax({
    	    type: 'POST',
    	    url: BASE_PATH + 'pedido/salvar-parcial',
    	    data: {
    	        obs: obs,
    	        id: id,
    	    },
    	    success: function(data) {
    	    	var retorno = JSON.parse(data);
    	    },
    	});
    });

    //CONCLUIR A VENDA
    $('#mbPagamento').on('click', '#btnConcluirVenda', function() {
    	var idPedido = $(this).data('pedido');
    	var consumidorIdent = $('#pedido-cpf').val();
    	var cpf  = '';
		var cnpj = '';
		
		// valida o consumidor informado
		if(consumidorIdent != '') {
			consumidorIdent = consumidorIdent.match(/\d+/g).join('');
			
			// tipo de consumidor
			// 1 => juridico | 0 => fisico
			if($('#cprf').val() == '1') {
				// valida o cnpj do consumidor nao identificado
				if(consumidorIdent != '' && consumidorIdent != '00000000000000' && consumidorIdent.length == 14) {
					cnpj = consumidorIdent;
				}
			}else if($('#cprf').val() == '0') {
				// valida o cpf do consumidor nao identificado
				if(consumidorIdent != '' && consumidorIdent != '00000000000' && consumidorIdent.length == 11) {
					cpf = consumidorIdent;
				}											
			}
		}
    	
    	var btnLoading = $(this).button('loading');
    	btnLoading.data('loading-text', '<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>');

    	//Salva parcialmente o pedido informando o fechamento
		var dadosPedido = {
			Pedido: {
				id: $('#pedido-id').val(),
				itens_quantidade: $('#venda-total-itens').html(),
				pedido_subtotal: accounting.unformat($('#venda-valor-subtotal').html(), ','),
				pedido_tipo_desconto: $('#pedido-tipo-desconto').val(), //%(1) ou R$(0)
				pedido_desconto_valor: accounting.unformat($('#pagamento-desconto').val(), ','),
				pedido_total: accounting.unformat($('#lblTotalPedido').html(), ','),
				fechado: 0,
			}
		};

		Venda_salvarParcial(dadosPedido)
		.done(function(e) {
			try {
				var retorno = JSON.parse(e);
				var permiteSenhaMaster = false;
				
				if(retorno.success == 1) {
					// pedido Salvo - agora conclui a Venda
					$.ajax({
						method: 'POST',
						data: {idPedido: idPedido, cpf: cpf, cnpj: cnpj},
						url: BASE_PATH + 'caixa-diario/concluir-venda',
						success: function(data) {
							try {
								var retorno = JSON.parse(data);
								
								if(retorno.success == 1) {
									// valida o status do button de conclusao
									btnLoading.data('complete-text', '<i class="fa fa-checked"></i> Venda Cocluída');
									btnLoading.button('complete');
		                        	valida_button_conclusao();

									var subtotal = accounting.formatMoney(retorno.data.subtotal, 'R$', 2, '.', ',');
									var total = accounting.formatMoney(retorno.data.total, 'R$', 2, '.', ',');
									var recebido = accounting.formatMoney(retorno.data.recebido, 'R$', 2, '.', ',');
									var troco = accounting.formatMoney(retorno.data.troco, 'R$', 2, '.', ',');
									var desconto = accounting.formatMoney(retorno.data.desconto_itens, 'R$', 2, '.', ',');

									var exibeTroco = 'none';
									if(retorno.data.troco > 0) {
										exibeTroco = 'table-row';
									}

									var content = '';
									content += '<div class="box box-primary">';
									content += '	<table class="table">';
									content += '		<tbody>';
									content += '			<tr class="bg-warning">';
									content += '				<td>SUBTOTAL</td>';
									content += '				<td class="text-right">'+ subtotal +'</td>';
									content += '			</tr>';
									content += '			<tr class="bg-danger">';
									content += '				<td>DESCONTOS</td>';
									content += '				<td class="text-right">'+ desconto +'</td>';
									content += '			</tr>';
									content += '			<tr class="bg-success strong font14" style="color: #5B5B5B">';
									content += '				<td>TOTAL</td>';
									content += '				<td class="text-right">'+ total +'</td>';
									content += '			</tr>';
									content += '			<tr class="bg-success">';
									content += '				<td>RECEBIDO</td>';
									content += '				<td class="text-right">'+ recebido +'</td>';
									content += '			</tr>';
									content += '			<tr class="bg-info" style="display:'+ exibeTroco +'">';
									content += '				<td>TROCO</td>';
									content += '				<td class="text-right">'+ troco +'</td>';
									content += '			</tr>';
									content += '		</tbody>';
									content += '	</table>';
									content += '</div>';

									//Exibe a mensagem de Venda Concluída
									$.confirm({
										keyboardEnabled: true,
										title: '<span class="text-success"><i class="fa fa-check"></i>&nbsp; Venda Concluída</span>',
										content: content,
										confirmButton: 'Ok',
										confirmButtonClass: 'btn-primary',
										confirm: function(){
											window.location = BASE_PATH +'pdv';
										},
										cancelButton: false,
										animation: 'scale',
										backgroundDismiss: false,
										closeIcon: false,
									});

									// cria e abre a nota fiscal
									if(retorno.data.emite_nf) {
										// envia o post para emitir a nota
										// e exibe uma animação de processamento especifica
										var _emitindoNota = null;
										$.ajax({
											url: BASE_PATH + 'nfe-envio/emitir-nota-fiscal',
											method: 'post',
											data: {
												'id_pedido': idPedido, 
												'cpf': cpf, 
												'cnpj': cnpj
											},
											beforeSend: function() {
												_emitindoNota = $.confirm({
													title: false,
													content: '<h1><b><i class="fa fa-spinner fa-pulse"></i>&nbsp; Emitindo Nota Fiscal ...</b>',
													confirmButton: false,
													cancelButton: false,
													theme: 'supervan', 
													backgroundDismiss: false,
													closeIcon: false,
													columnClass: 'col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-lg-8 col-lg-offset-2 col-xs-10 col-xs-offset-1',
												});
											},
											success: function(response) {
												let retorno = JSON.parse(response);
												
												// abre a pagina de view ou erro da nota
												if(retorno.status == true) {
													window.open(BASE_PATH + 'nfe-envio/view/' + retorno.id_nfeenvio, '_blank');
												}else {
													window.open(BASE_PATH + 'nfe-envio/erro?' + retorno.error, '_blank');
												}
												
												// fecha o confirm de emissao de nota
						        				if(typeof _emitindoNota != 'undefined' && _emitindoNota != null && _emitindoNota != '') {
													_emitindoNota.close();
												}
												_emitindoNota = null;
											},
											error: function() {
												// fecha o confirm de emissao de nota
						        				if(typeof _emitindoNota != 'undefined' && _emitindoNota != null && _emitindoNota != '') {
													_emitindoNota.close();
												}
												_emitindoNota = null;
												
												// confirm de erro
												$.confirm({
							            			title: '<b class="text-danger"><i class="fa fa-exclamation-triangle"></i>&nbsp; Atenção</b>',
							            			content: 'Houve um erro interno inesperado. Por favor, tente <a href="'+BASE_PATH+'nfe-envio" target="_blank">reenviar a nota</a>, se o erro persistir contate o administrador.',
							            			confirmButton: '<i class="fa fa-check"></i>',
							                        confirmButtonClass: 'btn-primary btn-flat',
							            			cancelButton: false,
							            			backgroundDismiss: false,
							            		});
											},
										});
									}

									// imprime a comanda
									if(retorno.data.gerar_comanda) {
										window.open(BASE_PATH + 'pdv/comanda?pedido=' + idPedido, '_blank');
									}

								}else {
									throw retorno.message;
								}

							}catch(e) {
								// valida o status do button de conclusao
								btnLoading.button('reset');
	                        	valida_button_conclusao();
	                        	
								toastr.error(e);
							}
						}
			    	}).fail(function() {
			    		// valida o status do button de conclusao
			    		btnLoading.button('reset');
                    	valida_button_conclusao();
                    	
						$.confirm({
							title: 'Não foi possível concluir a venda',
							content: 'Erro interno no servidor',
							confirmButton: 'Ok',
							cancelButton: false,
							animation: 'scale',
							backgroundDismiss: false,
							closeIcon: false,
						});
					}).then(function() {
						// valida o status do button de conclusao
						btnLoading.button('reset');
                    	valida_button_conclusao();
					});

				}else {
					permiteSenhaMaster = (retorno.senhaMaster == undefined ? false : retorno.senhaMaster);
					throw (retorno.message != '' ? retorno.message : 'Erro interno do servidor');
				}

			}catch(error) {
				// valida o status do button de conclusao
				btnLoading.button('reset');
            	valida_button_conclusao();
            	
				let HTML_SENHA_MASTER = '';
				if(permiteSenhaMaster) {
					HTML_SENHA_MASTER = getHtmlSenhaMaster('btnSenhaMasterFecharPDV');
					$('#mbSenhaMaster').modal('show').find('.modalContentSenhaMaster').html(error + HTML_SENHA_MASTER);
				}else {					
					$.confirm({
						title: 'Ocorreu um erro durante o processo',
						content: error,
						confirmButton: 'Ok',
						cancelButton: false,
						animation: 'scale',
						backgroundDismiss: false,
						closeIcon: false,
					});
				}
			}
		});
    });
    // ---------------------------------------------------------------------
	// Fim - PAGAMENTOS
	// ---------------------------------------------------------------------

    // CANCELAR VENDA
    $('#btnCancelarVenda').click(function() {
    	var jc = $.confirm({
        	keyboardEnabled: true,
        	title: '<b class="text-danger"><i class="fa fa-exclamation-triangle"></i>&nbsp; Atenção<b>',
            content: 'Deseja realmente cancelar a venda?',
            confirmButton: 'Sim',
            confirm: function() {
            	Venda_cancelar($('#pedido-id').val(), jc);
            }
        });
    });
    
    // Valida a senha master para o PDV
    $('body').on('click', '#btnSenhaMasterVendaSalvar, #btnSenhaMasterFecharPDV', function(e) {
    	$('#mbSenhaMaster').modal('hide');
		let senhaMaster = $(this).parent().parent().find('#senha-master_senha').val();
		var buttonID = $(this).attr('id');
		
		// monta os dados para salvar
		let dadosPedido = {
			Pedido: {
				id: $('#pedido-id').val(),
				pedido_subtotal: accounting.unformat($('#venda-valor-subtotal').html(), ','),
				pedido_tipo_desconto: $('#pedido-tipo-desconto').val(),
				pedido_desconto_valor: accounting.unformat($('#pagamento-desconto').val(), ','),
				pedido_total: accounting.unformat($('#lblTotalPedido').html(), ','),
				fechado: 0,
				senha_master: senhaMaster,
			}
		};
		
		//Envia a senha master junto com os dados do PDV
		Venda_salvarParcial(dadosPedido).done(function(response) {
			try {
				var retorno = JSON.parse(response);
				if(retorno.success == 1) {
					toastr.success('PDV salvo com sucesso.');
					if(buttonID == 'btnSenhaMasterFecharPDV') {
						$('#btnConcluirVenda').trigger('click');
					}
				}else {
					// reseta o desconto se houver erro
					$('#pagamento-desconto').val('R$ 0,00').change();
					throw (retorno.message != '' ? retorno.message : 'Erro interno do servidor');
				}
			}catch(error) {
				$.confirm({
					title: '<b class="text-danger"><i class="fa fa-exclamation-triangle"></i>&nbsp; Atenção: Não foi possível gravar o pedido<b>',
					content: error,
					confirmButton: 'Ok',
					cancelButton: false,
		    		confirmButtonClass: 'btn btn-primary btn-flat',
		            backgroundDismiss: false,
		            closeIcon: false,
					animation: 'scale',
				});
			}
		});
	});
});
/**
 * FIM DO DOCUMENTO READY
 */
// --------------------------------------------------------------------------------
/**
 * INICIO DAS FUNÇÕES
 */
function Venda_cancelar(idPedido, jc) {

	var dadosPedido = {
			id: idPedido
	};

	jc.close();

	return $.ajax({
		method: 'post',
		url: BASE_PATH + 'pedido/cancela',
		data: dadosPedido,
		success: function(data) {
			try {
                var retorno = JSON.parse(data);
                if(retorno.success == 1) {
                	$.confirm({
                		keyboardEnabled: true,
                		icon: 'fa fa-success',
        				title: 'Venda cancelada com sucesso!',
        				content: '',
        				confirmButton: 'Ok',
        				cancelButton: false,
        				animation: 'scale',
        				backgroundDismiss: false,
        				closeIcon: false,
        				confirm: function(){
        					window.location = BASE_PATH + 'pdv'
        				}
        			});
                }else {
                    throw retorno.message;
                }

			}catch(error) {
                $.confirm({
                	keyboardEnabled: true,
					title: 'Não foi possível cancelar a venda',
					content: error,
					confirmButton: 'Ok',
					cancelButton: false,
					animation: 'scale',
					backgroundDismiss: false,
					closeIcon: false,
				});
            }
        }
    }).fail(function() {
		$.confirm({
			keyboardEnabled: true,
			title: 'Não foi possível cancelar a venda',
			content: 'Erro interno no servidor',
			confirmButton: 'Ok',
			cancelButton: false,
			animation: 'scale',
			backgroundDismiss: false,
			closeIcon: false,
		});
	});
}

/**
 * Seta o produto no formulário
 *
 * @param array data (Dados do produto)
 * @param boolean isNew (Flag para identificar se está inserindo ou alterando um produto no pedido)
 */
function Produto_setarNoForm(data, isNew) {
	var numRow = data.numRow == undefined ? '' : data.numRow;
	$("#venda-item-num").val(numRow);

	if(isNew) {
		$('#btnProdutoAlterar').hide();
		$('#btnProdutoIncluir').show();
	}else {
		$('#btnProdutoIncluir').hide();
		$('#btnProdutoAlterar').show();
	}

	var preco = data.preco1 == undefined ? data.valor : data.preco1;
	var quantidade = data.quantidade == undefined ? '1,000' : data.quantidade;
	var desconto = data.desconto == undefined ? 'R$ 0,00' : data.desconto;
	var subtotal = data.subtotal == undefined ? preco : data.subtotal;
	var total = data.total == undefined ? preco : data.total;

	if(data.item_id != undefined) {
		$("#pedido-item-id").val(data.item_id);
	}

	$("#produto-id").val(data.id);
	$("#produto-ean").html(data.ean);
	$("#produto-descricao").html(data.descricao);
	$("#produto-valor").val(preco);
	$("#produto-quantidade").val(quantidade);
	$("#produto-desconto").val(desconto);
	$("#produto-tipo-desconto").val('0').trigger('change');
	$("#produto-subtotal").html(accounting.formatMoney(accounting.unformat(preco, ',') * accounting.unformat(quantidade, ','), 'R$', 2, '.', ','));
	$("#produto-total").html(total);

	if(isNew && $('#produto-incluir_automatico').bootstrapSwitch('state')) {

		$("#btnProdutoIncluir").trigger('click');

	}else {

		if(isNew && $('#produto-incluir_automatico').bootstrapSwitch('state')) {

			$("#btnProdutoIncluir").trigger('click');

		}else {

			if(isNew) {
				$('#btnProdutoAlterar').hide();
				$('#btnProdutoIncluir').show();
			}else {
				$('#btnProdutoIncluir').hide();
				$('#btnProdutoAlterar').show();
			}

			$("#pdv-form-produto-vazio").hide();
			$("#pdv-form-produto").show();

			//Seta o foco para o valor
			$("#produto-valor").focus();
		}
	}
}

/**
 * Seta o produto na venda
 *
 * @param array data (Dados do produto)
 * @param boolean numRow (No caso de alteração de um produto indica o número da linha)
 */
function Produto_setarNaVenda(data, numRow) {
	try {

		if(numRow != undefined && numRow > 0) {

			// --------------------------------------------------------------
			// ALTERANDO PRODUTO
			// --------------------------------------------------------------

			var objLinha = $('div.produto[data-row='+numRow+']');
			if(objLinha.length == 0) {
				throw 'O produto não pode ser econtrado na venda. Por favor, refaça a operação.';
			}

			// remove a mascara e formata o desconto
			if (data.tipoDesconto == '0') {
				data.desconto = accounting.unformat(data.desconto, ',');
			} else {
				// desconto em porcentagem => converte para valor
				data.desconto = (accounting.unformat(data.subtotal, ',') / 100) * accounting.unformat(data.desconto, ',');
			}
			
			var totalProduto = accounting.unformat(data.quantidade, ',') * (accounting.unformat(data.valor, ',') - data.desconto);
			var subtotalProduto = accounting.unformat(data.subtotal, ',');

			var dadosPost = {
				Pedido: {
					id: $('#pedido-id').val(),
				},
				PedidoItens: {
					id: data.item_id,
					produto_id: data.id,
					quantidade: accounting.unformat(data.quantidade, ','),
					tabela_praticada: 1,
					valor_unitario: accounting.unformat(data.valor, ','),
					subtotal: subtotalProduto,
					total: totalProduto,
					tipo_desconto: data.tipoDesconto, //R$(0) ou %(1)
					desconto_valor: accounting.unformat(data.desconto, ','),
					descricao_complementar: data.descricao,
				}
			};

			//Verifica se foi informada a senha master
			if(data.senha_master != undefined) {
				dadosPost.Pedido.senha_master = data.senha_master;
			}

			//Salva o produto via Ajax
			var $btn = $('#btnProdutoAlterar');
			var $btnCancela = $('#btnProdutoCancelar');
			$.ajax({
				method: 'post',
				url: BASE_PATH + 'pedido/produto-salvar',
				data: dadosPost,
				beforeSend: function() {
					$btn.button('loading');
					$btnCancela.button('loading');
				},
				success: function(dados) {

					try {
						var permiteSenhaMaster = false;

						$btn.button('reset');
						$btnCancela.button('reset');

						var retorno = JSON.parse(dados);

						if(retorno.success == 1) {

							//Altera os dados do produto na venda
							objLinha.find('.venda-produto-ean').html(data.ean);
							objLinha.find('.venda-produto-descricao').html(data.descricao);
							objLinha.find('.venda-produto-quantidade').html(data.quantidade);
							objLinha.find('.venda-produto-valor').html(data.valor);
							objLinha.find('.venda-produto-subtotal').val(data.subtotal);
							objLinha.find('.venda-produto-total').html(data.total);

							var valorDesconto = data.desconto;
							var qtde = accounting.unformat(data.quantidade, ',');
							
							if(valorDesconto > 0) {
								objLinha.find('.venda-desconto-lbl').html('DESCONTO');
								objLinha.find('.venda-produto-desconto').html(accounting.formatMoney((valorDesconto * qtde), 'R$', 2, '.', ','));
							}else {
								objLinha.find('.venda-desconto-lbl').html('');
								objLinha.find('.venda-produto-desconto').html('');
							}

							//Efeito na linha do produto
							objLinha.addClass('bg-warning');
							$(objLinha).hide().fadeIn(2000, function() {
								$(this).removeClass('bg-warning');
							});

							//Recalcula o total da venda
							Venda_calculaTotais();

							//Habilita o botão do pagamento
							if($('#btnPagamento').hasClass('disabled')) {
								$('#btnPagamento').removeClass('disabled');
							}

							//Limpa o form
							Produto_limparForm();

							//Mostra mensagem
							toastr.success('Produto alterado com sucesso!');

							//Salva parcialmente o pedido, atualizando os valores
							var dadosPedido = {
								Pedido: {
									id: $('#pedido-id').val(),
									itens_quantidade: $('#venda-total-itens').html(),
									pedido_subtotal: accounting.unformat($('#venda-valor-subtotal').html(), ','),
									pedido_tipo_desconto: 0, //%(1) ou R$(0)
									pedido_desconto_valor: 0,
									pedido_total: accounting.unformat($('#venda-total-geral').html(), ','),
								}
							};

							//Verifica se foi informada a senha master
							if(data.senha_master != undefined) {
								dadosPedido.Pedido.senha_master = data.senha_master;
							}

							Venda_salvarParcial(dadosPedido)
							.done(function(e) {
								try {

									var retorno = JSON.parse(e);
									if(retorno.success == 1) {
										toastr.success('PDV salvo com sucesso!');
									}else {
										throw (retorno.message != '' ? retorno.message : 'Erro interno do servidor');
									}

								}catch(error) {
									$.confirm({
										title: 'Ocorreu um erro durante o processo',
										content: error,
										confirmButton: 'Ok',
										cancelButton: false,
										animation: 'scale',
										backgroundDismiss: false,
										closeIcon: false,
									});
								}
				    		});

						}else {
							permiteSenhaMaster = (retorno.senhaMaster == undefined ? false : retorno.senhaMaster);
							throw retorno.message;
						}

					}catch(e) {
						$btn.button('reset');
						$btnCancela.button('reset');

						var HTML_SENHA_MASTER = '';
						if(permiteSenhaMaster) {
							HTML_SENHA_MASTER = getHtmlSenhaMaster('btnSenhaMasterProduto');
						}

						$.confirm({
							content: e + HTML_SENHA_MASTER,
							cancelButton: false,
							backgroundDismiss: false,
							closeIcon: false,
						});
					}
				}
			});

		} else {

			// --------------------------------------------------------------
			// NOVO PRODUTO
			// --------------------------------------------------------------
			var subtotalProduto = accounting.unformat(data.subtotal, ',');
			var totalProduto = (accounting.unformat(data.quantidade, ',') * accounting.unformat(data.valor, ',')) - accounting.unformat(data.desconto, ',');

			var dadosPost = {
				Pedido: {
					id: $('#pedido-id').val(),
				},
				PedidoItens: {
					produto_id: data.id,
					quantidade: accounting.unformat(data.quantidade, ','),
					tabela_praticada: 1,
					valor_unitario: accounting.unformat(data.valor, ','),
					subtotal: subtotalProduto,
					total: totalProduto,
					tipo_desconto: data.tipoDesconto, //R$(0) ou %(1)
					desconto_valor: accounting.unformat(data.desconto, ','),
					descricao_complementar: data.descricao,
				}
			};

			//Verifica se foi informada a senha master
			if (data.senha_master != undefined){
				dadosPost.Pedido.senha_master = data.senha_master;
			}

			//Salva o produto via Ajax
			var $btn = $('#btnProdutoIncluir');
			var $btnCancela = $('#btnProdutoCancelar');
			$.ajax({
				method: 'post',
				url: BASE_PATH + 'pedido/produto-salvar',
				data: dadosPost,
				beforeSend: function() {
					$btn.button('loading');
					$btnCancela.button('loading');
				},
				success: function(dados) {
					try {
						var permiteSenhaMaster = false;

						$btn.button('reset');
						$btnCancela.button('reset');

						var retorno = JSON.parse(dados);

						if(retorno.success == 1) {
							data.item_id = retorno.data.id;

							//Incluindo o primeiro produto na venda
							if ($('#sem-produtos').length > 0){
								$('#sem-produtos').remove();
							}

							var numItem = Venda_proxItemNum();

							var hProd = '';
							hProd += '<div class="row produto" data-row="'+ numItem +'">';
							hProd += '	<input class="venda-produto-id" type="hidden" value="'+ data.id +'"/>';
							hProd += '	<input class="pedido-item-id" type="hidden" value="'+ data.item_id +'"/>';
							hProd += '	<input class="venda-produto-subtotal" type="hidden" value="'+ data.subtotal +'"/>';
							hProd += '	<div class="col-md-2 col-sm-2 col-lg-2 col-xs-2 item">'+ numItem +'</div>';
							hProd += '	<div class="col-md-4 col-sm-4 col-lg-4 col-xs-4 venda-produto-ean">' + data.ean + '</div>';
							hProd += '	<div class="col-md-6 col-sm-6 col-lg-6 col-xs-6 venda-produto-descricao">' + data.descricao + '</div>';
							hProd += '	<div class="col-md-4 col-sm-4 col-lg-4 col-xs-4 text-right venda-produto-quantidade">' + data.quantidade + '</div>';
							hProd += '	<div class="col-md-4 col-sm-4 col-lg-4 col-xs-4 text-right venda-produto-valor">' + data.valor + '</div>';
							hProd += '	<div class="col-md-4 col-sm-4 col-lg-4 col-xs-4 text-right venda-produto-total">' + data.total + '</div>';
							
							var valorDesconto = accounting.unformat(data.desconto, ',');
							if(valorDesconto > 0) {
								hProd += '	<div class="col-md-10 col-lg-10 col-xs-10 col-sm-10 text-right text-danger venda-desconto-lbl">DESCONTO</div>';
								hProd += '	<div class="col-md-2 col-lg-2 col-xs-2 col-sm-2 text-left text-danger venda-produto-desconto">'+ data.desconto +'</div>';
							}else {
								hProd += '	<div class="col-md-10 col-lg-10 col-xs-10 col-sm-10 text-right text-danger venda-desconto-lbl"></div>';
								hProd += '	<div class="col-md-2 col-lg-2 col-xs-2 col-sm-2 text-left text-danger venda-produto-desconto"></div>';
							}

							hProd += '	<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">&nbsp;</div>';
							hProd += '	<div class="col-md-6 col-lg-6 hidden-sm hidden-xs btnedit"><button class="btn btn-xs btn-primary btnProdutoAlterar btn-flat pull-let" title="Alterar Item"><i class="fa fa-pencil-alt"></i></button> </div>';
							hProd += '	<div class="col-xs-6 col-sm-6 hidden-md hidden-lg btnedit2"> <button class="btn btn-xs btn-primary btnProdutoAlterar btn-flat pull-left" title="Alterar Item"><i class="fa fa-pencil-alt"></i></button> </div>';
							hProd += '	<div class="col-md-6 col-lg-6 col-sm-6 col-xs-6"> <button class="btn btn-xs btn-danger btnProdutoRemover btn-flat pull-right" title="Remover Item"><i class="fa fa-trash-alt"></i></button> </div>';
							hProd += '	<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">&nbsp;</div>';
							hProd += '</div>';

							//Inclui o produto na venda
							$('.box-produtos').prepend(hProd);

							//Recalcula o total da venda
							Venda_calculaTotais();

							//Habilita o botão do pagamento
							if ($('#btnPagamento').hasClass('disabled')){
								$('#btnPagamento').removeClass('disabled');
							}

							//Limpa o form
							Produto_limparForm();

							//Mostra mensagem
							toastr.success('Produto incluído com sucesso!');

							//Salva parcialmente o pedido, atualizando os valores
							var dadosPedido = {
								Pedido: {
									id: $('#pedido-id').val(),
									itens_quantidade: $('#venda-total-itens').html(),
									pedido_subtotal: accounting.unformat($('#venda-valor-subtotal').html(), ','),
									pedido_tipo_desconto: 0, //%(1) ou R$(0)
									pedido_desconto_valor: 0,
									pedido_total: accounting.unformat($('#venda-total-geral').html(), ','),
								}
							};

							//Verifica se foi informada a senha master
							if(data.senha_master != undefined) {
								dadosPedido.Pedido.senha_master = data.senha_master;
							}

							Venda_salvarParcial(dadosPedido)
							.done(function(e) {
								try {
									var retorno = JSON.parse(e);
									if (retorno.success == 1){
										toastr.success('PDV salvo com sucesso!');
									}else {
										throw (retorno.message != '' ? retorno.message : 'Erro interno do servidor');
									}

								}catch(error) {
									$.confirm({
										title: 'Ocorreu um erro durante o processo',
										content: error,
										confirmButton: 'Ok',
										cancelButton: false,
										animation: 'scale',
										backgroundDismiss: false,
										closeIcon: false,
									});
								}
				    		});

						}else {
							permiteSenhaMaster = (retorno.senhaMaster == undefined ? false : retorno.senhaMaster);
							throw retorno.message;
						}

					}catch(e) {
						$btn.button('reset');
						$btnCancela.button('reset');

						var HTML_SENHA_MASTER = '';
						if (permiteSenhaMaster){
							HTML_SENHA_MASTER = getHtmlSenhaMaster('btnSenhaMasterProduto');
						}

						$.confirm({
							content: e + HTML_SENHA_MASTER,
							cancelButton: false,
							backgroundDismiss: false,
							closeIcon: false,
						});
					}
				}
			});
		}

	}catch(e) {
		toastr.error('Ocorreu um erro ao realizar a operação. ' + e);
	}
}

/**
 * Remove um produto da venda
 *
 * @param objLinha (Objeto html da linha a ser removida)
 */
function Produto_removerDaVenda(objLinha) {

	//Verifica se o form está aberto com o produto
	if (objLinha.find('.pedido-item-id').val() == $('#pedido-item-id').val()) {
		Produto_limparForm();
	}

	objLinha.remove();

	//Recalcula o número dos itens
	Venda_recalcItemNum();

	//Recalcula o total da venda
	Venda_calculaTotais();



	//Removendo o último produto da venda
	if($('.produto').length == 0) {

		var hSemProd = '';
		hSemProd += '<div class="row" id="sem-produtos">';
    	hSemProd += '	<p class="text-muted text-center top15">Nenhum produto informado</p>';
		hSemProd += '</div>';

		//Verifica se foi informada a senha master
		if (data.senha_master != undefined){
			dadosPost.Pedido.senha_master = data.senha_master;
		}

		//Salva o produto via Ajax
		var $btn = $('#btnProdutoIncluir');
		$.ajax({
			method: 'post',
			url: BASE_PATH + 'pedido/produto-salvar',
			data: dadosPost,
			beforeSend: function() {
				$btn.button('loading');
			},
			success: function(dados) {
				try {
					var permiteSenhaMaster = false;

					$btn.button('reset');

					var retorno = JSON.parse(dados);

					if(retorno.success == 1) {
						data.item_id = retorno.data.id;

						//Incluindo o primeiro produto na venda
						if($('#sem-produtos').length > 0) {
							$('#sem-produtos').remove();
						}

						var numItem = Venda_proxItemNum();

						var hProd = '';
						hProd += '<div class="row produto" data-row="'+ numItem +'">';
						hProd += '	<input class="venda-produto-id" type="hidden" value="'+ data.id +'"/>';
						hProd += '	<input class="pedido-item-id" type="hidden" value="'+ data.item_id +'"/>';
						hProd += '	<input class="venda-produto-subtotal" type="hidden" value="'+ data.subtotal +'"/>';
						hProd += '	<div class="col-md-2 item">'+ numItem +'</div>';
						hProd += '	<div class="col-md-4 venda-produto-ean">' + data.ean + '</div>';
						hProd += '	<div class="col-md-6 venda-produto-descricao">' + data.descricao + '</div>';
						hProd += '	<div class="col-md-2"> <button class="btn btn-xs btn-default btnProdutoAlterar" title="Alterar Item"><i class="fa fa-pencil-alt"></i></button> </div>';
						hProd += '	<div class="col-md-2 text-right venda-produto-quantidade">' + data.quantidade + '</div>';
						hProd += '	<div class="col-md-3 text-right venda-produto-valor">' + data.valor + '</div>';
						hProd += '	<div class="col-md-3 text-right venda-produto-total">' + data.total + '</div>';
						hProd += '	<div class="col-md-2"> <button class="btn btn-xs btn-default btnProdutoRemover" title="Remover Item"><i class="fa fa-trash-alt"></i></button> </div>';

						var valorDesconto = accounting.unformat(data.desconto, ',');
						if(valorDesconto > 0) {
							hProd += '	<div class="col-md-7 text-right text-danger venda-desconto-lbl">DESCONTO</div>';
							hProd += '	<div class="col-md-3 text-right text-danger venda-produto-desconto">'+ data.desconto +'</div>';
						}else {
							hProd += '	<div class="col-md-7 text-right text-danger venda-desconto-lbl"></div>';
							hProd += '	<div class="col-md-3 text-right text-danger venda-produto-desconto"></div>';
						}
						hProd += '</div>';

						//Inclui o produto na venda
						$('.box-produtos').prepend(hProd);

						//Recalcula o total da venda
						Venda_calculaTotais();

						//Habilita o botão do pagamento
						if($('#btnPagamento').hasClass('disabled')) {
							$('#btnPagamento').removeClass('disabled');
						}

						//Limpa o form
						Produto_limparForm();

						//Mostra mensagem
						toastr.success('Produto incluído com sucesso!');

						//Salva parcialmente o pedido, atualizando os valores
						var dadosPedido = {
							Pedido: {
								id: $('#pedido-id').val(),
								itens_quantidade: $('#venda-total-itens').html(),
								pedido_subtotal: accounting.unformat($('#venda-valor-subtotal').html(), ','),
								pedido_tipo_desconto: 0, //%(1) ou R$(0)
								pedido_desconto_valor: 0,
								pedido_total: accounting.unformat($('#venda-total-geral').html(), ','),
							}
						};

						Venda_salvarParcial(dadosPedido)
						.done(function(e) {
							try {
								var retorno = JSON.parse(e);
								if (retorno.success == 1){
									toastr.success('PDV salvo com sucesso!');
								}
								else {
									throw (retorno.message != '' ? retorno.message : 'Erro interno do servidor');
								}
							}catch(error) {
								$.confirm({
									title: 'Ocorreu um erro durante o processo',
									content: error,
									confirmButton: 'Ok',
									cancelButton: false,
									animation: 'scale',
									backgroundDismiss: false,
									closeIcon: false,
								});
							}
			    		});

					}else {
						permiteSenhaMaster = (retorno.senhaMaster == undefined ? false : retorno.senhaMaster);
						throw retorno.message;
					}

				}catch(e) {

					$btn.button('reset');

					var HTML_SENHA_MASTER = '';
					if (permiteSenhaMaster){
						HTML_SENHA_MASTER = getHtmlSenhaMaster('btnSenhaMasterProduto');
					}

					$.confirm({
						content: e + HTML_SENHA_MASTER,
						cancelButton: false,
						backgroundDismiss: false,
						closeIcon: false,
					});
				}
			}
		});
	}
}

/**
 * Remove um produto da venda
 *
 * @param objLinha (Objeto html da linha a ser removida)
 */
function Produto_removerDaVenda(objLinha) {
	//Verifica se o form está aberto com o produto
	if (objLinha.find('.pedido-item-id').val() == $('#pedido-item-id').val()){
		Produto_limparForm();
	}

	objLinha.remove();

	//Recalcula o número dos itens
	Venda_recalcItemNum();

	//Recalcula o total da venda
	Venda_calculaTotais();

	//Removendo o último produto da venda
	if ($('.produto').length == 0){
		var hSemProd = '';
		hSemProd += '<div class="row" id="sem-produtos">';
    	hSemProd += '	<p class="text-muted text-center top15">Nenhum produto informado</p>';
		hSemProd += '</div>';

		$('.box-produtos').append(hSemProd);

		//Desabilita o botão do pagamento
		$('#btnPagamento').addClass('disabled');
	}

	//Mosta mensagem
	toastr.success('Produto removido com sucesso!');
}

/**
 * Calcula os totais do formulário de produto
 */
function Produto_calculaTotaisForm() {
	try {

		toastr.options = {'preventDuplicates': true};

		var valor = accounting.unformat($('#produto-valor').val(), ',');
		var qtde = accounting.unformat($('#produto-quantidade').val(), ',');
		var desc = accounting.unformat($('#produto-desconto').val(), ',');
		var descTipo = $('#produto-tipo-desconto').val();
		var subtotal = valor * qtde;

		if(descTipo == 1) {
			desc = (subtotal / 100) * desc;
		}

		if(desc > subtotal) {
			$('#produto-desconto').val('');
			desc = 0;
			throw 'O desconto informado está acima do permitido. Por favor informe um desconto menor.';
		}

		var total = (valor * qtde) - desc;

		//Formata o valor
		total = accounting.formatMoney(total, 'R$', 2, '.', ',');
		valor = accounting.formatMoney(valor, 'R$', 2, '.', ',');
		subtotal = accounting.formatMoney(subtotal, 'R$', 2, '.', ',');

		//Seta o valor no Form
		$('#produto-valor').val(valor);
		$('#produto-subtotal').html(subtotal);
		$('#produto-total').html(total);

	}catch(e) {
		toastr.warning('Erro ao incluir o produto na venda. ' + e);
	}
}

/**
 * Limpa o formulário do produto
 */
function Produto_limparForm() {
	//Limpa os campos
	$('#produto-id').val('');
	$('#produto-ean').html('');
	$('#produto-descricao').html('');
	$('#produto-valor').val('');
	$('#produto-quantidade').val('');
	$('#produto-desconto').val('');
	$('#produto-subtotal').html('');
	$('#produto-total').html('');

	//Esconde o form
	$('#pdv-form-produto').hide();

	//Mostra msg
	$('#pdv-form-produto-vazio').show();
}

/**
 * Retorna o número do próximo produto a ser incluído na venda
 *
 * @returns string
 */
function Venda_proxItemNum() {
	var n = 0;

	$('.item').each(function(){
	  item = parseInt($(this).html());
	  n = item > n ? item : n;
	});

	if(n == 999) {
		throw "Não é possível incluir na venda mais de 999 itens.";
	}

	str = '000' + (n+1);

	return (str).substring(str.length - 3);
}

/**
 * Reorganiza o número dos itens após a exclusão de um item da venda
 */
function Venda_recalcItemNum() {
	var totalItens = $('.box-produtos .produto').length;

	if (totalItens > 0){
		$('.box-produtos .produto').each(function(){
			str = '000' + totalItens;

			numRow = (str).substring(str.length - 3);

			$(this).attr('data-row', numRow);
			$(this).find('.item').html(numRow);

			totalItens--;
		});
	}
}

/**
 * Calcula e seta os totais de: Itens, Desconto, A Pagar
 */
function Venda_calculaTotais() {

	var totalItens = $('.box-produtos .produto').length;
	var totalDesconto = 0;
	var totalPagar = 0;
	var subtotalPagar = 0;

	$('.box-produtos .produto').each(function() {
		qtde = accounting.unformat($(this).find('.venda-produto-quantidade').html(), ',');
		desc = accounting.unformat($(this).find('.venda-produto-desconto').html(), ',');
		valorUnit = accounting.unformat($(this).find('.venda-produto-valor').html(), ',');
		subtotal = accounting.unformat($(this).find('.venda-produto-subtotal').val(), ',');

		total = (valorUnit * qtde) - desc;

		totalDesconto += desc;
		totalPagar += total;
		subtotalPagar += subtotal;
	});

	//Formata os valores
	totalGeral = accounting.formatMoney(totalPagar, 'R$', 2, '.', ',');
	totalDesconto = accounting.formatMoney(totalDesconto, 'R$', 2, '.', ',');
	subtotalPagar = accounting.formatMoney(subtotalPagar, 'R$', 2, '.', ',');

	//Seta os valores na Venda
	$('#venda-total-itens').html(totalItens);
	$('#venda-total-desconto').html(totalDesconto);
	$('#venda-valor-subtotal').html(subtotalPagar);
	$('#venda-total-geral').html(totalGeral);
}

/**
 * Salva o pedido via ajax
 *
 * @param Object dadosPedido
 * @returns jQuery Ajax
 */
function Venda_salvarParcial(dadosPedido) {
	return $.ajax({
		method: 'post',
		url: BASE_PATH + 'pedido/salvar-parcial',
		data: dadosPedido,
		beforeSend: function() {
			toastr.warning('<i class="fa fa-spinner fa-pulse"></i> Salvando pedido...');
		},
		success: function(response) {
			toastr.clear();
		}
	}).fail(function() {
		$.confirm({
			title: '<b class="text-danger"><i class="fa fa-exclamation-triangle"></i>&nbsp; Atenção<b>',
			content: 'Erro interno no servidor. Não foi possível gravar o pedido.',
			confirmButton: 'Ok',
			cancelButton: false,
    		confirmButtonClass: 'btn btn-primary btn-flat',
            backgroundDismiss: false,
            closeIcon: false,
			animation: 'scale',
		});
	});	
}

/**
 * Seta os dados no form de pagamento
 * @param dadosPgto array
 */
function Pagamento_setarNoForm(dadosPgto)
{
	//Limpa o form
	Pagamento_limparForm();

	$('#pagamento-id').val(dadosPgto.id);
	$('#pagamento-descricao').html(dadosPgto.descricao);

	var optParc = '';
	for (i=1; i <= dadosPgto.parcelas; i++){
		optParc += '<option>'+ i +'</option>';
	}
	$('#pagamento-parcelas').html(optParc);

	//Seta o Saldo a Pagar
	var saldoPagar = accounting.unformat($('#lblSaldoPagar').html(), ',');

	if(!isNaN(saldoPagar)) {
		$('#pagamento-valor').val(saldoPagar.toFixed(2));
	}
}

/**
 * Limpa o form de pagamento
 */
function Pagamento_limparForm()
{
	$('#pagamento-id').val('');
	$('#pagamento-descricao').html('');
	$('#pagamento-parcelas').html('<option>1</option>');
	$('#pagamento-valor').val('');
}

/**
 * Atualiza os valores do Pagamento: RECEBIDO e SALDO A PAGAR
 */
function Pagamento_atualizaValores(totalPedido, totalRecebido, saldoPagar, troco)
{
	$('#lblTotalRecebido').html(accounting.formatMoney(totalRecebido, 'R$', 2, '.', ','));
	$('#lblSaldoPagar').html(accounting.formatMoney(saldoPagar, 'R$', 2, '.', ','));

	// verifica o status do button de conclusao
	valida_button_conclusao();

	if(troco > 0) {
		$('#rowTroco').show();
		$('#lblTroco').html(accounting.formatMoney(troco, 'R$', 2, '.', ','));
	}else {
		$('#rowTroco').hide();
		$('#lblTroco').html(accounting.formatMoney(0, 'R$', 2, '.', ','));
	}
}

/**
 * Verifica se o button de conclusao deve estar desabilitado ou habilitado
 */
function valida_button_conclusao()
{
	// pega o saldo a pagar
	let saldoPagar = accounting.unformat($('#lblSaldoPagar').html(), ',');

	if(saldoPagar > 0) {
		$('#btnConcluirVenda').prop('disabled', true);
	}else {
		$('#btnConcluirVenda').prop('disabled', false);
	}
}

/**
 * Ajax para cadastro de cliente
 * @param json object cliente
 */
function cadastrarCliente(cliente)
{
	$.ajax({
		method: 'post',
		url: BASE_PATH + 'clientes/create-basic',
		data: cliente,
		beforeSend: function(){
			toastr.warning('<i class="fa fa-spinner fa-pulse"></i> Cadastrando cliente...');
		},
		success: function(retorno){
			retorno = JSON.parse(retorno);
			if(retorno.id > 0){
				$('.modalCadastrarCliente').modal('hide');

				$.confirm({
					title: ' ',
					content: retorno.mensagem,
					confirmButton: '<i class="fa fa-check"></i>',
					cancelButton: false,
					animation: 'scale',
					backgroundDismiss: false,
					closeIcon: false,
				});
			}
			else{
				$('.erros').html(retorno.mensagem).removeClass('hidden');
			}
		}
	})
	.done(function() {
		toastr.remove();
	})
	.fail(function() {
		$.confirm({
			title: '<i class="fa fa-ban"></i> Não foi possível cadastrar o cliente',
			content: '<strong>Erro interno no servidor</strong>',
			confirmButton: '<i class="fa fa-check"></i>',
			cancelButton: false,
			animation: 'scale',
			backgroundDismiss: false,
			closeIcon: false,
		});
	});
}

/**
 * Calcula o total do pedido
 */
function Pedido_calculaTotal() {
	//Pedido Total (subtotal - desconto itens)
	let pedidoSubtotal = accounting.unformat($('#lblSubTotalPedido').html(), ',');
	let pedidoTotal =  pedidoSubtotal - accounting.unformat($('#lblDescontoPedido').html(), ',');

	//Aplica o desconto
	pedidoTotal = getDescontoGeral(pedidoSubtotal, pedidoTotal, false);

	//Envia os valores para a tela
	$('#lblTotalPedido').html(accounting.formatMoney(pedidoTotal, "R$ ", 2, ".", ","));
	$('#lblSaldoPagar').html(accounting.formatMoney(pedidoTotal, "R$ ", 2, ".", ","));

	//Salva parcialmente o pedido informando os dados do desconto
	var dadosPedido = {
		Pedido: {
			id: $('#pedido-id').val(),
			pedido_subtotal: accounting.unformat($('#venda-valor-subtotal').html(), ','),
			pedido_tipo_desconto: $('#pedido-tipo-desconto').val(), //%(1) ou R$(0)
			pedido_desconto_valor: accounting.unformat($('#pagamento-desconto').val(), ','),
			pedido_total: accounting.unformat($('#lblTotalPedido').html(), ','),
		}
	};
	
	Venda_salvarParcial(dadosPedido).done(function(response) {
		let retorno = $.parseJSON(response);	
		let permiteSenhaMaster = false;
		
		try {
			
			if(retorno.success == '1') {
				toastr.success('PDV salvo com sucesso.');
			}else {
				permiteSenhaMaster = (retorno.senhaMaster == undefined ? false : retorno.senhaMaster);
				throw (retorno.message != '' ? retorno.message : 'Erro interno do servidor');
			}
			
		}catch(error) {
			let HTML_SENHA_MASTER = '';
			if(permiteSenhaMaster) {
				HTML_SENHA_MASTER = getHtmlSenhaMaster('btnSenhaMasterVendaSalvar');
			}
	
			$('#mbSenhaMaster').modal('show').find('.modalContentSenhaMaster').html(error + HTML_SENHA_MASTER);
		}
	});
}

var x = window.matchMedia("(max-width: 767px)");
myFunction(x);
x.addListener(myFunction);
	window.addEventListener('resize', function() {
	myFunction(x);
}, true);

function myFunction(x) {
	let page_element = document.getElementById('breadcrumbs-header');
	if(page_element) {
		if(x.matches || screen.width < 768 || window.innerWidth < 768 ) {
			$('.btnedit').hide();
			$('.space1').show();
			$('.space2').show();
			$('.btnedit2').show();
	 	} else if(x.matches || screen.width < 993 || window.innerWidth < 993) {

			$('.btnedit').hide();
			$('.space1').show();
			$('.space2').show();
			$('.btnedit2').show();

		} else if(screen.width < 1086 || window.innerWidth < 1105)  {
			$('.btnedit').show();
			$('.space1').hide();
			$('.space2').hide();
			$('.btnedit2').hide();
		} else {
			$('.btnedit2').hide();
			$('.space1').hide();
			$('.space2').hide();
			$('.btnedit').show();
		}
	}
}

// seta a mascara conforme o tipo de cliente
function setMaskClienteDoc(tipoCliente, limparCampo)
{
	if (limparCampo){
		$('#pedido-cpf').val('');
	}
	
	if (tipoCliente == 1){
		//PJ		
		$('#pedido-cpf').attr('placeholder', '00.000.000/0000-00');
		$('#pedido-cpf').mask('99.999.999/9999-99');
	}
	else {
		//PF
		$('#pedido-cpf').attr('placeholder', '000.000.000-00');
		$('#pedido-cpf').mask('999.999.999-99');
	}
}

function setClientePedido(identi) {
	
	let pedidoId = $('#pedido-id').val();

	$.ajax({
		method: 'POST',
		url: BASE_PATH + 'pedido/adicionar-cliente',
		data: {
			identi: identi,
			pedido: pedidoId,
		},
	});
}

// valida e retorna o desconto geral
function getDescontoGeral(pedidoSubtotal = null, pedidoTotal = null, _validate = true) {
	if(!pedidoSubtotal) {		
		pedidoSubtotal = accounting.unformat($('#lblSubTotalPedido').html(), ',');
	}
	if(!pedidoTotal) {		
		pedidoTotal =  pedidoSubtotal - accounting.unformat($('#lblDescontoPedido').html(), ',');
	}

	//Calcula o desconto aplicado ao pedido
	let tipoDescontoPedido = parseInt($('#pedido-tipo-desconto').val());
	let valorDescontoPedido = accounting.unformat($('#pagamento-desconto').val(), ',');
	let descontoTemp = 0;

	//Calcula o desconto com base no tipo de desconto (porcentagem ou monetário)
	if(tipoDescontoPedido == '1') {
		//Se o desconto for em percentual, calcula o valor correspondente em R$
		descontoTemp = parseFloat((valorDescontoPedido / 100) * pedidoTotal);
	}else {
		descontoTemp = parseFloat(valorDescontoPedido);
	}
	
	if(pedidoTotal > 0 && pedidoSubtotal > 0) {
		//Valida o desconto
		if(descontoTemp >= pedidoTotal) {
			descontoTemp = pedidoTotal - 0.01;
			$('#pagamento-desconto').val(tipoDescontoPedido == '1' ? '99,99' : accounting.formatMoney(descontoTemp, '', 2, '.', ','));
			toastr.warning('O valor do Pedido não pode ficar zerado.');
		}else if (descontoTemp < 0) {
			$('#pagamento-desconto').val('');
			descontoTemp = 0;
			toastr.warning('O desconto não pode ser negativo.');
		}

		// retorna o desconto aplicado
		return parseFloat(pedidoTotal) - descontoTemp;

	}else if(_validate) {
		toastr.warning('O pedido não estar zerado ou vazio.');
		$('#pedido-pedido_desconto_valor').val('');
		return null;
	}
}

function inserirPagamento() {
	var $btn = $('#btnIncluirPagamento');
	
	let dadosPgto = {
		PedidoPagamento: {
			valor: accounting.unformat($('#pagamento-valor').val(), ','),
			parcelas: $('#pagamento-parcelas').val(),
		}
	};

	$.ajax({
		method: 'POST',
		url: BASE_PATH + 'pedido-pagamento/incluir/' + $('#pedido-id').val() + '/' + $('#pagamento-id').val(),
		data: dadosPgto,
		beforeSend: function() {
			$btn.button('loading');
		},
		success: function(data) {
			try {
				$btn.button('reset');

                var retorno = JSON.parse(data);
                if(retorno.success == 1) {
                    toastr.success('Pagamento informado com sucesso!');

                    //Desabilita o campo do desconto do pedido
                    $('#pedido-tipo-desconto').prop('disabled', true);
                    $('#pagamento-desconto').prop('disabled', true);
                    $('#hintDesconto').show();

                    //Atualiza a grid de pagamentos
                    if(retorno.data != null && retorno.data.idpedido != null) {
                    	$.pjax({
                    	    type: 'POST',
                    	    url: BASE_PATH + 'pedido-pagamento/list',
                    	    container: '#PedidoPagamentoGrid-pjax',
                    	    data: {pedido_id: retorno.data.idpedido},
                    	    push: false,
                    	}).always(function() {
                    		//Atualiza a tabela de valores
                            Pagamento_atualizaValores(retorno.data.totalPedido, retorno.data.totalRecebido, retorno.data.saldoPagar, retorno.data.troco);

                            //Limpa o form de pagamento
                            Pagamento_limparForm();
                            
                            //Se está habilitado Concluir a Venda seta o foco para o botão
                        	if($('#btnConcluirVenda').attr('disabled') == undefined) {
                        		$('#btnConcluirVenda').focus();
                        	}
                    	});
                    }else {
                        toastr.error('Falha ao atualizar os pagamentos');
                    }
                }else {
                    toastr.error(retorno.message);
                }
            }catch(error) {
                $.confirm({
					title: 'Ocorreu um erro durante o processo',
					content: error,
					confirmButton: 'Ok',
					cancelButton: false,
					animation: 'scale',
					backgroundDismiss: false,
					closeIcon: false,
				});
            }
		}
	})
	.fail(function() {
		$btn.button('reset');
		$.confirm({
			title: 'Não foi possível incluir o pagamento',
			content: 'Erro interno no servidor',
			confirmButton: 'Ok',
			cancelButton: false,
			animation: 'scale',
			backgroundDismiss: false,
			closeIcon: false,
		});
	});
}

// evento disparado quando a modal de pagamento e exibida
// verifica o credito do cliente, e se houver credito disponivel mostra a modal para inserir o credito
function verificarCredito(btn = null) {
	// toastr config
	toastr.options = {'preventDuplicates': true};
	
	$.post(BASE_PATH + "clientes/verificar-credito", { cliente_id: $('.clientePDV').attr('id'), pedido_id: $('#pedido-id').val() }, function(response) {
		let retorno = JSON.parse(response);
		let saldoPagar = accounting.unformat($('#lblSaldoPagar').html(), ',');
		
		// success
		if(retorno.success == 1) {
			
			let creditoValor = retorno.data.credito;

			if(retorno.data.credito > 0 && saldoPagar > 0) {
				let modalCredito = $('#mbCredito');
				
				// valida o saldo reestante a pagar
				if(saldoPagar < retorno.data.credito) {
					creditoValor = saldoPagar;
				}
				
				modalCredito.find('#_creditoDisponivel').text(accounting.formatMoney(retorno.data.credito, 'R$ ', 2, '.', ','));
				modalCredito.find('#_creditoValor').val(creditoValor);
				modalCredito.find('#_creditoValor-disp').val(accounting.formatMoney(creditoValor, 'R$ ', 2, '.', ','));
				modalCredito.find('#_creditoSaldo').val(accounting.formatMoney((retorno.data.credito - creditoValor), 'R$ ', 2, '.', ','));
				modalCredito.modal('show');
				
				if(btn != null) {
					modalCredito.find('#_creditoPagamento').val(btn.data('pagamento'));
				}
			}else if(saldoPagar <= 0 && btn != null) {
				toastr.warning('o saldo a pagar já esta zerado.');
			}else if(retorno.data.credito <= 0 && btn != null) {
				toastr.warning('O cliente não possui crédito disponível.');
			}
		// erro esperado
		}else if(retorno.success == 2) {
			toastr.warning(retorno.message);
		// erro sem tratamento
		}else {
			toastr.error(retorno.message);
		}
	});
}
/**
 * FIM DAS FUNÇÕES
 */
