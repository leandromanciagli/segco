<template>
  <div>
    <loading :active.sync="isLoading" :is-full-page="true" color="#4CAF50">
    </loading>

    <md-card v-if="pacientes">
      <md-card-header data-background-color="green">
        <span class="md-title"
          >Pacientes en {{ nombreSistemaComp }} {{ nombreSalaComp }}</span
        >
      </md-card-header>

      <md-card-content>
        <div class="md-layout">
          <div class="md-layout-item md-size-50 text-left">
            <md-button class="md-simple" @click="getPacientesInternados()"
              >Internados</md-button
            >
            <md-button class="md-simple" @click="getPacientesEgresados()"
              >Egresados</md-button
            >
            <md-button class="md-simple" @click="getPacientesFallecidos()"
              >Fallecidos</md-button
            >
          </div>

          <div class="md-layout-item md-size-50 text-right">
            <md-button
              v-if="nombreSistemaComp == 'Guardia'"
              to="/paciente"
              class="md-success"
              >Agregar Paciente</md-button
            >
          </div>

          <div
            class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100"
          >
            <vue-good-table
              :columns="columnas"
              :rows="pacientes"
              :lineNumbers="true"
              :defaultSortBy="{ field: 'apellido', type: 'asec' }"
              :globalSearch="false"
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
                rowsPerPageLabel: 'Pacientes por página',
                ofLabel: 'de'
              }"
              :search-options="{ enabled: true, placeholder: 'Buscar' }"
              styleClass="vgt-table"
            >
              <div slot="emptystate" class="has-text-centered">
                <h3 class="h3">No hay pacientes para mostrar</h3>
              </div>
              <template slot="table-row" slot-scope="props">
                <span v-if="props.column.field == 'acciones'">
                  <md-menu md-direction="bottom-start">
                    <md-button
                      class="md-success"
                      style="height: 30px"
                      md-menu-trigger
                    >
                      <md-icon>menu</md-icon>
                    </md-button>

                    <md-menu-content>
                      <router-link
                        :to="{
                          name: 'Ver Paciente',
                          params: { pacienteId: props.row.id }
                        }"
                      >
                        <md-menu-item>Ver paciente</md-menu-item>
                      </router-link>

                      <router-link
                        v-if="props.row.fecha_egreso"
                        :to="{
                          name: 'Nueva Internación',
                          params: { pacienteId: props.row.id }
                        }"
                      >
                        <md-menu-item>Nueva internación</md-menu-item>
                      </router-link>

                      <md-menu-item
                        v-if="
                          !(props.row.fecha_obito || props.row.fecha_egreso) &&
                            loggedUser.roles.includes('ROLE_JEFE') &&
                            nombreSistemaComp == loggedUser.sistemaNombre
                        "
                        @click="getMedicosDelSistema(props.row.id)"
                        >Gestionar médicos</md-menu-item
                      >
                      <md-menu-item
                        v-if="
                          puedeDeclararEgreso &&
                            !(
                              props.row.fecha_obito || props.row.fecha_egreso
                            ) &&
                            nombreSistemaComp == loggedUser.sistemaNombre
                        "
                        @click="
                          declararEgreso(props.row.internacionId)
                        "
                        >Declarar egreso</md-menu-item
                      >
                      <md-menu-item
                        v-if="
                          !(props.row.fecha_obito || props.row.fecha_egreso) &&
                            nombreSistemaComp == loggedUser.sistemaNombre
                        "
                        @click="declararObito(props.row.internacionId)"
                        >Declarar óbito</md-menu-item
                      >
                    </md-menu-content>
                  </md-menu>
                </span>
              </template>
            </vue-good-table>
          </div>
        </div>
      </md-card-content>
    </md-card>

    <div>
      <md-dialog
        :md-active.sync="mostrarAsignarMedico"
        style="max-width: 100%;"
      >
        <md-dialog-title>Gestionar médicos</md-dialog-title>

        <md-dialog-content>
          <md-table md-card v-if="medicosDelSistema" class="md-body">
            <md-table-row>
              <md-table-head>Nombre</md-table-head>
              <md-table-head>Nro. Legajo</md-table-head>
              <md-table-head>Acciones</md-table-head>
            </md-table-row>

            <md-table-row v-for="medico in medicosDelSistema" :key="medico.id">
              <md-table-cell>{{
                medico.first_name + " " + medico.last_name
              }}</md-table-cell>

              <md-table-cell>{{ medico.legajo }}</md-table-cell>

              <md-table-cell>
                <md-button
                  v-if="medico.asignado"
                  class="md-success"
                  @click="desasignarMedico(medico.id)"
                >
                  Desasignar
                </md-button>
                <md-button
                  v-else
                  class="md-success"
                  @click="asignarMedico(medico.id)"
                >
                  Asignar
                </md-button>
              </md-table-cell>
            </md-table-row>
          </md-table>

          <span v-else class="md-body">No hay médicos en el sistema</span>
        </md-dialog-content>

        <md-dialog-actions>
          <md-button class="md-success" @click="mostrarAsignarMedico = false"
            >Cerrar</md-button
          >
        </md-dialog-actions>
      </md-dialog>
    </div>
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
  props: ["sistemaNombre", "sistemaId", "salaId", "salaNombre"],
  data() {
    return {
      isLoading: false,
      pacientes: null,
      sistemaNombreLocal: "",
      mostrarAsignarMedico: false,
      medicosDelSistema: null,
      medicoAsignado: false,
      pacienteSeleccionado: "",
      motivoAltaPaciente: null,
      columnas: [
        {
          label: "Dni",
          field: "dni",
          type: "number",
          width: "80px"
        },
        {
          label: "Apellido",
          field: "apellido",
          width: "150px"
        },
        {
          label: "Nombre",
          field: "nombre",
          width: "150px"
        },
        {
          label: "Sistema",
          field: this.getSistema,
          width: "100px"
        },
        {
          label: "Sala",
          field: this.getSala,
          width: "100px"
        },
        {
          label: "Cama",
          field: this.getCama,
          type: "number",
          width: "80px",
          filterable: false
        },
        {
          label: "Estado",
          field: this.getEstado,
          width: "80px"
        },
        {
          label: "Acciones",
          field: "acciones",
          width: "50px"
        }
      ]
    };
  },
  created() {
    events.$on(
      "loading_user:finish",
      () => (this.sistemaNombreLocal = this.loggedUser.sistemaNombre)
    );
    this.getPacientesInternados();
  },
  methods: {
    async getPacientesInternados() {
      this.isLoading = true;
      let sistemaId = this.sistemaId ? "?sistema=" + this.sistemaId : "";
      let salaId = this.salaId ? "&sala=" + this.salaId : "";
      const pacientes = await axios.get(
        this.burl("/api/paciente/internados" + sistemaId + salaId)
      );
      this.pacientes = pacientes.data;
      this.isLoading = false;
    },
    async getPacientesEgresados() {
      this.isLoading = true;
      const pacientes = await axios.get(this.burl("/api/paciente/egresados"));
      this.pacientes = pacientes.data;
      this.isLoading = false;
    },
    async getPacientesFallecidos() {
      this.isLoading = true;
      const pacientes = await axios.get(this.burl("/api/paciente/fallecidos"));
      this.pacientes = pacientes.data;
      this.isLoading = false;
    },
    getSistema(paciente) {
      return paciente.fecha_egreso || paciente.fecha_obito
        ? "-"
        : paciente.sistema;
    },
    getSala(paciente) {
      return paciente.fecha_egreso || paciente.fecha_obito
        ? "-"
        : paciente.sala;
    },
    getCama(paciente) {
      return paciente.fecha_egreso || paciente.fecha_obito
        ? "-"
        : paciente.cama;
    },
    getEstado(paciente) {
      if (paciente.fecha_egreso) {
        return "Egresado";
      } else if (paciente.fecha_obito) {
        return "Fallecido";
      } else {
        return "Internado";
      }
    },
    async getMedicosDelSistema(pacienteId) {
      this.isLoading = true;
      this.pacienteSeleccionado = pacienteId;
      let sistemaId = this.sistemaId ? "?sistema=" + this.sistemaId : "";
      const medicos = await axios.get(
        this.burl(
          "/api/sistemas/medicos" + sistemaId + "&paciente=" + pacienteId
        )
      );
      this.medicosDelSistema = medicos.data;
      this.mostrarAsignarMedico = true;
      this.isLoading = false;
    },
    async declararEgreso(internacionId) {
      const { value: motivoEgreso } = await 
      this.$swal
      .fire({
        title: "Declarar egreso",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#F33527",
        cancelButtonColor: "#47A44B",
        confirmButtonText:
          "Sí, declarar egreso",
        cancelButtonText: "Cancelar",
        input: 'select',
        inputOptions: {
          'alta_epidemiologica': 'Alta epidemiológica',
          'curado': 'Curado'
        },
        inputPlaceholder: 'Seleccionar motivo',
        showCancelButton: true,
        inputValidator: (value) => {
          return new Promise((resolve) => {
            if (value !== '') {
              resolve()
            } else {
              resolve('Debe seleccionar un motivo')
            }
          })
        }
      })
      if (motivoEgreso) {
        this.isLoading = true
        await axios
          .get(
            this.burl("/api/internacion/egreso/" + motivoEgreso + "?id=" + internacionId)
          )
        this.getPacientesInternados();
      }
    },
    async declararObito(internacionId) {
      const result = await this.$swal
        .fire({
          title: "Está seguro?",
          text: "Esta acción es irreversible",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#F33527",
          cancelButtonColor: "#47A44B",
          confirmButtonText: "Sí, declarar óbito",
          cancelButtonText: "Cancelar"
        })

      if (result.isConfirmed) {
        this.isLoading = true;
        await axios
          .get(
            this.burl("/api/internacion/obito?id=" + internacionId)
          )
        this.getPacientesInternados();
      }
    },
    async asignarMedico(medicoId) {
      this.isLoading = true;
      let form = {
        pacienteId: this.pacienteSeleccionado,
        medicoId: medicoId
      };
      const response = await axios.post(
        this.burl("/api/paciente/asignarMedico"),
        form
      );
      this.isLoading = false;
      this.getMedicosDelSistema(this.pacienteSeleccionado);
      this.$swal.fire({
        icon: "success",
        text: "Médico asignado",
        cancelButtonColor: "#47A44B"
      });
    },
    async desasignarMedico(medicoId) {
      this.isLoading = true;
      let form = {
        pacienteId: this.pacienteSeleccionado,
        medicoId: medicoId
      };
      const response = await axios.post(
        this.burl("/api/paciente/desasignarMedico"),
        form
      );
      this.isLoading = false;
      this.getMedicosDelSistema(this.pacienteSeleccionado);
      this.$swal.fire({
        icon: "success",
        text: "Médico desasignado",
        cancelButtonColor: "#47A44B"
      });
    }
  },
  computed: {
    nombreSistemaComp() {
      return this.sistemaNombre ? this.sistemaNombre : this.sistemaNombreLocal;
    },
    nombreSalaComp() {
      return this.salaNombre ? ", " + this.salaNombre : "";
    },
    puedeDeclararEgreso() {
      return (
        this.sistemaNombreLocal == "Piso Covid" ||
        this.sistemaNombreLocal == "Domicilio" ||
        this.sistemaNombreLocal == "Hotel"
      );
    }
  }
};
</script>

<style>
.vgt-input,
.vgt-select {
  width: 50%;
}

.vgt-global-search,
.vgt-table thead th,
.vgt-table th.line-numbers,
.vgt-table th.vgt-checkbox-col {
  background: linear-gradient(#ffffff, #ffffff);
  color: #4ba64f;
  border-right: none;
  border-bottom: 1px solid #ffffff;
}

table.vgt-table {
  font-size: 14px;
  border-collapse: collapse;
  background-color: #ffffff;
  table-layout: auto;
  border: 1px solid #ffffff;
}

.vgt-global-search {
  padding: 10px 0;
  border: 1px solid #ffffff;
}

.vgt-wrap__footer {
  color: #ffffff;
  padding: 1em;
  border: 1px solid #ffffff;
  background: linear-gradient(#ffffff, #ffffff);
}

.vgt-wrap__footer .footer__navigation__page-btn {
  text-decoration: none;
  color: #606266;
  font-weight: 700;
  white-space: nowrap;
}

.vgt-table thead th.sorting-asc:after {
  border-bottom: 5px solid #4ba64f;
}

.vgt-table thead th.sorting-desc:before {
  border-top: 5px solid #4ba64f;
}

.vgt-right-align {
  text-align: left;
}

table.vgt-table td {
  padding: 0.5em 0.5em 0.5em 0.5em;
  vertical-align: middle;
  border-bottom: 1px solid #dcdfe6;
  color: #606266;
}

.vgt-right-align {
  text-align: left;
}

.vgt-wrap__footer .footer__navigation__page-btn .chevron.right::after {
  border-left: 6px solid #4ba64f;
  margin-left: -3px;
}

.vgt-wrap__footer .footer__navigation__page-btn .chevron.left::after {
  border-right: 6px solid #4ba64f;
  margin-left: -3px;
}
</style>
