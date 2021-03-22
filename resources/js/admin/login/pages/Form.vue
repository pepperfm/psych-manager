<template>
  <div class="el-container justify-content-center align-items-center vh-100 bg-secondary">
    <el-card shadow="always">
      <div slot="header" class="clearfix">
        <span>Psych manager</span>
      </div>
      <div>
        <el-form :model="loginForm" class="login-box" status-icon :rules="rules" ref="loginForm">
          <el-form-item prop="username">
            <el-input type="email" v-model="loginForm.username" placeholder="Email" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item prop="password">
            <el-input type="password" v-model="loginForm.password" placeholder="Password" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="auth()">Войти</el-button>
            <el-button @click="resetForm('loginForm')">Сбросить</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
  </div>
</template>

<script>

export default {
  name: 'Login',
  components: {
  },
  data() {
    return {
      actions: {
        auth: '/api/v1/passport-login'
      },
      loginForm: {
        client_id: process.env.MIX_CLIENT_ID,
        client_secret: process.env.MIX_CLIENT_SECRET,
        grant_type: 'password',
        username: '',
        password: ''
      },
      rules: {
        username: [
          {  required: true, message: 'Please input email address', trigger: 'submit' },
          {  type: 'email', message: 'Please input correct email address', trigger: 'submit' }
        ],
        password: [
          { required: true, message: 'Please input password', trigger: 'submit' }
        ],
      }
    };
  },
  methods: {
    async auth() {
      await this.$refs.loginForm.validate((valid) => {
        if (!valid) {
          return false;
        }
      });

      try {
        let response = await this.$http.post(this.actions.auth, this.loginForm)
        localStorage.setItem('psych_token', response.data.access_token)
        await this.$http.post('/admin', {}, {
          headers: {
            "Content-Type": "application/json",
            accept: "*/*",
            Authorization: `Bearer ${localStorage.getItem('psych_token')}`,
          },
        })
        window.location.href = '/admin'
      } catch (e) {
        if (!e.response) {
          throw e
        }
        switch (e.response.status) {
          case 422:
            this.$message.error(e.response.data.message)
            break
          default:
            this.$message.error(e.response.data.message)
        }
      }

    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    }
  }
}
</script>

<style lang="css" scoped>
@import '~bootstrap/dist/css/bootstrap.css';

#login {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}
.bg-secondary {
  background-color: #e9ecef !important;
}
.login-box {
  width: 360px;
}
</style>
