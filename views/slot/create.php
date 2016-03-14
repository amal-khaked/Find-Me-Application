<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Slot */

$this->title = 'Create Slot';
$this->params['breadcrumbs'][] = ['label' => 'Slots', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slot-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
