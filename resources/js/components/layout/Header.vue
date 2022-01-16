<template>
    <nav class="main-header navbar d-flex">
     
        <div index="2" @click="toggleCollapse()">
          <i :class="[ 'fas fa-bars',  {'rotate': toggled },'burger']"></i
        ></div>
      
      
      <div>2</div>
       <el-dropdown>
        <span>Current User</span>
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item>Профиль</el-dropdown-item>
          <el-dropdown-item>Выход</el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
    </nav>
</template>

<script>
export default {
  data() {
    return {
      activeIndex: "1",
      pathName: "Psych Manager",
      toggled: true,
      toggled2: false,
    };
  },
  created() {
    this.$root.$on("set-path-name", (obj) => {
      this.pathName = obj.name;
      this.activeIndex = obj.activeIndex ? obj.activeIndex : "1";
      if (obj.active) {
        this.activeIndex = "active";
      }
    });
  },
  methods: {
      
   
    toggleCollapse() {
      this.$root.$emit("collapse-toggle", {});
    },
    exit() {
      localStorage.setItem("access_token", "");
      // this.$identity.deauth()
      // window.$identity.deauth()
      this.$router.push({ name: "login" });
    },
  },
};
</script>

<style lang="css" scoped>

.burger {
  font-size: 20px !important;
  color: rgba(0, 0, 0, 0.5);
}
.burger:hover {
  color: rgba(0, 0, 0, 0.7);
  transition: all 100ms ease-in-out;
}
.el-dropdown-link {
  width: 100%;
}
.logo {
  width: 220px;
  text-align: center;
}
.el-menu--horizontal > .el-menu-item.is-active {
  border: 0;
}
.el-menu-demo li {
  padding: 0 10px;
}

</style>
