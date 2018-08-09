<template>
  <div id="dashboard" class="content">
    <div class="container">
      <router-link to="/dashboard" class="breadcrumb">terug naar dashboard</router-link>
      <section class="dashboard-actions">
        <a href="#" class="btn primary-btn small-btn" @click.prevent="showAction($event, $route.params.id + '/wijzig/')"><i class="fa fa-pencil"></i> Wijzig info</a>
        <a href="#" class="btn primary-btn small-btn" @click.prevent="showAction($event, $route.params.id + '/nieuw-event/')"><i class="fa fa-plus"></i> nieuw event</a>
      </section>
      <h1>Dashboard</h1>
      <h2>{{ organisation.name }}</h2>
      <section class="organisation-events">
        <h2>Events</h2>
        <ul class="organisation-events-list">
          <li class="organisation-event" v-bind:key="event.id" v-for="event in events">
            <router-link :to="{ path: 'events/' + event.id }" append>
              <article class="card">
                <h3>{{ event.name }}</h3>
              </article>
            </router-link>
          </li>
        </ul>
      </section>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: "organisation-dashboard",
  data () {
    return {
      organisation: { },
      events: [],
    }
  },
  mounted() {
    console.log("Profile Vue Component mounted");
    axios({
      method: 'get',
      url: apiurl + "/api/organisations/" + this.$parent.session.id + ".json",
    })
    .then(response => {
      this.organisation = response.data.organisation;
    })
    .catch(error => {
    });

    axios({
      method: 'get',
      url: apiurl + "/api/events/organisation/" + this.$route.params.id + ".json",
    })
    .then(response => {
      this.events = response.data;
    })
    .catch(error => {
    });
  },
  created: function () {
    window.addEventListener('scroll', this.handleScroll);
  },
  destroyed: function () {
    window.removeEventListener('scroll', this.handleScroll);
  },
  methods: {
    showAction(event, link) {
      let actionButtons = document.querySelectorAll('a.open');

      let eventElement;
      if (event.target.tagName == 'I') {
        eventElement = event.target.parentNode;
      } else if (event.target.tagName == 'A') {
        eventElement = event.target;
      }

      console.log(event.target.tagName);

      console.log(eventElement.classList.value);
      if (event) {
        if (eventElement.classList.value == "btn primary-btn small-btn") {
          actionButtons.forEach((button) => {
            button.classList.remove('open');
          });
          eventElement.classList.add('open');
          let classArray = eventElement.classList;
        } else {
          this.$router.push(link);
          eventElement.classList.remove('open');
        }
      }
    },
    handleScroll(event) {
      let actionButtons = document.querySelectorAll('a.open');
      actionButtons.forEach((button) => {
        button.classList.remove('open');
      });
    }
  }
}
</script>

<style lang="scss">
</style>