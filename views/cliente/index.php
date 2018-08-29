<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\form\ActiveForm;
use yii\helpers\Url;

$this->title = 'Clientes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-primary panel-box">
	<div class="panel-body">
		<?= $this->render('_filter.php'); ?>
	</div>
	<div class="panel-body">
        <div class="row">
            <div class="col-md-4">
            	<?php $form = ActiveForm::begin(['action' => Url::to('cliente/upload-excel'), 'options' => ['enctype' => 'multipart/form-data']]) ?>
	            	<?= $form->field($modelImport, 'fileImport')->fileInput(); ?>
	            	<button type="submit">Enviar</button>
	            <?php ActiveForm::end(); ?>
	        </div>
            <div class="col-md-4 pull-right text-right">
	            <?= Html::a('<i class="fa fa-plus"></i>&nbsp; Cliente', ['cadastrar'], [
	                    'class' => 'btn btn-emerald btn-flat',
	                    'title' => 'Cadastrar Novo Cliente',
	                    'data-toggle' => 'tooltip',
	                ]);
	            ?>
	        </div>
        </div>
		<br>
		<?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => [
                   'id',
                   'nome',
                   'telefone',
                   'apelido',
                   'documento',
                   'sexo',
                   'data_nascimento',
                   'cep',
                   'tipo',
                   'situacao',
                   [
                       'class' => 'yii\grid\ActionColumn',
                       'template' => '{update}{delete}',
                   ],
                ],
                'responsive' => false,
                'hover' => true
            ]);
       ?>
    </div>
</div>
