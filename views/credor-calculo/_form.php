<?php
use app\base\Helper;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
?>
<?php $form = ActiveForm::begin(['id' => 'form-calculo']); ?>
	<?= $form->field($model, 'id_campanha')->hiddenInput()->label(false); ?>
	<!-- ./hidden input -->
	<div class="row">
		<div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">
    		<?= $form->field($model, 'atraso_inicio')->widget(MaskedInput::className(), [
                    'mask' => '9',
	                'clientOptions' => ['greedy' => false, 'repeat' => 5],
                ]); 
    		?>
		</div>
		<div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">
    		<?= $form->field($model, 'atraso_fim')->widget(MaskedInput::className(), [
                    'mask' => '9',
	                'clientOptions' => ['greedy' => false, 'repeat' => 5],
                ]);
    		?>
		</div>
	</div>
	<!-- ./row -->
	<div class="row">
        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">
            <?= $form->field($model, 'multa', ['enableClientValidation' => false])->textInput(['class' => 'form-control maskmoney-input', 'maxlength' => 9]);?>
		</div>
		<div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">
        	<?= $form->field($model, 'juros', ['enableClientValidation' => false])->textInput(['class' => 'form-control maskmoney-input', 'maxlength' => 9]);?>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">
        	<?= $form->field($model, 'honorario', ['enableClientValidation' => false])->textInput(['class' => 'form-control maskmoney-input', 'maxlength' => 9]);?>
        </div>
	</div>
	<!-- ./row -->
	<div class="row">
        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">
            <?= $form->field($model, 'desc_encargos_max', ['enableClientValidation' => false])->textInput(['class' => 'form-control maskmoney-input', 'maxlength' => 8]);?>
		</div>
		<div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">
        	<?= $form->field($model, 'desc_principal_max', ['enableClientValidation' => false])->textInput(['class' => 'form-control maskmoney-input', 'maxlength' => 8]);?>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">
        	<?= $form->field($model, 'desc_honorario_max', ['enableClientValidation' => false])->textInput(['class' => 'form-control maskmoney-input', 'maxlength' => 8]);?>
        </div>
	</div>
	<!-- ./row -->
	<hr>
	<!-- ./divisor  -->
    <div class="row">
		<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
            <button type="submit" class="<?= Helper::BTN_COLOR_EMERALD; ?> btn-block">
            	<i class="fa fa-save"></i>&nbsp; Salvar
            </button>
        </div>
        <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12 pull-right">
            <button type="button" class="<?= Helper::BTN_COLOR_DEFAULT; ?> btn-block" data-dismiss="modal">
            	<i class="fa fa-times"></i>&nbsp; Cancelar
        	</button>
        </div>
    </div>
    <!-- ./row -->
<?php ActiveForm::end(); ?>
<!-- ./form -->