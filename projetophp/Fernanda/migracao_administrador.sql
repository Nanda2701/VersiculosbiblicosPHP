ALTER TABLE usuario
    ADD COLUMN administrador TINYINT(1) NOT NULL DEFAULT 0;

UPDATE usuario
SET administrador = 1
WHERE email = 'nandamelo032@hotmail.com';
