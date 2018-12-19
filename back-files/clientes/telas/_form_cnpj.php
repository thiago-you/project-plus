<?php
use kartik\select2\Select2;
use yii\widgets\MaskedInput;
?>
<fieldset>
	<legend>Cadastro de Pessoa Juridica</legend>
	<div class="row">
		<div class="col-md-8 tour10">
        	<div class="row">
        		<div class="col-md-6">
          			<div class="form-group" >
            			 <?= $form->field($model, 'cnpj')->textInput(['maxlength' => true])->label('CNPJ')->widget(\yii\widgets\MaskedInput::className(), [
                			     'mask' => '99.999.999/9999-99',
                			     'clientOptions' => ['placeholder' => ' ', 'removeMaskOnSubmit' => true]
                			 ]);
            			 ?>
        			</div>
        		</div>
        		<div class="col-md-6">
           			<div class="form-group">
        				<label class="form-label">Inscrição Estadual</label>					   
        				<?= MaskedInput::widget([
                			    'model' => $model,
                			    'attribute' => 'iest',
                			    'mask' => '9',
                                'clientOptions' => [
                                    'repeat' => 12,
                                    'greedy' => false,
                                    'removeMaskOnSubmit' => true,
                                    'placeholder' => ' '
                                ]
                            ]);
                        ?>
        			</div>
        		</div>
    		</div>
		</div>
		<!-- ./col tour -->
		<div class="col-md-8 tour12">
			<div class="form-group">
				<?= $form->field($model, 'indie')->widget(Select2::classname(), [
    				    'data' => [
        				     1 => '1: Contribuinte de ICMS',
        					 2 => '2: Contribuinte isento de Inscrição no cadastro de Contribuintes do ICMS',
        					 9 => '9: Não Contribuinte, que pode ou não possuir Inscrição Estadual no Cadastro de Contribuintes do ICMS'
    					 ],
    					 'hideSearch' => true,
    				])->label('Indicador da IE');
				?>
			</div>
		</div>
	</div>
	<!-- ./row -->
	<div class="row">
		<div class="col-md-8 tour13">
            <div class="form-group required">
				<?= $form->field($model, 'razao_social')->textInput(['maxlength' => true])->label('Razão Social'); ?>
			</div>
		</div>
	</div>
	<!-- ./row -->
	<div class="row">
		<div class="col-md-8 tour14">
   			<div class="form-group">
				<?= $form->field($model, 'fantasia')->textInput(['maxlength' => '200'])->label('Nome Fantasia'); ?>
			</div>
		</div>
	</div>
	<!-- ./row -->
</fieldset>



