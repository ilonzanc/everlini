<template>
  <div id="profile-admin-invite" class="content">
    <div class="hero-image"></div>
    <div class="container">
      <h1 v-if="profile.user != null">{{profile.user.username}} uitnodigen</h1>
      <form @submit.prevent="onSubmit()">
        <label for="organisation" class="required">Organisatie</label>
        <select class="form-control" name="organisation" v-model="admin.organisation_id">
          <option value="default" selected disabled>- Selecteer een organisatie -</option>
          <option v-for="organisation in organisations" v-bind:key="organisation.id" :value="organisation.id">{{organisation.name}}</option>
        </select>
        <label for="message">Bericht</label>
        <textarea name="message"></textarea>
        <button type="submit" class="btn primary-btn">admin toevoegen</button>
      </form>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'profile-admin-invite',
    data() {
      return {
        profile: { },
        organisations: [],
        admin: {
          user_id: null,
          username: null,
          organisation_id: "default"
        }
      }
    },
    mounted () {
      console.log("Profile Vue Component mounted");
      axios({
        method: 'get',
        url: apiurl + "profiles/" + this.$route.params.username + ".json",
      })
      .then(response => {
        this.profile = response.data.profile;
        this.admin.user_id = response.data.profile.user.id;
        this.admin.username = response.data.profile.user.username;
      })
      .catch(error => {
      });

      axios({
        method: 'get',
        url: apiurl + "organisations.json?user=" + this.$parent.session.id,
      })
      .then(response => {
        this.organisations = response.data;
      })
      .catch(error => {
      });
    },
    methods: {
      onSubmit() {
        axios({
          method: 'post',
          url: apiurl + '/api/admins/add.json',
          data: this.admin
        })
        .then(response => {
          console.log(response.data);
        })
        .catch(error => {

        })
      }
    }
  }
</script>