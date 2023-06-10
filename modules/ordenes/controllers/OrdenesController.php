<?php

namespace app\modules\ordenes\controllers;

use app\controllers\CoreController;
use app\models\Bitacora;
use app\modules\ordenes\models\DetOrdenes;
use app\modules\ordenes\models\Ordenes;
use app\modules\ordenes\models\OrdenesSearch;
use Exception;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * OrdenesController implements the CRUD actions for Ordenes model.
 */
class OrdenesController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Ordenes models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrdenesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ordenes model.
     * @param int $id_orden Id Orden
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_orden)
    {
        $model = $this->findModel($id_orden);
        $orden = Ordenes::find()->where(['id_orden' => $id_orden])->one();
        $grid = new ActiveDataProvider(['query' => DetOrdenes::find()->where(['id_orden' => $id_orden])]);

        $sub_total = 0;
        $total = 0;

        $detOrdenes = DetOrdenes::find()->where(['id_orden' => $id_orden])->all();

        foreach ($detOrdenes as $detOrden) {
            $sub_total += ($detOrden->producto->precio * $detOrden->cantidad) - ($detOrden->producto->precio * $detOrden->cantidad) * ($detOrden->descuento / 100);
            $total += (($detOrden->producto->precio * $detOrden->cantidad) - ($detOrden->producto->precio * $detOrden->cantidad) * ($detOrden->descuento / 100)) * (1.13);
        }

        return $this->render('view', [
            'model' => $model,
            'orden' => $orden,
            'grid' => $grid,
            'sub_total' => $sub_total,
            'total' => $total,
        ]);
    }

    /**
     * Creates a new Ordenes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Ordenes();

        if ($model->load($this->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->estado = 0;

                if (!$model->save()) {
                    throw new Exception(implode("<br />", \yii\helpers\ArrayHelper::getColumn($model->getErrors(), 0, false)));
                }

                $data_original = Json::encode($model->getAttributes(), JSON_PRETTY_PRINT);

                $bitacora = new Bitacora();
                $bitacora->id_registro = $model->id_orden;
                $bitacora->controlador = $controller = Yii::$app->controller->id;
                $bitacora->accion = Yii::$app->controller->action->id;
                $bitacora->data_original = $data_original;
                $bitacora->data_modificada = NULL;
                $bitacora->id_usuario = Yii::$app->user->identity->id;
                $bitacora->fecha = $model->fecha_mod;

                if (!$bitacora->save()) {
                    throw new Exception(implode("<br />", \yii\helpers\ArrayHelper::getColumn($bitacora->getErrors(), 0, false)));
                }

                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                $controller = Yii::$app->controller->id . "/" . Yii::$app->controller->action->id;
                CoreController::getErrorLog(\Yii::$app->user->identity->id, $e, $controller);
                return $this->redirect(['index']);
            }

            Yii::$app->session->setFlash('success', "Registro creado exitosamente.");
            return $this->redirect(['det-ordenes/create', 'id_orden' => $model->id_orden]);
        } else {
            return $this->render('create', ['model' => $model,]);
        }
    }

    /**
     * Updates an existing Ordenes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_orden Id Orden
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_orden)
    {
        $model = $this->findModel($id_orden);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_orden' => $model->id_orden]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Ordenes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_orden Id Orden
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_orden)
    {
        $this->findModel($id_orden)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Ordenes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_orden Id Orden
     * @return Ordenes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_orden)
    {
        if (($model = Ordenes::findOne(['id_orden' => $id_orden])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
