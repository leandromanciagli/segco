-- SQL Manager for PostgreSQL 6.1.2.53864
-- ---------------------------------------
-- Host      : localhost
-- Database  : segco
-- Version   : PostgreSQL 13.0, compiled by Visual C++ build 1914, 64-bit

-- El siguiente script carga el esquema inicial del hospital.

-- La siguiente grilla muestra los usuarios cargados en la base de datos una vez finalizada la ejecución de este script:

-- GUARDIA:
--      EMAIL                       USERNAME            PASSWORD
--      jefeguardia@gmail.com	    jefeguardia		    soyJefeGuardia
--      guardiamedico1@gmail.com	guardiamedico1	    guardiamedico1
--      guardiamedico2@gmail.com	guardiamedico2	    guardiamedico2
--      guardiamedico3@gmail.com	guardiamedico3      guardiamedico3

-- PISOCOVID:
--      EMAIL                       USERNAME            PASSWORD
--      jefepisocovid@gmail.com	    jefepisocovid	    soyJefePisoCovid
--      pisocovidmedico1@gmail.com	pisocovidmedico1    pisocovidmedico1
--      pisocovidmedico2@gmail.com	pisocovidmedico2    pisocovidmedico2	
--      pisocovidmedico3@gmail.com	pisocovidmedico3    pisocovidmedico3		

-- UTI:
--      EMAIL                       USERNAME            PASSWORD
--      jefeuti@gmail.com	        jefeuti	            soyJefeUTI
--      utimedico1@gmail.com	    utimedico1		    utimedico1
--      utimedico2@gmail.com	    utimedico2		    utimedico2
--      utimedico3@gmail.com	    utimedico3		    utimedico3

-- HOTEL:
--      EMAIL                       USERNAME            PASSWORD
--      jefehotel@gmail.com	        jefehotel		    soyJefeHotel
--      hotelmedico1@gmail.com	    hotelmedico1		hotelmedico1
--      hotelmedico2@gmail.com	    hotelmedico2		hotelmedico2
--      hotelmedico3@gmail.com	    hotelmedico3		hotelmedico3

-- DOMICILIO:
--      EMAIL                       USERNAME            PASSWORD
--      jefedomicilio@gmail.com	    jefedomicilio	    soyJefeDomicilio
--      domiciliomedico1@gmail.com	domiciliomedico1	domiciliomedico1
--      domiciliomedico2@gmail.com	domiciliomedico2	domiciliomedico2
--      domiciliomedico3@gmail.com	domiciliomedico3	domiciliomedico3


-- Cantidad de salas y camas para cada sistema:

-- GUARDIA: 2 SALAS - 20 CAMAS
-- PISOCOVID: 2 SALAS - 10 CAMAS
-- UTI: 2 SALAS - 10 CAMAS
-- HOTEL: 2 SALAS - 10 CAMAS
-- DOMICILIO: 1 SALA - CAMAS ILIMITADAS


-- 12 pacientes, cada  uno con 1 sola internacion

-- Paciente 1: GUARDIA
-- Paciente 2: GUARDIA
-- Paciente 3: GUARDIA
-- Paciente 4: GUARDIA
-- Paciente 5: GUARDIA
-- Paciente 6: PISOCOVID
-- Paciente 7: PISOCOVID
-- Paciente 8: PISOCOVID
-- Paciente 9: PISOCOVID
-- Paciente 10: PISOCOVID
-- Paciente 11: UTI
-- Paciente 12: UTI

--
-- Data for table public.sistema
--
INSERT INTO sistema (id, nombre, descrip, camas_total, camas_disponibles, camas_ocupadas, sistemas_destino)
VALUES (1, 'GUARDIA', 'Guardia', 20, 15, 5, '[{"id": 2, "descrip": "Piso Covid"}, {"id": 3, "descrip": "UTI"}]');

INSERT INTO sistema (id, nombre, descrip, camas_total, camas_disponibles, camas_ocupadas, sistemas_destino)
VALUES (2, 'PISOCOVID', 'Piso Covid', 10, 5, 5, '[{"id": 3, "descrip": "UTI"}, {"id": 4, "descrip": "Hotel"}, {"id": 5, "descrip": "Domicilio"}]');

INSERT INTO sistema (id, nombre, descrip, camas_total, camas_disponibles, camas_ocupadas, sistemas_destino)
VALUES (3, 'UTI', 'UTI', 10, 8, 2, '[{"id": 2, "descrip": "Piso Covid"}]');

INSERT INTO sistema (id, nombre, descrip, camas_total, camas_disponibles, camas_ocupadas, sistemas_destino)
VALUES (4, 'HOTEL', 'Hotel', 10, 10, 0, '[{"id": 2, "descrip": "Piso Covid"}]');

INSERT INTO sistema (id, nombre, descrip, camas_total, camas_disponibles, camas_ocupadas, sistemas_destino)
VALUES (5, 'DOMICILIO', 'Domicilio', -1, -1, -1, '[{"id": 2, "descrip": "Piso Covid"}]');

--
-- Data for table public.sala
--
INSERT INTO sala (id, sistema_id, nombre)
VALUES (1, 1, 'Sala 1');

INSERT INTO sala (id, sistema_id, nombre)
VALUES (2, 1, 'Sala 2');

INSERT INTO sala (id, sistema_id, nombre)
VALUES (3, 2, 'Sala 1');

INSERT INTO sala (id, sistema_id, nombre)
VALUES (4, 2, 'Sala 2');

INSERT INTO sala (id, sistema_id, nombre)
VALUES (5, 3, 'Sala 1');

INSERT INTO sala (id, sistema_id, nombre)
VALUES (6, 3, 'Sala 2');

INSERT INTO sala (id, sistema_id, nombre)
VALUES (7, 4, 'Sala 1');

INSERT INTO sala (id, sistema_id, nombre)
VALUES (8, 4, 'Sala 2');

INSERT INTO sala (id, sistema_id, nombre)
VALUES (9, 5, 'Sala Domicilio');

--
-- Data for table public.cama
--


-- 20 CAMAS DE GUARDIA

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (1, 1, 1, 'ocupada');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (2, 1, 2, 'ocupada');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (3, 1, 3, 'ocupada');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (4, 1, 4, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (5, 1, 5, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (6, 1, 6, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (7, 1, 7, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (8, 1, 8, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (9, 1, 9, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (10, 1, 10, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (11, 2, 11, 'ocupada');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (12, 2, 12, 'ocupada');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (13, 2, 13, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (14, 2, 14, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (15, 2, 15, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (16, 2, 16, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (17, 2, 17, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (18, 2, 18, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (19, 2, 19, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (20, 2, 20, 'libre');

-- 10 CAMAS DE PISOCOVID

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (21, 3, 21, 'ocupada');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (22, 3, 22, 'ocupada');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (23, 3, 23, 'ocupada');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (24, 3, 24, 'ocupada');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (25, 3, 25, 'ocupada');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (26, 4, 26, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (27, 4, 27, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (28, 4, 28, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (29, 4, 29, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (30, 4, 30, 'libre');

-- 10 CAMAS DE UTI

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (31, 5, 31, 'ocupada');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (32, 5, 32, 'ocupada');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (33, 5, 33, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (34, 5, 34, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (35, 5, 35, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (36, 6, 36, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (37, 6, 37, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (38, 6, 38, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (39, 6, 39, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (40, 6, 40, 'libre');

-- 10 CAMAS DE HOTEL

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (41, 7, 41, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (42, 7, 42, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (43, 7, 43, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (44, 7, 44, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (45, 7, 45, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (46, 8, 46, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (47, 8, 47, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (48, 8, 48, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (49, 8, 49, 'libre');

INSERT INTO cama (id, sala_id, numero, estado)
VALUES (50, 8, 50, 'libre');

ALTER SEQUENCE cama_id_seq RESTART WITH 51;

--
-- Data for table public."user"
--

INSERT INTO "user" (id, sistema_id, email, username, password, roles, created_at, updated_at, first_name, last_name, activo, legajo)
VALUES (1, 1, 'jefeguardia@gmail.com', 'jefeguardia', 'RG6EBuyV0JfSQ4hciO9CduwEYlrxq8x0dyNr/wOjoXp+AZNG641n88ORYNrRo/W8dHp6m2G+8o7QMvjDA2o8UQ==', '["ROLE_JEFE"]', CURRENT_DATE, CURRENT_DATE, 'Romina', 'Lopez', true, '13000/1');

INSERT INTO "user" (id, sistema_id, email, username, password, roles, created_at, updated_at, first_name, last_name, activo, legajo)
VALUES (2, 2, 'jefepisocovid@gmail.com', 'jefepisocovid', 'i9mzZqhspmmRHplsBJBViaFS+Ii8/20lzuXybRnjcg4lLpIcduYn2l/mGFLO96SqN0fQmGubip/TncoVdg07qw==', '["ROLE_JEFE"]', CURRENT_DATE, CURRENT_DATE, 'Laura', 'Jimenez', true, '234234/5');

INSERT INTO "user" (id, sistema_id, email, username, password, roles, created_at, updated_at, first_name, last_name, activo, legajo)
VALUES (3, 3, 'jefeuti@gmail.com', 'jefeuti', 'DHKlo1HwEjR9FZI7r0sroHNMHw9AgKiqigjGGtAx4UDpyk8BZ8h/Ox6c8Gtur4SdlBezwBHop8utT0Un3vR4TA==', '["ROLE_JEFE"]', CURRENT_DATE, CURRENT_DATE, 'Marcelo', 'Rossi', true, '256777/2');

INSERT INTO "user" (id, sistema_id, email, username, password, roles, created_at, updated_at, first_name, last_name, activo, legajo)
VALUES (4, 4, 'jefehotel@gmail.com', 'jefehotel', 'bk39mQcb1ynVq84gLUteKwjvH7iQm9LfxlhfNu+8E2FG/Rpkn5V7DG6EEwSxA4OZNf6+iEfLzFNw3RgSRQzQgQ==', '["ROLE_JEFE"]', CURRENT_DATE, CURRENT_DATE, 'Juan', 'Paez', true, '521862/5');

INSERT INTO "user" (id, sistema_id, email, username, password, roles, created_at, updated_at, first_name, last_name, activo, legajo)
VALUES (5, 5, 'jefedomicilio@gmail.com', 'jefedomicilio', 'iRm4FYibS71jDrKUX20ptkVHx3Q8V5EOACYRSAP6sKsQchAhjSiYRlvWH0GIClSZdVReB5ok+y2f1sY4CzEs8A==', '["ROLE_JEFE"]', CURRENT_DATE, CURRENT_DATE, 'Ricardo', 'Scola', true, '476590/5');

INSERT INTO "user" (id, sistema_id, email, username, password, roles, created_at, updated_at, first_name, last_name, activo, legajo)
VALUES (6, 1, 'guardiamedico1@gmail.com', 'guardiamedico1', 'rjzPaJMIYrOiw9xnhBijzECtwkuUJHS1otO3QBMbqM4+ZYC+lb5Uf5WdGiQhJcjLhKkwUUe3VFYvk5quPA0AtA==', '["ROLE_MEDICO"]', CURRENT_DATE, CURRENT_DATE, 'Graciela', 'Ricardini', true, '475216/0');

INSERT INTO "user" (id, sistema_id, email, username, password, roles, created_at, updated_at, first_name, last_name, activo, legajo)
VALUES (7, 1, 'guardiamedico2@gmail.com', 'guardiamedico2', 'D1wA4lEBkUaFgQNoOPjxQN2LMuZDXwPvNqo4TT1u5tqBHIypdlqNINHSm46WVIlcfLVhr4gBKwCZm3ekiwK4vA==', '["ROLE_MEDICO"]', CURRENT_DATE, CURRENT_DATE, 'Mercedes', 'Testa', true, '985231/5');

INSERT INTO "user" (id, sistema_id, email, username, password, roles, created_at, updated_at, first_name, last_name, activo, legajo)
VALUES (8, 1, 'guardiamedico3@gmail.com', 'guardiamedico3', 'kXbNeJ/Krss7i9dT2bZAKPe1PChteu+Fa0NRV1a/i7GAmQPSIJLcsDb3beyWgxpfkS4CzJpH0eyYn0iI7MIBwg==', '["ROLE_MEDICO"]', CURRENT_DATE, CURRENT_DATE, 'Maximiliano', 'Jir', true, '784523/5');

INSERT INTO "user" (id, sistema_id, email, username, password, roles, created_at, updated_at, first_name, last_name, activo, legajo)
VALUES (9, 2, 'pisocovidmedico1@gmail.com', 'pisocovidmedico1', 'aORfLsm8gA7bxEyoACqtjS6gwRPTM0yW6p1Wp1ZVqP/oZ2fr8OqxPJqVCrU+Wis1FzGFxNZtbxrbKMsh2+9tng==', '["ROLE_MEDICO"]', CURRENT_DATE, CURRENT_DATE, 'Belén', 'Taibo', true, '985163/4');

INSERT INTO "user" (id, sistema_id, email, username, password, roles, created_at, updated_at, first_name, last_name, activo, legajo)
VALUES (10, 2, 'pisocovidmedico2@gmail.com', 'pisocovidmedico2', 'vNqJMlwNjylYLEQCRBXvWWN+VfPh2uIJNPBaEML6bxbiR2K4E/Gb8/XSZHlF8fMZJhbYaRxOfQxALSHPsksF9A==', '["ROLE_MEDICO"]', CURRENT_DATE, CURRENT_DATE, 'Samantha', 'Sanchez', true, '452139/5');

INSERT INTO "user" (id, sistema_id, email, username, password, roles, created_at, updated_at, first_name, last_name, activo, legajo)
VALUES (11, 2, 'pisocovidmedico3@gmail.com', 'pisocovidmedico3', 'Q2Ju6mTI+F6tfh3AhoxWx+M2bhYsGOFfNEc027m+XBpijaczpsdTU8pgMOBtDXuiDDuSqVvoyEMrUw8z93MKSw==', '["ROLE_MEDICO"]', CURRENT_DATE, CURRENT_DATE, 'Griselda', 'Gutierrez', true, '782153/4');

INSERT INTO "user" (id, sistema_id, email, username, password, roles, created_at, updated_at, first_name, last_name, activo, legajo)
VALUES (12, 3, 'utimedico1@gmail.com', 'utimedico1', '81XxEBmutUUABeRBQxPm1XEH8JG5Xv7GW1CgplpfpEL8Sq3loQ8vomyxjvt7wxzzpZiHHPX66jNf5gDODZEzpw==', '["ROLE_MEDICO"]', CURRENT_DATE, CURRENT_DATE, 'Carlos', 'Bozo', true, '786373/5');

INSERT INTO "user" (id, sistema_id, email, username, password, roles, created_at, updated_at, first_name, last_name, activo, legajo)
VALUES (13, 3, 'utimedico2@gmail.com', 'utimedico2', 'ZqIfOheu2Z3ff2K5ijPyXpcos+OBLB7ZtxDtVTyeBCGex1FudW9pk6/cTE7Ku12UU88qTAIUvsbU6gO7B5xRbA==', '["ROLE_MEDICO"]', CURRENT_DATE, CURRENT_DATE, 'Rodrigo', 'Malmaceda', true, '4412589/5');

INSERT INTO "user" (id, sistema_id, email, username, password, roles, created_at, updated_at, first_name, last_name, activo, legajo)
VALUES (14, 3, 'utimedico3@gmail.com', 'utimedico3', 'w3Pvjlb60u0SJdAXR52Hf7ssWOLfsYU7cCMbXGw8JbMNyHUgU/Q1b0qVK1YMe5y7vwgntkXznVAxEgZvYQSIBA==', '["ROLE_MEDICO"]', CURRENT_DATE, CURRENT_DATE, 'Elvira', 'Vásquez', true, '774152/9');

INSERT INTO "user" (id, sistema_id, email, username, password, roles, created_at, updated_at, first_name, last_name, activo, legajo)
VALUES (15, 4, 'hotelmedico1@gmail.com', 'hotelmedico1', 'OQ9YypQr1Y88hb0cjb4yH/YxVDslV+A+hsL4f3+jetduimZ9BP5nffoYPipdP/Zmh9lIE+Xlbxx1qM7JZAej5g==', '["ROLE_MEDICO"]', CURRENT_DATE, CURRENT_DATE, 'Carola', 'Del Greco', true, '452378/6');

INSERT INTO "user" (id, sistema_id, email, username, password, roles, created_at, updated_at, first_name, last_name, activo, legajo)
VALUES (16, 4, 'hotelmedico2@gmail.com', 'hotelmedico2', '+o27WKH1/AfPOmDpPIFWDKuklM3fhXSdtjHgWjtdLzDAjE1oefIzThr4aO9UVAP5bq1N7nv7ZBPqF+0d0G9wJg==', '["ROLE_MEDICO"]', CURRENT_DATE, CURRENT_DATE, 'Pedro', 'Carrosi', true, '459216/6');

INSERT INTO "user" (id, sistema_id, email, username, password, roles, created_at, updated_at, first_name, last_name, activo, legajo)
VALUES (17, 4, 'hotelmedico3@gmail.com', 'hotelmedico3', 'IaMFButhjXZ0tpUy79meps6tYDFWtUg4EjLddsfCqEGpk86GbuLzRD8OmpOns45IZjTsvL+qAHx0i0/3VoJhLw==', '["ROLE_MEDICO"]', CURRENT_DATE, CURRENT_DATE, 'Camila', 'Gómez', true, '743528/6');

INSERT INTO "user" (id, sistema_id, email, username, password, roles, created_at, updated_at, first_name, last_name, activo, legajo)
VALUES (18, 5, 'domiciliomedico1@gmail.com', 'domiciliomedico1', 's95HP92tCySdUnXUcYOiR4WelaqyQDvxgib5EG/jSAy2PYmGIWHKql/VscGP45nic3IKqbFDEdczpHPAZnsELA==', '["ROLE_MEDICO"]', CURRENT_DATE, CURRENT_DATE, 'Emanuel', 'Sarli', true, '125463/8');

INSERT INTO "user" (id, sistema_id, email, username, password, roles, created_at, updated_at, first_name, last_name, activo, legajo)
VALUES (19, 5, 'domiciliomedico2@gmail.com', 'domiciliomedico2', 'xAO4YDDQuwj0i3dNKJYWQAHTKUCs3zTGhU6jL66FIt7axXSAZFLcfX+uftplV56gxFNo/uQNRkGdUGdQ6YkETQ==', '["ROLE_MEDICO"]', CURRENT_DATE, CURRENT_DATE, 'Mario', 'Tabaro', true, '752165/8');

INSERT INTO "user" (id, sistema_id, email, username, password, roles, created_at, updated_at, first_name, last_name, activo, legajo)
VALUES (20, 5, 'domiciliomedico3@gmail.com', 'domiciliomedico3', 'bakN4qMq1GcKk2d7BDKMPqWzI3ji/hEzbvJmcxt93Ekka9udzJNalNkbiaMAAorlOw7DPrIPXaMI4TyxQpRJcw==', '["ROLE_MEDICO"]', CURRENT_DATE, CURRENT_DATE, 'Raúl', 'Fernandez', true, '452191/3');

INSERT INTO "user" (id, sistema_id, email, username, password, roles, created_at, updated_at, first_name, last_name, activo, legajo)
VALUES (21, 1, 'admin@segco.com', 'admin', 'Eti36Ru/pWG6WfoIPiDFUBxUuyvgMA4L8+LLuGbGyqV9ATuT9brCWPchBqX5vFTF+DgntacecW+sSGD+GZts2A==', '["ROLE_ADMIN"]', CURRENT_DATE, CURRENT_DATE, 'Raúl', 'Fernandez', true, null);

--
-- Data for table public.paciente
--
INSERT INTO paciente (id, dni, apellido, nombre, direccion, telefono, email, fecha_nacimiento, obra_social, antecedentes, contacto_nombre, contacto_apellido, contacto_telefono, contacto_parentesco)
VALUES (1, 24844469, 'Gutierrez', 'Laura', '12 1159', '2214123122', 'laura@gmail.com', '1999-10-19', 'IOMA', NULL, NULL, NULL, NULL, NULL);

INSERT INTO paciente (id, dni, apellido, nombre, direccion, telefono, email, fecha_nacimiento, obra_social, antecedentes, contacto_nombre, contacto_apellido, contacto_telefono, contacto_parentesco)
VALUES (2, 39597615, 'Rizzo', 'Leandro', '528', '2214207932', 'leandro@gmail.com', '1960-04-17', 'IOMA', NULL, NULL, NULL, NULL, NULL);

INSERT INTO paciente (id, dni, apellido, nombre, direccion, telefono, email, fecha_nacimiento, obra_social, antecedentes, contacto_nombre, contacto_apellido, contacto_telefono, contacto_parentesco)
VALUES (3, 41140079, 'Rodriguez', 'Camila', '528', '2214207932', 'camila@gmail.com', '1980-05-12', 'IOMA', NULL, NULL, NULL, NULL, NULL);

INSERT INTO paciente (id, dni, apellido, nombre, direccion, telefono, email, fecha_nacimiento, obra_social, antecedentes, contacto_nombre, contacto_apellido, contacto_telefono, contacto_parentesco)
VALUES (4, 13678999, 'Gerardo', 'Roman', '528 n 4241', '2343333', 'gerardo@gmail.com', '1985-06-19', 'OSDE', 'Neumonia', NULL, NULL, NULL, NULL);

INSERT INTO paciente (id, dni, apellido, nombre, direccion, telefono, email, fecha_nacimiento, obra_social, antecedentes, contacto_nombre, contacto_apellido, contacto_telefono, contacto_parentesco)
VALUES (5, 213423432, 'Salve', 'Monica', '43534', '4353453', 'monica@gmail.com', '1963-03-11', 'OSDE', NULL, 'Maria', 'Salve', '22154875523', 'Hermana');

INSERT INTO paciente (id, dni, apellido, nombre, direccion, telefono, email, fecha_nacimiento, obra_social, antecedentes, contacto_nombre, contacto_apellido, contacto_telefono, contacto_parentesco)
VALUES (6, 34534543, 'Gines', 'Maria', '23432', '22154875523', 'maria@gmail.com', '1989-08-15', 'IOMA', NULL, 'Micaela', 'Gines', '2215423985', 'Hija');

INSERT INTO paciente (id, dni, apellido, nombre, direccion, telefono, email, fecha_nacimiento, obra_social, antecedentes, contacto_nombre, contacto_apellido, contacto_telefono, contacto_parentesco)
VALUES (7, 353453, 'Pardo', 'Marcelo', '32534', '22154875523', 'marcelo@gmail.com', '1956-09-15', 'IOMA', NULL, 'joaquin', 'Gonzalez', '22154875523', NULL);

INSERT INTO paciente (id, dni, apellido, nombre, direccion, telefono, email, fecha_nacimiento, obra_social, antecedentes, contacto_nombre, contacto_apellido, contacto_telefono, contacto_parentesco)
VALUES (8, 1233333, 'Gar', 'Ines', '12312', '22154875523', 'ines@gmail.com', '1984-05-09', 'IOMA', NULL, 'Lisandro', 'Gar', '22154875523', 'Hijo');

INSERT INTO paciente (id, dni, apellido, nombre, direccion, telefono, email, fecha_nacimiento, obra_social, antecedentes, contacto_nombre, contacto_apellido, contacto_telefono, contacto_parentesco)
VALUES (9, 234234, 'Chasi', 'Raul', '23423', '22154875523', 'raul@gmail.com', '1974-04-16', 'IOMA', NULL, NULL, NULL, NULL, NULL);

INSERT INTO paciente (id, dni, apellido, nombre, direccion, telefono, email, fecha_nacimiento, obra_social, antecedentes, contacto_nombre, contacto_apellido, contacto_telefono, contacto_parentesco)
VALUES (10, 2342342, 'Medero', 'Jonatan', '34534', '22154875523', 'jon@gmail.com', '1979-12-17', 'IOMA', NULL, NULL, NULL, NULL, NULL);

INSERT INTO paciente (id, dni, apellido, nombre, direccion, telefono, email, fecha_nacimiento, obra_social, antecedentes, contacto_nombre, contacto_apellido, contacto_telefono, contacto_parentesco)
VALUES (11, 234444, 'Raletti', 'Gustavo', '23434', '22154875523', 'gus@gmail.com', '1976-01-17', 'IOMA', 'Colesterol', NULL, NULL, NULL, NULL);

INSERT INTO paciente (id, dni, apellido, nombre, direccion, telefono, email, fecha_nacimiento, obra_social, antecedentes, contacto_nombre, contacto_apellido, contacto_telefono, contacto_parentesco)
VALUES (12, 23423423, 'Roca', 'Martin', '324324', '22154875523', 'martin@gmail.com', '1968-11-16', 'IOMA', 'Diabetes', NULL, NULL, NULL, NULL);

ALTER SEQUENCE paciente_id_seq RESTART WITH 13;

--
-- Data for table public.internacion
--
INSERT INTO internacion (id, paciente_id, sintomas, fecha_inicio_sintomas, fecha_diagnostico, fecha_carga, fecha_egreso, fecha_obito)
VALUES (1, 1, 'Mareos, Vómitos, Dolor de cabeza', '2020-05-30 03:00:00', '2020-05-30 03:00:00', CURRENT_DATE, NULL, NULL);

INSERT INTO internacion (id, paciente_id, sintomas, fecha_inicio_sintomas, fecha_diagnostico, fecha_carga, fecha_egreso, fecha_obito)
VALUES (2, 2, 'Mareos', '2020-02-28 00:00:00', '2020-02-15 00:00:00', CURRENT_DATE, NULL, NULL);

INSERT INTO internacion (id, paciente_id, sintomas, fecha_inicio_sintomas, fecha_diagnostico, fecha_carga, fecha_egreso, fecha_obito)
VALUES (3, 3, 'Dificultad para respirar', '2020-04-28 00:00:00', '2020-04-30 00:00:00', CURRENT_DATE, NULL, NULL);

INSERT INTO internacion (id, paciente_id, sintomas, fecha_inicio_sintomas, fecha_diagnostico, fecha_carga, fecha_egreso, fecha_obito)
VALUES (4, 4, 'Fiebre', '2020-09-28 00:00:00', '2020-09-25 00:00:00', CURRENT_DATE, NULL, NULL);

INSERT INTO internacion (id, paciente_id, sintomas, fecha_inicio_sintomas, fecha_diagnostico, fecha_carga, fecha_egreso, fecha_obito)
VALUES (5, 5, 'Diarrea', '2020-09-28 00:00:00', '2020-09-30 00:00:00', CURRENT_DATE, NULL, NULL);

INSERT INTO internacion (id, paciente_id, sintomas, fecha_inicio_sintomas, fecha_diagnostico, fecha_carga, fecha_egreso, fecha_obito)
VALUES (6, 6, 'Dolor de cabeza', '2020-09-28 00:00:00', '2020-09-30 00:00:00', CURRENT_DATE, NULL, NULL);

INSERT INTO internacion (id, paciente_id, sintomas, fecha_inicio_sintomas, fecha_diagnostico, fecha_carga, fecha_egreso, fecha_obito)
VALUES (7, 7, 'Diarrea', '2020-09-28 00:00:00', '2020-09-30 00:00:00', CURRENT_DATE, NULL, NULL);

INSERT INTO internacion (id, paciente_id, sintomas, fecha_inicio_sintomas, fecha_diagnostico, fecha_carga, fecha_egreso, fecha_obito)
VALUES (8, 8, 'Vómitos', '2020-09-28 00:00:00', '2020-09-30 00:00:00', CURRENT_DATE, NULL, NULL);

INSERT INTO internacion (id, paciente_id, sintomas, fecha_inicio_sintomas, fecha_diagnostico, fecha_carga, fecha_egreso, fecha_obito)
VALUES (9, 9, 'Dolor en las articulaciones', '2020-09-28 00:00:00', '2020-09-30 00:00:00', CURRENT_DATE, NULL, NULL);

INSERT INTO internacion (id, paciente_id, sintomas, fecha_inicio_sintomas, fecha_diagnostico, fecha_carga, fecha_egreso, fecha_obito)
VALUES (10, 10, 'Fiebre', '2020-09-01 00:00:00', '2020-09-03 00:00:00', CURRENT_DATE, NULL, NULL);

INSERT INTO internacion (id, paciente_id, sintomas, fecha_inicio_sintomas, fecha_diagnostico, fecha_carga, fecha_egreso, fecha_obito)
VALUES (11, 11, 'Dificultad para respirar', '2020-09-15 00:00:00', '2020-09-17 00:00:00', CURRENT_DATE, NULL, NULL);

INSERT INTO internacion (id, paciente_id, sintomas, fecha_inicio_sintomas, fecha_diagnostico, fecha_carga, fecha_egreso, fecha_obito)
VALUES (12, 12, 'Dolor de cabeza', '2020-09-28 00:00:00', '2020-09-30 00:00:00', CURRENT_DATE, NULL, NULL);

ALTER SEQUENCE internacion_id_seq RESTART WITH 13;

--
-- Data for table public.internacion_cama
--
INSERT INTO internacion_cama (id, internacion_id, cama_id, fecha_desde, fecha_hasta)
VALUES (1, 1, 1, '2020-11-08 01:42:20', NULL);

INSERT INTO internacion_cama (id, internacion_id, cama_id, fecha_desde, fecha_hasta)
VALUES (2, 2, 2, '2020-11-07 21:49:42', NULL);

INSERT INTO internacion_cama (id, internacion_id, cama_id, fecha_desde, fecha_hasta)
VALUES (3, 3, 3, '2020-11-08 01:42:20', NULL);

INSERT INTO internacion_cama (id, internacion_id, cama_id, fecha_desde, fecha_hasta)
VALUES (4, 4, 11, '2020-11-07 21:49:42', NULL);

INSERT INTO internacion_cama (id, internacion_id, cama_id, fecha_desde, fecha_hasta)
VALUES (5, 5, 12, '2020-11-08 01:42:20', NULL);

INSERT INTO internacion_cama (id, internacion_id, cama_id, fecha_desde, fecha_hasta)
VALUES (6, 6, 21, '2020-11-07 21:49:42', NULL);

INSERT INTO internacion_cama (id, internacion_id, cama_id, fecha_desde, fecha_hasta)
VALUES (7, 7, 22, '2020-11-08 01:42:20', NULL);

INSERT INTO internacion_cama (id, internacion_id, cama_id, fecha_desde, fecha_hasta)
VALUES (8, 8, 23, '2020-11-07 21:49:42', NULL);

INSERT INTO internacion_cama (id, internacion_id, cama_id, fecha_desde, fecha_hasta)
VALUES (9, 9, 24, '2020-11-08 01:42:20', NULL);

INSERT INTO internacion_cama (id, internacion_id, cama_id, fecha_desde, fecha_hasta)
VALUES (10, 10, 25, '2020-11-07 21:49:42', NULL);

INSERT INTO internacion_cama (id, internacion_id, cama_id, fecha_desde, fecha_hasta)
VALUES (11, 11, 31, '2020-11-08 01:42:20', NULL);

INSERT INTO internacion_cama (id, internacion_id, cama_id, fecha_desde, fecha_hasta)
VALUES (12, 12, 32, '2020-11-07 21:49:42', NULL);

ALTER SEQUENCE internacion_cama_id_seq RESTART WITH 13;

INSERT INTO regla (id,evento,expresion,accion) VALUES
	 (1,'NUEVA EVOLUCION','evolucion.getSomnolencia()','aviso.alertar(paciente.getUsers(),"Evaluar pase a UTI",evento)'),
	 (4,'NUEVA EVOLUCION','evolucion.getFrecuenciaRespiratoria() > 30','aviso.alertar(paciente.getUsers(),"Evaluar pase a UTI",evento)'),
	 (2,'NUEVA EVOLUCION','evolucion.getMecanicaVentilatoria() matches "/regular/"  or  evolucion.getMecanicaVentilatoria() matches "/mala/"','aviso.alertar(paciente.getUsers(),"Evaluar pase a UTI",evento)'),
	 (3,'NUEVA EVOLUCION','evolucion.getInternacion().getFechaInicioSintomas().diff(evolucion.getFechaCarga()) .days >= 10','aviso.alertar(paciente.getUsers(),"Evaluar alta",evento)');

ALTER SEQUENCE regla_id_seq RESTART WITH 5;

INSERT INTO user_paciente (id, user_id, paciente_id, fecha_desde, fecha_hasta)
VALUES (1, 1, 1, '2020-11-07 21:49:42', NULL);

INSERT INTO user_paciente (id, user_id, paciente_id, fecha_desde, fecha_hasta)
VALUES (2, 1, 2, '2020-11-07 21:49:42', NULL);

INSERT INTO user_paciente (id, user_id, paciente_id, fecha_desde, fecha_hasta)
VALUES (3, 1, 3, '2020-11-07 21:49:42', NULL);

INSERT INTO user_paciente (id, user_id, paciente_id, fecha_desde, fecha_hasta)
VALUES (4, 1, 4, '2020-11-07 21:49:42', NULL);

INSERT INTO user_paciente (id, user_id, paciente_id, fecha_desde, fecha_hasta)
VALUES (5, 1, 5, '2020-11-07 21:49:42', NULL);

INSERT INTO user_paciente (id, user_id, paciente_id, fecha_desde, fecha_hasta)
VALUES (6, 2, 6, '2020-11-07 21:49:42', NULL);

INSERT INTO user_paciente (id, user_id, paciente_id, fecha_desde, fecha_hasta)
VALUES (7, 2, 7, '2020-11-07 21:49:42', NULL);

INSERT INTO user_paciente (id, user_id, paciente_id, fecha_desde, fecha_hasta)
VALUES (8, 2, 8, '2020-11-07 21:49:42', NULL);

INSERT INTO user_paciente (id, user_id, paciente_id, fecha_desde, fecha_hasta)
VALUES (9, 2, 9, '2020-11-07 21:49:42', NULL);

INSERT INTO user_paciente (id, user_id, paciente_id, fecha_desde, fecha_hasta)
VALUES (10, 2, 10, '2020-11-07 21:49:42', NULL);

INSERT INTO user_paciente (id, user_id, paciente_id, fecha_desde, fecha_hasta)
VALUES (11, 3, 11, '2020-11-07 21:49:42', NULL);

INSERT INTO user_paciente (id, user_id, paciente_id, fecha_desde, fecha_hasta)
VALUES (12, 3, 12, '2020-11-07 21:49:42', NULL);

ALTER SEQUENCE user_paciente_id_seq RESTART WITH 13;