<?php

namespace app\controllers;

use app\models\SignupForm;
use app\models\User;
use app\models\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['sign-up', 'login', 'logout'],
                'rules' => [
                    [
                        'actions' => ['login', 'sign-up'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
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

    public function actionSignUp()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $user = $model->signup()) {
            if ($user->sendActivateToken()) {
                Yii::$app->session->setFlash('success', 'Activate token send to your email.');
            } else {
                Yii::$app->session->setFlash('error', 'Activate token not send to your email.');
            }
            return $this->goHome();
        } else {
            return $this->render('signup', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionActivate($token)
    {
        if ($user = User::findByActivateToken($token)) {
            $user->removeActivateToken();
            if ($user->save()) {
                Yii::$app->session->setFlash('success', 'Your account activated success.');
            } else {
                throw new ServerErrorHttpException('Account not activated.');
            }
        }

        return $this->goHome();
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
