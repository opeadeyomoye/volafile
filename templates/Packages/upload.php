<?php

/**
 * @var \App\View\AppView $this
 * @var \Cake\Form\Form $form
 */
?>

<div class="flex justify-center">
    <div class="px-2 w-full max-w-4xl">
        <div class="bg-gray-900 rounded-md shadow-lg p-6">
            <div class="grid grid-cols-1 sm:min-h-80 md:grid-cols-2">
                <?= $this->Form->create($form, ['enctype' => 'multipart/form-data']) ?>
                    <div class="h-full flex justify-center items-center px-6 pt-5 pb-6 border-2 border-gray-600 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-base text-gray-400">
                                <label for="file-upload" class="relative cursor-pointer rounded-md font-medium text-indigo-300 hover:text-indigo-200 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>Upload a file</span>
                                    <?= $this->Form->file('file', [
                                        'id' => 'file-upload',
                                        'class' => 'sr-only'
                                    ]) ?>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-sm text-gray-500">
                                PNG, JPG, ZIP up to 10MB
                            </p>
                            <div class="pt-8">
                                <button role="button" class="px-4 py-2 rounded text-grey-300 bg-indigo-500">
                                    Select file
                                </button>
                            </div>
                        </div>
                    </div>
                <?= $this->Form->end() ?>

                <div class="mt-12 md:mt-0 md:ml-8">
                    <h2 class="text-2xl font-bold">Simple, private file sharing.</h2>
                </div>
            </div>
        </div>
    </div>
</div>
