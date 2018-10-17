// objeto da negociacao
function Negociacao() {
	// armazena o contexto
	const self = this;
	
	// atributos do objeto
	self.id = null;
	self.id_contrato = null;
	self.id_credor = null;
	self.id_campanha = null;
	self.data_negociacao = '';
	self.tipo = '';
	self.subtotal = 0;
	self.desconto = 0;
	self.receita = 0;
	self.total = 0;
	self.desconto_encargos = 0;
	self.desconto_principal = 0;
	self.desconto_honorarios = 0;
	self.desconto_total = 0;
	self.observacao = '';
	self.valor_entrada = '';
	self.taxa_parcelado = '';
	
	// array com a lista de parcelas da negociacao
	self.parcelas = [];
	
	// configura o máximo de desconto permitido
	self.desconto_max_encargos = 100.0000;
	self.desconto_max_principal = 100.0000;
	self.desconto_max_honorarios = 100.0000;
	self.desconto_max_total = 9999;
	
	// carrega os dados no objeto
	self.load = () => {
		self.id = $('#negociacao-id').val() || null;
		self.id_contrato = $('#negociacao-contrato').val() || null;
		self.id_credor = $('#negociacao-credor').val() || null;
		self.id_campanha = $('#negociacao-campanha').val() || null;
		self.data_negociacao = $('#negociacao-data').val() || '';
		self.tipo = $('#negociacao-tipo').val() || 'V';
		self.subtotal = $('#negociacao-subtotal').data('value') || 0;
		self.desconto = $('#negociacao-desconto').data('value') || 0;
		self.receita = $('#negociacao-receita').data('value') || 0;
		self.total = $('#negociacao-total').data('value') || 0;
		self.observacao = $('#negociacao-observacao').val() || '';
		self.valor_entrada = $('#valor-entrada').val() || 0;
		self.taxa_parcelado = $('#parcelado-valor-taxa').data('valor') || '';
		
		// atualiza os valores de desconto
		self.loadDesconto();
	}
	
	// carrega os valores de desconto
	self.loadDesconto = () => {
		// seta os descontos
		self.desconto_encargos = $('#desconto_encargos-disp').val() || 0;
		self.desconto_principal = $('#desconto_principal-disp').val() || 0;
		self.desconto_honorarios = $('#desconto_honorarios-disp').val() || 0;
		self.desconto_total = $('#desconto_total-disp').val() || 0;
		
		// seta os descontos máximos
		self.desconto_max_encargos = $('#desc-encargos-max').val();
		self.desconto_max_honorarios = $('#desc-honorario-max').val();
		self.desconto_max_principal = $('#desc-principal-max').val();
		
		// remove a mascara dos descontos
		self.desconto_encargos = accounting.unformat(self.desconto_encargos, '.');
		self.desconto_principal = accounting.unformat(self.desconto_principal, '.');
		self.desconto_honorarios = accounting.unformat(self.desconto_honorarios, '.');
		self.desconto_total = accounting.unformat(self.desconto_total, '.');
		
		return true;
	}
	
	// valida o limite de desconto concedido
	self.validarDesconto = () => {
		// configura para nao duplicar os toastr
		toastr.options = {preventDuplicates: true};
		
		// valida o desconto máximo
		if (self.desconto_encargos > 0 && self.desconto_encargos > self.desconto_max_encargos && self.desconto_max_encargos > 0) {
			toastr.warning(`O desconto dos encargos é superior ao limite permitido.<br>(Limite: ${self.desconto_max_encargos}%)`).attr('style', 'width: 450px !important');
			// seta o desconto para o máximo permitido
			$('#desconto_encargos-disp').val(self.desconto_max_encargos+'%').trigger('change');
			return false;
		}
		if (self.desconto_principal > 0 && self.desconto_principal > self.desconto_max_principal && self.desconto_max_principal > 0) {
			toastr.warning(`O desconto dos principal é superior ao limite permitido.<br>(Limite: ${self.desconto_max_principal}%)`).attr('style', 'width: 450px !important');
			// seta o desconto para o máximo permitido
			$('#desconto_principal-disp').val(self.desconto_max_principal+'%').trigger('change');
			return false;
		}
		if (self.desconto_honorarios > 0 && self.desconto_honorarios > self.desconto_max_honorarios && self.desconto_max_honorarios > 0) {
			toastr.warning(`O desconto dos honorários é superior ao limite permitido.<br>(Limite: ${self.desconto_max_honorarios}%)`).attr('style', 'width: 450px !important');
			// seta o desconto para o máximo permitido
			$('#desconto_honorarios-disp').val(self.desconto_max_honorarios+'%').trigger('change');
			return false;
		}
		if (self.desconto_total > 0 && self.desconto_total > self.desconto_max_total && self.desconto_max_total > 0) {
			toastr.warning(`O desconto dos total é superior ao limite permitido.<br>(Limite: ${self.desconto_max_total}%)`).attr('style', 'width: 450px !important');
			// seta o desconto para o máximo permitido
			$('#desconto_total-disp').val(self.desconto_max_total+'%').trigger('change');
			return false;
		}
		
		return true;
	}
	
	// recalcula a negociacao
	self.calcularNegociacao = () => {
		// calcula o total
		if (self.desconto_total > 0) {			
			// mensagem de aviso
			toastr.warning('Funcionalidade ainda em desenvolvimento.');

			// pega o valor principal das parcelas
			let principal = $('.table-parcela .parcelas-total-principal').data('value');
			let honorarioTaxa = $('.table-parcela .parcelas-total-honorarios').data('taxa');
			let encargos = $('.table-parcela .parcelas-total-multa').data('value') + $('.table-parcela .parcelas-total-juros').data('value');
			
			// verifica se o valor total desejado é menor ou igual o valor principal das parcelas
			// se sim, remove os encargos e os honorarios
			if (self.desconto_total <= principal) {
				// reseta os descontos normais
				self.desconto_encargos = 100;
				self.desconto_principal = 0;
				self.desconto_honorarios = 100;
				
				// seta os descontos nos inputs
				$('#desconto_encargos-disp').val('100.0000%');
				$('#desconto_principal-disp').val('0.0000%');
				$('#desconto_honorarios-disp').val('100.0000%');
				
				// atualiza cada parcela do contrato
				// removendo todos os descontos
				self.calcularParcelas();
				
				// calcula o desconto em porcentagem
				self.desconto_principal = (Math.abs(principal - self.desconto_total) / principal) * 100;
				
				// seta o desconto principal
				$('#desconto_principal-disp').val(accounting.formatMoney(self.desconto_principal, '', 4, '.', '.') + '%');
			} else {
				// calcula o valor principal - encargos
				let principalTemp = principal + (principal * (honorarioTaxa / 100));

				// verifica se o total desejado é menor que o total + honorarios
				// se sim, concede o desconto total nos encargos e calcula o desconto dos honorarios
				// senao, calcula o desconto dos encargos
				if (self.desconto_total <= principalTemp) {
					// desconto total dos encargos
					self.desconto_encargos = 100;
					
					// seta os descontos nos inputs
					$('#desconto_encargos-disp').val('100.0000%');
					$('#desconto_principal-disp').val('0.0000%');
					$('#desconto_honorarios-disp').val('0.0000%');
					
					// se o valor desejado for maior que o principal, entao calcula o desconto dos honorarios
					if (self.desconto_total > principal) {
						let honorariosTemp = principal * (honorarioTaxa / 100);
						self.desconto_honorarios = ((principalTemp - self.desconto_total) / honorariosTemp) * 100;
						
						// seta os descontos dos honorarios
						$('#desconto_honorarios-disp').val(accounting.formatMoney(self.desconto_honorarios, '', 4, '.', '.') + '%');
					}
				} else {
					// desconto total dos encargos
					self.desconto_encargos = 100;
					
					// seta os descontos nos inputs
					$('#desconto_encargos-disp').val('0.0000%');
					$('#desconto_principal-disp').val('0.0000%');
					$('#desconto_honorarios-disp').val('0.0000%');
					
					// se o valor desejado for maior que o principal, entao calcula o desconto dos honorarios
					if (self.desconto_total > principal) {
						// calcula o total removendo os honorarios
						let totalTemp = Math.round(((principal + encargos) + ((principal + encargos) * (honorarioTaxa / 100))) * 100) / 100;
						totalTemp = Math.round((totalTemp - (totalTemp * (honorarioTaxa / 100))) * 100) / 100;
						
						// calcula o desconto total dos encargos 
						self.desconto_total = self.desconto_total - (self.desconto_total * (honorarioTaxa / 100));
						self.desconto_total = Math.round((totalTemp - self.desconto_total) * 100) / 100;
						
						// calcula o desconto dos encargos
						self.desconto_encargos = Math.round((100 - ((self.desconto_total / encargos) * 100)) * 100) / 100;
						
						// seta os descontos dos honorarios
						$('#desconto_encargos-disp').val(accounting.formatMoney(self.desconto_encargos, '', 4, '.', '.') + '%');
					}
				}
			}	
		}
		
		// atualiza cada parcela do contrato
		self.calcularParcelas();
		
		// calcula o desconto da receita
		if (self.receita > 0 && self.desconto_honorarios > 0) {
			self.desconto += Math.round((self.receita * (self.desconto_honorarios / 100)) * 100) / 100;
			self.receita -= Math.round((self.receita * (self.desconto_honorarios / 100)) * 100) / 100;
		}
		
		// atualiza os totais da parcela
		$('.panel-calculo .parcelas-total-honorarios').data('value', self.receita).html(`<b>${accounting.formatMoney(self.receita, 'R$ ', 2, '.', ',')}</b>`);
		$('.panel-calculo .parcelas-total-total').data('value', self.subtotal).html(`<b>${accounting.formatMoney(self.subtotal, 'R$ ', 2, '.', ',')}</b>`);
		
		// calcula o total
		self.total = Math.round((self.subtotal - self.desconto) * 100) / 100;
		
		// atualiza os valores da self
		$('#negociacao-subtotal').data('value', self.subtotal).text(accounting.formatMoney(self.subtotal, 'R$ ', 2, '.', ','));
		$('#negociacao-desconto').data('value', self.desconto).text(accounting.formatMoney(self.desconto, 'R$ ', 2, '.', ','));
		$('#negociacao-receita').data('value', self.receita).text(accounting.formatMoney(self.receita, 'R$ ', 2, '.', ','));
		$('#negociacao-total').data('value', self.total).text(accounting.formatMoney(self.total, 'R$ ', 2, '.', ','));
		
		// calcula o valor das parcelas
		// se o tipo da negociacao for parcelado
		if (self.tipo == 'P') {		
			self.calcularValorParcela($('#quant-parcelas').val());
		}

		return true;
	}
	
	// calcula os valores de cada parcela
	self.calcularParcelas = () => {
		// reseta os valores da negociacao
		self.subtotal = 0;
		self.total = 0;
		self.desconto = 0;
		self.receita = 0;
		
		// atualiza cada parcela do contrato
		$('.panel-calculo .contrato-parcela').each(function() {
			// variaveis de controle dos valores da parcela
			let [total, receita, encargos, principal] = [0, 0, 0, 0];
			
			// variaveis de controle dos descontos
			let [encargosDesconto, principalDesconto] = [0, 0];
			
			// soma o total
			principal = Number($(this).find('td.principal').data('value'));
			
			// soma os encargos
			encargos += Number($(this).find('td.multa').data('value'));
			encargos += Number($(this).find('td.juros').data('value'));
		
			// calcula o desconto dos encargos
			if (self.desconto_encargos > 0) {
				encargosDesconto = Number(encargos * (self.desconto_encargos / 100));
			}
			// calcula o desconto principal
			if (self.desconto_principal > 0) {
				principalDesconto = Number(principal * (self.desconto_principal / 100));
			}

			// calcula a receita da parcela
			let taxaHonorario = Number($(this).find('td.honorario').data('value'));		
			receita = ((principal - principalDesconto) + (encargos - encargosDesconto)) * (taxaHonorario / 100);
			
			// calcula o total da parcela
			total = Math.round((principal + encargos + receita) * 100) / 100;
			
			// arredonda a receita para baixo
			receita = Math.round(receita * 100) / 100;
			
			// atualiza os valores na listagem
			$(this).find('td.honorario').text(accounting.formatMoney(receita, 'R$ ', 2, '.', ','));
			$(this).find('td.total').text(accounting.formatMoney(total, 'R$ ', 2, '.', ','));
			
			// seta o total da negociacao
			self.receita += receita;
			self.subtotal += total;
			self.desconto += encargosDesconto + principalDesconto;
		});
	}
	
	// calcula o valor de vada parcela da negociacao
	self.calcularValorParcela = quantParcelas => {
		self.parcelas = [];
		self.taxa_parcelado = 0;
		let taxa = 0;
		
		// valida o valor de entrada
		if (self.valor_entrada == undefined || isNaN(self.valor_entrada)) {
			self.valor_entrada = 0;
		}
		// valida q quantidade de parcelas
		if (quantParcelas == undefined || !quantParcelas) {
			quantParcelas = 0;
		}
				
		// calcula o valor das parcelas
		let valorParcela = (self.total - self.valor_entrada);
		
		// calcula a taxa de juros de cada parcela
		if (quantParcelas > 0) {			
			// calcula a taxa de juros da parcela
			taxa = (valorParcela * 0.15) * quantParcelas;
			taxa = taxa / (self.valor_entrada > 0 ? quantParcelas - 1 : quantParcelas); 
		
			// calcula a taxa de juros total
			self.taxa_parcelado = Math.floor((taxa * (self.valor_entrada > 0 ? quantParcelas - 1 : quantParcelas)) * 100) / 100;
		}
		
		// divide o valor das parcelas
		valorParcela = self.valor_entrada > 0 ? (valorParcela / (quantParcelas - 1)) : (valorParcela / quantParcelas);
		valorParcela = Math.round(valorParcela * 100) / 100;
		
		// soma a taxa de juros no valor da parcela
		valorParcela = Math.round((valorParcela + taxa) * 100) / 100;
		
		// calcula o vencimento da primeira parcela
		let vencimento = new Date();
		let dd = vencimento.getDate();
		let mm = vencimento.getMonth() + 1;
		let yyyy = vencimento.getFullYear();

		// formata o dia e mes
		if (dd < 10) {
		    dd = '0'+dd
		} 
		if (mm < 10) {
		    mm = '0'+mm
		} 

		// seta a primeira parcela (valor de entrada quando houver)
		self.parcelas.push({
			num: 1,
			vencimento: `${dd}/${mm}/${yyyy}`,
			valor: self.valor_entrada > 0 ? self.valor_entrada : valorParcela,
		});
		
		// seta cada parcela
		for (let i = 1; i < quantParcelas; i++) {
			// calcula o vencimento
			vencimento.setMonth(vencimento.getMonth() + 1);
			dd = vencimento.getDate();
			mm = vencimento.getMonth() + 1;
			yyyy = vencimento.getFullYear();

			// formata o dia e mes
			if (dd < 10) {
			    dd = '0'+dd
			} 
			if (mm < 10) {
			    mm = '0'+mm
			}
			
			self.parcelas.push({
				num: i + 1,
				vencimento: `${dd}/${mm}/${yyyy}`,
				valor: valorParcela,
			});
		}
		
		// adiciona o valor total de taxa
		$('#parcelado-valor-taxa').data('value', self.taxa_parcelado).text(accounting.formatMoney(self.taxa_parcelado, 'R$ ', 2, '.', ','));

		// seta o novo valor total
		self.total = self.total + self.taxa_parcelado;
		$('#negociacao-total').data('value', 100).text(accounting.formatMoney(self.total, 'R$ ', 2, '.', ','));
	}
};

// eventos da página
$(document).ready(function() {
	// cria o objeto de negociacao
	let negociacao = new Negociacao($('.panel-calculo'));
	
	// carrega os dados na negociacao
	negociacao.load();
	// calcula os descontos
	negociacao.calcularNegociacao();
	
	// altera a forma de negociacao
	$('body').on('change', '#negociacao-tipo', function() {
		// seta o tipo da negociacao
		negociacao.tipo = this.value;
		// calcula a negociacao e o valor das parcelas
		negociacao.calcularNegociacao();
		
		// p => Parcelado
		// v => A Vista
		if (this.value == 'P') {
			$('.negociacao-a-vista').slideUp(function() {
				$('.negociacao-parcelado').slideDown();
			});
		} else {
			$('.negociacao-parcelado').slideUp(function() {				
				$('.negociacao-a-vista').slideDown();
			});
		}
	});
	
	// salva a negociacao
	$('body').on('click', '#salvar-negociacao', function() {
		// carrega os dados na negociacao
		negociacao.load();
		
		// calcula e valida o desconto antes de enviar a requisicao
		if (negociacao.validarDesconto() && negociacao.calcularNegociacao()) {
			// salva o html da negociacao
			const negociacaoContent = $('.panel-calculo .panel-body').html();
			
			// seta a mensagem de loading
			$('.panel-calculo .panel-body').html('<br><br><br><h2 class="text-primary text-center"><i class="fa fa-spinner fa-pulse"></i>&nbsp; Carregando ...</h2><br><br><br>');
			
			// envia a requisicao para salvar a negociacao
			$.post(BASE_PATH + 'negociacao/salvar', {Negociacao: negociacao}, function(response) {
				let retorno = JSON.parse(response);
				
				if (retorno.success == true) {
					// mensagem de sucesso
					toastr.success('A negociação foi salva com sucesso.');
					// seta o id da negociacao
					$('#negociacao-id').val(retorno.id);
					// seta o conteudo da negociacao
					$('.panel-calculo .panel-body').html(retorno.content);
					// atualiza a lista de acionamentos
					$('.panel-acionamento .panel-body').load(BASE_PATH+'acionamento/index?contrato='+$('#id-contrato').val());
					
					// carrega os dados na negociacao
					negociacao.load();
					// calcula os descontos
					negociacao.calcularNegociacao();
				} else {
					// mensagem de erro
					toastr.error('Houve um erro interno ao tentar salvar a negociação.');
				}
			}).fail(function() {
				$('.panel-calculo .panel-body').html(negociacaoContent);
			});
		}		
	});
	
	// cancela a alteração e reccarega os dados na negociacao
	$('body').on('click', '#cancelar-alteracao', function() {
		// salva o html da negociacao
		const negociacaoContent = $('.panel-calculo .panel-body').html();
		
		// pega o id da negociacao e do contrato
		const negociacao = $('#negociacao-id');
		const contratoId = $('#negociacao-contrato').val();
		
		// seta a mensagem de loading
		$('.panel-calculo .panel-body').html('<br><br><br><h2 class="text-primary text-center"><i class="fa fa-spinner fa-pulse"></i>&nbsp; Carregando ...</h2><br><br><br>');
		
		$.get(BASE_PATH + 'negociacao/index?id='+negociacao.val()+'&contratoId='+contratoId, function(response) {
			const retorno = JSON.parse(response);
			
			if (retorno.success == true) {
				negociacao.val(retorno.id);
				$('.panel-calculo .panel-body').html(retorno.content);
			}
		}).fail(function() {
			$('.panel-calculo .panel-body').html(negociacaoContent);
		});
	});
	
	// calcula os descontos da negociacao
	$('body').on('change', '.negociacao-descontos:hidden', function(e) {
		// verifica se o evento foi ativiado pelo usuario
		if (e.isTrigger) {
			// carrea e valida os descontos
			negociacao.loadDesconto(); 
			negociacao.validarDesconto();
			
			// calcula os descontos
			negociacao.calcularNegociacao();
			
			// ativa o evento de change para recalcular as parcelas
			$('#quant-parcelas').trigger('change');
			
			return false;
		}
	});
	
	// abre a modal de acionamentos
	$('body').on('click', '#add-acionamento', function() {
		$('#modal-acionamento').modal('show');
	});
	
	// salva o acionamento
	$('body').on('click', '#save-acionamento', function() {
		// seta os dados enviados por post
		const post = {
			cliente: $('#id-cliente').data('id'),
			contrato: $('#id-contrato').val(),
			tipo: $('#acionamento-tipo').val(),
			titulo: $('#acionamento-titulo').val(),
			data: $('#acionamento-data').val(),
			hora: $('#acionamento-hora').val(),
			descricao: $('#acionamento-desc').val(),
		};
	
		// valida os dados necessários
		if (!post.cliente || !post.contrato || !post.tipo || !post.titulo || !post.data || !post.descricao) {
			toastr.options = {preventDuplicates: true};
			toastr.warning('Por favor, preencha todos os dados obrigatórios. (Tipo, Título, Data, Descrição)');
			return false;
		}
	
		// envia a requisicao
		$.post(BASE_PATH + 'acionamento/create', post, function(response) {
			const retorno = JSON.parse(response);
			
			if (retorno.success == 1) {
				// fecha a modal
				$('#modal-acionamento').modal('hide');
				// mensagem de sucesso
				toastr.success('Acionamento salvo com sucesso.');
				
				// reseta o form
				$('#acionamento-tipo').val('');
				$('#acionamento-titulo').val('');
				$('#acionamento-data').val($('#acionamento-data').data('value')).trigger('change');
				$('#acionamento-hora').val('').trigger('change');
				$('#acionamento-desc').val('');
				
				// atualiza a lista de acionamentos
				$('.panel-acionamento .panel-body').load(BASE_PATH+'acionamento/index?contrato='+post.contrato);
			} else {
				// mensagem de sucesso
				toastr.error(retorno.message);
			}
		});
	});
	
	// exlui um acionamento
	$('body').on('click', '.delete-acionamento', function() {
		const id = $(this).data('id');
		
		// mensagem de confirmação
        $.confirm({
            content: 'Você deseja mesmo exclúir este item?',
            backgroundDismiss: true,
            buttons: {
				ok: { 
					action: function() {
						// envia a requisição para excluir
						$.post(BASE_PATH+'acionamento/delete?id='+id, function(response) {
							const retorno = JSON.parse(response);
							
							if (retorno.success == true) {
								// mensagem de sucesso
								toastr.success('O acionamento foi excluído com sucesso.');
								// atualiza a lista de acionamentos
								$('.panel-acionamento .panel-body').load(BASE_PATH+'acionamento/index?contrato='+$('#id-contrato').val());	
							} else {
								// mensagem de erro
								toastr.error('Não foi possível excluír o acionamento.');
							}
						});
                    },
				},
			},
        });
	});
	
	// altera o status da negociação
	$('body').on('click', '#alterar-negociacao', function() {
		// salva o html da negociacao
		const negociacaoContent = $('.panel-calculo .panel-body').html();
		const idNegociacao = $('#negociacao-id').val();
		
		// verifica se a negociacao existe
		if (!idNegociacao) {
			toastr.warning('A negociação não foi encontrada. Atualize a página e tente novamente.');
			return false;
		}
		
		// verifica se alguma parcela ja foi faturada
		if ($('.faturar-parcela[data-status="1"]').length > 0) {
			toastr.options = {preventDuplicates: true};
		    toastr.warning('Não é possível abrir o contrato enquanto houver parcelas já faturadas.');
		    return false;
		}
		
		// seta a mensagem de loading
		$('.panel-calculo .panel-body').html('<br><br><br><h2 class="text-primary text-center"><i class="fa fa-spinner fa-pulse"></i>&nbsp; Carregando ...</h2><br><br><br>');
		
		// envia a requisicao para alterar o status da negociação
		$.get(BASE_PATH+'negociacao/alterar?id='+idNegociacao, function(response) {
			const retorno = JSON.parse(response);
			
			// verifica se houve erro
			if (retorno.success != true) {
				toastr.warning(retorno.message);
			}
			
			// seta o conteudo da negociacao
			$('.panel-calculo .panel-body').html(retorno.content);
			// atualiza a lista de acionamentos
			$('.panel-acionamento .panel-body').load(BASE_PATH+'acionamento/index?contrato='+$('#id-contrato').val());
		}).fail(function() {
			$('.panel-calculo .panel-body').html(negociacaoContent);
		});
	});
	
	// fatura a negociacao
	$('body').on('click', '#faturar-contrato', function() {
		// remove o focus
		this.blur();
		// pega a negociacao
		const idNegociacao = $('#negociacao-id').val();
		
		// verifica se a negociacao existe
		if (!idNegociacao) {
			toastr.warning('A negociação não foi encontrada. Atualize a página e tente novamente.');
			return false;
		}
		
		// verifica se a neociacao ja esta faturada e exibe um alerta de confirmação
		if ($(this).data('status') == 2) {			
			// mensagem de confirmação
			$.confirm({
				content: 'Você deseja mesmo estornar esta negociação? Todas as parcelas também serão estornadas.',
				backgroundDismiss: true,
				buttons: {
					ok: { 
						action: function() {
							// salva o html da negociacao
							const negociacaoContent = $('.panel-calculo .panel-body').html();
							
							// seta a mensagem de loading
							$('.panel-calculo .panel-body').html('<br><br><br><h2 class="text-primary text-center"><i class="fa fa-spinner fa-pulse"></i>&nbsp; Carregando ...</h2><br><br><br>');
							
							// envia a requisicao para alterar o status da negociação
							$.get(BASE_PATH+'negociacao/faturar?id='+idNegociacao, function(response) {
								const retorno = JSON.parse(response);
								
								// verifica se houve erro
								if (retorno.success != true) {
									toastr.warning(retorno.message);
								}
								
								// seta o conteudo da negociacao
								$('.panel-calculo .panel-body').html(retorno.content);
								// atualiza a lista de acionamentos
								$('.panel-acionamento .panel-body').load(BASE_PATH+'acionamento/index?contrato='+$('#id-contrato').val());
							}).fail(function() {
								$('.panel-calculo .panel-body').html(negociacaoContent);
							});
						},
					},
				},
			});
		} else {
			// salva o html da negociacao
			const negociacaoContent = $('.panel-calculo .panel-body').html();
			
			// seta a mensagem de loading
			$('.panel-calculo .panel-body').html('<br><br><br><h2 class="text-primary text-center"><i class="fa fa-spinner fa-pulse"></i>&nbsp; Carregando ...</h2><br><br><br>');
			
			// envia a requisicao para alterar o status da negociação
			$.get(BASE_PATH+'negociacao/faturar?id='+idNegociacao, function(response) {
				const retorno = JSON.parse(response);
				
				// verifica se houve erro
				if (retorno.success != true) {
					toastr.warning(retorno.message);
				}
				
				// seta o conteudo da negociacao
				$('.panel-calculo .panel-body').html(retorno.content);
				// atualiza a lista de acionamentos
				$('.panel-acionamento .panel-body').load(BASE_PATH+'acionamento/index?contrato='+$('#id-contrato').val());
			}).fail(function() {
				$('.panel-calculo .panel-body').html(negociacaoContent);
			});
		}
		
		return false;
	});
	
	// fatura uma parcela específica
	$('body').on('click', '.faturar-parcela', function() {
		// remove o focus
		this.blur();
		// pega a parcela
		const idParcela = $(this).data('id');
		
		// verifica se a negociacao existe
		if (!$('#negociacao-id').val()) {
			toastr.warning('A negociação não foi encontrada. Atualize a página e tente novamente.');
			return false;
		}
		// verifica o id da parcela
		if (!idParcela) {
			toastr.warning('Houve um erro ao verificar a parcela. Atualize a página e tente novamente.');
			return false;
		}
		
		// verifica se a neociacao ja esta faturada e exibe um alerta de confirmação
		if ($(this).data('status') == 1) {			
			// mensagem de confirmação
			$.confirm({
				content: 'Você deseja mesmo estornar esta parcela?',
				backgroundDismiss: true,
				buttons: {
					ok: { 
						action: function() {
							// salva o html da negociacao
							const negociacaoContent = $('.panel-calculo .panel-body').html();
							
							// seta a mensagem de loading
							$('.panel-calculo .panel-body').html('<br><br><br><h2 class="text-primary text-center"><i class="fa fa-spinner fa-pulse"></i>&nbsp; Carregando ...</h2><br><br><br>');
							
							// envia a requisicao para alterar o status da negociação
							$.get(BASE_PATH+'negociacao-parcela/faturar?id='+idParcela, function(response) {
								const retorno = JSON.parse(response);
								
								// verifica se houve erro
								if (retorno.success != true) {
									toastr.warning(retorno.message);
								}
								
								// seta o conteudo da negociacao
								$('.panel-calculo .panel-body').html(retorno.content);
								// atualiza a lista de acionamentos
								$('.panel-acionamento .panel-body').load(BASE_PATH+'acionamento/index?contrato='+$('#id-contrato').val());
							}).fail(function() {
								$('.panel-calculo .panel-body').html(negociacaoContent);
							});
						},
					},
				},
			});
		} else {
			// salva o html da negociacao
			const negociacaoContent = $('.panel-calculo .panel-body').html();
			
			// seta a mensagem de loading
			$('.panel-calculo .panel-body').html('<br><br><br><h2 class="text-primary text-center"><i class="fa fa-spinner fa-pulse"></i>&nbsp; Carregando ...</h2><br><br><br>');
			
			// envia a requisicao para alterar o status da negociação
			$.get(BASE_PATH+'negociacao-parcela/faturar?id='+idParcela, function(response) {
				const retorno = JSON.parse(response);
				
				// verifica se houve erro
				if (retorno.success != true) {
					toastr.warning(retorno.message);
				}
				
				// seta o conteudo da negociacao
				$('.panel-calculo .panel-body').html(retorno.content);
				// atualiza a lista de acionamentos
				$('.panel-acionamento .panel-body').load(BASE_PATH+'acionamento/index?contrato='+$('#id-contrato').val());
			}).fail(function() {
				$('.panel-calculo .panel-body').html(negociacaoContent);
			});
		}
		
		return false;
	});
	
	// abre o contrato para negociacao
	$('body').on('click', '.set-contrato', function() {
		const idContrato = $(this).data('id'); 
		
		// valida se o cotrato já esta aberto
		if (idContrato == $('#id-contrato').val()) {
			toastr.info('Este contrato já esta aberto.');
			return false;
		}
		
		// mensagem de loading
		toastr.clear();
		toastr.info('<i class="fa fa-spinner fa-pulse"></i>&nbsp; Carregando Contrato ...');
		
		// envia a requisição
		$.get(BASE_PATH+'contrato/negociacao/'+idContrato, function(response) {
			const retorno = JSON.parse(response);
			
			// seta o conteudo
			if (retorno.success == true) {				
				$('section.main-content').html(retorno.content);
			}
		}).fail(function() {
			toastr.error('Não foi possível abrir o contrato.');
		});
	});
	
	// recalcula o valor das parcelas da negociacao
	$('body').on('change', '#valor-entrada', function() {
		// valida o valor do contrato e o valor de entrada
		if (negociacao.total == 0) {
			toastr.warning('O valor do contrato já está zerado.');
			return false;
		}
		if (this.value >= negociacao.total) {
			toastr.warning('O valor da entrada não pode ser maior ou igual o valor total do contrato.');
			$('#valor-entrada-disp').val('R$ 0,00').trigger('change');
			return false;
		}
		
		// adiciona o valor de entrada no objeto de negociacao
		negociacao.valor_entrada = this.value;
		// ativa o evento de change para recalcular as parcelas
		$('#quant-parcelas').trigger('change');
	});
	
	// calcula as parcelas da negociacao
	$('body').on('change', '#quant-parcelas', function() {
		const listaParcelas = document.getElementById('tbody-lista-parcelas');
		
		// limpa a lista de parcelas
		listaParcelas.innerHTML = '';
		
		// calcula a negociacao e o valor das parcelas
		negociacao.calcularNegociacao();

		// verifica se há parcelas
		if (this.value != undefined && this.value > 0) {	
			// cria as parcelas
			if (negociacao.parcelas != undefined && negociacao.parcelas.length > 0) {
				negociacao.parcelas.forEach(function(parcela) {					
					const tr = document.createElement('tr');		        
					
					// cria os colunas da linha
					const tdNum = document.createElement('td');
					const tdVencimento = document.createElement('td');
					const tdValor = document.createElement('td');
					
					// adiciona os valores de cada coluna
					tdNum.textContent = parcela.num;
					tdNum.classList = 'text-center';
					tdVencimento.textContent = parcela.vencimento;
					tdValor.textContent = accounting.formatMoney(parcela.valor, 'R$ ', 2, '.', ',');
					
					// adiciona os valores na linha e a linha na tabela
					tr.appendChild(tdNum);
					tr.appendChild(tdVencimento);
					tr.appendChild(tdValor);
					listaParcelas.appendChild(tr);	
				});
			}
		}
	});
});

