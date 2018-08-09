<template>
  <div id="profile-events" class="content">
    <div class="container">
      <router-link to="/dashboard" class="breadcrumb">terug naar dashboard</router-link>
      <h1>Jouw events</h1>
      <router-link to="/profiel/nieuw-event" class="btn primary-btn"><i class="fa fa-plus"></i> Nieuw event</router-link>
      <router-link v-bind:key="event.id" v-for="event in events" :to="'/profiel/jouw-events/' + event.id">
        <article class="event">
          <div class="row">
            <div class="column column-sm-9">
              <div class="event-details">
                <h2>{{ event.name }}</h2>
              </div>
            </div>
            <div class="column column-sm-3">
              <section class="action-btns">
                <router-link :to="'/profiel/jouw-events/' + event.id + '/bewerken'" class="btn secundary-btn small-btn"><i class="fa fa-pencil"></i></router-link>
                <a href="#" @click.prevent="deleteEvent(event.id)" class="btn secundary-btn small-btn delete-btn"><i class="fa fa-trash"></i></a>
              </section>
            </div>
          </div>
        </article>
      </router-link>
      <section v-if="events.length == 0">
        <p>Je hebt nog geen evenementen aangemaakt :(</p>
      </section>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'profile-events',
    data() {
      return {
        events: [],
        loggedInUser: {}
      }
    },
    mounted () {
      console.log("Profile Event Vue Component mounted");
      axios({
        method: "get",
        url:
          apiurl + "/api/events/user/" + this.loggedInUser.id + ".json",
        })
        .then((response) => {
          this.events = response.data;
        })
        .catch((error) => {
          if (error.response) {
            console.log(error.response.status);
            console.log(error.response.headers);
            console.log(error.response.data.message);

            let errors = [];

          } else if (error.request) {
            console.log(error.request);
          } else {
            console.log('Error', error.message);
          }
          console.log(error.config);
      });
    },
    methods: {
      deleteEvent(id) {
        axios({
          method: 'put',
          url: apiurl + "/api/events/" + id + "/delete.json",
          data: this.event,
        })
        .then((response) => {
            this.$router.go();
        })
        .catch((error) => {
          if (error.response) {
            console.log(error.response.status);
            console.log(error.response.headers);
            console.log(error.response.data.message);

            let errors = [];

          } else if (error.request) {
            console.log(error.request);
          } else {
            console.log('Error', error.message);
          }
          console.log(error.config);
        });
      }
    }
  }
</script>

<style lang="scss">
#profile-events {
  .action-btns {
    float: right;

    a {
      float: right;

      &:first-child {
        margin-bottom: 0.5rem;
      }
    }
  }
  .event {
    margin: 0.5rem 0;
    padding: 1rem 0 1.5rem;
    border-bottom: 1px solid #eee;
  }
  .event-details {
    margin-top: 3px;
  }

  .column {
    margin-bottom: 0;
  }
}
</style>