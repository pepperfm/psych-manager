<template>
  <table class="table">
    <tr>
      <td class="td-heading">Телефон</td>
      <td class="td-text" v-text="user.phone"></td>
    </tr>
    <tr>
      <td class="td-heading">Email</td>
      <td class="td-text" v-text="user.email"></td>
    </tr>
    <tr>
      <td class="td-heading">Способ связи</td>
      <td class="td-text" v-text="user.connection_type_string"></td>
    </tr>
    <tr>
      <td class="td-heading">Категория</td>
      <td class="td-text" v-text="user.category"></td>
    </tr>
    <tr>
      <td class="td-heading">Телефон или ссылка соц. сети</td>
      <td class="td-text" v-text="user.connection_type_link"></td>
    </tr>
    <tr>
      <td class="td-heading">Формат общения</td>
      <td class="td-text"><i :class="user.meeting_type_icon"></i></td>
    </tr>
<!--    <tr>
      <td class="td-heading">Проблема</td>
      <td class="td-text" v-text="user.problem_name"></td>
    </tr>-->
    <tr>
      <td class="td-heading">План на терапию</td>
      <td class="td-text" v-text="user.therapy.plan"></td>
    </tr>
    <tr>
      <td class="td-heading">Моя концептуализация</td>
      <td class="td-text" v-text="user.therapy.concept_vision"></td>
    </tr>
    <tr>
      <td class="td-heading">Заметки</td>
      <td class="td-text" v-text="user.therapy.notes"></td>
    </tr>
  </table>
</template>

<script>
export default {
  name: "UserDetails",
  data() {
    return {
      actions: {
      },
      errors: [],
    }
  },
  props: {
    user: {
      type: Object
    },
  },
  async created() {
  },
  methods: {

  }
}
</script>

<style scoped>
  .td-heading {
    width: 35%;
  }
  .td-text {
    width: 65%;
  }
</style>
