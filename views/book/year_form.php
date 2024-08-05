<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\YearForm */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Топ 10 книг за год';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="year-form">

    <h1><?= Html::encode($this->title) ?></h1>

    <p> Введите год :</p>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Получить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>