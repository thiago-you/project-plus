<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use app\models\LoginForm;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\assets\AppAsset;

class SiteController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // se for guest redireciona para o login
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['site/login'])->send();
        }
        
        // registra a api para gerar graficos
        AppAsset::register(\Yii::$app->view)->js[] = 'plugins/chart.js/dist/Chart.js';
        
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin($invalidAcess = null)
    {
        // se não for guets retorna para a home
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        // layout de login
        $this->layout = 'login';
        
        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
