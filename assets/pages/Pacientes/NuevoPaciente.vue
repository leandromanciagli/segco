<template>
  <div>
    <loading :active.sync="isLoading" :is-full-page="false" color="#4CAF50">
    </loading>

    <md-card>
      <md-card-header data-background-color="green">
        <span class="md-title" v-if="pacienteId">Editar Paciente</span>
        <span class="md-title" v-else>Nuevo Paciente</span>
      </md-card-header>

      <md-card-content>
        <ValidationObserver v-slot="{ invalid }">
          <form @submit.prevent="submit">
            <div class="md-layout">
              <div class="md-layout-item md-small-size-50 md-size-50">
                <span class="md-title">Datos filiatorios</span>
                <div class="md-layout">
                  <div class="md-layout-item md-small-size-50 md-size-50">
                    <md-field>
                      <label>DNI</label>
                      <md-input v-model="dni" disabled type="number"></md-input>
                    </md-field>
                  </div>

                  <div
                    class="md-layout-item md-small-size-50 md-size-50"
                    style="margin-top: 23px"
                  >
                    <ValidationProvider rules="required" v-slot="{ errors }">
                      <datepicker
                        v-model="fechaNacimiento"
                        :lang="datePickerOptions"
                        placeholder="Fecha de nacimiento"
                        :disabled-date="datePickerOptions.disabledDate"
                        format="DD/MM/YYYY"
                      >
                      </datepicker>
                      <span class="field-error">{{ errors[0] }}</span>
                    </ValidationProvider>
                  </div>
                </div>

                <div class="md-layout">
                  <div class="md-layout-item md-small-size-50 md-size-50">
                    <md-field>
                      <ValidationProvider
                        rules="required|alpha_spaces"
                        v-slot="{ errors }"
                      >
                        <label>Apellido</label>
                        <md-input v-model="apellido" type="text"></md-input>
                        <span
                          class="field-error"
                          v-for="error in errors"
                          v-bind:key="error.id"
                          >{{ error }}</span
                        >
                      </ValidationProvider>
                    </md-field>
                  </div>

                  <div class="md-layout-item md-small-size-50 md-size-50">
                    <md-field>
                      <ValidationProvider
                        rules="required|alpha_spaces"
                        v-slot="{ errors }"
                      >
                        <label>Nombre</label>
                        <md-input v-model="nombre" type="text"></md-input>
                        <span
                          class="field-error"
                          v-for="error in errors"
                          v-bind:key="error.id"
                          >{{ error }}</span
                        >
                      </ValidationProvider>
                    </md-field>
                  </div>
                </div>

                <div class="md-layout">
                  <div class="md-layout-item md-small-size-50 md-size-50">
                    <md-field>
                      <ValidationProvider rules="required" v-slot="{ errors }">
                        <label>Teléfono</label>
                        <md-input v-model="telefono" type="text"></md-input>
                        <span class="field-error">{{ errors[0] }}</span>
                      </ValidationProvider>
                    </md-field>
                  </div>

                  <div class="md-layout-item md-small-size-50 md-size-50">
                    <md-field>
                      <ValidationProvider rules="required" v-slot="{ errors }">
                        <label>Domicilio</label>
                        <md-input v-model="domicilio" type="text"></md-input>
                        <span class="field-error">{{ errors[0] }}</span>
                      </ValidationProvider>
                    </md-field>
                  </div>
                </div>
                <div class="md-layout">
                  <div class="md-layout-item md-small-size-50 md-size-50">
                    <md-field>
                      <ValidationProvider
                        rules="required|email"
                        v-slot="{ errors }"
                      >
                        <label>Email</label>
                        <md-input v-model="email" type="email"></md-input>
                        <span
                          class="field-error"
                          v-for="error in errors"
                          v-bind:key="error.id"
                          >{{ error }}</span
                        >
                      </ValidationProvider>
                    </md-field>
                  </div>

                  <div class="md-layout-item md-small-size-50 md-size-50">
                    <!-- <md-field>
                      <label>Obra social</label>
                      <md-input v-model="obraSocial" type="text"></md-input>
                    </md-field> -->

                    <md-field>
                      <label for="obraSocial">Obra Social</label>
                      <md-select
                        v-model="obraSocial"
                        name="obraSocial"
                        md-dense
                      >
                        <md-option value="APRES">APRES</md-option>
                        <md-option value="IOMA">IOMA</md-option>
                        <md-option value="OSDE">OSDE</md-option>
                        <md-option value="OSPE">OSPE</md-option>
                        <md-option value="PAMI">PAMI</md-option>
                      </md-select>
                    </md-field>
                  </div>
                </div>
              </div>

              <div class="md-layout-item md-small-size-100 md-size-50">
                <span class="md-title">Datos de algún contacto</span>

                <div class="md-layout">
                  <div class="md-layout-item md-small-size-50 md-size-50">
                    <md-field>
                      <ValidationProvider
                        rules="alpha_spaces"
                        v-slot="{ errors }"
                      >
                        <label>Apellido</label>
                        <md-input
                          v-model="apellidoContacto"
                          type="text"
                        ></md-input>
                        <span class="field-error">{{ errors[0] }}</span>
                      </ValidationProvider>
                    </md-field>
                  </div>

                  <div class="md-layout-item md-small-size-50 md-size-50">
                    <md-field>
                      <label>Nombre</label>
                      <md-input v-model="nombreContacto" type="text"></md-input>
                    </md-field>
                  </div>
                </div>
                <div class="md-layout">
                  <div class="md-layout-item md-small-size-50 md-size-50">
                    <md-field>
                      <label>Teléfono</label>
                      <md-input
                        v-model="telefonoContacto"
                        type="text"
                      ></md-input>
                    </md-field>
                  </div>

                  <div class="md-layout-item md-small-size-50 md-size-50">
                    <md-field>
                      <ValidationProvider
                        rules="alpha_spaces"
                        v-slot="{ errors }"
                      >
                        <label>Relación</label>
                        <md-input
                          v-model="relacionContacto"
                          type="text"
                        ></md-input>
                        <span class="field-error">{{ errors[0] }}</span>
                      </ValidationProvider>
                    </md-field>
                  </div>
                </div>

                <span class="md-title">Antecedentes personales</span>

                <div class="md-layout">
                  <div class="md-layout-item md-size-100 right">
                    <md-field maxlength="5">
                      <label
                        >Ingrese un resumen de las enfermedades previas del
                        paciente</label
                      >
                      <md-textarea
                        v-model="antecedentesPersonales"
                      ></md-textarea>
                    </md-field>
                  </div>
                </div>
              </div>

              <div class="md-layout-item md-size-50 text-right">
                &nbsp;
              </div>

              <div class="md-layout-item md-size-50 text-right">
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
  name: "BasicSelect",
  props: ["pacienteId"],
  data() {
    return {
      dni: null,
      apellido: null,
      nombre: null,
      fechaNacimiento: null,
      telefono: null,
      domicilio: null,
      obraSocial: null,
      email: null,
      apellidoContacto: null,
      nombreContacto: null,
      telefonoContacto: null,
      relacionContacto: null,
      antecedentesPersonales: null,
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
  created() {
    if (this.pacienteId) {
      this.getPaciente();
    } else {
      this.existsWithDni();
    }
  },
  methods: {
    async getPaciente() {
      this.isLoading = true;
      const response = await axios.get(
        this.burl("/api/paciente/getPaciente?id=" + this.pacienteId)
      );
      (this.dni = response.data.dni),
      (this.apellido = response.data.apellido),
      (this.nombre = response.data.nombre),
      (this.fechaNacimiento = new Date(response.data.fecha_nacimiento)),
      (this.telefono = response.data.telefono),
      (this.domicilio = response.data.direccion),
      (this.obraSocial = response.data.obra_social),
      (this.email = response.data.email),
      (this.apellidoContacto = response.data.contacto_apellido),
      (this.nombreContacto = response.data.contacto_nombre),
      (this.telefonoContacto = response.data.contacto_telefono),
      (this.relacionContacto = response.data.contacto_parentesco),
      (this.antecedentesPersonales = response.data.antecedentes);
      this.isLoading = false;
    },
    async submit() {
      this.isLoading = true;
      let formData = {
        dni: this.dni,
        apellido: this.apellido,
        nombre: this.nombre,
        direccion: this.domicilio,
        telefono: this.telefono,
        email: this.email,
        fecha_nacimiento: this.fechaNacimiento
          ? this.fechaNacimiento.toISOString()
          : null,
        obra_social: this.obraSocial,
        antecedentes: this.antecedentesPersonales,
        contacto_nombre: this.nombreContacto,
        contacto_apellido: this.apellidoContacto,
        contacto_telefono: this.telefonoContacto,
        contacto_parentesco: this.parentescoContacto
      };
      let url = this.pacienteId ? "/api/paciente/update/" + this.pacienteId : "/api/paciente/new";
      const response = await axios.post(this.burl(url), formData);
      this.$swal
        .fire({
          title: this.pacienteId ? "Paciente actualizado" : "Paciente creado",
          icon: "success",
          timer: 2000,
          showConfirmButton: false
        })
        .then(() => {
          if (this.pacienteId) {
            this.$router.push({
              name: "Ver Paciente",
              params: { pacienteId: this.pacienteId }
            });
          } else {
            this.$router.push({
              path: "nuevaInternacion/" + response.data.id
            });
          }
        });
      this.isLoading = false;
    },
    async existsWithDni() {
      this.$swal
        .fire({
          title: "Por favor ingrese el número de DNI del nuevo paciente",
          input: "number",
          inputAttributes: {
            min: 0,
            max: 99999999
          },
          confirmButtonText: "Aceptar",
          showLoaderOnConfirm: true,
          showCancelButton: true,
          cancelButtonText: "Volver",
          allowOutsideClick: false,
          allowEscapeKey: false,
          confirmButtonColor: "#4caf50",
          preConfirm: result => {
            let data = { dni: result };
            return axios
              .post("api/paciente/existsWithDni", data)
              .then(response => {
                if (response.status != 200) {
                  throw new Error("Algo salió mal");
                }
                return response.data;
              })
              .catch(error => {
                this.$swal.showValidationMessage(`Ocurrió un error: ${error}`);
              });
          },
          allowOutsideClick: () => !this.$swal.isLoading()
        })
        .then(response => {
          if (response.isDismissed) {
            return this.$router.go(-1);
          } else if (response.value.exists) {
            this.$swal
              .fire({
                title: response.value.title,
                text: response.value.message,
                icon: "error",
                confirmButtonText: "Aceptar",
                allowOutsideClick: false,
                confirmButtonColor: "#4caf50"
              })
              .then(response => {
                this.existsWithDni();
              });
          } else {
            this.dni = response.value.dni;
          }
        });
    }
  }
};
</script>

<style>
.field-error {
  color: red;
}
</style>
