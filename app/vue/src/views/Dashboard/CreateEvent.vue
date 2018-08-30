<template>
  <div id="create-event" class="content">
    <div class="container">
      <router-link :to='"/dashboard/" + $route.params.name + "/" + $route.params.id' class="breadcrumbs">terug naar dashboard</router-link>
      <h1>Nieuw event toevoegen</h1>
      <div class="row">
        <div class="column column-sm-12 column-lg-6">
          <form @submit.prevent="onSubmit">
            <label for="name">Naam</label>
            <input type="text" id="name" name="name" required v-model="event.name" placeholder="Naam van je event...">
            <label for="description">Beschrijving</label>
            <textarea id="description" name="description" required v-model="event.description" placeholder="Vertel wat meer over je event..."></textarea>
            <h2>Tijdstip  </h2>
            <label for="startdate">Start event</label>
            <input type="date" id="startdate" name="startdate" required placeholder="dd-mm-jjjj" v-model="event.startdate.date">
            <input type="text" id="startdate" name="startdate" required placeholder="00:00" v-model="event.startdate.time">
            <label for="enddate">Eind event</label>
            <input type="date" id="enddate" name="enddate" required placeholder="dd-mm-jjjj" v-model="event.enddate.date">
            <input type="text" id="enddate" name="enddate" required placeholder="00:00" v-model="event.enddate.time">
            <h2>Locatie</h2>
            <input id="pac-input" type="text" name="location" placeholder="Locatie van je evenement..." v-model="event.location.name" @keyup="initMap">
            <h2>Tags</h2>
            <label class="required" for="interests">Tags</label>
            <ul v-if="event.tags.length > 0">
              <li v-for="tag in event.tags" v-bind:key="tag.index" class="tag removable-tag" @click.prevent="deleteInterest($event)">{{ tag }}</li>
            </ul>
            <div class="interests_input">
              <input type="text" name="0" id="interest_input" v-model="currentInterest" placeholder="Eigen interesse toevoegen...">
              <i class="fa fa-plus" @click.prevent="addInterest"></i>
            </div>
            <h2>Header</h2>
            <div class="upload-btn-wrapper">
              <button class="btn upload-btn ghost">Upload a file</button>
              <input id="header_image" ref="file" type="file" @change="uploadImage()">
            </div>
            <button class="btn primary-btn" type="submit">Toevoegen</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: "create-event",
  data () {
    return {
      currentInterest: null,
      currentInputIndex: 0,
      inputs: [ 'fullname', 'email'],
      rows: [],
      event: {
        organisation_id: "",
        name: "",
        description: "",
        startdate: {
          time: "",
          date: ""
        },
        enddate: {
          time: "",
          date: ""
        },
        location: {
          name: "",
          lat: "",
          lng: "",
        },
        tags: [],
        image_id: null
      },
      loggedInUser: {},
      file: ''
    }
  },
  mounted() {
    console.log('Create Event Component Mounted');
    this.event.organisation_id = this.$route.params.id;
  },
  methods: {
    uploadImage() {
      console.log();
      this.file = this.$refs.file.files[0];
    },
    onSubmit() {
      let formData = new FormData();
      formData.append('file', document.getElementById('header_image').files[0]);
        axios({
          method: 'post',
          url: apiurl + "attachments/add.json",
          headers: {
            'Content-Type': 'multipart/form-data'
          },
          data: formData
        })
        .then((response) => {
            this.event.image_id = response.data.image.id;
            axios({
              method: 'post',
              url: apiurl + "events/add.json",
              data: this.event
            })
            .then((response) => {
                this.$router.push('/dashboard/' + this.$route.params.name + '/' + this.$route.params.id  + '/events/' + response.data.event.id);
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
    initMap() {
      let input = document.getElementById('pac-input');
      let autocomplete = new google.maps.places.Autocomplete(input);

      autocomplete.addListener('place_changed', () => {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
          // User entered the name of a Place that was not suggested and
          // pressed the Enter key, or the Place Details request failed.
          window.alert("No details available for input: '" + place.name + "'");
          return;
        }
        this.event.location.name = place.formatted_address;
        this.event.location.lat = place.geometry.location.lat();
        this.event.location.lng = place.geometry.location.lng();
      });
    },
    addRow() {
        this.rows.push({value: this.event.tags[this.currentInputIndex], index: this.currentInputIndex});
        this.currentInputIndex++;
      },
      addInterest() {
        this.event.tags.push(this.currentInterest);
        this.currentInterest = null;
      },
      deleteInterest(event) {
        let interestIndex = this.event.tags.indexOf(event.target.innerHTML);
        this.event.tags.splice(interestIndex, 1);
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