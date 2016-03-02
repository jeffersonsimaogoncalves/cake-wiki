<?php
use Michelf\MarkdownExtra;

$this->assign('title', $wikiPage->title . ' - Wiki');
$content = MarkdownExtra::defaultTransform($wikiPage->content);
$content = str_replace('<table>', '<table class="table">', $content);

?>

<h1 class="page-header">
    <?= h($wikiPage->title) ?>
    <div class="pull-right">
        <?= $this->CkTools->editButton($wikiPage) ?>
        <?= $this->ListFilter->backToListButton() ?>
    </div>
</h1>

<div class="row">
    <div class="col-md-9 wiki-page-content">
        <?= $content ?>
        <hr>
        <?php if (!empty($wikiPage->attachments)): ?>
            <?= $this->Attachments->attachmentsArea($wikiPage, [
                'label' => false,
                'mode' => 'readonly'
            ]); ?>
        <?php endif; ?>
    </div>
    <div class="col-md-3 page-tree">
        <?php
        $currentId = $wikiPage->id;
        echo $this->CkTools->nestedList($pageTree, '<a href="{{url}}">{{title}}</a>', 0, [
            'callback' => function($record) use ($currentId) {
                return $currentId == $record->id;
            }
        ]);
        ?>
    </div>
</div>