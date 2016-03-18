
<h1 class="page-header">
    <?= __('wiki_pages.add') ?>
    <div class="pull-right">
        <?= $this->ListFilter->backToListButton() ?>
    </div>
</h1>


<div class="wikiPages form">
    <?= $this->Form->create($wikiPage, ['align' => 'horizontal', 'novalidate']); ?>
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
