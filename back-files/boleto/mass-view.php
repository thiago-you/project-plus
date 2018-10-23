<?php
use app\base\Util;
use yii\helpers\Html;
use app\models\BoletoOcorrencia;

$this->title = "Gerar Boletos Receita: {$model->id}";
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-default">
	<div class="box-header">
		<h4 class="text-info"><i class="fa fa-info-circle"></i>&nbsp; Verifique o resultado da geração de boletos e resolva as pendências se houver</h4>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Nº da Parcela</th>
					<th>Situação do Boleto</th>
					<th>Ocorrências</th>
					<th class="text-center"></th>
				</tr>
			</thead>
			<tbody>
            	<?php foreach ($boletos as $boleto): ?>
                	<?php  
                       // verifica se houve ocorrencias para este boleto
            	       $ocorrencias = BoletoOcorrencia::find()->where(['boleto_id' => $boleto->id, 'situacao' => BoletoOcorrencia::SITUACAO_PENDENTE])->all();
                	?>
        			<?php if (count($ocorrencias) > 0): ?>
            	    	<tr class="danger">
                   			<td width="10%">
               					<b class="text-info text-center"><?= $boleto->receitaParcela->parcela_num ?></b>
        	           		</td>
        	           		<td width="10%">
        	           			<span class="label label-warning text-center" style="width: 100%">PENDÊNCIAS</span>
        	           		</td>
        	           		<td>	
        	           			<table class="table table-condensed">
        	           				<thead>
        	           					<tr>
        	           						<th class="text-center">Cód. do Erro</th>
        	           						<th>Descrição do Erro</th>
        	           						<th>Data-Hora</th>
        	           					</tr>
        	           				</thead>
        	           				<tbody>
        								<?php foreach ($ocorrencias as $ocorrencia) : ?>
        									<tr>
        										<td><?= $ocorrencia->codigo ?></td>
        										<td><?= $ocorrencia->descricao ?></td>
        										<td><?= $ocorrencia->data_ocorrencia ?></td>
        									</tr>
        								<?php endforeach; ?>   	           				
        	           				</tbody>	
        	           			</table>
        	           		</td>	
        	           		<td class="text-center">
        	           			<?= Html::a('<i class="fa fa-pencil-alt"></i>', ['/boleto/update', 'id' => $boleto->id], [
                            	        'class' => 'btn btn-sm btn-warning',
                            	        'style' => 'margin-left: 3px',
                            	        'title' => 'Clique aqui para atualizar',
                            	        'data-toggle'=>'tooltip',
                                    ]);
        	           			?>			 	
        	           		</td>
        	           </tr>
        			<?php else: ?>
        				<tr class="success">	  
        					<td width="10%">
        	           			<b class="text-info"><?= $boleto->receitaParcela->parcela_num; ?></b>
        	           		</td>
        	           		<td width="10%" class="text-center">
        	           			<span class="label label-info text-center" style="width: 100%">EMITIDO</span>
        	           		</td>
        	           		<td>
        	           			-- Sem Ocorrências --
        	           		</td>
        	           		<td class="text-center">
        	           			<?= Html::a('<i class="fa fa-eye"></i>', ['/boleto/view', 'id' => $boleto->id], [
                        		        'target' => '_blank',
                        				'class' => Util::BTN_COLOR_INFO.' btn-sm',
                        				'style' => 'margin-left: 3px',
                        				'title' => 'Visualizar Boleto',
                        				'data-toggle' => 'tooltip',
                                	]);
        	           			?>
        	           		</td>
        	           </tr>
        	 		<?php endif; ?>
    			<?php endforeach; ?>
			</tbody>
			<!-- ./tbody -->
		</table>
		<!-- ./table -->
	</div>
	<!-- ./box-body -->
	<div class="box-footer">
		<div class="row">
			<div class="col-md-4 col-sm-4 col-xs-12 col-lg-4 pull-right">
				<?= Html::a(Util::BTN_RETURN, ['../../financeiro/receita/index'], ['class' => Util::BTN_COLOR_DEFAULT.' btn-block']); ?>
			</div>
		</div>
	</div>
	<!-- ./box-footer -->
</div>
<!-- ./box -->

