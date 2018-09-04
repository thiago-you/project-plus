<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Telefone */

$this->title = 'Update Telefone: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Telefones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="telefone-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
