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
 
 $LIST_AVALIACAO = "SELECT
                    ava.id as id,
                    ava.cliente as cliente_id,
                    ava.avaliado as avaliado_id,
                    cli.nome as cliente_nome,
                    usu.nome as avaliado_nome,
                    are.area as area_nome,
                    are.id as area_id,
                    car.cargo as cargo_nome,
                    car.id as cargo_id
                    FROM avaliacao ava
                    JOIN cliente cli ON cli.id = ava.cliente
                    JOIN usuario usu ON usu.id = ava.avaliado
                    JOIN area as are on are.id = usu.area
                    JOIN cargo car on car.id  = usu.cargo";
 
 $LIST_PERGUNTA = "SELECT 
                    per.id as id, 
                    per.avaliacao as avaliacao_id, 
                    ava.nome as avaliacao_nome,
                    usu.nome as avaliado_nome,
                    car.cargo as cargo_nome,
                    are. area as area_nome,
                    per.pergunta as pergunta, 
                    per.peso as peso 
                    FROM pergunta per
                    join avaliacao ava on ava.id = per.avaliacao
                    join usuario usu on usu.id = ava.avaliado
                    join cargo car on car.id = usu.cargo
                    join area are on are.id = usu.area";

 
?>