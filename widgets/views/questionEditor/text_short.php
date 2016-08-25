<?php $id = uniqid('qe_text_short') ?>

<div id="<?= $id ?>" class="select-one question-editor-extension">
    <div class="content"></div>
    <div class="template content-template">
        <label>Ответ:</label>
        <input type="text" class="form-control">
    </div>
</div>

<script>
    $(function(){
        $('#<?= $id ?>').questionEditorTextShort();
    });
</script>
