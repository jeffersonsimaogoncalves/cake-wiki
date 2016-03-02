<h1 class="page-header">
    Bearbeiten
    <div class="pull-right">
        <?= $this->ListFilter->backToListButton() ?>
        <?= $this->CkTools->viewButton($wikiPage, ['title' => 'Seite anzeigen', 'url' => $wikiPage->url]) ?>
    </div>
</h1>


<div class="wikiPages form">
    <?= $this->Form->create($wikiPage, ['horizontal' => true, 'novalidate']); ?>
    <fieldset>
        <?= $this->element('../WikiPages/form') ?>
    </fieldset>
    <div class="submit-group">
        <?= $this->Form->button(__('forms.save'), ['class' => 'btn-success']) ?>
        <small><?= __('or') ?></small>
        <?= $this->Html->link(__('forms.back_to_list'), ['action' => 'index']) ?>
    </div>
    <?= $this->Form->end() ?>
</div>

<hr>
<div class="clearfix">
    <div class="pull-right">
        <?= $this->CkTools->deleteButton($wikiPage, ['usePostLink' => true]) ?>
    </div>
</div>
