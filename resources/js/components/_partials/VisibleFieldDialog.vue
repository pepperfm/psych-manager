<template>
  <div class="row">
    <div class="col-12">
      <el-checkbox v-model="fields.name">Имя</el-checkbox>
    </div>
    <div class="col-12">
      <el-checkbox v-model="fields.category">Категория</el-checkbox>
    </div>
    <div class="col-12">
      <el-checkbox v-model="fields.phone">Телефон</el-checkbox>
    </div>
    <div class="col-12">
      <el-checkbox v-model="fields.email">Email</el-checkbox>
    </div>
    <div class="col-12">
      <el-checkbox v-model="fields.connection_type">Тип связи</el-checkbox>
    </div>
    <div class="col-12">
      <el-checkbox v-model="fields.meeting_type">Формат общения</el-checkbox>
    </div>
    <div class="col-12">
      <el-checkbox v-model="fields.session_date">Дата сессии</el-checkbox>
    </div>
  </div>
</template>
<script>
export default {
  name: "VisibleFieldDialog",
  props: {
    fields: {
      type: Object,
      required: true,
    }
  }
}
</script>

<style scoped>

</style>
