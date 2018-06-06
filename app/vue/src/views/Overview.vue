<template>
  <div id="overview__comp" class="content">
    <div class="container">
      <h1>Overzicht</h1>
      <router-link :to="'/evenementen/' + event.id" v-bind:key="event.id" v-for="event in events">
        <section class="event" >
          <div class="event-date">
            <div class="event-day">{{event.startdate | moment("DD")}}</div>
            <div class="event-month">{{event.startdate | moment("MMM")}}</div>
          </div>
          <div class="event-details">
            <h2>{{ event.name }}</h2>
          </div>
        </section>
      </router-link>
      <section v-if="!events">
        <p>Geen evenementen gevonden</p>
      </section>
    </div>
  </div>
</template>

<script>
  //import axios from "axios";
  import moment from 'moment';



  export default {
    name: "overview",
    computed: {
			searchparams() {
				return this.$store.state.searchparams;
			}
		},
    data() {
      return {
        location: "",
        events: [],
        meetupevents: []
      };
    },
    mounted() {
      let self = this;
      axios({
        method: "post",
        url: "http://localhost:8765/api/events/" + this.searchparams.location + "/" + this.searchparams.startdate + "/" + this.searchparams.enddate + ".json",
        headers: { },
        data: this.searchparams
      })
      .then(function(response) {
        console.log(response);
        self.events = response.data.events;
      })
      .catch(function(error) {
        console.log(error);
      });
      this.$jsonp('https://api.meetup.com/find/upcoming_events?key=766033144c453b4d295465e352538&sign=true&photo-host=public&lon=3.7304&page=20&radius=5&lat=51.0535')
      .then(json => {
        console.log(json);
      }).catch(err => {
        // Failed.
      })

      this.$http.jsonp('https://api.meetup.com/find/upcoming_events?key=766033144c453b4d295465e352538&sign=true&photo-host=public&lon=3.7304&page=20&radius=5&lat=51.0535', options)
      .then(function(data){
          let events = data.jsonp();
          console.log(events);
          self.meetupevents = events.events;
      }, function(error) {
      })
    },
    methods: {

    }
  };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>