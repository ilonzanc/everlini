<template>
  <div>
    <div class="tabs">
      <ul :class="tabsClassName">
        <li v-bind:key="tab.index" v-for="tab in tabs" :class="{'is-active': tab.isActive}">
          <a href="#" @click="selectTab(tab)">{{ tab.name }}</a>
        </li>
      </ul>
    </div>
    <div class="tabs-content">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: "tabs",
  props: ['tabsClassName'],
  data () {
    return {
      tabs: []
    }
  },
  mounted() {
    console.log("Tabs Component Mounted.");
  },
  created() {
    this.tabs = this.$children;
  },
  methods: {
    selectTab(selectedTab) {
      console.log(selectedTab.name);
      this.tabs.forEach(tab => {
        tab.isActive = (tab.name == selectedTab.name);
      });

      console.log('aaaahhhh');
        let enddate;
        switch(selectedTab.name) {
          case 'vandaag':
              console.log('today');
              enddate = moment().format('YYYY-MM-DD');
              break;
          case 'deze week':
              console.log('week');
              enddate = moment().add(7, 'days').format('YYYY-MM-DD');
              break;
          case 'deze maand':
              console.log('month');
              enddate = moment().add(30, 'days').format('YYYY-MM-DD');
              break;
          default:
              console.log('none selected');
        }

        this.$parent.params.startdate = moment().format('YYYY-MM-DD');
        this.$parent.params.enddate = enddate;
    }
  }
}
</script>

<style scoped lang="scss">
  ul {
    list-style-type: none;
  }
</style>