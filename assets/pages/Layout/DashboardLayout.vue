<template>
  <div class="wrapper" :class="{ 'nav-open': $sidebar.showSidebar }">
    <side-bar :sidebar-item-color="sidebarBackground">
      <mobile-menu slot="content"></mobile-menu>
      <sidebar-link v-if="!jwtToken" to="/login">
        <md-icon>person</md-icon>
        <p>Iniciar sesión</p>
      </sidebar-link>
      <sidebar-link v-if="jwtToken && esJefe" to="/sistemas">
        <md-icon>local_hospital</md-icon>
        <p>Sistemas</p>
      </sidebar-link>
      <sidebar-link
        v-if="jwtToken && !esAdmin"
        :to="{
          name: 'Pacientes',
          params: {
            sistemaNombre: loggedUser.sistemaNombre,
            sistemaId: loggedUser.sistemaId
          }
        }"
      >
        <md-icon>person</md-icon>
        <p>Pacientes</p>
      </sidebar-link>
      <sidebar-link v-if="jwtToken && esAdmin" to="/reglas">
        <md-icon>rule</md-icon>
        <p>Reglas del Sistema</p>
      </sidebar-link>
      <sidebar-link v-if="jwtToken && !esAdmin" to="/alertas">
        <md-icon>notification_important</md-icon>
        <p>Alertas {{ cantAvisosSinLeerComp }}</p>
      </sidebar-link>
      <sidebar-link v-if="jwtToken" to="/logout">
        <md-icon>keyboard_backspace</md-icon>
        <p>Cerrar sesión</p>
      </sidebar-link>
    </side-bar>

    <div class="main-panel">

      <top-navbar></top-navbar>

      <dashboard-content></dashboard-content>

    </div>
  </div>
</template>

<script>
import TopNavbar from "./TopNavbar.vue";
import DashboardContent from "./Content.vue";
import MobileMenu from "@/pages/Layout/MobileMenu.vue";

export default {
  components: {
    TopNavbar,
    DashboardContent,
    // ContentFooter,
    MobileMenu
  },
  data() {
    return {
      sidebarBackground: "green",
      cantAvisosSinLeer: ""
    };
  },
  created() {
    this.getCantAvisosSinLeer()
    events.$on('alerta_leida', () => ( this.getCantAvisosSinLeer() ))
  },
  methods: {
    async getCantAvisosSinLeer() {
      await this.$root.fetchLoggedUser();
      const response = await axios.get(
        this.burl("/api/alertas/cantAvisosSinLeer?usuarioId=" + this.loggedUser.id)
      );
      this.cantAvisosSinLeer = response.data;
      this.isLoading = false;
    }
  },
  computed: {
    esJefe() {
      if (this.loggedUser.roles) {
        return this.loggedUser.roles.includes("ROLE_JEFE");
      }
      return ""
    },
    esAdmin() {
      if (this.loggedUser.roles) {
        return this.loggedUser.roles.includes("ROLE_ADMIN");
      }
      return ""
    },
    cantAvisosSinLeerComp() {
      return this.cantAvisosSinLeer > 0 ? '( ' + this.cantAvisosSinLeer + ' )' : ''
    }
  }
};
</script>
