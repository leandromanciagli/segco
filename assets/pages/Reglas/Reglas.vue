<template>
  <div>
    <loading :active.sync="isLoading" :is-full-page="true" color="#4CAF50">
    </loading>

    <md-card>
      <md-card-header data-background-color="green">
        <span class="md-title">Reglas del Sistema</span>
      </md-card-header>

      <md-card-content>
        <div class="md-layout">
          <div class="md-layout-item md-size-50">
            &nbsp;
          </div>

          <div class="md-layout-item md-size-50 text-right">
            <md-button to="/regla" class="md-success">Agregar Regla</md-button>
          </div>

          <div
            class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100"
          >
            <vue-good-table
              :columns="columnas"
              :rows="reglas"
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
                rowsPerPageLabel: 'Reglas por página',
                ofLabel: 'de'
              }"
              styleClass="vgt-table"
            >
              <div slot="emptystate" class="has-text-centered">
                <h3 class="h3">No hay reglas para mostrar</h3>
              </div>
              <template slot="table-row" slot-scope="props">
                <span v-if="props.column.field == 'acciones'">
                  <!-- <md-button
                    class="md-success md-just-icon"
                    title="Editar"
                    :to="{
                      name: 'Nueva Regla',
                      params: { reglaId: props.row.id }
                    }"
                  >
                    <md-icon>edit</md-icon>
                  </md-button> -->

                  <md-button
                    class="md-success md-just-icon"
                    style="height: 30px"
                    title="Eliminar"
                    @click="eliminar(props.row.id)"
                  >
                    <md-icon>delete</md-icon>
                  </md-button>
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
      isLoading: false,
      reglas: [],
      columnas: [
        {
          label: "Evento",
          field: "evento",
          width: "280px"
        },
        {
          label: "Expresión",
          field: "expresion",
          width: "350px"
        },
        {
          label: "Acción",
          field: "accion",
          width: "305px"
        },
        {
          label: "",
          field: "acciones",
          width: "50px"
        }
      ]
    };
  },
  created() {
    this.getReglas();
  },
  methods: {
    async getReglas() {
      this.isLoading = true;
      const response = await axios.get(this.burl("/api/reglas/index"));
      this.reglas = response.data;
      this.isLoading = false;
    },
    eliminar(reglaId) {
      this.$swal
        .fire({
          title: "Está seguro?",
          text: "Esta acción es irreversible",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#F33527",
          cancelButtonColor: "#47A44B",
          confirmButtonText: "Sí, eliminar regla",
          cancelButtonText: "Cancelar"
        })
        .then(result => {
          if (result.isConfirmed) {
            this.isLoading = true;
            axios
              .post(this.burl("/api/reglas/delete?id=" + reglaId))
              .then(response => {
                this.getReglas();
              });
            this.isLoading = false;
          }
        });
    }
  }
};
</script>
