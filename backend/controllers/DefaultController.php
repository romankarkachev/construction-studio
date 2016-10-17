<?php
namespace backend\controllers;

use backend\models\DocumentsFiles;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\Facilities;
use backend\models\Documents;
use backend\models\DocumentsTP;

/**
 * Default controller
 */
class DefaultController extends Controller
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
                        'actions' => ['login', 'error', 'facility', 'row-files-form'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Отображает страницу со строительным объектом.
     * @param $identifier integer
     * @return Facilities если найдена модель
     * @throws NotFoundHttpException если не обнаружена модель
     */
    public function actionFacility($identifier)
    {
        $this->layout = 'na';

        $model = Facilities::find()->where(['identifier' => $identifier])->one();
        if ($model != null) {
            $docs_ids = Documents::find()->select('id')->where(['facility_id' => $model->id])->asArray()->column();

            $query = DocumentsTP::find();
            $query->select('*, `documents_tp`.`id` AS `id`, `documents_tp`.`price` AS `price`, `documents`.`created_at` AS `docCreatedAt`, (
                SELECT COUNT(`documents_tp_files`.`id`) FROM `documents_tp_files`
                WHERE `documents_tp`.`id` = `documents_tp_files`.`row_id` AND `documents_tp_files`.`shared` = 1
            ) AS `filesCount`');
            $query->where(['in', 'doc_id', $docs_ids]);

            $query->joinWith(['doc', 'ps', 'unit']);

            $dp_tp = new ActiveDataProvider([
                'query' => $query,
            ]);

            $dp_tp->pagination = false; // постраничная навигация не предусмотрена
            $dp_tp->setSort([
                'defaultOrder' => ['docCreatedAt' => SORT_DESC],
                'attributes' => [
                    'id',
                    'created_at',
                    'docCreatedAt',
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
            $rows = $dp_tp->getModels();
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

            return $this->render('facility', ['model' => $model, 'dp_tp' => $dp_tp, 'summaries' => $summaries]);
        }
        else
            throw new NotFoundHttpException('Объект не обнаружен.');
    }

    /**
     * Рендерит и возвращает форму с изображениями к строке табличной части.
     * При помощи параметра type = 1 можно выбрать режим карусели вместо fancybox.
     * @param integer $id
     * @param integer $carousel
     * @return string
     */
    public function actionRowFilesForm($id, $carousel = null)
    {
        if (Yii::$app->request->isAjax) {
            $model = DocumentsTP::findOne($id);
            $uploadsDir = Yii::getAlias('@uploads-dtpf').'/';
            if ($model != null) {
                // можно через fancybox:
                $form_name = '_row_files_form';

                // можно через bootstrap carousel:
                if ($carousel != null) $form_name = '_row_files_carousel_form';

                return $this->renderAjax($form_name, [
                    'model' => $model,
                    'uploadsDir' => $uploadsDir,
                ]);
            }
        }

        return false;
    }
}