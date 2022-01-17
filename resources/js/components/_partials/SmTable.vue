<template>
  <div class="table-responsive">

    <table class="table table-hover table-bordered ">
      <thead class="mb-50" >
        <tr>
          <th v-if="thKey < 7" v-for="(col, thKey) in row" :key="thKey">
            {{ col.label }}
          </th>
          <th>
            Operations
          </th>
        </tr>
      </thead>
      <th v-if="thKey < 2" v-for="(col, thKey) in row" :key="thKey">

        <el-row>
          <el-col :span="24">
            <el-select
              v-model="value"
              multiple
              filterable
              remote
              reserve-keyword
              placeholder="Please enter a keyword"
              :remote-method="remoteMethod"
              :loading="loading">
              <el-option
                v-for="item in options"
                :key="item.value"
                :label="item.label"
                :value="item.value">
              </el-option>
            </el-select>
          </el-col>
        </el-row>
      </th>
      <tbody>
        <tr  v-for="(item, index) in tableData" :key="index">
          <td v-if="colKey < 7" v-for="(col, colKey) in row" :key="colKey">
            {{ item[col.model] }}
          </td>
          <td class="align-middle">
            <div class="d-flex">
              <el-button icon="el-icon-info" type="info" plain circle></el-button>
              <el-button @click="$router.push({ name: 'users_update', params: { id: item.id }})" icon="el-icon-edit" type="warning" plain circle></el-button>
              <el-button icon="el-icon-delete" type="danger" circle></el-button>
            </div>
          </td>
        </tr>
      </tbody>


    </table>
  </div>

</template>

<script>

export default {
  data() {
    return {

    }
  },
  props: {
    tableData: {
      type: Array,
      required: true,
    },
    row: {
      type: Array,
      required: true,
    }
  },
}

</script>

<style>

</style>
