<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SlotSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="slot-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'slotID') ?>

    <?= $form->field($model, 'dayID') ?>

    <?= $form->field($model, 'agendaID') ?>

    <?= $form->field($model, 'content') ?>

    <?= $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'slotnum') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
