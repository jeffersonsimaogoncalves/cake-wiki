<?php
$this->assign('title', 'Wiki');
?>
<h1 class="page-header">
    <?= __d('wiki', 'wiki_pages.index') ?>
    <div class="pull-right">
        <?= $this->CkTools->addButton() ?>
    </div>
</h1>

    <?= $this->ListFilter->openForm(); ?>
    <?= $this->ListFilter->filterWidget('WikiPages.fulltext', [
        'inputOptions' => [
            'label' => false,
            'placeholder' => __d('wiki', 'wiki_pages.search'),
            'prepend' => '<i class="fa fa-search"></i>',
            'append' => $this->ListFilter->resetButton(null, ['class' => ''])
        ]
    ]) ?>
    <?= $this->ListFilter->closeForm(false, false); ?>

<?php if (empty($pageTree)) : ?>
    <div class="alert alert-info" role="alert"><?= __d('wiki', 'wiki_pages.nothing_found') ?></div>
<?php else: ?>
    <div class="page-tree">
        <?= $this->CkTools->nestedList($pageTree, '<a href="{{url}}">{{title}}</a>'); ?>
    </div>
<?php endif; ?>


<?= $this->element('recent_changes', compact('recentChanges')) ?>
