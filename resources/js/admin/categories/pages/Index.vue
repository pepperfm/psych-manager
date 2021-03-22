<template>
  <div class="col-4">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-xs-12 col-md-6">
            <el-button @click="create()" type="success" plain>Предложить новые</el-button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <table class="table table-hover" v-loading="loading">
          <thead>
          </thead>
          <tbody>
            <tr v-for="(groups, index) in categories" :key="index">
              <td>
                <h6 v-text="index" class="text-bold"></h6>
                <div v-for="(category, k) in groups" :key="category.id">
                  {{ category.name }}
                </div>
              </td>
            </tr>
            <!--<td class="actions">
              <el-button
                size="mini"
                type="warning"
                plain
                icon="el-icon-edit"
                @click="edit(category)">
              </el-button>
              <el-button
                @click="confirmDelete(category)"
                size="mini"
                type="danger"
                plain
                slot="reference"
                icon="el-icon-delete">
              </el-button>
            </td>-->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: "Index",
  data() {
    return {
      actions: {
        rest: '/api/v1/categories',
        getCategoriesForGroupSelect: '/api/v1/get-categories-select'
      },
      categories: [],
      categoriesSelect: [],
      loading: true,
    }
  },
  async created() {
    await this.getRecords()
    await this.getCategoriesSelect()
  },
  methods: {
    async getRecords() {
      try {
        let response = await this.$http.get(this.actions.rest)
        this.categories = response.data.data.categories
        this.loading = false
      } catch (e) {

      }
    },

    async getCategoriesSelect() {
      try {
        let response = await this.$http.get(this.actions.getCategoriesForGroupSelect)
        this.categoriesSelect = response.data.data.categories
        localStorage.setItem('categories', JSON.stringify(this.categoriesSelect))
      } catch (e) {

      }
    },

    async edit(category) {
      await this.$router.push({name: 'update', params: {id: category.id}})
    },

    async create() {
      await this.$router.push({name: 'create'})
    },

    confirmDelete(category) {
      this.$confirm('Удалятся так же всё проблемы этой категории. Продолжить?', 'Внимание', {
        confirmButtonText: 'Да',
        cancelButtonText: 'Отмена',
        type: 'warning'
      }).then(() => {
        this.remove(category)
        this.$message({
          type: 'success',
          message: 'Удалено'
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: 'Отменено'
        });
      });
    },

    async remove(category) {
      this.errors = []
      try {
        await this.$http.delete('/api/v1/categories/' + category.id)
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

  }
}
</script>

<style lang="css">
    .clickable {
      cursor: pointer;
    }
    .search--field {
        padding: 0 !important;
    }
    .page-link {
        color: #007bff !important;
    }
    .not-set {
        color: red;
    }
    .actions {
      text-align: center;
    }
</style>
