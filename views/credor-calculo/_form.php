<?php
use app\base\Util;
use yii\widgets\ActiveForm;
use kartik\money\MaskMoney;
use yii\widgets\MaskedInput;
?>
<?php $form = ActiveForm::begin(['id' => 'form-calculo']); ?>
	<?= $form->field($model, 'id_campanha')->hiddenInput()->label(false); ?>
	<!-- ./hidden input -->
	<div class="row">
		<div class="col-md-3 col-sm-3 col-xs-3 col-lg-3">
    		<?= $form->field($model, 'atraso_inicio')->widget(MaskedInput::className(), [
                    'mask' => '9',
	                'clientOptions' => ['greedy' => false, 'repeat' => 3],
                ]); 
    		?>
		</div>
		<div class="col-md-3 col-sm-3 col-xs-3 col-lg-3">
    		<?= $form->field($model, 'atraso_fim')->widget(MaskedInput::className(), [
                    'mask' => '9',
	                'clientOptions' => ['greedy' => false, 'repeat' => 3],
                ]);
    		?>
		</div>
        <div class="col-md-3 col-sm-3 col-xs-3 col-lg-3">
            <?= $form->field($model, 'multa')->widget(MaskMoney::className(), [
                    'options' => [
                        'maxlength' => '14',
                    ],
	                'pluginOptions' => [
                        'prefix' => 'R$ ',
                        'precision' => 2
                    ],
                ]);
            ?>
		</div>
		<div class="col-md-3 col-sm-3 col-xs-3 col-lg-3">
        	<?= $form->field($model, 'juros')->widget(MaskMoney::className(), [
	                'options' => [
                        'maxlength' => '14',
	                ],
                    'pluginOptions' => [
                        'prefix' => 'R$ ',
                        'precision' => 2
                    ],
                ]);
        	?>
        </div>
	</div>
	<!-- ./row -->
	<hr>
	<!-- ./divisor  -->
    <div class="row">
		<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
            <button type="submit" class="<?= Util::BTN_COLOR_EMERALD; ?> btn-block">
            	<i class="fa fa-save"></i>&nbsp; Salvar
            </button>
        </div>
        <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12 pull-right">
            <button type="button" class="<?= Util::BTN_COLOR_DEFAULT; ?> btn-block" data-dismiss="modal">
            	<i class="fa fa-times"></i>&nbsp; Cancelar
        	</button>
        </div>
    </div>
    <!-- ./row -->
<?php ActiveForm::end(); ?>
<!-- ./form -->