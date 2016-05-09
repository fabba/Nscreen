-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Gegenereerd op: 08 mei 2016 om 21:27
-- Serverversie: 10.1.10-MariaDB
-- PHP-versie: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nscreen_members`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `interest_area`
--

CREATE TABLE `interest_area` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `tag` text NOT NULL,
  `interest_value` float NOT NULL,
  `watched` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `interest_area`
--

INSERT INTO `interest_area` (`id`, `member_id`, `tag`, `interest_value`, `watched`) VALUES
(1, 1, 'LAUGHTER', 9.32485, 0),
(2, 1, 'ice cube', 0.941408, 0),
(3, 1, 'APPLAUSE', 7.00248, 0),
(4, 1, 'Hugh Laurie', 0.870465, 0),
(5, 1, 'Sir David Attenborough', 0.746729, 0),
(6, 1, 'Straight Outta Compton', 0.706576, 0),
(7, 1, 'Elle King', 0.65018, 0),
(8, 1, 'Kevin Hart', 0.63818, 0),
(9, 1, 'night manager', 0.614678, 0),
(10, 1, 'APPLAUSE Yeah', 0.604604, 0),
(11, 1, 'little bit', 0.583597, 0),
(12, 1, 'thing', 4.02396, 0),
(13, 1, 'le carre', 0.579375, 0),
(14, 1, 'erm', 0.574963, 0),
(15, 1, 'people', 4.15936, 0),
(16, 1, 'LAUGHTER Erm', 0.565292, 0),
(17, 1, 'movie', 2.78253, 0),
(18, 1, 'APPLAUSE Yay', 0.546357, 0),
(19, 1, 'Natural History Museum', 0.54155, 0),
(20, 1, 'black people', 0.536746, 0),
(21, 1, 'time', 5.8908, 0),
(22, 1, 'dinosaur footprint', 0.53554, 0),
(23, 1, 'cos', 1.11138, 0),
(24, 1, 'worst man', 0.514288, 0),
(25, 1, 'fully fledged movie', 0.507991, 0),
(26, 1, 'olivia', 0.506173, 0),
(27, 1, 'everybody', 0.504737, 0),
(28, 1, 'new Le Carre', 0.502473, 0),
(29, 1, 'export Elle King', 0.500269, 0),
(30, 1, 'double Bafta-winning star', 0.499335, 0),
(31, 1, 'best fake smile', 4.70251, 0),
(32, 1, 'Tracey Ullman', 1.75164, 0),
(33, 1, 'Cold Feet', 1.7206, 0),
(34, 1, 'Ralph Fiennes', 1.6813, 0),
(35, 1, 'Dame Judi Dench', 1.6774, 0),
(36, 1, 'James Nesbitt', 1.66461, 0),
(37, 1, 'James Bay', 1.58272, 0),
(38, 1, 'master builder', 1.55648, 0),
(39, 1, 'British Home Stores', 1.54951, 0),
(40, 1, 'best fake friend', 1.53541, 0),
(41, 1, 'John Hannah', 1.51058, 0),
(42, 1, 'long time', 4.08106, 0),
(43, 1, 'national treasure', 1.46572, 0),
(44, 1, 'Angela Merkel', 8.46385, 0),
(45, 1, 'new Cold Feet', 1.45921, 0),
(46, 1, 'big red chair', 1.93026, 0),
(47, 1, 'Liam Neeson', 1.45552, 0),
(48, 1, 'drama school', 1.44685, 0),
(49, 1, 'Stan Lee', 1.43325, 0),
(50, 1, 'great Ralph Fiennes', 1.41512, 0),
(51, 1, 'Julie Walters', 1.39495, 0),
(52, 1, 'fabulous Tracey Ullman', 1.39448, 0),
(53, 1, 'famous people', 1.38854, 0),
(54, 1, 'original Cold Feet', 1.37919, 0),
(55, 1, 'English Patient', 1.3764, 0),
(56, 1, 'old chap', 1.3746, 0),
(57, 1, 'good story', 1.37309, 0),
(58, 1, 'Catherine Zeta-Jones', 2.61312, 0),
(59, 1, 'Ryan Reynolds', 9.43939, 0),
(60, 1, 'Toby Jones', 2.42653, 0),
(61, 1, 'Laura Mvula', 2.29246, 0),
(62, 1, 'oggy oggy', 2.22313, 0),
(63, 1, 'Oggy oggy oggy', 2.14143, 0),
(64, 1, 'American football', 2.10585, 1),
(65, 1, 'true story', 2.06161, 0),
(66, 1, 'American football players', 1.94351, 0),
(67, 1, 'Muhammad Ali', 1.92148, 0),
(68, 1, 'Zayn Malik', 1.89988, 0),
(69, 1, 'HIGH-PITCHED TONE', 1.89383, 0),
(70, 1, 'way', 1.87036, 0),
(71, 1, 'Captain Mainwaring', 1.86124, 0),
(72, 1, 'good thing', 1.83517, 0),
(73, 1, 'love story', 1.82914, 0),
(74, 1, 'man', 1.82907, 0),
(75, 1, 'biggest movie stars', 1.82806, 0),
(76, 1, 'neural passages', 1.82766, 0),
(77, 1, 'fabulous Catherine Zeta-Jones', 1.81754, 0),
(78, 1, 'Yeah', 1.81681, 0),
(79, 1, 'big love story', 1.81666, 0),
(80, 1, 'extraordinary true story', 1.80463, 0),
(81, 1, 'Home Guard', 1.80087, 0),
(82, 1, 'Johnny Depp', 1.87983, 0),
(83, 1, 'Daniel Radcliffe', 1.32919, 0),
(84, 1, 'Benedict Cumberbatch', 0.812982, 0),
(85, 1, 'Whitey Bulger', 0.742242, 0),
(86, 1, 'Black Mass', 0.709152, 0),
(87, 1, 'APPLAUSE Daniel Radcliffe', 0.61826, 0),
(88, 1, 'Daniel Radcliffe time', 0.617602, 0),
(89, 1, 'Radcliffe time traveller', 0.615168, 0),
(90, 1, 'James McAvoy', 0.596898, 0),
(91, 1, 'teddy bear', 0.574744, 0),
(92, 1, 'Victor Frankenstein', 0.552778, 0),
(93, 1, 'Sir Johnny Depp', 0.500156, 0),
(94, 1, 'red chair', 0.48427, 0),
(95, 1, 'Nazi skinheads', 0.443772, 0),
(96, 1, 'night', 0.43875, 0),
(97, 1, 'otter', 0.436074, 0),
(98, 1, 'welcome Benedict Cumberbatch', 0.43013, 0),
(99, 1, 'mobster Whitey Bulger', 0.421458, 0),
(100, 1, 'movie Victor Frankenstein', 0.414372, 0),
(101, 1, 'quality family time', 0.410988, 0),
(102, 1, 'CHEERING', 0.407368, 0),
(103, 1, 'slightly aggressive otter', 0.407002, 0),
(104, 1, 'nice thing', 0.405492, 0),
(105, 1, 'Victor Frankenstein stars', 0.404854, 0),
(106, 1, 'Little Mix', 0.763108, 0),
(107, 1, 'Rebel Wilson', 0.672649, 0),
(108, 1, 'dance floor', 0.646978, 0),
(109, 1, 'Peanut butter jelly', 0.609885, 0),
(110, 1, 'butter jelly time', 0.609774, 0),
(111, 1, 'Yes. Yeah', 0.583951, 0),
(112, 1, 'Ant', 0.582341, 0),
(113, 1, 'Dec', 0.57393, 0),
(114, 1, 'Simon Cowell', 0.559389, 0),
(115, 1, 'APPLAUSE DROWNS SPEECH', 0.551119, 0),
(116, 1, 'Julianne Moore', 0.546011, 0),
(117, 1, 'marriage equality', 0.535786, 0),
(118, 1, 'dick sand', 0.535613, 0),
(119, 1, 'Shot Simon Cowell', 0.534221, 0),
(120, 1, 'APPLAUSE Aw', 0.533561, 0),
(121, 1, 'LAUGHTER OK', 0.53281, 0),
(122, 1, 'LAUGHTER Sorry', 0.527994, 0),
(123, 1, 'Ellen Page', 0.5198, 0),
(124, 1, 'slash cousin cos', 0.515121, 0),
(125, 1, 'Valentine', 0.514505, 0),
(126, 1, 'Jason Derulo', 0.512875, 0),
(127, 1, 'random graffiti cos', 0.512604, 0),
(128, 1, 'love', 0.507044, 0),
(129, 1, 'great new romcom', 0.501882, 0),
(130, 1, 'Byker Grove', 0.499775, 0),
(131, 1, 'Little Ant', 0.494214, 0),
(132, 1, 'Mr Justin Bieber', 0.993104, 0),
(133, 1, 'Maggie Smith', 0.964564, 0),
(134, 1, 'Miss Shepherd', 0.950473, 0),
(135, 1, 'Dame Maggie Smith', 0.948924, 0),
(136, 1, 'Alan Bennett', 0.948651, 0),
(137, 1, 'Bradley Cooper', 0.929565, 0),
(138, 1, 'Alex Jennings', 0.911218, 0),
(139, 1, 'van', 0.888756, 0),
(140, 1, 'Sienna Miller', 0.876772, 0),
(141, 1, 'american sniper', 0.871759, 0),
(142, 1, 'Prince Charles', 0.847876, 0),
(143, 1, 'Meryl Streep', 0.840588, 0),
(144, 1, 'new film Burnt', 0.837855, 0),
(145, 1, 'kitchen drama Burnt', 0.820193, 0),
(146, 1, 'British chat', 0.817067, 0),
(147, 1, 'Old ugly corner', 0.80982, 0),
(148, 1, 'people kind LAUGHTER', 0.808939, 0),
(149, 1, 'black cab driver', 0.80845, 0),
(150, 1, 'Downton Abbey', 0.808247, 0),
(151, 1, 'Silver Linings Playbook', 0.80824, 0),
(152, 1, 'amazingly talented star', 0.807518, 0),
(153, 1, 'big deal', 0.807074, 0),
(154, 1, 'real juggling act', 0.804921, 0),
(155, 1, 'proper spy stuff', 0.804456, 0),
(156, 1, 'Michelin restaurant food', 0.804141, 0),
(157, 1, 'big Downton fan', 0.803306, 0);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `interest_area`
--
ALTER TABLE `interest_area`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `interest_area`
--
ALTER TABLE `interest_area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
