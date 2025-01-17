CREATE DATABASE "FINANCE"
    WITH 
    OWNER = postgres
    ENCODING = 'UTF8'
    LC_COLLATE = 'Spanish_Nicaragua.1252'
    LC_CTYPE = 'Spanish_Nicaragua.1252'
    TABLESPACE = pg_default
    CONNECTION LIMIT = -1;
	
CREATE SCHEMA "SYSTEM"
    AUTHORIZATION postgres;
	
/*********************************************************** ESTRUCTURA MODULO SEGURIDAD ***************************************************************************************************************************/
	--Creacion de tabla usuario
CREATE TABLE "SYSTEM"."usuarios" (
	"id_usuario" serial PRIMARY KEY NOT NULL,		
	"u_nombre_completo" VARCHAR(200) NOT NULL,
	"u_apellido_completo" VARCHAR(200) NOT NULL,
	"u_email" VARCHAR(75) NULL DEFAULT NULL,
	"usuario" VARCHAR(15) NULL DEFAULT NULL,
	"u_clave" VARCHAR(75) NOT NULL,
	"id_rol" INTEGER NOT NULL,
	"u_estado" SMALLINT NOT NULL DEFAULT '1',	
	"reset_clave" SMALLINT NULL DEFAULT '0',
	"u_bloqueado" SMALLINT NULL DEFAULT '0',		
	"fecha_vencimiento" DATE NOT NULL,	
	"cantidad_intento" INTEGER NULL,
	"fecha_creacion" DATE NOT NULL,	
	"usuario_creacion" INTEGER NOT NULL,
	"fecha_modifica" DATE NULL ,	
	"usuario_modifica" INTEGER NULL
	);
	
COMMENT ON COLUMN "SYSTEM"."usuarios"."id_usuario" IS 'clave primaria de la tabla';
COMMENT ON COLUMN "SYSTEM"."usuarios"."u_clave" IS 'clave del usuario';
COMMENT ON COLUMN "SYSTEM"."usuarios"."u_nombre_completo" IS 'nombre completo del usuario';
COMMENT ON COLUMN "SYSTEM"."usuarios"."u_apellido_completo" IS 'apellidos del usuario';
COMMENT ON COLUMN "SYSTEM"."usuarios"."u_email" IS 'correo del usuario';
COMMENT ON COLUMN "SYSTEM"."usuarios"."u_estado" IS 'estado del registro activo o inactivo';
COMMENT ON COLUMN "SYSTEM"."usuarios"."usuario" IS 'cuenta para inicio de sesion del usuario';
COMMENT ON COLUMN "SYSTEM"."usuarios"."u_bloqueado" IS 'estado que indicara si la cuenta esta bloqueada';
COMMENT ON COLUMN "SYSTEM"."usuarios"."reset_clave" IS 'estado que indica si el usuario solcito reset de clave';
COMMENT ON COLUMN "SYSTEM"."usuarios"."fecha_vencimiento" IS 'fecha que caduca la clave del usuario';
COMMENT ON COLUMN "SYSTEM"."usuarios"."cantidad_intento" IS 'cantidad de intentos que el usuario realiza para entrar al sistema';
COMMENT ON COLUMN "SYSTEM"."usuarios"."fecha_creacion" IS 'fecha de creacion del registro';
COMMENT ON COLUMN "SYSTEM"."usuarios"."usuario_creacion" IS 'usuario que crea el registro';
COMMENT ON COLUMN "SYSTEM"."usuarios"."fecha_modifica" IS 'fecha en que se modifica del registro';
COMMENT ON COLUMN "SYSTEM"."usuarios"."usuario_modifica" IS 'usuario que modifica  el registro';

CREATE TABLE "SYSTEM"."roles" (
	"id_rol" serial PRIMARY KEY NOT NULL,		
	"rol" VARCHAR(255) NOT NULL, 	
	"descripcion" VARCHAR(2000) NOT NULL, 	
	"u_estado" SMALLINT NOT NULL DEFAULT '1',	
	"fecha_creacion" DATE NOT NULL,	
	"usuario_creacion" INTEGER NOT NULL,
	"fecha_modifica" DATE NULL ,	
	"usuario_modifica" INTEGER NULL
);

COMMENT ON COLUMN "SYSTEM"."roles"."id_rol" IS 'clave primaria de la tabla';
COMMENT ON COLUMN "SYSTEM"."roles"."rol" IS 'cuenta para inicio de sesion del usuario';
COMMENT ON COLUMN "SYSTEM"."roles"."descripcion" IS 'descripcion para el rol';
COMMENT ON COLUMN "SYSTEM"."roles"."u_estado" IS 'estado del registro activo o inactivo';
COMMENT ON COLUMN "SYSTEM"."roles"."fecha_creacion" IS 'fecha de creacion del registro';
COMMENT ON COLUMN "SYSTEM"."roles"."usuario_creacion" IS 'usuario que crea el registro';
COMMENT ON COLUMN "SYSTEM"."roles"."fecha_modifica" IS 'fecha en que se modifica del registro';
COMMENT ON COLUMN "SYSTEM"."roles"."usuario_modifica" IS 'usuario que modifica  el registro';

CREATE TABLE "SYSTEM"."menu" (
	"id_menu" serial PRIMARY KEY NOT NULL,	
	"id_menupadre" INTEGER DEFAULT NULL,	
	"nombre" VARCHAR(255) NOT NULL, 	
	"icono" VARCHAR(255) NOT NULL, 	
	"orden" INTEGER DEFAULT NULL,	
	"u_estado" SMALLINT NOT NULL DEFAULT '1',	
	"fecha_creacion" DATE NOT NULL,	
	"usuario_creacion" INTEGER NOT NULL,
	"fecha_modifica" DATE NULL ,	
	"usuario_modifica" INTEGER NULL
);

COMMENT ON COLUMN "SYSTEM"."menu"."id_menu" IS 'clave primaria de la tabla';
COMMENT ON COLUMN "SYSTEM"."menu"."id_menupadre" IS 'indica si el registro es hijo de un menu indicado como padre';
COMMENT ON COLUMN "SYSTEM"."menu"."nombre" IS 'indica el nombre del menu';
COMMENT ON COLUMN "SYSTEM"."menu"."icono" IS 'icono que tendra el menu';
COMMENT ON COLUMN "SYSTEM"."menu"."orden" IS 'posicion que tendra en el menu';
COMMENT ON COLUMN "SYSTEM"."menu"."u_estado" IS 'estado del registro activo o inactivo';
COMMENT ON COLUMN "SYSTEM"."menu"."fecha_creacion" IS 'fecha de creacion del registro';
COMMENT ON COLUMN "SYSTEM"."menu"."usuario_creacion" IS 'usuario que crea el registro';
COMMENT ON COLUMN "SYSTEM"."menu"."fecha_modifica" IS 'fecha en que se modifica del registro';
COMMENT ON COLUMN "SYSTEM"."menu"."usuario_modifica" IS 'usuario que modifica  el registro';

CREATE TABLE "SYSTEM"."opcion" (
	"id_opcion" serial PRIMARY KEY NOT NULL,	
	"id_menu" INTEGER DEFAULT NULL,	
	"nombre" VARCHAR(255) NOT NULL, 	
	"descripcion" VARCHAR(2000) NOT NULL, 	
	"icono" VARCHAR(255) NOT NULL, 	
	"orden" INTEGER DEFAULT NULL,	
	"nombrevista" VARCHAR(255) NOT NULL, 		
	"u_estado" SMALLINT NOT NULL DEFAULT '1',	
	"fecha_creacion" DATE NOT NULL,	
	"usuario_creacion" INTEGER NOT NULL,
	"fecha_modifica" DATE NULL ,	
	"usuario_modifica" INTEGER NULL
);

COMMENT ON COLUMN "SYSTEM"."opcion"."id_opcion" IS 'clave primaria de la tabla';
COMMENT ON COLUMN "SYSTEM"."opcion"."id_menu" IS 'indica si el registro es hijo de un menu indicado como padre';
COMMENT ON COLUMN "SYSTEM"."opcion"."nombre" IS 'indica el nombre del menu';
COMMENT ON COLUMN "SYSTEM"."opcion"."descripcion" IS 'indica la descripcion del menu';
COMMENT ON COLUMN "SYSTEM"."opcion"."icono" IS 'icono que tendra el menu';
COMMENT ON COLUMN "SYSTEM"."opcion"."orden" IS 'posicion que tendra en el menu';
COMMENT ON COLUMN "SYSTEM"."opcion"."nombrevista" IS 'nombre del archivo fisico del formulario o pantalla';
COMMENT ON COLUMN "SYSTEM"."opcion"."u_estado" IS 'estado del registro activo o inactivo';
COMMENT ON COLUMN "SYSTEM"."opcion"."fecha_creacion" IS 'fecha de creacion del registro';
COMMENT ON COLUMN "SYSTEM"."opcion"."usuario_creacion" IS 'usuario que crea el registro';
COMMENT ON COLUMN "SYSTEM"."opcion"."fecha_modifica" IS 'fecha en que se modifica del registro';
COMMENT ON COLUMN "SYSTEM"."opcion"."usuario_modifica" IS 'usuario que modifica  el registro';
  

CREATE TABLE "SYSTEM"."rol_menu" (
	"rol_menu_id" serial PRIMARY KEY NOT NULL,		
	"id_rol" INTEGER NOT NULL,
	"id_menu" INTEGER NOT NULL,
	"u_estado" SMALLINT NOT NULL DEFAULT '1',	
	"fecha_creacion" DATE NOT NULL,	
	"usuario_creacion" INTEGER NOT NULL,
	"fecha_modifica" DATE NULL ,	
	"usuario_modifica" INTEGER NULL
);

COMMENT ON COLUMN "SYSTEM"."rol_menu"."rol_menu_id" IS 'clave primaria de la tabla';
COMMENT ON COLUMN "SYSTEM"."rol_menu"."id_rol" IS 'indical el rol ';
COMMENT ON COLUMN "SYSTEM"."rol_menu"."id_menu" IS 'indica el menu ';
COMMENT ON COLUMN "SYSTEM"."rol_menu"."u_estado" IS 'estado del registro activo o inactivo';
COMMENT ON COLUMN "SYSTEM"."rol_menu"."fecha_creacion" IS 'fecha de creacion del registro';
COMMENT ON COLUMN "SYSTEM"."rol_menu"."usuario_creacion" IS 'usuario que crea el registro';
COMMENT ON COLUMN "SYSTEM"."rol_menu"."fecha_modifica" IS 'fecha en que se modifica del registro';
COMMENT ON COLUMN "SYSTEM"."rol_menu"."usuario_modifica" IS 'usuario que modifica  el registro';


CREATE TABLE "SYSTEM"."rol_opcion" (
	"rolopcion_id" serial PRIMARY KEY NOT NULL,		
	"id_rol" INTEGER NOT NULL,
	"id_opcion" INTEGER NOT NULL,	
	"u_estado" SMALLINT NOT NULL DEFAULT '1',	
	"fecha_creacion" DATE NOT NULL,	
	"usuario_creacion" INTEGER NOT NULL,
	"fecha_modifica" DATE NULL ,	
	"usuario_modifica" INTEGER NULL
);

COMMENT ON COLUMN "SYSTEM"."rol_opcion"."rolopcion_id" IS 'clave primaria de la tabla';
COMMENT ON COLUMN "SYSTEM"."rol_opcion"."id_rol" IS 'indical el rol ';
COMMENT ON COLUMN "SYSTEM"."rol_opcion"."id_opcion" IS 'indica la opcion  ';
COMMENT ON COLUMN "SYSTEM"."rol_opcion"."u_estado" IS 'estado del registro activo o inactivo';
COMMENT ON COLUMN "SYSTEM"."rol_opcion"."fecha_creacion" IS 'fecha de creacion del registro';
COMMENT ON COLUMN "SYSTEM"."rol_opcion"."usuario_creacion" IS 'usuario que crea el registro';
COMMENT ON COLUMN "SYSTEM"."rol_opcion"."fecha_modifica" IS 'fecha en que se modifica del registro';
COMMENT ON COLUMN "SYSTEM"."rol_opcion"."usuario_modifica" IS 'usuario que modifica  el registro';
--CREACION DE FOREING KEY

ALTER TABLE "SYSTEM".opcion
   ADD CONSTRAINT fk_id_menu
   FOREIGN KEY (id_menu) 
   REFERENCES "SYSTEM".menu(id_menu);

ALTER TABLE "SYSTEM".rol_menu
   ADD CONSTRAINT fk_id_rol
   FOREIGN KEY (id_rol) 
   REFERENCES "SYSTEM".roles(id_rol); 
   
   ALTER TABLE "SYSTEM".rol_menu
   ADD CONSTRAINT fk_id_menu
   FOREIGN KEY (id_menu) 
   REFERENCES "SYSTEM".menu(id_menu); 
   
   ALTER TABLE "SYSTEM".rol_opcion
   ADD CONSTRAINT fk_id_rol
   FOREIGN KEY (id_rol) 
   REFERENCES "SYSTEM".roles(id_rol); 
   
     ALTER TABLE "SYSTEM".rol_opcion
   ADD CONSTRAINT fk_id_opcion
   FOREIGN KEY (id_opcion) 
   REFERENCES "SYSTEM".opcion(id_opcion); 
   
   
      ALTER TABLE "SYSTEM".usuarios
   ADD CONSTRAINT fk_id_rol
   FOREIGN KEY (id_rol) 
   REFERENCES "SYSTEM".roles(id_rol); 
   
  --CREACION DE INDICES 
  create index idmenu on "SYSTEM".menu("id_menu");  
  create index idopcion on "SYSTEM".opcion("id_opcion");  
  create index idopcionmenu on "SYSTEM".opcion("id_menu");
  create index idrolmenu on "SYSTEM".rol_menu("id_menu");
  create index idrol_rol on "SYSTEM".rol_menu("id_rol");
  create index idrolopcion_opcion on "SYSTEM".rol_opcion("id_opcion");
  create index idrolopcion_rol on "SYSTEM".rol_opcion("id_rol");
  create index idrol on "SYSTEM".roles("id_rol");
  create index idusuario on "SYSTEM".usuarios("id_usuario");
  create index idrol_usuario on "SYSTEM".usuarios("id_rol"); 


/*********************************************************** FIN DE ESTRUCTURA MODULO SEGURIDAD ***************************************************************************************************************************/
/*********************************************************** ESTRUCTURA CATALOGOS DEL SISTEMA ***************************************************************************************************************************/
CREATE TABLE "SYSTEM"."catalogo" (
	"id_catalogo" serial PRIMARY KEY NOT NULL,		
	"nombre" VARCHAR(255) NOT NULL, 	
	"codigo" VARCHAR(255) NOT NULL, 	
	"descripcion" VARCHAR(2000) NOT NULL, 	
	"u_estado" SMALLINT NOT NULL DEFAULT '1',	
	"fecha_creacion" DATE NOT NULL,	
	"usuario_creacion" INTEGER NOT NULL,
	"fecha_modifica" DATE NULL ,	
	"usuario_modifica" INTEGER NULL
);

COMMENT ON COLUMN "SYSTEM"."catalogo"."id_catalogo" IS 'clave primaria de la tabla';
COMMENT ON COLUMN "SYSTEM"."catalogo"."nombre" IS 'nombre del catalogo';
COMMENT ON COLUMN "SYSTEM"."catalogo"."codigo" IS 'codigo del catalogo no podra ser editado';
COMMENT ON COLUMN "SYSTEM"."catalogo"."descripcion" IS 'descripcion para el catalogo';
COMMENT ON COLUMN "SYSTEM"."catalogo"."u_estado" IS 'estado del registro activo o inactivo';
COMMENT ON COLUMN "SYSTEM"."catalogo"."fecha_creacion" IS 'fecha de creacion del registro';
COMMENT ON COLUMN "SYSTEM"."catalogo"."usuario_creacion" IS 'usuario que crea el registro';
COMMENT ON COLUMN "SYSTEM"."catalogo"."fecha_modifica" IS 'fecha en que se modifica del registro';
COMMENT ON COLUMN "SYSTEM"."catalogo"."usuario_modifica" IS 'usuario que modifica  el registro';

CREATE TABLE "SYSTEM"."catalogovalor" (
	"id_catalogovalor" serial PRIMARY KEY NOT NULL,	
	"id_catalogo" INTEGER NOT NULL,	
	"nombre" VARCHAR(255) NOT NULL, 	
	"descripcion" VARCHAR(2000) NOT NULL, 	
	"u_estado" SMALLINT NOT NULL DEFAULT '1',	
	"fecha_creacion" DATE NOT NULL,	
	"usuario_creacion" INTEGER NOT NULL,
	"fecha_modifica" DATE NULL ,	
	"usuario_modifica" INTEGER NULL
);

COMMENT ON COLUMN "SYSTEM"."catalogovalor"."id_catalogovalor" IS 'clave primaria de la tabla';
COMMENT ON COLUMN "SYSTEM"."catalogovalor"."id_catalogo" IS 'representa el catalogo al que pertenece el valor';
COMMENT ON COLUMN "SYSTEM"."catalogovalor"."nombre" IS 'nombre del valor';
COMMENT ON COLUMN "SYSTEM"."catalogovalor"."descripcion" IS 'descripcion para el valor';
COMMENT ON COLUMN "SYSTEM"."catalogovalor"."u_estado" IS 'estado del registro activo o inactivo';
COMMENT ON COLUMN "SYSTEM"."catalogovalor"."fecha_creacion" IS 'fecha de creacion del registro';
COMMENT ON COLUMN "SYSTEM"."catalogovalor"."usuario_creacion" IS 'usuario que crea el registro';
COMMENT ON COLUMN "SYSTEM"."catalogovalor"."fecha_modifica" IS 'fecha en que se modifica del registro';
COMMENT ON COLUMN "SYSTEM"."catalogovalor"."usuario_modifica" IS 'usuario que modifica  el registro';

--CREACION DE FOREING KEY

ALTER TABLE "SYSTEM".catalogovalor
   ADD CONSTRAINT fk_id_catalogo
   FOREIGN KEY (id_catalogo) 
   REFERENCES "SYSTEM".catalogo(id_catalogo);
   
   
   
--CREACION DE INDICES 
  create index ind_catalogo on "SYSTEM".catalogo("id_catalogo");  
  create index ind_catalogovalor on "SYSTEM".catalogovalor("id_catalogo"); 


/*********************************************************** FIN DE ESTRUCTURA CATALOGOS DEL SISTEMA ***************************************************************************************************************************/
