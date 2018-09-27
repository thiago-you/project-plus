<?php
use yii\helpers\Url;
use app\base\Helper;
use app\models\Cliente;
use yii\web\JqueryAsset;
use yii\bootstrap\BootstrapAsset;

$this->title = 'Negociação';
$this->params['breadcrumbs'][] = $this->title;

/* @var $cliente app\models\Cliente */
/* @var $contrato app\models\Contrato */
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
    	<h2 class="font18"><?= $cliente->nome; ?></h2>
    </div>
    <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
    	<small>
        	<?php if (strlen($cliente->documento) == 11) {
        	       echo '<b>CPF:</b>&nbsp; '.Helper::mask($cliente->documento, Helper::MASK_CPF);
            	} else {            	    
            	    echo '<b>CNPJ:</b>&nbsp; '.Helper::mask($cliente->documento, Helper::MASK_CNPJ); 
            	}
        	?>
    	</small>
    </div>
    <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
    	<small><b>Data de Nasc.:</b>&nbsp; <?= Helper::formatDateToDisplay($cliente->data_nascimento, Helper::DATE_DEFAULT); ?></small>
    </div>
    <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
    	<small><b>Endereço:</b>&nbsp; <?= $cliente->getEnderecoCompleto(); ?></small>
    </div>
</div>
<!-- ./dados do cliente -->
<div class="row">
    <div class="col-md-7 col-sm-7 col-lg-7 col-xs-12">
        <div class="panel panel-primary panel-box panel-dados-cliente">
        	<div class="panel-body">
        		<div class="nav-tabs-custom">
            		<ul class="nav nav-tabs">
    					<li class="active"><a href="#tab-telefone" data-toggle="tab">Telefones</a></li>
    					<li><a href="#tab-endereco" data-toggle="tab">Endereços</a></li>
    					<li><a href="#tab-email" data-toggle="tab">Emails</a></li>
    					<li><a href="#tab-dados" data-toggle="tab">Dados</a></li>
    				</ul>
    			</div>
    			<div class="tab-content">
    				<div class="tab-pane active" id="tab-telefone">
    					<table id="table-telefones" class="table table-bordered table-hover">
    			  			<thead>
    			  				<tr>
    			  					<th>Telefone</th>
    			  					<th>Tipo</th>
    			  					<th>Observação</th>
    			  					<th></th>
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
    					  					<td class="text-center">
    					  					</td>
    					  				</tr>
    				  				<?php endforeach; ?>
				  				<?php endif; ?>
    			  			</tbody>
    			  		</table>
    			  		<!-- ./table telefone -->
    				</div>
    				<!-- ./tab telefone -->
    				<div class="tab-pane" id="tab-endereco">
    					<table id="table-enderecos" class="table table-bordered table-hover">
    			  			<thead>
    			  				<tr>
    			  					<th>Endereço</th>
    			  					<th width="10%"></th>
    			  				</tr>
    			  			</thead>
    			  			<tbody>
    			  				<?php if (is_array($cliente->enderecos) && !empty($cliente->enderecos)): ?>
    			  					<?php foreach ($cliente->enderecos as $endereco): ?>
    				  					<tr>
    					  					<td>
    					  						<?= $endereco->getEnderecoCompleto(); ?>
    				  						</td>
    					  					<td class="text-center">
    					  					</td>
    					  				</tr>
    				  				<?php endforeach; ?>
    			  				<?php endif; ?>
    			  			</tbody>
    			  		</table>
    			  		<!-- ./table telefone -->
    				</div>
    				<!-- ./tab endereco -->
    				<div class="tab-pane" id="tab-email">
    					<table id="table-emails" class="table table-bordered table-hover">
    			  			<thead>
    			  				<tr>
    			  					<th>Email</th>
    			  					<th>Observação</th>
    			  					<th></th>
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
    					  					<td class="text-center">
    					  					</td>
    					  				</tr>
    				  				<?php endforeach; ?>
    			  				<?php endif; ?>
    			  			</tbody>
    			  		</table>
    			  		<!-- ./table email -->
    				</div>
    				<!-- ./tab email -->
    				<div class="tab-pane" id="tab-dados">
        				<table id="table-dados" class="table table-bordered table-hover">
    			  			<tbody>
    		  					<tr>
    			  					<td>RG</td>
    			  					<td><?= $cliente->rg; ?></td>
    			  				</tr>
    		  					<tr>
    			  					<td>Data de Nascimento</td>
    			  					<td><?= Helper::formatDateToDisplay($cliente->data_nascimento, Helper::DATE_DEFAULT); ?></td>
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
    			  		<!-- ./table dados -->
    				</div>
    				<!-- ./tab dados -->
				</div>
    			<!-- ./tabs -->
            </div>
            <!-- ./panel-body - dados do cliente -->
    	</div>
    	<!-- ./panel dados do cliente-->
    	<div class="panel panel-primary panel-box panel-contratos">
    		<div class="panel-heading">
    			<div class="row">
                	<div class="col-md-4 col-sm-4 col-lg-4 col-xs-4">
                		<h3>Contratos</h3>
                	</div>
                	<div class="col-md-8 col-sm-8 col-lg-8 col-xs-8 text-right">
                		<h3 class="text-danger">Total em Aberto: <?= Helper::mask($totalContratos, Helper::MASK_MONEY); ?></h3>
                	</div>
            	</div>
            	<!-- ./row -->
            </div>
            <!-- ./panel heading -->
        	<div class="panel-body">
            	<?php if (is_array($contratos) && !empty($contratos)): ?>
  					<?php foreach ($contratos as $clienteContrato): ?>
  						<div class="row">
  							<div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
  								<i class="fa fa-file-invoice-dollar"></i>
  								<?= $clienteContrato->num_contrato ? $clienteContrato->num_contrato : 'Sem Número'; ?>
							</div>
							<!-- ./num do contrato -->
							<div class="col-md-6 col-sm-6 col-lg-6 col-xs-6 text-right">
								<?= Helper::mask($clienteContrato->getValorTotal(), Helper::MASK_MONEY); ?>
							</div>
							<!-- ./valor do contrato -->
    		  				<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
        		  				<?php if (is_array($clienteContrato->contratoParcelas) && !empty($clienteContrato->contratoParcelas)): ?>
        		  					<?php $numParcelas = count($clienteContrato->contratoParcelas); ?>
                                	<table class="table table-bordered table-hover table-parcelas">
                    		  			<thead>
                    		  				<tr>
                    		  					<th>Núm.</th>
                    		  					<th>Vencimento</th>
                    		  					<th>Valor</th>
                    		  					<th>Atraso</th>
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
                                                        <?= Helper::formatDateToDisplay($parcela->data_vencimento, Helper::DATE_DEFAULT); ?>
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
	<!-- ./contrato -->
	<div class="col-md-5 col-sm-5 col-lg-5 col-xs-12">
		<div class="panel panel-primary panel-box">
        	<div class="panel-heading">
        	</div>
        	<!-- ./panel heading -->
        	<div class="panel-body">
            </div>
            <!-- ./panel-body -->
    	</div>
    	<!-- ./panel -->
	</div>
	<!-- ./ acionamentos -->
</div>
<!-- ./row -->
<div class="row">
    <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
        <div class="panel panel-primary panel-box panel-calculo">
        	<div class="panel-body">
        		<?= $this->render('contrato-calculo', [
                        'contrato' => $contrato,
	                    'negociacao' => $negociacao,
            		]); 
        		?>
        	</div>
    	</div>
	</div>
</div>
<!-- ./row calculo -->
<?php 
// CSS
$this->registerCssFile(Url::home().'app/css/negociacao.css', ['depends' => [BootstrapAsset::className()]]);
// JS
$this->registerJsFile(Url::home().'app/js/negociacao.js', ['depends' => [JqueryAsset::className()]]);
?>




