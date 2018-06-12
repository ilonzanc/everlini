<template>
  <div id="profile-event-detail" class="content">
    <div class="container">
      <h1>{{ event.name }}</h1>
      <section class="action-btns">
        <router-link :to="'/profiel/jouw-events/' + event.id + '/bewerken'" class="btn primary-btn small-btn"><i class="fa fa-pencil"></i><span> Bewerken</span></router-link>
        <a href="#" @click.prevent="deleteEvent(event.id)" class="btn small-btn delete-btn"><i class="fa fa-trash"></i><span> Verwijderen</span></a>
      </section>
      <table>
        <tbody>
          <tr>
            <td>Event naam:</td>
            <td>{{ event.name }}</td>
          </tr>
          <tr>
            <td>Beschrijving:</td>
            <td>{{ event.description }}</td>
          </tr>
          <tr>
            <td>Start tijd:</td>
            <td>{{event.startdate | moment("DD MMM") }} | {{event.startdate | moment("HH:mm")}}</td>
          </tr>
          <tr>
            <td>Eind tijd:</td>
            <td>{{event.enddate | moment("DD MMM")}} | {{event.enddate | moment("HH:mm")}}</td>
          </tr>
          <tr>
            <td>Adres:</td>
            <td>{{ event.address }}</td>
          </tr>
        </tbody>
      </table>
      <section class="event-blog">
        <router-link :to="'/profiel/jouw-events/' + this.$route.params.id + '/nieuwe-blogpost'" class="btn primary-btn new-blog-btn"><i class="fa fa-plus"></i><span>Nieuwe blogpost</span></router-link>
        <h2>Blog</h2>
        <article class="event-blog-post" v-for="post in event.posts" :key="post.id" v-if="post.published == true">
          <h3>{{ post.title }}</h3>
          <p>{{ post.body }}</p>
          <router-link :to="'/profiel/jouw-events/' + event.id + '/posts/' + post.id + '/bewerken'" class="btn primary-btn small-btn"><i class="fa fa-pencil"></i><span> Bewerken</span></router-link>
          <a href="#" @click.prevent="deletePost(post.id)" class="btn small-btn delete-btn"><i class="fa fa-trash"></i><span> Verwijderen</span></a>
        </article>
      </section>
    </div>
  </div>
</template>

<script>
  import moment from 'moment';
  export default {
    name: "profile-event-detail",
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
      console.log('Mounted Event Detail Vue Component');
      let self = this;
      axios({
        method: "get",
        url: "http://localhost:8765/api/events/" + this.$route.params.id + ".json",
        headers: { },
      })
      .then(function(response) {
        console.log(response);
        self.event = response.data.event[0];
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
      },
      deletePost(id) {
        var self = this;
        console.log("click");
        axios({
          method: 'put',
          url: "http://localhost:8765/api/posts/" + id + "/delete.json",
          data: self.event,
        })
        .then((response) => {
            console.log(response)
            //this.$router.push('/profiel/jouw-events');
        })
        .catch((error) => {
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

.action-btns {
  text-align: right;

  a {
    //float: right;

    &:first-child {
      margin-bottom: 0.5rem;
    }
  }
}

article {
  clear: right;
}

.new-blog-btn {
  float: right;
}

table td {
  padding: 0.5rem 0;
}

table td:first-child {
  padding-right: 1rem;
  vertical-align: top;
}
</style>