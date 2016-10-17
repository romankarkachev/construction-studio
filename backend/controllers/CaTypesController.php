<?php

namespace backend\controllers;

use Yii;
use backend\models\CATypes;
use backend\models\CATypesSearch;
use backend\models\Counteragents;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * CaTypesController implements the CRUD actions for CATypes model.
 */
class CaTypesController extends Controller
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
                        'actions' => ['index', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['root'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all CATypes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CATypesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new CATypes model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CATypes();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/ca-types']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CATypes model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/ca-types']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CATypes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // проверим, не используется ли элемент в Контрагентах
        $used_in = Counteragents::find()->where(['ct_id' => $id])->count();

        if ($used_in > 0) return $this->render('/default/error', [
            'name' => 'Элемент используется',
            'message' => Html::encode('Элемент, который Вы пытаетесь удалить, используется в одном или нескольких других элементах системы (&laquo;Контрагенты&raquo;).'),
        ]);

        $this->findModel($id)->delete();

        return $this->redirect(['/ca-types']);
    }

    /**
     * Finds the CATypes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CATypes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CATypes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрошенная страница не может быть найдена.');
        }
    }
}