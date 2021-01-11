<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

use Cake\Core\Configure;

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        volafile - Simple, ephemeral file-sharing.
        <?php // $this->fetch('title')
        ?>
    </title>

    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <?= $this->Html->css('app.min') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>

<body class="bg-gray-800 text-white">
    <header class="w-full flex justify-center pt-8 md:pt-12">
        <a href="<?= $this->Url->build('/') ?>" class="text-2xl font-semibold tracking-wide text-gray-300 md:text-3xl">
            volafile
        </a>
    </header>

    <main class="mt-12">
        <?= $this->Flash->render() ?>
        <?= $this->fetch('content') ?>
    </main>

    <footer>
    </footer>

    <?= $this->fetch('bodyScript') ?>
</body>

</html>
