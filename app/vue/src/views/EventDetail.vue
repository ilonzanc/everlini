<template>
  <div id="event-detail" class="content">
    <div class="event-detail-header" style="background: url('')"></div>
    <div class="container">
      <router-link to="/evenementen">Terug naar overzicht</router-link>
      <h1>{{ event.name }}</h1>
      <img v-if="currentMeetUpEvent.id" class="event-meetup-logo" src="../assets/images/meetup-logo-small.png">
      {{event.startdate | moment("DD MMM") }} | {{event.startdate | moment("HH:mm")}}
      <span v-if="event.enddate"> - {{event.enddate | moment("DD MMM")}} | {{event.enddate | moment("HH:mm")}}</span>
      <p v-if="event.street">{{ event.street }} {{ event.housenr }}, {{ event.postal_code }} {{ event.city }}</p>
      <p v-if="event.venue">{{ event.venue.address_1 }}, {{ event.venue.city }}</p>
      <a href="#" class="btn small-btn save-btn" @click.prevent="saveEvent" v-if="$parent.session != null"><i :class="['fa fa-heart', { 'red': eventFavorited }]"></i>{{eventFavorited ? 'Opgeslagen!' : 'Opslaan'}}</a>
      <p v-html="event.description"></p>
      <section class="event-blog">
        <h2>Blog</h2>
        <article class="event-blog-post" v-for="post in event.posts" :key="post.id" v-if="post.published == true">
          <h3>{{ post.title }}</h3>
          <p>{{ post.body }}</p>
        </article>
        <p v-if="!event.posts">Dit evenement heeft geen nieuwe posts.</p>
      </section>

    </div>
  </div>
</template>

<script>
  import moment from 'moment';
  export default {
    name: "event-detail",
    computed: {
      currentMeetUpEvent() {
        return this.$store.getters.getCurrentMeetUpEvent;
      }
    },
    data() {
      return {
        location: "",
        event: "",
        eventFavorited: false
      }
    },
    mounted() {
      //console.log('Mounted Detail Vue Component');
      if(!this.currentMeetUpEvent.id) {
        axios({
          method: "get",
          url: "http://localhost:8765/api/events/" + this.$route.params.id + ".json",
          headers: { },
        })
        .then(response => {
          //console.log(response);
          this.event = response.data.event[0];
        })
        .catch(error => {
          //console.log(error);
        });
      } else {
        this.$jsonp('https://api.meetup.com/' +
          this.currentMeetUpEvent.groupname + '/events/' + this.currentMeetUpEvent.id + '?key=766033144c453b4d295465e352538&sign=true&fields=*, group_category')
        .then(json => {
          //console.log(json);
          this.event = json.data;
          this.event.startdate = json.data.local_date + ' ' + json.data.local_time ;
          this.checkIfFavorited();
        }).catch(err => {
          //console.log(err);
        })
      }

    },
    methods: {
      saveEvent() {
        delete this.event.created;
        if (this.currentMeetUpEvent.id) {
          this.event.meetup_id = this.event.id;
          console.log(this.event);
          axios({
            method: 'post',
            url: "http://localhost:8765/api/events/add.json",
            data: this.event
          })
          .then(response => {
            console.log(response);
            this.event.id = response.data.id;
            this.saveFavorite();
          })
          .catch(error => {
            console.log(error);
          });
        } else {
          this.saveFavorite();
        }

      },
      saveFavorite() {
        if(this.eventFavorited == false) {
          axios({
            method: "post",
            url: "http://localhost:8765/api/favorite/add.json",
            headers: { },
            data: {
              event_id: this.event.id,
              user_id: this.$parent.session.id
            }
          })
          .then(response => {
            console.log(response);
            this.eventFavorited = true;
          })
          .catch(error => {
            console.log(error);
          });
        } else {
          axios({
            method: "delete",
            url: "http://localhost:8765/api/favorite/delete.json",
            headers: { },
            data: {
              event_id: this.event.id,
              user_id: this.$parent.session.id,
              meetup_id: this.currentMeetUpEvent.id
            }
          })
          .then(response => {
            console.log(response);
            this.eventFavorited = false;
          })
          .catch(error => {
            console.log(error);
          });
        }

      },
      checkIfFavorited() {
        axios({
          method: "put",
          url: "http://localhost:8765/api/favorites.json",
          headers: { },
          data: {
            event_id: this.event.id,
            user_id: this.$parent.session.id,
            meetup_id: this.currentMeetUpEvent.id
          }
        })
        .then(response => {
          //console.log(response);
          if(response.data.favorites.length >= 1 ) {
            this.eventFavorited = true;
          }
        })
        .catch(error => {
          //console.log(error);
        });
      }
    }
  };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss">
.save-btn {
  background: #FECA57;
  transition: all 0.3s ease-in-out;

  img {
    width: 70px;
  }

  &:active {
    border: none;
    background: #FF6B6B;
  }
}
.event-meetup-logo {
  width: 50px;
  float: right;
}
</style>