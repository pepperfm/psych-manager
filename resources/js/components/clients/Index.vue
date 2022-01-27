<template>
  <div class="col-12 ">
    <div class="card shadow-lg">
      <!-- Main container header -->
      <div class="card-header">
        <div class="row">
          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <el-button @click="create()" type="primary" plain>Добавить клиента</el-button>
            <el-button @click="fieldsDialogIsVisible = true" type="info" plain>Видимые поля</el-button>
          </div>
          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
            <el-pagination
              background
              layout="total, prev, pager, next"
              :current-page="filters.pagination.page"
              :page-size="filters.pagination.limit"
              @current-change="handleCurrentChange"
              @size-change="handleSizeChange"
              :total="total"
            >
            </el-pagination>
          </div>
        </div>
      </div>

      <div class="card-body">
        <table class="table table-responsive table-hover" v-loading="loading">
          <thead>
            <!-- Column lables -->
            <tr v-if="filters.visibleFields.name">
              <th @click="sorting('name')" class="clickable">
                Имя <i class="el-icon-sort"></i>
                <i
                  v-if="filters.sort.field === 'name'"
                  class="pull-right fa"
                  v-bind:class="{
                      'fas fa-sort-amount-down-alt': !filters.sort.order,
                      'fas fa-sort-amount-up' : filters.sort.order
                    }"></i>
              </th>
              <th v-if="filters.visibleFields.category">
                Категория
                <i
                  v-if="filters.sort.field === 'category_id'"
                  class="pull-right fa"
                  v-bind:class="{
                      'fas fa-sort-amount-down-alt': !filters.sort.order,
                      'fas fa-sort-amount-up' : filters.sort.order
                    }"></i>
              </th>
              <th v-if="filters.visibleFields.phone">Телефон</th>
              <th v-if="filters.visibleFields.email">Email</th>
              <th v-if="filters.visibleFields.connection_type">Способ связи</th>
              <th v-if="filters.visibleFields.meeting_type" class="text-center">Формат общения</th>
              <th @click="sorting('session_date_users')" class="clickable text-center" v-if="filters.visibleFields.session_date">
                Ближайшая сессия <i class="el-icon-sort"></i>
                <i
                  v-if="filters.sort.field === 'session_date_users'"
                  class="pull-right fa"
                  v-bind:class="{
                      'fas fa-sort-amount-down-alt': !filters.sort.order,
                      'fas fa-sort-amount-up' : filters.sort.order
                    }"></i>
              </th>
              <th>Действия</th>
            </tr>
          </thead>
              <!-- Table inputs -->
          <tbody>
            <tr>
              <td class="search--field" v-if="filters.visibleFields.name">
                <el-input placeholder="Введите имя" v-model="filters.fields.name" size="small" @input="getRecords()" clearable></el-input>
              </td>
              <td class="search--field" v-if="filters.visibleFields.category">
                <el-select
                  v-model="filters.fields.category_id"
                  placeholder="Выберите категорию"
                  size="small"
                  @input="getRecords()"
                  clearable
                >
                  <el-option
                    v-for="category in categories"
                    :key=category.id
                    :label="category.name"
                    :value="category.id">
                  </el-option>
                </el-select>
              </td>
              <td class="search--field" v-if="filters.visibleFields.phone">
                <el-input placeholder="Введите телефон" v-model="filters.fields.phone" size="small" @input="getRecords()" clearable></el-input>
              </td>
              <td class="search--field" v-if="filters.visibleFields.email">
                <el-input placeholder="Введите email" v-model="filters.fields.email" size="small" @input="getRecords()" clearable></el-input>
              </td>
              <td class="search--field" v-if="filters.visibleFields.connection_type">
                <el-select
                  v-model="filters.fields.connection_type"
                  placeholder="Выберите способ"
                  clearable
                  size="small"
                  @input="getRecords()"
                >
                  <el-option
                    v-for="item in connection_types"
                    :key=item.id
                    :label="item.name"
                    :value="item.id">
                  </el-option>
                </el-select>
              </td>
              <td class="search--field" v-if="filters.visibleFields.meeting_type">
                <el-select
                  v-model="filters.fields.meeting_type"
                  placeholder="Выберите формат"
                  clearable
                  size="small"
                  @input="getRecords()"
                >
                  <el-option
                    v-for="item in meeting_types"
                    :key=item.id
                    :label="item.name"
                    :value="item.id">
                  </el-option>
                </el-select>
              </td>
              <td class="search--field" v-if="filters.visibleFields.session_date">
                <el-date-picker
                  v-model="filters.fields.date_range"
                  class="w-100"
                  type="daterange"
                  format="dd.MM.yyyy – HH:mm"
                  value-format="yyyy-MM-dd HH:mm"
                  unlink-panels
                  range-separator="-"
                  start-placeholder="Искать С этой даты"
                  end-placeholder="По эту дату"
                  @change="getRecords()"
                  size="small"
                  align="right"
                  :picker-options="pickerOptions">
                </el-date-picker>
              </td>
              <td class="search--field">
                <el-button type="warning" size="small" plain @click="clearFilters()" class="w-100">
                  <i class="el-icon-s-open"></i>
                  Сбросить фильтры
                </el-button>
              </td>
            </tr>
            <!-- Table rendering -->
            <tr v-for="(client, index) in clients" :key="index">
              <td class="clickable" @click="showClientInfo(client)" v-if="filters.visibleFields.name">
                <el-link :type="client.name_label" :underline="false">{{ client.name }}</el-link>
              </td>
              <td v-text="client.category" class="clickable" @click="showClientInfo(client)" v-if="filters.visibleFields.category"></td>
              <td v-text="client.phone" v-if="filters.visibleFields.phone"></td>
              <td v-text="client.email" v-if="filters.visibleFields.email"></td>
              <td v-text="client.connection_type_string" v-if="filters.visibleFields.connection_type"></td>
              <td class="clickable text-center" @click="showClientInfo(client)" v-if="filters.visibleFields.meeting_type">
                <i :class="client.meeting_type_icon"></i>
              </td>
              <td v-if="filters.visibleFields.session_date" class="text-center">
                <el-link :href="`/admin/sessions/#/view/${client.session_id}`" type="info" target="_blank" :underline="false">
                  {{ client.session_date }}
                </el-link>
              </td>
              <!-- Edit buttons -->
              <td class="actions">
                <small v-if="client.deleted">Общение завершено</small>
                <el-button
                  v-if="!client.deleted"
                  size="mini"
                  type="warning"
                  plain
                  icon="el-icon-edit"
                  @click="edit(client)">
                </el-button>
                <el-popconfirm
                  v-if="!client.deleted"
                  title="Завершить общение с клиентом?"
                  confirm-button-text="Да"
                  cancel-button-text="Нет"
                  @confirm="remove(client)"
                >
                  <el-button
                    size="mini"
                    type="success"
                    plain
                    slot="reference"
                    icon="el-icon-circle-check">
                  </el-button>
                </el-popconfirm>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <el-dialog :title="client.name" :visible.sync="showClientDetails">
      <user-details :client="client"></user-details>
    </el-dialog>

    <el-dialog
      title="Видимые поля"
      :visible.sync="fieldsDialogIsVisible"
      width="30%">
      <visible-field-dialog :fields="filters.visibleFields"></visible-field-dialog>
      <span slot="footer" class="dialog-footer">
        <el-button @click="fieldsDialogIsVisible = false">Отмена</el-button>
        <el-button type="primary" @click="saveDialogFields()">Сохранить</el-button>
      </span>
    </el-dialog>

  </div>
</template>

<script>

import FilterUser from './models/FilterClient.js'
import UserDetails from "../_partials/UserDetails";
import VisibleFieldDialog from "../_partials/VisibleFieldDialog";

export default {
  name: "Index",
  components: {
    FilterUser, UserDetails, VisibleFieldDialog
  },
  data: () => ({
    actions: {
      rest: '/api/v1/clients',

      getCategories: '/api/v1/static-data/categories',
      getConnectionTypes: '/api/v1/static-data/connection-types',
      getMeetingTypes: '/api/v1/static-data/meeting-types',
    },
    filters: JSON.parse(JSON.stringify(FilterUser)),
    pickerOptions: {
      shortcuts: [{
        text: 'День',
        onClick(picker) {
          const end = new Date();
          const start = new Date();
          start.setTime(start.getTime() - 3600 * 1000 * 22.91);
          picker.$emit('pick', [start, end]);
        }
      }, {
        text: 'Неделя',
        onClick(picker) {
          const end = new Date();
          const start = new Date();
          start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
          picker.$emit('pick', [start, end]);
        }
      }, {
        text: 'Месяц',
        onClick(picker) {
          const end = new Date();
          const start = new Date();
          start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
          picker.$emit('pick', [start, end]);
        }
      }, {
        text: '3 месяца',
        onClick(picker) {
          const end = new Date();
          const start = new Date();
          start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
          picker.$emit('pick', [start, end]);
        }
      }]
    },
    moduleName: 'clients',
    clients: [],
    client: {},
    connection_types: [],
    meeting_types: [],
    categories: [],
    total: 0,
    showClientDetails: false,
    fieldsDialogIsVisible: false,
    loading: true,
    saveUserAlias: 'saveUserAlias',
  }),
  async created() {
    await this.getRecords();
    await this.getCategories();
    await this.getMeetingTypes();
    await this.getConnectionTypes();
  },
  methods: {
  async getRecords() {
      try {
        await this.saveFilters()

        let response = await this.$http.get(this.actions.rest, { params: { options: this.filters }})
        this.clients = response.data.data.clients
        this.total = response.data.data.total
        this.loading = false
      } catch (e) {

      }
    },

    async showClientInfo(client) {
      let response = await this.$http.get(`${this.actions.rest}/${client.id}`)
      this.client = response.data.data.client
      this.showClientDetails = true
    },

    async saveFilters() {
      this.$localStorage.removeItem(this.saveUserAlias)
      this.$localStorage.setItem(this.saveUserAlias, JSON.stringify(this.filters))

      this.fieldsDialogIsVisible = false
    },

    async getConnectionTypes() {
      let response = await this.$http.get(this.actions.getConnectionTypes)
      this.connection_types = response.data.data.connection_types
    },
    async getMeetingTypes() {
      let response = await this.$http.get(this.actions.getMeetingTypes)
      this.meeting_types = response.data.data.meeting_types
    },
    async getCategories() {
      let response = await this.$http.get(`${this.actions.getCategories}`)
      this.categories = response.data.data.categories
      console.log(response.data.data);
    },

    async edit(model) {
      await this.$router.push({name: `${this.moduleName}.update`, params: { id: model.id }})
    },

    async create() {
      await this.$router.push({name: 'create'})
    },

    async remove(client) {
      this.errors = []
      try {
        await this.$http.delete('/api/v1/clients/' + client.id)
        await this.getRecords()
      } catch (e) {
        if (!e.response) {
          throw e
        }
        switch (e.response.status) {
          case 422:
            this.errors = e.response.data
            this.$message.error(this.errors.message)
            break;
          default:
            this.$message.error('Server error')
        }
      }
    },


    async sorting(field) {
      this.filters.sort.field = field
      this.filters.sort.order = !this.filters.sort.order

      await this.getRecords()
    },
    async handleCurrentChange(page) {
      this.filters.pagination.page = page

      await this.getRecords()
    },
    async handleSizeChange(size) {
      this.filters.pagination.limit = size

      await this.getRecords()
    },
    async clearFilters() {
      this.filters = JSON.parse(JSON.stringify(FilterUser))

      await this.getRecords()
    },

    saveDialogFields() {
      this.saveFilters()
      this.fieldsDialogIsVisible = false
    },
  }

};
</script>

<style scoped>

</style>
