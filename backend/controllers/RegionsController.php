<?php

namespace backend\controllers;

use Yii;
use backend\models\Regions;
use backend\models\RegionsSearch;
use backend\models\Facilities;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * RegionsController implements the CRUD actions for Regions model.
 */
class RegionsController extends Controller
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
                        'actions' => ['index', 'show', 'create', 'update', 'delete', 'list-nf'],
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
     * Выводит список регионов.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RegionsSearch();
        // стандартный механизм не применяем:
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider = $searchModel->search(['RegionsSearch' => ['parent_id' => null]]);
        $dataProvider->sort = ['defaultOrder' => ['name' => SORT_ASC,]];
        $dataProvider->pagination = ['pageSize' => 50,];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Выводит населенные пункты, которые входят в регион, идентификатор которого передается в параметрах.
     * @param integer $url
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionShow($url)
    {
        $parent_model = Regions::findOne(['id' => $url]);
        if ($parent_model !== null) {
            $searchModel = new RegionsSearch();
            $dataProvider = $searchModel->search(['RegionsSearch' => ['parent_id' => $parent_model->id]]);
            $dataProvider->sort = ['defaultOrder' => ['name' => SORT_ASC,]];
            $dataProvider->pagination = ['pageSize' => 50,];

            return $this->render('index', [
                'parent_model' => $parent_model,
                'dataProvider' => $dataProvider,
            ]);
        }
        else {
            throw new NotFoundHttpException('Запрошенная страница не существует.');
        }
    }

    /**
     * Creates a new Regions model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Regions();

        $parent_id = Yii::$app->request->get('parent_id');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // если в параметрах передается идентификатор региона, то после успешного сохранения
            // перенаправляем в список входящих в этот регион населенных пунктов
            if ($parent_id != null)
                return $this->redirect(['/regions/'.$parent_id]);
            else
                // иначе в общий список - список регионов
                return $this->redirect(['/regions']);
        } else {
            // в параметрах может передаваться идентификатор региона, в который необходимо поместить будущий
            // населенный пункт
            if ($parent_id != null) {
                $model->parent_id = $parent_id;
                $parent_model = Regions::findOne($parent_id);
            }

            return $this->render('create', [
                'model' => $model,
                'parent_model' => $parent_model,
            ]);
        }
    }

    /**
     * Updates an existing Regions model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/regions']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'parent_model' => $model->parent,
            ]);
        }
    }

    /**
     * Deletes an existing Regions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->parent_id == null) {
            // если указан регион, берем все населенные пункты региона
            $ids = Regions::find()->select('id')->where(['parent_id' => $id])->asArray()->column();
            // добавляем и сам регион, он тоже мог быть использован
            $ids[] = $id;
            // выполним отбор с этим набором идентификаторов
            $used_in = Facilities::find()->where(['in', 'region_id', $ids])->count();
        }
        else
            // если это населенный пункт, то выполним отбор, где он мог быть использован
            $used_in = Facilities::find()->where(['region_id' => $id])->count();

        if ($used_in > 0) return $this->render('/default/error', [
            'name' => 'Элемент используется',
            'message' => 'Элемент, который Вы пытаетесь удалить, используется в одном или нескольких Объектах.',
        ]);

        // удаляем населенные пункты, которые входят в этот регион
        if ($model->parent_id != null) Regions::deleteAll(['parent_id' => $id]);

        // а затем сам элемент
        $model->delete();

        return $this->redirect(['/regions']);
    }

    /**
     * Finds the Regions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Regions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Regions::findOne($id)) !== null) {
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

        $query = Regions::find()->select(['id', 'text' => 'name'])
            ->andFilterWhere(['like', 'name', $q]);

        return ['results' => $query->asArray()->all()];
    }
}