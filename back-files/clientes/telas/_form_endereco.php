<?php
use app\models\Cidade;
use app\models\EstadoFederacao;

use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;
use kartik\select2\Select2;
use app\base\Util;
?>

<fieldset>
	<legend>Informações do Endereço</legend>
	<div class="row">
		<div class="col-md-12">
			<small class="help-block"><i class="fa fa-info-circle"></i>&nbsp; Se algum campo de endereço foir preenchido, será nessário informar o endereço por completo (CEP, Logradouro, Número, Bairro, Esatdo e Cidade).</small>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 tour15">
			<div class="row">
        		<div class="col-md-6">
        			<div class="form-group">
                        <?= $form->field($model, 'fone')->widget(MaskedInput::className(), [
                            	'mask' => ['(99) 9999-9999', '(99) 99999-9999'],
                                'clientOptions' => ['placeholder' => ' ', 'removeMaskOnSubmit' => true],
                            ])->textInput(['maxlength' => false]);
                        ?>
           			</div>
        		</div>
        		<div class="col-md-6">
        			<div class="form-group">
                        <?= $form->field($model, 'fone_celular')->widget(MaskedInput::className(), [
                            	'mask' => ['(99) 9999-9999', '(99) 99999-9999'],
                                'clientOptions' => ['placeholder' => ' ', 'removeMaskOnSubmit' => true],
                            ])->textInput(['maxlength' => false ]);
                        ?> 			
                   </div>
        		</div>
    		</div>
		</div>
		<div class="col-md-6 tour17">
			<div class="row">
				<div class="col-md-6">
        			<div class="form-group">
    					<?=$form->field($model, 'cep')->widget(MaskedInput::className (), [
                	   		    'mask' => '99999-999',
        					    'clientOptions' => ['placeholder' => ' ', 'removeMaskOnSubmit' => true]
                	   		])->textInput(['maxlength' => false]); 
    					?>
    				</div>
    			</div>
				<div class="col-md-6"> 
					<button type="button" class="<?= Util::BTN_COLOR_SUCCESS; ?> btnBuscaCep btn-block top25" data-loading-text="Carregando..."><i class="fa fa-search"></i>&nbsp; Busque pelo CEP</button>
				</div>				
   			</div>
		</div>
	</div>
    <div class="row">
		<div class="col-md-12 tour16">
        	<!-- ./row -->
        	<div class="row">
        		<div class="col-md-8">
        			<div class="form-group">
            			<?= $form->field($model, 'endereco')->textInput(['maxlength' => true])->label('Logradouro'); ?>
            		</div>
        		</div>
        		<div class="col-md-4">
        			<div class="form-group">
                		<?= $form->field($model, 'numero')->textInput(['maxlength' => true])->label('Número'); ?>
                	</div>
        		</div>
        	</div>
            <!-- ./row -->
        	<div class="row">
        		<div class="col-md-6">
        			<div class="form-group">
                    	<?= $form->field($model, 'complemento')->textInput(['maxlength' => true])->label('Complemento'); ?>
                    </div>
        		</div>
        		<div class="col-md-6">
        			<div class="form-group">
                    	<?= $form->field($model, 'bairro')->textInput(['maxlength' => 20])->label('Bairro'); ?>
                	</div>
        		</div>
        	</div>
        	<!-- ./row -->
        	<div class="row">
        		<div class="col-md-4">
        			<div class="form-group">
                        <?= $form->field($model, 'id_estado')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(EstadoFederacao::find()->orderBy('nome_estado')->all(), 'id', 'unidade_federacao'),
                                'pluginOptions' => [ 'allowClear' => true],
                                'hideSearch' => true,
                                'options' => [
                                    'placeholder' => 'UF',
                                    'onchange' => '
                                         if ($(this).val() > 0) {
                                             $.post(BASE_PATH + "cidade/lists/'.'"+$(this).val(), function(data) {
                                                $("#select2-clientes-id_cidade-container" ).html(\'\');
                                                $("#clientes-id_cidade").html(data);
                                             }).done(function() {
                                                 $("#clientes-id_cidade").val($("#clientes-id_cidade").data("cidade-id"));
                                             });
                                        } else {
                                             $("#clientes-id_cidade").html("");
                                             $("#clientes-id_cidade").val(null).trigger("change.select2");
                                        }
                                    '
                                ],
                            ])->label ('Estado');
                        ?>
          			</div>
        		</div>
        		<div class="col-md-8">
        			<div class="form-group">
                        <?= $form->field($model, 'id_cidade')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(Cidade::findAll([
                                    'estado_federacao_id' => $model->id_estado
                                ]), 'id', 'descricao'),
                            	'pluginOptions' => ['allowClear' => true],
                            	'hideSearch' => false,
                            	'options' => [
                            	    'placeholder' => 'Selecione a cidade',
                            	    'data-cidade-id' => $model->id_cidade,
                            	],
                            ])->label('Cidade');
                        ?>             
           			</div>
        		</div>
        	</div>
        	<!-- ./row -->
    	</div>
	</div>
	<!-- ./row -->
	<div class="row">
		<div class="col-md-12 tour18">
        	<div class="row">
        		<div class="col-md-4">
        			<div class="form-group">
                    	<?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'email@email.com']); ?>
                   </div>
        		</div>
        		<div class="col-md-4">
        			<div class="form-group">
                    	<?= $form->field($model, 'email_alt_1')->textInput(['maxlength' => true, 'placeholder' => 'email@email.com']); ?>
                   </div>
        		</div>
        		<div class="col-md-4">
        			<div class="form-group">
                    	<?= $form->field($model, 'email_alt_2')->textInput(['maxlength' => true, 'placeholder' => 'email@email.com']); ?>
                   </div>
        		</div>
        	</div>
        	<!-- ./row -->
    	</div>
	</div>
	<!-- ./row -->
</fieldset>
<!-- ./fieldset -->
