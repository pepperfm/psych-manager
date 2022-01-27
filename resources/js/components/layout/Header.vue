<template>
    <nav class="main-header navbar d-flex">
        <div index="2" @click="toggleCollapse(); toggleIcon()">
          <i :class="[ 'fas fa-bars',  {'rotate': toggled },'burger']"></i
        ></div>
       <el-dropdown @command="handleCommand">
        <span class="currentUser" >{{ user.name }}</span>
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item command="profile">Профиль</el-dropdown-item>
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
      toggled: false,
      toggled2: true,
      user: $identity,
    };
  },
  async created() {
    this.$root.$on("set-path-name", (obj) => {
      this.pathName = obj.name;
      this.activeIndex = obj.activeIndex ? obj.activeIndex : "1";
      if (obj.active) {
        this.activeIndex = "active";
      }
    });
  },
  methods: {
    toggleIcon() {
      this.toggled = !this.toggled;
    },
    toggleCollapse() {
      this.$root.$emit("collapse-toggle", {});
    },
    exit() {
      localStorage.setItem("accessToken", "");
      // this.$identity.deauth()
      // window.$identity.deauth()
      this.$router.push({name: "login"});
    },
    handleCommand(command) {
      this.$router.push(command)
    },
  },
};
</script>

<style lang="css" scoped>
.rotate {
  transform: rotate(270deg);
}
.burger {
  transition: all 300ms ease-in-out;
  font-size: 20px !important;
  color: rgba(0, 0, 0, 0.5);
}
.burger:hover {
  color: rgba(0, 0, 0, 0.7);
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
