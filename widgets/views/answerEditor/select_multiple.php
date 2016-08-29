<?php $id = uniqid('qe_select_multiple') ?>

<div id="<?= $id ?>" class="select-multiple answer-editor-extension">
    <div class="content"></div>
    <div class="template content-template">
        <div class="items"></div>
    </div>
    <div class="template item-template item row">
        <div class="col-md-1"><input type="checkbox" class="pull-right"></div>
        <div class="col-md-10"><span class="text"></span></div>
    </div>
</div>

<script>
    $(function () {
        $('#<?= $id ?>').answerEditorSelectMultiple();
    });
</script>
