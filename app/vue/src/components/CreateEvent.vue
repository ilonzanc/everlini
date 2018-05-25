<template>
<div id="create-event">
    <div class="container">
        <h1>Nieuw event toevoegen</h1>
        <form method="POST" action="" @submit.prevent="onSubmit">
            <input type="text" id="user_id" name="user_id" required v-model="event.user_id">
            <label for="name">Naam Event</label>
            <input type="text" id="name" name="name" required v-model="event.name">
            <label for="name">Naam Event</label>
            <input type="text" id="name" name="name" required v-model="event.name">
        </form>
    </div>
</div>
</template>

<script>
import axios from 'axios';
export default {

}
</script>

<style lang="scss">
</style>