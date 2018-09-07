<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Credor */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Credors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credor-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nome',
            'tipo',
            'tipo_cobranca',
            'ativo',
            'razao_social',
            'cnpj',
            'telefone',
            'email:email',
            'logradouro',
            'numero',
            'complemento',
            'bairro',
            'cep',
            'cidade_id',
            'estado_id',
            'logo',
            'codigo',
            'sigla',
        ],
    ]) ?>

</div>
