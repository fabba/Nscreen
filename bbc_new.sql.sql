-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Gegenereerd op: 26 mei 2016 om 18:15
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
-- Tabelstructuur voor tabel `bbc_programs`
--

DROP TABLE IF EXISTS `bbc_programs`;
CREATE TABLE `bbc_programs` (
  `id` int(11) NOT NULL,
  `title` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `description` longtext CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `video_url` text NOT NULL,
  `image_url` text NOT NULL,
  `date_published` date NOT NULL,
  `scenes_url` text NOT NULL,
  `shots_url` text NOT NULL,
  `type_program` text NOT NULL,
  `link` text NOT NULL,
  `youtube_url` text NOT NULL,
  `bbc_id` text NOT NULL,
  `format` text NOT NULL,
  `genre` text NOT NULL,
  `duration` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `bbc_programs`
--

INSERT INTO `bbc_programs` (`id`, `title`, `description`, `video_url`, `image_url`, `date_published`, `scenes_url`, `shots_url`, `type_program`, `link`, `youtube_url`, `bbc_id`, `format`, `genre`, `duration`) VALUES
(1106, 'Snooker: World Championship Highlights:2016, Day 11', 'Highlights from the quarter-finals are introduced by Jason Mohammad in Sheffield. Also in HD. [S]', 'bb8old.bluetrace.eu/videos/20160426-231500-world-championship-snooker-highlights-h264lg.mp4', 'bb8old.bluetrace.eu/bbc_images/6277991922053129944.jpg', '2016-04-26', '', '', '', '', '', 'b07951m9', '', '["sport", "sport/snooker"]', 3000),
(1107, 'Room 101 - Extra Storage:Series 4', '4/8. Frank Skinner hosts the comedy panel show as Alexander Armstrong, Dame Kelly Holmes and Henry Blofeld compete to have their pet hates and peeves consigned to Room 101. Also in HD. [S]', 'bb8old.bluetrace.eu/videos/20160426-232000-room-101---extra-storage-h264lg.mp4', 'bb8old.bluetrace.eu/bbc_images/6277993210535259577.jpg', '2016-04-26', '', '', '', '', '', 'b0644kmp', '["discussionandtalk", "discussion & talk"]', '["comedy", "entertainment"]', 2400),
(1108, 'Live at the Apollo:Series 9', '1/6. Stand-up comedy from the world-famous venue. Comedy legend Eddie Izzard introduces South Africas Trevor Noah and Devons favourite son Josh Widdicombe. Contains adult humour.  Also in HD. [S]', 'bb8old.bluetrace.eu/videos/20160427-000000-live-at-the-apollo-h264lg.mp4', 'bb8old.bluetrace.eu/bbc_images/6278003518456769713.jpg', '2016-04-27', '', '', '', '', '', 'b03jfxdr', '["performancesandevents", "performances & events"]', '["comedy/standup", "comedy"]', 1800),
(1109, 'Super League Show:2016', 'Highlights from round 12 of the Super League. St Helens v  Leeds, Widnes v Warrington, Wigan v Huddersfield, Catalans v Salford, Wakefield v Hull FC and Castleford v Hull KR. [S]', 'bb8old.bluetrace.eu/videos/20160427-000500-the-super-league-show-h264lg.mp4', 'bb8old.bluetrace.eu/bbc_images/6278004806955015436.jpg', '2016-04-27', '', '', '', '', '', 'b0795mxz', '', '["sport"]', 2700),
(1110, 'World Championship Snooker Extra:2016, Day 11', 'Jason Mohammad introduces extended highlights from the afternoons quarter-finals. Also in HD. [S]', 'bb8old.bluetrace.eu/videos/20160427-003000-world-championship-snooker---extra-h264lg.mp4', 'bb8old.bluetrace.eu/bbc_images/6278011249397899200.jpg', '2016-04-27', '', '', '', '', '', 'b0795077', '', '["sport", "sport/snooker"]', 10800),
(1111, 'MasterChef:Series 12', '11/25. Its the last week of the heats and the final group of amateurs battle for a place in the quarter-final. [AD,S,SL]', 'bb8old.bluetrace.eu/videos/20160427-005000-masterchef-h264lg.mp4', 'bb8old.bluetrace.eu/bbc_images/6278016403366718314.jpg', '2016-04-27', '', '', '', '', '', 'b077p7wx', '["games & quizzes"]', '["factual", "factual/foodanddrink"]', 3600),
(1112, 'A History of Ancient Britain:Series 1, Age of Cosmology', '3/4. Age of Cosmology: Neil Oliver continues the epic story of how Britain and its people came to be. He explores the age of the first cosmological priests, back in the Stone Age. Also in HD. [AD,S]', 'bb8old.bluetrace.eu/videos/20160427-005500-a-history-of-ancient-britain-h264lg.mp4', 'bb8old.bluetrace.eu/bbc_images/6278017691873761371.jpg', '2016-04-27', '', '', '', '', '', 'b00z0k23', '["documentaries"]', '["factual/history", "factual"]', 3600),
(1113, 'This Farming Life:', '5/12. An insight into modern farming life. As Christmas finally arrives, there are celebrations and a sad discovery in Martins cattle shed. [S,SL]', 'bb8old.bluetrace.eu/videos/20160427-015000-this-farming-life-h264lg.mp4', 'bb8old.bluetrace.eu/bbc_images/6278031865248983915.jpg', '2016-04-27', '', '', '', '', '', 'b074b536', '["documentaries"]', '["factual"]', 3600),
(1114, 'Paul Mertons Birth of Hollywood:', '3/3. Paul Merton traces the rise of the studios through the story of Metro Goldwyn Mayer - the biggest dream factory of them all, which boasted of more stars than the heavens. [AD,S]', 'bb8old.bluetrace.eu/videos/20160427-015500-paul-mertons-birth-of-hollywood-h264lg.mp4', 'bb8old.bluetrace.eu/bbc_images/6278033153756026972.jpg', '2016-04-27', '', '', '', '', '', 'b011vmsd', '["documentaries"]', '["factual"]', 3600),
(1115, 'Britains Treasure Islands:Outposts of Empire', '3/3. Outposts of Empire: The final part of Stewart McPhersons journey to visit all of the UKs Overseas Territories takes him to islands united by being military or trading bases. [AD,S,SL]', 'bb8old.bluetrace.eu/videos/20160427-025500-britains-treasure-islands-h264lg.mp4', 'bb8old.bluetrace.eu/bbc_images/6278048615638292574.jpg\r\n', '2016-04-27', '', '', '', '', '', 'b078lw8y', '["documentaries"]', '["factual", "factual/scienceandnature"]', 3600),
(1116, 'Snooker: World Championship:Crucible Classics, Ken Doherty v Paul Hunter, 2003', 'Hazel Irvine relives the 2003 semi-final between Ken Doherty and Paul Hunter. Hunter was already a two-time Masters winner and many considered that 2003 would be his year. [S]', 'bb8old.bluetrace.eu/videos/20160427-033000-world-snooker-crucible-classics-h264lg.mp4', 'bb8old.bluetrace.eu/bbc_images/6278057635044698036.jpg\r\n', '2016-04-27', '', '', '', '', '', 'b07950nm', '', '["sport", "sport/snooker"]', 7200),
(1118, 'Rip Off Britain:Food: Series 3', '2/10. Gloria Hunniford, Angela Rippon and Julia Somerville investigate the truth about our food. The team discovers whats being done to ensure our water supplies are clean. Also in HD. [S]', 'bb8old.bluetrace.eu/videos/20160427-060000-rip-off-britain-food-h264lg.mp4', 'bb8old.bluetrace.eu/bbc_images/6278096289758423919.jpg', '2016-04-27', '', '', '', '', '', 'b07987m4', '["documentaries"]', '["factual", "factual/consumer", "factual/foodanddrink"]', 2700),
(1120, 'Flog It: Trade Secrets:Series 1 - Reversions, The Great Outdoors', 'The Great Outdoors: Paul Martin and experts offer tips on antiques and collectibles. The experts reveal whats hot and whats not in the world of sporting memorabilia. Also in HD. [S]', 'bb8old.bluetrace.eu/videos/20160427-064500-flog-it-trade-secrets-h264lg.mp4', 'bb8old.bluetrace.eu/bbc_images/6278107886170123433.jpg', '2016-04-27', '', '', '', '', '', 'b05p6tp4', '', '["factual/antiques", "factual"]', 1800),
(1121, 'Homes Under the Hammer:Series 20', 'Property renovation series. The team visit a former millworkers cottage in Derbyshire, a house in Kent and an investment property in Lowestoft in Suffolk. Also in HD. [S]', 'bb8old.bluetrace.eu/videos/20160427-071500-homes-under-the-hammer-h264lg.mp4', 'bb8old.bluetrace.eu/bbc_images/6278115617111256234.jpg\r\n', '2016-04-27', '', '', '', '', '', 'b07946lr', '', '["factual", "factual/money", "factual/homesandgardens"]', 3600),
(1122, 'MasterChef:Series 12', '12/25. The six talented heat winners are set a test by Jay Rayner, challenging the amateur cooks to make an exceptional dish centred on one ingredient, duck breast. [AD,S,SL]', 'bb8old.bluetrace.eu/videos/20160428-010500-masterchef-h264lg.mp4', 'bb8old.bluetrace.eu/bbc_images/6278391354011659124.jpg', '2016-04-28', '', '', '', '', '', 'b077p80n', '["games & quizzes"]', '["factual", "factual/foodanddrink"]', 1800),
(1123, 'Five Star Babies: Inside the Portland Hospital:', '...Hospital. 1/2. A look inside the UKs only private maternity hospital. This episode meets high-society it girl Hui, who isnt ashamed to admit she is too scared to give birth naturally. [AD,S,SL]', 'bb8old.bluetrace.eu/videos/20160428-013500-five-star-babies-inside-the-portland-h264lg.mp4', 'bb8old.bluetrace.eu/bbc_images/6278399084952791925.jpg', '2016-04-28', '', '', '', '', '', 'b077nscw', '["documentaries"]', '["factual"]', 3600),
(1124, 'Bunkers, Brutalism and Bloodymindedness: Concrete Poetry with Jonathan Meades:', '...Bloodymindedness: Concrete Poetry with Jonathan Meades. 2/2. Meades reclaims the reputation of buildings he argues stood for optimism and grandeur. Contains some strong language.  Also in HD. [S]', 'bb8old.bluetrace.eu/videos/20160428-014500-bunkers-brutalism-and-h264lg.mp4', 'bb8old.bluetrace.eu/bbc_images/6278401661950023832.jpg\r\n', '2016-04-28', '', '', '', '', '', 'b03wcsdj', '["documentaries"]', '["factual"]', 3600),
(1125, 'Britains Biggest Superyachts: Chasing Perfection:Britains Biggest Superyachts: Chasing Perfection', '...Chasing Perfection. A behind-the-scenes look at Sunseeker, Britains biggest superyacht builder, as they build their first Â£20m, 40-metre flagship superyacht. [AD,S,SL]', 'bb8old.bluetrace.eu/videos/20160428-023500-britains-biggest-superyachts-h264lg.mp4', 'bb8old.bluetrace.eu/bbc_images/6278414546835057526.jpg\r\n', '2016-04-28', '', '', '', '', '', 'b077kgw9', '["documentaries"]', '["factual"]', 3600),
(1126, 'Snooker: World Championship:Crucible Classics, Shaun Murphy v Matthew Stevens, 2007', 'Hazel Irvine relives the 2007 quarter-final between Shaun Murphy and Matthew Stevens. This was a rerun of the 2005 final which Murphy won. [S]', 'bb8old.bluetrace.eu/videos/20160428-024500-world-snooker-crucible-classics-h264lg.mp4', 'bb8old.bluetrace.eu/bbc_images/6278417123807373273.jpg', '2016-04-28', '', '', '', '', '', 'b0795176', '', '["sport", "sport/snooker"]', 7200),
(1127, 'Britain Through a Lens: The Documentary Film Mob:Britain Through a Lens: The Documentary Film Mob', '...Documentary Film Mob. A film telling the unlikely story of how, between 1929 and 1945, a group of tweed-wearing radicals and pin-striped bureaucrats created the British documentary genre. [AD,S]', 'bb8old.bluetrace.eu/videos/20160428-024500-britain-through-a-lens-the-h264lg.mp4', 'bb8old.bluetrace.eu/bbc_images/6278417123832289630.jpg\r\n', '2016-04-28', '', '', '', '', '', 'b012p53d', '["documentaries"]', '["factual/history", "factual"]', 3600);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `bbc_programs`
--
ALTER TABLE `bbc_programs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `bbc_programs`
--
ALTER TABLE `bbc_programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1136;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
