<?php $this->extend('default');
    $this->assign('title', 'Dashboard');
?>
<section class="stat-top">
    <h2>Statistics</h2>
    <article class="stat-numbers">
        <h2><?php echo $usersCount;?></h2>
        <h3>Registered users</h3>
    </article>
    <article class="stat-numbers">
        <h2><?php echo $eventsCount;?></h2>
        <h3>Upcoming events</h3></article>
    <article class="stat-numbers">
        <h2></h2>
        <h3>Most popular event</h3>
    </article>
</section>

