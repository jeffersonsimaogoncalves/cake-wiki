<?php
$this->assign('title', 'Wiki');
?>
<h1 class="page-header">
    Wiki
    <div class="pull-right">
        <?= $this->CkTools->addButton('Neue Seite anlegen') ?>
    </div>
</h1>

<div class="page-tree">
<?= $this->CkTools->nestedList($pageTree, '<a href="{{url}}">{{title}}</a>'); ?>
</div>


<hr>
<h4>Letzte Änderungen</h4>
<ul>
    <?php foreach($recentChanges as $change): ?>
        <li>
            <span class="label label-default"><?= $change->action ?></span> <?= $change->created ?> - <strong><?= $change->user->full_name ?></strong> in <?= $this->Html->link($change->wiki_page->title, $change->wiki_page->url) ?> 
        </li>
    <?php endforeach; ?>
</ul>