<?php
use Cake\Core\Configure;

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


<div class="page-tree">
    <?= $this->CkTools->nestedList($pageTree, '<a href="{{url}}">{{title}}</a>'); ?>
</div>


<?php if (Configure::read('Wiki.useModelHistory')): ?>
    <hr>
    <h4><?= __d('wiki', 'wiki_pages.recent_changes') ?></h4>
    <ul>
        <?php foreach($recentChanges as $change): ?>
            <li>
                <span class="label label-default"><?= $change->action ?></span> <?= $change->created ?> - <strong><?= $change->user->full_name ?></strong> in <?= $this->Html->link($change->wiki_page->title, $change->wiki_page->url) ?> 
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif;  ?>