<?php $id = uniqid('qe_dictation') ?>

<div id="<?= $id ?>" class="select-one answer-editor-extension">
    <div class="content"></div>
    <div class="template content-template">
        not implemented yet
    </div>
</div>

<script>
    $(function () {
        $('#<?= $id ?>').answerEditorDictation();
    });
</script>
