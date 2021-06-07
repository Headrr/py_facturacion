-- pasteleria.factura_usuarios definition

CREATE TABLE `factura_usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `mobile` bigint(20) NOT NULL,
  `address` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


INSERT INTO pasteleria.factura_usuarios (email,password,first_name,last_name,mobile,address) VALUES
	 ('pasteleria@talca.cl','123','Pasteleria','Talca',912345698,'Talca');