<?php
use yii\helpers\Html;
use app\base\Util;

$this->title = 'Credor Calculos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credor-calculo-index">
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>Atraso</th>
				<th>Multa</th>
				<th>Juros</th>
				<th class="text-center"></th>
			</tr>
		</thead>
		<!-- ./thead -->
		<tbody>
			<?php if (!empty($model) && is_array($model)): ?>
        		<?php foreach($model as $calculo): ?>
        			<tr id="<?= $calculo->id; ?>">
        				<td><?= $calculo->getAtraso(); ?></td>
        				<td><?= Util::mask($calculo->multa, Util::MASK_MONEY); ?></td>
        				<td><?= Util::mask($calculo->juros, Util::MASK_MONEY); ?></td>
        				<td class="text-center">
        					<?= Html::button('<i class="fa fa-edit fa-fw"></i>', [
        			                'class' => Util::BTN_COLOR_WARNING.' editar-faixa'
        	  				   ]); 
        	  				?>
        					<?= Html::button('<i class="fa fa-times fa-fw"></i>', [ 
        			                'class' => Util::BTN_COLOR_DANGER.' excluir-faixa'
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
