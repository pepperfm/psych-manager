<template>
  <el-aside
    id="sidebar"
    style="overflow-y: scroll"
    class="hidden-sm-and-down aside"
    :style="{ width: collapsed ? '64px' : '300px' }"
  >
    <el-row>
      <el-col class="aside-user-info" :class="{ collapsed: collapsed }">
        <el-col>
          <el-menu
            :default-active="$route.meta.menuitem"
            class="el-menu-vertical"
            :collapse="collapsed"
            background-color="#30313e"
            text-color="#cfd8dc"
            active-text-color="#ffffff"
          >
            <el-menu-item index="2" @click="toRoute('users')">
              <i class="fas fa-address-card"></i>
              <span slot="title">Клиенты</span>
            </el-menu-item>

            <el-menu-item index="3" @click="toRoute('sessions')">
              <i class="fas fa-comments"></i>
              <span slot="title">Сессии</span>
            </el-menu-item>
          </el-menu>
        </el-col>
      </el-col>
    </el-row>
  </el-aside>
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
.aside {
    background-color: #30313e;
}

</style>
