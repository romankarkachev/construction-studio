<?php

namespace backend\controllers;

use Yii;
use backend\models\FacilitiesStates;
use backend\models\FacilitiesStatesSearch;
use backend\models\Facilities;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * FacilitiesStatesController implements the CRUD actions for FacilitiesStates model.
 */
class FacilitiesStatesController extends Controller
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
     * Lists all FacilitiesStates models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FacilitiesStatesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new FacilitiesStates model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FacilitiesStates();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/facilities-states']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FacilitiesStates model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/facilities-states']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing FacilitiesStates model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // проверим, не используется ли элемент в Объектах
        $used_in = Facilities::find()->where(['fs_id' => $id])->count();

        if ($used_in > 0) return $this->render('/default/error', [
            'name' => 'Элемент используется',
            'message' => Html::encode('Элемент, который Вы пытаетесь удалить, используется в одном или нескольких других элементах системы (&laquo;Объекты&raquo;).'),
        ]);
        $this->findModel($id)->delete();

        return $this->redirect(['/facilities-states']);
    }

    /**
     * Finds the FacilitiesStates model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FacilitiesStates the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FacilitiesStates::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрошенная страница не может быть найдена.');
        }
    }
}