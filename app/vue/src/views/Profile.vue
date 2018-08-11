<template>
  <div id="profile" class="content">
    <div class="hero-image"></div>
    <div class="container">
      <section class="profile-actions">
        <div v-if="profile.user_id == $parent.session.id">
        <a href="#" class="btn primary-btn small-btn" @click.prevent="showAction($event, '/dashboard')"><i class="fa fa-dashboard"></i> Dashboard</a>
        <a href="#" class="btn primary-btn small-btn" @click.prevent="showAction($event, '/profiel-aanpassen')"><i class="fa fa-pencil"></i> Wijzig</a>
        </div>
        <div v-if="profile.user_id != $parent.session.id">
        <a href="#" class="btn primary-btn small-btn" @click.prevent="showAction($event, profile.user.username + '/uitnodigen-als-admin/')"><i class="fa fa-envelope"></i> Maak admin</a>
        </div>
      </section>
      <section class="profile-card">
        <h1>
          <div class="user-avatar"><i class="fa fa-user-circle" aria-hidden="true"></i></div>
          {{ profile.firstname + ' ' + profile.lastname }}
        </h1>
      </section>
      <div class="row">
      <section class="event-newsfeed">
        <h2>Newsfeed</h2>
        <article v-for="post in posts" v-bind:key="post.id">
          <span v-if="posts.length > 0">{{ post.created | moment("DD MMM") }}</span>
          <h3>{{ post.title }}</h3>
          <p>{{ post.body }}</p>
        </article>
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
        profile: { },
        favoriteEvents: [],
        favoriteMeetUps: [],
        meetups: [],
        loggedInUser: {},
        organisations: [],
        posts: []
      }
    },
    mounted () {
      console.log("Profile Vue Component mounted");
      axios({
        method: 'get',
        url: apiurl + "/api/profiles/" + this.$route.params.username + ".json",
      })
      .then(response => {
        this.profile = response.data.profile;
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
        url: apiurl + "/api/favorites/user/" +  this.$parent.session.id + ".json",
      })
      .then(response => {
        console.log(response);
        for (let i = 0; i < response.data.favoriteEvents.length; i++) {
          if (response.data.favoriteEvents[i].meetup_id == null) {
            this.favoriteEvents.push(response.data.favoriteEvents[i]);
          } else {
            this.favoriteMeetUps.push(response.data.favoriteEvents[i]);
          }
        }
        this.getMeetUps();
        this.buildNewsfeed();
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
      buildNewsfeed() {
        this.favoriteEvents.forEach(event => {
          if (event.posts.length > 0) {
            event.posts.forEach(post => {
              this.posts.push(post);
            })
          }
        });

        this.posts.sort(function(a,b){
          return new Date(b.created) - new Date(a.created);
        });
      }
    }
  }
</script>