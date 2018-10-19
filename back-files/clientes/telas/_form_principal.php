<?php
use kartik\select2\Select2;
use kartik\switchinput\SwitchInput;
?>
<fieldset>
	<legend>Informações de Dados do Cadastro</legend>
	<div class="row">
		<div class="col-md-6 tour1">
        	<div class="row">
        		<div class="col-md-8">
        			<div class="form-group">
        				<?= $form->field($model, 'tipo')->widget(Select2::classname(), [
        					    'data' => [1 => 'Fisica', 2 => 'Juridica'],
        					    'hideSearch' => true,
        				    ]);
        				?>
        			</div>
        		</div>
        		<?php if($model->isNewRecord){ $model->ativo = true; } // coloca o cliente como ativo em um novo cadastro ?>
        		<div class="col-md-4">
        			<div class="form-group">
        				<?= $form->field($model, 'ativo')->widget(SwitchInput::classname(), [
                                'pluginOptions' => [
                                    'onText' => 'Sim',
                                    'offText' => 'Não',
                        			'value' => '',
                                ], 	
            				]);
        				?>
        			</div>
        		</div>
        	</div>
    	</div>
	</div>
	<!-- ./row -->
	<div class="row">
		<div class="col-md-12 no-padding" id="dv_cpf">
			<?= $this->render('/clientes/telas/_form_cpf', [
    				 'model' => $model,
    				 'form' => $form,
    			 ]);
			?>
		</div>
		<!-- ./pessoa fisica -->
		<div class="col-md-12" id="dv_cnpj" style="display: none">
			<?= $this->render('/clientes/telas/_form_cnpj', [
    				 'model' => $model,
    				 'form' => $form,
    			 ]);
			?>
		</div>
		<!-- ./pessoa juridica -->
	</div>
	<!-- ./row -->
	<div class="row">
		<div class="col-md-12 tour-obs">
			<?= $form->field($model, 'observacao')->textarea([
                    'rows' => 6, 
                    'style' => ['resize' => 'none'],
                    'value' => str_replace(['<br/>', '<br />', '<br>'], "\n", $model->observacao),
                ], ['maxlength' => true])->label('Observação');
            ?>
		</div>
	</div>
	<!-- ./row -->
</fieldset>





