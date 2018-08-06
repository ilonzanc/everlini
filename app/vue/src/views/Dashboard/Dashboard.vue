<template>
  <div id="dashboard" class="content">
    <div class="hero-image"></div>
    <div class="container">
      <section v-if="this.$parent.session.profile" class="profile-card">
        <h1>
          Dashboard
        </h1>
        <section class="your-organisations-list">
          <h2>Jouw organisaties</h2>
          <router-link to="/organisatie-aanmaken" class="btn primary-btn">Organisatie aanmaken</router-link>
          <ul>
            <li v-bind:key="organisation.id" v-for="organisation in organisations">
              <h3>{{ organisation.name }}</h3>
            </li>
          </ul>
        </section>
        <section class="event-feed">
        </section>
      </section>
      <section class="organisation-card">
        <h1>{{ user.name }}</h1>
        <router-link to="/profiel/jouw-events" class="btn primary-btn">Jouw events</router-link>
        <router-link to="/profiel/nieuw-event" class="btn primary-btn"><i class="fa fa-plus"></i> Nieuw event</router-link>
      </section>

    </div>
  </div>
</template>

<script>
  export default {
    name: 'dashboard',
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
        loggedInUser: {},
        organisations: []
      }
    },
    mounted () {
      console.log("Profile Vue Component mounted");
      this.loggedInUser = JSON.parse(localStorage.getItem("user"));
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
        url: apiurl + "/api/organisations.json?user=" + this.$parent.session.id,
      })
      .then(response => {
        this.organisations = response.data;
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