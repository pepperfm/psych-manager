<template>
  <div class="col-xs-12">
    <div class="row">
      <div class="col-xs-4 col-md-4">
        <div class="card">
          <div class="card-header">
            <el-button @click="backToIndex()" type="warning">
              <i class="el-icon-d-arrow-left"></i>
              Назад
            </el-button>
            <el-button type="success" @click="save()">
              <i class="el-icon-message"></i>
              Отправить
            </el-button>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 col-md-4">
        <div class="card">
          <div class="card-body">

            <div class="form-group">
              <label>Названия</label>
              <el-select
                v-model="category.names"
                multiple
                filterable
                allow-create
                default-first-option
                clearable
                placeholder="Введите несколько названий">
                <el-option-group
                  v-for="group in categories"
                  :key="group.label"
                  :label="group.label">
                  <el-option
                    v-for="category in group.options"
                    :key="category.id"
                    :label="category.name"
                    :value="category.name"
                    :disabled="category.disabled">
                  </el-option>
                </el-option-group>
              </el-select>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Category from "./models/Category";

export default {

  name: "Form",
  data() {
    return {
      actions: {
        rest: '/api/v1/categories',
        sendConfirmEmail: '/api/v1/confirm-categories-create'
      },
      category: JSON.parse(JSON.stringify(Category)),
      categories: [],
      errors: [],
    }
  },
  async created() {
    if (this.$route.params.id) {
      await this.getCategory(this.$route.params.id);
    }
    this.categories = JSON.parse(localStorage.categories)
  },
  methods: {
    async getCategory(id) {
      try {
        let response = await this.$http.get(`${this.actions.rest}/${id}`)
        this.category = response.data.data.category
      } catch (e) {
        this.$message.error('Error')
      } finally {

      }
    },

    async save() {
      this.errors = []
      try {
        let response = await this.$http.post(this.actions.sendConfirmEmail, this.category)
        // if (this.category.id) {
        //   response = await this.$http.put(`${this.actions.rest}/${this.category.id}`, this.category)
        // } else {
        //   response = await this.$http.post(this.actions.rest, this.category)
        // }
        this.$message.success(response.data.message)
        await this.$router.push({name: 'category-index'})
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
      this.$router.push({name: 'category-index'})
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
