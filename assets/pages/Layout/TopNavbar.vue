<template>
  <md-toolbar md-elevation="0" class="md-transparent">
    <div class="md-toolbar-row">
      <div class="md-toolbar-section-start">
        <h3 v-if="jwtToken" class="md-title">
          {{ nombreUsuario }} {{ rolUsuario }} {{ sistemaUsuario }}
        </h3>
      </div>
      <div class="md-toolbar-section-end">
        <md-button
          class="md-just-icon md-simple md-toolbar-toggle"
          :class="{ toggled: $sidebar.showSidebar }"
          @click="toggleSidebar"
        >
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </md-button>

      </div>
    </div>
  </md-toolbar>
</template>

<script>
export default {
  data() {
    return {

    }
  },
  methods: {
    toggleSidebar() {
      this.$sidebar.displaySidebar(!this.$sidebar.showSidebar);
    }
  },
  computed: {
    nombreUsuario() {
      if (this.loggedUser.first_name) {
        return this.loggedUser.first_name + " " + this.loggedUser.last_name + ", "
      }
      return ""
    },
    rolUsuario() {
      if (this.loggedUser.roles) {
        if (this.loggedUser.roles.includes("ROLE_JEFE")) {
          return "Jefe";
        } else {
          if (this.loggedUser.roles.includes("ROLE_ADMIN")) {
            return "Administrador de reglas";
          } else {
            return "Médico";
          }
        }
      }
      return "";
    },
    sistemaUsuario() {
      if (this.loggedUser.roles) {
        if (this.loggedUser.roles.includes("ROLE_ADMIN")) {
          return "";
        } else {
          return "de " + this.loggedUser.sistemaNombre;
        }
      }
      return "";
    }
  }
};
</script>

<style lang="css"></style>
