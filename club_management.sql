

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";




CREATE TABLE `activites` (
  `id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `lieu` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `activites` (`id`,`description`,`lieu`) VALUES
(2,'Marathon', 'fouchenaa'),
(3,'Football Match', 'lac 2');




CREATE TABLE `membres` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT 'member'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `membres` (`id`, `nom`, `prenom`, `email`, `telephone`, `role`) VALUES
(2, 'yassine', 'ukgiu', 'yassineyahyaoui40.gmail@com', '24524864', 'president'),
(3, 'hama', 'gharbi', 'yahyaouiyassine@dsfdsf.com', '5555555', 'member'),
(6, 'a', 'a', 'yassineyahyaoui40a@gmail.com', '11111111', 'president');


CREATE TABLE `rep_act` (
  `id` int(11) NOT NULL,
  `act_id` int(11) NOT NULL,
  `membre_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `rep_act` (`id`, `act_id`, `membre_id`) VALUES
(1, 2, 6),
(2, 3, 3);

CREATE TABLE `evenment` (
  `id` int(11) NOT NULL,
  `act_id` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `rep_eve` (
  `id` int(11) NOT NULL,
  `eve_id` int(11) NOT NULL,
  `membre_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



ALTER TABLE `activites`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `evenment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `act_id` (`act_id`);


ALTER TABLE `rep_eve`
  ADD PRIMARY KEY (`id`),
  ADD KEY `membre_id` (`membre_id`),
  ADD KEY `eve_id` (`eve_id`);
  

ALTER TABLE `rep_act`
  ADD PRIMARY KEY (`id`),
  ADD KEY `membre_id` (`membre_id`),
  ADD KEY `act_id` (`act_id`);


ALTER TABLE `membres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);




ALTER TABLE `activites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;


ALTER TABLE `membres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `rep_act`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `evenment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `rep_eve`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;




ALTER TABLE `rep_act`
  ADD CONSTRAINT `activites_ibfk_1` FOREIGN KEY (`membre_id`) REFERENCES `membres` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `rep_act`
  ADD CONSTRAINT `activites_ibfk_2` FOREIGN KEY (`act_id`) REFERENCES `activites` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `evenment`
  ADD CONSTRAINT `activites_ibfk_3` FOREIGN KEY (`act_id`) REFERENCES `activites` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `rep_eve`
  ADD CONSTRAINT `evenment_ibfk_1` FOREIGN KEY (`membre_id`) REFERENCES `membres` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `rep_eve`
  ADD CONSTRAINT `evenment_ibfk_2` FOREIGN KEY (`eve_id`) REFERENCES `evenment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

