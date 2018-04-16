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
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;

$this->layout = false;

if (!Configure::read('debug')) :
    throw new NotFoundException(
        'Please replace src/Template/Pages/home.ctp with your own version or re-enable debug mode.'
    );
endif;

$cakeDescription = 'CakePHP: the rapid development PHP framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
    <script src="https://use.fontawesome.com/64d3cadbb4.js"></script>
    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('main.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <header>
        <h1 style="color: transparent">Everlini<?= $this->Html->image('everlini_wit.png') ?></div></h1>
        <form>
            <input type="text" placeholder="<?= 'Zoeken in ' . $this->fetch('title')  . '...' ?>">
        </form>
        <div class="header__right"><i class="fa fa-user"></i><?= h($Auth->user('username')); ?></div>
    </header>
    <aside>
        <nav>
            <ul>
                <li><a href="/">Dashboard</a></li>
                <li><h3>Everlini</h3></li>
                <li><a href="/">Events</a></li>
                <li><a href="/">Venues</a></li>
                <li><a href="/">Posts</a></li>
                <li><a href="/">Profiles</a></li>
                <li><a href="/">Media</a></li>
                <li><a href="/">Interests</a></li>
                <li><a href="/">Reviews</a></li>
                <li><a href="/">Interests</a></li>
                <li><h3>Accounts</h3></li>
                <li><a href="/users">Users</a></li>
                <li><a href="/roles">Roles</a></li>
            </ul>
        </nav>
    </aside>
    <main>
        <?= $this->Flash->render() ?>
        <h1><a href=""><?= $this->fetch('title') ?></a></h1>
        <?= $this->fetch('content') ?>
    </main>
</body>
</html>
