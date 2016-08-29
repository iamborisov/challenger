<?php $id = uniqid('qe_text_short') ?>

<div id="<?= $id ?>" class="select-one answer-editor-extension">
    <div class="content"></div>
    <div class="template content-template">
        <input type="text" class="form-control">
    </div>
</div>

<script>
    $(function () {
        $('#<?= $id ?>').answerEditorTextShort();
    });
</script>
