<?php
use app\base\Helper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ContratoTipo;
?>
<?php $form = ActiveForm::begin(); ?>
    <div class="panel panel-primary panel-box">
		<div class="panel-body">
    		<div class="row">
    			<div class="col-md-8 col-sm-8 col-lg-8 col-xs-12">
                    <?= $form->field($model, 'descricao')->textInput(['maxlength' => true]); ?>
            	</div>
            	<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <?= $form->field($model, 'ativo')->dropDownList([
                        	ContratoTipo::ATIVO => 'Ativo',
                            ContratoTipo::NAO_ATIVO => 'Inativo',
                        ]); 
                    ?>
                </div>
        	</div>
    	</div>
		<!-- ./panel-body -->    
        <div class="panel-footer">
        	<div class="row">
    			<div class="col-md-3 col-sm-4 col-lg-3 col-xs-6">
                    <?= Html::submitButton('<i class="fa fa-save"></i>&nbsp; '. ($model->isNewRecord ? 'Cadastrar' : 'Alterar'), [
                            'class' => $model->isNewRecord 
                            ? Helper::BTN_COLOR_SUCCESS.' btn-block' 
                            : Helper::BTN_COLOR_PRIMARY.' btn-block',
                        ]);
                    ?>
    			</div>
    			<div class="col-md-3 col-sm-4 col-lg-3 col-xs-6 pull-right">
                    <?= Html::a('<i class="fa fa-reply"></i>&nbsp; Voltar', ['/contrato-tipo'], [
                            'class' => Helper::BTN_COLOR_DEFAULT.' btn-block',
                        ]);
                    ?>
    			</div>
    		</div>
    		<!-- ./row -->
        </div>        
	</div>
	<!-- ./panel -->
<?php ActiveForm::end(); ?>
<!-- ./form -->