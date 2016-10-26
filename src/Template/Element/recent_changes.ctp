<?php
use Cake\Core\Configure;
?>
<?php if (Configure::read('Wiki.useModelHistory')): ?>
    <hr>
    <h4><?= __d('wiki', 'wiki_pages.recent_changes') ?></h4>
    <ul>
        <?php foreach ($recentChanges as $change): ?>
            <li>
                <span class="label label-default">
                    <?= $change->action ?>
                </span>
                &nbsp;<?= $change->created ?>&nbsp;-&nbsp;
                <strong>
                    <?= (!empty($change->user)) ? $change->user->full_name : __d('wiki', 'wiki_pages.recent_changes.unknown_user') ?>
                </strong>
                &nbsp;in&nbsp;
                <?= $this->Html->link($change->wiki_page->title, $change->wiki_page->url) ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif;  ?>
