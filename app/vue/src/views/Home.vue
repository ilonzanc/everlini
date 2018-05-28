<template>
  <div id="home" class="content">
    <div class="hero-image"></div>
    <div class="container">

      <h1>Ontdek evenementen bij jou in de buurt die matchen met jouw interesses</h1>
      <form method="POST" action="" @submit.prevent="onSubmit">
        <div class="row">
          <div class="column column-sm-12 column-lg-6">
            <label for="location">Locatie</label>
            <input type="text" id="location" name="location" placeholder="Waar zullen we zoeken?" v-model="params.location">
          </div>
          <div class="column column-sm-12 column-lg-6">
            <label>Binnen 13 km</label>
          </div>
        </div>
        <label>Datum</label>
        <div class="row">
          <div class="column column-sm-12 column-lg-6 timecolumn">
            <div class="tags-flexbox">
              <div class="tag">vandaag</div>
              <div class="tag">deze week</div>
              <div class="tag">deze maand</div>
            </div>
          </div>
          <div class="column column-sm-12 column-lg-6">
            <div class="time-inputs">

                <label for="startdate">Van</label>
                <input class="inline-input" type="text" id="startdate" name="startdate" placeholder="dd-mm-jjjj" v-model="params.startdate">


                <label for="enddate">Tot</label>
                <input class="inline-input" type="text" id="enddate" name="enddate" placeholder="dd-mm-jjjj" v-model="params.enddate">

            </div>
          </div>
        </div>
        <label>Interesses</label>
        <div class="row"></div>
        <div class="row">
          <div class="column column-sm-12 column-lg-6">
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
          </div>
          <div class="column column-sm-12 column-lg-6">
            <button type="submit" class="btn primary-btn">Zoeken</button>
          </div>
        </div>
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
      padding: 15px 17px 16px;
      background: #FECA57;
      color: #fff;
      border-top-right-radius: 5px;
      border-bottom-right-radius: 5px;
      cursor: pointer;
    }
  }



  @media (min-width: 42.5rem) {
    .timecolumn {
      border-right: 1px solid rgba(#424242, 0.3);
    }

    .time-inputs {
      .inline-input {
        width: 170px;
      }

      label:first-child {
        padding-left: 0;
      }
    }
  }


</style>