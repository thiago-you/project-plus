<?php

use yii\widgets\ActiveForm;

use app\base\Util;
use app\modules\financeiro\models\Receita;

$this->title = 'Extrato de Movimentações Bancárias';
?>

<?php $form = ActiveForm::begin(); ?>
<div id="Extrato" class="movimento-bancario-index">
	<div class="modal-header">
    	<h4 class="modal-title text-primary"><i class="fa fa-search"></i>Consulta de Compras do Cliente</h4>
    </div>
	<div id="div_mod">
		<table class="table table-bordered table-striped">
        	<thead>
            	<tr>
                	<th style="width:30px;">Parcela</th>
                    <th style="width:50px;">Vencimento</th>
                    <th style="width:50px;">Dias</th>
                    <th style="width:80px;">Vlr Fatura</th>
                    <th style="width:80px;">Vlr Parcela</th>
                    <th style="width:80px;">Vlr Juros</th>
                    <th style="width:80px;">Vlr Desconto</th>
                    <th style="width:80px;">Vlr a Pagar</th>
                    <th style="width:40px;">Situação</th>
                </tr>
             </thead>
             
            <tbody>
    			<?php 
    			
    			if (count($modelsFaturas)) {
    				$tot_valor_fatura   = 0;
    				$tot_valor_juros    = 0;
    				$tot_valor_desconto = 0;
    				$tot_valor_pago     = 0;
    				$tot_valor_pagar    = 0;
    				
    			?>                                                
            
            	<?php foreach ($modelsFaturas as $i => $modelFaturas) { ?>
                    <tr class="item">
                       	<?php
                       	        if ($modelFaturas->situacao > 0) continue;
                        	
                        	    $modelReceber = new Receita();
        						$nr_dias = $modelReceber->verifica_dias($modelFaturas->data_vencimento,$modelFaturas->valor_parcela);
        						
        						$tot_valor_fatura += Util::maskBackend($modelFaturas->valor_parcela, Util::MASK_MONEY);
        						$tot_valor_juros += Util::maskBackend($modelFaturas->valor_juros, Util::MASK_MONEY);
        						$tot_valor_desconto += Util::maskBackend($modelFaturas->valor_desconto, Util::MASK_MONEY);
       							$tot_valor_pagar += Util::maskBackend($modelFaturas->valor_pago, Util::MASK_MONEY);
        						
        						
        						
        						if ($modelFaturas->situacao == 0) {
        							$situacao = 'Aberto';
        						}
        						if ($modelFaturas->situacao == 1) {
        							$situacao = 'Pago';
        						}
        						if ($modelFaturas->situacao == 2) {
        							$situacao = 'Cancelado';
        						}
       					?>
                        <td><?= $modelFaturas->nr_parcela ?></td>
                        <td class="text-center"><?=$modelFaturas->data_vencimento ?></td>
                        <td class="text-right"><?=$nr_dias ?></td>
                        <td class="text-right"><?=$modelFaturas->valor_fatura?></td>
                        <td class="text-right"><?=$modelFaturas->valor_parcela?></td>
                        <td class="text-right"><?=$modelFaturas->valor_juros ?></td>
                        <td class="text-right"><?=$modelFaturas->valor_desconto ?></td>
                       	<td class="text-right"><?= $modelFaturas->valor_pago ?></td>
                        <td class="text-center"><?=$situacao ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tbody>
                    <tr class="item">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="text-right"><?= Util::maskBackend($tot_valor_fatura, Util::MASK_MONEY); ?></td>
                        <td class="text-right"><?= Util::maskBackend($tot_valor_juros, Util::MASK_MONEY); ?></td>
                        <td class="text-right"><?= Util::maskBackend($tot_valor_desconto, Util::MASK_MONEY); ?></td>
                       	<td class="text-right"><?= Util::maskBackend($tot_valor_pagar, Util::MASK_MONEY); ?></td>
                        <td>&nbsp;</td>
                    </tr>
            </tbody>
      <?php } ?>
             
             
        </table>
	</div>
</div>
<?php ActiveForm::end(); ?>       
                    