

CREATE TABLE `jogos` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `genero` varchar(100) DEFAULT NULL,
  `plataforma` varchar(50) DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL DEFAULT 0.00,
  `descricao` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `imagem` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
ALTER TABLE `jogos`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `jogos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
COMMIT;

