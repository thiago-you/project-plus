<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin(); ?>
	<div class="panel panel-primary panel-box">
		<div class="panel-body">
			<fieldset>
				<legend>Informações Principais</legend>
        		<div class="row">
        			<div class="col-md-6">
                        <?= $form->field($model, 'nome')->textInput(['maxlength' => true]); ?>
        			</div>
        			<div class="col-md-6">
                    	<?= $form->field($model, 'sobrenome')->textInput(['maxlength' => true]); ?>
                    </div>
        		</div>
    			<div class="row">
        			<div class="col-md-3">
                        <?= $form->field($model, 'apelido')->textInput(['maxlength' => true]); ?>
        			</div>
        			<div class="col-md-3">
                        <?= $form->field($model, 'documento')->textInput(['maxlength' => true]); ?>
        			</div>
        			<div class="col-md-3">
                        <?= $form->field($model, 'sexo')->dropDownList([ 'M' => 'M', 'F' => 'F', ], ['prompt' => '']); ?>
        			</div>
        			<div class="col-md-3">
                        <?= $form->field($model, 'data_nascimento')->textInput(); ?>
                    </div>
        		</div>  
    		</fieldset>
    		<fieldset>      
            	<legend>Endereço</legend>
            	<div class="row">
        			<div class="col-md-4">
                        <?= $form->field($model, 'cep')->textInput(['maxlength' => true]); ?>
        			</div>
        			<div class="col-md-4">
                        <?= $form->field($model, 'endereco')->textInput(['maxlength' => true]); ?>
        			</div>
        			<div class="col-md-4">
    		            <?= $form->field($model, 'numero')->textInput(['maxlength' => true]); ?>
        			</div>
        		</div><div class="row">
        			<div class="col-md-3">
            			<?= $form->field($model, 'complemento')->textInput(['maxlength' => true]); ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'bairro')->textInput(['maxlength' => true]); ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'id_cidade')->textInput(); ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'id_estado')->textInput(); ?>
        			</div>
        		</div>
    		</fieldset>
    		<fieldset>
    			<legend>Outros Dados</legend>
        		<div class="row">
        			<div class="col-md-4">
        				<?= $form->field($model, 'email')->textInput(['maxlength' => true]); ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'situacao')->textInput(); ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'tipo')->textInput(['maxlength' => true]); ?>
        			</div>
        		</div>
    		</fieldset>
        </div>
        <div class="panel-footer">
    		<div class="row">
    			<div class="col-md-3">
                    <div class="form-group">
                        <?= Html::submitButton('<i class="fa fa-save"></i>&nbsp; '. ($model->isNewRecord ? 'Cadastrar' : 'Alterar'), [
                                'class' => $model->isNewRecord 
                                ? 'btn btn-emerald btn-flat btn-block' 
                                : 'btn btn-primary btn-flat btn-block'            
                            ]);
                        ?>
                    </div>
    			</div>
    			<div class="col-md-3 pull-right">
                    <div class="form-group">
                        <?= Html::a('<i class="fa fa-reply"></i>&nbsp; Voltar', ['/cliente'], [
                                'class' => 'btn btn-flat btn-warning btn-block',
                            ]);
                        ?>
                    </div>
    			</div>
    		</div>
        </div>
	</div>
<?php ActiveForm::end(); ?>
