<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Colaborador */

$this->title = 'Create Colaborador';
$this->params['breadcrumbs'][] = ['label' => 'Colaboradors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="colaborador-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
