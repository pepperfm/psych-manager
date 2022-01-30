<template>
  <div class="col-xs-12">
    <div class="row">
      <div class="col-xs-12 col-md-12">
        <div class="card">
          <div class="card-header">
            <el-button @click="backToIndex()" type="warning">
              <i class="el-icon-d-arrow-left"></i>
              Назад
            </el-button>
            <el-button type="success" @click="save()">
              <i class="el-icon-check"></i>
              Сохранить
            </el-button>
          </div>
        </div>
      </div>
    </div>
    <div class="row" v-loading="loading">
      <div class="col-xl-24">
        <div class="card">
          <div class="card-body">
            <h4>Основная информация</h4>

            <div class="form-group">
              <label>Клиент</label>
              <el-select v-model="session.client_id" placeholder="Введите/выберите имя" size="medium" filterable remote clearable>
                <el-option
                  v-for="client in clients"
                  :key="client.id"
                  :label="client.name"
                  :value="client.id"
                >
                </el-option>
              </el-select>
            </div>
            <!-- TODO: установить интервал сессий -->
            <div class="form-group">
              <label>Дата и время сессии</label>
              <el-date-picker
                v-model="session.session_date"
                type="datetime"
                placeholder="Select date and time"
                format="dd.MM.yyyy HH:mm"
                value-format="yyyy-MM-dd HH:mm:ss"
                unlink-panels
                range-separator="-"
                size="medium"
                default-time="12:00:00"
              >
              </el-date-picker>
            </div>
            <div class="form-group">
              <label>План на сессию</label>
              <el-input
                v-model="session.comment"
                placeholder=""
                type="textarea"
                resize="none"
                :rows="6"
                size="medium">
              </el-input>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Session from "./models/Session";
import {mapGetters, mapActions} from "vuex";

export default {

  name: "Form",
  data() {
    return {
      actions: {
        rest: '/api/v1/sessions',
      },
      session: JSON.parse(JSON.stringify(Session)),
      loading: true,
      errors: [],
    }
  },
  computed: {
    ...mapGetters({
      clients: 'sessions/clients',
    }),
  },

  async created() {

    if (this.$route.params.id) {
      await this.getSession(this.$route.params.id);
    }
    await this.getClients()
    this.loading = false;
  },
  methods: {

    ...mapActions({
      getClients: 'sessions/getClients',
    }),

    async getSession(id) {
      try {
        let response = await this.$http.get(`${this.actions.rest}/${id}`)
        this.session = response.data.data.session
      } catch (e) {
        this.$message.error('Error')
      }
    },

    async save() {
      this.errors = []
      try {
        let response
        if (this.session.id) {
          response = await this.$http.put(`${this.actions.rest}/${this.session.id}`, this.session)
        } else {
          response = await this.$http.post(this.actions.rest, this.session)
        }
        this.$message.success(response.data.message)
        await this.$router.push({name: 'sessions'})
      } catch (e) {
        if (!e.response) {
          throw e
        }
        switch (e.response.status) {
          case 422:
            this.errors = e.response.data

            break;
          default:
            this.$message.error('Server error')
        }
      } finally {

      }
    },

    backToIndex() {
      this.$router.push({name: 'sessions'})
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
</style>
