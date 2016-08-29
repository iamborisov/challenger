<?php $id = uniqid('qe_text_long') ?>

<div id="<?= $id ?>" class="select-one answer-editor-extension">
    <div class="content"></div>
    <div class="template content-template">
        <textarea class="form-control"></textarea>
    </div>
</div>

<script>
    $(function () {
        $('#<?= $id ?>').answerEditorTextLong();
    });
</script>
