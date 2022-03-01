import { extend } from "vee-validate";

import {
  required,
  numeric,
  max,
  double,
  alpha_spaces,
  email
} from "vee-validate/dist/rules";

extend("required", {
  ...required,
  message: "No puede estar vacío"
});

extend("alpha_spaces", {
  ...alpha_spaces,
  message: "Solo letras y/o espacios"
});

extend("email", {
  ...email,
  message: "Debe ser un email válido"
});

extend("numeric", {
  ...numeric,
  message: "Debe ser un valor numérico"
});

extend("double", {
  ...double,
  message: "Debe ser un valor decimal"
});

extend("max", {
  ...max,
  validate(value, { length }) {
    return value.length <= length;
  },
  params: ["length"],
  message: "Máximo {length} caracteres"
});
