<?php
use app\base\Util;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
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
	        			<div class="col-md-6">
	                        <?= $form->field($model, 'nome')->textInput(['maxlength' => true]); ?>
	        			</div>
	        		</div>
	        		<!-- ./row -->
	    			<div class="row">
	        			<div class="col-md-3">
	                        <?= $form->field($model, 'nome_social')->textInput(['maxlength' => true]); ?>
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
	        		<!-- ./row -->
	        		<div class="row">
	                    <div class="col-md-4">
	                        <?= $form->field($model, 'tipo')->textInput(['maxlength' => true]); ?>
	        			</div>
	        		</div>
	        		<!-- ./row -->
				</div>
				<!-- ./tab principal -->
			  	<div class="tab-pane" id="tab-contato">
			  	
			  	</div>
			  	<!-- ./tab contato -->
			  	<div class="tab-pane" id="tab-endereco">
			  	</div>
			  	<!-- ./tab endereco -->
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
                                : Util::BTN_COLOR_PRIMARY.' btn-block'            
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
<?php ActiveForm::end(); ?>
