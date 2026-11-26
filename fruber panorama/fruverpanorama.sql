CREATE DATABASE IF NOT EXISTS sistemasinformacion_domicilio_web;
	USE  sistemasinformacion_domicilio_web;
	
    CREATE TABLE  Rol(
			id_Rol int(30) primary key,
			descripcion varchar(150) not null,
			nombre_Rol varchar(35) not null
	);
	CREATE TABLE Usuario(
		id_usuario int(15) primary key auto_increment,
		nombre_1 varchar(30) not null ,
		apellido_1 varchar(39) not null,
		tipo_de_documento varchar(50) not null , 
		numero_documento  varchar(50) not null ,
        email  varchar(80) not null,
        contrasena varchar(60) not null,
		dirrecion varchar(180),
		telefono varchar(15),
		id_RolFK int(15),
		FOREIGN KEY (id_RolFK) references Rol(id_Rol)
	);


    CREATE TABLE domiciliario (
		id_domiciliario int primary key auto_increment ,
		vehiculo varchar(50) DEFAULT "caminado",
		id_UsuarioFK int(15),
		FOREIGN KEY (id_UsuarioFK)  REFERENCES Usuario(id_usuario)
    );

	CREATE TABLE Pedido (
		id_pedido INT PRIMARY KEY AUTO_INCREMENT,
		fecha_hora DATETIME,
		metodo_pago VARCHAR(30),
		direccion_entrega VARCHAR(180),
        estado ENUM('Pendiente', 'En camino', 'Entregado', 'Cancelado') DEFAULT 'Pendiente',
		total DECIMAL(10,2),
		id_UsuarioFK INT(15), 
        id_domiciliarioFK INT, 
		FOREIGN KEY (id_UsuarioFK) REFERENCES Usuario(id_usuario),
        FOREIGN KEY (id_domiciliarioFK) REFERENCES domiciliario(id_domiciliario)
	);
    
    CREATE TABLE Producto (
    id_producto INT PRIMARY KEY AUTO_INCREMENT,
    nombre_producto VARCHAR(50) NOT NULL,
    precio DECIMAL(10,2) NOT NULL
);
    
   CREATE TABLE Detalle_Pedido (
    id_detalle INT PRIMARY KEY AUTO_INCREMENT,
    id_pedidoFK INT,
    id_productoFK INT,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pedidoFK) REFERENCES Pedido(id_pedido),
    FOREIGN KEY (id_productoFK) REFERENCES Producto(id_producto)
);
    
   

INSERT INTO Rol (id_Rol, descripcion, nombre_Rol) VALUES 
(1, 'Acceso total al sistema', 'Administrador'), 
(2, 'Usuario que realiza pedidos', 'Cliente'),
(3, 'Encargado de entregar pedidos', 'Domiciliario');
    


INSERT INTO Producto (nombre_producto, precio) VALUES 
-- Frutas
('Banano', 3800),
('Limón Mandarino', 2200),
('Naranja Tangelo', 2300),
('Mandarina', 2500),
('Mango Tommy', 2800),
('Lulo', 3200),
('Maracuyá', 2800),
('Tomate de Árbol', 2600),
('Fresa Orgánica', 6500),
('Limón Tahití', 1800),
('Manzana Royal', 4500),
('Naranja Valencia Jugosa', 6500),

-- Verduras
('Zanahoria Baby', 3800),
('Brócoli', 2800),
('Coliflor', 3200),
('Espinaca', 2500),
('Calabacín (Zucchini)', 2000),
('Apio', 1800),
('Pepino cohombro', 1500),
('Cebolla cabezona blanca', 1800),
('Pimentón', 2800),
('Aguacate Hass (x3)', 7500),
('Lechuga batavia', 2200),
('Tomate', 2400),

-- Carnes
('Res', 16500),
('Cerdo', 11000),
('Pollo', 7500),
('Pavo', 14000),
('Cordero', 19500),
('Conejo', 18000),
('Pato', 16000),
('Cabrito', 21000),
('Atún', 6200),
('Búfalo', 22000),
('Codorniz', 13500),
('Chuleta de cerdo', 11500),

-- Granos
('arveja', 3200),
('Lentejas', 2800),
('Garbanzos', 4200),
('Fríjol Negro', 3800),
('Fríjol Cabecita Negra', 3900),
('Fríjol Rojo Radical', 5200),
('Fríjol Blanco', 4500),
('Maíz Pira', 2600),
('Maíz Blanco Trillado', 2200),
('Cebada Perlada', 2400),
('arroz diana', 2350),
('arveja', 3200), -- Nota: Tienes la arveja repetida en tu HTML al final de los granos

-- Aceites
('Aceite de Oliva Extra Virgen', 32000),
('Aceite de girasol', 11500),
('Aceite de coco', 18900),
('Aceite de aguacate', 24500),
('Aceite de ajonjolí', 16800),
('Aceite de macadamia', 28000),
('Aceite de canola', 12500),
('Aceite de maíz', 14200),
('Aceite de soya', 8900),
('Aceite de maní tostado', 19000),
('Aceite de pepita de uva', 26000),
('Aceite de linaza', 17500),

-- Otros (Snacks y Galletas)
('Doritos', 7800),
('Cheese Tris', 5500),
('Choclitos', 6200),
('Galletas Tosh de Ajonjolí', 4200),
('Tostacos', 5800),
('Chicharrón de paquete', 6900),
('Crispetas', 3800),
('Maní Moto', 5200),
('Maicitos', 4500),
('Rosquitas de queso', 3200),
('Mezcla de frutos secos', 11800),
('Galletas Ducales', 5600),
('Galletas Festival', 2800),
('Galletas Oreo', 3500),
('Galletas con chispas de chocolate', 4800),
('Galletas Wafer', 3200),
('Galletas de mantequilla', 8500),
('Galletas Tosh de coco o miel', 4200),
('Galletas Club Social', 1200),
('Galletas Saltinas', 4800),
('Galletas de soda integrales', 5200),
('Tocinetas', 5900),
('Boliqueso', 4500),
('Semillas de girasol saladas', 4200);