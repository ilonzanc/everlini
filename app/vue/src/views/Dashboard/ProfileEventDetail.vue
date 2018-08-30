<template>
  <div id="profile-event-detail" class="content">
    <div class="container">
      <router-link :to='"/dashboard/" + $route.params.name + "/" + $route.params.id' class="breadcrumbs">terug naar dashboard</router-link>
      <section class="action-btns">
        <router-link :to="'/dashboard/' + $route.params.name + '/' + $route.params.id + '/events/' + event.id + '/bewerken'" class="btn primary-btn small-btn"><i class="fa fa-pencil"></i><span> Bewerken</span></router-link>
        <a href="#" @click.prevent="onDelete('event', event.id)" class="btn small-btn delete-btn"><i class="fa fa-trash"></i><span> Verwijderen</span></a>
      </section>
      <h1>{{ event.name }}</h1>
      <p>{{ event.description }}</p>
      <ul class="tag-list">
        <li class="tag" v-for="tag in event.interests" v-bind:key="tag.id">{{tag.name}}</li>
      </ul>
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
            <td>{{event.startdate}}</td>
          </tr>
          <tr>
            <td>Eind tijd:</td>
            <td>{{event.enddate}}</td>
          </tr>
          <tr>
            <td>Adres:</td>
            <td>{{ event.address }}</td>
          </tr>
        </tbody>
      </table>
      <section class="event-blog">
        <router-link :to="'/dashboard/' + this.$route.params.name + '/' + this.$route.params.id  + '/events/' + event.id + '/nieuwe-blogpost'" class="btn primary-btn new-blog-btn"><i class="fa fa-plus"></i><span>Nieuwe blogpost</span></router-link>
        <h2>Blogposts</h2>
        <article class="event-blog-post" v-for="post in event.posts" :key="post.id">
          <h3>{{ post.title }}</h3>
          <p>{{ post.body }}</p>
          <router-link :to="'/dashboard/' + $route.params.name + '/' + $route.params.id + '/events/' + $route.params.eventid + '/posts/' + post.id + '/bewerken'" class="btn primary-btn small-btn"><i class="fa fa-pencil"></i><span> Bewerken</span></router-link>
          <a href="#" @click.prevent="onDelete('post', post.id)" class="btn small-btn delete-btn"><i class="fa fa-trash"></i><span> Verwijderen</span></a>
        </article>
      </section>
      <article v-if="deleteEntity.status" class="toast-message">
        <a href="" @click.prevent="closeToastMessage()"><i class="fa fa-times"></i></a>
        <p>Ben je zeker dat je {{ deleteEntity.type }} {{ deleteEntity.id }} wilt verwijderen?</p>
        <button @click.prevent="confirmDelete()" class="btn delete-btn">Verwijderen</button>
        <button @click.prevent="closeToastMessage()" class="btn ghost">Cancel</button>
      </article>
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
        event: "",
        deleteEntity: {
          status: false,
          id: null,
          type: null
        },
      }
    },
    mounted() {
      console.log('Mounted Event Detail Vue Component');
      axios({
        method: "get",
        url: apiurl + "events/" + this.$route.params.eventid + ".json",
        headers: { },
      })
      .then((response) => {
        this.event = response.data.event[0];
        this.event.startdate = moment(String(response.data.event[0].startdate)).utcOffset(0).format('DD MMM | H:mm');
        this.event.enddate = moment(String(response.data.event[0].enddate)).utcOffset(0).format('DD MMM | H:mm');
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
      saveEvent() {
        axios({
          method: "post",
          url: apiurl + "favorite/add.json",
          headers: { },
          data: {
            event_id: this.event.id,
            user_id: this.$parent.session.id
          }
        })
        .then((response) => {
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
      closeToastMessage() {
        let overlay = document.querySelector('.overlay');
        overlay.classList.remove('show');
        this.deleteEntity.status = false;
        this.deleteEntity.id = null;
        this.deleteEntity.type = null;
      },
      onDelete(type, entity_id) {
        let overlay = document.querySelector('.overlay');
        overlay.classList.add('show');
        this.deleteEntity.status = true;
        this.deleteEntity.id = entity_id;
        this.deleteEntity.type = type;
      },
      confirmDelete() {
        axios({
          method: 'put',
          url: apiurl + this.deleteEntity.type + "s/" + this.deleteEntity.id + "/delete.json",
          data: this.event,
        })
        .then((response) => {
          if (this.deleteEntity.type == "post") {
            this.$router.go();
          } else {
            this.$router.push("/dashboard/" + this.$route.params.name + "/" + this.$route.params.id);
          }

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
  };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss">
  #profile-event-detail {
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
  }
  @media (min-width: 42.5rem) {
    #profile-event-detail {
      .action-btns {
        float: right;
      }
    }
  }

</style>