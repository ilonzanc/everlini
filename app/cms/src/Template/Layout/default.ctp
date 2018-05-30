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

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Everlini - Dashboard -
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
    <script src="https://use.fontawesome.com/64d3cadbb4.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <?= $this->Html->css('main.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <header>
        <a href="/"><h1 style="color: transparent">Everlini<?= $this->Html->image('everlini_wit.png') ?></div></h1></a>
        <form>
            <input type="text" placeholder="<?= 'Zoeken in ' . $this->fetch('title')  . '...' ?>">
        </form>
        <div class="header__right">
            <?php if ($Auth->user('username')) :
            ?>
            <i class="fa fa-user"></i><?= h($Auth->user('username')); ?>
            <?php
            else :
                echo $this->Html->link(
                    'Inloggen',
                    ['controller' => 'Users', 'action' => 'login']
                );
            endif;
            ?>
        </div>
    </header>
    <aside>
        <nav>
            <ul>
                <li><a href="/">Dashboard</a></li>
                <li><h3>Everlini</h3></li>
                <li>
                    <?php echo $this->Html->link(
                        'Events',
                        ['controller' => 'Events', 'action' => 'index']
                    );
                    ?>
                </li>
                <li>
                    <?php echo $this->Html->link(
                        'Venues',
                        ['controller' => 'Venues', 'action' => 'index']
                    );
                    ?>
                </li>
                <li>
                    <?php echo $this->Html->link(
                        'Posts',
                        ['controller' => 'Posts', 'action' => 'index']
                    );
                    ?>
                </li>
                <li>
                    <?php echo $this->Html->link(
                        'Profiles',
                        ['controller' => 'Profiles', 'action' => 'index']
                    );
                    ?>
                </li>
                <li>
                    <?php echo $this->Html->link(
                        'Media',
                        ['controller' => 'Attachments', 'action' => 'index']
                    );
                    ?>
                </li>
                <li>
                    <?php echo $this->Html->link(
                        'Interests',
                        ['controller' => 'Interests', 'action' => 'index']
                    );
                    ?>
                </li>
                <li>
                    <?php echo $this->Html->link(
                        'Reviews',
                        ['controller' => 'Reviews', 'action' => 'index']
                    );
                    ?>
                </li>
                <li><h3>Accounts</h3></li>
                <li>
                    <?php echo $this->Html->link(
                        'Users',
                        ['controller' => 'Users', 'action' => 'index']
                    );
                    ?>
                </li>
                <li>
                    <?php echo $this->Html->link(
                        'Roles',
                        ['controller' => 'Roles', 'action' => 'index']
                    );
                    ?>
                </li>
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
