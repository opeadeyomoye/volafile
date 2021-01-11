<?php
/**
 * @var \App\View\AppView $this
 */
?>

<div class="flex justify-center">
    <div class="px-2 w-full max-w-4xl">
        <div class="bg-gray-900 rounded-md shadow-lg p-6">
            <?= $this->fetch('content') ?>
        </div>
    </div>
</div>
