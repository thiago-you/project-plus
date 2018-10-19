<?php
use app\models\Cidade;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;
use app\models\EstadoFederacao;
use app\base\Util;
?>
<fieldset>
	<legend>Informações do Local de Cobrança</legend>
    <div class="row">
    	<div class="col-md-4 tour22">
    		<div class="form-group">
    			<label>Copiar endereço?</label>
				<button type="button" class="btn btn-block btn-info btn-flat" id="copiar_end"><i class="fa fa-copy"></i>&nbsp; Copiar endereço do cliente</button>
    		</div>
    	</div>
    	<div class="col-md-8 tour23">
        	<div class="row">
            	<div class="col-md-6">
            		<div class="form-group">
                        <?= $form->field($model, 'tpcobr')->widget(Select2::classname(), [
                                'data' => [1 => 'Carteira', 2 => 'Boleto', 3 => 'Banco'],
                                'hideSearch' => true,
                       	        'pluginOptions' => ['allowClear' => true],
                       	        'options' => [
                       	            'placeholder' => 'Selecione',
                       	        ],
                            ])->label('Tipo de Cobrança');
                   	    ?>
            		</div>
            	</div>
        		<div class="col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'loc_cobr')->widget(Select2::classname(), [
                                'data' => [1 => 'Residencial', 2 => 'Comercial', 3 => 'Outros'],
                                'hideSearch' => true,
                       	        'pluginOptions' => ['allowClear' => true],
                       	        'options' => [
                       	            'placeholder' => 'Selecione',
                       	        ],
                            ])->label('Local de Cobrança');
                   	    ?>
        			</div>
        		</div>
    		</div>
		</div>
		<!-- ./col tour -->
    </div>
    <!-- ./row -->
    <div class="row">
	    <div class="col-md-12 tour25">
			<div class="row">
				<div class="col-md-4">
        			<div class="form-group">
    					<?=$form->field($model, 'cep_cobr')->widget(MaskedInput::className (), [
            	   		    'mask' => '99999-999',
    					    'clientOptions' => ['placeholder' => ' ', 'removeMaskOnSubmit' => true]
            	   		])->textInput(['maxlength' => false])->label('CEP p/ Cobrança'); ?>
    				</div>
    			</div>
				<div class="col-md-4"> 
					<button type="button" class="<?= Util::BTN_COLOR_SUCCESS; ?> btnBuscaCepCobr btn-block" data-loading-text="Carregando..." style="margin-top: 25px"><i class="fa fa-search"></i> Busque pelo CEP</button>
				</div>				
   			</div>
		</div>
	</div>
	<!-- ./row -->
	<div class="row">
		<div class="col-md-12 tour24">
        	<div class="row">
        		<div class="col-md-10">
        	        <div class="form-group">
        			    <?= $form->field($model, 'ender_cobr')->textInput(['maxlength' => true])->label('Logradouro'); ?>
        			</div>
        		</div>
        		<div class="col-md-2">
        	        <div class="form-group">
        			    <?= $form->field($model, 'nrend_cobr')->textInput(['maxlength' => true])->label('Número'); ?>
        			</div>
        		</div>
        	</div>
        	<!-- ./row -->
        	<div class="row">
        		<div class="col-md-6">
        	        <div class="form-group">
        			    <?= $form->field($model, 'compl_cobr')->textInput(['maxlength' => true])->label('Complemento'); ?>
        			</div>
        		</div>
        		<div class="col-md-6">
        	        <div class="form-group">
        			    <?= $form->field($model, 'bairro_cobr')->textInput(['maxlength' => true])->label('Bairro'); ?>
        			</div>
        		</div>
        	</div>
        	<!-- ./row -->
        	<div class="row">
        	    <div class="col-md-3">
        	    	<div class="form-group">
        			    <?= $form->field($model, 'id_estado_cobr')->widget(Select2::classname(), [
            	            	'data' => ArrayHelper::map(EstadoFederacao::find()->orderBy('nome_estado')->all(), 'id', 'unidade_federacao'),  
            					'pluginOptions' => ['allowClear' => true],
            	                'hideSearch' => false,
            			        'options' => [
            			            'placeholder' => 'UF',
            			            'onchange' => '
                                         if ($(this).val() > 0) {
                                             $.post(BASE_PATH + "cidade/lists/'.'"+$(this).val(), function(data) {
                                                $("#select2-clientes-id_cidade_cobr-container" ).html(\'\');
                                                $("#clientes-id_cidade_cobr").html(data);
                                             }).done(function() {
                                                 $("#clientes-id_cidade_cobr").val($("#clientes-id_cidade_cobr").data("cidade-id"));
                                             });
                                        } else {
                                             $("#clientes-id_cidade_cobr").html("");
                                             $("#clientes-id_cidade_cobr").val(null).trigger("change.select2");
                                        }
                                    '
            			        ],
            	            ])->label('Estado');
        				?>
        			</div>
        	    </div>
        		<div class="col-md-9">
        	       <div class="form-group">
        				<?= $form->field($model, 'id_cidade_cobr')->widget(Select2::classname(), [
        				        'data' => ArrayHelper::map(Cidade::findAll([
        				            'estado_federacao_id' => $model->id_estado_cobr
        				        ]), 'id', 'descricao'),
                                'pluginOptions' => ['allowClear' => true],
                                'hideSearch' => false,
            				    'options' => [
            				        'placeholder' => 'Selecione a cidade',
            				        'data-cidade-id' => $model->id_cidade_cobr,
            				    ],
                            ])->label('Cidade');
                    	?>
        	  		</div>
        		</div>
        	</div>
        	<!-- ./row -->
		</div>
	</div>
	<!-- ./row tour -->
	<div class="row">
		<div class="col-md-12 tour26">
			<div class="row">
        		<div class="col-md-8">
        	        <div class="form-group">
        			    <?= $form->field($model, 'nome_contato')->textInput(['maxlength' => true])->label('Pessoa p/ Contato'); ?>
        			</div>
        		</div>
        		<div class="col-md-4">
        	        <div class="form-group">
        			    <?= $form->field($model, 'fone_contato')->textInput(['maxlength' => true])->label('Telefone p/ Contato')->widget(MaskedInput::className(), [
            			        'mask' => '(99) 99999-9999',
                                'clientOptions' => ['removeMaskOnSubmit' => true, 'placeholder' => ' ', 'removeMaskOnSubmit' => true],
            			    ]);
        			    ?>
        			</div>
        		</div>
            </div>
            <!-- ./row -->
        </div>
    </div>
    <!-- ./row tour -->
</fieldset>
<?php 
$script = <<<JS
    // busca o endereco pelo CEP
    $(document).ready(function() {
        // se o cep for preenchido corretamente, chama a pesquisa automaticamente
        $('body').on('keyup', '#clientes-cep_cobr', function() {
            let cep = $(this).val().replace(/_|-| /gi, '');
            if(cep.length == 8) {
                $('.btnBuscaCepCobr').trigger('click');
            }
        });        

        // pesquisa o endereco pelo cep
        $('body').on('click', '.btnBuscaCepCobr', function() {
            let cep = $('#clientes-cep_cobr').val().replace(/_|-| /gi, '');
            let button = $(this);
            
            toastr.clear();
            if (cep.length < 8) {
                toastr.options = {
                    'preventDuplicates' : true,
                };
                toastr.warning('CEP de cobrança não foi preenchido completamente ou não é valido.');
                return false;
            } else {
                button.button('loading');		
            }
            
            $.get(BASE_PATH + 'api/busca-cep?cep='+cep, function(response) {
                if (response.erro == true) {
                    toastr.warning('CEP não encontrado ou em formato inválido.');
                    $('#clientes-id_cidade_cobr').data('cidade-id', '');
                } else {
                    $('#clientes-ender_cobr').val(response.logradouro);
                    $('#clientes-bairro_cobr').val(response.bairro);
                    $('#clientes-id_estado_cobr').val(response.uf_id).trigger('change');
                    $('#clientes-id_cidade_cobr').data('cidade-id', response.cidade_id);
                }
            }).done(function() {
                button.button('reset');
            });
        });
    });
JS;
$this->registerJs($script)
?>


