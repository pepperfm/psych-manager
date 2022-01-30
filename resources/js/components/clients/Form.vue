<template>
  <div class="col-12">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12 text-left">
                <el-button @click="backToIndex()" type="warning">
                  <i class="el-icon-d-arrow-left"></i>
                  Назад
                </el-button>
                <el-button type="success" @click="save()">
                  <i class="el-icon-check"></i>
                  Сохранить
                </el-button>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                <el-button type="info" @click="clientSessions = true">
                  <i class="el-icon-data-analysis"></i>
                  Сессии клиента
                </el-button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row" v-loading="loading">
      <div class="col-lg-4 col-md-6">
        <div class="card">
          <div class="card-body">
            <h4>Основная информация</h4>

            <div class="form-group">
              <label>Имя</label>
              <el-input v-model="client.name" placeholder="Имя" size="medium" clearable></el-input>
            </div>
            <div class="form-group row">
              <div class="col-6">
                <label>Email</label>
                <el-input v-model="client.email" placeholder="Email" size="medium" clearable></el-input>
              </div>
              <div class="col-6">
                <label>Телефон</label>
                <el-input v-model="client.phone" placeholder="Телефон" size="medium" clearable></el-input>
              </div>
            </div>
            <div class="form-group">
              <label>Дата рождения</label>
              <el-date-picker
                v-model="client.birthday_date"
                type="date"
                placeholder="Выберите день"
                size="medium"
                format="dd.MM.yyyy"
                value-format="yyyy-MM-dd"
                clearable>
              </el-date-picker>
            </div>

            <el-divider></el-divider>

            <div class="form-group row">
              <div class="col-6">
                <label>Способ связи</label>
                <span class="not-set">*</span>
                <el-select
                  v-model="client.connection_type_id"
                  placeholder="Способ связи"
                  clearable
                  size="medium"
                >
                  <el-option
                    v-for="item in connection_types"
                    :key=item.id
                    :label="item.name"
                    :value="item.id">
                  </el-option>
                </el-select>
              </div>
              <div class="col-6">
                <label>Пол</label>
                <el-select
                  v-model="client.gender"
                  placeholder="Пол"
                  clearable
                  size="medium"
                >
                  <el-option
                    v-for="item in gender_list"
                    :key=item.id
                    :label="item.name"
                    :value="item.id">
                  </el-option>
                </el-select>
              </div>
            </div>
            <div class="form-group">
              <el-input
                v-model="client.connection_type_link"
                placeholder="Телефон или ссылка социальной сети"
                size="medium"
                clearable>
              </el-input>
            </div>
            <div class="form-group">
              <el-input
                v-model="client.curator_contacts"
                placeholder="Контакты опекуна"
                size="medium"
                clearable>
              </el-input>
            </div>

            <el-divider></el-divider>

            <div class="form-group">
              <label>Формат общения</label>
              <el-select
                class="w-100"
                v-model="client.meeting_type"
                clearable
                size="medium"
              >
                <el-option
                  v-for="item in meeting_types"
                  :key=item.id
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>
            </div>
            <!-- TODO: если постоянная ссылка для онлайн общения, подумать, нужна ли она -->
            <div class="form-group">
              <label>Категория</label>
              <el-select
                class="w-100"
                v-model="client.category_id"
                clearable
                size="medium"
              >
                <el-option
                  v-for="category in categories"
                  :key=category.id
                  :label="category.name"
                  :value="category.id">
                </el-option>
              </el-select>
            </div>
          </div>
        </div>
      </div>
      <div class="row col-lg-8 col-md-6">
        <div class="col-lg-6 col-md-12">
          <div class="card card-textarea">
            <div class="card-body">
              <legend>О терапии</legend>

              <div class="form-group">
                <label>Запрос клиента</label>
                <el-input
                  v-model="client.therapy.request"
                  placeholder=""
                  type="textarea"
                  resize="none"
                  :rows="2"
                  size="medium">
                </el-input>
              </div>

              <div class="form-group row">
                <div class="col-12">
                  <label>Степень тяжести кейса</label>
                  <el-select
                    v-model="client.therapy.problem_severity"
                    placeholder="Тяжесть"
                    clearable
                    size="medium"
                  >
                    <el-option
                      v-for="item in problem_severity"
                      :key=item.id
                      :label="item.name"
                      :value="item.id">
                    </el-option>
                  </el-select>
                </div>
              </div>

              <div class="form-group">
                <label>План на терапию</label>
                <el-input
                  v-model="client.therapy.plan"
                  placeholder=""
                  type="textarea"
                  resize="none"
                  :rows="4"
                  size="medium">
                </el-input>
              </div>

              <div class="form-group">
                <label>Моя концептуализация</label>
                <el-input
                  v-model="client.therapy.concept_vision"
                  placeholder=""
                  type="textarea"
                  resize="none"
                  :rows="8"
                  size="medium">
                </el-input>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6 col-md-12">
          <div class="card card-textarea">
            <div class="card-body">
              <h4>Заметки</h4>

              <div class="form-group">
                <el-input
                  v-model="client.therapy.notes"
                  placeholder=""
                  type="textarea"
                  resize="none"
                  :rows="8"
                  size="medium">
                </el-input>
              </div>
            </div>
          </div>
        </div>
      </div>

      <el-drawer
        :visible.sync="clientSessions"
        direction="rtl"
        size="50%">
        <sessions :user="client"></sessions>
      </el-drawer>
    </div>
  </div>
</template>

<script>
import Client from "./models/Client";
import Sessions from "../_partials/ClientSessions";

export default {

  name: "Form",
  data() {
    return {
      actions: {
        rest: '/api/v1/clients',
        // getProblems: '/api/v1/problems',

        getCategories: '/api/v1/static-data/categories',
        getConnectionTypes: '/api/v1/static-data/connection-types',
        getGenderList: '/api/v1/static-data/gender-list',
        getMeetingTypes: '/api/v1/static-data/meeting-types',
      },
      client: JSON.parse(JSON.stringify(Client)),
      connection_types: [],
      meeting_types: [],
      gender_list: [],
      categories: [],
      errors: [],
      problem_severity: [
        {id: 1, name: 1},
        {id: 2, name: 2},
        {id: 3, name: 3},
        {id: 4, name: 4},
        {id: 5, name: 5}
      ],
      clientSessions: false,
      isRouterUpdate: false,
      loading: true,
    }
  },
  components: {
    Sessions
  },
  async created() {
    console.log()
    if (this.$route.params.id) {
      this.isRouterUpdate = true
      await this.getUser(this.$route.params.id);
    }
    await this.getConnectionTypes()
    await this.getMeetingTypes()
    await this.getGenderList()
    await this.getCategories()
    this.loading = false
  },
  methods: {
    async getUser(id) {
      try {
        let response = await this.$http.get(`${this.actions.rest}/${id}`)
        this.client = response.data.data.client
      } catch (e) {
        this.$message.error('Error')
      } finally {

      }
    },

    async getConnectionTypes() {
      let response = await this.$http.get(this.actions.getConnectionTypes)
      this.connection_types = response.data.data.connection_types
    },
    async getMeetingTypes() {
      let response = await this.$http.get(this.actions.getMeetingTypes)
      this.meeting_types = response.data.data.meeting_types
    },
    async getGenderList() {
      let response = await this.$http.get(this.actions.getGenderList)
      this.gender_list = response.data.data.gender_list
    },
    // async getProblems() {
    //   let response = await this.$http.get(this.actions.getProblems)
    //   this.problems = response.data.data.problems
    // },
    async getCategories() {
      let response = await this.$http.get(`${this.actions.getCategories}`)
      this.categories = response.data.data.categories
    },

    async save() {
      this.errors = []
      try {
        let response
        if (this.client.id) {
        response = await this.$http.put(`${this.actions.rest}/${this.client.id}`, this.client)
        } else {
          response = await this.$http.post(this.actions.rest, this.client)
        }
        this.$message.success(response.data.message)
        await this.$router.push({name: 'clients'})
      } catch (e) {
        if (!e.response) {
          throw e
        }
        this.errors = e.response.data
        switch (e.response.status) {
          case 422:
            this.$message.error(this.errors.message)

            break;
          default:
            this.$message.error('Server error')
        }
      }
    },

    backToIndex() {
      this.$router.push({name: 'clients'})
    },
  }
}
</script>

<style scoped>
.contents-inner {
  min-height: 95vh;
  padding: 15px 45px 30px 45px;
}
.el-date-editor.el-input, .el-date-editor.el-input__inner {
  width: 100%;
}
.text-span {
  font: 400 13.3333px Arial;
}
.el-input__icon:after {
  height: 0;
}
.w-50.el-input--mini > .el-input__inner {
  width: 50%;
}
.el-select {
  width: 100%;
}
@media (max-width: 768px) {
  .card-textarea {
    max-height: 330px;
  }
}
</style>
