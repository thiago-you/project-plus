<?php
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = 'Clientes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-primary panel-box">
	<div class="panel-body">
        <p>
            <?= Html::a('<i class="fa fa-plus"></i>&nbsp; Cliente', ['cadastrar'], [
                    'class' => 'btn btn-emerald btn-flat',
                    'title' => 'Cadastrar Novo Cliente',
                    'data-toggle' => 'tooltip',
                ]);
            ?>
        </p>
       <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => [
                   'id_cliente',
                   'nome',
                   'sobrenome',
                   'apelido',
                   'documento',
                   'sexo',
                   'data_nascimento',
                   // 'data_cadastro',
                   'cep',
                   // 'endereco',
                   // 'numero',
                   // 'complemento',
                   // 'bairro',
                   // 'id_cidade',
                   // 'id_estado',
                   // 'email:email',
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
