<template>
  <div id="profile-edit" class="content">
    <div class="container">
      <h1>Profiel wijzigen</h1>
      <form v-if="profile != null" @submit.prevent="onSubmit()">
        <div class="row">
          <div class="column column-sm-12 column-lg-6">
            <section>
              <article v-bind:key="message.index" v-for="message in errors.flash" class="flash-message error">
                <i class="fa fa-exclamation-triangle"></i>{{message}}
              </article>
            </section>
            <label class="required" for="username">Gebruikersnaam</label>
            <input type="text" id="profile_username" name="username" placeholder="Jouw gebruikersnaam..." v-model="profile.user.username">
            <span v-if="errors.profile.username" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.profile.username}}</span>
            <label for="firstname">Voornaam</label>
            <input type="text" id="profile_firstname" name="firstname" placeholder="Jouw voornaam..." v-model="profile.firstname">
            <span v-if="errors.profile.firstname" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.profile.firstname}}</span>
            <label for="lastname">Naam</label>
            <input type="text" id="profile_lastname" name="lastname" placeholder="Jouw naam..." v-model="profile.lastname">
            <span v-if="errors.profile.lastname" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.profile.lastname}}</span>
            <label for="dateofbirth">Geboortedatum</label>
            <input type="date" id="profile_dateofbirth" name="dateofbirth" placeholder="Jouw geboortedatum..." v-model="profile.dateofbirth">
            <span v-if="errors.profile.dateofbirth" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.profile.dateofbirth}}</span>
            <label class="required" for="email">Emailadres</label>
            <input type="email" id="profile_email" name="email" placeholder="Jouw emailadres..." v-model="profile.user.email">
            <span v-if="errors.profile.email" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.profile.email}}</span>
          </div>
          <div class="column column-sm-12 column-lg-6">
            <label class="required" for="interests">Interesses</label>
            <ul v-if="profile.user.interests.length > 0">
              <li v-for="interest in profile.user.interests" v-bind:key="interest.index" class="tag removable-tag" @click.prevent="deleteInterest($event)">{{ interest }}</li>
            </ul>
            <div class="interests_input">
              <input type="text" name="0" id="interest_input" v-model="currentInterest" placeholder="Eigen interesse toevoegen...">
              <i class="fa fa-plus" @click.prevent="addInterest"></i>
            </div>
            <span v-if="errors.interests" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.profile.user.interests}}</span>
            <button type="submit" class="btn primary-btn widebtn">bewerken</button>
          </div>
        </div>

      </form>
      <div v-else>
        Laden...
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'profile-edit',
    data() {
      return {
        profile: null,
        interests: [],
        validationStatus: true,
        currentInterest: null,
        errors: {
          profile: {
            firstname: "",
            lastname: "",
            dateofbirth: "",
            username: "",
            email: ""
          },
          flash: {

          }
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
        //console.log(response.data.profile.user.interests);
        let interests = response.data.profile.user.interests;
        this.profile = response.data.profile;
        this.profile.user.interests = [];
        this.profile.dateofbirth = moment(String(response.data.profile.dateofbirth)).format('YYYY-MM-DD');
        interests.forEach(interest => {
          this.profile.user.interests.push(interest.name);
        })
      })
      .catch(error => {
      });
    },
    methods: {
      onSubmit() {
        this.errors.flash = false
        if (this.validationStatus) {
          axios({
            method: 'post',
            url: apiurl + "profiles/" + this.profile.id + "/edit.json",
            data: this.profile,
          })
          .then((response) => {
            if (response.data.errors) {
              this.errors.flash = response.data.errors;
              this.validationStatus = false;
            } else {
              localStorage.setItem("user", JSON.stringify(response.data.user));
              this.$parent.session = response.data.user;
              this.$router.push('/profiel/' + response.data.user.username);
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
      },
      addInterest() {
        this.profile.user.interests.push(this.currentInterest);
        this.currentInterest = null;
      },
      deleteInterest(event) {
        let interestIndex = this.profile.user.interests.indexOf(event.target.innerHTML);
        this.profile.user.interests.splice(interestIndex, 1);
      }
    }
  }
</script>
<style lang="scss">
  .interests_input {
  position: relative;
  .fa-plus {
    position: absolute;
    right: 0;
    top: 0;
    padding: 15px 17px 16px;
    background: #FECA57;
    color: #fff;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
    cursor: pointer;
  }
}
</style>