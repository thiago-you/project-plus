<?php
use yii\helpers\Url;
use app\base\Helper;
use app\models\Cliente;
use yii\web\JqueryAsset;
use kartik\helpers\Html;
use yii\bootstrap\Modal;
use kartik\select2\Select2;
use app\models\Acionamento;
use app\models\Colaborador;
use yii\helpers\ArrayHelper;
use yii\bootstrap\BootstrapAsset;
use kartik\datetime\DateTimePicker;

$this->title = 'Negociação';
$this->params['breadcrumbs'][] = $this->title;

/* @var $cliente app\models\Cliente */
/* @var $contrato app\models\Contrato */
?>
<input id="id-contrato" class="hidden" value="<?= $contrato->id; ?>"/>
<!-- ./hidden id contrato -->
<div class="row">
	<div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            	<h2 class="font16 text-primary">
            		<span id="id-cliente" data-id="<?= $cliente->id; ?>"><?= $cliente->id; ?></span>
            		&nbsp;-&nbsp;
            		<?= $cliente->nome; ?>
            	</h2>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
            	<small>
                	<?php if (strlen($cliente->documento) == 11) {
                	       echo '<b>CPF:</b>&nbsp; '.Helper::mask($cliente->documento, Helper::MASK_CPF);
                    	} else {            	    
                    	    echo '<b>CNPJ:</b>&nbsp; '.Helper::mask($cliente->documento, Helper::MASK_CNPJ); 
                    	}
                	?>
            	</small>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
            	<small><b>Data de Nasc.:</b>&nbsp; <?= Helper::dateMask($cliente->data_nascimento, Helper::DATE_DEFAULT); ?></small>
            </div>
            <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            	<small><b>Endereço:</b>&nbsp; <?= $cliente->getEnderecoCompleto(); ?></small>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
    	<div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            	<h2 class="font16 text-primary">
            		<?= "Carteira {$contrato->carteira->id} - {$contrato->carteira->nome}"; ?>
            	</h2>
            </div>
            <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
            	<small>
                	<b>CNPJ:</b>&nbsp; <?= Helper::mask($contrato->carteira->cnpj, Helper::MASK_CNPJ); ?>
            	</small>
            </div>
            <div class="col-md-8 col-sm-8 col-lg-8 col-xs-12">
            	<small>
                	<b>Email:</b>&nbsp; <?= $contrato->carteira->email; ?>
            	</small>
            </div>
        </div>
    </div>
</div>
<!-- ./dados do cliente -->
<div class="row">
    <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
        <div class="panel panel-primary panel-dados-cliente">
        	<div class="panel-body">
    			<div class="row">
    				<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
    					<table id="table-telefones" class="table table-bordered table-hover">
    			  			<thead>
    			  				<tr>
    			  					<th>Telefone</th>
    			  					<th>Tipo</th>
    			  					<th>Observação</th>
    			  				</tr>
    			  			</thead>
    			  			<tbody>
    			  				<?php if (is_array($cliente->telefones) && !empty($cliente->telefones)): ?>
    			  					<?php foreach ($cliente->telefones as $telefone): ?>
    				  					<tr>
    					  					<td>
    					  						<?= Helper::mask($telefone->numero, Helper::MASK_TELEFONE); ?>
    				  						</td>
    					  					<td>
    					  						<?= $telefone->getTipoDescricao(); ?>
    				  						</td>
    					  					<td>
    					  						<?= $telefone->observacao; ?>
    					  					</td>
    					  				</tr>
    				  				<?php endforeach; ?>
				  				<?php endif; ?>
    			  			</tbody>
    			  		</table>
			  		</div>
			  		<!-- ./table telefone -->
			  		<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
    					<table id="table-emails" class="table table-bordered table-hover">
    			  			<thead>
    			  				<tr>
    			  					<th>Email</th>
    			  					<th>Observação</th>
    			  				</tr>
    			  			</thead>
    			  			<tbody>
    			  				<?php if (is_array($cliente->emails) && !empty($cliente->emails)): ?>
    			  					<?php foreach ($cliente->emails as $email): ?>
    				  					<tr>
    					  					<td>
                                                <?= $email->email; ?>
    				  						</td>
    					  					<td>
    					  						<?= $email->observacao; ?>
    					  					</td>
    					  				</tr>
    				  				<?php endforeach; ?>
    			  				<?php endif; ?>
    			  			</tbody>
    			  		</table>
			  		</div>
			  		<!-- ./table email -->
			  		<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
    					<table id="table-enderecos" class="table table-bordered table-hover">
    			  			<thead>
    			  				<tr>
    			  					<th>Endereço</th>
    			  				</tr>
    			  			</thead>
    			  			<tbody>
    			  				<?php if (is_array($cliente->enderecos) && !empty($cliente->enderecos)): ?>
    			  					<?php foreach ($cliente->enderecos as $endereco): ?>
    				  					<tr>
    					  					<td>
    					  						<?= $endereco->getEnderecoCompleto(); ?>
    				  						</td>
    					  				</tr>
    				  				<?php endforeach; ?>
    			  				<?php endif; ?>
    			  			</tbody>
    			  		</table>
			  		</div>
			  		<!-- ./table telefone -->
			  		<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
        				<table id="table-dados" class="table table-bordered table-hover">
        					<thead>
    			  				<tr>
    			  					<th colspan="2">Outros Dados</th>
    			  				</tr>
    			  			</thead>
    			  			<tbody>
    		  					<tr>
    			  					<td>RG</td>
    			  					<td><?= $cliente->rg; ?></td>
    			  				</tr>
    		  					<tr>
    			  					<td>Data de Nascimento</td>
    			  					<td><?= Helper::dateMask($cliente->data_nascimento, Helper::DATE_DEFAULT); ?></td>
    			  				</tr>
    		  					<tr>
    			  					<td>Nome do Pai</td>
    			  					<td><?= $cliente->nome_pai; ?></td>
    			  				</tr>
    		  					<tr>
    			  					<td>Nome da Mãe</td>
    			  					<td><?= $cliente->nome_mae; ?></td>
    			  				</tr>
    			  				<tr>
    			  					<td>Nome do Conjuge</td>
    			  					<td><?= $cliente->nome_conjuge; ?></td>
    			  				</tr>
    		  					<tr>
    			  					<td>Profissão</td>
    			  					<td><?= $cliente->profissao; ?></td>
    			  				</tr>
    		  					<tr>
    			  					<td>Empresa</td>
    			  					<td><?= $cliente->empresa; ?></td>
    			  				</tr>
    		  					<tr>
    			  					<td>Salário</td>
    			  					<td><?= Helper::mask($cliente->salario, Helper::MASK_MONEY); ?></td>
    			  				</tr>
    		  					<tr>
    			  					<td>Sexo</td>
    			  					<td><?= $cliente->sexo == Cliente::SEXO_MASC ? 'Masculino' : 'Feminino'; ?></td>
    			  				</tr>
    			  				<tr>
    			  					<td>Estado Civil</td>
    			  					<td><?= $cliente->getEstadoCivilDescricao(); ?></td>
    			  				</tr>
    			  			</tbody>
    			  		</table>
			  		</div>
			  		<!-- ./table dados -->
		  		</div>
		  		<!-- ./row -->
            </div>
            <!-- ./panel-body - dados do cliente -->
    	</div>
    	<!-- ./panel dados do cliente-->
	</div>
	<!-- ./col -->
</div>
<!-- ./row dados -->
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
		<div class="panel panel-primary panel-contratos">
    		<div class="panel-heading">
    			<div class="row">
                	<div class="col-md-4 col-sm-4 col-lg-4 col-xs-4">
                		<h3>Contratos</h3>
                	</div>
                	<div class="col-md-8 col-sm-8 col-lg-8 col-xs-8 text-right">
                		<h3>Total em Aberto: <?= Helper::mask($totalContratos, Helper::MASK_MONEY); ?></h3>
                	</div>
            	</div>
            	<!-- ./row -->
            </div>
            <!-- ./panel heading -->
        	<div class="panel-body">
            	<?php if (is_array($contratos) && !empty($contratos)): ?>
  					<?php foreach ($contratos as $clienteContrato): ?>
  						<div class="row">
  							<div class="contratos-collapse" data-toggle="collapse" data-target="#contrato-<?= $clienteContrato->id; ?>">
  								<div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
      								<i class="fa fa-file-invoice-dollar"></i>&nbsp;
      								N° <?= $clienteContrato->num_contrato ? $clienteContrato->num_contrato : 'Sem Número'; ?>
    							</div>
    							<!-- ./num do contrato -->
    							<div class="col-md-6 col-sm-6 col-lg-6 col-xs-6 text-right">
    								<?= Helper::mask($clienteContrato->getValorTotal(), Helper::MASK_MONEY); ?>
    							</div>
    							<!-- ./valor do contrato -->
  							</div>
  							<!-- ./colpase bar -->
    		  				<div id="contrato-<?= $clienteContrato->id; ?>" class="collapse col-md-12 col-sm-12 col-lg-12 col-xs-12 <?= $clienteContrato->id == $contrato->id ? 'in' : ''; ?>">
        		  				<br>
        		  				<?php if (is_array($clienteContrato->contratoParcelas) && !empty($clienteContrato->contratoParcelas)): ?>
        		  					<?php $numParcelas = count($clienteContrato->contratoParcelas); ?>
        		  					
                                	<?= Html::a('<i class="fa fa-file-signature"></i>&nbsp; Abrir Contrato', Url::to(['negociacao', 'id' => $clienteContrato->id]), [
                        	                'class' => Helper::BTN_COLOR_PURPLE.' btn-sm pull-right',
                    	                    'target' => '_self',
                                    	]);
                                	?>
        		  					<!-- ./row -->
                                	<table class="table table-bordered table-hover table-parcelas">
                    		  			<thead>
                    		  				<tr>
                    		  					<th>Núm.</th>
                    		  					<th>Vencimento</th>
                    		  					<th>Valor</th>
                    		  					<th>Atraso (dias)</th>
                    		  					<th>Status</th>
                    		  					<th>Observação</th>
                    		  				</tr>
                    		  			</thead>
                    		  			<tbody>
        		  							<?php foreach ($clienteContrato->contratoParcelas as $parcela): ?>
                			  					<tr>
                				  					<td class="text-center">
                                                        <?= "{$parcela->num_parcela}/{$numParcelas}"; ?>
                			  						</td>
                			  						<td>
                                                        <?= Helper::dateMask($parcela->data_vencimento, Helper::DATE_DEFAULT); ?>
                			  						</td>
                			  						<td>
                                                        <?= Helper::mask($parcela->valor, Helper::MASK_MONEY); ?>
                			  						</td>
                			  						<td>
                                                        <?= $parcela->getAtraso(); ?>
                			  						</td>
                			  						<td class="text-center">
                                                        <?= $parcela->getStatusDescricao(); ?>
                			  						</td>
                			  						<td>
                                                        <?= $parcela->observacao; ?>
                			  						</td>
                				  				</tr>
                			  				<?php endforeach; ?>
                		  				<?php endif; ?>
                		  			</tbody>
                		  		</table>
                	  		   <!-- ./table contratos -->
    		  				</div>
    		  				<!-- ./col -->
		  				</div>
		  				<!-- ./row -->
					<?php endforeach; ?>
				<?php endif; ?>
        	</div>
        	<!-- ./panel body -->
    	</div>
    	<!-- ./panel contratos -->
	</div>
	<!-- ./col -->
</div>
<!-- ./row contratos -->
<div class="row">
    <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
        <div class="panel panel-primary panel-calculo">
        	<div class="panel-body">
        		<?= $this->render('/negociacao/negociacao', [
                        'contrato' => $contrato,
	                    'negociacao' => $negociacao,
            		]); 
        		?>
        	</div>
    	</div>
	</div>
</div>
<!-- ./row calculo -->
<div class="row">
	<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
		<div class="panel panel-primary panel-acionamento">
        	<div class="panel-heading">
        		<div class="row">
        			<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
        				<?= Html::button('<i class="fa fa-plus"></i>&nbsp; Acionamento', [
				                'id' => 'add-acionamento',
				                'class' => Helper::BTN_COLOR_EMERALD.' btn-block',
				                'title' => 'Novo acionamento',
				                'data-toggle' => 'tooltip',
            				]); 
        				?>
        			</div>
        		</div>
        	</div>
        	<!-- ./panel heading -->
        	<div class="panel-body">
        		<hr>
        		<?= $this->renderAjax('/acionamento/index', [
		                'acionamentos' => $acionamentos,
            		]);
        		?>
            </div>
            <!-- ./panel-body -->
    	</div>
    	<!-- ./panel -->
	</div>
</div>	
<!-- ./ row acionamentos -->
<?php Modal::begin([
    'id' => 'modal-acionamento',
    'header' => '<h4 class="modal-title text-primary"><i class="fa fa-plus"></i>&nbsp; Acionamento</h4>',
]); ?>
    <div class="modal-body">
    	<div class="row">
    		<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
    			<div class="form-group"> 	
        			<?= Html::label('Acionamento', 'acionamento-tipo'); ?>
        			<?= Select2::widget([
                            'name' => 'acionamento-tipo',
        	                'id' => 'acionamento-tipo',
        	                'data' => Acionamento::getTipos(),
            			]); 
        			?>
    			</div>
    		</div>
  		</div>
  		<!-- ./row -->
  		<div class="row">
    		<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
        		<div class="form-group"> 	
    				<?= Html::label('Título', 'acionamento-titulo'); ?>
        			<?= Html::textInput('acionamento-titulo', '', [
        	                'id' => 'acionamento-titulo',
                            'class' => 'form-control',
            			]); 
        			?>
    			</div>
    		</div>
    	</div>
    	<!-- ./row -->
  		<div class="row">
    		<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
        		<div class="form-group"> 	
    				<?= Html::label('Descrição', 'acionamento-desc'); ?>
        			<?= Html::textarea('acionamento-desc', '', [
        	                'id' => 'acionamento-desc',
                            'class' => 'form-control',
			                'rows' => 4, 
			                'style' => ['resize' => 'none'],
            			]); 
        			?>
    			</div>
    		</div>
    	</div>
    	<!-- ./row -->
    	<hr>	
		<div class="row">
			<div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
				<?= Html::label('Colaborador do Agendamento', 'acionamento-colab'); ?>
    			<?= Select2::widget([
		                'data' => ArrayHelper::map(Colaborador::find()->where(['!=', 'cargo', Colaborador::CARGO_CLIENTE])->all(), 'id', 'nome'),
                        'name' => 'acionamento-colab',
    	                'id' => 'acionamento-colab',
		                'pluginOptions' => [
                            'allowClear' => true
		                ],
		                'options' => [
                            'class' => 'form-control',
                            'placeholder' => 'Selecione ...',
		                ],
        			]); 
    			?>
			</div>
  			<div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
    			<div class="form-group"> 	
        			<?= Html::label('Data do Agendamento', 'acionamento-data-agendamento'); ?>
        			<?= DateTimePicker::widget([
                            'name' => 'acionamento-data-agendmaneto',
        	                'id' => 'acionamento-data-agendamento',
			                'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
			                'removeButton' => ['title' => 'Remover Data', 'data-toggle' => 'tooltip'],
    		                'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd/mm/yyyy hh:ii',
                                'orientation' => 'bottom',
    		                ],
            			]); 
        			?>
    			</div>
    		</div>
  		</div>
  		<!-- ./row -->
    </div>
    <!-- ./modal-body -->
    <div class="modal-footer">
    	<div class="row">
    		<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
    			<?= Html::button(Helper::BTN_CREATE, [
		                'id' => 'save-acionamento',
		                'class' => Helper::BTN_COLOR_EMERALD.' btn-block',
        			]); 
    			?>
    		</div>
    		<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12 pull-right">
    			<?= Html::button(Helper::BTN_CANCEL, [
		                'data-dismiss' => 'modal',
		                'class' => Helper::BTN_COLOR_DEFAULT.' btn-block',
        			]); 
    			?>
    		</div>
    	</div>
    	<!-- ./row -->
    </div>
<?php Modal::end(); ?>
<?php 
// PLUGIN (evita bug ao carregar conteudo por ajax)
$this->registerJsFile(Url::home().'plugins/jquery-maskmoney/jquery.maskMoney.min.js', ['depends' => [JqueryAsset::className()]]);
// CSS
$this->registerCssFile(Url::home().'app/css/negociacao.css', ['depends' => [BootstrapAsset::className()]]);
// JS
$this->registerJsFile(Url::home().'app/js/negociacao.js?d=201810302229', ['depends' => [JqueryAsset::className()]]);
?>




