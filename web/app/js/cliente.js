$(document).ready(function() {
	// valida o tipo do cliente
	$('body').on('change', '#cliente-tipo', function() {
		if (this.value == 'F') {
			if (!$('.cliente-fisico').is(':visible')) {
				// esconde os dados de cliente juridico
				// e mostra os dados de cliente fisico
				$('.cliente-juridico').hide(function() {
					$('#cliente-nome').siblings('label').text('Nome');
					$('.cliente-fisico').show();
				});
			}
		} else {
			if (!$('.cliente-juridico').is(':visible')) {
				// esconde os dados de cliente fisico 
				// e mostra os dados de cliente juridico
				$('.cliente-fisico').hide(function() {
					// altera a label do nome
					$('#cliente-nome').siblings('label').text('Razão Social');
					$('.cliente-juridico').show();
				});
			}
		}
	});

	// insere as mascaras dos plugins
	Inputmask({'mask': '(99) 9999-9999[9]', 'greedy': false}).mask($('.input-numero'));
	Inputmask({'mask': '99999-999', 'greedy': false}).mask($('.input-cep'));
	Inputmask({'alias': 'email'}).mask($('.input-email'));
	
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
		
		// insere a mascara de telefone
		Inputmask({'mask': '(99) 9999-9999[9]', 'greedy': false}).mask(linha.find('td:first input'));		
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
		
		// insere a mascara de CEP
		Inputmask({'alias': 'email'}).mask(linha.find('td:first input'));
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
		linha.append('<td class="linha-cep"><input class="form-control" name="Enderecos['+enderecoId+'][cep]" maxlength="" type="text"/></td>');
		linha.append('<td class="select-cidade"><select id="Enderecos-'+enderecoId+'-cidade" class="form-control js-example-basic-single" name="Enderecos['+enderecoId+'][cidade_id]"></select></td>');
		linha.append('<td class="select-estado"><select id="Enderecos-'+enderecoId+'-estado" class="form-control" name="Enderecos['+enderecoId+'][estado_id]"></select></td>');
		linha.append('<td class="text-center"><button class="btn btn-sm btn-danger btn-flat btn-deletar"><i class="fa fa-times"></i></button></td>');
		
		// insere a mascara de CEP
		Inputmask({'mask': '99999-999', 'greedy': false}).mask(linha.find('td.linha-cep input'));
		
		// ativa o plugin
		linha.find('select:first').select2({
			'placeholder': 'Selecione a cidade',
		});
		// ativa o plugin e insere a lista de estados (const no main.js)
		linha.find('select:last').select2({
			'placeholder': 'Selecione o estado'
		}).html(listaEstados).val('1').trigger('change.select2');
	});

	// deleta um registro
	$('body').on('click', '.btn-deletar', function() {
		$(this).closest('tr').remove();
	});
	
	/**
	 * Seta a lista de cidades do estado
	 */
	$('body').on('change.select2', '#table-enderecos td.select-estado select', function (e) {
		// pega o select de cidades
        let cidadesSelect =  $(this).closest('tr').find('td.select-cidade select');

        // envia a requisicao para buscar as cidades do estado
        $.get(BASE_PATH + 'site/cidades?ufId='+$(this).find('option:selected').val(), function(response) {
            // seta as cidades encontradas para o estado
            cidadesSelect.html(response);
        });
	});
});



