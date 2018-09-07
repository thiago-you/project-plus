<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AcionamentoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Acionamentos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="acionamento-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Acionamento', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_cliente',
            'colaborador_id',
            'titulo',
            'descricao',
            //'data',
            //'telefone',
            //'tipo',
            //'subtipo',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
