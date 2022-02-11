<template>
  <div class="col-8">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12 text-left">
                <el-button type="success" @click="save()">
                  <i class="el-icon-check"></i>
                  Сохранить
                </el-button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-4">
        <div class="card">
          <div class="card-body" v-loading="loading">
            <div>
              <h4>Основная информация</h4>
            </div>
            <el-form ref="user" :model="user">
              <div class="form-group">
                <label>Имя</label>
                <el-input v-model="user.name" size="small"></el-input>
              </div>
              <div class="form-group">
                <label>Email</label>
                <el-input v-model="user.email" size="small"></el-input>
              </div>
              <div class="form-group">
                <label>Телефон</label>
                <span class="not-set">*</span>
                <el-input v-model="user.phone" size="small"></el-input>
              </div>
              <div class="form-group">
                <label>Пол</label>
                <el-select v-model="user.gender" size="small" class="w-100">
                  <el-option label="Жещнина" :value="0"></el-option>
                  <el-option label="Мужчина" :value="1"></el-option>
                </el-select>
              </div>
              <div class="form-group">
                <label>Личный сайт</label>
                <el-input
                  v-model="user.connection_type_link"
                  placeholder="https://"
                  size="small"
                  clearable>
                </el-input>
              </div>
            </el-form>
          </div>
        </div>
      </div>
      <div class="col-6">
        <div class="card">
          <div class="card-body" v-loading="loading">
            <div>
              <h4>Специализация</h4>
            </div>
            <form class="form-control">
              <div class="form-group">
                <label>Категории</label>
              </div>
              <div class="form-group">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Form",
  data() {
    return {
      actions: {
        rest: '/api/v1/users',
        restCategories: '/api/v1/categories',
        syncCategories: '/api/v1/users/sync-categories',
        getGenderList: '/api/v1/static-data/gender-list',
      },
      gender_list: [],
      categories: [],
      selected_categories: [],
      errors: [],
      new_category_name: '',
      loading: true,
      user: {
        name: '',
        email: '',
        phone: '',
        gender: '',
        group_id: '',
        connection_type_link: '',
        categories: []
      },
      addCategoryInput: false,
      tooltips: this.$tooltips
    }
  },
  async created() {
    await this.getGenderList()
    await this.getUser();
    await this.getCategories();
    this.loading = false
  },
  methods: {
    async getUser() {
      let response = await this.$http.get(this.actions.rest)
      this.user = response.data.data.user
    },
    async getCategories() {
      try {
        let response = await this.$http.get(this.actions.restCategories)
        this.categories = response.data.data.categories
      } catch (e) {

      }
    },
    async getGenderList() {
      let response = await this.$http.get(this.actions.getGenderList)
      this.gender_list = response.data.data.gender_list
    },

    async syncCategories() {
      let response = await this.$http.post(this.actions.syncCategories, {
        categories: this.selected_categories
      })
    },
    async addNewCategory() {
      let response = await this.$http.post(this.actions.restCategories, this.new_category_name)
    },

    async save() {
      this.errors = []
      try {
        let response = await this.$http.put(`${this.actions.rest}/${this.user.id}`, this.user)
        this.$message.success(response.data.message)
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

    handleClose(tag) {
      this.categories.splice(this.categories.indexOf(tag), 1);
    },
    showInput() {
      this.addCategoryInput = true;
      this.$nextTick(_ => {
        this.$refs.saveTagInput.$refs.input.focus();
      });
    },
    handleInputConfirm() {
      let inputValue = this.new_category_name;
      if (inputValue) {
        this.categories.push(inputValue);
      }
      this.addCategoryInput = false;
      this.new_category_name = '';
    }

  }
}
</script>

<style scoped>
  .not-set {
    color: red;
  }
  .button-new-tag {
    margin-left: 10px;
    height: 32px;
    line-height: 30px;
    padding-top: 0;
    padding-bottom: 0;
  }
  .input-new-tag {
    width: auto;
    margin-left: 10px;
    vertical-align: bottom;
  }
</style>
