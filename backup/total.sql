PGDMP         %                }            FINANCE    15.10    15.10     �           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                      false            �           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                      false            �           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                      false            �           1262    16398    FINANCE    DATABASE     }   CREATE DATABASE "FINANCE" WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'Spanish_Mexico.1252';
    DROP DATABASE "FINANCE";
                postgres    false            �          0    16501    catalogo 
   TABLE DATA           �   COPY "SYSTEM".catalogo (id_catalogo, nombre, codigo, descripcion, u_estado, fecha_creacion, usuario_creacion, fecha_modifica, usuario_modifica) FROM stdin;
    SYSTEM          postgres    false    228   :       �          0    16511    catalogovalor 
   TABLE DATA           �   COPY "SYSTEM".catalogovalor (id_catalogovalor, id_catalogo, nombre, descripcion, u_estado, fecha_creacion, usuario_creacion, fecha_modifica, usuario_modifica) FROM stdin;
    SYSTEM          postgres    false    230   �       �          0    24782    company 
   TABLE DATA           �   COPY "SYSTEM".company (id_company, nombre, direccion, ciudad, id_valestado, codigozip, nombrecompleto, telefono, email, credito, "NTE", u_estado, fecha_creacion, usuario_creacion, fecha_modifica, usuario_modifica) FROM stdin;
    SYSTEM          postgres    false    240   O       �          0    16425    menu 
   TABLE DATA           �   COPY "SYSTEM".menu (id_menu, id_menupadre, nombre, icono, orden, u_estado, fecha_creacion, usuario_creacion, fecha_modifica, usuario_modifica) FROM stdin;
    SYSTEM          postgres    false    220   �       �          0    16435    opcion 
   TABLE DATA           �   COPY "SYSTEM".opcion (id_opcion, id_menu, nombre, descripcion, icono, orden, nombrevista, u_estado, fecha_creacion, usuario_creacion, fecha_modifica, usuario_modifica) FROM stdin;
    SYSTEM          postgres    false    222   z       �          0    16415    roles 
   TABLE DATA           �   COPY "SYSTEM".roles (id_rol, rol, descripcion, u_estado, fecha_creacion, usuario_creacion, fecha_modifica, usuario_modifica) FROM stdin;
    SYSTEM          postgres    false    218   �       �          0    16445    rol_menu 
   TABLE DATA           �   COPY "SYSTEM".rol_menu (rol_menu_id, id_rol, id_menu, u_estado, fecha_creacion, usuario_creacion, fecha_modifica, usuario_modifica) FROM stdin;
    SYSTEM          postgres    false    224   U       �          0    16453 
   rol_opcion 
   TABLE DATA           �   COPY "SYSTEM".rol_opcion (rolopcion_id, id_rol, id_opcion, u_estado, fecha_creacion, usuario_creacion, fecha_modifica, usuario_modifica) FROM stdin;
    SYSTEM          postgres    false    226   �       �          0    16401    usuarios 
   TABLE DATA             COPY "SYSTEM".usuarios (id_usuario, u_nombre_completo, u_apellido_completo, u_email, usuario, u_clave, id_rol, u_estado, reset_clave, u_bloqueado, fecha_vencimiento, cantidad_intento, fecha_creacion, usuario_creacion, fecha_modifica, usuario_modifica) FROM stdin;
    SYSTEM          postgres    false    216          �           0    0    catalogo_id_catalogo_seq    SEQUENCE SET     H   SELECT pg_catalog.setval('"SYSTEM".catalogo_id_catalogo_seq', 5, true);
          SYSTEM          postgres    false    227            �           0    0 "   catalogovalor_id_catalogovalor_seq    SEQUENCE SET     S   SELECT pg_catalog.setval('"SYSTEM".catalogovalor_id_catalogovalor_seq', 17, true);
          SYSTEM          postgres    false    229            �           0    0    company_id_company_seq    SEQUENCE SET     F   SELECT pg_catalog.setval('"SYSTEM".company_id_company_seq', 1, true);
          SYSTEM          postgres    false    239            �           0    0    menu_id_menu_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('"SYSTEM".menu_id_menu_seq', 3, true);
          SYSTEM          postgres    false    219            �           0    0    opcion_id_opcion_seq    SEQUENCE SET     D   SELECT pg_catalog.setval('"SYSTEM".opcion_id_opcion_seq', 9, true);
          SYSTEM          postgres    false    221            �           0    0    rol_menu_rol_menu_id_seq    SEQUENCE SET     H   SELECT pg_catalog.setval('"SYSTEM".rol_menu_rol_menu_id_seq', 5, true);
          SYSTEM          postgres    false    223            �           0    0    rol_opcion_rolopcion_id_seq    SEQUENCE SET     L   SELECT pg_catalog.setval('"SYSTEM".rol_opcion_rolopcion_id_seq', 19, true);
          SYSTEM          postgres    false    225            �           0    0    roles_id_rol_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('"SYSTEM".roles_id_rol_seq', 2, true);
          SYSTEM          postgres    false    217            �           0    0    usuarios_id_usuario_seq    SEQUENCE SET     H   SELECT pg_catalog.setval('"SYSTEM".usuarios_id_usuario_seq', 19, true);
          SYSTEM          postgres    false    215            �   �   x�m�A
�0EדS��4Zp�D趛�%�6�����Ic��@`�����6` �a\Ԉݝ4$ǒ8��3Ydٻ�/�5h��J�J����	L���Ē7l�@&�ܼ�Q�&�:��=�_�+<}�pW�֕�8y?X�r7���"��΁S�/"���n'�� �Qd�      �   N  x�}��O�0���_�#�m0��:&��x����l�K[D�{��"����}���{뚲��p��ڔ��B��j���q��tO�X��z{����ۻE4$�Yt8�(ޠKك�%l��[
���CIas$c�>s������&��:!4��V�vٳ��?�M]!���������?'~�r�c�v$'��� �G����XiӬ�]	������S��)����MO,�QªB�s������/b'� ��X�2���p��R�Y�5 �o7�G��c��	`-nV՞�^���O^��n�='OikTt�xa`��R�˛(�~��      �   w   x�]��	�0 ��Z�����.�%C���#����2C�]&���C��i��y�<\x-��6�$}��gaS/�|�
�\���)k��Ku�6�����XOH�b��=&�      �   �   x�}α�0��}
�~�-b��G`u�iޤҤ���MM̙����B�~��oE��(/���d>�r1 �|p�!�m��V�uF��#��5(�$�4o�Ì!��1����C4�j�84�	3���#:�<3XL�eV_�ּ줔w3B�      �   Q  x��ҽN�0��y
��!M? �t錐��X���Z8�`Ǖ�8<bc͋qN�RZҲ%g���w�l�E	v�J�
]ir�rp܀�2���>�p�}�
`�%-��ޒV� VX��tr����d{d�ei6i&�ۣ��O�'4��p@�mPk��)�r��|�]͹C��E::F2*<(:�B��딙��-��"��3���J�e�Q�F������`�,it9Gpa(�V�؈��=���M�Lu��yJ��9l�K���3�z| ���l4���F���Ș��A�߯G����x
� ��祛����������v�+��|������C���_��$���	5      �   j   x�}�1
�0��9=E/PI����p)�b�&�6���������a^bjZ�J��,Ŷ�i>l�*<�������08��� ��Δb��oe�)W�į$?%��)-�1�?(
      �   B   x�3�4C##]3]S '����@�إ��`)S]#]C��	P��H�1��\*F��� P^&      �   Z   x�mα� ��:�BP0.�	��^.I�A��נ��1��,�Ǹ�d�;��*9UT�i���ȩ�ZN�8�9�Y�E?�-�ud����8�h#�.j      �   �   x�3��M,��Wp�IJ-*��t?�97�J��(39���|���������������\���|�
N�JC7Ӳb�p���r�r�b����������|��w'Ws�0�BNC 4 B##S] 2�pLt̀|�,D�H���Ӑ+F��� ��0�     