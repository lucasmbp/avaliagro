<?php
 $LIST_USUARIOS = "SELECT 
					u.id as id, 
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
 
 $LIST_AREAS = "SELECT 
                   a.id as id, 
                   a.area as area, 
                   a.cliente as cliente_id,
                   cl.nome as cliente_nome
                   FROM area a
                   join cliente cl on cl.id = a.cliente";
 
 $LIST_PERFIS = "SELECT p.id, p.perfil FROM perfil p";

 $LIST_CLIENTES = "SELECT 
                    cl.id as id, 
                    cl.cnpj as cnpj, 
                    cl.nome as nome, 
                    cl.responsavel as responsavel 
                    FROM cliente cl";

 
?>