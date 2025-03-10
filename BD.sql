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
/******************************************************* CONFUGARACIONES *************************************************/
	CREATE TABLE "SYSTEM"."company" (
		"id_company" serial PRIMARY KEY NOT NULL, 
		"nombre" VARCHAR(255) NOT NULL, 	
		"direccion" VARCHAR(2000) NOT NULL, 
		"ciudad" VARCHAR(2000) NOT NULL, 	
		"id_valestado" INTEGER NOT NULL,	
		"codigozip" INTEGER NOT NULL,	
		"nombrecompleto" VARCHAR(255) NULL,
		"telefono" VARCHAR(255)  NULL, 
		"email" VARCHAR(255)  NULL, 
		"credito" DOUBLE PRECISION null,
		"NTE" DOUBLE PRECISION null,
		"u_estado" SMALLINT NOT NULL DEFAULT '1',	
		"fecha_creacion" DATE NOT NULL,	
		"usuario_creacion" INTEGER NOT NULL,
		"fecha_modifica" DATE NULL ,	
		"usuario_modifica" INTEGER NULL
	);
	
	
		CREATE TABLE "SYSTEM".cliente (
		"id_cliente" serial PRIMARY KEY NOT NULL, 		
		"id_valestado" INTEGER NOT NULL,
		"full_name" VARCHAR(500) NULL,		
		"address" VARCHAR(2000) NOT NULL, 
		"city" VARCHAR(2000) NOT NULL, 	 
		"codigozip" INTEGER NOT NULL,			
		"phone" VARCHAR(255) NOT  NULL, 
		"phone_movil" VARCHAR(255)  NULL, 
		"email" VARCHAR(255)  NULL, 
		"company_name" VARCHAR(255)  NULL, 
		"contact_info" VARCHAR(255)  NULL, 
		"contact_phone" VARCHAR(255)  NULL, 
		"contact_email" VARCHAR(255)  NULL, 
		"valor_nte" DOUBLE PRECISION null,
		"customer_fee" DOUBLE PRECISION null,		
		"u_estado" SMALLINT NOT NULL DEFAULT '1',	
		"fecha_creacion" DATE NOT NULL,	
		"usuario_creacion" INTEGER NOT NULL,
		"fecha_modifica" DATE NULL ,	
		"usuario_modifica" INTEGER NULL
	);
	
	--CREACION DE FOREING KEY  
	   ALTER TABLE "SYSTEM".cliente
	   ADD CONSTRAINT fk_id_valestado
	   FOREIGN KEY (id_valestado) 
	   REFERENCES "SYSTEM".catalogovalor(id_catalogovalor);
	   
	   --CREACION DE INDICES   
	  create index cliente_idcliente on "SYSTEM".CLIENTE("id_cliente");   
	
	CREATE TABLE "SYSTEM".trabajo (
		"id_trabajo" serial PRIMARY KEY NOT NULL, 
		"id_company" INTEGER NOT NULL,		
		"id_cliente" INTEGER NOT NULL,
		"id_estadotrabajo" INTEGER NOT NULL,
		"id_tecnico" INTEGER NULL,
		"num_referencia" VARCHAR(250) NOT NULL,
		"u_estado" SMALLINT NOT NULL DEFAULT '1',	
		"fecha_creacion" DATE NOT NULL,	
		"usuario_creacion" INTEGER NOT NULL,
		"fecha_modifica" DATE NULL ,	
		"usuario_modifica" INTEGER NULL
	);
	
	--CREACION DE FOREING KEY 
	   
	   ALTER TABLE "SYSTEM".trabajo
	   ADD CONSTRAINT fk_id_cliente
	   FOREIGN KEY (id_cliente) 
	   REFERENCES "SYSTEM".cliente(id_cliente);
	   
	   ALTER TABLE "SYSTEM".trabajo
	   ADD CONSTRAINT fk_id_company
	   FOREIGN KEY (id_company) 
	   REFERENCES "SYSTEM".company(id_company);
	   
	   ALTER TABLE "SYSTEM".trabajo
	   ADD CONSTRAINT fk_id_estadotrabajo
	   FOREIGN KEY (id_estadotrabajo) 
	   REFERENCES "SYSTEM".catalogovalor(id_catalogovalor);
	   
	   --CREACION DE INDICES   
	  create index trabajo_idcliente on "SYSTEM".trabajo("id_cliente");  
	  create index trabajo_id_estadotrabajo on "SYSTEM".trabajo("id_estadotrabajo");
	  create index trabajo_id_company on "SYSTEM".trabajo("id_company");
	  
	  
	  CREATE TABLE "SYSTEM".servicio (
		"id_servicio" serial PRIMARY KEY NOT NULL, 
		"id_trabajo" INTEGER NOT NULL,
		"id_valservice" INTEGER NOT NULL,
		"id_valappliance" INTEGER NOT NULL,
		"id_valbrand" INTEGER NOT NULL,
		"id_valsymptom" INTEGER NOT NULL,		
		"model" VARCHAR(250) NOT NULL,
		"problemdetail" VARCHAR(2500) NOT NULL,	
		"servicefee" DOUBLE PRECISION not null,
		"covered" DOUBLE PRECISION null,		
		"fecha_creacion" DATE NOT NULL,	
		"usuario_creacion" INTEGER NOT NULL,
		"fecha_modifica" DATE NULL ,	
		"usuario_modifica" INTEGER NULL
	);
	
	--CREACION DE FOREING KEY 
	   
	   ALTER TABLE "SYSTEM".servicio
	   ADD CONSTRAINT fk_id_trabajo
	   FOREIGN KEY (id_trabajo) 
	   REFERENCES "SYSTEM".trabajo(id_trabajo); 
	   
	   --CREACION DE INDICES   
	  create index servicio_idtrabajo on "SYSTEM".servicio("id_trabajo");   
	  create index servicio_id_valservice on "SYSTEM".servicio("id_valservice");   
	  create index servicio_id_valappliance on "SYSTEM".servicio("id_valappliance");   
	  create index servicio_id_valbrand on "SYSTEM".servicio("id_valbrand");   
	  create index servicio_id_valsymptom on "SYSTEM".servicio("id_valsymptom");

  CREATE TABLE "SYSTEM".cita (
		"id_cita" serial PRIMARY KEY NOT NULL, 
		"id_trabajo" INTEGER NOT NULL,		
		"fecha" DATE NOT NULL,		
		"horaini" VARCHAR(50) NOT NULL,					
		"minini" VARCHAR(50) NOT NULL,					
		"tiemponi" VARCHAR(50) NOT NULL,							
		"horafin" VARCHAR(50) NOT NULL,					
		"minfin" VARCHAR(50) NOT NULL,					
		"tiempofin" VARCHAR(50) NOT NULL,					
		"nota" VARCHAR(2500) NOT NULL,					
		"fecha_creacion" DATE NOT NULL,	
		"usuario_creacion" INTEGER NOT NULL,
		"fecha_modifica" DATE NULL ,	
		"usuario_modifica" INTEGER NULL
	);	


	--CREACION DE FOREING KEY 
	   
	   ALTER TABLE "SYSTEM".cita
	   ADD CONSTRAINT fk_id_trabajo
	   FOREIGN KEY (id_trabajo) 
	   REFERENCES "SYSTEM".trabajo(id_trabajo); 

 --CREACION DE INDICES   
	  create index cita_idtrabajo on "SYSTEM".cita("id_trabajo"); 	

  CREATE TABLE "SYSTEM".payment (
		"id_payment" serial PRIMARY KEY NOT NULL, 
		"id_trabajo" INTEGER NOT NULL,		
		"id_valpayment" INTEGER NOT NULL,
		"nota" VARCHAR(2500) NULL,		
		"fecha_creacion" DATE NOT NULL,	
		"usuario_creacion" INTEGER NOT NULL,
		"fecha_modifica" DATE NULL ,	
		"usuario_modifica" INTEGER NULL
	);	


	--CREACION DE FOREING KEY 
	   
	   ALTER TABLE "SYSTEM".payment
	   ADD CONSTRAINT fk_id_trabajo
	   FOREIGN KEY (id_trabajo) 
	   REFERENCES "SYSTEM".trabajo(id_trabajo); 
	   
	   ALTER TABLE "SYSTEM".payment
	   ADD CONSTRAINT fk_id_valpayment
	   FOREIGN KEY (id_valpayment) 
	   REFERENCES "SYSTEM".catalogovalor(id_catalogovalor);

 --CREACION DE INDICES   
	  create index payment_idtrabajo on "SYSTEM".payment("id_trabajo"); 	   	  
	  create index payment_idvalpayment on "SYSTEM".payment("id_valpayment"); 
	  
	  
	   CREATE TABLE "SYSTEM".movimientotrabajo (
		"id_movimiento" serial PRIMARY KEY NOT NULL, 
		"id_trabajo" INTEGER NOT NULL,	
		"id_estadotrabajo" INTEGER NOT NULL,		
		"nota" VARCHAR(3000) NULL,		
		"fecha_creacion" DATE NOT NULL,	
		"usuario_creacion" INTEGER NOT NULL		
	);	
	
		--CREACION DE FOREING KEY 
	   
	   ALTER TABLE "SYSTEM".movimientotrabajo
	   ADD CONSTRAINT fk_id_trabajo
	   FOREIGN KEY (id_trabajo) 
	   REFERENCES "SYSTEM".trabajo(id_trabajo); 
	   
	   ALTER TABLE "SYSTEM".movimientotrabajo
	   ADD CONSTRAINT fk_id_estadotrabajo
	   FOREIGN KEY (id_estadotrabajo) 
	   REFERENCES "SYSTEM".catalogovalor(id_catalogovalor);
	   
	    --CREACION DE INDICES   
	  create index mov_idtrabajo on "SYSTEM".movimientotrabajo("id_trabajo"); 	   	  
	  create index mov_estadotrabajo on "SYSTEM".movimientotrabajo("id_estadotrabajo"); 
	  
	  
	  
	  
	  	  CREATE TABLE "SYSTEM".diagnostico (
		"id_diagnostico" serial PRIMARY KEY NOT NULL, 
		"id_servicio" INTEGER NOT NULL, 		
		"serial" VARCHAR(250) NOT NULL, 
		"nota" VARCHAR(2500) NULL,		
		"laborfee" DOUBLE PRECISION not null,
		"fecha_creacion" DATE NOT NULL,	
		"usuario_creacion" INTEGER NOT NULL,
		"fecha_modifica" DATE NULL ,	
		"usuario_modifica" INTEGER NULL
	);
	  
	   --CREACION DE FOREING KEY 
	   
	   ALTER TABLE "SYSTEM".diagnostico
	   ADD CONSTRAINT fk_id_servicio
	   FOREIGN KEY (id_servicio) 
	   REFERENCES "SYSTEM".servicio(id_servicio); 
	   
	     --CREACION DE INDICES   
	  create index diag_id_servicio on "SYSTEM".servicio("id_servicio"); 

	  	  CREATE TABLE "SYSTEM".parte (
		"id_parte" serial PRIMARY KEY NOT NULL, 
		"id_servicio" INTEGER NOT NULL, 		
		"id_valorparte" INTEGER NOT NULL,
		"cantidad" INTEGER NOT NULL, 		 		
		"serial" VARCHAR(250)  NULL, 		
		"costo" DOUBLE PRECISION not null,
		"fecha_creacion" DATE NOT NULL,	
		"usuario_creacion" INTEGER NOT NULL		
	);	  
	
		--CREACION DE FOREING KEY 
	   
	   ALTER TABLE "SYSTEM".parte
	   ADD CONSTRAINT fk_id_servicio
	   FOREIGN KEY (id_servicio) 
	   REFERENCES "SYSTEM".servicio(id_servicio); 
	   
	   ALTER TABLE "SYSTEM".parte
	   ADD CONSTRAINT fk_id_valorparte
	   FOREIGN KEY (id_valorparte) 
	   REFERENCES "SYSTEM".catalogovalor(id_catalogovalor);
	   
	    --CREACION DE INDICES   
	  create index parte_idservicio on "SYSTEM".parte("id_servicio"); 	   	  
	  create index parte_valorparte on "SYSTEM".parte("id_valorparte"); 
	  
	 