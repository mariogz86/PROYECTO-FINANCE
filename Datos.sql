 
INSERT INTO "SYSTEM".ROLES(
	 rol,descripcion,u_estado, fecha_creacion, usuario_creacion,fecha_modifica,usuario_modifica)
VALUES ('Administrador','control total del sistema',1,'2024-06-05',1,NULL,NULL),
('Tecnico','control de ciertas opciones del sistema',1,'2024-06-05',1,NULL,NULL);

INSERT INTO "SYSTEM".usuarios(
	 u_nombre_completo, u_apellido_completo, u_email, usuario, u_clave,id_rol, u_estado,reset_clave,u_bloqueado,fecha_vencimiento,cantidad_intento, fecha_creacion,usuario_creacion,fecha_modifica,usuario_modifica)
VALUES ('Mario Alberto','Gómez Briceño','magbgol@gmail.com','mgomez','$2y$10$HXG0IzckvgLvGrZxKEL4ReL3dQ3prV9Vkin9zsSJ7rDGn25cO.5.K',1,1,0,0,'2025-12-30',NULL,'2024-06-05',1,NUll,NULL);
 


INSERT INTO "SYSTEM".menu(
	id_menupadre, nombre, icono, orden, u_estado, fecha_creacion, usuario_creacion,fecha_modifica,usuario_modifica) VALUES
	(0, 'Seguridad del Sistema', '<i class=\"fas fa-shield-alt\"></i>', 1,1,'2024-06-05',1,NULL,NULL),
	(0, 'Catálogos del Sistema', '<i class=\"far fa-list-alt\"></i>', 2,1,'2024-06-05',1,NULL,NULL);
 
	
INSERT INTO "SYSTEM".opcion(
	id_menu, nombre, descripcion, icono, orden, nombrevista, u_estado, fecha_creacion, usuario_creacion,fecha_modifica,usuario_modifica)
	VALUES  (1, 'Rol', 'para agregar un nuevo rol', '<i class="\fas fa-user-tag\"></i>', 1, 'rol',1,'2024-06-05',1,NULL,NULL),
			(1, 'Menú', 'para agregar un nuevo menú', '<i class=\"fas fa-bars\"></i>', 2, 'menu',1,'2024-06-05',1,NULL,NULL),
			(1, 'Opción de menú', 'asociar una opción al menú', '<i class=\"fas fa-clipboard-list\"></i>', 3, 'opcionmenu',1,'2024-06-05',1,NULL,NULL),
			(1, 'Opción por rol', 'asociar una opción al rol', '<i class="\fas fa-user-cog\"></i>', 4, 'opcionrol',1,'2024-06-05',1,NULL,NULL),
			(1, 'Usuario', 'usuarios del sistema', '<i class=\"fas fa-user-alt\"></i>', 5, 'usuario',1,'2024-06-05',1,NULL,NULL),
			(2, 'Catálogo', 'para agregar Catálogo', '<i class=\"fas fa-list-alt\"></i>', 1, 'catalogo',1,'2024-06-05',1,NULL,NULL),
			(2, 'Valor de catálogo', 'para agregar valor al catálogo', '<i class=\"far fa-check-square\"></i>', 2, 'valcatalogo',1,'2024-06-05',1,NULL,NULL);
			
			
			
INSERT INTO "SYSTEM".rol_menu(
	 id_rol, id_menu, u_estado, fecha_creacion, usuario_creacion,fecha_modifica,usuario_modifica)
	VALUES (1, 1,1,'2024-06-05',1,NULL,NULL),
			(1, 2,1,'2024-06-05',1,NULL,NULL);


INSERT INTO "SYSTEM".rol_opcion(
	 id_rol, id_opcion, u_estado, fecha_creacion, usuario_creacion,fecha_modifica,usuario_modifica)
	VALUES (1,1,1, '2024-06-05',1,NULL,NULL),
			(1,2,1, '2024-06-05',1,NULL,NULL),
			(1,3,1, '2024-06-05',1,NULL,NULL),
			(1,4,1, '2024-06-05',1,NULL,NULL),
			(1,5,1, '2024-06-05',1,NULL,NULL),
			 (1,6,1, '2024-06-05',1,NULL,NULL),
			 (1,7,1, '2024-06-05',1,NULL,NULL);
			