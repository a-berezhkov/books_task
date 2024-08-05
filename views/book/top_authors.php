<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\YearForm */
/* @var $topAuthors array */
/* @var $year int */

$this->title = 'Топ 10 авторов для года: ' . Html::encode($year);
$this->params['breadcrumbs'][] = ['label' => 'Форма рейтинга книг по году', 'url' => ['top-authors']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="top-authors">

    <h1><?= Html::encode($this->title) ?></h1>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Рейтинг</th>
                <th>ФИО автора</th>
                <th>Количество книг</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($topAuthors as $index => $author) : ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= Html::encode($author['name']) ?></td>
                    <td><?= Html::encode($author['book_count']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p>
        <?= Html::a('Назад к поиску!', ['top-authors'], ['class' => 'btn btn-primary']) ?>
    </p>
</div>