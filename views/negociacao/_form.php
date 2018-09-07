<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Negociacao */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="negociacao-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_contrato')->textInput() ?>

    <?= $form->field($model, 'data_negociacao')->textInput() ?>

    <?= $form->field($model, 'data_cadastro')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
