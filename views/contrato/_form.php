<?php
use yii\web\View;
use app\base\Helper;
use app\models\Cliente;
use app\models\Carteira;
use kartik\helpers\Html;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use app\models\ContratoTipo;
use yii\helpers\ArrayHelper;
?>
<?php $form = ActiveForm::begin(); ?>
	<div class="panel panel-primary panel-box">
        <div class="panel-body">
        	<div class="row">
        		<div class="col-md-7 col-sm-7 col-lg-7 col-xs-12">
                	<div class="row">
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
                            <?= $form->field($model, 'data_cadastro')->widget(DatePicker::className(), [
                                    'removeButton' => false,
                                    'pluginOptions' => [
                    					'autoclose' => true,
                    					'format' => 'dd/mm/yyyy'
                    				],
                            	]);
                            ?>
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
                            <?= $form->field($model, 'data_vencimento')->widget(DatePicker::className(), [
                                    'removeButton' => false,
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
                		<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                			<?= $form->field($model, 'id_cliente')->dropDownList(ArrayHelper::map(Cliente::find()->all(), 'id', 'nome'), [
                                    'prompt' => 'Selecione o cliente...'
                                ]);
                			?>
                		</div>
                	</div>
                	<!-- ./row -->
                	<div class="row">
                		<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                			<?= $form->field($model, 'id_carteira')->dropDownList(ArrayHelper::map(Carteira::find()->all(), 'id', 'nome'), [
	                                'prompt' => 'Selecione a carteira...',
                    			]); 
                			?>
                		</div>
                	</div>
                	<!-- ./row -->
                	<div class="row">
                		<div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
                            <?= $form->field($model, 'codigo_cliente')->textInput(['maxlength' => true]); ?>
                		</div>
                		<div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
                            <?= $form->field($model, 'codigo_contrato')->textInput(['maxlength' => true]); ?>
                		</div>
                	</div>
                	<!-- ./row -->
                	<div class="row">
                		<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
                            <?= $form->field($model, 'num_contrato')->textInput(['maxlength' => true]); ?>
                		</div>
                		<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
                            <?= $form->field($model, 'num_plano')->textInput(['maxlength' => true]); ?>
                		</div>
                		<div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
                            <?= $form->field($model, 'valor')->widget(MaskMoney::className(), [
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
                	<div class="row">
                    	<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                    		<?= $form->field($model, 'tipo')->widget(Select2::className(), [
            		                'data' => ArrayHelper::map(ContratoTipo::find()->where([
        		                         'ativo' => ContratoTipo::ATIVO       
            		                ])->all(), 'id', 'descricao'),
            		                'pluginOptions' => [
		                                'allowClear' => true,
            		                ],
            		                'options' => [
		                                'placeholder' => 'Selecione o tipo...'
            		                ],
                        		]); 
                    		?>
                    	</div>
                	</div>
                	<!-- ./row -->
                	<div class="row">
                    	<div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
                            <?= $form->field($model, 'regiao')->textInput(['maxlength' => true]); ?>
                    	</div>
                    	<div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
                            <?= $form->field($model, 'filial')->textInput(['maxlength' => true]); ?>
                    	</div>
                	</div>
                	<!-- ./row -->
                	<div class="row">
                		<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                    		<?= $form->field($model, 'observacao')->textarea(['maxlength' => true]); ?>
                		</div>
                    </div>
                    <!-- ./row -->
                </div>
                <!-- ./col dados do contrato -->
                <div class="col-md-5 col-sm-5 col-lg-5 col-xs-12">
                	<div class="row">
                		<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                			<?= $form->field($model, 'data_negociacao')->widget(DatePicker::className(), [
                                    'removeButton' => false,
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
                		<div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
                			<?= Html::button('<i class="fa fa-plus"></i>&nbsp; Parcela', [
                                'id' => 'nova-parcela',
                                'class' => Helper::BTN_COLOR_EMERALD.' btn-block',
    			                'disabled' => $model->data_negociacao ? false : true,
                            ]);
                        ?>
                		</div>
                	</div>
                	<!-- ./row -->
                	<div class="row">
                		<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                			<div class="parcelas" style="max-height: 400px; overflow-y: auto; display: block;">
                    			<table id="table-parcelas" class="table table-bordered table-hover">
                    				<thead>
                    					<tr>
                    						<th class="text-center">N°</th>
                    						<th>Vencimento</th>
                    						<th>Valor</th>
                    						<th></th>
                    					</tr>
                    				</thead>
                    				<!-- ./thead -->
                    				<tbody data-num="<?= $model->isNewRecord ? 0 : count($model->contratoParcelas) ?>">
            							<!-- ./template row -->
                    					<?php if (!$model->isNewRecord && count($model->contratoParcelas) > 0): ?>
                    						<?php foreach($model->contratoParcelas as $parcela): ?>
                    							<tr id="linha-<?= ++$num; ?>">
                    								<td class="text-center"><?= $num; ?></td>
                    								<td>
                    									<?= $parcela->data_vencimento; ?>
                    									<input name="Parcela[<?= $num; ?>][vencimento]" value="<?= $parcela->data_vencimento; ?>" class="hidden"/>
                									</td>
                    								<td>
                    									<?= Helper::mask($parcela->valor, Helper::MASK_MONEY); ?>
                    									<input name="Parcela[<?= $num; ?>][valor]" value="<?= $parcela->valor; ?>" class="hidden"/>
                    								</td>
                    								<td class="text-center">
                    									<?= Html::button('<i class="fa fa-edit fa-fw"></i>', [
            									                'class' => Helper::BTN_COLOR_WARNING.' btn-xs editar-parcela',
                    									   ]); 
                    									?>
                    									<?= Html::button('<i class="fa fa-times fa-fw"></i>', [
            									                'class' => Helper::BTN_COLOR_DANGER.' btn-xs deletar-parcela',
                    									   ]); 
                    									?>
                    								</td>
                    							</tr>
                    						<?php endforeach; ?>
                    					<?php endif; ?>
                    				</tbody>
                    				<!-- ./tbody -->
                    			</table>
                    			<!-- ./table -->
                			</div>
                			<!-- ./parcelas -->
                		</div>
                	</div>
                	<!-- ./row -->
                </div>
                <!-- ./dados da parcela -->
            </div>
            <!-- ./row -->
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
                    <?= Html::a('<i class="fa fa-reply"></i>&nbsp; Voltar', ['/contrato'], [
                            'class' => Helper::BTN_COLOR_DEFAULT.' btn-block',
                        ]);
                    ?>
    			</div>
    		</div>
    		<!-- ./row -->
        </div>
        <!-- ./panel-footer -->
    </div>
    <!-- ./panel -->
<?php ActiveForm::end(); ?>
<!-- ./form -->
<div id="modal-parcela" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
  			<div class="modal-header">
    			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    			<h4 class="modal-title">
    				<i class="fa fa-credit-card"></i>&nbsp; Parcelas
				</h4>
  			</div>
  			<!-- ./modal-header -->
  			<div class="modal-body">
  				<input id="parcela-num" value="" class="hidden"/>
  				<!-- ./hidden input -->
  				<div class="row">
  					<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
    					<?= Html::label('Vencimento', 'parcela-vencimento'); ?>
    					<?= DatePicker::widget([
                                'name' => 'parcela-vencimento',
				                'id' => 'parcela-vencimento',
    			                'removeButton' => false,
    			                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd/mm/yyyy'
    			                ],
    					   ]); 
    					?>
					</div>    			
					<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
						<?= Html::label('Valor', 'parcela-valor'); ?>
    					<?= MaskMoney::widget([
    			                'name' => 'parcela-valor',
				                'id' => 'parcela-valor',
                                'options' => [
                                    'maxlength' => '14',
                                ],
            	                'pluginOptions' => [
                                    'prefix' => 'R$ ',
                                    'precision' => 2,
	                                'decimal' => ',',
	                                'thousands' => '.',
                                ],
                            ]);
                        ?>
					</div>
					<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
						<?= Html::label('Núm. Parcelas', 'parcela-quant'); ?>
    					<?= Html::textInput('parcela-quant', 1, [
				                'id' => 'parcela-quant',
				                'type' => 'number',
				                'class' => 'form-control',
				                'min' => '1',
                            ]);
                        ?>
                        <small class="help-block">
                        	<i class="fa fa-info-circle"></i>&nbsp; A parcela vai se repetir mensalmente.
                        </small>
					</div>
				</div>		
				<!-- ./row -->
  			</div>
  			<!-- ./modal-body -->
  			<div class="modal-footer">
  				<div class="row">
  					<div class="col-md-4 col-sm-4 col-lg-4 col-xs-6">
						<?= Html::button('<i class="fa fa-save"></i>&nbsp; Salvar', [
				                'id' => 'save-parcela',
				                'class' => Helper::BTN_COLOR_SUCCESS.' btn-block', 
                            ]);
                        ?>
  					</div>
  					<div class="col-md-4 col-sm-4 col-lg-4 col-xs-6 pull-right">
  						<?= Html::button('<i class="fa fa-times"></i>&nbsp; Cancelar', [
                                'class' => Helper::BTN_COLOR_DEFAULT.' btn-block',
				                'data-dismiss' => 'modal',
                            ]);
                        ?>
  					</div>
  				</div>
  				<!-- ./row -->
  			</div>
  			<!-- ./modal-footer -->
		</div>
	</div>
</div>
<!-- /.modal parcela -->
<?php 
$script = <<<JS
$(document).ready(function() {
    $('body').on('change', '#contrato-data_negociacao', function() {
        if (this.value != '') {
            $('#nova-parcela').prop('disabled', false);
        } else {
            $('#nova-parcela').prop('disabled', true);
        }
    });

    // limpa os dados da modal
    $('#modal-parcela').on('hidden.bs.modal', function() {
        $(this).find('#parcela-num').val('');
        $(this).find('#parcela-vencimento').val('').trigger('change');
        $(this).find('#parcela-valor-disp').val('').trigger('change');
        $(this).find('#parcela-quant').val(1).prop('disabled', false);
    });

    // abre a modal para adicionar a parcela
    $('body').on('click', '#nova-parcela', function() {
        // busca a modal
        const modalParcela = $('#modal-parcela');        
            
        // exibe a modal
        modalParcela.modal('show');

        return false;
    });

    // abre a modal de edicao da parcela
    $('body').on('click', '.editar-parcela', function() {
        // busca a linha e os valores
        const linha = $(this).closest('tr');
        
        const parcelaNum = linha.attr('id').replace('linha-', '');
        const vencimento = linha.find('td:nth-child(2)').find('input').val();
        const valor = linha.find('td:nth-child(3)').text().trim();

        // busca a modal
        const modalParcela = $('#modal-parcela');        
            
        // limpa os dados e exibe a modal
        modalParcela.find('#parcela-num').val(parcelaNum);
        modalParcela.find('#parcela-vencimento').val(vencimento).trigger('change');
        modalParcela.find('#parcela-valor-disp').val(valor).trigger('change');
        modalParcela.find('#parcela-quant').prop('disabled', true);
        modalParcela.modal('show');

        return false;
    });

    // valida o numero máximo de parcelas
    $('body').on('change', '#parcela-quant', function() {
        if (Number(this.value) > 500) {
            $(this).val(500).trigger('change');
        }
    });

    // adiciona a parcela na lista
    $('body').on('click', '#save-parcela', function() {
        // busca a modal e a tabela
        const modalParcela = $('#modal-parcela');
        const tabela = $('#table-parcelas tbody');

        // pega os valores da modal
        let parcelaVencimento = modalParcela.find('#parcela-vencimento').val();
        const parcelaNum = modalParcela.find('#parcela-num').val();
        const parcelaValor = modalParcela.find('#parcela-valor').val();
        const parcelaValorDisp = modalParcela.find('#parcela-valor-disp').val();
        const parcelaQuant = Number(modalParcela.find('#parcela-quant').val());

        // valida os valores da modal
        if (parcelaVencimento == undefined || parcelaVencimento == '' ||
            parcelaValor == undefined || parcelaValor == '' || parcelaValor == 0
        ) {
            toastr.error('Por favor, preencha a data de vencimento e o valor da parcela.');
            return false;
        }
       
        // adiciona a nova linha
        if (parcelaNum == '') {
            // incrementa o numero da parcela        
            let num = parseInt(tabela.attr('data-num'));
    
            // pega o vencimento da primeira parcela
            let vencimento = parcelaVencimento.split('/');
    		vencimento = new Date(vencimento[2], Number(vencimento[1])-1, vencimento[0]);
    		let dd, mm, yyyy;
           
            // insere todas as parcelas
            for (let i = 0; i < parcelaQuant; i++) {
                // atualiza o numero da parcela
                tabela.attr('data-num', ++num);
                
                // adiciona a linha na tabela
                tabela.append('<tr></tr>').find('tr:last').attr('id', 'linha-'+num);
                let linha = tabela.find('tr:last');
    
                // calcula o vencimento da próxima parcela
                if (i > 0) {
        			vencimento.setMonth(vencimento.getMonth() + 1);
        			dd = vencimento.getDate();
        			mm = vencimento.getMonth() + 1;
        			yyyy = vencimento.getFullYear();
        
        			// formata o dia e mes
        			if (dd < 10) {
        			    dd = '0'+dd
        			} 
        			if (mm < 10) {
        			    mm = '0'+mm
        			}

                    // seta o vencimento
                    parcelaVencimento = dd+'/'+mm+'/'+yyyy;
                }

                linha.append('<td class="text-center"></td>').find('td:last').append(num);
                linha.append('<td></td>').find('td:last').append(parcelaVencimento + '<input name="Parcela['+num+'][vencimento]" value="'+parcelaVencimento+'" class="hidden"/>');
                linha.append('<td></td>').find('td:last').append(parcelaValorDisp + '<input name="Parcela['+num+'][valor]" value="'+parcelaValor+'" class="hidden"/>');
                linha.append('<td class="text-center"></td>').find('td:last').append('<button class="btn btn-warning btn-flat btn-xs editar-parcela"><i class="fa fa-edit fa-fw"></i></button>&nbsp;<button class="btn btn-danger btn-flat btn-xs deletar-parcela"><i class="fa fa-times fa-fw"></i></button>');
            }
        } else {
            // busca a linha alterada        
            let linha = tabela.find('tr#linha-'+parcelaNum);
                
            // seta os novos valores
            linha.find('td:nth-child(2)').html(parcelaVencimento + '<input name="Parcela['+parcelaNum+'][vencimento]" value="'+parcelaVencimento+'" class="hidden"/>');
            linha.find('td:nth-child(3)').html(parcelaValorDisp + '<input name="Parcela['+parcelaNum+'][valor]" value="'+parcelaValor+'" class="hidden"/>');
        }

        // limpa o num e fecha a modal
        modalParcela.modal('hide').find('#parcela-num').val('');
    });

    // deleta a parcela
    $('body').on('click', '.deletar-parcela', function() {
        // busca e remove a linha
        $(this).closest('tr').remove();
        
        // atualiza o numero de linhas na tabela
        const tabela = $('#table-parcelas tbody');
        const numParcelas = Number($('#table-parcelas tbody').attr('data-num')) - 1;
        tabela.attr('data-num', numParcelas);

        // atualiza o numero de cada tabela
        let num = 0;
        tabela.find('tr').each(function() {
            $(this).attr('id', 'linha-'+(++num));
            $(this).find('td:first').text(num);
        });

        return false;
    });
});
JS;
$this->registerJs($script, View::POS_LOAD);
?>

