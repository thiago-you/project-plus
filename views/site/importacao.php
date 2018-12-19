<?php
use app\base\Helper;
use kartik\helpers\Html;
use kartik\file\FileInput;
use kartik\form\ActiveForm;

$this->title = 'Importação';
$this->params['breadcrumbs'][] = ['label' => 'Home', 'url' => ['/']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <div class="panel panel-primary panel-box">
        <div class="panel-heading">
        	<h4><i class="fa fa-upload"></i>&nbsp; Importação de Arquivo Excel</h4>
        </div>
        <!-- ./box-header -->
        <br><br>
        <div class="panel-body">  
        	<div class="row">
        		<div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
        			<?= Html::label('Carteira', 'carteira'); ?>
        			<?= Html::dropDownList('carteira', null, $carteiraes, [
                            'id' => 'carteira',
			                'prompt' => 'Selecione a carteira para a importação ...',
			                'class' => 'form-control',
            			]); 
        			?>
        		</div>
    		</div>
    		<br>
        	<div class="row">
        		<div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
                	<?= $form->field($model, 'fileImport')->widget(FileInput::className(), [
        	                'pluginOptions' => [
                                'showPreview' => false,
                                'showCaption' => true,
                                'showRemove' => true,
                                'showUpload' => false,
                                'browseLabel' => 'Procurar...',
                                'removeLabel' => '',
        	                ]
                    	])->label('Arquivo Excel'); 
                	?>
            	</div>
            	<div class="col-md-6 col-sm-6 col-lg-6 col-xs-12 top25">
            		<?= Html::button('<i class="fa fa-question"></i>', [
                            'class' => Helper::BTN_COLOR_PURPLE.' btn-circle',
    		                'data-toggle' => 'modal', 
    		                'data-target' => '#modal-info-layout',
    		                'title' => 'Informações de Layout',
    		                'onClick' => '$(this).blur();',
                		]); 
            		?>
            	</div>
        	</div>
        	<!-- ./row -->
        </div>
        <!-- ./box-body -->
        <br><br><br>
        <div class="panel-footer">
        	<div class="row">
        		<div class="col-md-4 col-lg-4 col-xs-12 col-sm-4">
        			<?= Html::submitButton('<i class="fa fa-paper-plane"></i>&nbsp; Enviar', [
        			        'class' => Helper::BTN_COLOR_PRIMARY.' btn-block',
        			    ]);
        			?>
        		</div>
        	</div>
        </div>
        <!-- ./box-footer -->
    </div>
    <!-- ./box -->
<?php ActiveForm::end(); ?>
<!-- ./form -->
<div id="modal-info-layout" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
  			<div class="modal-header">
    			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    			<h4 class="modal-title"><i class="fa fa-table"></i>&nbsp; Layout de Importação</h4>
  			</div>
  			<!-- ./modal-header -->
  			<div class="modal-body">
    			<table class="table table-borderd table-info-import">
    				<thead>
    					<tr>
    						<th class="text-center">Coluna</th>
    						<th>Campo</th>
    						<th>Observação</th>
    					</tr>
    				</thead>
    				<!-- ./thead -->
    				<tbody>
    					<tr>
    						<td>1 °</td>
    						<td>CPF/CNPJ</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>2 °</td>
    						<td>Nome/Razão Social</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>3 °</td>
    						<td>N° Contrato</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>4 °</td>
    						<td>Data do Contrato</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>5 °</td>
    						<td>Plano</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>6 °</td>
    						<td>Produto</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>7 °</td>
    						<td>Obs. Contrato</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>8 °</td>
    						<td>Parcela</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>9 °</td>
    						<td>Vencimento</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>10 °</td>
    						<td>Valor</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>11 °</td>
    						<td>Encargo</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>12 °</td>
    						<td>Saldo</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>13 °</td>
    						<td>Obs. Parcela</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>14 °</td>
    						<td>Telefone Residencial</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>15 °</td>
    						<td>Telefone Comercial</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>16 °</td>
    						<td>Celular</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>17 °</td>
    						<td>Email</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>18 °</td>
    						<td>Endereço</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>19 °</td>
    						<td>Número</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>20 °</td>
    						<td>Bairro</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>21 °</td>
    						<td>CEP</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>22 °</td>
    						<td>Cidade</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>23 °</td>
    						<td>UF</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>24 °</td>
    						<td>IE_INS ?</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>25 °</td>
    						<td>Data ?</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>26 °</td>
    						<td>Nome da Mãe</td>
    						<td></td>
    					</tr>
    					<tr>
    						<td>27 °</td>
    						<td>Nome do Pai</td>
    						<td></td>
    					</tr>
    				</tbody>
    				<!-- ./tbody -->
    			</table>
  			</div>
  			<!-- ./modal-body -->
		</div>
	</div>
</div>
<!-- /.modal info layout -->

