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
<body id="default-layout">
    <header>
        <?php echo $this->Html->link(
            '<h1 style="color: transparent">Everlini' . $this->Html->image('everlini_wit.png', ["alt" => "Everlini logo"]) . '</h1>',
            ['controller' => 'Pages', 'action' => 'home'],
            ['escape' => false]
        );?>
        <?php if ($this->request->params['action'] == "index") : ?>
        <form>
        </form>
        <?php endif; ?>
        <div class="header__right">
            <a href="#" class="user-btn">
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
            </a>
            <ul class="user-dropdown">
                <li>
                    <?php echo $this->Html->link(
                        'logout<i class="fa fa-sign-out"></i>',
                        ['controller' => 'Users', 'action' => 'logout'],
                        ['escape'=>false]
                    );
                    ?>
                </li>
            </ul>
        </div>
    </header>
    <aside>
        <nav>
            <ul>
                <li><h3>Everlini</h3></li>
                <li>
                    <?php echo $this->Html->link(
                        '<i class="fa fa-calendar"></i> Events',
                        ['controller' => 'Events', 'action' => 'index'],
                        ['escape'=>false]
                    );
                    ?>
                </li>
                <li>
                    <?php echo $this->Html->link(
                        '<i class="fa fa-file-text-o"></i> Posts',
                        ['controller' => 'Posts', 'action' => 'index'],
                        ['escape'=>false]
                    );
                    ?>
                </li>
                <li>
                    <?php echo $this->Html->link(
                        '<i class="fa fa-user-o"></i> Profiles',
                        ['controller' => 'Profiles', 'action' => 'index'],
                        ['escape'=>false]
                    );
                    ?>
                </li>
                <li>
                    <?php echo $this->Html->link(
                        '<i class="fa fa-users"></i> Organisations',
                        ['controller' => 'Organisations', 'action' => 'index'],
                        ['escape'=>false]
                    );
                    ?>
                </li>
                <li>
                    <?php echo $this->Html->link(
                        '<i class="fa fa-user-o"></i> Admins',
                        ['controller' => 'Admins', 'action' => 'index'],
                        ['escape'=>false]
                    );
                    ?>
                </li>
                <li>
                    <?php echo $this->Html->link(
                        '<i class="fa fa-picture-o"></i> Media',
                        ['controller' => 'Attachments', 'action' => 'index'],
                        ['escape'=>false]
                    );
                    ?>
                </li>
                <li>
                    <?php echo $this->Html->link(
                        '<i class="fa fa-heart-o"></i> Interests',
                        ['controller' => 'Interests', 'action' => 'index'],
                        ['escape'=>false]
                    );
                    ?>
                </li>
                <li><h3>Accounts</h3></li>
                <li>
                    <?php echo $this->Html->link(
                        '<i class="fa fa-user-o"></i> Users',
                        ['controller' => 'Users', 'action' => 'index'],
                        ['escape'=>false]
                    );
                    ?>
                </li>
                <li>
                    <?php echo $this->Html->link(
                        '<i class="fa fa-key"></i> Roles',
                        ['controller' => 'Roles', 'action' => 'index'],
                        ['escape'=>false]
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
<script>
    let userbutton = document.querySelector('.user-btn');
    userbutton.addEventListener('click', e => {
        e.preventDefault(e);
        document.querySelector('.user-dropdown').classList.toggle('open');
    });
</script>
