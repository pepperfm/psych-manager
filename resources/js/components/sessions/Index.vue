<template>
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <el-button @click="create()" type="primary" plain>Добавить сессию</el-button>
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
      <div class="card-body" v-loading="loading">
        <el-tabs type="card">

          <el-tab-pane label="Таблица">
            <table class="table table-responsive table-hover">
              <thead>
                <tr>
                  <th @click="sorting('session_date')" class="clickable" width="10%">
                    Дата сессии
                    <i
                      v-if="filters.sort.field === 'session_date'"
                      class="pull-right fa"
                      v-bind:class="{
                        'fas fa-sort-amount-down': !filters.sort.order,
                        'fas fa-sort-amount-up' : filters.sort.order
                      }"></i>
                  </th>
                  <th width="10%">Формат общения</th>
                  <th width="15%">Клиент</th>
                  <th width="15%">
                    Способ связи
                    <i
                      v-if="filters.sort.field === 'status'"
                      class="pull-right fa"
                      v-bind:class="{
                        'fas fa-sort-amount-down': !filters.sort.order,
                        'fas fa-sort-amount-up' : filters.sort.order
                      }"></i>
                  </th>
                  <th width="40%">Комментарий/План</th>
                  <th width="10%">Действия</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="search--field">
                    <el-date-picker
                      v-model="filters.fields.date_range"
                      type="daterange"
                      format="dd.MM.yyyy – HH:mm"
                      value-format="yyyy-MM-dd HH:mm"
                      unlink-panels
                      range-separator="-"
                      start-placeholder="Искать С этой даты"
                      end-placeholder="По эту дату"
                      @change="getRecords()"
                      size="small"
                      :picker-options="pickerOptions">
                    </el-date-picker>
                  </td>
                  <td class="search--field">
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
                  <td class="search--field">
                    <el-input placeholder="Введите имя" v-model="filters.fields.user_name" size="small" @input="getRecords()" clearable></el-input>
                  </td>
                  <td class="search--field">
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
                  <td></td>
                  <td class="search--field">
                    <el-button type="warning" size="small" plain @click="clearFilters()" class="w-100">
                      <i class="el-icon-s-open"></i>
                      Сбросить фильтры
                    </el-button>
                  </td>
                </tr>
                <tr v-for="(session, index) in sessions" :key="index">
                  <td v-text="session.session_date"></td>
                  <td><i :class="session.client.meeting_type_icon"></i></td>
                  <td>
                    <el-link :href="`/admin/users/#/view/${session.client.id}`" type="primary" target="_blank">
                      {{ session.client.name }}
                    </el-link>
                  </td>
                  <td>
                    <span>{{ session.client.connection_type_string }}</span>
                  </td>
                  <td v-text="session.comment"></td>
                  <td class="actions">
                    <el-button
                      size="mini"
                      type="warning"
                      plain
                      icon="el-icon-edit"
                      @click="edit(session)">
                    </el-button>
                    <el-popconfirm
                      title="Точно?"
                      confirm-button-text="Да"
                      cancel-button-text="Нет"
                      @confirm="remove(session)"
                    >
                      <el-button
                        size="mini"
                        type="danger"
                        plain
                        slot="reference"
                        icon="el-icon-delete">
                      </el-button>
                    </el-popconfirm>
                  </td>
                </tr>
              </tbody>
            </table>
          </el-tab-pane>

          <el-tab-pane label="Календарь">
            <vue-cal
              class="vuecal--blue-theme"
              style="height: 90vh"
              :time-from="9 * 60"
              :time-to="22 * 60 + 15"
              :time-step="30"
              active-view="month"
              click-to-navigate
              :disable-views="['years', 'year']"
              locale="ru"
              :events="calendarSessions"
              events-on-month-view="short"
              :on-event-click="onEventClick"
            >
            </vue-cal>
            <el-dialog :title="calendarItem.name" :visible.sync="showCalendarEvent">
              <table class="table">
                <tr>
                  <td class="td-heading">Время</td>
                  <td class="td-text" v-text="calendarItem.session_time"></td>
                </tr>
                <tr>
                  <td class="td-heading">Телефон</td>
                  <td class="td-text">{{ calendarItem.phone }}</td>
                </tr>
                <tr>
                  <td class="td-heading">Способ связи</td>
                  <td class="td-text" v-text="calendarItem.connection_type_string"></td>
                </tr>
                <tr>
                  <td class="td-heading">Телефон или ссылка соц. сети</td>
                  <td class="td-text" v-text="calendarItem.connection_type_link"></td>
                </tr>
                <tr>
                  <td class="td-heading">Формат общения</td>
                  <td class="td-text"><i :class="calendarItem.meeting_type_icon"></i></td>
                </tr>
                <tr>
                  <td class="td-heading">Комментарий</td>
                  <td class="td-text" v-text="calendarItem.comment"></td>
                </tr>
              </table>
              <el-tooltip class="item" effect="dark" content="Удалить сессию" placement="right">
                <el-button type="danger" icon="el-icon-delete" circle @click="remove(calendarItem)"></el-button>
              </el-tooltip>
            </el-dialog>
          </el-tab-pane>

        </el-tabs>
      </div>
    </div>
  </div>
</template>

<script>
import FilterSession from './models/FilterSession.js'
import {mapActions, mapGetters} from "vuex";
import UserFilterMixin from "../_mixins/UserFilterMixin";
import FilterClient from "../clients/models/FilterClient";

export default {
  name: "Index",
  mixins: [UserFilterMixin],
  data() {
    return {
      actions: {
        rest: '/api/v1/sessions',
      },
      filters: JSON.parse(JSON.stringify(FilterSession)),
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
      moduleName: 'sessions',
      sessions: [],
      total: 0,
      calendarItem: {},
      showCalendarEvent: false,
      loading: true,
      saveSessionAlias: 'saveSessionAlias',
    }
  },
  computed: {
    ...mapGetters({
      calendarSessions: 'sessions/calendarSessions',
      meeting_types: 'staticData/meeting_types',
      connection_types: 'staticData/connection_types',
    }),
  },
  async created() {
    let response = await this.getUserFilters(this.moduleName)
    this.filters = Object.keys(response.filters).length > 0 ? response.filters : JSON.parse(JSON.stringify(FilterSession))

    if (this.calendarSessions.length <= 0) {
      await this.getCalendarSessions();
    }
    if (this.meeting_types.length <= 0) {
      await this.getMeetingTypes();
    }
    if (this.connection_types.length <= 0) {
      await this.getConnectionTypes();
    }
    await this.getRecords()
  },
  methods: {
    ...mapActions({
      getCalendarSessions: 'sessions/getCalendarSessions',
      getConnectionTypes: 'staticData/getConnectionTypes',
      getMeetingTypes: 'staticData/getMeetingTypes',
    }),
    async getRecords() {
      try {
        await this.setUserFilters(this.moduleName, this.filters)

        let response = await this.$http.get(this.actions.rest, { params: { options: this.filters }})
        this.sessions = response.data.data.sessions
        this.total = response.data.data.total
        this.loading = false
      } catch (e) {

      }
    },

    onEventClick(event, e) {
      this.calendarItem = event
      this.showCalendarEvent = true

      e.stopPropagation()
    },

    async edit(model) {
      await this.$router.push({name: `${this.moduleName}.update`, params: { id: model.id }})
    },

    async create() {
      await this.$router.push({name: `${this.moduleName}.create`})
    },

    async remove(session) {
      this.errors = []
      try {
        await this.$http.delete(`${this.actions.rest}/${session.id}`)
        this.showCalendarEvent = false
        await this.getRecords()
        await this.getCalendarSessions()
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
      this.filters = JSON.parse(JSON.stringify(FilterClient))
      await this.clearUserFilters(this.moduleName)
      await this.getRecords()
    },
  }
}
</script>

<style lang="css">
.clickable {
  cursor: pointer;
}

.search--field {
  padding: 0px !important;
}

.page-link {
  color: #007bff !important;
}

.not-set {
  color: red;
}

.td-heading {
  width: 35%;
}

.td-text {
  width: 65%;
}

@media (min-width: 1200px) {
  .table-responsive {
    display: table;
  }
}
</style>
