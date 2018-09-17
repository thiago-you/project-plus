<?php
use yii\helpers\Html;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= \Yii::$app->language; ?>">
        <head>
            <meta charset="<?= \Yii::$app->charset; ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <?= Html::csrfMetaTags(); ?>
            <title><?= \Yii::$app->name.' | '.Html::encode($this->title); ?></title>
            <link rel="shortcut icon" href="<?= \Yii::$app->request->baseUrl; ?>/img/favicon-1.png" type="image/png" />
            <!-- ./favicon -->
            <?php $this->head(); ?>
        </head>
        <!-- ./head -->
        <body id="login-page">
            <?php $this->beginBody(); ?>
            	<?= $content; ?>
            <?php $this->endBody(); ?>
            <!-- ./app body -->
        </body>
        <!-- ./body -->
    </html>
<?php $this->endPage() ?>
