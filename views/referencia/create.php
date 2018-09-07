<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Referencia */

$this->title = 'Create Referencia';
$this->params['breadcrumbs'][] = ['label' => 'Referencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="referencia-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
