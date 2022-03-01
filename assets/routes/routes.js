import DashboardLayout from "@/pages/Layout/DashboardLayout.vue";
import Dashboard from "@/pages/Dashboard.vue";
import Login from "@/pages/Login.vue";
import Pacientes from "@/pages/Pacientes/Pacientes.vue";
import NuevoPaciente from "@/pages/Pacientes/NuevoPaciente.vue";
import VerPaciente from "@/pages/Pacientes/VerPaciente.vue";
import NuevaEvolucion from "@/pages/Evoluciones/NuevaEvolucion.vue";
import NuevaInternacion from "@/pages/Internaciones/NuevaInternacion.vue";
import VerInternacion from "@/pages/Internaciones/VerInternacion.vue";
import Sistemas from "@/pages/Sistemas/Sistemas.vue";
import Logout from "@/pages/Logout.vue";
import Reglas from "@/pages/Reglas/Reglas.vue";
import NuevaRegla from "@/pages/Reglas/NuevaRegla.vue";
import Alertas from "@/pages/Alertas/Alertas.vue";
import Salas from "@/pages/Sistemas/Salas.vue";
import Home from "@/pages/Home.vue";

/*
Si no quiero agregar el footer poner la opcion   hideFooter: true ejemplo: 
{
  path: "notifications",
  name: "Notifications",
  meta: {
    hideFooter: true
  },
  component: Notifications
},
*/
const routes = [
  {
    path: "/",
    component: DashboardLayout,
    redirect: "/login",
    children: [
      {
        path: "dashboard",
        name: "Dashboard",
        component: Dashboard,
        meta: {
          requiresAuth: true,
          role: ["ROLE_JEFE"]
        }
      },
      {
        path: "login",
        name: "Iniciar sesión",
        component: Login
      },
      {
        path: "home",
        name: "Redireccion",
        component: Home,
        meta: {
          requiresAuth: true
        }
      },
      {
        path: "logout",
        name: "Cerrar sesión",
        component: Logout
      },
      {
        path: "sistemas",
        name: "Sistemas",
        component: Sistemas,
        meta: {
          requiresAuth: true,
          role: ["ROLE_JEFE"]
        }
      },
      {
        path: "pacientes/:sistemaId?/:sistemaNombre?/:salaId?/:salaNombre?",
        name: "Pacientes",
        component: Pacientes,
        props: true,
        meta: {
          requiresAuth: true,
          role: ["ROLE_JEFE","ROLE_MEDICO"]
        }
      },
      {
        path: "paciente/:pacienteId?",
        name: "Paciente",
        component: NuevoPaciente,
        props: true,
        meta: {
          requiresAuth: true,
          role: ["ROLE_JEFE","ROLE_MEDICO"]
        }
      },
      {
        path: "verPaciente/:pacienteId",
        name: "Ver Paciente",
        component: VerPaciente,
        props: true,
        meta: {
          requiresAuth: true,
          role: ["ROLE_JEFE","ROLE_MEDICO"]
        }
      },
      {
        path: "nuevaEvolucion/:internacionId/:pacienteId",
        name: "Nueva Evolución",
        component: NuevaEvolucion,
        props: true,
        meta: {
          requiresAuth: true,
          role: ["ROLE_JEFE","ROLE_MEDICO"]
        }
      },
      {
        path: "nuevaInternacion/:pacienteId",
        name: "Nueva Internación",
        component: NuevaInternacion,
        props: true,
        meta: {
          requiresAuth: true,
          role: ["ROLE_JEFE"]
        }
      },
      {
        path: "verInternacion/:internacionId",
        name: "Ver Internación",
        component: VerInternacion,
        props: true,
        meta: {
          requiresAuth: true,
          role: ["ROLE_JEFE","ROLE_MEDICO"]
        }
      },
      {
        path: "reglas",
        name: "Reglas",
        component: Reglas,
        props: true,
        meta: {
          requiresAuth: true,
          role: ["ROLE_ADMIN"]
        }
      },
      {
        path: "regla/:reglaId?",
        name: "Nueva Regla",
        component: NuevaRegla,
        props: true,
        meta: {
          requiresAuth: true,
          role: ["ROLE_ADMIN"]
        }
      },
      {
        path: "alertas",
        name: "Alertas",
        component: Alertas,
        props: true,
        meta: {
          requiresAuth: true,
          role: ["ROLE_JEFE","ROLE_MEDICO"]
        }
      },
      {
        path: "salas/:sistemaNombre/:sistemaId",
        name: "Salas",
        component: Salas,
        props: true,
        meta: {
          requiresAuth: true,
          role: ["ROLE_JEFE"]
        }
      }
    ]
  }
];

export default routes;
