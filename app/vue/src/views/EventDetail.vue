<template>
  <div id="event-detail" class="content">
    <div class="event-detail-header" style="background: url('')"></div>
    <div class="container">
      <router-link class="breadcrumbs" to="/evenementen">Terug naar overzicht</router-link>
      <div v-if="eventdata != null" class="row">
        <div class="column column-lg-8">
          <div class="event-header-image" :style='"background-image: url(" + eventdata.attachment.path + ")"'></div>
          <a href="#" class="btn small-btn save-btn" @click.prevent="saveEvent" v-if="$parent.session != null"><i :class="['fa fa-heart', { 'red': eventFavorited }]"></i>{{eventFavorited ? 'Opgeslagen!' : 'Opslaan'}}</a>
          <h1>{{ eventdata.name }}</h1>
          <img v-if="currentMeetUpEvent.id" class="event-meetup-logo" src="../assets/images/meetup-logo-small.png">
          {{eventdata.startdate | moment("DD MMM") }} | {{eventdata.startdate | moment("HH:mm")}}
          <span v-if="eventdata.enddate"> - {{eventdata.enddate | moment("DD MMM")}} | {{eventdata.enddate | moment("HH:mm")}}</span>
          <p v-if="eventdata.street">{{ eventdata.street }} {{ eventdata.housenr }}, {{ eventdata.postal_code }} {{ eventdata.city }}</p>
          <p v-if="eventdata.venue">{{ eventdata.venue.address_1 }}, {{ eventdata.venue.city }}</p>
          <p v-html="eventdata.description"></p>
          <section class="event-blog">
            <h2>Blog</h2>
            <article class="event-blog-post" v-for="post in eventdata.posts" :key="post.id" v-if="post.published == true">
              <h3>{{ post.title }}</h3>
              <p>{{ post.body }}</p>
            </article>
            <p v-if="eventdata.posts.length <= 0">Dit evenement heeft geen nieuwe posts.</p>
          </section>
        </div>
        <div class="column column-lg-4"></div>
      </div>
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
        eventdata: null,
        eventFavorited: false
      }
    },
    mounted() {
      console.log('Mounted Detail Vue Component');
      if(!this.currentMeetUpEvent.id) {
        axios({
          method: "get",
          url: apiurl + "events/" + this.$route.params.id + ".json",
          headers: { },
        })
        .then(response => {
          this.eventdata = response.data.event[0];
          this.checkIfFavorited();
        })
        .catch(error => {
        });
      } else {
        this.$jsonp('https://api.meetup.com/' +
          this.currentMeetUpEvent.groupname + '/events/' + this.currentMeetUpEvent.id + '?key=766033144c453b4d295465e352538&sign=true&fields=*, group_category')
        .then(json => {
          this.event = json.data;
          this.eventdata.startdate = json.data.local_date + ' ' + json.data.local_time ;
          this.checkIfFavorited();
        }).catch(err => {
        })
      }

    },
    methods: {
      saveEvent() {
        delete this.eventdata.created;
        if (this.currentMeetUpEvent.id) {
          this.eventdata.meetup_id = this.eventdata.id;
          axios({
            method: 'post',
            url: apiurl + "events/add.json",
            data: this.event
          })
          .then(response => {
            this.eventdata.id = response.data.id;
            this.saveFavorite();
          })
          .catch(error => {
          });
        } else {
          this.saveFavorite();
        }

      },
      saveFavorite() {
        if(this.eventFavorited == false) {
          axios({
            method: "post",
            url: apiurl + "favorites/add.json",
            headers: { },
            data: {
              event_id: this.eventdata.id,
              user_id: this.$parent.session.id
            }
          })
          .then(response => {
            this.eventFavorited = true;
          })
          .catch(error => {
          });
        } else {
          axios({
            method: "delete",
            url: apiurl + "favorite/delete.json",
            headers: { },
            data: {
              event_id: this.eventdata.id,
              user_id: this.$parent.session.id,
              meetup_id: this.currentMeetUpEvent.id
            }
          })
          .then(response => {
            this.eventFavorited = false;
          })
          .catch(error => {
          });
        }

      },
      checkIfFavorited() {
        axios({
          method: "put",
          url: apiurl + "favorites.json",
          data: {
            event_id: this.eventdata.id,
            user_id: this.$parent.session.id,
            meetup_id: this.currentMeetUpEvent.id
          }
        })
        .then(response => {
          console.log(response);
          if(response.data.favorites.length >= 1 ) {
            this.eventFavorited = true;
          }
        })
        .catch(error => {
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