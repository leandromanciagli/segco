<template>
  <div>
    <loading :active.sync="isLoading" :is-full-page="true" color="#4CAF50">
    </loading>

    <md-card>
      <md-card-header data-background-color="green">
        <span class="md-title">Alertas</span>
      </md-card-header>

      <md-card-content>
        <div class="md-layout">
          <div class="md-layout-item md-size-50">
            &nbsp;
          </div>

          <div
            class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100"
          >
            <vue-good-table
              :columns="columnas"
              :rows="alertas"
              :lineNumbers="true"
              :pagination-options="{
                enabled: true,
                mode: 'records',
                perPage: 10,
                perPageDropdown: [10, 20],
                position: 'bottom',
                dropdownAllowAll: false,
                setCurrentPage: 1,
                nextLabel: 'siguiente',
                prevLabel: 'anterior',
                rowsPerPageLabel: 'Avisos por página',
                ofLabel: 'de'
              }"
              styleClass="vgt-table"
            >
              <div slot="emptystate" class="has-text-centered">
                <h3 class="h3">No hay alertas para mostrar</h3>
              </div>
              <template slot="table-row" slot-scope="props">
                <span v-if="props.column.field == 'acciones'">

                  <div class="md-layout">
                    <md-button
                      v-if="props.row.detalle"
                      class="md-success md-just-icon"
                      style="height: 30px; margin-right: 10px;"
                      title="Ver paciente"
                      @click="verPaciente(props.row.detalle)"
                    >
                      <md-icon>pageview</md-icon>
                    </md-button>
                    <md-button
                      v-if="!props.row.leido"
                      class="md-success md-just-icon"
                      style="height: 30px;"
                      title="Marcar como leída"
                      @click="marcarComoLeida(props.row.id)"
                    >
                      <md-icon>done</md-icon>
                    </md-button>
                  </div>

                </span>
              </template>
            </vue-good-table>
          </div>
        </div>
      </md-card-content>
    </md-card>
  </div>
</template>

<script>
import "vue-good-table/dist/vue-good-table.css";
import { VueGoodTable } from "vue-good-table";
import Loading from "vue-loading-overlay";
// Import stylesheet
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  components: {
    VueGoodTable,
    Loading
  },
  data() {
    return {
      blockCall: false,
      isLoading: false,
      alertas: [],
      columnas: [
        {
          label: "Evento",
          field: "evento",
          width: "400px",
          tdClass: this.tdClassFunc
        },
        {
          label: "Mensaje",
          field: "mensaje",
          width: "470px",
          tdClass: this.tdClassFunc
        },
        {
          label: "",
          field: "acciones"
        }
      ]
    };
  },
  created() {
    this.isLoading = true;
    events.$on("loading_user:finish", () => {if(!this.blockCall){this.blockCall = true;this.getAlertas()}});
  },
  methods: {
    async getAlertas() {
      const response = await axios.get(
        this.burl("/api/alertas/index?usuarioId=" + this.loggedUser.id)
      );
      this.alertas = response.data;
      console.log(this.alertas)
      this.isLoading = false;
      this.blockCall = false;
    },
    async marcarComoLeida(alertaId) {
      this.isLoading = true;
      const response = await axios.get(
        this.burl("/api/alertas/leida?id=" + alertaId)
      );
      this.alertas = response.data;
      events.$emit('alerta_leida')
      this.getAlertas();
    },
    tdClassFunc(row) {
      return row.leido ? "" : "no-leido";
    },
    verPaciente(url) {
      this.$router.push({path: url})
    }
  }
};
</script>

<style>
.no-leido {
  background-color: #f7a520;
  color: white !important ;
}
</style>
