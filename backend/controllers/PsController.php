<?php

namespace backend\controllers;

use backend\models\DocumentsTP;
use Yii;
use backend\models\PS;
use backend\models\PSSearch;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * PsController implements the CRUD actions for PS model.
 */
class PsController extends Controller
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
                        'actions' => ['index', 'create', 'update', 'delete', 'list-nr'],
                        'allow' => true,
                        'roles' => ['root', 'manager'],
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
     * Lists all PS models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PSSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['name' => SORT_ASC,]];
        $dataProvider->pagination = ['pageSize' => 50,];

        $searchApplied = Yii::$app->request->get('PSSearch') != null;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchApplied' => $searchApplied,
        ]);
    }

    /**
     * Creates a new PS model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PS();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/ps']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PS model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/ps']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PS model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // проверим, не используется ли элемент в Строках табличных частей документов
        $used_in = DocumentsTP::find()->where(['ps_id' => $id])->count();

        if ($used_in > 0) return $this->render('/default/error', [
            'name' => 'Элемент используется',
            'message' => Html::encode('Элемент, который Вы пытаетесь удалить, используется в одном или нескольких других элементах системы (&laquo;Документы&raquo;).'),
        ]);

        $this->findModel($id)->delete();

        return $this->redirect(['/ps']);
    }

    /**
     * Finds the PS model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PS the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PS::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрошенная страница не может быть найдена.');
        }
    }

    /**
     * Функция выполняет поиск номенклатуры по наименованию, переданному в параметрах.
     * @param string $q
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionListNr($q)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $query = PS::find()->select(['id', 'text' => 'name', 'unit_id' => 'bu_id', 'price'])
            ->andFilterWhere(['like', 'name', $q]);

        return ['results' => $query->asArray()->all()];
    }
}