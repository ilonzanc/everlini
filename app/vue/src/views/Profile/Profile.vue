<template>
  <div id="profile" class="content">
    <div class="hero-image"></div>
    <div class="container">
      <section v-if="this.$parent.session.profile" class="profile-card">
        <h1>
          <div class="user-avatar"><i class="fa fa-user-circle" aria-hidden="true"></i></div>
          {{ user.firstname + ' ' + user.lastname }}
        </h1>
        <router-link to="/organisatie-aanmaken" class="btn primary-btn">Organisatie aanmaken</router-link>
        <section class="event-schedule">
          <h2>Aankomende events</h2>
          <article class="event" v-bind:key="meetup.id" v-for="meetup in meetups">
            <div class="event-date">
              <div class="event-day">{{meetup.local_date | moment("DD")}}</div>
              <div class="event-month">{{meetup.local_date | moment("MMM")}}</div>
            </div>
            <div class="event-details">
              <h3>{{ meetup.name }}</h3>
            </div>
          </article>
          <article class="event" v-bind:key="favevent.id" v-for="favevent in favoriteEvents">
            <div class="event-date">
              <div class="event-day">{{favevent.startdate | moment("DD")}}</div>
              <div class="event-month">{{favevent.startdate | moment("MMM")}}</div>
            </div>
            <div class="event-details">
              <h3>{{ favevent.name }}</h3>
            </div>
          </article>
        </section>
        <section class="event-feed">
        </section>
      </section>
      <section v-if="this.$parent.session.organisation" class="organisation-card">
        <h1>{{ user.name }}</h1>
        <router-link to="/profiel/jouw-events" class="btn primary-btn">Jouw events</router-link>
      </section>

    </div>
  </div>
</template>

<script>
  export default {
    name: 'profile',
    data() {
      return {
        user: {
          firstname: "",
          lastname: "",
          name: ""
        },
        favoriteEvents: [],
        favoriteMeetUps: [],
        meetups: [],
        loggedInUser: {}
      }
    },
    mounted () {
      console.log("Profile Vue Component mounted");
      this.loggedInUser = JSON.parse(localStorage.getItem("user"));
      if (this.$parent.session.profile) {
        axios({
          method: 'get',
          url: apiurl + "/api/profiles/" + this.$parent.session.profile.id + ".json",
        })
        .then(response => {
          this.user = response.data.profile;
        })
        .catch(error => {
        });

        axios({
          method: 'get',
          url: apiurl + "/api/favorites/user/" +  this.loggedInUser.id + ".json",
        })
        .then(response => {
          for (let i = 0; i < response.data.favoriteEvents.length; i++) {
            if (response.data.favoriteEvents[i].meetup_id == null) {
              this.favoriteEvents.push(response.data.favoriteEvents[i]);
            } else {
              this.favoriteMeetUps.push(response.data.favoriteEvents[i]);
            }
          }
          this.getMeetUps();
        })
        .catch(error => {
        });
      } else if (this.$parent.session.organisation) {
        axios({
          method: 'get',
          url: apiurl + "/api/organisations/" + this.$parent.session.organisation.id + ".json",
        })
        .then(response => {
          this.user = response.data.organisation;
        })
        .catch(error => {
        });
      }

    },
    methods: {
      getMeetUps() {
        for (let m = 0; m < this.favoriteMeetUps.length; m++) {
          this.$jsonp('https://api.meetup.com/' +
          this.favoriteMeetUps[m].meetup_groupname + '/events/' + this.favoriteMeetUps[m].meetup_id + '?key=766033144c453b4d295465e352538&sign=true&fields=*, group_category')
          .then(json => {
            this.meetups.push(json.data);
          }).catch(err => {
          })
        }
      }
    }
  }
</script>