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
      this.tabs.forEach(tab => {
        tab.isActive = (tab.name == selectedTab.name);
      });
    }
  }
}
</script>

<style scoped lang="scss">
  ul {
    list-style-type: none;
  }
</style>