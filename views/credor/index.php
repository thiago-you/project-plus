<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CredorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Credors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credor-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Credor', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nome',
            'tipo',
            'tipo_cobranca',
            'ativo',
            //'razao_social',
            //'cnpj',
            //'telefone',
            //'email:email',
            //'logradouro',
            //'numero',
            //'complemento',
            //'bairro',
            //'cep',
            //'cidade_id',
            //'estado_id',
            //'logo',
            //'codigo',
            //'sigla',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
