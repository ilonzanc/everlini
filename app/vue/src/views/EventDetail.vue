<template>
  <div id="event-detail" class="content">
    <div class="container">
      <h1>{{ event.name }}</h1>
      {{event.startdate | moment("DD MMM") }} | {{event.startdate | moment("HH:mm")}} -
      {{event.enddate | moment("DD MMM")}} | {{event.enddate | moment("HH:mm")}}
      <p>{{ event.street }} {{ event.housenr }}, {{ event.postal_code }} {{ event.city }}</p>
      <a href="#" class="btn small-btn save-btn" @click.prevent="saveEvent"><i class="fa fa-heart"></i> Opslaan</a>
      <p>{{ event.description }}</p>
    </div>
  </div>
</template>

<script>
  import moment from 'moment';
  export default {
    name: "event-detail",
    computed: {
      searchparams() {
        return this.$store.state.searchparams;
      }
    },
    data() {
      return {
        location: "",
        event: ""
      }
    },
    mounted() {
      console.log('Mounted Detail Vue Component');
      let self = this;
      axios({
        method: "get",
        url: "http://localhost:8765/api/events/" + this.$route.params.id + ".json",
        headers: { },
      })
      .then(function(response) {
        console.log(response);
        self.event = response.data.event;
      })
      .catch(function(error) {
        console.log(error);
      });
    },
    methods: {
      saveEvent() {
        console.log('click');
        let self = this;
        axios({
          method: "post",
          url: "http://localhost:8765/api/favorite/add.json",
          headers: { },
          data: {
            event_id: self.event.id,
            user_id: this.$parent.session.id
          }
        })
        .then(function(response) {
          console.log(response);
        })
        .catch(function(error) {
          console.log(error);
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

  &:active {
    border: none;
    background: #FF6B6B;
  }
}
</style>