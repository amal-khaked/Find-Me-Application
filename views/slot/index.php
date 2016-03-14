<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SlotSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Slots';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slot-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Slot', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'slotID',
            'dayID',
            'agendaID',
            'content',
            'type',
            // 'date',
            // 'slotnum',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
