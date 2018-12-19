<?php

use app\base\Util;
use app\models\Clientes;
use app\models\EstadoFederacao;
use app\models\Cidade;

use kartik\widgets\ActiveForm;
use kartik\select2\Select2;

use yii\widgets\MaskedInput;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$form = ActiveForm::begin([
    'id' => $model->formName(),   
    'enableClientValidation' => false,
    'enableAjaxValidation' => false,
]);
?>

<div class="box">
	<div class="box-body">
		<div class="row"> 
        	<div class="col-md-8">
        		<div class="form-group required">
					<?= $form->field($model, 'nome')->textInput(['maxlength' => true])->label('Nome do Cliente<i class="text-danger">*</i>'); ?>
				</div>
        	</div>
        	<div class="col-md-4">
        		<div class="form-group">
                	<?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'email@email.com']); ?>
               	</div>
        	</div>
        </div>
         <div class="row"> 
        	<div class="col-md-3">
         		<?= $form->field($model, 'tipo')->widget(Select2::className(), [
             		    'data' => [
             		        Clientes::TIPO_PESSOA_FISICA => 'FISÍCA', 
             		        Clientes::TIPO_PESSOA_JURIDICA => 'JURÍDICA'
             		    ],
         		    ])->label('Tipo de Pessoa<i class="text-danger">*</i>');
         		?>	
         	</div>
        	<div class="col-md-3">
        		<div class="form-group required" id="cadastrar-cliente-cpf">
    				 <?= $form->field($model, 'cpf')->textInput([
        				 	  'maxlength' => true,
        				 	  'class' => 'required',
        				  ])->widget(MaskedInput::className(), [
        				      'mask' => '999.999.999-99',
        				      'clientOptions' => ['placeholder' => ' ', 'removeMaskOnSubmit' => true]
        				  ])->label('CPF<i class="text-danger">*</i>');
    				 ?>
            	</div>
            	<div class="form-group" style="display: none;" id="cadastrar-cliente-cnpj">
         			<?= $form->field($model, 'cnpj')->textInput([
        				 	  'maxlength' => true,
        				 	  'class' => 'required',
        				  ])->widget(MaskedInput::className(), [
        				      'mask' => '99.999.999/9999-99',
        				      'clientOptions' => ['placeholder' => ' ', 'removeMaskOnSubmit' => true],
        				  ])->label('CNPJ<i class="text-danger">*</i>');
    				 ?>
         		</div>
        	</div>
        	<div class="col-md-3">
    			<div class="form-group">
                    <?= $form->field($model, 'fone')->widget(MaskedInput::className(), [
                        	'mask' => ['(99) 9999-9999', '(99) 99999-9999'],
                            'clientOptions' => ['placeholder' => ' ', 'removeMaskOnSubmit' => true],
                        ])->label('Telefone Fixo');
                    ?>
       			</div>
    		</div>
    		<div class="col-md-3">
    			<div class="form-group">
                    <?= $form->field($model, 'fone_celular')->widget(MaskedInput::className(), [
                        	'mask' => ['(99) 9999-9999', '(99) 99999-9999'],
                            'clientOptions' => ['placeholder' => ' ', 'removeMaskOnSubmit' => true],
                        ])->label('Celular');
                    ?> 			
               </div>
    		</div>
        </div>        
        <?php if ($model->tipo_cadastro == Clientes::TIPO_NORMAL): ?>
			<hr>
			<div class="row">
				<div class="col-md-2">
        			<div class="form-group">
        				<?=$form->field($model, 'cep')->widget(MaskedInput::className (), [
                        	    'mask' => '99999-999',
                                'clientOptions' => ['placeholder' => ' ', 'removeMaskOnSubmit' => true]
                        	])->label('CEP<i class="text-danger">*</i>'); 
        				?>
        			</div>
        		</div>
        		<div class="col-md-6">
        			<div class="form-group">
                    	<?= $form->field($model, 'endereco')->textInput(['maxlength' => true])->label('Logradouro<i class="text-danger">*</i>'); ?>
                    </div>        		
        		</div>
        		<div class="col-md-2">
        			<div class="form-group">
                        <?= $form->field($model, 'numero')->textInput(['maxlength' => true])->label('Núm.<i class="text-danger">*</i>'); ?>
                    </div>
        		</div>
        		<div class="col-md-2">
        			<div class="form-group">
                        <?= $form->field($model, 'complemento')->textInput(['maxlength' => true])->label('Complemento'); ?>
                    </div>
        		</div>
        	</div>   
        	<div class="row">
        		<div class="col-md-4">
        			<div class="form-group">
                        <?= $form->field($model, 'bairro')->textInput(['maxlength' => true])->label('Bairro<i class="text-danger">*</i>'); ?>
                    </div>
        		</div>
        		<div class="col-md-4">
        			<div class="form-group">
                        <?= $form->field($model, 'id_estado')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(EstadoFederacao::find()->orderBy('nome_estado')->all(), 'id', 'unidade_federacao'),
                                'pluginOptions' => [ 'allowClear' => true],
                                'hideSearch' => false,
                                'options' => [
                                    'placeholder' => 'UF',
                                    'onchange' => '
                                        if($(this).val() > 0) {
                                            $.post(BASE_PATH + "cidade/lists/' . '"+$(this).val(), function(data) {
                                                $("#select2-clientes-id_cidade-container" ).html(\'\');
                                                $("#clientes-id_cidade").attr("disabled", "disabled").html(data);
                                            }).always(function() {
				                                $("#clientes-id_cidade").val($("#cidade-hidden-id").val()).removeAttr("disabled").trigger("change");
                                            });
                                       }else {
                                            $("#clientes-id_cidade" ).html("");
                                            $("#clientes-id_cidade").val("").trigger("change.select2").removeAttr("disabled").trigger("change");
                                       }
                                    '
                                ],
                            ])->label('Estado<i class="text-danger">*</i>');
                        ?>
          			</div>
        		</div>
        		<div class="col-md-4">
        			<div class="form-group">
                        <?= $form->field($model, 'id_cidade')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(Cidade::findAll(['estado_federacao_id' => $model->id_estado]), 'id', 'descricao'),
                            	'pluginOptions' => ['allowClear' => true],
                            	'hideSearch' => false,
                            	'options' => ['placeholder' => 'Selecione a cidade' ],
                            ])->label('Cidade<i class="text-danger">*</i>');
                        ?>             
           			</div>
           			<input id="cidade-hidden-id" type="hidden"/>
           			<!-- ./hidden id da cidade -->
        		</div>        	
        	</div>
        	<!-- ./row -->
        <?php endif; ?>    
    </div>
    <!-- ./box-body -->
    <br>
    <div class="box-footer">
    	<div class="row">
    		<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
            	<?= Html::submitButton(Util::BTN_CREATE, ['class' => Util::BTN_CREATE_CLASS. ' btn-block', 'id' => 'btnSave']); ?>
        	</div>
        	<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12 pull-right">
            	<?= Html::button(Util::BTN_RETURN, ['class' => Util::BTN_COLOR_DEFAULT.' btn-block', 'id' => 'btnClose']); ?>
        	</div>	
    	</div>
    	<!-- ./row -->
    </div>
    <!-- ./box-footer -->
</div>    
<?php
ActiveForm::end();
// form
$js = <<<JS
    $(document).ready(function() {
        toastr.options = {
            'preventDuplicates' : true,
        };
            
        $('#btnClose').click(function() {
            $('#modalCadastrarCliente').modal('hide');
        });
            
        //Serialize e envia o formulário
        $('form#Clientes').on('beforeSubmit', function(e) {
            const \$form = $(this);
            
            $.post(\$form.attr("action"), \$form.serialize()).done(function(result) {
                const retorno = JSON.parse(result);
              
                if (retorno.success == 1) { 
                    toastr.success(retorno.message);        
                    $('#modalCadastrarCliente').modal('hide');
    
                    if ($('.clientePDV').size != 0) {
                        $('.clientePDV').attr('id', retorno.id);
                        $('.clientePDV').text(retorno.nome);
    
                        if (retorno.tipo == 2) {
        					$('#cprf').val(1).trigger('change');
        					$('#pedido-cpf').val(retorno.identificacao);
                            
                            if (typeof window['setMaskClienteDoc'] === "function") {
                                window['setMaskClienteDoc'](1, false);
                                window['setClientePedido'](retorno.identificacao);
                            }	
        				} else {
        					$('#cprf').val(0).trigger('change');
        					$('#pedido-cpf').val(retorno.identificacao);
                           
                            if(typeof window['setMaskClienteDoc'] === "function") {
                                window['setMaskClienteDoc'](1, false);
                                window['setClientePedido'](retorno.identificacao);
                            }
        				}
                    }
                } else {
                    toastr.error(retorno.message);
                }
            }).fail(function() {        
                $('#modalCadastrarCliente').modal('hide');        
                $.confirm({
    				content: 'Não foi possível cadastrar o cliente',
    				cancelButton: false,
    				backgroundDismiss: false,
    				closeIcon: false,
    			});            
            });
            
            return false;
        });
            
        //Consulta API de CEP
        //Ajax de busca de CEP
        $(document).on('click', '.btnBuscaCep', function() {
            buscarCep();
        }).on('change', '#clientes-cep', function() {
            buscarCep();
        }).on('keypress', '#clientes-cep', function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                buscarCep();
                return false;
            }
        });
    
        function buscarCep() {
            toastr.clear();
            const cep_val = $('#clientes-cep').val().replace(/\_|\' '|\-|\ /gi, '');
            
            if (cep_val.length == 0) {
                return false;
            }
            
            const dados = {
                cep: $('#clientes-cep'),
                endereco: $('#clientes-endereco'),
                bairro: $('#clientes-bairro'),
                estado: $('#clientes-id_estado'),
                estado_placeholder: $('#select2-clientes-id_estado-container'),
                cidade: $('#clientes-id_cidade'),
                cidade_placeholder: $('#select2-clientes-id_cidade-container'),
                button: $('.btnBuscaCep'),
            };
            
            if (cep_val.length != 8 && cep_val.length > 0) {
                toastr.warning('O CEP não foi preenchido completamente.');
                dados.cep.val('');
                defaultForm(dados);
                return false;
            }
            
            // seta as mensagens de loadings
            dados.cep.attr('disabled', 'disabled');
            dados.endereco.attr({disabled: 'disabled', placeholder: 'Carregando ...'});
            dados.bairro.attr({disabled: 'disabled', placeholder: 'Carregando ...'});
            dados.estado.attr('disabled', 'disabled');
            dados.cidade.attr('disabled', 'disabled');
            dados.estado_placeholder.find('span').text('Carregando ...');
            dados.cidade_placeholder.find('span').text('Carregando ...');
            dados.button.button('loading');		
            
            // envia a requisição    
            $.get(BASE_PATH + 'api/busca-cep?cep='+ cep_val, function(response, status) {
                // seta os dados de endereço
                if (response != false && response != null && response != undefined) {
                    dados.endereco.val(response.logradouro);
                    dados.bairro.val(response.bairro);
                    dados.estado.val(response.uf_id).trigger('change');
                    $('#cidade-hidden-id').val(response.cidade_id);
                }
                
            }).always(function() {
                defaultForm(dados);
            });
        }
    
        // reseta o form para o padrão, removendo o disabled e o placeholder
        function defaultForm(dados) {
            dados.button.button('reset');
            dados.cep.removeAttr('disabled');
            dados.endereco.removeAttr('disabled').attr('placeholder', '');
            dados.bairro.removeAttr('disabled').attr('placeholder', '');
            dados.estado.removeAttr('disabled');
        }
            
        $('#clientes-tipo').on('change', function() {
            if ($(this).val() == 1) {
                $('#cadastrar-cliente-cnpj').hide();
                $('#cadastrar-cliente-cpf').fadeIn(500);
            } else {
                $('#cadastrar-cliente-cnpj').fadeIn(500);
                $('#cadastrar-cliente-cpf').hide();
            }
        });

        // valida o cpf/cnpj digitado
        $('body').on('change', '#clientes-cpf, #clientes-cnpj', function() {
    		const element = $(this);
    		const documento = element.val();
    		const tipoDoc = documento.length == 14 ? 'cpf' : 'cnpj';
    		
    		if (!documento || documento.lenght == 0) {
    			return false;
    		}
    		if (validarDocumento(documento, tipoDoc)) {
    		   return true;
    		} else {
    			toastr.warning(tipoDoc.toUpperCase() +' "'+ documento +'" não é válido.');
    			element.val('');
    			element.focus();
    		    return false;
    		}
        });
    });
JS;
// JS
$this->registerJS($js);    
?>







   
