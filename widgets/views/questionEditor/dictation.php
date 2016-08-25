<?php $id = uniqid('qe_dictation') ?>

<div id="<?= $id ?>" class="select-one question-editor-extension">
    <div class="content"></div>
    <div class="template content-template">
        not implemented yet
    </div>
</div>

<script>
    $(function(){
        $('#<?= $id ?>').questionEditorDictation();
    });
</script>
