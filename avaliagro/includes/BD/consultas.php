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
					
 $LIST_CARGOS = "SELECT id, cargo FROM cargo";
 
 $LIST_AREAS = "SELECT id, area FROM area";
 
 $LIST_PERFIS = "SELECT id, perfil FROM perfil";

 $LIST_CLIENTES = "SELECT id, cnpj, nome, responsavel FROM cliente";
 
?>