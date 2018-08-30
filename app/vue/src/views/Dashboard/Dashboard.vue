<template>
  <div id="dashboard" class="content">
    <div class="hero-image"></div>
    <div class="container">
      <section v-if="this.$parent.session" class="profile-card">
        <h1>
          Dashboard
        </h1>
        <section class="your-organisations">
          <h2>Jouw organisaties</h2>
          <router-link to="organisatie-aanmaken" append class="btn primary-btn">Organisatie aanmaken</router-link>
          <ul class="your-organisations-list">
            <li v-bind:key="organisation.id" v-for="organisation in organisations">
              <router-link :to="{ path: organisation.slug + '/' + organisation.id }" append>
                <article class="card">
                  <h3>{{ organisation.name }}</h3>
                </article>
              </router-link>
            </li>
          </ul>
        </section>
        <section class="event-feed">
        </section>
      </section>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'dashboard',
    data() {
      return {
        organisations: [],
      }
    },
    mounted () {
      console.log("Profile Vue Component mounted");

      axios({
        method: 'get',
        url: apiurl + "organisations.json?user=" + this.$parent.session.id,
      })
      .then(response => {
        console.log(response.data);
        this.organisations = response.data;
        this.organisations.forEach(organisation => {
          organisation.slug = organisation.name.toLowerCase();
          organisation.slug = organisation.slug.split(' ').join('_');
        });
      })
      .catch(error => {
      });
    },
    methods: {
    }
  }
</script>