<?php
$this->Html->script([
    '/scherersoftware/wiki/js/vendor/bootstrap-markdown-editor.js',
    '//cdnjs.cloudflare.com/ajax/libs/ace/1.2.3/ace.js',
    '//cdnjs.cloudflare.com/ajax/libs/marked/0.3.5/marked.min.js'
], ['block' => 'script']);
$this->Html->css([
    '/scherersoftware/wiki/css/vendor/bootstrap-markdown-editor.css'
], ['block' => 'css']);

?>

<?php
    echo $this->Form->input('parent_id', [
        'label' => __d('wiki', 'wiki_page.parent_id'),
        'options' => $parentWikiPages,
        'escape' => false,
        'empty' => '-'
    ]);
    echo $this->Form->input('title', ['label' => __d('wiki', 'wiki_page.title')]);
    echo $this->Attachments->attachmentsArea($wikiPage, [
        'label' => __d('wiki', 'wiki_page.attachments'),
        'formFieldName' => 'attachment_uploads',
        'additionalButtons' => function ($attachment) {
            return '<a class="btn btn-default btn-xs insert-image-btn" title="' . __d('wiki', 'wiki_pages.insert_image') . '" data-url="' . $attachment->downloadUrl() . '" data-filename="' . $attachment->filename . '"><i class="fa fa-fw fa-file-image-o"></i></a>';
        }
    ]);
?>
<div id="wiki-content">
    <?= h($wikiPage->content) ?>
</div>
<textarea name="content" id="content" style="display: none;"></textarea>
<hr>