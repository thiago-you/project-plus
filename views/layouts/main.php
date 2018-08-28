<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= \Yii::$app->language; ?>">
        <head>
            <meta charset="UTF-8"/>
            <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
            <meta name="viewport" content="width=device-width, initial-scale=1"/>
            <?= Html::csrfMetaTags(); ?>
            <title><?= \Yii::$app->name . ' | ' . Html::encode($this->title); ?></title>
            <?php $this->head(); ?>
        </head>
        <!-- ./head -->
        <body>
            <?php $this->beginBody(); ?>
                <div id="main-content-wrapper" class="wrap">
                	<div class="main-header">
                        <nav id="main-navbar" class="navbar navbar-default navbar-fixed-top" role="navigation">
                			<div id="main-container" class="container">
                				<div class="navbar-header font20 text-center">
                                    <a class="navbar-brand" href="<?= \Yii::$app->homeUrl; ?>">
                                    	Exemplo &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|
                                    </a>
                                </div>
                                <!-- ./brand -->
                                <?= $this->render('menu'); ?>
                                <!-- ./menu -->
                			</div>
                			<!-- ./main-container -->
                        </nav>
                        <!-- ./navbar -->
                    </div>
               		<div class="container">
                        <section id="breadcrumbs-header" class="content-header">
                        	<div class="row">
                        		<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                                    <h1 class="font20">
                                        <?php if($this->title == 'Auto Solutions'): ?>
                                            <small>Bem vindo, </small>
                                            <?= ucfirst(\Yii::$app->user->identity->username); ?>.
                                        <?php else: ?>
											<?= $this->title; ?>                                        
                                        <?php endif; ?>
                                    </h1>
                                    <?= Breadcrumbs::widget([
                                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                                        ]);
                                    ?>
                                </div>
                            </div>
                        </section>
                        <!-- ./breadcrumbs container -->
                        <section class="content">
                        	<div class="row">
                            	<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                            		<?php foreach (\Yii::$app->session->getAllFlashes() as $key => $message): ?>
                                    	<div class="alert alert-flat alert-<?= $key ?> flash-msg">
                                    		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    		<?= $message ?>
                                    	</div>
                            		<?php endforeach; ?>
                           		</div>
                            </div>
                            <!-- ./flash-msg -->
                            <?= $content ?>
                            <!-- ./page-content -->
                        </section>
                        <!-- ./section-content -->                            
               		</div> 
            	</div>
                <!-- .content-wrapper -->
                <footer class="main-footer">
               		<div class="row">
                   		<div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                           	&copy; Exemplo <?= date('Y') ?> - v<?= \Yii::$app->params['version']; ?>
                        </div>
                        <!-- ./copyright -->
                    </div>
                </footer>
    			<!-- ./footer -->
            <?php $this->endBody() ?>
        </body>
        <!-- ./body -->
    </html>
    <!-- ./html -->
<?php $this->endPage() ?>
<!-- ./file -->