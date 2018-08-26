<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JqueryAsset;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\BootstrapAsset;

use app\base\Util;
use app\assets\AppAsset;
use app\models\NfeEnviada;
use app\models\EmpresaConfig;
use \app\modules\loading\LoadingAsset;

$base_path = Yii::getAlias('@web');

$user_status = true;
if(Yii::$app->user->isGuest || !Yii::$app->user->identity) {    
    $user_status = false;
}

$script = <<< JS
var BASE_PATH = '{$base_path}/';
// valida se o usuario é valido
if(!'{$user_status}') {
    window.location.replace(BASE_PATH + 'site/logout');
}
JS;

$this->registerJs($script, \yii\web\View::POS_BEGIN);
LoadingAsset::register($this);
AppAsset::register($this);

// get dados do user, colaborador e empresa
$_userName = Yii::$app->user->identity->username;
$_empresaNome = Yii::$app->user->identity->empresa_nome_fantasia;
$_empresaDados = Json::decode(Yii::$app->user->identity->empresa_dados);
$_colaboradorNome = Yii::$app->user->identity->colaborador_nome;
$_colaboradorCargo = Yii::$app->user->identity->colaborador_cargo;
$_colaboradorDados = Json::decode(Yii::$app->user->identity->colaborador_dados);

// busca o apelido e telefone do colab
$_colaboradorDados['apelido'] = \app\models\Colaborador::find()->where(['user_id' => Yii::$app->user->identity->id])->select('apelido')->asArray(false)->one()->apelido;
$_colaboradorDados['fone1'] = \app\models\Colaborador::find()->where(['user_id' => Yii::$app->user->identity->id])->select('fone1')->asArray(false)->one()->fone1;

// get foto do colaborador
$_path = Url::to([\Yii::$app->params['dir']['colaborador']['imagem']]);
$out_foto = \Yii::$app->params['default']['colaborador']['imagem'];
if(!empty($colaboradorDados)) {
    if (!empty($colaboradorDados['foto'])) {
        $root = Yii::getAlias('@webroot');
        $test_foto = $root . \Yii::$app->params['dir']['colaborador']['imagem'] . $colaboradorDados['foto'];
        if (file_exists($test_foto)) {
            $out_foto = $_path . '/' . $colaboradorDados['foto'];
        }
    }
}
$colaboradorFoto = $out_foto;

// valida o layout da empresa
$skin = 'skin-blue';
if(EmpresaConfig::find(Yii::$app->user->identity->empresa_id)->one()->nf_contingencia == EmpresaConfig::EMPRESA_EM_CONTINGENCIA) {
    // procura a quantidade de notas em contingência
    $nfe = (NfeEnviada::countNfeNaoEnviada() + NfeEnviada::countNfceNaoEnviada());
    $skin = 'skin-red';
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
        <head>
            <meta charset="<?= Yii::$app->charset ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <?= Html::csrfMetaTags() ?>
            <title><?php echo Yii::$app->name . ' | ' . Html::encode($this->title); ?></title>
            <?php $this->head(); ?>
        </head>
        <!-- ./head -->
        <body id="megaMenu" class="<?= $skin; ?> layout-top-nav">
        	<input id="avisos" type="hidden" value="<?= \Yii::$app->session->get('AVISO'); ?>" />
        	<input id="user_id" type="hidden" value="<?= \Yii::$app->user->id; ?>" />
        	<div class="hidden"></div>
            <div class="loading-fog text-center" id="loading-fog">
                <div class="cssload-loader text-center"><p>Carregando</p></div>
            </div>
            <?php $this->beginBody(); ?>
                <div class="wrapper">
            		<div id="relogio" class="label label-emerald" title="" data-toggle="tooltip" data-placement="left">
            			<i id="relogio-icon" class="fa fa-clock-o fa-2x"></i>
                		<div id="relogio-hover">
                    		<b> Sessão</b><br/>
                    		<input id="relogio-timer" type="text" readonly="readonly" title="Tempo restante para expirar sua sessão"/><br/><br/>

                			<p id="relogio-hint"><b>Click para Renovar a Sessão!</b></p>
                		</div>
                	</div>
                    <!-- Fim Relógio -->
                    <header class="main-header">
                        <nav id="main-navbar" class="navbar navbar-static-top" role="navigation">
                			<div id="main-container" class="container">
                				<div id="main-navbar-header" class="navbar-header">
                                    <a id="logo" class="navbar-brand font20 text-center" href="/">
                                    	<b>GER</b>CPN<br/>
                                    	<span class="subbrand navbar-fluid font9" title="<?= Yii::$app->user->identity->empresa_nome_fantasia ?>"><?= Util::shortName(Yii::$app->user->identity->empresa_nome_fantasia, 30); ?></span>
                                    </a>
                                    <button id="navbar-collapse" class="navbar-toggle collapsed" data-target="#navbar-collapse" data-toggle="collapse" type="button">
                                    	<i class="fa fa-bars"></i>
                                	</button>
                                </div>
                                <!-- ./logo-default -->
                                <div id="contingencia-brand" style="display: <?= $skin == 'skin-red' ? 'block' : 'none'; ?>">
    								<svg id="left-svg-line">
    									<line x1="21" y1="21" x2="21" y2="31"/>
    								</svg>
    								<svg id="right-svg-line">
    									<line x1="21" y1="21" x2="21" y2="31"/>
    								</svg>
    								<!-- svg lines -->
    								<div id="contingencia-logo">
    									<button id="btn-contingencia" class="btn btn-flat btn-danger" data-toggle="tooltip" title="Notas em Contingência">
                            		    	<i class="fa fa-chain-broken"></i>&nbsp; Em Contingência &nbsp;
                            		    	<span class="badge badge-notify">
                                		  		<i id="notification-badge"><?= $nfe ?></i>
                                		  	</span>
                            		  	</button>
    								</div>
    								<!-- ./(button) de contingencia -->
								</div>
								<!-- ./logo-contingencia -->
                                <?php include('menu-top-user.php'); ?>
                                <?= $this->render(\Yii::$app->params['menu']); ?>
                                <!-- ./menu -->
                			</div>
                			<!-- ./main-container -->
                        </nav>
                        <!-- ./navbar -->
                    </header>
					<!-- ./header -->
                    <div id="main-content-wrapper" class="content-wrapper">
                		<div class="container">
                            <section id="breadcrumbs-header" class="content-header">
                            	<div class="row">
                            		<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                                        <h1 class="font20">
                                            <?php echo(isset($this->params['page']['title']) ? $this->params['page']['title'] : $this->title); ?>
                                            <?php echo(isset($this->params['page']['titleSmall']) ? '<small>' . $this->params['page']['titleSmall'] . '</small>' : ''); ?>
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
                            	&copy; CPN Informática <?= date('Y') ?> - GERCPN - v<?= Util::getVersion(); ?>
                            </div>
                            <!-- ./copyright -->
                            <div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-lg-offset1 text-right pull-right">    
                            	<div class="row">                        
                            		<div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                                    	<div class="btn-group dropup dropdown-fba">
                                            <button id="dropdown-contato" type="button" class="btn btn-emerald btn-small dropdown-toggle pull-right btnFaleConosco" data-placement="left" title="Fale Conosco" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            	<i class="fa fa-envelope-o"></i>
                                          	</button>
                                          	<ul class="dropdown-menu btn-block text-left">
                                            	<li style="text-align: right">
                                            		<button class="btn btn-small btn-lava btn-modal-contato btnFaleConoscoOpt" data-color="lava" data-assunto="Problema" data-toggle="tooltip" data-title="Problema" data-placement="left"><i class="fa fa-bug"></i></button>
                                            	</li>
                                            	<li style="text-align: right">
                                            		<button class="btn btn-small btn-orange btn-modal-contato btnFaleConoscoOpt" data-color="orange" data-assunto="Dúvida" data-toggle="tooltip" data-title="Dúvida" data-placement="left"><i class="fa fa-question-circle"></i></button>
                                        		</li>
                                            	<li style="text-align: right">
                                            		<button class="btn btn-small btn-purple btn-modal-contato btnFaleConoscoOpt" data-color="purple" data-assunto="Outros" data-toggle="tooltip" data-title="Outros" data-placement="left"><i class="fa fa-comment-o"></i></button>
                                            	</li>
                                          	</ul>
                                        </div>
                            		</div>
									<div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
                                        <span class="text-right" id="show-news" data-toggle="tooltip" title="Click para ver as novidades do sistema">
                                        	<span class="btn btn-info btn-small btn-block"><i class="fa fa-exclamation-circle"></i>&nbsp; O que há de novo <i class="fa fa-question"></i></span>
                                        </span>
                                    </div>        
                                </div>
                            </div>
                            <!-- ./buttons -->
                        </div>
                    </footer>
					<!-- ./footer -->
                	<div class="modal fade mb-news" id="mbNews" tabindex="-1" data-backdrop="static" data-keyboard="false">
                		<div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                </div>
                                <!-- ./modal-body -->
                                <div class="modal-footer">
                                	<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 text-right">
                                    	<button type="button" class="btn btn-primary btn-flat" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Fechar</button>	
                                		<!-- ./button -->
                            		</div>
                                </div>
                                <!-- ./footer -->
                            </div>
                            <!-- ./modal-content -->
                		</div>
                	</div>
                    <!-- ./modal da ultima atualizacao do sistema -->
                    <div class="modal fade" id="modal-email-contato" tabindex="-1">
                		<div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                    				<h4 class="modal-title"><i class="fa fa-envelope"></i> Contato - <span></span></h4>
                    			</div>
                    			<div class="modal-body" id="contato-body-enviando" style="display: none;">
                                	<br/><h2 class="text-center text-primary"><i class="fa fa-spinner fa-spin"></i>&nbsp; Enviando ...</h2><br/>
                                </div>
                                <div class="modal-body" id="contato-body-conteudo">
                                	<br/>
                                        <h2 class="text-center text-primary"><i class="fa fa-spinner fa-spin"></i>&nbsp; Carregando ...</h2>
                                    <br/>
                                </div>
                            </div>
                            <!-- ./modal-content -->
                		</div>
                	</div>
                    <!-- ./modal de contato por email -->
                    <?php
                        //JS
                        $this->registerJsFile(Url::home(). 'app/js/layout-main.js', ['depends' => [JqueryAsset::className()]]);
                        //CSS
                        $this->registerCssFile(Url::home() . 'app/css/layout-main.css?d=201708081022', ['depends' => [BootstrapAsset::className()]]);
                    ?>
                </div>
                <!-- ./wrapper -->
            <?php $this->endBody(); ?>
        </body>
        <!-- ./body -->
    </html>
    <!-- ./html -->
<?php $this->endPage() ?>
