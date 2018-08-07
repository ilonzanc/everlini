<template>
  <div id="profile" class="content">
    <div class="hero-image"></div>
    <div class="container">
      <section class="profile-actions">
        <a href="#" class="btn primary-btn small-btn" @click.prevent="showAction($event, '/organisatie-dashboard')"><i class="fa fa-dashboard"></i> Dashboard</a>
        <a href="#" class="btn primary-btn small-btn" @click.prevent="showAction($event, '/profiel-aanpassen')"><i class="fa fa-pencil"></i> Wijzig</a>
      </section>
      <section class="profile-card">
        <h1>
          <div class="user-avatar"><i class="fa fa-user-circle" aria-hidden="true"></i></div>
          {{ user.firstname + ' ' + user.lastname }}
        </h1>
      </section>
      <div class="row">
      <section class="event-newsfeed">
        <h2>Newsfeed</h2>
      </section>
        <aside class="event-schedule">
          <h2>Aankomende events</h2>
          <article class="event" v-bind:key="meetup.id" v-for="meetup in meetups">
            <div class="event-date">
              <div class="event-day">{{meetup.local_date | moment("DD")}}</div>
              <div class="event-month">{{meetup.local_date | moment("MMM")}}</div>
            </div>
            <div class="event-details">
              <p>{{ meetup.name }}</p>
            </div>
          </article>
          <article class="event" v-bind:key="favevent.id" v-for="favevent in favoriteEvents">
            <div class="event-date">
              <div class="event-day">{{favevent.startdate | moment("DD")}}</div>
              <div class="event-month">{{favevent.startdate | moment("MMM")}}</div>
            </div>
            <div class="event-details">
              <p>{{ favevent.name }}</p>
            </div>
          </article>
        </aside>
      </div>
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
        loggedInUser: {},
        organisations: []
      }
    },
    mounted () {
      console.log("Profile Vue Component mounted");
      this.loggedInUser = JSON.parse(localStorage.getItem("user"));
      axios({
        method: 'get',
        url: apiurl + "/api/profiles/" + this.loggedInUser.profile.id + ".json",
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
      },
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
    }
  }
</script>