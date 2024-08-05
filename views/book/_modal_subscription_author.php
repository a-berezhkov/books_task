<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\bootstrap5\Modal;
use yii\helpers\Url;

$uniqueId = uniqid();
?>
<p>
    <?= Html::a('Подписаться на книги автора: ' . Html::encode($model->name), '#', ['id' => 'subscribe-link' . $uniqueId]) ?>
</p>
<?php
Modal::begin([
    'title' => $model->name,
    'id' => 'subscribe-modal' . $uniqueId,
    'toggleButton' => false,
]);
$form = ActiveForm::begin([
    'id' => 'subscribe-form' . $uniqueId,
    'action' => Url::to(['book/subscribe']),
    // 'enableAjaxValidation' => true,
]);

echo $form->field($subscriptionForm, 'author_id')->hiddenInput(['value' => $model->id])->label(false);
echo $form->field($subscriptionForm, 'phone')->textInput(['maxlength' => true]);

echo Html::submitButton('Подписаться', ['class' => 'btn btn-primary']);
ActiveForm::end();
Modal::end();
?>

<?php

$script = <<<JS
$("#subscribe-link$uniqueId").on('click', function(event) {
    event.preventDefault();
    $("#subscribe-modal$uniqueId").modal('show');
});
$("#subscribe-form$uniqueId").submit(function(e) {

    e.preventDefault();
});
$("#subscribe-form$uniqueId").on('beforeSubmit', function(e) {
    e.preventDefault()

    let form = $(this);
 

    $.ajax({
        url:  form.attr('action'),
        type: 'post',
        data: form.serialize(),
        success: function(response) {
          console.log(response); 
            if (response.success) {
                $("#subscribe-modal$uniqueId").modal('hide');
                alert('Вы успешно подписались!');
            } else {
                if (response.message) {
                    alert(response.message);
                }
                else {
                    console.log(response.errors);
                    alert('Что - то пошло не так, обратитесь к администратору');
                }
              
            }
        }
    });

    return false;
});
JS;
$this->registerJs($script);
?>