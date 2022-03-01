<template>
  <div>
    <loading :active.sync="isLoading" :is-full-page="false" color="#4CAF50">
    </loading>

    <md-card>
      <md-card-header data-background-color="green">
        <h3 class="title">Nueva Internación</h3>
      </md-card-header>

      <md-card-content>
        <ValidationObserver v-slot="{ invalid }">
          <form @submit.prevent="submit">
            <div class="md-layout">
              <div
                class="md-layout-item md-small-size-100 md-size-100"
                style="margin-top: 20px"
              >
                <ValidationProvider rules="required" v-slot="{ errors }">
                  <datepicker
                    v-model="fechaInicioSintomas"
                    :lang="datePickerOptions"
                    placeholder="Fecha inicio síntomas"
                    :disabled-date="datePickerOptions.disabledDate"
                    format="DD/MM/YYYY"
                  >
                  </datepicker>
                  <span class="field-error">{{ errors[0] }}</span>
                </ValidationProvider>
              </div>

              <div
                class="md-layout-item md-small-size-100 md-size-100"
                style="margin-top: 20px; margin-bottom: 10px;"
              >
                <ValidationProvider rules="required" v-slot="{ errors }">
                  <datepicker
                    v-model="fechaDiagnostico"
                    :lang="datePickerOptions"
                    placeholder="Fecha diagnóstico"
                    :disabled-date="datePickerOptions.disabledDate"
                    format="DD/MM/YYYY"
                  >
                  </datepicker>
                  <span class="field-error">{{ errors[0] }}</span>
                </ValidationProvider>
              </div>

              <div class="md-layout-item md-size-20">
                <md-field>
                  <ValidationProvider rules="required" v-slot="{ errors }">
                    <label>Descripción</label>
                    <md-textarea v-model="descripcion"></md-textarea>
                    <span class="field-error">{{ errors[0] }}</span>
                  </ValidationProvider>
                </md-field>
              </div>

              <div class="md-layout-item md-size-80">
                &nbsp;
              </div>

              <div class="md-layout-item md-size-50">
                <md-button
                  type="submit"
                  class="md-raised md-success"
                  :disabled="invalid"
                  >Guardar</md-button
                >
              </div>
            </div>
          </form>
        </ValidationObserver>
      </md-card-content>
    </md-card>
  </div>
</template>

<script>
import Datepicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import "vue2-datepicker/locale/es";
import Loading from "vue-loading-overlay";
// Import stylesheet
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  components: {
    Datepicker,
    Loading
  },
  props: ["pacienteId"],
  data() {
    return {
      fechaInicioSintomas: null,
      fechaDiagnostico: null,
      descripcion: null,
      isLoading: false,
      datePickerOptions: {
        disabledDate(date) {
          return date > new Date();
        },
        formatLocale: {
          firstDayOfWeek: 1
        },
        monthBeforeYear: false
      }
    };
  },
  methods: {
    async submit() {
      this.isLoading = true;
      let formData = {
        fecha_inicio_sintomas: this.formatDate(
          this.fechaInicioSintomas.toISOString()
        ),
        fecha_diagnostico: this.formatDate(this.fechaDiagnostico.toISOString()),
        sintomas: this.descripcion,
        pacienteId: this.pacienteId
      };
      const response = await axios.post(
        this.burl("/api/internacion/new"),
        formData
      );
      this.$swal
        .fire({
          title: "Internación creada",
          icon: "success",
          timer: 2000,
          showConfirmButton: false
        })
        .then(() => {
          this.$router.push({
            name: "Ver Paciente",
            params: { pacienteId: this.pacienteId }
          });
        });
      this.isLoading = false;
    }
  }
};
</script>

<style>
.field-error {
  color: red;
}
</style>
