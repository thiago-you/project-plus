<?php
use app\base\Helper;
use kartik\helpers\Html;
use kartik\file\FileInput;
use kartik\form\ActiveForm;

$this->title = 'Importação';
$this->params['breadcrumbs'][] = ['label' => 'Home', 'url' => ['/']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <div class="panel panel-primary panel-box">
        <div class="panel-heading">
        	<h4><i class="fa fa-upload"></i>&nbsp; Importação de Arquivo Excel</h4>
        </div>
        <!-- ./box-header -->
        <br><br>
        <div class="box-body">  
        	<div class="row">
        		<div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
                	<?= $form->field($model, 'fileImport')->widget(FileInput::className(), [
        	                'pluginOptions' => [
                                'showPreview' => false,
                                'showCaption' => true,
                                'showRemove' => true,
                                'showUpload' => false,
                                'browseLabel' => 'Procurar...',
                                'removeLabel' => '',
        	                    'maxFileSize' => 10000 // 10mb
        	                ]
                    	])->label('Arquivo Excel'); 
                	?>
            	</div>
        	</div>
        </div>
        <!-- ./box-body -->
        <br><br><br>
        <div class="panel-footer">
        	<div class="row">
        		<div class="col-md-4 col-lg-4 col-xs-12 col-sm-4">
        			<?= Html::submitButton('<i class="fa fa-paper-plane"></i>&nbsp; Enviar', [
        			        'class' => Helper::BTN_COLOR_PRIMARY.' btn-block',
        			    ]);
        			?>
        		</div>
        	</div>
        </div>
        <!-- ./box-footer -->
    </div>
    <!-- ./box -->
<?php ActiveForm::end(); ?>
<!-- ./form -->