<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Book $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'isbn')->textInput() ?>
 
    <?= $form->field($model, 'photoFile')->fileInput() ?>

    <?= $form->field($model, 'authors[]')->dropDownList(
        ArrayHelper::map($authors, 'id', 'name'), 
        [
            'multiple' => 'true',
            'options' => isset($selectedAuthors) ? array_fill_keys($selectedAuthors, ['selected' => true]) : []
        ]
    )->label('Авторы'); ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
