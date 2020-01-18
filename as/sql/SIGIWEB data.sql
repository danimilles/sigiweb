-- #####################################################################################################
-- #                                                                                                   #
-- #            INSERCI�N DE DATOS - SIGIWEB - LA CONTRASE�A DE LOS USUARIOS ES sigiir2020             #
-- #                                                                                                   #
-- #####################################################################################################

BEGIN

INSERT INTO Departamentos VALUES (1, 'Departamento de Ingl�s', null);
INSERT INTO Departamentos VALUES (1, 'Departamento de Matem�ticas', null);
INSERT INTO Departamentos VALUES (1, 'Departamento de Lengua y Literatura', null);
INSERT INTO Departamentos VALUES (1, 'Departamento de Educaci�n F�sica', null);

EXECUTE IMMEDIATE 'ALTER TABLE USUARIOS DISABLE ALL TRIGGERS';
--EXECUTE IMMEDIATE 'ALTER TRIGGER "SIGI"."TR_SEC_USUARIOS" DISABLE';

INSERT INTO USUARIOS (NUM_CARNE, PASS, NOMBRE, APELLIDOS, FECHANACIMIENTO, SEXO, EMAIL, FECHA_INICIO_CARNE, FECHA_VALIDEZ_CARNE) VALUES ('00000P','$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Administrador', 'SIGI', '08/03/85', 'M', 'admin@mail.com', SYSDATE, '10/10/2090');
UPDATE USUARIOS SET NUM_CARNE = '00000P' WHERE NOMBRE = 'Administrador';
INSERT INTO USUARIOS (NUM_CARNE, PASS, NOMBRE, APELLIDOS, FECHANACIMIENTO, SEXO, EMAIL, FECHA_INICIO_CARNE, FECHA_VALIDEZ_CARNE) VALUES ('99999P','$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Bibliotecario','SIGI', '08/03/85', 'M', 'bib@mail.com', SYSDATE, '10/10/2090');
UPDATE USUARIOS SET NUM_CARNE = '99999P' WHERE NOMBRE = 'Bibliotecario';

EXECUTE IMMEDIATE 'ALTER TABLE USUARIOS ENABLE ALL TRIGGERS';
--EXECUTE IMMEDIATE 'ALTER TRIGGER "SIGI"."TR_SEC_USUARIOS" ENABLE';



ALTA_ALUMNO('$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Gustavo','Guti�rrez','71329879E','30/07/1999','M','dignissim.lacus.Aliquam@orci.co.uk','690413258','ESO',1,'A');
ALTA_ALUMNO('$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Marta','Garc�a','89289228N','30/04/1999','F','non@necanteblandit.co.uk','689480010','ESO',2,'B');
ALTA_ALUMNO('$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Jos�','Calvo','19510662F','30/08/2001','M','Donec.porttitor.tellus@urnaetarcu.org','659301282','ESO',3,'C');
ALTA_ALUMNO('$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Marcos','P�rez','41916984K','20/08/2000','M','nulla@Mauris.net','694675183','ESO',3,'A');
ALTA_ALUMNO('$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Julia','Mart�nez','98989269J','14/09/1999','F','dis.parturient.montes@ametluctus.net','639888702','ESO',2,'B');
ALTA_ALUMNO('$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Samuel','Alonso','78256829R','08/02/2002','M','Nullam.vitae.diam@pedeacurna.com','664054478','ESO',2,'C');
ALTA_ALUMNO('$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Juan Jos�','Cacho','78432654A','02/07/1999','M','juanjosecacho@orci.co.uk','690413258','ESO',1,'A');
ALTA_ALUMNO('$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Jos� Antonio','Martos','96381013H','14/04/1999','M','jamartos@necanteblandit.co.uk','689480010','ESO',2,'B');
ALTA_ALUMNO('$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Enrique','Recio','19510889C','28/02/2001','M','recio_enrique@urnaetarcu.org','659301282','ESO',3,'C');
ALTA_ALUMNO('$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Raquel','Gonz�lez','40175984X','25/01/2000','F','raqgonz@Mauris.net','694675183','ESO',3,'A');
ALTA_ALUMNO('$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Martina','San Jos�','90019269U','14/09/1999','F','saintjosephmartina@ametluctus.net','639888702','ESO',2,'B');
ALTA_ALUMNO('$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Arturo','V�zquez','78250139A','08/11/2001','M','vazqart@pedeacurna.com','664054478','ESO',2,'C');

alta_profesor('$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Lidia','M�rquez','46855676U','08/03/1985','F','luctus.sit.amet@ipsum.net','638058824','Departamento de Ingl�s');
alta_profesor('$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Antonio','Sevilla','17276859N','20/09/1972','M','lorem@orci.ca','651780358','Departamento de Matem�ticas');
alta_profesor('$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Atenea','Fern�ndez','89206182X','11/11/1961','F','euismod.urna@iaculisaliquet.net','686518375','Departamento de Lengua y Literatura');
alta_profesor('$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Clara','S�nchez','74613335M','19/06/1966','F','Quisque.libero@sitamet.com','680764602','Departamento de Educaci�n F�sica');
alta_profesor('$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Manuel','P�rez','47451443Y','26/01/1959','M','Curabitur.ut@nunc.com','608097594','Departamento de Ingl�s');
alta_profesor('$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Luc�a','Ostos','47419457H','09/09/1984','F','amet@inlobortis.net','691795626','Departamento de Matem�ticas');
alta_profesor('$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Gonzalo','Barrios','74553335M','19/01/1978','M','neighbourhoodsgonz@sitamet.com','680764602','Departamento de Educaci�n F�sica');
alta_profesor('$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Olga','Valencia','47451863A','25/04/1969','F','olga_valencia_69@nunc.com','608097594','Departamento de Ingl�s');
alta_profesor('$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Mart�n','Iglesias','22574876Z','10/12/1989','M','iglesiasmartin_89@inlobortis.net','691795626','Departamento de Matem�ticas');
alta_profesor('$2y$10$5vKk5gQGlnJ.ToHfasK3vegDIqxbD1HEdTxjEqVKC2jxD4peUQ8bO','Mar�a Jos�','Corbacho','47499857X','01/06/1970','F','corbacheramarijose@inlobortis.net','691795626','Departamento de Lengua y Literatura');

asignar_jefe_departamento('00001P');
asignar_jefe_departamento('00002P');
asignar_jefe_departamento('00003P');
asignar_jefe_departamento('00004P');

INSERT INTO aulas (nombre,numero) VALUES ('Sal�n de actos',1);
INSERT INTO aulas (nombre,numero) VALUES ('Sala de Usos M�ltiples',2);
INSERT INTO aulas (nombre,numero) VALUES ('1� ESO A',3);
INSERT INTO aulas (nombre,numero) VALUES ('1� ESO B',4);
INSERT INTO aulas (nombre,numero) VALUES ('1� ESO C',5);
INSERT INTO aulas (nombre,numero) VALUES ('2� ESO A',6);
INSERT INTO aulas (nombre,numero) VALUES ('2� ESO B',7);
INSERT INTO aulas (nombre,numero) VALUES ('2� ESO C',8);
INSERT INTO aulas (nombre,numero) VALUES ('3� ESO A',9);
INSERT INTO aulas (nombre,numero) VALUES ('3� ESO B',10);
INSERT INTO aulas (nombre,numero) VALUES ('3� ESO C',11);
INSERT INTO aulas (nombre,numero) VALUES ('4� ESO A',12);
INSERT INTO aulas (nombre,numero) VALUES ('4� ESO B',13);
INSERT INTO aulas (nombre,numero) VALUES ('4� ESO C',14);
INSERT INTO aulas (nombre,numero) VALUES ('1� BACHILLERATO A',15);
INSERT INTO aulas (nombre,numero) VALUES ('1� BACHILLERATO B',16);
INSERT INTO aulas (nombre,numero) VALUES ('2� BACHILLERATO A',17);
INSERT INTO aulas (nombre,numero) VALUES ('2� BACHILLERATO B',18);
INSERT INTO aulas (nombre,numero) VALUES ('Aula de m�sica',19);
INSERT INTO aulas (nombre,numero) VALUES ('Aula de inform�tica',20);
INSERT INTO aulas (nombre,numero) VALUES ('Gimnasio',21);
INSERT INTO aulas (nombre,numero) VALUES ('Laboratorio de qu�mica',22);
INSERT INTO aulas (nombre,numero) VALUES ('Laboratorio de f�sica',23);
INSERT INTO aulas (nombre,numero) VALUES ('Aula de tecnolog�a',24);

INSERT INTO materiales (descripcion,estado,unidades,oid_d) VALUES ('Bal�n medicinal','Perfecto',4,2);
INSERT INTO materiales (descripcion,estado,unidades,oid_d) VALUES ('Carro de ordenadores','Perfecto',1,2);
INSERT INTO materiales (descripcion,estado,unidades,oid_d) VALUES ('Pelota de f�tbol','Bueno',6,1);
INSERT INTO materiales (descripcion,estado,unidades,oid_d) VALUES ('Matraz aforado de 100 ml','Bueno',4,3);
INSERT INTO materiales (descripcion,estado,unidades,oid_d) VALUES ('Pipetas','Deteriorado',12,4);
INSERT INTO materiales (descripcion,estado,unidades,oid_d) VALUES ('Bal�n medicinal','Deteriorado',8,1);
INSERT INTO materiales (descripcion,estado,unidades,oid_d) VALUES ('Carro de ordenadores','Malo',1,4);
INSERT INTO materiales (descripcion,estado,unidades,oid_d) VALUES ('Calculadora','Bueno',10,4);

add_libro(2,'262-4703931179','Veinte mil leguas de viaje submarino','Julio Verne','2','Aventura','Perfecto');
add_libro(10,'620-8016218468','Historia de una escalera','Antonio Buero Vallejo','9','Drama','Perfecto');
add_libro(12,'442-0443373350','El �rbol de la ciencia','P�o Baroja','6','Novela','Perfecto');
add_libro(13,'444-2712507875','Los girasoles ciegos','Alberto M�ndez','1','Novela','Bueno');
add_libro(11,'005-2864124785','Cr�nica de una muerte anunciada','Gabriel Garc�a M�rquez','3','Novela','Bueno');
add_libro(10,'031-2782810858','Luces de bohemia','Ram�n Mar�a del Valle-Incl�n','8','Drama','Bueno');
add_libro(1,'908-4234890765','Ana Karenina','Le�n Tolst�i','8','Novela','Deteriorado');
add_libro(2,'028-1948407325','Ensayo sobre la ceguera','Jos� Saramago','1','Novela','Deteriorado');
add_libro(1,'149-1583623756','1984','George Orwell','8','Novela','Deteriorado');
add_libro(3,'207-3480148095','Orgullo y prejuicio','Jane Austen','2','Novela','Perfecto');
add_libro(4,'779-1818243068','Grandes esperanzas','Charles Dickens','6','Novela','Perfecto');
add_libro(1,'839-9513469999','Cien a�os de soledad','Gabriel Garc�a M�rquez','7','Novela','Perfecto');
add_libro(6,'672-4509642817','El viejo y el mar','Ernest Hemingway','7','Novela','Bueno');
add_libro(4,'806-0533047353','Los miserables','V�ctor Hugo','9','Novela','Bueno');
add_libro(4,'190-1833563919','Diccionario lengua espa�ola','Larousse','9','Docencia','Bueno');
add_libro(2,'408-5453819380','Diccionario espa�ol-ingl�s','Oxford','4','Docencia','Deteriorado');
add_libro(6,'990-5200696512','Rayuela','Julio Cort�zar','8','Novela','Deteriorado');
add_libro(1,'248-4477701300','El Se�or de los Anillos','J. R. R. Tolkien','3','Fantas�a','Deteriorado');
add_libro(3,'155-7745226858','Madame Bovary','Gustave Flaubert','1','Novela','Perfecto');
add_libro(2,'927-4005541936','Moby Dick','Herman Melville','2','Aventura','Perfecto');
add_libro(2,'051-2975823231','La dama del alba','Alejandro Casona','9','Drama','Perfecto');
add_libro(1,'827-6404173132','El hombre en el castillo','Philip K. Dick','4','Novela','Bueno');
add_libro(1,'059-3821697981','El curioso incidente del perro a medianoche','Mark Haddon','7','Novela','Bueno');
add_libro(6,'984-2333156933','Frankenstein','Mary Shelley','1','Fantas�a','Bueno');
add_libro(2,'647-5923320061','El principito','Antoine de Saint-Exup�ry','4','Novela','Deteriorado');
add_libro(1,'420-7403315664','El retrato de Dorian Gray','Oscar Wilde','9','Novela','Deteriorado');
add_libro(5,'684-0417146893','Los viajes de Gulliver','Jonathan Swift','5','Fantas�a','Deteriorado');
add_libro(3,'687-5082381774','Alicia en el pa�s de las maravillas','Lewis Carroll','3','Fantas�a','Perfecto');
add_libro(5,'615-1317368698','Nada','Carmen Lafortet','0','Novela','Perfecto');
add_libro(1,'421-9329259563','Niebla','Miguel de Unamuno','8','Novela','Perfecto');
add_libro(1,'818-4844570861','La vuelta al mundo en ochenta d�as','Julio Verne','8','Aventura','Bueno');
add_libro(5,'421-4185176123','El resplandor','Stephen King','7','Terror','Bueno');
add_libro(6,'172-7080560398','El nombre de la rosa','Umberto Exo','0','Novela','Bueno');
add_libro(6,'954-3164827426','La guerra de los mundos','H. G. Wells','6','Novela','Deteriorado');
add_libro(3,'972-0943860145','Ulises','James Joyce','5','Novela','Deteriorado');
add_libro(5,'791-6361320719','El halc�n malt�s','Dashiell Hammett','1','Novela','Deteriorado');
add_libro(1,'349-5329138834','Atlas de geograf�a universal','Vox','2','Docencia','Perfecto');
add_libro(4,'955-9891916003','La casa de Bernarda Alba','Federico Garc�a-Lorca','1','Drama','Perfecto');

presta_libro('00001A',11);
presta_libro('00003A',2);
presta_libro('00004A',30);
presta_libro('00005A',4);
presta_libro('00003P',22);
presta_libro('00002P',5);
presta_libro('00004P',15);
presta_libro('00004P',8);

reserva_aula('00003P',12,'09/08/2020',2);
reserva_aula('00002P',3,'16/06/2020',2);
reserva_aula('00005P',3,'28/08/2020',6);
reserva_aula('00010P',21,'30/06/2020',2);
reserva_aula('00009P',15,'02/08/2020', 1);
reserva_aula('00009P',2,'27/09/2020',4);
reserva_aula('00005P',23,'01/09/2020',4);
reserva_aula('00005P',11,'05/10/2020',2);
reserva_aula('00010P',19,'23/06/2020',2);
reserva_aula('00001P',11,'08/07/2020',3);
reserva_aula('00004P',4,'12/06/2020',4);
reserva_aula('00003P',8,'14/06/2020',2);
reserva_aula('00005P',6,'15/06/2020',5);
reserva_aula('00007P',6,'24/06/2020',5);

reserva_material('00001P',1,'25/06/2020',4);
reserva_material('00001P',1,'13/06/2020',1);
reserva_material('00002P',8,'21/10/2020',5);
reserva_material('00002P',2,'01/12/2020',4);
reserva_material('00003P',2,'05/07/2020',6);
reserva_material('00004P',3,'20/10/2020',6);
reserva_material('00004P',3,'10/06/2020',4);
reserva_material('00005P',4,'25/08/2020',4);
reserva_material('00006P',4,'26/06/2020',4);
reserva_material('00006P',8,'20/06/2020',2);
reserva_material('00007P',5,'11/06/2020',5);
reserva_material('00008P',5,'30/11/2020',3);
reserva_material('00009P',6,'21/06/2020',1);
reserva_material('00009P',6,'30/06/2020',5);
reserva_material('00010P',7,'09/06/2020',5);
reserva_material('00010P',7,'06/10/2020',1);

COMMIT WORK;

END;
/