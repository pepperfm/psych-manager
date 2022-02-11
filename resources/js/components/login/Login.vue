<template>
  <div class="login">
    <el-card>
      <h2>Login</h2>
      <el-form
        @submit.prevent.native="auth()"
        :model="loginForm"
        :rules="rules"
        ref="loginForm"
      >
        <el-form-item prop="email">
          <el-input
            v-model="loginForm.username"
            placeholder="Email"
            prefix-icon="fas fa-at"
          ></el-input>
        </el-form-item>
        <el-form-item prop="password">
          <el-input
            v-model="loginForm.password"
            placeholder="Password"
            type="password"
            prefix-icon="fas fa-lock"
          ></el-input>
        </el-form-item>
        <el-form-item>
          <el-button
            native-type="submit"
            type="primary"
          >Login</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>

export default {
  name: "login",

  data() {
    let validatePass = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('Please input the password'));
      } else {
        if (value.length < 6) {
          callback(new Error('Password length should more then 5 characters'));
        } else {
          callback();
        }
      }
    };
    return {
      actions: {
        auth: '/api/v1/login'
      },
      loginForm: {
        username: "",
        password: "",
        grant_type: "password",
      },
      loading: false,
      rules: {
        email: [
          { type: 'email', trigger: "submit" },
        ],
        password: [
          { validator: validatePass, trigger: "submit" },
        ]
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
        localStorage.setItem('accessToken', response.data.access_token)

        // let redirect = this.$route.query.redirect ? this.$route.query.redirect : '/'
        // await this.$router.push(redirect)
        await this.$router.push({ name: 'main'})
        await this.$message.success('Success');
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
    resetForm() {
      this.$refs.loginForm.resetFields();
    }
  }
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.login {
  flex: 1;
  display: flex;
  justify-content: space-evenly;
  align-items: center;
}

.login-button {
  width: 100%;
  margin-top: 40px;
}
.login-form {
  width: 290px;
}
.forgot-password {
  margin-top: 10px;
}
</style>
<style lang="scss">
$teal: rgb(0, 124, 137);
.el-button--primary {
  background: $teal;
  border-color: $teal;

  &:hover,
  &.active,
  &:focus {
    background: lighten($teal, 7);
    border-color: lighten($teal, 7);
  }
}
.login .el-input__inner:hover {
  border-color: $teal;
}
.login .el-input__prefix {
  background: rgb(238, 237, 234);
  left: 0;
  height: calc(100% - 2px);
  left: 1px;
  top: 1px;
  border-radius: 3px;
  .el-input__icon {
    width: 30px;
  }
}
.login .el-input input {
  padding-left: 35px;
}
.login .el-card {
  padding-top: 0;
  padding-bottom: 30px;
}
h2 {
  font-family: "Open Sans";
  letter-spacing: 1px;
  font-family: Roboto, sans-serif;
  padding-bottom: 20px;
}
a {
  color: $teal;
  text-decoration: none;
  &:hover,
  &:active,
  &:focus {
    color: lighten($teal, 7);
  }
}
.login .el-card {
  width: 340px;
  display: flex;
  justify-content: center;
}
</style>
