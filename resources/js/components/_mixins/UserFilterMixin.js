export default {
  data() {
    return {
      actions: {
        setUserFilters: '/api/v1/filters',
        getUserFilters: '/api/v1/filters',
        clearUserFilters: '/api/v1/filters/clear',
      },
    }
  },
  created() {
    this.$root.$emit('set-path-name', { name: ""});
  },
  methods: {
    async getUserFilters(module) {
      try {
        let response = await this.$http.get(`${this.actions.getUserFilters}/${module}`, {})

        return response.data.data

      } catch (e) {
        throw e
      } finally {
      }
    },

    async setUserFilters(module, filters) {
      try {
        let response = await this.$http.post(`${this.actions.setUserFilters}/${module}`, filters);

        return response.data.data
      } catch (e) {
        throw e
      } finally {
      }
    },

    async clearUserFilters(module) {
      try {
        let response = await this.$http.get(`${this.actions.clearUserFilters}/${module}`, {})
        this.$message.success(response.data.message)

        return response.data.data
      } catch (e) {
        throw e
      } finally {
      }
    }
  }
}
