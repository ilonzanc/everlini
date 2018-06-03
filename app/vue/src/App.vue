<template>
  <div id="app" v-on:save-session="onSaveSession">
    <header :class="{ 'transparent-header': isHome }">
		<router-link to="/"><img src="./assets/images/logo.svg" class="logo"></router-link>
		<a href="#" class="toggleBars" @click.prevent="toggleNav">
			<img src="./assets/icons/hamburger_icon.svg">
		</a>
      <nav :class="['main-nav ', { 'nav-open': navOpen }]">
        <ul>
          <li><router-link to="/">Home</router-link></li>
          <li v-if="session != null"><router-link to="/profiel">Jouw profiel</router-link></li>
          <li><router-link to="/about">About</router-link></li>
          <li><router-link to="/contact">Contact</router-link></li>
          <li v-if="session != null"><a href="#" class="btn secundary-btn" @click.prevent="logOut">Uitloggen</a></li>
          <li v-if="session == null"><router-link to="/aanmelden" class="btn secundary-btn">Aanmelden</router-link></li>
        </ul>
      </nav>
    </header>
    <main>
      	<router-view></router-view>
    </main>
    <footer>
      <div class="container">
        <nav class="footer-nav">
          <ul>
            <li><router-link to="/about">About</router-link></li>
            <li><router-link to="/contact">Contact</router-link></li>
            <li><router-link to="/privacy-policy">Privacy Policy</router-link></li>
            <li><router-link to="/disclaimer">Disclaimer</router-link></li>
          </ul>
        </nav>
      </div>
    </footer>
  </div>
</template>

<script>
import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from "axios";
import moment from 'moment';

import routes from './router/index.js';

Vue.use(VueRouter);
Vue.use(require('vue-moment'));

export default {
  name: 'app',
  data () {
    return {
      session: {
        id: ""
      },
      fbAccesToken: "",
      navOpen: false,
      isHome: false
    }
  },
  watch: {
    $route() {
      this.navOpen = false;
      this.checkIfHome();
    }
  },
  mounted () {
    this.session = JSON.parse(localStorage.getItem("user"));
    this.checkIfHome();
  },
  methods: {
    onSaveSession: function (user) {
      console.log('click');
      data.session = user;
    },
    toggleNav() {
      if (this.navOpen == false) {
        this.navOpen = true;
      }
      else {
        this.navOpen = false;
      }
    },
    closeNav() {
      this.navOpen = false;
    },
    logOut() {
      localStorage.removeItem("user");
      this.session = null;
      this.navOpen = false;
      this.$router.push('/');
    },
    checkIfHome() {
      console.log("Check if home....");
      if(this.$route.name == "home") {
        this.isHome = true;
      }
      else {
        this.isHome = false;
      }
    }
  }
}
</script>

<style type="text/css" src="./assets/css/main.css"></style>