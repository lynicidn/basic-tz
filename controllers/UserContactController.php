<?php

namespace app\controllers;

use app\models\UserContactSearch;
use app\models\UserContact;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class UserContactController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserContactSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new UserContact();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->goHome();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param string|integer $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        if (!$model = UserContact::findOne($id)) {
            throw new NotFoundHttpException('User contact not found.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->goHome();
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param string|integer $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        if (!$model = UserContact::findOne($id)) {
            throw new NotFoundHttpException('User contact not found.');
        }

        $model->delete();

        return $this->goBack();
    }
}