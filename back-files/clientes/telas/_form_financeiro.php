<?php
use kartik\select2\Select2;
use kartik\money\MaskMoney;
?>
<fieldset>
	<legend>Informações Financeiras do Cliente</legend>
	<div class="row">
		<div class="col-md-12 tour19">
        	<div class="row">
        		<div class="col-md-6">
                    <div class="form-group">
        			    <?= $form->field($model, 'situacao')->widget(Select2::classname(), [
                                'data' => [1 => 'Normal', 2 => 'Bloqueada', 3 => 'Inativa'],
                                'hideSearch' => true,
            					'value' => $model->situacao,
            			        'pluginOptions' => ['allowClear' => false],
                                'pluginEvents' => [
                                    'change' => "function() { habilita_campo(); }",
                                ],
            			        'options' => [
                                    'placeholder' => 'Selecione',
                                    'onchange' => '
                                        if($(this).val() == 2) {
                                            $("#clientes-id_restricao" ).val(1);
                                            $("#select2-clientes-id_restricao-container").html("Inadimplente");
                			            }else {
                                            $("#clientes-id_restricao" ).val("");
                                            $("#select2-clientes-id_restricao-container").html("Sem Restrição");
                			            }
                                    ',
            			        ],
                            ])->label('Situação Financeira');
        			    ?>
        			</div>
        		</div>
        		<div class="col-md-6">
                    <div class="form-group">
        				<?=$form->field($model, 'id_restricao')->widget(Select2::classname(), [
                                'data' => [1 => 'Inadimplente', 2 => 'Serasa', 3 => 'Limite Credito'],
                                'id' => 'tipo_restricao',
                                'hideSearch' => true,
                                'disabled' => true,
                                'options' => [
                                    'placeholder' => 'Sem Restrição',
                                ],
                            ])->label('Tipo de Restrição');
        				?>										    
        			</div>
        		</div>
        	</div>
        	<!-- ./row -->
    	</div>
	</div>
	<!-- ./row -->
   	<div class="row">
		<div class="col-md-3 tour20">
            <div class="form-group">
            	<?= $form->field($model, 'limite_credito')->widget(MaskMoney::className(), [
                	    'pluginOptions' => [
                	        'prefix' => 'R$ ',
    						'allowNegative' => false,
    						'thousands' => '.',
    					    'decimal' => ',',
    					    'precision' => 2, 
                	    ],
                	    'options' => [
            	            'maxlength' => 16,
            	        ],
            	   ]);
            	?>               	
			</div>
		</div>
		<div class="col-md-9 tour21">
			<div class="row">
        		<div class="col-md-3">
                    <div class="form-group">
                    	<?= $form->field($model, 'divida_atual')->widget(MaskMoney::className(), [
            					'value' => $model->divida_atual,
            					'disabled' => true,
                    	        'readonly' => true,
                    	        'pluginOptions' => [
            						'prefix' => 'R$ ',
            						'allowNegative' => false,
            						'thousands' => '.',
            					    'decimal' => ',',
            					    'precision' => 2, 
            					],
            			        'options' => [
            			            'maxlength' => 16,
            			        ],
            			    ]);
                    	?> 
        			</div>
        		</div>
        		<div class="col-md-3">
                    <div class="form-group">
                       	<?= $form->field($model, 'vlr_disponivel')->widget(MaskMoney::className(), [
        			            'value' => $model->vlr_disponivel < 0 ? 0 : $model->vlr_disponivel,
            			        'disabled' => true,
            			        'readonly' => true,
        			            'pluginOptions' => [
            						'prefix' => 'R$ ',
            						'allowNegative' => true,
            						'thousands' => '.',
            					    'decimal' => ',',
            					    'precision' => 2, 
            					],
            			        'options' => [
            			            'maxlength' => 16,
            			        ],
            			    ]);
        			    ?>
        			</div>
        		</div>
        		<div class="col-md-3">
                    <div class="form-group">
        			    <?= $form->field($model, 'vlr_potencial')->widget(MaskMoney::className(), [
            			        'disabled' => true,
            			        'pluginOptions' => [
            			            'prefix' => 'R$ ',
            			            'thousands' => '.',
            			            'decimal' => ',',
            			            'precision' => 2
            			        ]
            			    ])->hiddenInput()->label(false);
        			    ?>
        			</div>
        		</div>
    		</div>
		</div>
	</div>
	<!-- ./row -->
	<br/><br/><br/><br/><br/><br/>
	<!-- ./espaçamento manual -->
</fieldset>
