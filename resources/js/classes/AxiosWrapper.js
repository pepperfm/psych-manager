import axios from "axios";

class AxiosWrapper {
  constructor() {
    this.createClient()
  }

  async request(options) {
    let token = localStorage.getItem('accessToken');

    if (this.client.defaults.headers['accessToken'] !== `Bearer ${token}`) {
      this.createClient();
    }

    return this.client(options)
      .then(this.onSuccess)
      .catch(this.onError);
  }

  async get(path, options) {
    return this.request(Object.assign({ method: 'GET', url: path }, options));
  }
  async post(path, payload, options) {
    return this.request(Object.assign({ method: 'POST', url: path, data: payload }, options));
  }
  async put(path, payload, options) {
    return this.request(Object.assign({ method: 'PUT', url: path, data: payload }, options));
  }
  async delete(path, payload, options) {
    return this.request(Object.assign({ method: 'DELETE', url: path, data: payload }, options));
  }

  createClient() {
    let token = localStorage.getItem('accessToken');
    let headers = {};

    if (token) {
      headers = { Pragma: 'no-cache', Authorization: `Bearer ${token}` }
    }

    this.client = axios.create({
      withCredentials: true,
      headers: headers,
    });
    this.onSuccess = this.onSuccess.bind(this);
    this.onError = this.onError.bind(this);
  }

  onSuccess(response) {
    console.log(response.data.data)
    return response;
  }
  onError(error) {
    // FIXME подумать над тем, когда падает авторизация
    if (error.response.status === 401 || error.response.status === 403) {
      // router.push('/login')
      if (this.$vm) {
        this.$vm.$root.$emit('require-auth');
      }
    }

    return Promise.reject(error);
  }
}

export default new AxiosWrapper
