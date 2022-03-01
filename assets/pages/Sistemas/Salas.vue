<template>
  <div>
    <loading :active.sync="isLoading" :is-full-page="true" color="#4CAF50">
    </loading>

    <div class="content">
      <div class="md-layout">
        <div
          v-for="sala in salas"
          :key="sala.id"
          class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-33"
        >
          <router-link
            :to="{
              name: 'Pacientes',
              params: {
                sistemaNombre: sistemaNombre,
                sistemaId: sistemaId,
                salaId: sala.id,
                salaNombre: sala.nombre
              }
            }"
          >
            <stats-card data-background-color="green">
              <template slot="header">
                <md-icon>local_hospital</md-icon>
              </template>

              <template slot="content">
                <h3 class="title">{{ sala.nombre }}</h3>
                <div v-if="sala.nombre != 'Sala Domicilio'">
                  <p class="category">
                    Camas totales:{{ sala.camas_total }}
                  </p>
                  <p class="category">
                    Camas ocupadas: {{ sala.camas_ocupadas }}
                  </p>
                  <p class="category">
                    Camas disponibles: {{ sala.camas_disponibles }}
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
  props: ["sistemaNombre", "sistemaId"],
  data() {
    return {
      isLoading: false,
      salas: []
    };
  },
  created() {
    this.getSalas();
  },
  methods: {
    async getSalas() {
      this.isLoading = true;
      const salas = await axios.get(
        this.burl("/api/sistemas/salas?id=" + this.sistemaId)
      );
      this.salas = salas.data;
      this.isLoading = false;
    }
  }
};
</script>
