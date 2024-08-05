<?php

use app\models\Subscription;
use yii\helpers\Html;
use yii\helpers\VarDumper;

/* @var $model app\models\Book */

$isAuthor =  (\Yii::$app->user->id) ? $model->created_by === \Yii::$app->user->id : false;
 
?>

<div class="card mb-4 shadow-sm">
    <?php if ($model->photo_url) : ?>
        <?= Html::img($model->photo_url, ['class' => 'card-img-top', 'alt' => $model->title]) ?>
    <?php else : ?>
        <div class="card-img-top" style="background-color: #f0f0f0; height: 200px;"></div>
    <?php endif; ?>

    <div class="card-body">
        <h5 class="card-title"><?= Html::encode($model->title) ?></h5>
        <?php if ($isAuthor) {
            echo  Html::a('Редактировать', ['book/update', 'id' => $model->id], ['class' => 'btn btn-primary']);
            echo Html::a('Удалить', ['book/delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить эту книгу?',
                    'method' => 'post',
                ],
            ]);
        }  ?>
        <a href="<?= \yii\helpers\Url::to(['book/view', 'id' => $model->id]) ?>" class="btn btn-primary">Подробнее</a>
    </div>

    <div class="card-footer">
        <?php
        foreach ($model->authors as $author) {
            echo $this->render('_modal_subscription_author', [
                'model' => $author,
                'subscriptionForm' => new Subscription(),
            ]);
        }
        ?>
    </div>

</div>