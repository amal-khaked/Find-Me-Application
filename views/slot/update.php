<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Slot */

$this->title = 'Update Slot: ' . ' ' . $model->slotID;
$this->params['breadcrumbs'][] = ['label' => 'Slots', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->slotID, 'url' => ['view', 'id' => $model->slotID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="slot-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
