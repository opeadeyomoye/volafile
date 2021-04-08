<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Domain\Core\Package $package
 */

$packageLink = $this->Url->build([
    'action' => 'download',
    $package->id,
    '?' => ['ac' => urlencode($package->accessCode)]
], ['fullBase' => true]);

$this->extend('/shell');
?>

<div class="py-5">
    <div class="w-full flex justify-center">
        <svg class="max-w-1/5 h-auto text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
        </svg>
    </div>

    <div class="mt-8 max-w-lg mx-auto md:mt-12">
        <p class="text-gray-200 text-center">
            Great! Your file was successfully uploaded. You can share the link below to allow others access it.
        </p>

        <div class="flex justify-center">
            <form class="mt-8 sm:flex">
                <label for="downloadLink" class="sr-only">Download link</label>
                <input disabled value="<?= $packageLink ?>" id="downloadLink" name="link" type="text" class="w-full px-5 py-3 bg-gray-600 focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs border-gray-300 rounded-md" />
                <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3 sm:flex-shrink-0">
                    <button onclick="copyLink(this);" type="button" class="w-full flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Copy
                    </button>
                </div>
            </form>
        </div>

        <p class="mt-3 max-w-5/6 mx-auto text-gray-500 text-center text-xs md:text-sm">
            Make sure you trust your recipient when sharing sensitive data.
        </p>
    </div>
</div>

<?php $this->start('bodyScript') ?>
<script type="text/javascript">
    const linkElement = document.getElementById('downloadLink');

    const copyLink = (button) => {
        const el = document.createElement('textarea');

        el.value = linkElement.value;
        document.body.appendChild(el);

        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);

        const defaultText = button.innerText;
        button.innerText = 'Copied!';

        setTimeout(() => button.innerText = defaultText, 3000);
    };
</script>
<?php $this->end() ?>
