$(document).ready(function() {
	// valida o tipo do cliente
	$('body').on('change', '#cliente-tipo', function() {
		if (this.value == 'F') {
			if (!$('.cliente-fisico').is(':visible')) {
				$('.cliente-juridico').hide(function() {
					$('.cliente-fisico').show();
				});
			}
		} else {
			if (!$('.cliente-juridico').is(':visible')) {
				$('.cliente-fisico').hide(function() {
					$('.cliente-juridico').show();
				});
			}
		}
	});

	// adiciona um novo telefone na lista
	$('body').on('click', '#add-telefone', function() {
		// busca a tabela e o ultimo id
		let tbody = $('#table-telefones').find('tbody');
		let telefoneId = parseInt(tbody.find('tr:last').data('id'));
		
		// valida se é um numero 
		if (isNaN(telefoneId)) {
			telefoneId = 0;
		}

		// cria e atribui a linha
		tbody.append('<tr></tr>');
		let linha = tbody.find('tr:last');
		
		// adiciona os campos na linha
		linha.attr('data-id', ++telefoneId);
		linha.append('<td><input class="form-control" name="Telefones['+telefoneId+'][numero]" maxlength="" type="text"/></td>');
		linha.append('<td><input class="form-control" name="Telefones['+telefoneId+'][ramal]" maxlength="" type="text"/></td>');
		linha.append('<td><select class="form-control" name="Telefones['+telefoneId+'][tipo]"><option value="1">Residencial</option><option value="2">Móvel</option><option value="3">Comercial</option><option value="4">Fax</option><option value="5">Referência</option></select></td>');
		linha.append('<td><select class="form-control" name="Telefones['+telefoneId+'][contato]"><option value="S">Sim</option><option value="N">Não</option></select></td>');
		linha.append('<td><select class="form-control" name="Telefones['+telefoneId+'][whatsapp]"><option value="S">Sim</option><option value="N" selected="">Não</option></select></td>');
		linha.append('<td><select class="form-control" name="Telefones['+telefoneId+'][ativo]"><option value="S">Sim</option><option value="N">Não</option></select></td>');
		linha.append('<td><input class="form-control" name="Telefones['+telefoneId+'][observacao]" maxlength="" type="text"/></td>');
		linha.append('<td class="text-center"><button class="btn btn-sm btn-danger btn-flat btn-deletar"><i class="fa fa-times"></i></button></td>');
	});

	// adiciona um novo email na lista
	$('body').on('click', '#add-email', function() {
		// busca a tabela e o ultimo id
		let tbody = $('#table-emails').find('tbody');
		let emailId = parseInt(tbody.find('tr:last').data('id'));
		
		// valida se é um numero 
		if (isNaN(emailId)) {
			emailId = 0;
		}

		// cria e atribui a linha
		tbody.append('<tr></tr>');
		let linha = tbody.find('tr:last');
		
		// adiciona os campos na linha
		linha.attr('data-id', ++emailId);
		linha.append('<td><input class="form-control" name="Emails['+emailId+'][email]" maxlength="" type="text"/></td>');
		linha.append('<td><input class="form-control" name="Emails['+emailId+'][observacao]" maxlength="" type="text"/></td>');
		linha.append('<td class="text-center"><button class="btn btn-sm btn-danger btn-flat btn-deletar"><i class="fa fa-times"></i></button></td>');
	});

	// adiciona um novo endereco na lista
	$('body').on('click', '#add-endereco', function() {
		// busca a tabela e o ultimo id
		let tbody = $('#table-enderecos').find('tbody');
		let enderecoId = parseInt(tbody.find('tr:last').data('id'));
		
		// valida se é um numero 
		if (isNaN(enderecoId)) {
			enderecoId = 0;
		}

		// cria e atribui a linha
		tbody.append('<tr></tr>');
		let linha = tbody.find('tr:last');
		
		// adiciona os campos na linha
		linha.attr('data-id', ++enderecoId);
		linha.append('<td><input class="form-control" name="Enderecos['+enderecoId+'][logradouro]" maxlength="" type="text"/></td>');
		linha.append('<td><input class="form-control" name="Enderecos['+enderecoId+'][numero]" maxlength="" type="text"/></td>');
		linha.append('<td><input class="form-control" name="Enderecos['+enderecoId+'][complemento]" maxlength="" type="text"/></td>');
		linha.append('<td><input class="form-control" name="Enderecos['+enderecoId+'][bairro]" maxlength="" type="text"/></td>');
		linha.append('<td><input class="form-control" name="Enderecos['+enderecoId+'][cep]" maxlength="" type="text"/></td>');
		linha.append('<td><input class="form-control" name="Enderecos['+enderecoId+'][cidade_id]" maxlength="" type="text"/></td>');
		linha.append('<td><input class="form-control" name="Enderecos['+enderecoId+'][estado_id]" maxlength="" type="text"/></td>');
		linha.append('<td class="text-center"><button class="btn btn-sm btn-danger btn-flat btn-deletar"><i class="fa fa-times"></i></button></td>');
	});

	// deleta um registro
	$('body').on('click', '.btn-deletar', function() {
		$(this).closest('tr').remove();
	});
});



