<?php

use app\modules\productos\models\Productos;
use kartik\editable\Editable;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\modules\compras\models\DetCompras $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="row">
    <div class="col-md-8">
        <div class="card card-outline card-dark">
            <div class="card-header">
                <h3 class="card-title">Datos de compra</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm table-striped table-hover table-bordered">
                    <tr>
                        <td width="20%"><b>Codigo:</b></td>
                        <td width="30%"><?= $compra->codigo ?></td>
                        <td width="20%"><b>Numero factura:</b></td>
                        <td width="30%"><?= $compra->num_factura ?></td>
                    </tr>
                    <tr>
                        <td><b>Tipo de compra:</b></td>
                        <td><?= $compra->estado == 0 ? "Credito" : "Contado"; ?></td>
                        <td><b>Proveedor:</b></td>
                        <td><?= $compra->proveedor->nombre ?></td>
                    </tr>
                    <tr>
                        <td><b>Fecha:</b></td>
                        <td><?= $compra->fecha ?></td>
                        <td><b>Estado:</b></td>
                        <td><span class="badge bg-<?= $compra->estado == 0 ? "warning" : "green"; ?>"><?= $compra->estado == 0 ? "Sin procesar" : "Procesada"; ?></span></td>
                    </tr>
                    <tr>
                        <td><b>Comentarios:</b></td>
                        <td colspan="3"><?= nl2br($compra->comentarios) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body" style="background-color: #ffdf7e;">
                <table class="table table-sm" style="text-align: right;">
                    <tr>
                        <td width="40%">
                            <h4>Sub-Total:</h4>
                        </td>
                        <td width="10%">
                            <h4>$</h4>
                        </td>
                        <td width="10%">
                            <h4><?php echo number_format($sub_total, 2) ?></h4>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4>IVA:</h4>
                        </td>
                        <td>
                            <h4>$</h4>
                        </td>
                        <td>
                            <h4><?php echo number_format($iva, 2) ?></h4>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4>Retencion:</h4>
                        </td>
                        <td>
                            <h4>$</h4>
                        </td>
                        <td>
                            <h4><?php echo number_format($retencion, 2) ?></h4>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4>Total:</h4>
                        </td>
                        <td>
                            <h4>$</h4>
                        </td>
                        <td>
                            <h4><b><?php echo number_format($total, 2) ?></b></h4>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-dark">
            <div class="card-header">
                <h3 class="card-title">Agregar detalle de compra</h3>
            </div>
            <?php $form = ActiveForm::begin(['id' => 'crear-form'], ['options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="card-body">
                <form role="form">
                    <div class="row">
                        <div class="col-md-12">
                            <?= Html::activeLabel($model, 'id_producto', ['class' => 'control-label']) ?>
                            <?= $form->field($model, 'id_producto', ['showLabels' => false])->widget(Select2::class, [
                                'data' => ArrayHelper::map(
                                    Productos::find()->all(),
                                    'id_producto',
                                    function ($model) {
                                        return $model->sku . ' - ' . $model->nombre;
                                    }
                                ),
                                'language' => 'es',
                                'options' => ['placeholder' => '- Seleccionar Producto -'],
                                'pluginOptions' => ['allowClear' => true],
                            ]); ?>
                        </div>
                        <div class="col-md-4">
                            <?= Html::activeLabel($model, 'cantidad', ['class' => 'control-label']) ?>
                            <?= $form->field($model, 'cantidad', ['showLabels' => false])->textInput(['type' => 'number']); ?>
                        </div>
                        <div class="col-md-4">
                            <?= Html::activeLabel($model, 'costo', ['class' => 'control-label']) ?>
                            <?= $form->field($model, 'costo', ['showLabels' => false, 'addon' => [
                                'prepend' => ['content' => '<span>$</span>'],
                            ]])->textInput(['type' => 'number', 'step' => '0.01']); ?>
                        </div>
                        <div class="col-md-4">
                            <?= Html::activeLabel($model, 'descuento', ['class' => 'control-label']) ?>
                            <?= $form->field($model, 'descuento', ['showLabels' => false, 'addon' => [
                                'append' => ['content' => '<span>%</span>'],
                            ]])->textInput(['value' => '0.00', 'type' => 'number', 'step' => '0.01']); ?>
                        </div>
                        <div class="card-footer">
                            <?= Html::submitButton(
                                $model->isNewRecord ? '<i class="fa fa-save"></i> Guardar' : '<i class="fa fa-save"></i> Actualizar',
                                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => 'submit-button']
                            ) ?>
                            <?= Html::a('<i class="fa fa-ban"></i> Cancelar', ['/compras/compras/view', 'id_compra' => $compra->id_compra], ['class' => 'btn btn-danger']) ?>
                        </div>
                    </div>
                </form>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="det-compras-index">
            <?php
            $gridColums = [
                [
                    'class' => 'kartik\grid\SerialColumn',
                    'contentOptions' => ['class' => 'kartik-sheet-style'],
                    'width' => '36px',
                    'header' => '#',
                    'headerOptions' => ['class' => 'kartik-sheet-style']
                ],
                [
                    'class' => 'kartik\grid\DataColumn',
                    'attribute' => 'id_producto',
                    'width' => '25%',
                    'vAlign' => 'middle',
                    'format' => 'html',
                    'value' => function ($model) {
                        return $model->producto->nombre;
                    },
                    'pageSummary' => 'Total',
                ],
                [
                    'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'cantidad',
                    'editableOptions' => [
                        'asPopover' => false,
                        'formOptions' => ['action' => ['/compras/det-compras/editarcantidad']],
                        'inputType' => Editable::INPUT_TEXT,
                        'options' => [
                            'pluginOptions' => ['min' => 0, 'max' => 10000]
                        ]
                    ],
                    'refreshGrid' => true,
                    'hAlign' => 'right',
                    'vAlign' => 'middle',
                    'width' => '10%',
                    'pageSummary' => true,
                    'filter' => false,
                ],
                [
                    'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'costo',
                    'editableOptions' => [
                        'asPopover' => false,
                        'formOptions' => ['action' => ['/compras/det-compras/editarcosto']],
                        'inputType' => Editable::INPUT_TEXT,
                        'options' => [
                            'pluginOptions' => ['min' => 0, 'max' => 10000]
                        ]
                    ],
                    'refreshGrid' => true,
                    'hAlign' => 'right',
                    'vAlign' => 'middle',
                    'width' => '10%',
                    'pageSummary' => true,
                    'filter' => false,
                    'format' => 'currency',
                ],
                [
                    'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'descuento',
                    'editableOptions' => [
                        'asPopover' => false,
                        'formOptions' => ['action' => ['/compras/det-compras/editardescuento']],
                        'inputType' => Editable::INPUT_TEXT,
                        'options' => [
                            'pluginOptions' => ['min' => 0, 'max' => 10000]
                        ]
                    ],
                    'refreshGrid' => true,
                    'hAlign' => 'right',
                    'vAlign' => 'middle',
                    'width' => '10%',
                    'pageSummary' => false,
                    'filter' => false,
                ],
                [
                    'class' => 'kartik\grid\FormulaColumn',
                    'header' => 'Total descuento',
                    'vAlign' => 'middle',
                    'value' => function ($model, $key, $index, $widget) {
                        $p = compact('model', 'key', 'index');
                        return ($widget->col(2, $p) * $widget->col(3, $p)) * ($widget->col(4, $p) / 100);
                    },
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'hAlign' => 'right',
                    'width' => '10%',
                    'mergeHeader' => true,
                    'pageSummary' => true,
                    'footer' => true,
                    'format' => 'currency'
                ],
                [
                    'class' => 'kartik\grid\FormulaColumn',
                    'header' => 'Sub-Total',
                    'vAlign' => 'middle',
                    'value' => function ($model, $key, $index, $widget) {
                        $p = compact('model', 'key', 'index');
                        return ($widget->col(2, $p) * $widget->col(3, $p)) - ($widget->col(5, $p));
                    },
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'hAlign' => 'right',
                    'width' => '10%',
                    'mergeHeader' => true,
                    'pageSummary' => true,
                    'footer' => true,
                    'format' => 'currency'
                ],
                [
                    'class' => 'kartik\grid\FormulaColumn',
                    'header' => 'IVA',
                    'vAlign' => 'middle',
                    'value' => function ($model, $key, $index, $widget) {
                        $p = compact('model', 'key', 'index');
                        return ($widget->col(6, $p) * 0.13);
                    },
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'hAlign' => 'right',
                    'width' => '10%',
                    'mergeHeader' => true,
                    'pageSummary' => true,
                    'footer' => true,
                    'format' => 'currency'
                ],
                [
                    'class' => 'kartik\grid\FormulaColumn',
                    'header' => 'Total',
                    'vAlign' => 'middle',
                    'value' => function ($model, $key, $index, $widget) {
                        $p = compact('model', 'key', 'index');
                        return ($widget->col(6, $p) + $widget->col(7, $p));
                    },
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'hAlign' => 'right',
                    'width' => '10%',
                    'mergeHeader' => true,
                    'pageSummary' => true,
                    'footer' => true,
                    'format' => 'currency'
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'header' => 'Acciones',
                    'template' => '{delete}',
                    'width' => '5%',
                    'vAlign' => GridView::ALIGN_BOTTOM,
                    'buttons' => [
                        'delete' => function ($url) {
                            return Html::a('<span class="fa fa-trash-alt" style="color:red"></span>', $url, [
                                'title' => Yii::t('app', 'Delete'),
                                'data-confirm' => Yii::t('yii', 'Esta seguro de borrar este registro?'),
                                'data-method' => 'post', 'data-pjax' => '0',
                            ]);
                        }
                    ],
                    'urlCreator' => function ($action, $model) {
                        if ($action === 'delete') {
                            $url = Url::to(['/compras/det-compras/delete', 'id_det_compra' => $model->id_det_compra, 'id_compra' => $model->id_compra]);
                            return $url;
                        }
                    }
                ],
            ];

            echo GridView::widget([
                'id' => 'kv-det-compras',
                'dataProvider' => $grid,
                'columns' => $gridColums,
                'containerOptions' => ['style' => 'overflow: auto'], //only set when $responsive = false
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'pjax' => true, //pjax is set to always true for this demo
                // set your toolbar
                'toolbar' => [],
                'toggleDataContainer' => ['class' => 'btn-group mr-2'],
                // set export properties
                // parameters from the demo form
                'bordered' => true,
                'striped' => true,
                'condensed' => true,
                'responsive' => true,
                'hover' => true,
                'showPageSummary' => true,
                'panel' => [
                    'type' => 'dark',
                    'heading' => '',
                ],
                'persistResize' => false,
            ]);
            ?>
        </div>
    </div>
</div>