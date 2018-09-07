<?php
use app\base\Util;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Cliente;
use yii\web\View;
use app\models\Telefone;
use kartik\date\DatePicker;
use yii\widgets\MaskedInput;
?>
<?php $form = ActiveForm::begin(); ?>
	<div class="panel panel-primary panel-box">
		<div class="panel-body">
			<div class="nav-tabs-custom">
        		<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-principal" data-toggle="tab">Principal</a></li>
					<li><a href="#tab-contato" data-toggle="tab">Contato</a></li>
					<li><a href="#tab-endereco" data-toggle="tab">Endereço</a></li>
				</ul>
			</div>
			<!-- ./tabs -->
			<div class="tab-content">
				<div class="tab-pane active" id="tab-principal">
					<div class="row">
	        			<div class="col-md-3 col-sm-3 col-xs-12 col-lg-3">
	                        <?= $form->field($model, 'tipo')->dropDownList([
		                        	Cliente::TIPO_FISICO => 'Fisico',
                        			Cliente::TIPO_JURIDICO => 'Jurídico',
		                        ]); 
	                        ?>
	        			</div>
	        			<div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
	        				<div class="cliente-fisico" style="display: <?= $layout == Cliente::TIPO_FISICO ? 'block' : 'none'; ?>;">
		                        <?= $form->field($model, 'nome')->textInput(['name' => 'cliente-nome', 'id' => 'cliente-nome', 'maxlength' => true]); ?>
	        				</div>
							<div class="cliente-juridico" style="display: <?= $layout == Cliente::TIPO_FISICO ? 'none' : 'block'; ?>;">
		                        <?= $form->field($model, 'nome')->textInput(['name' => 'cliente-razao-social', 'id' => 'cliente-razao-social', 'maxlength' => true])->label('Razão Social'); ?>
	        				</div>
	        			</div>
	        		</div>
	        		<!-- ./row -->
	    			<div class="row">
        				<div class="cliente-fisico" style="display: <?= $layout == Cliente::TIPO_FISICO ? 'block' : 'none'; ?>;">
		        			<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
	                        	<?= $form->field($model, 'documento')->widget(MaskedInput::className(), [
                        				'mask' => '999.999.999-99',
                        				'options' => [
		                        			'name' => 'cliente-cpf',
                        					'id' => 'cliente-cpf',
                        					'class' => 'form-control',
                        				],
		                        	])->label('CPF');
		                        ?>
							</div>	      
	        			</div>
						<!-- ./cliente fisico -->  			
						<div class="cliente-juridico" style="display: <?= $layout == Cliente::TIPO_FISICO ? 'none' : 'block'; ?>;">
		        			<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
								<?= $form->field($model, 'documento')->widget(MaskedInput::className(), [
										'mask' => '99.999.999/9999-99',
										'options' => [
											'name' => 'cliente-cnpj',
											'id' => 'cliente-cnpj',		
											'class' => 'form-control',
										],
		                        	])->label('CNPJ');
		                        ?>
							</div>	
						</div>
						<!-- ./cliente juridico -->
        				<div class="cliente-fisico" style="display: <?= $layout == Cliente::TIPO_FISICO ? 'block' : 'none'; ?>;">
		        			<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
		                        <?= $form->field($model, 'data_nascimento')->widget(DatePicker::className(), [
                        				'pluginOptions' => [
                        					'autoclose' => true,
                        					'format' => 'yyyy-mm-dd'
                        				]
		                        	]);
		                        ?>
		                    </div>
		        			<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
		                        <?= $form->field($model, 'nome_social')->textInput(['name' => 'cliente-apelido', 'id' => 'cliente-apelido', 'maxlength' => true])->label('Apelido'); ?>
		        			</div>
		        			<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
		                        <?= $form->field($model, 'sexo')->dropDownList([
                        				Cliente::SEXO_MASC => 'Masculino', 
                        				Cliente::SEXO_FEM => 'Feminino', 
			                        ]); 
		                        ?>
		        			</div>
        				</div>
        				<!-- ./cliente fisico -->
        				<div class="cliente-juridico" style="display: <?= $layout == Cliente::TIPO_FISICO ? 'none' : 'block'; ?>;">
		        			<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
		                        <?= $form->field($model, 'nome_social')->textInput(['name' => 'cliente-fantasia', 'id' => 'cliente-fantasia', 'maxlength' => true])->label('Nome Fantasia'); ?>
		        			</div>
		        			<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
		                        <?= $form->field($model, 'inscricao_estadual')->textInput(['maxlength' => true]); ?>
		        			</div>
        				</div>
        				<!-- ./cliente juridico -->
	        		</div>  
	        		<!-- ./row -->
	        		<div class="cliente-fisico" style="display: <?= $layout == Cliente::TIPO_FISICO ? 'block' : 'none'; ?>;">
		        		<div class="row">
		                    <div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
		                        <?= $form->field($model, 'rg')->widget(MaskedInput::className(), [
                        				'mask' => '99.999.999-9',
		                        	]);
		                        ?>
		        			</div>
		        			<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
		                        <?= $form->field($model, 'nome_pai')->textInput(['maxlength' => true]); ?>
		        			</div>
		        			<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
		                        <?= $form->field($model, 'nome_mae')->textInput(['maxlength' => true]); ?>
		        			</div>
		        			<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
		                        <?= $form->field($model, 'nome_conjuge')->textInput(['maxlength' => true]); ?>
		        			</div>
		        		</div>
		        		<!-- ./row -->
		        		<div class="row">
		                    <div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
		                        <?= $form->field($model, 'estado_civil')->dropDownList(Cliente::getListaEstadoCivil()); ?>
		        			</div>
		        			<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
		                        <?= $form->field($model, 'empresa')->textInput(['maxlength' => true]); ?>
		        			</div>
		        			<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
		                        <?= $form->field($model, 'profissao')->textInput(['maxlength' => true]); ?>
		        			</div>
		        			<div class="col-md-2 col-sm-2 col-lg-2 col-xs-12">
		                        <?= $form->field($model, 'salario')->textInput(['maxlength' => true]); ?>
		        			</div>
		        		</div>
		        		<!-- ./row -->
					</div>	  
					<!-- ./cliente fisico -->      		
				</div>
				<!-- ./tab principal -->
			  	<div class="tab-pane" id="tab-contato">
			  		<br>
			  		<?= Html::button('<i class="fa fa-plus"></i>&nbsp; Telefone', ['id' => 'add-telefone', 'class' => Util::BTN_COLOR_EMERALD]); ?>
			  		<table id="table-telefones" class="table table-bordered table-hover">
			  			<thead>
			  				<tr>
			  					<th class="text-center" colspan="8">Telefones</th>
			  				</tr>
			  				<tr>
			  					<th>Telefone</th>
			  					<th width="10%">Ramal</th>
			  					<th>Tipo</th>
			  					<th>Para Contato?</th>
			  					<th>Whatsapp</th>
			  					<th>Ativo</th>
			  					<th>Observação</th>
			  					<th></th>
			  				</tr>
			  			</thead>
			  			<tbody>
			  				<?php if (is_array($model->telefones) && !empty($model->telefones)): ?>
			  					<?php foreach ($model->telefones as $telefone): ?>
				  					<?= Html::hiddenInput("Telefones[$telefone->id][id]", $telefone->id); ?>
				  					<!-- ./hidden id -->
				  					<tr data-id="<?= $telefone->id; ?>">
					  					<td>
					  						<?= $form->field($telefone, 'numero')->textInput([
		  											'id' => null,
		  											'name' => "Telefones[$telefone->id][numero]",
			  										'maxlength' => true,
					  							])->label(false); 
					  						?>
				  						</td>
					  					<td>
					  						<?= $form->field($telefone, 'ramal')->textInput([
			  										'id' => null,
			  										'name' => "Telefones[$telefone->id][ramal]",
			  										'maxlength' => true,
  												])->label(false); 
					  						?>
				  						</td>
					  					<td>
					  						<?= $form->field($telefone, 'tipo')->dropDownList(Telefone::getListaTipos(), [
					  								'id' => null,
			  										'name' => "Telefones[$telefone->id][tipo]",
				  								])->label(false); 
			  								?>
				  						</td>
					  					<td>
					  						<?= $form->field($telefone, 'contato')->dropDownList([
			  										Telefone::SIM => 'Sim',
			  										Telefone::NAO => 'Não',
					  							], [
			  										'id' => null,
		  											'name' => "Telefones[$telefone->id][contato]",
		  										])->label(false); 
					                        ?>
				                        </td>
					  					<td>
					  						<?= $form->field($telefone, 'whatsapp')->dropDownList([
			  										Telefone::SIM => 'Sim',
			  										Telefone::NAO => 'Não',
					  							], [
			  										'id' => null,
		  											'name' => "Telefones[$telefone->id][whatsapp]",
		  										])->label(false); 
					                        ?>
				                        </td>
					  					<td>
					  						<?= $form->field($telefone, 'ativo')->dropDownList([
			                        				Telefone::SIM => 'Sim', 
			                        				Telefone::NAO => 'Não', 
					  							], [
		  											'id' => null,
		  											'name' => "Telefones[$telefone->id][ativo]",
					  							])->label(false); 
					                        ?>
				                        </td>
					  					<td>
					  						<?= $form->field($telefone, 'observacao')->textInput([
				  									'id' => null,
			  										'name' => "Telefones[$telefone->id][observacao]",
				  									'maxlength' => true,
						  						])->label(false); 
						  					?>
					  					</td>
					  					<td class="text-center">
					  						<?= Html::button('<i class="fa fa-times"></i>', [
			  										'class' => Util::BTN_COLOR_DANGER.' btn-deletar', 
					  							]); 
						  					?>
					  					</td>
					  				</tr>
				  				<?php endforeach; ?>
			  				<?php else: ?>
			  					<tr data-id="1">
				  					<td>
				  						<?= Html::textInput('Telefones[1][numero]', null, [
		  										'maxlength' => true,
				  								'class' => 'form-control',
				  							]); 
				  						?>
			  						</td>
				  					<td>
				  						<?= Html::textInput('Telefones[1][ramal]', null, [
			  									'maxlength' => true,
				  								'class' => 'form-control',
  											]); 
					  					?>
			  						</td>
				  					<td>
				  						<?= Html::dropDownList('Telefones[1][tipo]', null, Telefone::getListaTipos(), ['class' => 'form-control']); ?>
			  						</td>
				  					<td>
				  						<?= Html::dropDownList('Telefones[1][contato]', null, [
		  										Telefone::SIM => 'Sim',
		  										Telefone::NAO => 'Não',
				  							], ['class' => 'form-control']); 
				                        ?>
			                        </td>
				  					<td>
				  						<?= Html::dropDownList('Telefones[1][whatsapp]', Telefone::NAO, [
		  										Telefone::SIM => 'Sim',
		  										Telefone::NAO => 'Não',
				  							], ['class' => 'form-control']); 
				                        ?>
			                        </td>
				  					<td>
				  						<?= Html::dropDownList('Telefones[1][ativo]', null, [
		                        				Telefone::SIM => 'Sim', 
		                        				Telefone::NAO => 'Não', 
				  							], ['class' => 'form-control']); 
				                        ?>
			                        </td>
				  					<td>
				  						<?= Html::textInput('Telefones[1][observacao]', null, [
		  										'maxlength' => true, 
		  										'class' => 'form-control',
				  							]); 
					  					?>
				  					</td>
				  					<td class="text-center">
				  						<?= Html::button('<i class="fa fa-times"></i>', [
		  										'class' => Util::BTN_COLOR_DANGER.' btn-deletar', 
				  							]); 
					  					?>
				  					</td>
				  				</tr>
			  				<?php endif; ?>
			  			</tbody>
			  		</table>
			  		<!-- ./table telefone -->
			  		<?= Html::button('<i class="fa fa-plus"></i>&nbsp; Email', ['id' => 'add-email', 'class' => Util::BTN_COLOR_EMERALD]); ?>
			  		<table id="table-emails" class="table table-bordered table-hover">
			  			<thead>
			  				<tr>
			  					<th class="text-center" colspan="3">Emails</th>
			  				</tr>
			  				<tr>
			  					<th>Email</th>
			  					<th>Observação</th>
			  					<th></th>
			  				</tr>
			  			</thead>
			  			<tbody>
			  				<?php if (is_array($model->emails) && !empty($model->emails)): ?>
			  					<?php foreach ($model->emails as $email): ?>
				  					<?= Html::hiddenInput("Emails[$email->id][id]", $email->id); ?>
				  					<!-- ./hidden id -->
				  					<tr data-id="<?= $email->id; ?>">
					  					<td>
					  						<?= $form->field($email, 'email')->textInput([
		  											'id' => null,
					  								'name' => "Emails[$email->id][email]",
			  										'maxlength' => true,
					  							])->label(false); 
					  						?>
				  						</td>
					  					<td>
					  						<?= $form->field($email, 'observacao')->textInput([
				  									'id' => null,
					  								'name' => "Emails[$email->id][observacao]",
				  									'maxlength' => true,
						  						])->label(false); 
						  					?>
					  					</td>
					  					<td class="text-center">
					  						<?= Html::button('<i class="fa fa-times"></i>', [
			  										'class' => Util::BTN_COLOR_DANGER.' btn-deletar', 
					  							]); 
						  					?>
					  					</td>
					  				</tr>
				  				<?php endforeach; ?>
			  				<?php else: ?>
			  					<tr data-id="1">
				  					<td>
				  						<?= Html::textInput('Emails[1][email]', null, [
		  										'maxlength' => true,
				  								'class' => 'form-control',
				  							]); 
				  						?>
			  						</td>
				  					<td>
				  						<?= Html::textInput('Emails[1][observacao]', null, [
		  										'maxlength' => true, 
		  										'class' => 'form-control',
				  							]); 
					  					?>
				  					</td>
				  					<td class="text-center">
				  						<?= Html::button('<i class="fa fa-times"></i>', [
		  										'class' => Util::BTN_COLOR_DANGER.' btn-deletar', 
				  							]); 
					  					?>
				  					</td>
				  				</tr>
			  				<?php endif; ?>
			  			</tbody>
			  		</table>
			  		<!-- ./table email -->
			  	</div>
			  	<!-- ./tab contato -->
			  	<div class="tab-pane" id="tab-endereco">
			  		<br>
			  		<?= Html::button('<i class="fa fa-plus"></i>&nbsp; Endereço', ['id' => 'add-endereco', 'class' => Util::BTN_COLOR_EMERALD]); ?>
			  		<table id="table-enderecos" class="table table-bordered table-hover">
			  			<thead>
			  				<tr>
			  					<th class="text-center" colspan="8">Endereços</th>
			  				</tr>
			  				<tr>
			  					<th>Logradouro</th>
			  					<th width="10%">Número</th>
			  					<th width="10%">Complemento</th>
			  					<th width="10%">Bairro</th>
			  					<th width="10%">CEP</th>
			  					<th width="10%">Cidade</th>
			  					<th width="10%">Estado</th>
			  					<th width="5%"></th>
			  				</tr>
			  			</thead>
			  			<tbody>
			  				<?php if (is_array($model->enderecos) && !empty($model->enderecos)): ?>
			  					<?php foreach ($model->enderecos as $endereco): ?>
				  					<?= Html::hiddenInput("Enderecos[$endereco->id][id]", $endereco->id); ?>
				  					<!-- ./hidden id -->
				  					<tr data-id="<?= $endereco->id; ?>">
					  					<td>
					  						<?= $form->field($endereco, 'logradouro')->textInput([
		  											'id' => null,
		  											'name' => "Enderecos[$endereco->id][logradouro]",
			  										'maxlength' => true,
					  							])->label(false); 
					  						?>
				  						</td>
					  					<td>
					  						<?= $form->field($endereco, 'numero')->textInput([
			  										'id' => null,
			  										'name' => "Enderecos[$endereco->id][numero]",
			  										'maxlength' => true,
  												])->label(false); 
					  						?>
				  						</td>
					  					<td>
					  						<?= $form->field($endereco, 'complemento')->textInput([
			  										'id' => null,
			  										'name' => "Enderecos[$endereco->id][complemento]",
			  										'maxlength' => true,
  												])->label(false); 
					  						?>
				  						</td>
					  					<td>
					  						<?= $form->field($endereco, 'bairro')->textInput([
			  										'id' => null,
			  										'name' => "Enderecos[$endereco->id][bairro]",
			  										'maxlength' => true,
  												])->label(false); 
					  						?>
				                        </td>
					  					<td>
					  						<?= $form->field($endereco, 'cep')->textInput([
			  										'id' => null,
			  										'name' => "Enderecos[$endereco->id][cep]",
			  										'maxlength' => true,
  												])->label(false); 
					  						?>
				                        </td>
					  					<td>
					  						<?= $form->field($endereco, 'cidade_id')->textInput([
			  										'id' => null,
			  										'name' => "Enderecos[$endereco->id][cidade_id]",
			  										'maxlength' => true,
  												])->label(false); 
					  						?>
				                        </td>
					  					<td>
					  						<?= $form->field($endereco, 'estado_id')->textInput([
				  									'id' => null,
			  										'name' => "Enderecos[$endereco->id][estado_id]",
				  									'maxlength' => true,
						  						])->label(false); 
						  					?>
					  					</td>
					  					<td class="text-center">
					  						<?= Html::button('<i class="fa fa-times"></i>', [
			  										'class' => Util::BTN_COLOR_DANGER.' btn-deletar', 
					  							]); 
						  					?>
					  					</td>
					  				</tr>
				  				<?php endforeach; ?>
			  				<?php else: ?>
			  					<tr data-id="1">
				  					<td>
				  						<?= Html::textInput('Enderecos[1][logradouro]', null, [
		  										'maxlength' => true,
				  								'class' => 'form-control',
				  							]); 
				  						?>
			  						</td>
				  					<td>
				  						<?= Html::textInput('Enderecos[1][numero]', null, [
			  									'maxlength' => true,
				  								'class' => 'form-control',
  											]); 
					  					?>
			  						</td>
				  					<td>
				  						<?= Html::textInput('Enderecos[1][complemento]', null, [
			  									'maxlength' => true,
				  								'class' => 'form-control',
  											]); 
					  					?>
			  						</td>
				  					<td>
				  						<?= Html::textInput('Enderecos[1][bairro]', null, [
			  									'maxlength' => true,
				  								'class' => 'form-control',
  											]); 
					  					?>
			                        </td>
				  					<td>
				  						<?= Html::textInput('Enderecos[1][cep]', null, [
			  									'maxlength' => true,
				  								'class' => 'form-control',
  											]); 
					  					?>
			                        </td>
				  					<td>
				  						<?= Html::textInput('Enderecos[1][cidade_id]', null, [
			  									'maxlength' => true,
				  								'class' => 'form-control',
  											]); 
					  					?>
			                        </td>
				  					<td>
				  						<?= Html::textInput('Enderecos[1][estado_id]', null, [
		  										'maxlength' => true, 
		  										'class' => 'form-control',
				  							]); 
					  					?>
				  					</td>
				  					<td class="text-center">
				  						<?= Html::button('<i class="fa fa-times"></i>', [
		  										'class' => Util::BTN_COLOR_DANGER.' btn-deletar', 
				  							]); 
					  					?>
				  					</td>
				  				</tr>
			  				<?php endif; ?>
			  			</tbody>
			  		</table>
			  		<!-- ./table telefone -->
			  	</div>
			  	<!-- ./tab endereco -->
			  	<div class="tab-pane" id="tab-contrato">
			  		
			  	</div>
			  	<!-- ./tab contrato -->
			</div>
        </div>
        <!-- ./painel-body -->
        <div class="panel-footer">
    		<div class="row">
    			<div class="col-md-3">
                    <div class="form-group">
                        <?= Html::submitButton('<i class="fa fa-save"></i>&nbsp; '. ($model->isNewRecord ? 'Cadastrar' : 'Alterar'), [
                                'class' => $model->isNewRecord 
                                ? Util::BTN_COLOR_SUCCESS.' btn-block' 
                                : Util::BTN_COLOR_PRIMARY.' btn-block',
                            ]);
                        ?>
                    </div>
    			</div>
    			<div class="col-md-3 pull-right">
                    <div class="form-group">
                        <?= Html::a('<i class="fa fa-reply"></i>&nbsp; Voltar', ['/cliente'], [
                                'class' => Util::BTN_COLOR_DEFAULT.' btn-block',
                            ]);
                        ?>
                    </div>
    			</div>
    		</div>
        </div>
        <!-- ./panel-footer -->
	</div>
	<!-- ./box -->
<?php ActiveForm::end(); ?>
<!-- ./form -->
<?php 
$script = <<<JS
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
JS;
// JS
$this->registerJs($script, View::POS_READY);
?>


