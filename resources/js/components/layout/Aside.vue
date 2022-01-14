<template>
   <aside class="main-sidebar">
     <div>1</div>
     <div>2</div>
     <div>3</div>
   </aside>
</template>
<script>
// import UserFiltersMixin from '@admin-mixins/UserFiltersMixin.js'
export default {
  data() {
    return {
      collapsed: false,
    };
  },
  // mixins: [UserFiltersMixin],
  async created() {
    // this.clearUserFilters('aside')
    // let filters = await this.getUserFilters('aside')

    // this.collapsed = filters.collapsed

    this.$root.$on("collapse-toggle", () => {
      this.collapsed = !this.collapsed;
      // this.setUserFilters('aside', { collapsed: this.collapsed })
    });
  },
  methods: {
    handleOpen() {
      this.isCollapse = false;
    },
    handleClose() {
      this.isCollapse = true;
    },
    toRoute(alias) {
      this.$router.push({ name: alias });
    },
  },
};
</script>
<style lang="css" scoped>
.collapsed {
    width: 64px;
}
.el-menu-vertical {
    border: 0;
}


</style>
