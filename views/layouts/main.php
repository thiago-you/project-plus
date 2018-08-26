<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\base\Util;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language; ?>">
        <head>
            <meta charset="UTF-8"/>
            <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
            <meta name="viewport" content="width=device-width, initial-scale=1"/>
            <?= Html::csrfMetaTags(); ?>
            <!-- ./meta -->
            <title><?= Yii::$app->name . ' | ' . Html::encode($this->title); ?></title>
            <?php $this->head(); ?>
            <!-- ./title and head -->
        </head>
        <!-- ./head -->
        <body>
            <?php $this->beginBody() ?>
                <div id="main-content-wrapper" class="wrap">
                	<div class="main-header">
                        <?php
                    	    NavBar::begin([
                    	        'brandLabel' => 'Auto Solutions',
                    	        'brandUrl' => Yii::$app->homeUrl,
                    	        'options' => [
                    	            'class' => 'navbar-inverse navbar-fixed-top',
                    	        ],
                    	    ]);
                    		    echo Nav::widget([
                    		        'options' => ['class' => 'navbar-nav navbar-right'],
                    		        'items' => [
                    		            ['label' => 'Home', 'url' => ['/site/index']],
                    		            ['label' => 'Clientes', 'url' => ['/cliente']],
                    		            Yii::$app->user->isGuest ? (
                    		                ['label' => 'Login', 'url' => ['/site/login']]
                    		            ) : (
                    		                '<li>'
                    		                . Html::beginForm(['/site/logout'], 'post')
                    		                . Html::submitButton(
                    		                    'Logout',
                    		                    ['class' => 'btn btn-link logout']
                    		                )
                    		                . Html::endForm()
                    		                . '</li>'
                    		            )
                    		        ],
                    		    ]);
                    	    NavBar::end();
                        ?>
                    </div>
               		<div class="container">
                        <section id="breadcrumbs-header" class="content-header">
                        	<div class="row">
                        		<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                                    <h1 class="font20">
                                        <?php if($this->title == 'Auto Solutions'): ?>
                                            <small>Bem vindo, </small>
                                            <?= ucfirst(Yii::$app->user->identity->username); ?>.
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
                            	<div class="col-md-12">
                            		<?php foreach (Yii::$app->session->getAllFlashes() as $key => $message): ?>
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
                           	&copy; Auto Solutions <?= date('Y') ?> - v<?= Util::getVersion(); ?>
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