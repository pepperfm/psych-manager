<template>
  <div class="app-wrapper">
<!--    <div v-if="device==='mobile'&&sidebar.opened" class="drawer-bg" @click="handleClickOutside"/>-->
    <div class="main-container">
      <header />
      <app />
    </div>
  </div>
</template>

<script>
import Header from './Header'
import Aside from './Aside'
import App from '@components/home/App'

export default {
  name: "Layout",
  components: {
    Header,
    Aside,
    App,
  }
}
</script>

<style rel="stylesheet/css" lang="css" scoped>

.app-wrapper {
  position: relative;
  height: 100%;
  width: 100%;
}
.app-wrapper .mobile.openSidebar{
  position: fixed;
  top: 0;
}
.drawer-bg {
  background: #000;
  opacity: 0.3;
  width: 100%;
  top: 0;
  height: 100%;
  position: absolute;
  z-index: 999;
}
</style>
