<template>
  <div class="content">
    <loading :active.sync="isLoading" :is-full-page="false" color="#4CAF50">
    </loading>
    <div class="md-layout">
      <div
        class="md-layout-item md-medium-size-75 md-xsmall-size-75 md-size-33"
      >
        <md-field>
          <label>Nombre de usuario</label>
          <md-input v-model="username" @keyup.enter="login"></md-input>
        </md-field>

        <md-field>
          <label>Contraseña</label>
          <md-input
            v-model="password"
            @keyup.enter="login"
            type="password"
          ></md-input>
        </md-field>

        <md-button class="md-success" @click="login" :disabled="canSubmit"
          >Aceptar</md-button
        >
      </div>
    </div>
  </div>
</template>

<script>
import Loading from "vue-loading-overlay";
// Import stylesheet
import "vue-loading-overlay/dist/vue-loading.css";
export default {
  components: {
    Loading
  },
  data() {
    return {
      username: "",
      password: "",
      isLoading: false
    };
  },
  created() {
    if(this.jwtToken){
      this.redirect();
      }
  },
  methods: {
    async redirect() {
      this.isLoading = true;
      await this.$root.fetchLoggedUser();
      this.$router.push({ name: "Redireccion" }); // con esto me cambio de vista
    },
    async login() {
      this.isLoading = true;
      var credentials = {
        _username: this.username,
        _password: this.password
      };
      await axios
        .post(this.burl("/api/login_check"), credentials, { dataType: "text" }) //mando el post
        .then(response => {
          if (response.status === 200) {
            this.jwtToken = response.data["token"]; //seteo el token
            this.redirect();
          }
        })
        .catch(error => {
          this.isLoading = false;
          this.$swal("Usuario o contraseña incorrectos", "", "error");
        });
    }
  },
  computed: {
    canSubmit() {
      return !(this.username && this.password);
    }
  }
};
</script>
