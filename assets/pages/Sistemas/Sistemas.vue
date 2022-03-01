<template>
  <div>
    <loading :active.sync="isLoading" :is-full-page="true" color="#4CAF50">
    </loading>

    <div class="content">
      <div class="md-layout">
        <div
          v-for="sistema in sistemas"
          :key="sistema.id"
          class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-33"
        >
          <router-link
            :to="{
              name: 'Salas',
              params: { sistemaNombre: sistema.descrip, sistemaId: sistema.id }
            }"
          >
            <stats-card
              :data-background-color="sistema.nombre == 'UTI' ? 'red' : 'green'"
            >
              <template slot="header">
                <md-icon v-if="sistema.nombre == 'GUARDIA'"
                  >local_hospital</md-icon
                >
                <md-icon v-if="sistema.nombre == 'PISOCOVID'">healing</md-icon>
                <md-icon v-if="sistema.nombre == 'UTI'">add_alert</md-icon>
                <md-icon v-if="sistema.nombre == 'HOTEL'">hotel</md-icon>
                <md-icon v-if="sistema.nombre == 'DOMICILIO'">home</md-icon>
              </template>

              <template slot="content">
                <h3 class="title">{{ sistema.descrip }}</h3>
                <div v-if="sistema.nombre != 'DOMICILIO'">
                  <p class="category">
                    Camas totales:{{ sistema.camas_total }}
                  </p>
                  <p class="category">
                    Camas ocupadas: {{ sistema.camas_ocupadas }}
                  </p>
                  <p class="category">
                    Camas disponibles: {{ sistema.camas_disponibles }}
                  </p>
                </div>
              </template>
            </stats-card>
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import StatsCard from "../../components/Cards/StatsCard.vue";
import Loading from "vue-loading-overlay";
// Import stylesheet
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  components: {
    StatsCard,
    Loading
  },
  data() {
    return {
      isLoading: false,
      sistemas: []
    };
  },
  created() {
    this.getSistemas();
  },
  methods: {
    async getSistemas() {
      this.isLoading = true;
      const sistemas = await axios.get(this.burl("/api/sistemas/index"));
      this.sistemas = sistemas.data;
      this.isLoading = false;
    }
  }
};
</script>
