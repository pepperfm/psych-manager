<template>
  <div class="col-12">
    <div class="card">
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
          <tr v-for="(user, index) in users" :key="index">
            <td class="clickable" @click="showUserInfo(user)" v-if="filters.visibleFields.name">
              <el-link :type="user.name_label" :underline="false">{{ user.name }}</el-link>
            </td>
            <td v-text="user.category" class="clickable" @click="showUserInfo(user)" v-if="filters.visibleFields.category"></td>
            <td v-text="user.phone" v-if="filters.visibleFields.phone"></td>
            <td v-text="user.email" v-if="filters.visibleFields.email"></td>
            <td v-text="user.connection_type_string" v-if="filters.visibleFields.connection_type"></td>
            <td class="clickable text-center" @click="showUserInfo(user)" v-if="filters.visibleFields.meeting_type">
              <i :class="user.meeting_type_icon"></i>
            </td>
            <td v-if="filters.visibleFields.session_date" class="text-center">
              <el-link :href="`/admin/sessions/#/view/${user.session_id}`" type="info" target="_blank" :underline="false">
                {{ user.session_date }}
              </el-link>
            </td>
            <td class="actions">
              <small v-if="user.deleted">Общение завершено</small>
              <el-button
                v-if="!user.deleted"
                size="mini"
                type="warning"
                plain
                icon="el-icon-edit"
                @click="edit(user)">
              </el-button>
              <el-popconfirm
                v-if="!user.deleted"
                title="Завершить общение с клиентом?"
                confirm-button-text="Да"
                cancel-button-text="Нет"
                @confirm="remove(user)"
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

    <el-dialog :title="user.name" :visible.sync="showUserDetails">
      <user-details :user="user"></user-details>
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

      getCategories: '/api/v1/static-data/get-categories',
      getConnectionTypes: '/api/v1/static-data/get-connection-types',
      getMeetingTypes: '/api/v1/static-data/get-meeting-types',
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
    users: [],
    user: {},
    connection_types: [],
    meeting_types: [],
    categories: [],
    total: 0,
    showUserDetails: false,
    fieldsDialogIsVisible: false,
    loading: true,
    saveUserAlias: 'saveUserAlias',
  }),
  async created() {
    await this.getRecords();
  },
  methods: {
    async getRecords() {
      let response = await this.$http.get(this.actions.rest, { params: { options: this.filters }})
      this.tableData = response.data.data.users
      this.tableItem = response.data.data.users[5].id
    },
  },
};
</script>

<style scoped>
</style>
