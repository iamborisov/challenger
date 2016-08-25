<?php

$id = $id ? $id : uniqid('ql');

?>

<div id="<?= $id ?>" class="questions-list">
    <ul class="list well">
    </ul>

    <li class="template item-template item">
        #<span class="id"></span>
        <span class="text"></span>
        <a class="btn btn-danger remove pull-right btn-xs">Удалить</a>
    </li>

    <a id="<?= $id ?>-add"
       href="#"
       class="btn btn-success add pull-right"
       data-toggle="modal"
       data-target="<?= $modalSelector ?>"
    >Добавить задание</a>
</div>

<script>
    $(function () {
        $("#<?= $id ?>").questionsList({
            data: {
                items: <?= \yii\helpers\Json::encode($data) ?>,
                order: <?= \yii\helpers\Json::encode(array_keys($data)) ?>
            },
            modal: $('<?= $modalSelector ?>'),
            name: '<?= $name ?>'
        });
    });
</script>