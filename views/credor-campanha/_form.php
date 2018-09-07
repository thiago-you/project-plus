<?php
use yii\web\View;
use app\base\Util;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use app\models\CredorCampanha;
?>
<?php $form = ActiveForm::begin(['id' => 'form-campanha']); ?>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <!-- ./row -->
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">        
            <?= $form->field($model, 'vigencia_inicial')->widget(DatePicker::className(), [
	                'removeButton' => false,
    				'pluginOptions' => [
    					'autoclose' => true,
    					'format' => 'yyyy-mm-dd',
    				],
            	]);
            ?>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <?= $form->field($model, 'vigencia_final')->widget(DatePicker::className(), [
                    'removeButton' => false,
                    'pluginOptions' => [
    					'autoclose' => true,
    					'format' => 'yyyy-mm-dd'
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
                            ]);
                        ?>
                    </div>
    				<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4">
                        <?= $form->field($model, 'por_parcela')->dropDownList([
                                CredorCampanha::NAO => 'Não',
                                CredorCampanha::SIM => 'Sim',
                            ]);
                        ?>
					</div>
    				<div class="col-md-4 col-sm-4 col-xs-4 col-lg-4">    				
                		<?= $form->field($model, 'por_portal')->dropDownList([
        		                CredorCampanha::NAO => 'Não',  
        		                CredorCampanha::SIM => 'Sim', 
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
<?php 
$script = <<<JS
	$(document).ready(function() {
		// registra a campanha por ajax
		$('body').on('submit', '#form-campanha', function(e) {
            e.preventDefault();			

            // pega a lista e o id            
            let lista = $('#campanhas-lista');
            let id = lista.data('id');

            // valida se achou a lista e o id
            if (lista == undefined || lista.length == 0 || id === undefined || id === '') {
                toastr.error('Não foi possível adicionar a campanha. Por favor, tente novamente mais tarde.');
                return false;
            }

            // incrementa a lista e seta a campanha
            lista.data('id', ++id);
            lista.append('<input name="Campanha['+id+'][nome]" value="'+$('#credorcampanha-nome').val()+'"/>');
            lista.append('<input name="Campanha['+id+'][vigencia-inicial]" value="'+$('#credorcampanha-vigencia_inicial').val()+'"/>');
            lista.append('<input name="Campanha['+id+'][vigencia-final]" value="'+$('#credorcampanha-vigencia_final').val()+'"/>');
            lista.append('<input name="Campanha['+id+'][prioridade]" value="'+$('#credorcampanha-prioridade').val()+'"/>');
            lista.append('<input name="Campanha['+id+'][por-parcela]" value="'+$('#credorcampanha-por_parcela').val()+'"/>');
            lista.append('<input name="Campanha['+id+'][por-portal]" value="'+$('#credorcampanha-por_portal').val()+'"/>');

            // seta a option no select
            $('#credor-id_campanha').append('<option value="'+id+'">'+$('#credorcampanha-nome').val()+'</option>').val(id).trigger('change');

            // fecha a modal
            $('#modal-campanha').modal('hide');

            return false;
		});
	});
JS;
// JS
$this->registerJs($script, View::POS_READY);
?>

