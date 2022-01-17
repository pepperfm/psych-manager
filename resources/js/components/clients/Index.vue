<template>
  <div>
    <sm-table :row="tableItem" :table-data="tableData"></sm-table>
  </div>
</template>

<script>
import SmTable from "../_partials/SmTable.vue";
export default {
  name: "Index",
  components: {
    SmTable,
  },
  data() {
    return {
       actions: {
        rest: '/api/v1/users',
      },
      tableData: [],
      tableItem: [],
    };
  },
  async created() {
    await this.getRecords();
  },
  methods: {
    async getRecords() {
      let response = await this.$http.get(this.actions.rest)
      this.tableData = response.data.data.users
      this.tableItem = response.data.data.users[5].id
    },
  },
};
</script>

<style scoped>
</style>
