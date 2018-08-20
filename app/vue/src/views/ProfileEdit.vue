<template>
  <div id="profile-edit" class="content">
    <div class="container">
      <h1>Profiel wijzigen</h1>
      <form @submit.prevent="onSubmit()">
        <div class="row">
          <div class="column column-sm-12 column-lg-6">
            <section>
              <article v-bind:key="message.index" v-for="message in errors.flash" class="flash-message error">
                <i class="fa fa-exclamation-triangle"></i>{{message}}
              </article>
            </section>
            <label class="required" for="username">Gebruikersnaam</label>
            <input type="text" id="profile_username" name="username" placeholder="Jouw gebruikersnaam..." v-model="profile.user.username">
            <span v-if="errors.profile.user.username" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.profile.user.username}}</span>
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
            <span v-if="errors.profile.user.email" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.profile.user.email}}</span>
            <button type="submit" class="btn primary-btn widebtn">Registreren</button>
          </div>
          <div class="column column-sm-12 column-lg-6">
            <label class="required" for="interests">Interesses</label>
            <div class="interests_input">
              <input type="text" name="0" v-model="interests[0]" placeholder="Eigen interesse toevoegen...">
              <i class="fa fa-plus" @click.prevent="addRow"></i>
            </div>
            <div class="additional_interests" v-bind:key="row.index" v-for="row in rows">
              <div class="interests_input">
                <input type="text" :name="currentInputIndex" v-model="interests[row.index + 1]" placeholder="Nog eentje...">
                <i class="fa fa-plus" @click.prevent="addRow"></i>
              </div>
            </div>
            <span v-if="errors.interests" class="form-error"><i class="fa fa-exclamation-triangle"></i>{{errors.profile.user.interests}}</span>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'profile-edit',
    data() {
      return {
        currentInputIndex: 0,
        inputs: [ 'fullname', 'email'],
        rows: [],
        profile: {},
        interests: [],
        validationStatus: true,
        errors: {
          profile: {
            firstname: "",
            lastname: "",
            dateofbirth: "",
            user: {
              email: "",
              username: "",
              interests: []
            }
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
        this.profile = response.data.profile;
        response.data.profile.user.interests.forEach(interest => {
          this.interests.push(interest.name);
        })
      })
      .catch(error => {
      });
    },
    methods: {
      onSubmit() {
        this.errors.flash = false
        this.user.role_id = role_id;
        this.validateRegister();
        if (this.validationStatus) {
          axios({
            method: 'post',
            url: apiurl + "register.json",
            data: this.user,
          })
          .then((response) => {
            if (response.data.errors) {
              this.errors.flash = response.data.errors;
              this.validationStatus = false;
            } else {
              this.user = response.data;
              localStorage.setItem("user", JSON.stringify(response.data));
              location.href = '/profiel/' + response.data.username;
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
      addRow() {
        this.rows.push({value: this.profile.user.interests[this.currentInputIndex], index: this.currentInputIndex});
        this.currentInputIndex++;
      },
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