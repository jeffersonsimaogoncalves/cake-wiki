<?php
$this->Html->script([
    'vendor/bootstrap-markdown-editor.js',
    '//cdnjs.cloudflare.com/ajax/libs/ace/1.2.0/ace.js',
    '//cdnjs.cloudflare.com/ajax/libs/marked/0.3.2/marked.min.js'
], ['block' => 'script']);
$this->Html->css([
    'vendor/bootstrap-markdown-editor.css'
], ['block' => 'css']);

?>

<?php
    echo $this->Form->input('parent_id', [
        'label' => 'Parent',
        'options' => $parentWikiPages,
        'escape' => false,
        'empty' => '-'
    ]);
    echo $this->Form->input('title', ['label' => 'Titel']);
    echo $this->Attachments->attachmentsArea($wikiPage, [
        'label' => 'Dateien',
        'formFieldName' => 'attachment_uploads'
    ]);
?>
<div id="wiki-content">
    <?= h($wikiPage->content) ?>
</div>
<textarea name="content" id="content" style="display: none;"></textarea>
<hr>