<?php

use app\models\Departamentos;
use kartik\depdrop\DepDrop;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\editors\Summernote;
use kartik\select2\Select2;
use kartik\widgets\SwitchInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\modules\compras\models\Proveedores $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-dark">
            <div class="card-header">
                <h3 class="card-title">Crear / Editar registro</h3>
            </div>
            <?php $form = ActiveForm::begin(['id' => 'crear-form'], ['options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="card-body">
                <form role="form">
                    <div class="row">
                        <div class="col-md-6">
                            <?= Html::activeLabel($model, 'nombre', ['class' => 'control-label']) ?>
                            <?= $form->field($model, 'nombre', ['showLabels' => false])->textInput(['autofocus' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= Html::activeLabel($model, 'codigo', ['class' => 'control-label']) ?>
                            <?= $form->field($model, 'codigo', ['showLabels' => false])->textInput() ?>
                        </div>
                        <div class="col-md-12">
                            <?= Html::activeLabel($model, 'direccion', ['class' => 'control-label']) ?>
                            <?= $form->field($model, 'direccion', ['showLabels' => false])->widget(Summernote::class, [
                                'useKrajeePresets' => false,
                                'container' => [
                                    'class' => 'kv-editor-container',
                                ],
                                'pluginOptions' => [
                                    'height' => 200,
                                    'dialogsFade' => true,
                                    'toolbar' => [
                                        ['style1', ['style']],
                                        ['style2', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript']],
                                        ['font', ['fontname', 'fontsize', 'color', 'clear']],
                                        ['para', ['ul', 'ol', 'paragraph', 'height']],
                                        ['insert', ['link', 'table', 'hr']],
                                    ],
                                    'fontSizes' => ['8', '9', '10', '11', '12', '13', '14', '16', '18', '20', '24', '36', '48'],
                                    'placeholder' => 'Escribir direccion del proveedor...',
                                ]
                            ]); ?>
                        </div>
                        <div class="col-md-6">
                            <?= Html::activeLabel($model, 'id_departamento', ['class' => 'control-label']) ?>
                            <?= $form->field($model, 'id_departamento', ['showLabels' => false])->widget(Select2::class, [
                                'data' => ArrayHelper::map(Departamentos::find()->all(), 'id_departamento', 'nombre'),
                                'language' => 'es',
                                'options' => ['placeholder' => '- Seleccionar Departamento -'],
                                'pluginOptions' => ['allowClear' => true],
                            ]); ?>
                        </div>
                        <div class="col-md-6">
                            <?= Html::hiddenInput('model_id1', $model->isNewRecord ? '' : $model->id_departamento, ['id' => 'model_id1']); ?>
                            <?= Html::activeLabel($model, 'id_municipio', ['class' => 'control-label']) ?>
                            <?= $form->field($model, 'id_municipio', ['showLabels' => false])->widget(DepDrop::class, [
                                'language' => 'es',
                                'type' => DepDrop::TYPE_SELECT2,
                                'pluginOptions' => [
                                    'depends' => ['proveedores-id_departamento'],
                                    'initialize' => $model->isNewRecord ? false : true,
                                    'url' => Url::to(['/compras/default/municipios']),
                                    'placeholder' => '- Seleccionar Municipio -',
                                    'loadingText' => 'Cargando datos...',
                                    'params' => ['model_id1']
                                ]
                            ]); ?>
                        </div>
                        <div class="col-md-6">
                            <?= Html::activeLabel($model, 'telefono', ['class' => 'control-label']) ?>
                            <?= $form->field($model, 'telefono', ['showLabels' => false])->textInput() ?>
                        </div>
                        <div class="col-md-6">
                            <?= Html::activeLabel($model, 'email', ['class' => 'control-label']) ?>
                            <?= $form->field($model, 'email', ['showLabels' => false])->textInput() ?>
                        </div>
                        <div class="col-md-12">
                            <?= Html::activeLabel($model, 'estado', ['class' => 'control-label']) ?>
                            <?php
                            echo $form->field($model, 'estado', ['showLabels' => false])->widget(SwitchInput::class, [
                                'pluginOptions' => [
                                    'handleWitdth' => 80,
                                    'onColor' => 'success',
                                    'offColor' => 'danger',
                                    'onText' => '<i class="fa fa-check"></i> Activo',
                                    'offText' => '<i class="fa fa-ban"></i> Inactivo'
                                ]
                            ]);
                            ?>
                        </div>
                    </div>
                    <div class="card-footer">
                        <?= Html::submitButton(
                            $model->isNewRecord ? '<i class="fa fa-save"></i> Guardar' : '<i class="fa fa-save"></i> Actualizar',
                            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => 'submit-button']
                        ) ?>
                        <?= Html::a('<i class="fa fa-ban"></i> Cancelar', ['index'], ['class' => 'btn btn-danger']) ?>
                    </div>
                </form>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>