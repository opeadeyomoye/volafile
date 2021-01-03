<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Form\Form $form
 */

?>
<p class="text-lg">Hello World!</p>

<div class="mt-6">

<?= $this->Form->create($form, ['enctype' => 'multipart/form-data']) ?>
<?= $this->Form->label('file', __('Upload a file')) ?>
<?= $this->Form->control('file', ['type' => 'file']) ?>
<?= $this->Form->button('Submit') ?>
<?= $this->Form->end() ?>

</div>
