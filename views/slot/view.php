<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Slot */

$this->title = $model->slotID;
$this->params['breadcrumbs'][] = ['label' => 'Slots', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slot-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->slotID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->slotID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'slotID',
            'dayID',
            'agendaID',
            'content',
            'type',
            'date',
            'slotnum',
        ],
    ]) ?>

</div>
