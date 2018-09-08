<?php
use app\base\Util;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Colaborador;
?>
<?php $form = ActiveForm::begin(); ?>
	<div class="panel panel-primary panel-box">
		<div class="panel-body">
            <div class="row">
            	<div class="col-md-8 col-sm-8 col-lg-8 col-xs-12">
                    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]); ?>
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <?= $form->field($model, 'cargo')->dropDownList(Colaborador::getListaCargos()); ?>
                </div>
			</div>
			<!-- ./row -->
			<div class="row">
				<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">        
            		<?= $form->field($model, 'username')->textInput(['maxlength' => true, 'autocomplete' => 'off']); ?>
            	</div>
        		<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
            		<?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'autocomplete' => 'off']); ?>
            	</div>
        	</div>
        	<!-- ./row -->
		</div>
		<!-- ./painel-body -->
        <div class="panel-footer">
    		<div class="row">
    			<div class="col-md-3 col-sm-4 col-lg-3 col-xs-6">
                    <div class="form-group">
                        <?= Html::submitButton('<i class="fa fa-save"></i>&nbsp; '. ($model->isNewRecord ? 'Cadastrar' : 'Alterar'), [
                                'class' => $model->isNewRecord 
                                ? Util::BTN_COLOR_SUCCESS.' btn-block' 
                                : Util::BTN_COLOR_PRIMARY.' btn-block',
                            ]);
                        ?>
                    </div>
    			</div>
    			<div class="col-md-3 col-sm-4 col-lg-3 col-xs-6 pull-right">
                    <div class="form-group">
                        <?= Html::a('<i class="fa fa-reply"></i>&nbsp; Voltar', ['/colaborador'], [
                                'class' => Util::BTN_COLOR_DEFAULT.' btn-block',
                            ]);
                        ?>
                    </div>
    			</div>
    		</div>
    		<!-- ./row -->
        </div>
        <!-- ./panel-footer -->
	</div>
	<!-- ./painel-footer -->
<?php ActiveForm::end(); ?>
<!-- ./form -->


