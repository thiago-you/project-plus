<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

$this->title = 'Login';
?>
<div class="container">
    <div class="panel panel-default panel-box">
    	<div class="panel-body">
            <h2 class="text-center"><?= Html::encode($this->title); ?></h2>
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            	<div class="row">
                	<div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-lg-10 col-lg-offset-1 col-xs-12">
                        <div class="form-group">
                            <?= $form->field($model, 'username')->textInput(['id' => 'usuario-nome', 'autofocus' => true])->label('Usuario'); ?>
                            <?= $form->field($model, 'password')->passwordInput()->label('Senha'); ?>
                            <?= $form->field($model, 'rememberMe')->checkbox()->label('Lembrar Login'); ?>
                        </div>
                    </div>
       			</div>
       			<!-- ./inputs -->
       			<br>
       			<div class="row">
       				<div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-lg-10 col-lg-offset-1 col-xs-12">
                        <div class="form-group"> 
                             <?= Html::submitButton('<i class="fa fa-arrow-right"></i>&nbsp; Login', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
                        </div>
                   	</div>
       			</div>
       			<!-- ./button -->
            <?php ActiveForm::end(); ?>
            <!-- ./form -->
            <br>
            <small class="help-block">
                <i class="fa fa-info-circle"></i>&nbsp; Login padrão para o ambiente de desenvolvimento: <b>admin/admin</b>.
            </small>
            <!-- ./help -->
            <div class="row sys-info">
            	<div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                	<?= date('d/m/Y H:i', strtotime('now')); ?>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6 text-right">
                	V<?= \Yii::$app->params['version']; ?>
                </div>
            </div>
        </div>
        <!-- ./panle-body -->
    </div>
    <!-- ./panel -->
</div>
<!-- ./container -->
<style type="text/css">
#login-page {
    background-image: url('<?= Url::home().'img/img-'.rand(1, 10); ?>.jpg');
    background-repeat: no-repeat;
    background-position: center center;
    background-attachment: fixed;
    background-size: cover;
    overflow: hidden;
}
#login-page .container {
    margin-top: 6%;
}
#login-page .container .panel {
    position: absolute;
    width: 430px;
    left: 50%;
    margin-left: -215px;
    box-shadow: 0px 2px 6px 0px rgba(0,0,0,.6);
}
#login-page .container .panel,
#login-page .container .panel .panel-body {
    padding-bottom: 2px;
}
#login-page .container .panel .sys-info {
    margin-top: 20px;
    font-size: 10px;
    color: #737373;
    font-weight: bold;
}
.content-error {
    padding: 15px 15px 0px 15px;
    margin-right: auto;
    margin-left: auto;
}
.alert {
    border-radius: 0px;
}
</style>
<!-- ./style da img de fundo -->
<?php 
$script = <<<JS
	$(document).ready(function() {
		$('#usuario-nome').focus();
	});
JS;
$this->registerJs($script, View::POS_LOAD);
?>
