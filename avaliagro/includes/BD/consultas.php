<?php
 $LIST_USUARIOS = "SELECT 
					u.id, 
					u.nome as nome,
					u.login as login, 
					u.email as email, 
					u.senha as senha, 
					ca.cargo as cargo, 
					ca.id as id_cargo, 
					a.area as area, 
					a.id as id_area, 
					c.nome as cliente,
					c.id as id_cliente,
					p.perfil as perfil,
					p.id as id_perfil
					FROM `usuario` u 
					join cargo ca on ca.id = u.cargo 
					join area a on a.id = u.area 
					join perfil p on p.id = u.perfil 
					join cliente c on c.id = u.cliente";
					
 $LIST_CARGOS = "SELECT ca.id, ca.cargo FROM cargo ca";
 
 $LIST_AREAS = "SELECT a.id, a.area FROM area a";
 
 $LIST_PERFIS = "SELECT p.id, p.perfil FROM perfil p";

 $LIST_CLIENTES = "SELECT cl.id, cl.cnpj, cl.nome, cl.responsavel FROM cliente cl";
 
?>