<?php
use app\base\Helper;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use app\models\CredorCampanha;
?>
<?php $form = ActiveForm::begin(['id' => 'form-campanha']); ?>
	<?= $form->field($model, 'id_credor')->hiddenInput()->label(false); ?>
	<!-- ./hidden id_credor -->
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <?= $form->field($model, 'nome')->textInput(['maxlength' => true, 'readonly' => $readonly]); ?>
        </div>
    </div>
    <!-- ./row -->
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">        
            <?= $form->field($model, 'vigencia_inicial')->widget(DatePicker::className(), [
	                'removeButton' => false,
                    'disabled' => $readonly,
    				'pluginOptions' => [
    					'autoclose' => true,
    					'format' => 'dd/mm/yyyy',
    				],
            	]);
            ?>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <?= $form->field($model, 'vigencia_final')->widget(DatePicker::className(), [
                    'removeButton' => false,
                    'disabled' => $readonly,
                    'pluginOptions' => [
    					'autoclose' => true,
    					'format' => 'dd/mm/yyyy'
    				],
            	]);
            ?>
        </div>
    </div>
    <!-- ./row -->
    <div class="row">
    	<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    		<fieldset>
    			<legend class="text-primary">Configurações</legend>
    			<div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-4 col-lg-4">	        
                        <?= $form->field($model, 'prioridade')->dropDownList([
                                '1' => '1',
                            ], [
                                'disabled' => $readonly,
                            ]);
                        ?>
                    </div>
    				<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4">
                        <?= $form->field($model, 'por_parcela')->dropDownList([
                                CredorCampanha::NAO => 'Não',
                                CredorCampanha::SIM => 'Sim',
                            ], [
                                'disabled' => $readonly,
                            ]);
                        ?>
					</div>
    				<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4">    				
                		<?= $form->field($model, 'por_portal')->dropDownList([
        		                CredorCampanha::NAO => 'Não',  
        		                CredorCampanha::SIM => 'Sim', 
                            ], [
                                'disabled' => $readonly,
                            ]);
                		?>
            		</div>
        		</div>
        		<!-- ./row -->
            </fieldset>
        </div>
	</div>
	<!-- ./row -->
	<hr>
	<!-- ./divisor  -->
	<?php if (!$readonly): ?>
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
    <?php endif; ?>
<?php ActiveForm::end(); ?>
<!-- ./form -->

