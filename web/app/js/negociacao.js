// objeto da negociacao
function Negociacao(context) {
	// armazena o contexto
	var self = this;
	// seta o contexto
	var context = context;
	
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
	
	// configura o máximo de desconto permitido
	self.desconto_max_encargos = 75;
	self.desconto_max_principal = 75;
	self.desconto_max_honorarios = 75;
	self.desconto_max_total = 9999;
	
	// carrega os dados no objeto
	self.load = () => {
		self.id = $('#negociacao-id', context).val() || null;
		self.id_contrato = $('#negociacao-contrato', context).val() || null;
		self.id_credor = $('#negociacao-credor', context).val() || null;
		self.id_campanha = $('#negociacao-campanha', context).val() || null;
		self.data_negociacao = $('#negociacao-data', context).val() || '';
		self.tipo = $('#negociacao-tipo', context).val() || 0;
		self.subtotal = $('#negociacao-subtotal', context).data('value') || 0;
		self.desconto = $('#negociacao-desconto', context).data('value') || 0;
		self.receita = $('#negociacao-receita', context).data('value') || 0;
		self.total = $('#negociacao-total', context).data('value') || 0;

		// atualiza os valores de desconto
		self.loadDesconto();
	}
	
	// carrega os valores de desconto
	self.loadDesconto = () => {
		self.desconto_encargos = $('#desconto_encargos-disp', context).val() || 0;
		self.desconto_principal = $('#desconto_principal-disp', context).val() || 0;
		self.desconto_honorarios = $('#desconto_honorarios-disp', context).val() || 0;
		self.desconto_total = $('#desconto_total-disp', context).val() || 0;
		self.observacao = $('#negociacao-observacao', context).val() || '';
		
		// remove a mascara dos descontos
		self.desconto_encargos = accounting.unformat(self.desconto_encargos, '.');
		self.desconto_principal = accounting.unformat(self.desconto_principal, '.');
		self.desconto_honorarios = accounting.unformat(self.desconto_honorarios, '.');
		self.desconto_total = accounting.unformat(self.desconto_total, '.');
	}
	
	// valida o limite de desconto concedido
	self.validarDesconto = () => {
		if (self.desconto_encargos > 0 && self.desconto_encargos > self.desconto_max_encargos) {
			toastr.warning('O desconto dos encargos é superior ao limite permitido.');
			return false;
		}
		if (self.desconto_principal > 0 && self.desconto_principal > self.desconto_max_principal) {
			toastr.warning('O desconto principal é superior ao limite permitido.');
			return false;
		}
		if (self.desconto_honorarios > 0 && self.desconto_honorarios > self.desconto_max_honorarios) {
			toastr.warning('O desconto dos honorários é superior ao limite permitido.');
			return false;
		}
		if (self.desconto_total > 0 && self.desconto_total > self.desconto_max_total) {
			toastr.warning('O desconto total é superior ao limite permitido.');
			return false;
		}

		// seta o valor total
		self.total = self.subtotal;
		self.desconto = 0;
		self.receita = $('.parcelas-honorarios', context).data('value');
		
		// calcula o desconto dos encargos
		if (self.desconto_encargos > 0) {
			let encargos = $('.parcelas-multa', context).data('value') + $('.parcelas-juros', context).data('value');
			self.desconto += encargos * (self.desconto_encargos / 100);
			self.total -= encargos * (self.desconto_encargos / 100);;
		}
		// calcula o desconto principal
		if (self.desconto_principal > 0) {
			self.desconto += $('.parcelas-subtotal', context).data('value') * (self.desconto_principal / 100);
			self.total -= $('.parcelas-subtotal', context).data('value') * (self.desconto_principal / 100);;
		}
		
		
		
		// calcula o total
		if (self.desconto_total > 0) {			
			self.total -= self.desconto_total;
			self.desconto += self.desconto_total;
		}
		// calcula os honorarios
		if (self.desconto_honorarios > 0) {
			self.desconto += self.receita * (self.desconto_honorarios / 100);
			self.receita = self.receita - (self.receita * (self.desconto_honorarios / 100));
		}
		
		return true;
	}
};

// eventos da página
$(document).ready(function() {
	// cria o objeto de negociacao
	let negociacao = new Negociacao($('.panel-calculo'));
	
	// carrega os dados na negociacao
	negociacao.load();
	
	// salva a negociacao
	$('body').on('click', '#salvar-negociacao', function() {
		// carrega os dados na negociacao
		negociacao.load();
		
		// calcula e valida o desconto antes de enviar a requisicao
		if (negociacao.validarDesconto()) {
			// envia a requisicao para salvar a negociacao
			$.post(BASE_PATH + 'negociacao/salvar', {Negociacao: negociacao}, function(response) {
				let retorno = JSON.parse(response);
				
				if (retorno.success == true) {
					toastr.success('A negociação foi salva com sucesso.');
					
					// altera o status da negociacao
					$('.negociacao-status').removeClass('label-warning').addClass('label-info').text('Em Negociação');
				} else {
					toastr.error('Houve um erro interno ao tentar salvar a negociação.');
				}
			});
		}		
	});
	
	// calcula os descontos da negociacao
	$('body').on('change', '.negociacao-descontos', function() {
		// carrega os dados de desconto
		negociacao.loadDesconto();
		// calcula e valida os descontos
		negociacao.validarDesconto();
		
		// atualiza os valores da negociacao
		$('#negociacao-desconto').data('value', negociacao.desconto).text(accounting.formatMoney(negociacao.desconto, 'R$ ', 2, '.', ','));
		$('#negociacao-receita').data('value', negociacao.receita).text(accounting.formatMoney(negociacao.receita, 'R$ ', 2, '.', ','));
		$('#negociacao-total').data('value', negociacao.total).text(accounting.formatMoney(negociacao.total, 'R$ ', 2, '.', ','));
	});
});


