<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserContact */

$this->title = 'Create user contact';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-contact">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', ['model' => $model])?>

</div>
