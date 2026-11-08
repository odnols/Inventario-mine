-- Faça backup da tabela item_receita antes de executar esta migração.
-- Valores que não sejam 0, 1, 2 ou 3 são preservados para inspeção.
ALTER TABLE item_receita MODIFY tipo_craft VARCHAR(20) NOT NULL;

UPDATE item_receita
SET tipo_craft = CASE tipo_craft
    WHEN '0' THEN 'bancada'
    WHEN '1' THEN 'ferraria'
    WHEN '2' THEN 'fornalha'
    WHEN '3' THEN 'pocao'
    ELSE tipo_craft
END
WHERE tipo_craft IN ('0', '1', '2', '3');
