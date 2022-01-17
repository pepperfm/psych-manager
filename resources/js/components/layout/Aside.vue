<template>
     <el-aside
    id="sidebar"
    style="overflow-y: scroll"
    class="hidden-sm-and-down aside shadow-lg"
    :style="{ width: collapsed ? '80px' : '250px' }"
  >
    <el-row>
      <el-col class="aside-user-info" :class="{ collapsed: collapsed }">
          
        <el-col>
          <el-menu
            :default-active="$route.meta.menuitem"
            class="el-menu-vertical"
            :collapse="collapsed"
            background-color="#343a40"
            text-color="#cfd8dc"
            active-text-color="#ffffff"
          >
            
            <el-menu-item class="main-nav-logo" @click="toRoute('main')">
              <el-avatar class="main-avatar"></el-avatar>
              <span slot="title">Psych Manager</span>
            </el-menu-item>
            <el-menu-item index="2" @click="toRoute('clients')">
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
.main-avatar {
  vertical-align: middle;
}
.el-menu-item.is-active {
  background-color: #007bff !important;
  border-radius: 0.25rem;
}
.el-menu-item {
  height: 45px;
  line-height: 45px;
}
.el-menu-item:not(.is-active, .main-nav-logo):hover {
  background-color: rgba(255, 255, 255, 0.1 )!important;
  border-radius: 0.25rem;
}
.el-menu-item {
  margin: 0.3rem 0.5rem;
}
.main-nav-logo {
  height: 57px;
  margin: 0;
  background-color: #343a40;
}
.main-nav-logo:hover, .main-nav-logo:active, .main-nav-logo:focus {
  background-color: #343a40 !important;
}


</style>
