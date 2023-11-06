

DELETE FROM check_mnv;
DELETE FROM materiais_no_veiculo;
DELETE FROM material;
DELETE FROM compartimento;
DELETE FROM veiculo;
DELETE FROM setor;

ALTER TABLE check_mnv AUTO_INCREMENT = 1;
ALTER TABLE materiais_no_veiculo AUTO_INCREMENT = 1;
ALTER TABLE material AUTO_INCREMENT = 1;
ALTER TABLE compartimento AUTO_INCREMENT = 1;
ALTER TABLE veiculo AUTO_INCREMENT = 1;
ALTER TABLE setor AUTO_INCREMENT = 1;