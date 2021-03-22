<template>
  <div class="card">
    <div class="card-body">
      <legend>Сессии</legend>
      <el-table
        :data="user.sessions"
        border>
        <el-table-column
          prop="session_date"
          label="Дата сессии">
          <template slot-scope="scope">
            <el-link :href="`/admin/sessions/#/view/${scope.row.user.session_id}`" type="primary" target="_blank" :underline="false">
              {{ scope.row.session_date }}
            </el-link>
          </template>
        </el-table-column>
        <el-table-column
          prop="comment"
          label="Комментарий к сессии">
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
export default {
  name: "ClientSessions",
  data() {
    return {
      actions: {
        rest: '/api/v1/users',
      },
      errors: [],
    }
  },
  props: {
    user: {
      type: Object,
    },
  },
}
</script>

<style scoped>

</style>
