<?php

use yii\helpers\Json;

$id = uniqid('ae');

?>

<div id="<?= $id ?>" class="answer-editor">
    <?php foreach ($types as $sysname): ?>
        <?= $this->render($sysname) ?>
    <?php endforeach; ?>
</div>

<input id="input-<?= $id ?>"
       type="hidden"
       name="<?= $name ?>"
/>

<script>
    $(function () {
        $('#<?= $id ?>').answerEditor({
            input: '#input-<?= $id ?>',
            types: <?= Json::encode($types) ?>,
            type: '<?= $type ?>',
            data: <?= Json::encode($data) ?>
        });
    });
</script>
