// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from "vue";
import VueRouter from "vue-router";
import App from "./App";

import axios from "axios";

// vee-validate
import {
  ValidationProvider,
  ValidationObserver,
  setInteractionMode
} from "vee-validate";

setInteractionMode("eager");

Vue.component("ValidationProvider", ValidationProvider);
Vue.component("ValidationObserver", ValidationObserver);

import "./vee-validate-rules.js";

import VueSweetalert2 from "vue-sweetalert2";

// router setup
import routes from "./routes/routes";

// Plugins
import GlobalComponents from "./globalComponents";
import GlobalDirectives from "./globalDirectives";
import Notifications from "./components/NotificationPlugin";

// MaterialDashboard plugin
import MaterialDashboard from "./material-dashboard";

import Chartist from "chartist";

//vue-loading with styles
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

import moment from "moment-timezone";
moment.tz.setDefault("America/Argentina/Buenos_Aires");

Object.defineProperty(Vue.prototype, "$moment", { value: moment });

// configure router
const router = new VueRouter({
  routes, // short for routes: routes
  linkExactActiveClass: "nav-item active"
});

window.axios = axios;
window.events = new Vue();

Vue.prototype.$Chartist = Chartist;

Vue.use(VueRouter);
Vue.use(MaterialDashboard);
Vue.use(GlobalComponents);
Vue.use(GlobalDirectives);
Vue.use(Notifications);
Vue.use(VueSweetalert2);
Vue.use(Loading, {
  color: "#4CAF50"
});

/**************************************************************** */

/*todo lo que se ponga aca va a estar/hacerse en todos los componentes del sistema*/
Vue.mixin({
  computed: {
    config: {
      set: function(info) {
        var datos = info;
        var resp = {};
        for (var i = 0; i < datos.length; i++) {
          resp[datos[i].variable] = datos[i].valor;
        }
        this.$root.$data.store_config = resp;
        events.$emit("loading_config:finish");
      },
      get: function() {
        return this.$root.$data.store_config;
      }
    },
    jwtToken: {
      set: function(token) {
        this.$root.$data.store_token = token;
        localStorage.setItem("token", token);
        axios.defaults.headers.common["Authorization"] = "Bearer " + token;
      },
      get: function() {
        return this.$root.$data.store_token;
      },
      clear: function() {
        this.$root.$data.store_token = "";
      }
    },
    loggedUser: {
      set: function(data) {
        if (data !== "") {
          this.$root.$data.store_user["id"] = data.id;
          this.$root.$data.store_user["email"] = data.email;
          this.$root.$data.store_user["username"] = data.username;
          this.$root.$data.store_user["roles"] = [];
          this.$root.$data.store_user["sistemaNombre"] = data.sistema.descrip;
          this.$root.$data.store_user["sistemaId"] = data.sistema.id;
          data.roles.forEach(r => {
            this.$root.$data.store_user["roles"].push(r);
          });
          this.$root.$data.store_user["created_at"] = data.created_at;
          this.$root.$data.store_user["updated_at"] = data.updated_at;
          this.$root.$data.store_user["first_name"] = data.first_name;
          this.$root.$data.store_user["last_name"] = data.last_name;
          this.$root.$data.store_user["activo"] = data.activo;
          events.$emit("loading_user:finish");
        } else {
          this.$root.$data.store_user = {};
        }
      },
      get: function() {
        return this.$root.$data.store_user;
      },
      clear: function() {
        this.$root.$data.store_user = "";
        this.$root.$data.store_user = {};
      }
    }
  },
  methods: {
    //para utilizar este metodo en su componente se pone this.makeCorsRequest('https://www...').then((respuesta) => { console.log(respuesta)})
    async makeCorsRequest(url) {
      var info = "";
      delete axios.defaults.headers.common["Authorization"];
      await axios
        .get(url)
        .then(response => {
          axios.defaults.headers.common["Authorization"] =
            "Bearer " + this.jwtToken;
          info = response.data;
        })
        .catch(error => {
          axios.defaults.headers.common["Authorization"] =
            "Bearer " + this.jwtToken;
          info = error;
        });
      return info;
    },
    burl(path) {
      let url = new URL(window.location);

      if (process.env.APLICATION_ENV === "production") {
        var baseUrl = `${url.origin}/index.php`;
      } else {
        var baseUrl = url.origin;
      }

      return `${baseUrl}${path}`;
    },
    asset(name) {
      let url = new URL(window.location);
      if (process.env.NODE_ENV === "production") {
        return `${name}`;
      }
      return `${url.origin}/${name}`;
    },
    logOut() {
      axios.defaults.headers.common["Authorization"] = null;
      localStorage.removeItem("token");
      this.jwtToken = "";
      this.loggedUser = "";
      this.$router.push("/login");
    },

    /**
     * Devuelve una fecha con formato
     *
     * @param {String} dateTime
     */
    formatDateTime: value => moment(String(value)).format("DD/MM/YYYY HH:mm"),

    /**
     * Devuelve una fecha con formato
     *
     * @param {String} dateTime
     */
    formatDate: value => moment(String(value)).format("DD/MM/YYYY"),

    /**
     * Devuelve una hora con formato
     *
     * @param {String} dateTime
     */
    formatTime: value => moment(String(value)).format("HH:mm")
  }
});

/* eslint-disable no-new */
new Vue({
  el: "#app",
  render: h => h(App),
  router,
  data: {
    store_config: "",
    store_token: "",
    store_user: {},
    Chartist: Chartist,
    cancelSource: null,
  },
  created() {
    axios.interceptors.response.use(
      response => {
        //metodo para redirigir a login cuando la sesion expira, siempre y cuando no este en el login
        return response;
      },
      error => {
        if (error.name) {
          this.expireJWTcheck(error);
        }
        return Promise.reject(error);
      }
    );

    this.newCancelToken();

    this.jwtToken = localStorage.getItem("token")
      ? localStorage.getItem("token")
      : "";

    //si abrís la app
    if (this.store_token !== "") {
      this.fetchLoggedUser();
    }

    events.$on("change:route", componente => this.cambiarRuta(componente));
    events.$on("user:logout", () => this.logOut());

    this.$router.beforeEach( async (to, from, next) => {
      if (to.matched.some(record => record.meta.requiresAuth)) {
        if (this.jwtToken == null) {
          next({
            path: "/login",
            params: { nextUrl: to.fullPath }
          });
        } else {
          if (to.matched.some(record => record.meta.role)) {
            //si el usuario tiene el rol necesario para ingresar
            if (
              to.matched.some(record => {if (record.meta.role) { return record.meta.role.some(r => this.loggedUser.roles.includes(r) ) } return false})
            ) {
              next();
            }
            //si no tiene el rol necesario se lo envia a su default
            else {
              next(this.getUserHome());
            }
          } else {
            if (to.path !== "/login" && to.path !== "/home") {
              next();
            } else {
              next(await this.getUserHome());
            }
          }
        }
      } else {
        if ((to.path == "/login" || to.path == "/") && this.jwtToken) {
          next(this.getUserHome());
        } else {
          next();
        }
      }
    });
  },

  methods: {
    async getUserHome() {
      if (this.loggedUser.roles) {

      }else { 
        await this.fetchLoggedUser() 
      }

      if (!this.loggedUser.roles.includes("ROLE_ADMIN")) {
        return {
          name: "Pacientes",
          params: {
            sistemaId: this.loggedUser.sistemaId,
            sistemaNombre: this.loggedUser.sistemaNombre
          }
        };
      } else {
        return { name: "Reglas" };
      }
    },
    async fetchPageConfig() {
      await axios.get(this.burl("/configuracion/")).then(response => {
        this.config = response.data;
        if (this.config.estado === "deshabilitado") {
          events.$emit("mantenimiento:active");
        }
      });
    },

    async fetchLoggedUser() {
      await axios
        .get(this.burl("/api/session"))
        .then(response => {
          this.loggedUser = response.data;
        })
        .catch(error => {});
    },

    newCancelToken() {
      this.cancelSource = axios.CancelToken.source();

      let requestInterceptor = config => {
        config.cancelToken = this.cancelSource.token;
        return config;
      };

      //token de canselacion para los axios

      axios.interceptors.request.use(requestInterceptor);
    },

    expireJWTcheck(error) {
      if (window.location.hash != "#/login" && 401 === error.response.status) {
        this.cancelSource.cancel("sesión expiró");
        this.newCancelToken();
        this.$swal
          .fire({
            title: "La sesión expiró",
            text: "Su sesión ha expirado. Será redirigido a la página de login",
            timer: 2500,
            showConfirmButton: false
          })
          .then(() => {
            localStorage.removeItem("token");
            this.store_token = "";
            axios.defaults.headers.common["Authorization"] = null;
            this.$router.replace({ path: "/" });
          });
      } else {
        this.cancelSource.cancel();
        this.newCancelToken();
        if (
          400 === error.response.status ||
          403 === error.response.status ||
          404 === error.response.status ||
          500 === error.response.status
        ) {
          if (error.response.data.length > 200) {
            events.$emit(
              "alert:error",
              "Se produjo una violacion en los tipos de parámetros"
            );
          } else {
            this.$swal
              .fire({
                title: error.response.data.title
                  ? error.response.data.title
                  : "Algo salió mal!",
                text: error.response.data.message,
                icon: "error"
              })
              .then(() => {
                if (error.response.data.relocate) {
                  if (error.response.data.relocate == "go back") {
                    router.go(-1);
                  } else {
                    this.$router.replace({
                      path: error.response.data.relocate
                    });
                  }
                }
              });
          }
        }
      }
    },

    cambiarRuta(componente) {
      this.$router.replace({ name: componente });
    },

    checkBlockUser() {
      if (!this.loggedUser.activo) {
        Vue.swal({
          title: "Cuenta bloqueada",
          text:
            "Su cuenta esta bloqueada. Pongase en contacto con la administracion.",
          type: "warning",
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Ok"
        }).then(() => {
          localStorage.removeItem("token");
          this.store_token = "";
          axios.defaults.headers.common["Authorization"] = null;
          this.store_user = {};
          this.loggedUser.clear;
          events.$emit("user:logout");
          this.$router.replace({ name: "login" });
        });
      }
    }
  },

  watch: {
    $route(to, from) {
      if (
        this.store_token !== "" && // tengo token
        to.path !== "/logout" // no voy a logout
      ) {
        this.fetchLoggedUser();
      }
    }
  }
});
