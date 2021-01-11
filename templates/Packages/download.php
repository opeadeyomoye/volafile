<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Domain\Core\Package $package
 * @var \App\Domain\Core\File[] $files
 * @var bool $requiresKey
 */

$this->extend('/shell');
$count = count($files);
?>

<?php if ($requiresKey) : ?>
    <div class="py-4 md:py-8">
        <div class="flex justify-center">
            <svg class="w-1/6 h-auto text-gray-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>

        <p class="mt-4 max-w-4/5 mx-auto text-center md:text-base md:mt-6">
            A password is required to access these files.
        </p>

        <div class="flex justify-center mt-4 md:mt-6">
            <?= $this->Form->create(null, ['method' => 'post', 'class' => 'sm:flex']) ?>
            <label for="password" class="sr-only">Enter password</label>
            <input id="password" name="key" type="password" class="w-full px-5 py-3 bg-gray-700 text-center md:text-left focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs border-gray-300 rounded-md" placeholder="Password" />
            <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3 sm:flex-shrink-0">
                <button onclick="copyLink(this);" type="button" class="w-full flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Unlock
                </button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>

<?php else : ?>
    <div class="w-full max-w-md mx-auto">
        <p class="text-center"><?= $count ?> file<?= $count === 1 ? '' : 's' ?> shared:</p>
        <div class="p-4">
            <div class="-mt-4">
                <?php foreach ($files as $file) : ?>
                    <div class="mt-4 grid grid-cols-4 gap-x-4 md:gap-x-6">
                        <div class="col-span-3 p-4 bg-gray-800 rounded text-gray-200 truncate">
                            <?= $file->name() ?>
                        </div>
                        <div class="text-sm text-center flex items-center">
                            <?= $this->Html->link(__('Download'), [
                                'action' => 'downloadFile',
                                $package->id(),
                                $file->id()
                            ], [
                                'class' => 'text-indigo-200 hover:text-indigo-300 transition focus:underline'
                            ]) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="mt-6 flex h-full justify-center">
        <?= $this->Html->link(__('Share files privately'), '/', [
            'class' => 'underline text-gray-500 hover:text-gray-400'
        ]) ?>
    </div>
<?php endif; ?>
