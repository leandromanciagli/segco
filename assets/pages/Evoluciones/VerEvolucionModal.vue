<template>
  <div>
    <md-dialog :md-active.sync="mostrar">
      <md-dialog-title>Ver evolución</md-dialog-title>

      <md-dialog-content>
        <div class="md-layout">
          <div
            class="md-layout-item md-small-size-100 md-large-size-100 md-medium-size-100"
          >
            <h4>Signos vitales</h4>

            <div class="md-layout-item">
              <span class="md-body-1">Temperatura: {{ data.temperatura }}</span>
            </div>

            <div class="md-layout-item">
              <span class="md-body-1"
                >TA Sistólica: {{ data.tension_arterial_sistolica }}</span
              >
            </div>

            <div class="md-layout-item">
              <span class="md-body-1"
                >TA Diastólica: {{ data.tension_arterial_diastolica }}</span
              >
            </div>

            <div class="md-layout-item">
              <span class="md-body-1"
                >Frecuencia cardíaca: {{ data.frecuencia_cardiaca }}</span
              >
            </div>

            <div class="md-layout-item">
              <span class="md-body-1"
                >Frecuencia respiratoria:
                {{ data.frecuencia_respiratoria }}</span
              >
            </div>

            <h4>Sistema respiratorio</h4>

            <div class="md-layout-item">
              <span class="md-body-1"
                >Mecánica ventilatoria: {{ data.mecanica_ventilatoria }}</span
              >
            </div>

            <div class="md-layout-item">
              <span class="md-body-1"
                >Requiere O2 suplementario: {{ requiereO2 }}
              </span>
            </div>

            <div class="md-layout-item">
              <span v-if="data.canula_nasal_oxigeno" class="md-body-1"
                >Valor cánula nasal de oxígeno:
                {{ data.canula_nasal_oxigeno }}</span
              >
            </div>

            <div class="md-layout-item">
              <span v-if="data.mascara_con_reservorio" class="md-body-1"
                >Valor máscara con reservorio:
                {{ data.mascara_con_reservorio }}</span
              >
            </div>

            <div class="md-layout-item">
              <span v-if="data.saturacion_oxigeno" class="md-body-1"
                >Saturación O2: {{ data.saturacion_oxigeno }}</span
              >
            </div>

            <div class="md-layout-item">
              <span v-if="data.pafi" class="md-body-1"
                >Valor PaFi: {{ data.pafi }}</span
              >
            </div>

            <div class="md-layout-item">
              <span class="md-body-1"
                >Prono vigil: {{ data.prono_vigil | castBoolean }}</span
              >
            </div>

            <div class="md-layout-item">
              <span class="md-body-1">Tos: {{ data.tos | castBoolean }}</span>
            </div>

            <div class="md-layout-item">
              <span v-if="data.disnea" class="md-body-1"
                >Disnea: {{ data.disnea }}</span
              >
            </div>

            <div class="md-layout-item">
              <span class="md-body-1"
                >Estabilidad/desaparición de síntomas respiratorios:
                {{
                  data.estabilidad_desaparicion_sintomas_resp | castBoolean
                }}</span
              >
            </div>

            <h4>Otros síntomas</h4>

            <div class="md-layout-item">
              <span class="md-body-1"
                >Somnolencia: {{ data.somnolencia | castBoolean }}</span
              >
            </div>

            <div class="md-layout-item">
              <span class="md-body-1"
                >Anosmia: {{ data.anosmia | castBoolean }}</span
              >
            </div>

            <div class="md-layout-item">
              <span class="md-body-1"
                >Disgeusia: {{ data.disgeusia | castBoolean }}</span
              >
            </div>

            <h4>Estudios realizados</h4>

            <div class="md-layout-item">
              <span v-if="data.radiografia_tipo" class="md-body-1"
                >Radiografía tipo: {{ data.radiografia_tipo }}</span
              >
            </div>

            <div class="md-layout-item">
              <span v-if="data.radiografia_descrip" class="md-body-1"
                >Descripción radiografía: {{ data.radiografia_descrip }}</span
              >
            </div>

            <div class="md-layout-item">
              <span v-if="data.tomografia_torax_tipo" class="md-body-1"
                >Tomografía tórax tipo: {{ data.tomografia_torax_tipo }}</span
              >
            </div>

            <div class="md-layout-item">
              <span v-if="data.tomografia_torax_descrip" class="md-body-1"
                >Descripción tomografía tórax:
                {{ data.tomografia_torax_descrip }}</span
              >
            </div>

            <div class="md-layout-item">
              <span v-if="data.electrocardiograma_tipo" class="md-body-1"
                >Electrocardiograma tipo:
                {{ data.electrocardiograma_tipo }}</span
              >
            </div>

            <div class="md-layout-item">
              <span v-if="data.electrocardiograma_descrip" class="md-body-1"
                >Descripción Electrocardiograma:
                {{ data.electrocardiograma_descrip }}</span
              >
            </div>

            <div class="md-layout-item">
              <span v-if="data.pcr_covid_tipo" class="md-body-1"
                >PCR Covid tipo: {{ data.pcr_covid_tipo }}</span
              >
            </div>

            <div class="md-layout-item">
              <span v-if="data.pcr_covid_descrip" class="md-body-1"
                >Descripción PCR Covid: {{ data.pcr_covid_descrip }}</span
              >
            </div>

            <h4>Observaciones</h4>

            <div class="md-layout-item">
              <span v-if="data.observacion" class="md-body-1">{{
                data.observacion
              }}</span>
            </div>
          </div>
        </div>
      </md-dialog-content>

      <md-dialog-actions>
        <md-button class="md-success" @click="cerrar">Cerrar</md-button>
      </md-dialog-actions>
    </md-dialog>
  </div>
</template>

<script>
export default {
  name: "evolucion",
  props: ["data", "mostrar"],
  methods: {
    cerrar() {
      this.$emit("cerrarEvolucionModal");
    }
  },
  computed: {
    requiereO2() {
      return this.data.canula_nasal_oxigeno || this.data.mascara_con_reservorio
        ? "Si"
        : "No";
    }
  },
  filters: {
    castBoolean(value) {
      return value ? "Si" : "No";
    }
  }
};
</script>
