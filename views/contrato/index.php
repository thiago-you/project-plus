<?php
use app\base\Util;
use yii\helpers\Html;
use app\models\Cliente;
use kartik\grid\GridView;

$this->title = 'Clientes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-primary panel-box">
	<div class="panel-body">
		<?= $this->render('_filter', [
                'model' => $searchModel,
            ]);                
        ?>
	</div>
	<!-- ./filtro -->
	<div class="panel-body">
		<?php $columns = [
                [
                    'attribute' => 'id',
                    'hAlign' => GridView::ALIGN_CENTER,
                ],
                [
                    'attribute' => 'cliente.nome',
                    'width' => '40%',
                    'format' => 'raw',
                    'header' => '<span class="text-primary">Nome</span>',
                ],
                [
                    'attribute' => 'cliente.documento',
                    'label' => 'CPF/CNPJ',
                    'format' => 'raw',
                    'header' => '<span class="text-primary">Documento</span>',
                    'value' => function($model) {
                        if ($model->cliente->documento) {                            
                            if ($model->cliente->tipo == Cliente::TIPO_FISICO) {
                                return Util::mask($model->cliente->documento, Util::MASK_CPF);
                            }
                            
                            return Util::mask($model->cliente->documento, Util::MASK_CNPJ);
                        }
                        
                        return 'Sem Documento';
                    }
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                	'hAlign'=> GridView::ALIGN_CENTER,
                	'template' => '{negociacao}{update}{delete}',
                    'width' => '15%',
                	'header' => '',
                	'buttons' => [
    	                'negociacao' => function ($url, $model) {
                            return Html::a('<i class="fa fa-cog"></i>', ['/cliente/negociacao/', 'id' => $model->cliente->id], [
                                'class' => Util::BTN_COLOR_PURPLE,
                                'title' => 'Negociação',
                                'data-toggle' => 'tooltip',
        	                ]);
    	                },
    	                'update' => function ($url, $model) {
                			return Html::a('<i class="fa fa-pencil-alt"></i>', $url, [
                				'class' => Util::BTN_COLOR_WARNING,
                				'title' => 'Alterar',
                				'data-toggle' => 'tooltip',
                			]);
                		},
                		'delete' => function ($url, $model) {
                			return Html::a('<i class="fa fa-trash-alt"></i>', $url, [
                				'class' => Util::BTN_COLOR_DANGER,
                				'title' => 'Excluír',
                				'data-toggle' => 'tooltip',
                				'data-method' => 'post',
                			]);
                		},
                	],
                ],
            ];
		
		    // grid
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => $columns,
                /* 'pjax' => true,
                'pjaxSettings' => [
                    'options' => [
                        'id' => 'grid-contratos',
                        'enablePushState'=>false
                    ],
                ], */
    		    'toolbar' => [
    		        ['content' => Html::a('<i class="fa fa-plus"></i>&nbsp; Contrato', ['create'], ['class' => Util::BTN_COLOR_EMERALD, 'title' => 'Cadastrar Novo Contrato', 'data-toggle' => 'tooltip',])],
    		        ['content'=> Html::a('<i class="fa fa-undo"></i>', ['index'], ['id' => '_LimparFiltro', 'class' => Util::BTN_COLOR_DEFAULT, 'data-toggle' => 'tooltip', 'title' => 'Limpar Filtros'])],
    		        '{toggleData}',
    		    ],
    		    'bordered' => true,
    		    'striped' => true,
    		    'condensed' => true,
    		    'responsive' => true,
    		    'hover' => true,
    		    'showPageSummary' => false,
    		    'persistResize' => false,
            ]);
       ?>
    </div>
</div>
