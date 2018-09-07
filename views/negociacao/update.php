<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Negociacao */

$this->title = 'Update Negociacao: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Negociacaos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="negociacao-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
