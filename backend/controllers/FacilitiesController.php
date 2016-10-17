<?php

namespace backend\controllers;

use Yii;
use backend\models\Facilities;
use backend\models\FacilitiesSearch;
use backend\models\FacilitiesFiles;
use backend\models\FacilitiesFilesSearch;
use backend\models\DocumentsSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * FacilitiesController implements the CRUD actions for Facilities model.
 */
class FacilitiesController extends Controller
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
                        'actions' => ['index', 'create', 'update', 'delete', 'upload-files', 'download', 'delete-file', 'toggle-shared'],
                        'allow' => true,
                        'roles' => ['root', 'manager'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'delete-file' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Facilities models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FacilitiesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['updated_at' => SORT_DESC,]];
        $dataProvider->pagination = ['pageSize' => 50,];

        $searchApplied = Yii::$app->request->get('FacilitiesSearch') != null;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchApplied' => $searchApplied,
        ]);
    }

    /**
     * Creates a new Facilities model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Facilities();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/facilities']);
        } else {
            $model->name = 'Новый объект';
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Facilities model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/facilities/update', 'id' => $model->id]);
        } else {
            // документы к объекту
            $searchModel = new DocumentsSearch();
            $dp_docs = $searchModel->search(['DocumentsSearch' => ['facility_id' => $model->id]]);
            $dp_docs->setSort([
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => [
                    'id',
                    'created_at',
                    'tpAmount',
                    'rowsCount',
                    'filesCount',
                    'total_amount',
                    'comment',
                ]
            ]);
            $dp_docs->pagination = ['pageSize' => 100,];

            // файлы к объекту
            $searchModel = new FacilitiesFilesSearch();
            $dp_files = $searchModel->search(['FacilitiesFilesSearch' => ['facility_id' => $model->id]]);
            $dp_files->setSort([
                'defaultOrder' => ['uploaded_at' => SORT_DESC],
            ]);
            $dp_files->pagination = false;

            return $this->render('update', [
                'model' => $model,
                'documents' => $dp_docs,
                'files' => $dp_files,
            ]);
        }
    }

    /**
     * Deletes an existing Facilities model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['/facilities']);
    }

    /**
     * Finds the Facilities model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Facilities the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Facilities::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрошенная страница не может быть найдена.');
        }
    }

    /**
     * Загрузка файлов, перемещение их из временной папки, запись в базу данных.
     * @return mixed
     */
    public function actionUploadFiles()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $facility_id = Yii::$app->request->post('facility_id');
        $upload_path = FacilitiesFiles::getUploadsFilepath();
        if ($upload_path === false) return 'Невозможно создать папку для хранения загруженных файлов!';

        // массив загружаемых файлов
        $files = $_FILES['files'];
        // массив имен загружаемых файлов
        $filenames = $files['name'];
        if (count($filenames) > 0)
            for ($i=0; $i < count($filenames); $i++) {
                $ext = end(explode('.', basename($filenames[$i])));
                $filename = mb_strtolower(Yii::$app->security->generateRandomString().'.'.$ext, 'utf-8');
                $filepath = $upload_path.'/'.$filename;
                if (move_uploaded_file($files['tmp_name'][$i], $filepath)) {
                    $fu = new FacilitiesFiles();
                    $fu->facility_id = $facility_id;
                    $fu->ffp = $filepath;
                    $fu->fn = $filename;
                    $fu->ofn = $filenames[$i];
                    $fu->size = filesize($filepath);
                    if ($fu->validate()) $fu->save(); else return 'Загруженные данные неверны.';
                };
            };

        return [];
    }

    /**
     * Отдает на скачивание файл, на который позиционируется по идентификатору из параметров.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если файл не будет обнаружен
     */
    public function actionDownload($id)
    {
        if (is_numeric($id)) if ($id > 0) {
            $model = FacilitiesFiles::findOne($id);
            if (file_exists($model->ffp))
                return Yii::$app->response->sendFile($model->ffp, $model->ofn);
            else
                throw new NotFoundHttpException('Файл не обнаружен.');
        };
    }

    /**
     * Удаляет файл, привязанный к объекту.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если файл не будет обнаружен
     */
    public function actionDeleteFile($id)
    {
        $model = FacilitiesFiles::findOne($id);
        if ($model != null) {
            $facility_id = $model->facility_id;
            $model->delete();

            return $this->redirect(['/facilities/update', 'id' => $facility_id]);
        }
        else
            throw new NotFoundHttpException('Файл не обнаружен.');
    }

    /**
     * Переключает значение общего доступа к файлу.
     */
    public function actionToggleShared()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            if ($id !== null) {
                $model = FacilitiesFiles::findOne(['id' => $id]);
                if ($model != null) {
                    if ($model->shared == 0) $model->shared = 1; else $model->shared = 0;
                    $model->save();
                }
            }
        }
    }
}