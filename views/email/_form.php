<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Email */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="email-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_cliente')->textInput() ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'observacao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ativo')->dropDownList([ 'S' => 'S', 'N' => 'N', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
