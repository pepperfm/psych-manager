<template>
  <el-row>
    <el-row class="pb-3">
      <el-col :span="24">
        <el-card>
          <el-button type="success" @click="save">Сохранить</el-button>
        </el-card>
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="6" v-loading="loading">
        <el-card>
          <div slot="header">
            <h4>Основная информация</h4>
          </div>
          <el-form ref="doctor" :model="doctor">
            <div class="form-group">
              <label>Имя</label>
              <el-input v-model="doctor.name" size="small"></el-input>
            </div>
            <div class="form-group">
              <label>Email</label>
              <el-input v-model="doctor.email" size="small"></el-input>
            </div>
            <div class="form-group">
              <label>Телефон</label>
              <span class="not-set">*</span>
              <el-input v-model="doctor.phone" size="small"></el-input>
            </div>
            <div class="form-group">
              <label>Пол</label>
              <el-select v-model="doctor.gender" size="small" class="w-100">
                <el-option label="Жещнина" :value="0"></el-option>
                <el-option label="Мужчина" :value="1"></el-option>
              </el-select>
            </div>
            <div class="form-group">
              <label>Личный сайт</label>
              <el-input
                v-model="doctor.connection_type_link"
                placeholder="https://"
                size="small"
                clearable>
              </el-input>
            </div>
          </el-form>
        </el-card>
      </el-col>

      <el-col :span="8" :offset="1" v-loading="loading">
        <el-card>
          <div slot="header">
            <h4>Специализация</h4>
          </div>
          <el-form ref="doctor" :model="doctor">
            <div class="form-group">
              <label>Категории</label>
              <el-tooltip class="item" effect="dark" :content="tooltips.doctor.categories" placement="top">
                <i class="el-icon-info"></i>
              </el-tooltip>
              <el-input
                class="input-new-tag"
                v-if="addCategoryInput"
                v-model="new_category_name"
                ref="saveTagInput"
                size="small"
                @keyup.enter.native="handleInputConfirm"
                @blur="handleInputConfirm"
              >
              </el-input>
              <el-button v-else class="button-new-tag" size="small" plain @click="showInput">+ Добавить категорию</el-button>
            </div>
            <div class="form-group">
              <el-checkbox-group v-model="selected_categories" size="small">
                <el-checkbox-button
                  v-for="category in categories"
                  :key="category.id"
                  :label="category.id"
                  effect="plain"
                  border
                  :checked="doctor.categories.includes(category.id)"
                  @change="syncCategories">
                  {{ category.name }}
                </el-checkbox-button>
              </el-checkbox-group>
            </div>

          </el-form>
        </el-card>
      </el-col>
    </el-row>
  </el-row>
</template>

<script>
export default {
  name: "Form",
  data() {
    return {
      actions: {
        rest: '/api/v1/doctors',
        restCategories: '/api/v1/categories',
        syncCategories: '/api/v1/doctors/sync-categories',
        getGenderList: '/api/v1/static-data/get-gender-list',
      },
      gender_list: [],
      categories: [],
      selected_categories: [],
      errors: [],
      new_category_name: '',
      loading: true,
      doctor: {
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
    await this.getDoctor();
    await this.getCategories();
    this.loading = false
  },
  methods: {
    async getDoctor() {
      let response = await this.$http.get(this.actions.rest)
      this.doctor = response.data.data.doctor
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
        let response = await this.$http.put(`${this.actions.rest}/${this.doctor.id}`, this.doctor)
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
