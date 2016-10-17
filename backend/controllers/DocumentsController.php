<?php

namespace backend\controllers;

use Yii;
use backend\models\Documents;
use backend\models\DocumentsSearch;
use backend\models\DocumentsFiles;
use backend\models\DocumentsFilesSearch;
use backend\models\DocumentsTP;
use backend\models\DocumentsTPSearch;
use backend\models\DocumentsTPFiles;
use backend\models\DocumentsTPFilesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * DocumentsController implements the CRUD actions for Documents model.
 */
class DocumentsController extends Controller
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
                        'actions' => [
                            'index', 'create', 'update', 'delete',
                            'upload-files', 'download', 'delete-file',
                            'add-row', 'edit-row', 'delete-row', 'upload-files-tp', 'download-tp', 'delete-tp-file',
                            'toggle-shared',
                        ],
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
                    'delete-row' => ['POST'],
                    'delete-tp-file' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Creates a new Documents model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @param $f_id \backend\models\Facilities
     * @return mixed
     */
    public function actionCreate($f_id)
    {
        $model = new Documents();
        $model->facility_id = $f_id;
        $model->ca_id = $model->facility->customer_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Documents model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]); // всегда остаемся в документе
        } else {
            // табличная часть
            $searchModel = new DocumentsTPSearch();
            $dataProvider = $searchModel->search(['DocumentsTPSearch' => ['doc_id' => $model->id]]);
            $dataProvider->pagination = false; // постраничная навигация не предусмотрена
            $dataProvider->setSort([
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => [
                    'id',
                    'created_at',
                    'docCreatedAt' => [
                        'asc' => ['documents.created_at' => SORT_ASC],
                        'desc' => ['documents.created_at' => SORT_DESC],
                    ],
                    'filesCount',
                    'psName' => [
                        'asc' => ['ps.name' => SORT_ASC],
                        'desc' => ['ps.name' => SORT_DESC],
                    ],
                    'unitName' => [
                        'asc' => ['units.name' => SORT_ASC],
                        'desc' => ['units.name' => SORT_DESC],
                    ],
                    'volume',
                    'price' => [
                        'asc' => ['documents_tp.price' => SORT_ASC],
                        'desc' => ['documents_tp.price' => SORT_DESC],
                    ],
                    'amount',
                    'comment',
                ]
            ]);

            // подсчитаем общие суммы
            $rows = $dataProvider->getModels();
            $total_amount = 0; // общая сумма по всем позициям
            $summaries = []; // общие суммы в разрезе типов номенклатуры
            foreach ($rows as $row) {
                if (isset($summaries[$row->ps->type->name_plural_nominative_case]))
                    $summaries[$row->ps->type->name_plural_nominative_case] = $summaries[$row->ps->type->name_plural_nominative_case] + $row->amount;
                else
                    $summaries[$row->ps->type->name_plural_nominative_case] = $row->amount;

                $total_amount = $total_amount + $row->amount;
            }
            $summaries['total_amount'] = $total_amount;

            // файлы к документу
            $searchModel = new DocumentsFilesSearch();
            $dp_files = $searchModel->search(['DocumentsFilesSearch' => ['doc_id' => $model->id]]);

            return $this->render('update', [
                'model' => $model,
                'summaries' => $summaries,
                'dp_table_part' => $dataProvider,
                'dp_files' => $dp_files,
            ]);
        }
    }

    /**
     * Deletes an existing Documents model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $facility_id = $model->facility_id;
        $model->delete();

        return $this->redirect(['/facilities/update', 'id' => $facility_id]);
    }

    /**
     * Finds the Documents model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Documents the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Documents::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрошенная страница не может быть найдена.');
        }
    }

    /**
     * Позиционируется на модель DocumentsTP по идентификатору.
     * Если модель не будет обнаружена, будет выброшено исключение HTTP 404.
     * @param integer $id
     * @return Documents the loaded model
     * @throws NotFoundHttpException если модель не будет обнаружена
     */
    protected function findTPModel($id)
    {
        if (($model = DocumentsTP::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Элемент не обнаружен.');
        }
    }

    /**
     * Загрузка файлов, перемещение их из временной папки, запись в базу данных.
     * @return mixed
     */
    public function actionUploadFiles()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $doc_id = Yii::$app->request->post('doc_id');
        $upload_path = DocumentsFiles::getUploadsFilepath();
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
                    $fu = new DocumentsFiles();
                    $fu->doc_id = $doc_id;
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
            $model = DocumentsFiles::findOne($id);
            if (file_exists($model->ffp))
                return Yii::$app->response->sendFile($model->ffp, $model->ofn);
            else
                throw new NotFoundHttpException('Файл не обнаружен.');
        };
    }

    /**
     * Удаляет файл, привязанный к документу.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если файл не будет обнаружен
     */
    public function actionDeleteFile($id)
    {
        $model = DocumentsFiles::findOne($id);
        if ($model != null) {
            $doc_id = $model->doc_id;
            $model->delete();

            return $this->redirect(['/documents/update', 'id' => $doc_id]);
        }
        else
            throw new NotFoundHttpException('Файл не обнаружен.');
    }

    /**
     * Выполняет добавление строки табличной части документа.
     * Если добавление выполнено успешно, отображается страница редактирования документа.
     * @return mixed
     */
    public function actionAddRow()
    {
        $model = new DocumentsTP();

        if ($model->load(Yii::$app->request->post()))
            if (!$model->save()) {
                Yii::$app->session->setFlash('error', 'Не удалось сохранить добавить строку табличной части!');
            }

        return $this->redirect(['/documents/update', 'id' => $model->doc_id]);
    }

    /**
     * Выполняет редактирование строки табличной части.
     * @param integer $id
     * @return mixed
     */
    public function actionEditRow($id)
    {
        $model = $this->findTPModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/documents/update', 'id' => $model->doc_id]);
        } else {
            $searchModel = new DocumentsTPFilesSearch();
            $dp_files = $searchModel->search(['DocumentsTPFilesSearch' => ['row_id' => $model->id]]);
            $dp_files->setSort([
                'defaultOrder' => ['uploaded_at' => SORT_DESC],
            ]);
            $dp_files->pagination = false;

            return $this->render('/documents-tp/update', [
                'model' => $model,
                'dp_files' => $dp_files,
            ]);
        }
    }

    /**
     * Удаляет строку табличной части.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteRow($id)
    {
        $model = $this->findTPModel($id);
        $doc_id = $model->doc_id;
        $model->delete();

        return $this->redirect(['/documents/update', 'id' => $doc_id]);
    }

    /**
     * Загрузка файлов, перемещение их из временной папки, запись в базу данных.
     * @return mixed
     */
    public function actionUploadFilesTp()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $row_id = Yii::$app->request->post('row_id');
        $upload_path = DocumentsTPFiles::getUploadsFilepath();
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
                    $fu = new DocumentsTPFiles();
                    $fu->row_id = $row_id;
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
    public function actionDownloadTp($id)
    {
        if (is_numeric($id)) if ($id > 0) {
            $model = DocumentsTPFiles::findOne($id);
            if (file_exists($model->ffp))
                return Yii::$app->response->sendFile($model->ffp, $model->ofn);
            else
                throw new NotFoundHttpException('Файл не обнаружен.');
        };
    }

    /**
     * Удаляет файл, привязанный к строке табличной части.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если файл не будет обнаружен
     */
    public function actionDeleteTpFile($id)
    {
        $model = DocumentsTPFiles::findOne($id);
        if ($model != null) {
            $row_id = $model->row_id;
            $model->delete();

            return $this->redirect(['/documents/edit-row', 'id' => $row_id]);
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
            $type = Yii::$app->request->post('type');
            if ($id !== null && $type != null) {
                switch ($type) {
                    case 0:
                        $model = DocumentsFiles::findOne(['id' => $id]);
                        break;
                    case 1:
                        $model = DocumentsTPFiles::findOne(['id' => $id]);
                        break;
                }
                if ($model != null) {
                    if ($model->shared == 0) $model->shared = 1; else $model->shared = 0;
                    $model->save();
                }
            }
        }
    }
}