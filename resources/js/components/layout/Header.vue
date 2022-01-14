<template>
    <nav class="main-header navbar d-flex">
      <div>1</div>
      <div>2</div>
      <div>3</div>
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
    handleSelect(key, keyPath) {},
    toggleIcon() {
      this.toggled = !this.toggled;
     },
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
.rotate {
  transform: rotate(-180deg);
}
.burger {
  transition: all 300ms ease-in-out;
  font-size: 25px !important;
}
.burger:hover {
  color: #333;
  transition: all 300ms ease-in-out;
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
