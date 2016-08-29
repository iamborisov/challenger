<?php $id = uniqid('qe_select_one') ?>

<div id="<?= $id ?>" class="select-one answer-editor-extension">
    <div class="content"></div>
    <div class="template content-template">
        <div class="items"></div>
    </div>
    <div class="template item-template item row">
        <div class="col-md-1"><input type="radio" name="<?= $id ?>" class="pull-right"></div>
        <div class="col-md-10"><span class="text"></span></div>
    </div>
</div>

<script>
    $(function () {
        $('#<?= $id ?>').answerEditorSelectOne();
    });
</script>
