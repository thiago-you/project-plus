<?php
use yii\helpers\Html;
use app\base\Helper;

$this->title = 'Credor Calculos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credor-calculo-index">
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th width="50px" class="text-center">#</th>
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
        				<td class="text-center"><?= ++$i; ?></td>
    					<td><?= $calculo->getAtraso(); ?></td>
        				<td><?= Helper::mask($calculo->multa, Helper::MASK_MONEY); ?></td>
        				<td><?= Helper::mask($calculo->juros, Helper::MASK_MONEY); ?></td>
        				<td class="text-center">
        					<?= Html::button('<i class="fa fa-edit fa-fw"></i>', [
        			                'class' => Helper::BTN_COLOR_WARNING.' editar-faixa'
        	  				   ]); 
        	  				?>
        					<?= Html::button('<i class="fa fa-times fa-fw"></i>', [ 
        			                'class' => Helper::BTN_COLOR_DANGER.' excluir-faixa'
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
