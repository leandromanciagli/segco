<template>
  <div>
    <loading :active.sync="isLoading" :is-full-page="true" color="#4CAF50">
    </loading>

    <form>
      <md-card>
        <md-card-header data-background-color="green">
          <span class="md-title">Ver Internación</span>
        </md-card-header>

        <md-card-content>
          <div class="md-layout">
            <div class="md-layout-item md-small-size-100 md-size-33">
              <span class="md-title">Internación</span>

              <div>
                <div class="md-layout-item">
                  <span class="md-body-1">Fecha de inicio de síntomas:</span>
                  <div class="md-layout-item">
                    <span class="md-body-1">
                      {{ formatDate(internacion.fecha_inicio_sintomas) }}
                    </span>
                  </div>
                </div>

                <div class="md-layout-item">
                  <span class="md-body-1">Fecha de diagnóstico de Covid:</span>
                  <div class="md-layout-item">
                    <span class="md-body-1"
                      >{{ formatDate(internacion.fecha_diagnostico) }} hs.</span
                    >
                  </div>
                </div>

                <div class="md-layout-item">
                  <span class="md-body-1">Fecha de internación:</span>
                  <div class="md-layout-item">
                    <span class="md-body-1"
                      >{{ formatDateTime(internacion.fecha_carga) }}
                    </span>
                  </div>
                </div>

                <div class="md-layout-item" v-if="internacion.fecha_obito">
                  <span class="md-body-1">El paciente falleció.</span>
                  <div class="md-layout">
                    <span class="md-body-1"
                      >Fecha de óbito:
                      {{ formatDateTime(internacion.fecha_obito) }}
                      hs.</span
                    >
                  </div>
                </div>

                <div class="md-layout-item" v-if="internacion.fecha_egreso">
                  <span class="md-body-1">El paciente egresó.</span>
                  <div class="md-layout">
                    <span class="md-body-1"
                      >Fecha de egreso:
                      {{ formatDateTime(internacion.fecha_egreso) }}
                      hs.
                    </span>
                  </div>
                </div>

                <div class="md-layout-item" v-if="internacion.fecha_egreso">
                  <div class="md-layout">
                    <span class="md-body-1"
                      >Motivo:
                      {{ motivoEgreso }}
                    </span>
                  </div>
                </div>

              </div>
            </div>

            <div class="md-layout-item md-small-size-100 md-size-66">
              <span class="md-title">Evoluciones</span>
              <br />
              <br />
              <md-empty-state
                md-rounded
                md-icon="assignment"
                md-label="No hay evoluciones"
                md-description="Este paciente no registró evoluciones durante esta internación."
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
    Loading,
    VerEvolucionModal
  },
  props: ["internacionId"],
  data() {
    return {
      isLoading: false,
      paciente: {},
      internacion: "",
      evoluciones: [],
      evolucion: null,
      mostrarEvolucion: false,
      expandSingle: false
    };
  },
  created() {
    this.getInternacionYEvoluciones();
  },
  methods: {
    async getInternacionYEvoluciones() {
      this.isLoading = true;
      const internacion = await axios.get(
        this.burl("/api/internacion/getInternacion?id=" + this.internacionId)
      );
      this.internacion = internacion.data;
      if (this.internacion) {
        const evoluciones = await axios.get(
          this.burl("/api/evolucion/index?id=" + this.internacionId)
        );
        if (evoluciones.data) {
          this.evoluciones = evoluciones.data;
          this.getEvolucionesIntercaladasConCambiosDeSistema();
        }
      }
      this.isLoading = false;
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
    motivoEgreso() {
      return this.internacion.motivo_egreso == 'alta_epidemiologica' ? 'Alta epidemiológica' : 'Curado'
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
</style>
