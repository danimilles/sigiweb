-- #####################################################################################################
-- #                                                                                                   #
-- #            INSERCIÓN DE DATOS - SIGIWEB - LA CONTRASEÑA DE LOS USUARIOS ES iissi2019              #
-- #                                                                                                   #
-- #####################################################################################################

BEGIN

INSERT INTO Departamentos VALUES (1, 'Departamento de Inglés', null);
INSERT INTO Departamentos VALUES (1, 'Departamento de Matemáticas', null);
INSERT INTO Departamentos VALUES (1, 'Departamento de Lengua y Literatura', null);
INSERT INTO Departamentos VALUES (1, 'Departamento de Educación Física', null);

EXECUTE IMMEDIATE 'ALTER TABLE USUARIOS DISABLE ALL TRIGGERS';
--EXECUTE IMMEDIATE 'ALTER TRIGGER "SIGI"."TR_SEC_USUARIOS" DISABLE';

INSERT INTO USUARIOS (NUM_CARNE, PASS, NOMBRE, APELLIDOS, FECHANACIMIENTO, SEXO, EMAIL, FECHA_INICIO_CARNE, FECHA_VALIDEZ_CARNE) VALUES ('00000P','$2y$10$MbUkmmHS0psxVBmoaYFBhektyS1J9UdroENskQbrJB6/DXLaLYwrW','Administrador', 'SIGI', '08/03/85', 'M', 'admin@mail.com', SYSDATE, '10/10/2090');
UPDATE USUARIOS SET NUM_CARNE = '00000P' WHERE NOMBRE = 'Administrador';
INSERT INTO USUARIOS (NUM_CARNE, PASS, NOMBRE, APELLIDOS, FECHANACIMIENTO, SEXO, EMAIL, FECHA_INICIO_CARNE, FECHA_VALIDEZ_CARNE) VALUES ('99999P','$2y$10$MbUkmmHS0psxVBmoaYFBhektyS1J9UdroENskQbrJB6/DXLaLYwrW','Bibliotecario','SIGI', '08/03/85', 'M', 'bib@mail.com', SYSDATE, '10/10/2090');
UPDATE USUARIOS SET NUM_CARNE = '99999P' WHERE NOMBRE = 'Bibliotecario';

EXECUTE IMMEDIATE 'ALTER TABLE USUARIOS ENABLE ALL TRIGGERS';
--EXECUTE IMMEDIATE 'ALTER TRIGGER "SIGI"."TR_SEC_USUARIOS" ENABLE';



ALTA_ALUMNO('$2y$10$oFYcttUiANp19q/95nWDtecngDhU.xVqTrszpEQhdVXyBA2g24wUi','Gustavo','Gutiérrez','71329879E','30/07/99','M','dignissim.lacus.Aliquam@orci.co.uk','690413258','ESO',1,'A');
ALTA_ALUMNO('$2y$10$oFYcttUiANp19q/95nWDtecngDhU.xVqTrszpEQhdVXyBA2g24wUi','Marta','García','89289228N','30/04/99','F','non@necanteblandit.co.uk','689480010','ESO',2,'B');
ALTA_ALUMNO('$2y$10$oFYcttUiANp19q/95nWDtecngDhU.xVqTrszpEQhdVXyBA2g24wUi','José','Calvo','19510662F','30/08/01','M','Donec.porttitor.tellus@urnaetarcu.org','659301282','ESO',3,'C');
ALTA_ALUMNO('$2y$10$oFYcttUiANp19q/95nWDtecngDhU.xVqTrszpEQhdVXyBA2g24wUi','Marcos','Pérez','41916984K','20/08/00','M','nulla@Mauris.net','694675183','ESO',3,'A');
ALTA_ALUMNO('$2y$10$oFYcttUiANp19q/95nWDtecngDhU.xVqTrszpEQhdVXyBA2g24wUi','Julia','Martínez','98989269J','14/09/99','F','dis.parturient.montes@ametluctus.net','639888702','ESO',2,'B');
ALTA_ALUMNO('$2y$10$oFYcttUiANp19q/95nWDtecngDhU.xVqTrszpEQhdVXyBA2g24wUi','Samuel','Alonso','78256829R','08/02/02','M','Nullam.vitae.diam@pedeacurna.com','664054478','ESO',2,'C');
ALTA_ALUMNO('$2y$10$oFYcttUiANp19q/95nWDtecngDhU.xVqTrszpEQhdVXyBA2g24wUi','Juan José','Cacho','78432654A','02/07/99','M','juanjosecacho@orci.co.uk','690413258','ESO',1,'A');
ALTA_ALUMNO('$2y$10$oFYcttUiANp19q/95nWDtecngDhU.xVqTrszpEQhdVXyBA2g24wUi','José Antonio','Martos','96381013H','14/04/99','M','jamartos@necanteblandit.co.uk','689480010','ESO',2,'B');
ALTA_ALUMNO('$2y$10$oFYcttUiANp19q/95nWDtecngDhU.xVqTrszpEQhdVXyBA2g24wUi','Enrique','Recio','19510889C','28/02/01','M','recio_enrique@urnaetarcu.org','659301282','ESO',3,'C');
ALTA_ALUMNO('$2y$10$oFYcttUiANp19q/95nWDtecngDhU.xVqTrszpEQhdVXyBA2g24wUi','Raquel','González','40175984X','25/01/00','F','raqgonz@Mauris.net','694675183','ESO',3,'A');
ALTA_ALUMNO('$2y$10$oFYcttUiANp19q/95nWDtecngDhU.xVqTrszpEQhdVXyBA2g24wUi','Martina','San José','90019269U','14/09/99','F','saintjosephmartina@ametluctus.net','639888702','ESO',2,'B');
ALTA_ALUMNO('$2y$10$oFYcttUiANp19q/95nWDtecngDhU.xVqTrszpEQhdVXyBA2g24wUi','Arturo','Vázquez','78250139A','08/11/01','M','vazqart@pedeacurna.com','664054478','ESO',2,'C');

alta_profesor('$2y$10$oFYcttUiANp19q/95nWDtecngDhU.xVqTrszpEQhdVXyBA2g24wUi','Lidia','Márquez','46855676U','08/03/85','F','luctus.sit.amet@ipsum.net','638058824','Departamento de Inglés');
alta_profesor('$2y$10$oFYcttUiANp19q/95nWDtecngDhU.xVqTrszpEQhdVXyBA2g24wUi','Antonio','Sevilla','17276859N','20/09/72','M','lorem@orci.ca','651780358','Departamento de Matemáticas');
alta_profesor('$2y$10$oFYcttUiANp19q/95nWDtecngDhU.xVqTrszpEQhdVXyBA2g24wUi','Atenea','Fernández','89206182X','11/11/61','F','euismod.urna@iaculisaliquet.net','686518375','Departamento de Lengua y Literatura');
alta_profesor('$2y$10$oFYcttUiANp19q/95nWDtecngDhU.xVqTrszpEQhdVXyBA2g24wUi','Clara','Sánchez','74613335M','19/06/66','F','Quisque.libero@sitamet.com','680764602','Departamento de Educación Física');
alta_profesor('$2y$10$oFYcttUiANp19q/95nWDtecngDhU.xVqTrszpEQhdVXyBA2g24wUi','Manuel','Pérez','47451443Y','26/01/59','M','Curabitur.ut@nunc.com','608097594','Departamento de Inglés');
alta_profesor('$2y$10$oFYcttUiANp19q/95nWDtecngDhU.xVqTrszpEQhdVXyBA2g24wUi','Lucía','Ostos','47419457H','09/09/84','F','amet@inlobortis.net','691795626','Departamento de Matemáticas');
alta_profesor('$2y$10$oFYcttUiANp19q/95nWDtecngDhU.xVqTrszpEQhdVXyBA2g24wUi','Gonzalo','Barrios','74553335M','19/01/78','M','neighbourhoodsgonz@sitamet.com','680764602','Departamento de Educación Física');
alta_profesor('$2y$10$oFYcttUiANp19q/95nWDtecngDhU.xVqTrszpEQhdVXyBA2g24wUi','Olga','Valencia','47451863A','25/04/69','F','olga_valencia_69@nunc.com','608097594','Departamento de Inglés');
alta_profesor('$2y$10$oFYcttUiANp19q/95nWDtecngDhU.xVqTrszpEQhdVXyBA2g24wUi','Martín','Iglesias','22574876Z','10/12/89','M','iglesiasmartin_89@inlobortis.net','691795626','Departamento de Matemáticas');
alta_profesor('$2y$10$oFYcttUiANp19q/95nWDtecngDhU.xVqTrszpEQhdVXyBA2g24wUi','María José','Corbacho','47499857X','01/06/70','F','corbacheramarijose@inlobortis.net','691795626','Departamento de Lengua y Literatura');

asignar_jefe_departamento('00001P');
asignar_jefe_departamento('00002P');
asignar_jefe_departamento('00003P');
asignar_jefe_departamento('00004P');

INSERT INTO aulas (nombre,numero) VALUES ('Salón de actos',1);
INSERT INTO aulas (nombre,numero) VALUES ('Sala de Usos Múltiples',2);
INSERT INTO aulas (nombre,numero) VALUES ('1º ESO A',3);
INSERT INTO aulas (nombre,numero) VALUES ('1º ESO B',4);
INSERT INTO aulas (nombre,numero) VALUES ('1º ESO C',5);
INSERT INTO aulas (nombre,numero) VALUES ('2º ESO A',6);
INSERT INTO aulas (nombre,numero) VALUES ('2º ESO B',7);
INSERT INTO aulas (nombre,numero) VALUES ('2º ESO C',8);
INSERT INTO aulas (nombre,numero) VALUES ('3º ESO A',9);
INSERT INTO aulas (nombre,numero) VALUES ('3º ESO B',10);
INSERT INTO aulas (nombre,numero) VALUES ('3º ESO C',11);
INSERT INTO aulas (nombre,numero) VALUES ('4º ESO A',12);
INSERT INTO aulas (nombre,numero) VALUES ('4º ESO B',13);
INSERT INTO aulas (nombre,numero) VALUES ('4º ESO C',14);
INSERT INTO aulas (nombre,numero) VALUES ('1º BACHILLERATO A',15);
INSERT INTO aulas (nombre,numero) VALUES ('1º BACHILLERATO B',16);
INSERT INTO aulas (nombre,numero) VALUES ('2º BACHILLERATO A',17);
INSERT INTO aulas (nombre,numero) VALUES ('2º BACHILLERATO B',18);
INSERT INTO aulas (nombre,numero) VALUES ('Aula de música',19);
INSERT INTO aulas (nombre,numero) VALUES ('Aula de informática',20);
INSERT INTO aulas (nombre,numero) VALUES ('Gimnasio',21);
INSERT INTO aulas (nombre,numero) VALUES ('Laboratorio de química',22);
INSERT INTO aulas (nombre,numero) VALUES ('Laboratorio de física',23);
INSERT INTO aulas (nombre,numero) VALUES ('Aula de tecnología',24);

INSERT INTO materiales (descripcion,estado,unidades,oid_d) VALUES ('Balón medicinal','Perfecto',4,2);
INSERT INTO materiales (descripcion,estado,unidades,oid_d) VALUES ('Carro de ordenadores','Perfecto',1,2);
INSERT INTO materiales (descripcion,estado,unidades,oid_d) VALUES ('Pelota de fútbol','Bueno',6,1);
INSERT INTO materiales (descripcion,estado,unidades,oid_d) VALUES ('Matraz aforado de 100 ml','Bueno',4,3);
INSERT INTO materiales (descripcion,estado,unidades,oid_d) VALUES ('Pipetas','Deteriorado',12,4);
INSERT INTO materiales (descripcion,estado,unidades,oid_d) VALUES ('Balón medicinal','Deteriorado',8,1);
INSERT INTO materiales (descripcion,estado,unidades,oid_d) VALUES ('Carro de ordenadores','Malo',1,4);
INSERT INTO materiales (descripcion,estado,unidades,oid_d) VALUES ('Calculadora','Bueno',10,4);

add_libro(2,'262-4703931179','Veinte mil leguas de viaje submarino','Julio Verne','2','Aventura','Perfecto');
add_libro(10,'620-8016218468','Historia de una escalera','Antonio Buero Vallejo','9','Drama','Perfecto');
add_libro(12,'442-0443373350','El árbol de la ciencia','Pío Baroja','6','Novela','Perfecto');
add_libro(13,'444-2712507875','Los girasoles ciegos','Alberto Méndez','1','Novela','Bueno');
add_libro(11,'005-2864124785','Crónica de una muerte anunciada','Gabriel García Márquez','3','Novela','Bueno');
add_libro(10,'031-2782810858','Luces de bohemia','Ramón María del Valle-Inclán','8','Drama','Bueno');
add_libro(1,'908-4234890765','Ana Karenina','León Tolstói','8','Novela','Deteriorado');
add_libro(2,'028-1948407325','Ensayo sobre la ceguera','José Saramago','1','Novela','Deteriorado');
add_libro(1,'149-1583623756','1984','George Orwell','8','Novela','Deteriorado');
add_libro(3,'207-3480148095','Orgullo y prejuicio','Jane Austen','2','Novela','Perfecto');
add_libro(4,'779-1818243068','Grandes esperanzas','Charles Dickens','6','Novela','Perfecto');
add_libro(1,'839-9513469999','Cien años de soledad','Gabriel García Márquez','7','Novela','Perfecto');
add_libro(6,'672-4509642817','El viejo y el mar','Ernest Hemingway','7','Novela','Bueno');
add_libro(4,'806-0533047353','Los miserables','Víctor Hugo','9','Novela','Bueno');
add_libro(4,'190-1833563919','Diccionario lengua española','Larousse','9','Docencia','Bueno');
add_libro(2,'408-5453819380','Diccionario español-inglés','Oxford','4','Docencia','Deteriorado');
add_libro(6,'990-5200696512','Rayuela','Julio Cortázar','8','Novela','Deteriorado');
add_libro(1,'248-4477701300','El Señor de los Anillos','J. R. R. Tolkien','3','Fantasía','Deteriorado');
add_libro(3,'155-7745226858','Madame Bovary','Gustave Flaubert','1','Novela','Perfecto');
add_libro(2,'927-4005541936','Moby Dick','Herman Melville','2','Aventura','Perfecto');
add_libro(2,'051-2975823231','La dama del alba','Alejandro Casona','9','Drama','Perfecto');
add_libro(1,'827-6404173132','El hombre en el castillo','Philip K. Dick','4','Novela','Bueno');
add_libro(1,'059-3821697981','El curioso incidente del perro a medianoche','Mark Haddon','7','Novela','Bueno');
add_libro(6,'984-2333156933','Frankenstein','Mary Shelley','1','Fantasía','Bueno');
add_libro(2,'647-5923320061','El principito','Antoine de Saint-Exupéry','4','Novela','Deteriorado');
add_libro(1,'420-7403315664','El retrato de Dorian Gray','Oscar Wilde','9','Novela','Deteriorado');
add_libro(5,'684-0417146893','Los viajes de Gulliver','Jonathan Swift','5','Fantasía','Deteriorado');
add_libro(3,'687-5082381774','Alicia en el país de las maravillas','Lewis Carroll','3','Fantasía','Perfecto');
add_libro(5,'615-1317368698','Nada','Carmen Lafortet','0','Novela','Perfecto');
add_libro(1,'421-9329259563','Niebla','Miguel de Unamuno','8','Novela','Perfecto');
add_libro(1,'818-4844570861','La vuelta al mundo en ochenta días','Julio Verne','8','Aventura','Bueno');
add_libro(5,'421-4185176123','El resplandor','Stephen King','7','Terror','Bueno');
add_libro(6,'172-7080560398','El nombre de la rosa','Umberto Exo','0','Novela','Bueno');
add_libro(6,'954-3164827426','La guerra de los mundos','H. G. Wells','6','Novela','Deteriorado');
add_libro(3,'972-0943860145','Ulises','James Joyce','5','Novela','Deteriorado');
add_libro(5,'791-6361320719','El halcón maltés','Dashiell Hammett','1','Novela','Deteriorado');
add_libro(1,'349-5329138834','Atlas de geografía universal','Vox','2','Docencia','Perfecto');
add_libro(4,'955-9891916003','La casa de Bernarda Alba','Federico García-Lorca','1','Drama','Perfecto');

presta_libro('00001A',11);
presta_libro('00003A',2);
presta_libro('00004A',30);
presta_libro('00005A',4);
presta_libro('00003P',22);
presta_libro('00002P',5);
presta_libro('00004P',15);
presta_libro('00004P',8);

reserva_aula('00003P',12,'09/08/19',2);
reserva_aula('00002P',3,'16/06/19',2);
reserva_aula('00005P',3,'28/08/19',6);
reserva_aula('00010P',21,'30/06/19',2);
reserva_aula('00009P',15,'02/08/19',1);
reserva_aula('00009P',2,'27/09/19',4);
reserva_aula('00005P',23,'01/09/19',4);
reserva_aula('00005P',11,'05/10/19',2);
reserva_aula('00010P',19,'23/06/19',2);
reserva_aula('00001P',11,'08/07/19',3);
reserva_aula('00004P',4,'12/06/19',4);
reserva_aula('00003P',8,'14/06/19',2);
reserva_aula('00005P',6,'15/06/19',5);
reserva_aula('00007P',6,'24/06/19',5);

reserva_material('00001P',1,'25/06/19',4);
reserva_material('00001P',1,'13/06/19',1);
reserva_material('00002P',8,'21/10/19',5);
reserva_material('00002P',2,'01/12/19',4);
reserva_material('00003P',2,'05/07/19',6);
reserva_material('00004P',3,'20/10/19',6);
reserva_material('00004P',3,'10/06/19',4);
reserva_material('00005P',4,'25/08/19',4);
reserva_material('00006P',4,'26/06/19',4);
reserva_material('00006P',8,'20/06/19',2);
reserva_material('00007P',5,'11/06/19',5);
reserva_material('00008P',5,'30/11/19',3);
reserva_material('00009P',6,'21/06/19',1);
reserva_material('00009P',6,'30/06/19',5);
reserva_material('00010P',7,'09/06/19',5);
reserva_material('00010P',7,'06/10/19',1);

COMMIT WORK;

END;
/