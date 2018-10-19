<?php
use kartik\select2\Select2;
use kartik\date\DatePicker;
?>
<fieldset>
	<legend>Cadastro de Pessoa Fisica</legend>
	<div class="col-md-12">  
    	<div class="row">
    		<div class="col-md-8 tour3">
        		<div class="row">
        			<div class="col-md-6">
                		<div class="form-group" >
            				 <?= $form->field($model, 'cpf')->textInput([
                				 	'maxlength' => true,
                				 	'class' => 'required',
                				  ])->label('CPF')->widget(\yii\widgets\MaskedInput::className(), [
                				 	 'mask' => '999.999.999-99',
                				      'clientOptions' => ['placeholder' => ' ', 'removeMaskOnSubmit' => true]
                				  ]);
            				 ?>
                    	</div>
                    </div>
        			<div class="col-md-6">
            			<div class="form-group">
        				 	<?= $form->field($model, 'rg')->textInput(['maxlength' => true])->label('RG'); ?>
        				</div>
        			</div>
    			</div>
			</div>
		</div>
    	<!-- ./row -->
  		<div class="row">	
			<div class="col-md-8 tour5">
    			<div class="form-group required">
					<?= $form->field($model, 'nome')->textInput(['maxlength' => true, 'id' => 'clientes_nome'])->label('Nome do Cliente'); ?>
				</div>
			</div>
		</div>
		<!-- ./row -->
		<div class="row">
			<div class="col-md-4 tour6">
    			<div class="form-group">
					<?= $form->field($model, 'apelido')->textInput(['maxlength' => true])->label('Apelido') ?>
				</div>
			</div>	
			<div class="col-md-8 tour7">
				<div class="row">
        			<div class="col-md-6">
            			<div class="form-group">
        				 	<?= $form->field($model, 'dtanascto')->widget(DatePicker::classname(), [				        
                					  'options' => ['placeholder' => 'dd/mm/yyyy'],
                    	    		  'removeButton' => false,
                				 	  'pickerButton' => ['title' => 'Selecione a Data',],
                				 	  'removeButton' => ['title' => 'Limpar Data Selecionada',],
                    		    	  'pluginOptions' => [
                    		          'autoclose'=>true,
                    		    		  'todayHighlight' => true,
                    		    		  'format' => 'dd/mm/yyyy',
                    		    	      'endDate'=>date('d/m/Y'),
                    		    	  ],
                                ])->label('Data de Nascimento');
                    		?>
                    	</div>
                    </div>
        			<div class="col-md-6">
            			<div class="form-group">
                            <?= $form->field($model, 'sexo')->widget(Select2::classname(), [
                                    'data' => [1 => 'Masculino', 2 => 'Feminino'],
                                    'hideSearch' => true,
                                ])->label('Sexo');
                            ?>										    
        				</div>
        			</div>
    			</div>
    			<!-- ./row -->
			</div>
		</div>
		<!-- ./row -->
		<div class="row">
			<div class="col-md-12 tour9">
        		<div class="row">
        			<div class="col-md-6">
            			<div class="form-group">
        				 	<?= $form->field($model, 'nome_pai')->textInput(['maxlength' => true])->label('Nome do Pai'); ?>
        				</div>
        			</div>
        			
        			<div class="col-md-6">
            			<div class="form-group">
        					<?= $form->field($model, 'nome_mae')->textInput(['maxlength' => true])->label('Nome da Mãe'); ?>
        				</div>
        			</div>
        		</div>
    		</div>
		</div>
		<!-- ./row -->
	</div>
</fieldset>
