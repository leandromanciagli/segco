<template>
  <div>
    <loading
      :active.sync="isLoading"
      :is-full-page="true"
      :opacity="1"
      color="#4CAF50"
    >
    </loading>

    <form>
      <md-card>
        <md-card-header data-background-color="green">
          <span class="md-title">Ver Paciente</span>
        </md-card-header>

        <md-card-content>
          <div class="md-layout">
            <div class="md-layout-item md-small-size-100 md-size-33">
              <span class="md-title">Datos filiatorios</span>

              <div class="md-layout-item">
                <span class="md-body-1">Dni: {{ paciente.dni }}</span>
              </div>

              <div class="md-layout-item">
                <span class="md-body-1">Nombre: {{ paciente.nombre }}</span>
              </div>

              <div class="md-layout-item">
                <span class="md-body-1">Apellido: {{ paciente.apellido }}</span>
              </div>

              <div class="md-layout-item">
                <span class="md-body-1"
                  >Dirección: {{ paciente.direccion }}</span
                >
              </div>

              <div class="md-layout-item">
                <span class="md-body-1">Teléfono: {{ paciente.telefono }}</span>
              </div>

              <div class="md-layout-item">
                <span class="md-body-1">Email: {{ paciente.email }}</span>
              </div>

              <div class="md-layout-item">
                <span class="md-body-1"
                  >Fecha de nacimiento:
                  {{ formatDate(paciente.fecha_nacimiento) }}</span
                >
              </div>

              <div class="md-layout-item">
                <span class="md-body-1"
                  >Obra Social: {{ paciente.obra_social }}</span
                >
              </div>

              <div>
                <md-dialog :md-active.sync="mostrarAntecedentes">
                  <md-dialog-title>Antecedentes</md-dialog-title>

                  <md-dialog-content>
                    <span v-if="paciente.antecedentes" class="md-body">{{
                      paciente.antecedentes
                    }}</span>
                    <span v-else class="md-body">Sin antecedentes</span>
                  </md-dialog-content>

                  <md-dialog-actions>
                    <md-button
                      class="md-success"
                      @click="mostrarAntecedentes = false"
                      >Cerrar</md-button
                    >
                  </md-dialog-actions>
                </md-dialog>
              </div>

              <md-button
                class="md-dense md-success"
                @click="mostrarAntecedentes = true"
                >Ver Antecedentes</md-button
              >

              <div>
                <md-dialog :md-active.sync="mostrarContacto">
                  <md-dialog-title>Datos de algún contacto</md-dialog-title>

                  <md-dialog-content>
                    <div class="md-layout-item">
                      <span class="md-body-1"
                        >Nombre: {{ paciente.contacto_nombre }}</span
                      >
                    </div>

                    <div class="md-layout-item">
                      <span class="md-body-1"
                        >Apellido: {{ paciente.contacto_apellido }}</span
                      >
                    </div>

                    <div class="md-layout-item">
                      <span class="md-body-1"
                        >Teléfono: {{ paciente.contacto_telefono }}</span
                      >
                    </div>

                    <div class="md-layout-item">
                      <span class="md-body-1"
                        >Dirección: {{ paciente.contacto_relacion }}</span
                      >
                    </div>
                  </md-dialog-content>

                  <md-dialog-actions>
                    <md-button
                      class="md-success"
                      @click="mostrarContacto = false"
                      >Cerrar</md-button
                    >
                  </md-dialog-actions>
                </md-dialog>
              </div>

              <md-button
                class="md-dense md-success"
                @click="mostrarContacto = true"
                >Ver datos de algún contacto</md-button
              >

              <md-button
                v-if="
                  ultimaInternacion.sistema == loggedUser.sistemaNombre &&
                    !(
                      ultimaInternacion.fecha_egreso ||
                      ultimaInternacion.fecha_obito
                    )
                "
                class="md-dense md-success"
                :to="{
                  name: 'Paciente',
                  params: { pacienteId: pacienteId }
                }"
                >Editar Paciente
              </md-button>
            </div>

            <div class="md-layout-item md-small-size-100 md-size-33">
              <span class="md-title">Internación</span>

              <div
                v-if="
                  !(
                    ultimaInternacion.fecha_egreso ||
                    ultimaInternacion.fecha_obito
                  )
                "
              >
                <div class="md-layout-item">
                  <span class="md-body-1">Fecha de inicio de síntomas:</span>
                  <div class="md-layout-item">
                    <span class="md-body-1"
                      >{{
                        formatDate(ultimaInternacion.fecha_inicio_sintomas)
                      }}
                      </span
                    >
                  </div>
                </div>
                <div class="md-layout-item">
                  <span class="md-body-1">Fecha de diagnóstico de Covid:</span>
                  <div class="md-layout-item">
                    <span class="md-body-1"
                      >{{
                        formatDate(ultimaInternacion.fecha_diagnostico)
                      }}
                      </span
                    >
                  </div>
                </div>
                <div class="md-layout-item">
                  <span class="md-body-1">Fecha de internación:</span>
                  <div class="md-layout-item">
                    <span class="md-body-1"
                      >{{
                        formatDateTime(ultimaInternacion.fecha_carga)
                      }}
                      hs.</span
                    >
                  </div>
                </div>
                <div class="md-layout-item">
                  <span class="md-body-1"
                    >Sistema actual: {{ ultimaInternacion.sistema }}</span
                  >
                </div>
                <div class="md-layout-item">
                  <span class="md-body-1"
                    >Sala: {{ ultimaInternacion.sala }}</span
                  >
                </div>
                <div class="md-layout-item">
                  <span class="md-body-1"
                    >Cama: {{ ultimaInternacion.cama }}</span
                  >
                </div>
              </div>

              <div v-if="ultimaInternacion.fecha_obito">
                <span class="md-body-1">El paciente falleció.</span>
                <div class="md-layout">
                  <span class="md-body-1"
                    >Fecha de óbito:
                    {{ formatDateTime(ultimaInternacion.fecha_obito) }}
                    hs.</span
                  >
                </div>
              </div>

              <div v-if="ultimaInternacion.fecha_egreso">
                <span class="md-body-1"
                  >No posee ninguna internación vigente</span
                >
              </div>

              <div>
                <md-dialog :md-active.sync="mostrarPrevias">
                  <md-dialog-title>Internaciones previas</md-dialog-title>

                  <md-dialog-content>
                    <md-table
                      md-card
                      v-if="internacionesPrevias.length > 0"
                      class="md-body"
                    >
                      <md-table-row>
                        <md-table-head>Fecha ingreso</md-table-head>
                        <md-table-head>Fecha egreso</md-table-head>
                        <md-table-head>Fecha óbito</md-table-head>
                        <md-table-head>Acciones</md-table-head>
                      </md-table-row>

                      <md-table-row
                        v-for="internacion in internacionesPrevias"
                        :key="internacion.id"
                      >
                        <md-table-cell
                          >{{
                            formatDateTime(internacion.fecha_carga)
                          }}.hs</md-table-cell
                        >

                        <md-table-cell v-if="internacion.fecha_egreso"
                          >{{
                            formatDateTime(internacion.fecha_egreso)
                          }}.hs</md-table-cell
                        >
                        <md-table-cell v-else> - </md-table-cell>
                        <md-table-cell v-if="internacion.fecha_obito"
                          >{{
                            formatDateTime(internacion.fecha_obito)
                          }}.hs</md-table-cell
                        >
                        <md-table-cell v-else> - </md-table-cell>
                        <md-table-cell>
                          <md-button
                            :to="{
                              name: 'Ver Internación',
                              params: { internacionId: internacion.id }
                            }"
                            class="md-dense md-success"
                          >
                            Ver
                          </md-button>
                        </md-table-cell>
                      </md-table-row>
                    </md-table>
                    <span v-else class="md-body"
                      >Sin internaciones previas</span
                    >
                  </md-dialog-content>

                  <md-dialog-actions>
                    <md-button
                      class="md-success"
                      @click="mostrarPrevias = false"
                      >Cerrar</md-button
                    >
                  </md-dialog-actions>
                </md-dialog>
              </div>

              <div>
                <md-button
                  class="md-dense md-success"
                  @click="getInternacionesPrevias()"
                  >Internaciones previas</md-button
                >
              </div>

              <div
                v-if="
                  ultimaInternacion.fecha_egreso &&
                    !ultimaInternacion.fecha_obito
                "
              >
                <md-button
                  :to="{
                    name: 'Nueva Internación',
                    params: { pacienteId: pacienteId }
                  }"
                  class="md-dense md-success"
                  >Nueva Internación</md-button
                >
              </div>
            </div>

            <div
              v-if="
                !(
                  ultimaInternacion.fecha_egreso ||
                  ultimaInternacion.fecha_obito
                ) && ultimaInternacion.sistema == loggedUser.sistemaNombre
              "
              class="md-layout-item md-small-size-100 md-size-33"
            >
              <span class="md-title">Otras acciones</span>

              <br /><br />

              <span class="md-subheading">Cambiar de sistema</span>
              <div class="md-layout">
                <div class="md-layout-item md-small-size-40 md-size-40">
                  <md-field>
                    <label for="cambiarDeSistema">Seleccionar</label>
                    <md-select
                      v-model="sistemaDestinoSelected"
                      name="sistemaDestino"
                    >
                      <md-option
                        v-for="sistema in sistemasDestino"
                        :key="sistema.id"
                        :value="sistema.id"
                      >
                        {{ sistema.descrip }}
                      </md-option>
                    </md-select>
                  </md-field>
                </div>

                <div class="md-layout-item md-small-size-40 md-size-40">
                  <md-button class="md-success" @click="cambiarDeSistema()"
                    >Aceptar</md-button
                  >
                </div>
              </div>

              <div>
                <md-button
                  v-if="puedeDeclararEgreso"
                  class="md-dense md-success"
                  @click="declararEgreso()"
                  >Declarar egreso</md-button
                >
              </div>

              <div>
                <md-button class="md-dense md-danger" @click="declararObito()"
                  >Declarar óbito</md-button
                >
              </div>
            </div>

            <div
              v-if="
                !(
                  ultimaInternacion.fecha_egreso ||
                  ultimaInternacion.fecha_obito
                ) && ultimaInternacion.sistema == loggedUser.sistemaNombre
              "
              class="md-layout"
            >
              <div class="md-layout-item md-small-size-100 md-size-100">
                <span class="md-title">Evoluciones</span>

                <md-button
                  :to="{
                    name: 'Nueva Evolución',
                    params: {
                      internacionId: ultimaInternacion.id,
                      pacienteId: pacienteId
                    }
                  }"
                  class="md-dense md-success"
                  id="nuevaEvolucionButton"
                  >Nueva evolución</md-button
                >
                <br />
                <br />
                <md-empty-state
                  md-rounded
                  md-icon="assignment"
                  md-label="No hay evoluciones"
                  md-description="Este paciente aún no registra evoluciones. Las evoluciones registradas se mostrarán aquí."
                  v-if="evoluciones.length == 0"
                >
                </md-empty-state>

                <div class="full-control">
                  <div class="list">
                    <md-list>
                      <md-list-item
                        md-expand
                        v-for="(evol, sistema) in evoluciones"
                        :key="sistema"
                      >
                        <span class="md-list-item-text">{{ sistema }}</span>
                        <md-list slot="md-expand">
                          <md-list-item
                            class="md-inset"
                            v-for="(e, i) in evoluciones[sistema]"
                            :key="i"
                          >
                            Fecha: {{ formatDateTime(e.fecha_carga) }}
                            <md-button
                              class="md-dense"
                              @click="verEvolucion(e.id)"
                              >Ver</md-button
                            >
                          </md-list-item>
                        </md-list>
                      </md-list-item>
                    </md-list>
                  </div>
                </div>

                <ver-evolucion-modal
                  v-if="evolucion"
                  :mostrar="mostrarEvolucion"
                  :data="evolucion"
                  @cerrarEvolucionModal="mostrarEvolucion = false"
                >
                </ver-evolucion-modal>
              </div>
            </div>
          </div>
        </md-card-content>
      </md-card>
    </form>
  </div>
</template>

<script>
import VerEvolucionModal from "./../Evoluciones/VerEvolucionModal";
import Loading from "vue-loading-overlay";
// Import stylesheet
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  name: "IconButtons",
  name: "ListExpansion",
  name: "EmptyStateRounded",
  components: {
    VerEvolucionModal,
    Loading
  },
  props: ["pacienteId"],
  data() {
    return {
      isLoading: false,
      paciente: {},
      ultimaInternacion: "",
      internacionesPrevias: [],
      mostrarAntecedentes: false,
      mostrarContacto: false,
      mostrarPrevias: false,
      mostrarEvolucion: false,
      evoluciones: [],
      sistemasDestino: [],
      sistemaDestinoSelected: "",
      evolucion: null,
      expandSingle: false
    };
  },
  created() {
    this.getPaciente();
    this.getUltimaInternacionYEvoluciones();
    this.getSistemasDestino();
  },
  methods: {
    async getPaciente() {
      axios
        .get(this.burl("/api/paciente/getPaciente?id=" + this.pacienteId))
        .then(response => {
          this.paciente = response.data;
        });
    },
    async getUltimaInternacionYEvoluciones() {
      this.isLoading = true;
      const ultimaInternacion = await axios.get(
        this.burl("/api/internacion/ultima?pacienteId=" + this.pacienteId)
      );
      this.ultimaInternacion = ultimaInternacion.data;
      if (this.ultimaInternacion) {
        const evoluciones = await axios.get(
          this.burl("/api/evolucion/index?id=" + this.ultimaInternacion.id)
        );
        if (evoluciones.data) {
          this.evoluciones = evoluciones.data;
          this.getEvolucionesIntercaladasConCambiosDeSistema();
        }
      }
      this.isLoading = false;
    },
    async getInternacionesPrevias() {
      this.isLoading = true
      const internaciones = await axios.get(
        this.burl("/api/internacion/previas?pacienteId=" + this.pacienteId)
      );
      this.internacionesPrevias = internaciones.data
      this.mostrarPrevias = true
      this.isLoading = false
    },
    async getSistemasDestino() {
      const response = await axios.get(
        this.burl("/api/sistemas/sistemasDestino")
      );
      this.sistemasDestino = response.data;
    },
    async declararEgreso() {
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
        this.isLoading = true;
        const response = await axios
          .get(
            this.burl("/api/internacion/egreso/" + motivoEgreso + "?id=" + this.ultimaInternacion.id)
          )
        this.isLoading = false;
        this.$router.push({
          name: 'Pacientes',
          params: {
            sistemaNombre: this.loggedUser.sistemaNombre,
            sistemaId: this.loggedUser.sistemaId
          }
        });
      }
    },
    async declararObito() {
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
        const response = await axios.get(
            this.burl(
              "/api/internacion/obito?id=" +
              this.ultimaInternacion.id
            )
          )
        this.isLoading = false;
        this.$router.push({
          name: 'Pacientes',
          params: {
            sistemaNombre: this.loggedUser.sistemaNombre,
            sistemaId: this.loggedUser.sistemaId
          }
        })
      }
    },
    async cambiarDeSistema() {
      const result = await this.$swal
      .fire({
        title: "Está seguro?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#F33527",
        cancelButtonColor: "#47A44B",
        confirmButtonText: "Sí, cambiar",
        cancelButtonText: "Cancelar"
      })
        
      if (result.isConfirmed) {
        this.isLoading = true;
        let form = {
          sistemaDestinoId: this.sistemaDestinoSelected,
          internacionId: this.ultimaInternacion.id
        };
        const response = await axios.post(this.burl("/api/paciente/cambiarDeSistema"), form)
        this.isLoading = false;
        this.$router.push({
          name: 'Pacientes',
          params: {
            sistemaNombre: this.loggedUser.sistemaNombre,
            sistemaId: this.loggedUser.sistemaId
          }
        });
      }
    },
    async verEvolucion(evolucionId) {
      this.isLoading = true;
      const response = await axios.get(
        this.burl("/api/evolucion/show?id=" + evolucionId)
      );
      this.evolucion = response.data;
      this.isLoading = false;
      this.mostrarEvolucion = true;
    },
    cerrarEvolucionModal() {
      this.$modal.hide("evolucion");
    },
    getHistorialDeSistemas() {
      return [...new Set(this.evoluciones.map(({ sistema }) => sistema))];
    },
    getEvolucionesIntercaladasConCambiosDeSistema() {
      let evolucionesIntercaladasConCambiosDeSistema = {};
      let sistemas = this.getHistorialDeSistemas();
      sistemas.forEach(sistema => {
        evolucionesIntercaladasConCambiosDeSistema[sistema] = [];
        this.evoluciones.forEach(evolucion => {
          if (evolucion.sistema == sistema) {
            evolucionesIntercaladasConCambiosDeSistema[sistema].push(evolucion);
          }
        });
      });
      this.evoluciones = evolucionesIntercaladasConCambiosDeSistema;
    }
  },
  computed: {
    puedeDeclararEgreso() {
      return (
        this.loggedUser.sistemaNombre == "Piso Covid" ||
        this.loggedUser.sistemaNombre == "Domicilio" ||
        this.loggedUser.sistemaNombre == "Hotel"
      );
    }
  }
};
</script>

<style lang="scss" scoped>
$list-width: 100%;

.full-control {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap-reverse;
}

.list {
  width: $list-width;
}

.full-control > .md-list {
  width: $list-width;
  max-width: 100%;
  height: 400px;
  display: inline-block;
  overflow: auto;
  border: 1px solid rgba(#000, 0.12);
  vertical-align: top;
}

#nuevaEvolucionButton {
  float: right;
}
</style>
