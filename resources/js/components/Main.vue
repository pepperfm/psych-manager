<template>
  <div>
    <div class="container-fluid">
      <h1>Панель управления</h1>
    </div>
    <div class="content">
      <div class="content-fluid">
        <p>{{ categories }}</p>
        <p>{{ connection_types }}</p>
        <p>{{ meeting_types }}</p>


      </div>
    </div>
  </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";

export default {
    name: "Main",
  computed: {
    ...mapGetters({
      categories: 'staticData/categories',
      connection_types: 'staticData/connection_types',
      meeting_types: 'staticData/meeting_types',
      session_clients: 'sessions/clients',

    }),
  },
  async created() {
    await this.getCategories();
    await this.getConnectionTypes();
    await this.getMeetingTypes();
    await this.getClients();

  },
  methods: {
    ...mapActions({
      getCategories: 'staticData/getCategories',
      getConnectionTypes: 'staticData/getConnectionTypes',
      getMeetingTypes: 'staticData/getMeetingTypes',
      getClients: 'sessions/getClients'
    }),
  },
}
</script>

<style scoped>
   
</style>
