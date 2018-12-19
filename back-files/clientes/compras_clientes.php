<?php

use app\base\Util;
use yii\widgets\ActiveForm;

$this->title = 'Consulta de Compras';
?>

<?php $form = ActiveForm::begin(); ?>
<div id="Extrato" class="movimento-bancario-index">
	<div class="modal-header">
    	<h4 class="modal-title text-primary"><i class="fa fa-search"></i>Consulta Compras do Cliente</h4>
    </div>
	<div id="div_mod">
		<table class="table table-bordered table-striped">
        	<thead>
            	<tr>
                	<th style="width:30px;">Fatura</th>
                    <th style="width:50px;">Emissão</th>
                    <th style="width:50px;">Documento</th>
                    <th style="width:50px;">Pedido</th>
                    <th style="width:80px;">Cobrança</th>
                    <th style="width:80px;">Vlr Fatura</th>
                    <th style="width:40px;">Parcelas</th>
                    <th style="width:80px;">Local Pagto</th>
                    <th style="width:40px;">Situação</th>
                </tr>
             </thead>
             
             <tbody>
			<?php 
														
		   	if (count($modelsCompras)) {
		   		$tot_valor_fatura   = 0;
			?>                                                
	                                                
	       	<?php foreach ($modelsCompras as $i => $modelCompras) { ?>
	               <tr class="item">
	                  	<?php
	   						$tot_valor_fatura += Util::maskBackend($modelCompras->valor_fatura, Util::MASK_MONEY);
	   						
	   						if ($modelCompras->situacao_baixa == 0) {
	   							$situacao = 'Aberto';
	   						}
	   						if ($modelCompras->situacao_baixa == 1) {
	   							$situacao = 'Pago';
	   						}
	   						if ($modelCompras->situacao_baixa == 2) {
	   							$situacao = 'Cancelado';
	   						}
	   						
	   						if ($modelCompras->forma_pagamento == 1) {
	   							$cobranca = 'CHP';
	   						}
	   						if ($modelCompras->forma_pagamento == 2) {
	   							$cobranca = 'BOL';
	   						}
	   						if ($modelCompras->forma_pagamento == 3) {
	   							$cobranca = 'NTP';
	   						}
	   						if ($modelCompras->forma_pagamento == 4) {
	   							$cobranca = 'OUT';
	   						}
	   						
	   						if ($modelCompras->local_pagamento == 1) {
	   							$local = 'Carteira';
	   						}
	   						if ($modelCompras->local_pagamento == 2) {
	   							$local = 'Banco';
	   						}
	   						
	   						
	   				?>
	                   <td><?= $modelCompras->id_fatura ?></td>
	                   <td class="text-center"><?=$modelCompras->data_emissao ?></td>
	                   <td class="text-right"><?=$modelCompras->nr_documento?></td>
	                   <td class="text-right"><?=$modelCompras->nr_pedido?></td>
	                   <td class="text-right"><?=$cobranca ?></td>
	                  	<td class="text-right"><?= $modelCompras->valor_fatura ?></td>
	                  	<td class="text-right"><?= $modelCompras->qtde_parcela ?></td>
	                  	<td class="text-center"><?=$local?></td>
	                   <td class="text-center"><?=$situacao ?></td>
	               </tr>
	           <?php } ?>
	       </tbody>
	       <tbody>
	               <tr class="item">
	                   <td colspan='5'>&nbsp;</td>
	                   <td class="text-right">R$ <?= number_format($tot_valor_fatura, 2, ',', '.'); ?></td>
	                   <td colspan='3'>&nbsp;</td>
	               </tr>
	       </tbody>
	 <?php } ?>
        </table>
	</div>
</div>
<?php ActiveForm::end(); ?>       
                    