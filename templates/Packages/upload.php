<?php

/**
 * @var \App\View\AppView $this
 * @var \Cake\Form\Form $form
 */

$this->extend('/shell');

$this->Html->script('filesize.min', ['block' => 'bodyScript', 'once' => true]);
$this->Html->script('upload-package', ['block' => 'bodyScript', 'once' => true]);
?>

<div class="grid grid-cols-1 sm:min-h-80 md:grid-cols-2">
    <?= $this->Form->create($form, ['enctype' => 'multipart/form-data']) ?>
    <div id="initialUploadArea" class="h-full flex justify-center items-center px-6 pt-5 pb-6 border-2 border-gray-600 border-dashed rounded-md">
        <div class="space-y-1 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="flex text-base text-gray-400">
                <label for="file-upload" class="relative cursor-pointer rounded-md font-medium text-indigo-300 hover:text-indigo-200 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                    <span>Upload a file</span>
                    <?= $this->Form->file('file', [
                        'id' => 'file-upload',
                        'class' => 'sr-only',
                        'onChange' => 'changeFile()'
                    ]) ?>
                </label>
                <p class="pl-1">or drag and drop</p>
            </div>
            <p class="text-sm text-gray-500">
                PNG, JPG, ZIP up to 10MB
            </p>
            <div class="pt-8">
                <button onclick="selectFile()" type="button" class="px-4 py-2 rounded text-grey-300 bg-indigo-500">
                    Select file
                </button>
            </div>
        </div>
    </div>

    <div id="previewArea" class="hidden flex flex-col justify-between h-full">
        <div>
            <div class="p-4 bg-gray-800 rounded">
                <div class="w-full h-full flex items-center bg-gray-900 rounded p-2">
                    <svg class="h-12 w-auto text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <div class="ml-4 leading-snug">
                        <span id="filename" class="text-gray-200">...</span><br />
                        <span id="fileSize" class="text-sm text-gray-500">0 Bytes</span>
                    </div>
                    <button onclick="removeFile()" title="Delete" type="button" class="ml-auto px-2 rounded-full text-2xl text-gray-500 hover:text-gray-600">
                        &times;
                    </button>
                </div>
            </div>

            <div class="mt-6">
                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <input onchange="toggleKeySection()" id="useKey" name="" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="useKey" class="font-medium text-gray-200">Protect with password</label>
                    </div>
                </div>
                <div id="keySection" class="hidden mt-3">
                    <?= $this->Form->password('key', [
                        'id' => 'password',
                        'required' => false,
                        'class' => 'w-full max-w-56 px-2 py-1 bg-gray-700 focus:border-gray-600 border-gray-700 rounded'
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="pt-8">
            <div class="mt-4">
                <button class="w-full py-3 rounded text-center text-gray-100 font-semibold bg-indigo-500" type="submit">
                    Upload
                </button>
            </div>
        </div>
    </div>
    <?= $this->Form->end() ?>

    <div class="mt-12 md:mt-0 md:ml-8">
        <h2 class="text-2xl font-bold">Simple, ephemeral file sharing.</h2>

        <p class="mt-4 text-sm leading-relaxed">
            volafile lets you share files privately. You can upload a file and get a unique download link
            that's known only to you and other people you might share it with.
        </p>

        <p class="mt-4 text-sm leading-relaxed">
            All uploads expire after 24 hours, and you can optionally add another layer of protection
            by locking your uploads with a password.
        </p>
    </div>
</div>