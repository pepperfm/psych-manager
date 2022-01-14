<template>
  <el-row>
    <el-col>
      <el-menu
        style="display: flex"
        :default-active="activeIndex"
        class="el-menu-demo hidden-sm-and-down"
        mode="horizontal"
        @select="handleSelect"
      >
        <el-menu-item index="1" class="logo">
          <svg
            width="40"
            height="40"
            viewBox="0 0 40 40"
            fill="black"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M39.9998 20.0501C39.9998 8.98246 31.0398 0 19.9998 0C8.95982 0 -0.000183105 8.98246 -0.000183105 20.0501C-0.000183105 29.7544 6.87982 37.8346 15.9998 39.6992V26.0652H11.9998V20.0501H15.9998V15.0376C15.9998 11.1679 19.1398 8.02005 22.9998 8.02005H27.9998V14.0351H23.9998C22.8998 14.0351 21.9998 14.9373 21.9998 16.0401V20.0501H27.9998V26.0652H21.9998V40C32.0998 38.9975 39.9998 30.4561 39.9998 20.0501Z"
              fill="black"
            />
          </svg>
        </el-menu-item>

        <el-menu-item index="2" @click="toggleCollapse(); toggleIcon()">
          <i :class="[ 'el-icon-s-unfold',  {'rotate': toggled },'burger']"></i
        ></el-menu-item>

        <el-menu-item
          index="3"
          style="
            font-size: 18px;

            text-align: left;
            border-bottom: none;
          "
        >{{ pathName }}
        </el-menu-item>

<!--        <el-submenu style="margin-left: auto" index="4">
          <template class="header-submenu-title" slot="title"> lang</template>
          <el-menu-item index="4-1" style="text-align: center" @click="exit()">
            <span slot="title">Русский</span>
          </el-menu-item>
          <el-menu-item index="4-2" style="text-align: center" @click="exit()">
            <span slot="title">English</span>
          </el-menu-item>
        </el-submenu>

        <el-submenu index="5">
          <template class="header-submenu-title" slot="title">
            CurrentUser
          </template>
          <el-menu-item index="2-1" style="text-align: center" @click="exit()">
            <span slot="title">user</span>
          </el-menu-item>
          <el-menu-item index="2-2" style="text-align: center" @click="exit()">
            <span slot="title">settings</span>
          </el-menu-item>
          <el-menu-item index="2-3" style="text-align: center" @click="exit()">
            <span slot="title">lorem</span>
          </el-menu-item>
          <el-menu-item index="2-4" style="text-align: center" @click="exit()">
            <span slot="title" style="color: red">Выход</span>
          </el-menu-item>
        </el-submenu>-->

      </el-menu>
    </el-col>
  </el-row>
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
