<template>
    <div id="home">
      <div class="container">
        <h1>Ontdek evenementen bij jou in de buurt die matchen met jouw interesses</h1>
        <form method="POST" action="" @submit.prevent="onSubmit">
          <label for="location">Locatie</label>
          <input type="text" id="location" name="location" placeholder="Waar zullen we zoeken?" v-model="params.location">
          <label>Binnen 13 km</label>
          <label>Datum</label>
          <div class="row">
            <div class="column column-sm-6"><label>Van</label>
          <input type="text" id="startdate" name="startdate" placeholder="Start datum..." v-model="params.startdate"></div>
            <div class="column column-sm-6"><label>Tot</label>
          <input type="text" id="enddate" name="enddate" placeholder="Eind datum..." v-model="params.enddate"></div>
          </div>
          <label>Interesses</label>
          <div class="interests_input">
          <input type="text" name="0" v-model="params.interests[0]" placeholder="Eigen interesse toevoegen...">
          <i class="fa fa-plus" @click.prevent="addRow"></i>
          </div>
          <div class="additional_interests" v-for="row in rows">
            <div class="interests_input">
              <input type="text" :name="currentInputIndex" v-model="params.interests[row.index + 1]" placeholder="Nog eentje...">
              <i class="fa fa-plus" @click.prevent="addRow"></i>
            </div>
          </div>
          <button type="submit" class="btn primary-btn">Zoeken</button>
      </form>
    </div>
  </div>
</template>

<script>
  import axios from "axios";
  export default {
    computed: {
      searchparams() {
        return this.$store.getters.getSearch;
      }
    },
    name: "home",
    data() {
      return {
        currentInputIndex: 0,
        inputs: [ 'fullname', 'email'],
        rows: [],
        params: {
          location: "",
          startdate: "",
          enddate: "",
          interests: [],
        }
      };
    },
    mounted() {
      this.params = this.searchparams;
    },
    methods: {
      onSubmit() {
        this.$store.commit('updateSearch', this.params);
        this.$router.push('/evenementen');
      },
      addRow: function() {
        this.rows.push({value: this.params.interests[this.currentInputIndex], index: this.currentInputIndex});

        this.currentInputIndex++;
      }
    }
  };
</script>

<style lang="scss">
.interests_input {
  position: relative;
  .fa-plus {
    position: absolute;
    right: 0;
    top: 0;
    padding: 10px 12px 11px;
    background: #FECA57;
    color: #fff;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
  }
}
</style>