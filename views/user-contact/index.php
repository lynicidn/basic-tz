<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserContactSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User contacts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p class="well well-sm">
        <?= Html::a('', ['create'], ['class' => 'btn btn-success glyphicon glyphicon-plus']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            'phone',
            'email:email',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>

</div>
