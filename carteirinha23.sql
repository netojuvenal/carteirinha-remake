-- --------------------------------------------------------
-- Banco de dados: carteirinha23 (Versão Refatorada)
-- Compatível com o código PHP existente
-- --------------------------------------------------------

DROP DATABASE IF EXISTS `carteirinha23`;
CREATE DATABASE `carteirinha23` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `carteirinha23`;

-- --------------------------------------------------------
-- Estrutura da tabela `tags_cardapio` (ADICIONADA - necessária para o código)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tags_cardapio` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `tipo` varchar(255) DEFAULT NULL,
  `gluten` tinyint(1) DEFAULT 0,
  `lactose` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estrutura da tabela `cardapio` (REFATORADA)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardapio` (
  `id` int NOT NULL AUTO_INCREMENT,
  `data_refeicao` date NOT NULL,
  `data_hora_cardapio` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dia` varchar(255) NOT NULL,
  `proteina` varchar(255) NOT NULL,  -- Alterado de 'acompanhamento' para 'proteina'
  `principal` varchar(255) NOT NULL,
  `sobremesa` varchar(255) NOT NULL,
  `ind_excluido` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estrutura da tabela `status_ref`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `status_ref` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estrutura da tabela `status_feedback`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `status_feedback` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estrutura da tabela `status_notification`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `status_notification` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estrutura da tabela `status_msg`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `status_msg` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estrutura da tabela `usuario`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `matricula` varchar(255) NOT NULL,
  `senha` varchar(60) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `telefone` varchar(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `matricula` (`matricula`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estrutura da tabela `justificativa`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `justificativa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estrutura da tabela `horario_padrao`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `horario_padrao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `inicio_vig` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fim_vig` timestamp NULL DEFAULT NULL,
  `horario` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estrutura da tabela `refeicao`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `refeicao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `id_cardapio` int NOT NULL,
  `id_status_ref` int NOT NULL,
  `id_justificativa` int DEFAULT NULL,
  `data_solicitacao` date NOT NULL,
  `hora_solicitacao` time NOT NULL,
  `outra_justificativa` varchar(100) DEFAULT NULL,
  `motivo_cancelamento` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_cardapio` (`id_cardapio`),
  KEY `id_status` (`id_status_ref`),
  KEY `id_justificativa` (`id_justificativa`),
  CONSTRAINT `refeicao_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `refeicao_ibfk_2` FOREIGN KEY (`id_cardapio`) REFERENCES `cardapio` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `refeicao_ibfk_3` FOREIGN KEY (`id_status_ref`) REFERENCES `status_ref` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `refeicao_ibfk_4` FOREIGN KEY (`id_justificativa`) REFERENCES `justificativa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estrutura da tabela `feedback` (REFATORADA)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `id_cardapio` int NOT NULL,
  `id_nota` int NOT NULL,
  `comentario` text,
  `data_feedback` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_usuario_feedback` (`id_usuario`),
  KEY `fk_cardapio_feedback` (`id_cardapio`),
  KEY `fk_nota_feedback` (`id_nota`),
  CONSTRAINT `fk_usuario_feedback` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cardapio_feedback` FOREIGN KEY (`id_cardapio`) REFERENCES `cardapio` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_nota_feedback` FOREIGN KEY (`id_nota`) REFERENCES `status_feedback` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Estrutura da tabela `notificacao`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `notificacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_remetente` int NOT NULL,
  `id_destinatario` int NOT NULL,
  `data` date NOT NULL,
  `hora` time NOT NULL,
  `assunto` text NOT NULL,
  `mensagem` text NOT NULL,
  `lida` tinyint(1) NOT NULL DEFAULT '0',
  `transferencia` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_remetente` (`id_remetente`),
  KEY `fk_destinatario` (`id_destinatario`),
  KEY `fk_transferencia_notification` (`transferencia`),
  CONSTRAINT `fk_destinatario` FOREIGN KEY (`id_destinatario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_remetente` FOREIGN KEY (`id_remetente`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_transferencia_notification` FOREIGN KEY (`transferencia`) REFERENCES `status_notification` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dados iniciais
-- --------------------------------------------------------

-- Inserir dados em `status_ref`
INSERT INTO `status_ref` (`id`, `descricao`) VALUES
(1, 'Agendada'),
(2, 'Cancelada'),
(3, 'Confirmada'),
(4, 'Não compareceu'),
(5, 'transferida');

-- Inserir dados em `status_feedback`
INSERT INTO `status_feedback` (`id`, `descricao`) VALUES
(1, 'Muito Ruim'),
(2, 'Ruim'),
(3, 'Neutro'),
(4, 'Bom'),
(5, 'Muito bom');

-- Inserir dados em `status_notification`
INSERT INTO `status_notification` (`id`, `descricao`) VALUES
(0, 'Sem transferência'),
(1, 'Em processo'),
(2, 'Transferida'),
(3, 'Cancelada'),
(5, 'Cancelada por tempo');

-- Inserir dados em `status_msg`
INSERT INTO `status_msg` (`id`, `descricao`) VALUES
(1, 'Lida'),
(2, 'Não Lida');

-- Inserir dados em `justificativa`
INSERT INTO `justificativa` (`id`, `descricao`) VALUES
(1, 'Aula no contra turno'),
(2, 'Transporte'),
(3, 'Projeto/TCC/Estágio'),
(4, 'Outro'),
(5, 'Refeição recebida por transferência');

-- Inserir dados em `usuario` (com senhas MD5 para '12345')
INSERT INTO `usuario` (`id`, `nome`, `email`, `matricula`, `senha`, `categoria`, `telefone`) VALUES
(1, 'root', 'root@gmail.com', '20201180041', MD5('12345'), 'adm', '00'),
(2, 'vitor', 'vitor@gmail.com', '20201180046', MD5('12345'), 'estudante', '00'),
(3, 'botteste', 'botteste@gmail.com', '20201180011', MD5('12345'), 'estudante', '75982777354'),
(4, 'Murilo Neves Matutino', 'mmatutino617@gmail.com', '2000', MD5('12345'), 'estudante', '71999343064'),
(5, 'João', 'joazinhodograu@gmail.com', '2001', MD5('12345'), 'estudante', '00'),
(6, 'Cristiano Matutino', 'cris524matutino4@gmail.com', '2002', MD5('12345'), 'estudante', '999999'),
(7, 'adm', 'adm@gmail.com', '2003', MD5('12345'), 'adm', '55');

-- Inserir dados em `tags_cardapio` (exemplos básicos)
INSERT INTO `tags_cardapio` (`id`, `nome`, `tipo`, `gluten`, `lactose`) VALUES
(1, 'Carne cozida', 'proteina', 0, 0),
(2, 'Frango grelhado', 'proteina', 0, 0),
(3, 'Peixe assado', 'proteina', 0, 0),
(4, 'Arroz', 'acompanhamento', 0, 0),
(5, 'Feijão', 'acompanhamento', 0, 0),
(6, 'Macarrão', 'principal', 1, 0),
(7, 'Purê de batata', 'acompanhamento', 0, 0),
(8, 'Salada verde', 'acompanhamento', 0, 0),
(9, 'Maçã', 'sobremesa', 0, 0),
(10, 'Laranja', 'sobremesa', 0, 0),
(11, 'Banana', 'sobremesa', 0, 0),
(12, 'Pudim', 'sobremesa', 0, 1),
(13, 'Miojo', 'principal', 1, 0);

-- Inserir dados em `cardapio` (com data atualizada e estrutura correta)
INSERT INTO `cardapio` (`id`, `data_refeicao`, `data_hora_cardapio`, `dia`, `proteina`, `principal`, `sobremesa`, `ind_excluido`) VALUES
(47, DATE_ADD(CURDATE(), INTERVAL 1 DAY), DATE_ADD(CURDATE(), INTERVAL 1 DAY), 'Segunda-feira', 'Arroz e feijão', 'Carne cozida', 'Maçã', 0),
(48, DATE_ADD(CURDATE(), INTERVAL 2 DAY), DATE_ADD(CURDATE(), INTERVAL 2 DAY), 'Terça-feira', 'Alface', 'Frango grelhado', 'Laranja', 0),
(49, DATE_ADD(CURDATE(), INTERVAL 3 DAY), DATE_ADD(CURDATE(), INTERVAL 3 DAY), 'Quarta-feira', 'Salada', 'Peixe assado', 'Banana', 0),
(50, DATE_ADD(CURDATE(), INTERVAL 4 DAY), DATE_ADD(CURDATE(), INTERVAL 4 DAY), 'Quinta-feira', 'Farofa', 'Miojo', 'Pudim', 0),
(51, DATE_ADD(CURDATE(), INTERVAL 5 DAY), DATE_ADD(CURDATE(), INTERVAL 5 DAY), 'Sexta-feira', 'Batata palha', 'Miojo', 'Abacaxi', 0);

-- Inserir dados em `horario_padrao`
INSERT INTO `horario_padrao` (`id`, `inicio_vig`, `fim_vig`, `horario`) VALUES
(1, '2024-07-17 07:10:38', '2024-07-18 07:11:14', '16:10:00'),
(2, '2024-07-18 07:11:14', '2024-07-19 07:14:12', '22:11:00'),
(3, '2024-07-19 07:14:12', '2024-07-19 07:14:33', '04:14:00'),
(4, '2024-07-19 07:14:33', '2024-07-26 12:19:36', '09:00:00'),
(5, '2024-07-26 12:19:36', '2024-09-16 19:26:52', '10:19:00'),
(6, '2025-04-15 01:12:06', '2025-04-14 03:00:00', '21:26:00'),
(7, '2025-04-14 03:00:00', NULL, '23:59:00');

-- Inserir dados em `refeicao` (dados de exemplo atualizados)
INSERT INTO `refeicao` (`id`, `id_usuario`, `id_cardapio`, `id_status_ref`, `id_justificativa`, `data_solicitacao`, `hora_solicitacao`, `outra_justificativa`, `motivo_cancelamento`) VALUES
(30, 4, 48, 1, 1, CURDATE(), '14:52:25', 'contra-turno', NULL),
(32, 5, 48, 1, 1, CURDATE(), '15:02:20', 'contra-turno', NULL),
(43, 6, 50, 1, 1, CURDATE(), '20:50:10', 'contra-turno', NULL),
(45, 4, 47, 1, 1, CURDATE(), '19:09:58', 'contra-turno', NULL),
(46, 4, 49, 1, 1, CURDATE(), '22:12:18', 'contra-turno', NULL),
(47, 4, 51, 1, 1, CURDATE(), '22:18:24', 'contra-turno', NULL),
(50, 4, 47, 1, 1, CURDATE(), '22:19:47', 'contra-turno', NULL),
(55, 4, 49, 1, 3, CURDATE(), '23:25:23', 'projeto', NULL),
(71, 4, 50, 1, 1, CURDATE(), '18:28:16', 'contra-turno', NULL);

-- Inserir dados em `notificacao` (dados de exemplo)
INSERT INTO `notificacao` (`id`, `id_remetente`, `id_destinatario`, `data`, `hora`, `assunto`, `mensagem`, `lida`, `transferencia`) VALUES
(11, 1, 2, CURDATE(), '17:45:02', 'Testando notificação', 'Teste notificação', 1, 0),
(17, 1, 2, CURDATE(), '06:23:10', 'Testando novas notificações', 'lorem ipsum.', 1, 0),
(18, 6, 4, CURDATE(), '22:03:16', 'testando', 'a mensagem chegou?', 1, 0);

-- --------------------------------------------------------
-- Triggers (atualizados)
-- --------------------------------------------------------
DELIMITER $$

DROP TRIGGER IF EXISTS `trg01_refeicao`$$
CREATE TRIGGER `trg01_refeicao` BEFORE INSERT ON `refeicao` 
FOR EACH ROW 
BEGIN
    SET NEW.id_status_ref = 1; -- Status padrão: Agendada
END$$

DROP TRIGGER IF EXISTS `trg02_refeicao`$$
CREATE TRIGGER `trg02_refeicao` BEFORE INSERT ON `refeicao` 
FOR EACH ROW 
BEGIN
    SET NEW.data_solicitacao = CURDATE();
    SET NEW.hora_solicitacao = CURTIME();
END$$

DELIMITER ;

-- --------------------------------------------------------
-- Views (úteis para o sistema)
-- --------------------------------------------------------

-- View para ver cardápio da semana
CREATE OR REPLACE VIEW vw_cardapio_semana AS
SELECT 
    id,
    data_refeicao,
    data_hora_cardapio,
    dia,
    principal,
    proteina,
    sobremesa,
    ind_excluido
FROM cardapio
WHERE data_refeicao >= CURDATE()
    AND data_refeicao <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)
    AND ind_excluido = 0
ORDER BY data_refeicao;

-- View para refeições agendadas hoje
CREATE OR REPLACE VIEW vw_refeicoes_hoje AS
SELECT 
    r.id,
    u.nome,
    u.matricula,
    c.dia,
    c.principal,
    c.proteina,
    c.sobremesa,
    sr.descricao as status,
    j.descricao as justificativa,
    r.hora_solicitacao
FROM refeicao r
JOIN usuario u ON r.id_usuario = u.id
JOIN cardapio c ON r.id_cardapio = c.id
JOIN status_ref sr ON r.id_status_ref = sr.id
LEFT JOIN justificativa j ON r.id_justificativa = j.id
WHERE r.data_solicitacao = CURDATE()
    AND r.id_status_ref = 1
ORDER BY r.hora_solicitacao;

-- View para notificações não lidas
CREATE OR REPLACE VIEW vw_notificacoes_nao_lidas AS
SELECT 
    n.id,
    u_rem.nome as remetente,
    u_dest.nome as destinatario,
    n.assunto,
    n.mensagem,
    n.data,
    n.hora,
    sn.descricao as tipo_transferencia
FROM notificacao n
JOIN usuario u_rem ON n.id_remetente = u_rem.id
JOIN usuario u_dest ON n.id_destinatario = u_dest.id
LEFT JOIN status_notification sn ON n.transferencia = sn.id
WHERE n.lida = 0
ORDER BY n.data DESC, n.hora DESC;

-- --------------------------------------------------------
-- Índices adicionais para performance
-- --------------------------------------------------------
CREATE INDEX idx_cardapio_data ON cardapio(data_refeicao);
CREATE INDEX idx_cardapio_dia ON cardapio(dia);
CREATE INDEX idx_cardapio_excluido ON cardapio(ind_excluido);

CREATE INDEX idx_refeicao_usuario_data ON refeicao(id_usuario, data_solicitacao);
CREATE INDEX idx_refeicao_status ON refeicao(id_status_ref);
CREATE INDEX idx_refeicao_cardapio ON refeicao(id_cardapio);

CREATE INDEX idx_notificacao_destinatario ON notificacao(id_destinatario);
CREATE INDEX idx_notificacao_lida ON notificacao(lida);
CREATE INDEX idx_notificacao_transferencia ON notificacao(transferencia);

CREATE INDEX idx_usuario_matricula ON usuario(matricula);
CREATE INDEX idx_usuario_categoria ON usuario(categoria);

-- --------------------------------------------------------
-- Procedures úteis
-- --------------------------------------------------------

-- Procedure para agendar refeição
DELIMITER $$
CREATE PROCEDURE sp_agendar_refeicao(
    IN p_id_usuario INT,
    IN p_id_cardapio INT,
    IN p_id_justificativa INT,
    IN p_outra_justificativa VARCHAR(100)
)
BEGIN
    DECLARE v_data_solicitacao DATE;
    DECLARE v_hora_solicitacao TIME;
    
    SET v_data_solicitacao = CURDATE();
    SET v_hora_solicitacao = CURTIME();
    
    INSERT INTO refeicao (
        id_usuario, 
        id_cardapio, 
        id_status_ref, 
        id_justificativa, 
        data_solicitacao, 
        hora_solicitacao, 
        outra_justificativa
    ) VALUES (
        p_id_usuario,
        p_id_cardapio,
        1, -- Agendada
        p_id_justificativa,
        v_data_solicitacao,
        v_hora_solicitacao,
        p_outra_justificativa
    );
    
    SELECT LAST_INSERT_ID() as id_refeicao;
END$$

-- Procedure para cancelar refeição
CREATE PROCEDURE sp_cancelar_refeicao(
    IN p_id_refeicao INT,
    IN p_motivo VARCHAR(100)
)
BEGIN
    UPDATE refeicao 
    SET id_status_ref = 2, -- Cancelada
        motivo_cancelamento = p_motivo
    WHERE id = p_id_refeicao
        AND id_status_ref = 1; -- Apenas se estiver agendada
    
    SELECT ROW_COUNT() as linhas_afetadas;
END$$

DELIMITER ;

-- --------------------------------------------------------
-- Comentários das tabelas
-- --------------------------------------------------------
ALTER TABLE `cardapio` COMMENT = 'Cardápio semanal de refeições';
ALTER TABLE `refeicao` COMMENT = 'Registro de refeições solicitadas';
ALTER TABLE `usuario` COMMENT = 'Usuários do sistema';
ALTER TABLE `notificacao` COMMENT = 'Sistema de notificações';
ALTER TABLE `feedback` COMMENT = 'Avaliações das refeições';
ALTER TABLE `tags_cardapio` COMMENT = 'Tags para classificação dos alimentos';

-- --------------------------------------------------------
-- Verificação final da estrutura
-- --------------------------------------------------------
SELECT 
    TABLE_NAME,
    TABLE_ROWS as 'Registros',
    DATA_LENGTH as 'Tamanho (bytes)',
    CREATE_TIME as 'Criado em'
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'carteirinha23'
ORDER BY TABLE_NAME;

-- Mostrar todas as tabelas criadas
SHOW TABLES;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;