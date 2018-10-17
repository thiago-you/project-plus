<?php 
use app\base\Helper;
use kartik\helpers\Html;
use app\models\Colaborador;
?>
<?php foreach ($acionamentos as $acionamento): ?>
	<div class="acionamento-box">
		<div class="acionamento-body">
    		<div class="row">
    			<div class="col-md-8 col-sm-8 col-lg-8 col-xs-12">
    				<p>
    					<b><?= $acionamento->titulo; ?></b>
    					<br>
    					<small><b>Tipo:</b>&nbsp; <i><?= $acionamento->getTipo(); ?></i></small>
    				</p>
    			</div>
    			<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12 text-right">
    				<small><?= Helper::dateMask($acionamento->data, Helper::DATE_DATETIME); ?></small>
    			</div>
    		</div>
    		<!-- ./row -->
    		<div class="row">
    			<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
    				<p><?= $acionamento->descricao; ?></p>
    			</div>
    		</div>
    		<!-- ./row -->
		</div>
		<!-- ./acionamento-body -->
		<div class="acionamento-footer">
			<div class="row">
				<div class="col-md-8 col-sm-8 col-lg-8 col-xs-12">
					<small><b>Usuário:</b>&nbsp; <i><?= $acionamento->colaborador->nome; ?></i></small>
    			</div>
    			<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12 text-right">
    				<?php if(\Yii::$app->user->identity->cargo == Colaborador::CARGO_ADMINISTRADOR): ?>
        				<?= Html::button('<i class="fa fa-trash"></i>', [
    			                'class' => Helper::BTN_COLOR_DANGER. ' btn-xs delete-acionamento',
    			                'data-id' => $acionamento->id,
    			                'title' => 'Excluír Acionamento',
    			                'data-toggle' => 'tooltip',
            				]);
        				?>
    				<?php endif; ?>
    			</div>
    		</div>
    		<!-- ./row -->
		</div>
		<!-- ./acionamento-footer -->
	</div>
<?php endforeach; ?>