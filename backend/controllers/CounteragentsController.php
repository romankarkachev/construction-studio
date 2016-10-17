<?php

namespace backend\controllers;

use backend\models\Documents;
use backend\models\Facilities;
use Yii;
use backend\models\Counteragents;
use backend\models\CounteragentsSearch;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * CounteragentsController implements the CRUD actions for Counteragents model.
 */
class CounteragentsController extends Controller
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
                        'actions' => ['index', 'create', 'update', 'delete', 'list-nf'],
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
     * Lists all Counteragents models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CounteragentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['name' => SORT_ASC,]];
        $dataProvider->pagination = ['pageSize' => 50,];

        $searchApplied = Yii::$app->request->get('CounteragentsSearch') != null;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchApplied' => $searchApplied,
        ]);
    }

    /**
     * Creates a new Counteragents model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Counteragents();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/counteragents']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Counteragents model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/counteragents']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Counteragents model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // проверим, не используется ли контрагент в Объектах, Документах
        $used_in = Facilities::find()->where(['customer_id' => $id])->count();
        if ($used_in == 0) $used_in = Documents::find()->where(['ca_id' => $id])->count();

        if ($used_in > 0) return $this->render('/default/error', [
            'name' => 'Элемент используется',
            'message' => Html::encode('Элемент, который Вы пытаетесь удалить, используется в одном или нескольких других элементах системы (&laquo;Объектах&raquo; или &laquo;Документах&raquo;).'),
        ]);

        $this->findModel($id)->delete();

        return $this->redirect(['/counteragents']);
    }

    /**
     * Finds the Counteragents model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Counteragents the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Counteragents::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрошенная страница не может быть найдена.');
        }
    }

    /**
     * Функция выполняет поиск контрагента по наименованию, переданному в параметрах.
     * @param string $q
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionListNf($q)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $query = Counteragents::find()->select([
                'id' => 'counteragents.id',
                'text' => 'counteragents.name',
                'name_full',
                'phones',
                'email',
                'comment',
                'ca_type' => 'ca_types.name'
            ])
            ->joinWith(['type'])
            ->andFilterWhere(['like', 'counteragents.name', $q]);

        return ['results' => $query->asArray()->all()];
    }
}