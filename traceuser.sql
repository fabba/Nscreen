-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Gegenereerd op: 03 mei 2016 om 14:28
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
-- Tabelstructuur voor tabel `traceuser`
--

CREATE TABLE `traceuser` (
  `visitID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `objectID` int(11) NOT NULL,
  `sessionID` varchar(40) NOT NULL,
  `pageID` text NOT NULL,
  `pageType` text NOT NULL,
  `imagesCount` int(11) NOT NULL,
  `textSizeCount` int(11) NOT NULL,
  `linksCount` int(11) NOT NULL,
  `windowSizeX` int(11) NOT NULL,
  `windowSizeY` int(11) NOT NULL,
  `pageSizeX` int(11) NOT NULL,
  `pageSizeY` int(11) NOT NULL,
  `objectsListed` text NOT NULL,
  `startDatetime` datetime NOT NULL,
  `endDatetime` datetime NOT NULL,
  `timeOnPage` int(11) DEFAULT NULL,
  `mouseClicksCount` int(11) DEFAULT NULL,
  `pageViewsCount` int(11) DEFAULT NULL,
  `mouseMovingTime` int(11) DEFAULT NULL,
  `mouseMovingDistance` int(11) DEFAULT NULL,
  `scrollingCount` int(11) NOT NULL,
  `scrollingTime` int(11) NOT NULL,
  `scrollingDistance` int(11) DEFAULT NULL,
  `printPageCount` int(11) DEFAULT NULL,
  `selectCount` int(11) DEFAULT NULL,
  `selectedText` text NOT NULL,
  `searchedText` text NOT NULL,
  `copyCount` int(11) DEFAULT NULL,
  `copyText` text NOT NULL,
  `clickOnPurchaseCount` int(11) DEFAULT NULL,
  `purchaseCount` int(11) DEFAULT NULL,
  `forwardingToLinkCount` int(11) DEFAULT NULL,
  `forwardedToLink` text,
  `logFile` text
) ENGINE=MyISAM DEFAULT CHARSET=cp1250;

--
-- Gegevens worden geëxporteerd voor tabel `traceuser`
--

INSERT INTO `traceuser` (`visitID`, `userID`, `objectID`, `sessionID`, `pageID`, `pageType`, `imagesCount`, `textSizeCount`, `linksCount`, `windowSizeX`, `windowSizeY`, `pageSizeX`, `pageSizeY`, `objectsListed`, `startDatetime`, `endDatetime`, `timeOnPage`, `mouseClicksCount`, `pageViewsCount`, `mouseMovingTime`, `mouseMovingDistance`, `scrollingCount`, `scrollingTime`, `scrollingDistance`, `printPageCount`, `selectCount`, `selectedText`, `searchedText`, `copyCount`, `copyText`, `clickOnPurchaseCount`, `purchaseCount`, `forwardingToLinkCount`, `forwardedToLink`, `logFile`) VALUES
(4, 1, 0, '0', 'index', 'index', 1, 155095, 10, 1203, 971, 1203, 0, '', '2016-05-03 11:42:52', '2016-05-03 11:45:52', 180000, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 0, '', 0, 0, 0, '', ''),
(5, 1, 0, 'pmhjhvuk34nj4la4k0pr9ae7n7', 'index', 'index', 1, 155186, 10, 1203, 971, 1203, 0, '', '2016-05-03 11:46:16', '2016-05-03 11:47:16', 60004, 3, 1, 7800, 7120, 0, 0, 0, 0, 0, '', '', 0, '', 0, 0, 0, '', '\n 2016-5-3 11:46:18; MouseMove; to:1038,462\n 2016-5-3 11:46:22; MouseMove; to:1198,246   to:399,18\n 2016-5-3 11:46:47; MouseMove; to:602,1\n 2016-5-3 11:46:47; MouseMove; to:547,418   to:645,826   to:640,923\n 2016-5-3 11:46:50; MouseClick; on oid=null;\n 2016-5-3 11:46:51; MouseMove; to:634,331   to:627,484   to:637,542\n 2016-5-3 11:46:55; MouseClick; on oid=null;\n 2016-5-3 11:46:57; MouseMove; to:997,0\n 2016-5-3 11:46:57; MouseClick; on oid=null;\n 2016-5-3 11:46:59; MouseMove; to:429,1   to:936,339   to:1171,425   to:1200,651   to:865,494   to:802,463   to:724,477   to:1012,485'),
(6, 1, 0, 'eecopplta8c2a2loqfkdt7rem1', 'index', 'index', 1, 155107, 10, 1203, 971, 1203, 0, '', '2016-05-03 11:48:39', '2016-05-03 12:00:00', 603605, 8, 8, 30800, 24366, 0, 0, 0, 0, 0, '', '', 0, '', 0, 0, 0, '', '\n 2016-5-3 11:48:40; MouseMove; to:484,597\n 2016-5-3 11:48:40; MouseMove; to:664,804   to:658,877   to:668,958\n 2016-5-3 11:48:42; MouseClick; on oid=null;\n 2016-5-3 11:48:43; MouseClick; on oid=null;\n 2016-5-3 11:48:44; MouseMove; to:612,843   to:696,970\n 2016-5-3 11:49:38; MouseMove; to:600,728\n 2016-5-3 11:49:39; MouseMove; to:591,655   to:864,864   to:788,955   to:699,969\n 2016-5-3 11:50:20; MouseMove; to:655,423\n 2016-5-3 11:50:22; MouseMove; to:507,22\n 2016-5-3 11:51:6; MouseMove; to:551,432\n 2016-5-3 11:51:6; MouseMove; to:543,500   to:457,563   to:270,639   to:195,528   to:290,722   to:464,660   to:990,637   to:1192,584   to:432,118   to:400,33   to:634,929   to:402,127\n 2016-5-3 11:52:35; MouseMove; to:572,1\n 2016-5-3 11:52:35; MouseMove; to:1189,452\n 2016-5-3 11:53:22; MouseMove; to:528,179\n 2016-5-3 11:53:22; MouseMove; to:984,518   to:1124,472   to:585,969   to:767,965   to:1093,335\n 2016-5-3 11:53:47; MouseClick; on oid=null;\n 2016-5-3 11:54:29; MouseMove; to:1013,308\n 2016-5-3 11:54:54; MouseMove; to:1202,17   to:1011,164   to:1026,420\n 2016-5-3 11:54:55; MouseClick; on oid=null;\n 2016-5-3 11:54:56; MouseMove; to:966,458   to:544,673   to:681,935\n 2016-5-3 11:56:1; MouseMove; to:587,473\n 2016-5-3 11:56:3; MouseMove; to:529,444   to:426,380   to:542,576   to:1095,523   to:1200,374   to:859,377   to:508,460   to:1198,507   to:689,512   to:346,441\n 2016-5-3 11:56:15; MouseClick; on oid=null;\n 2016-5-3 11:56:16; MouseMove; to:948,506   to:1201,564   to:1021,563   to:900,622   to:906,747   to:931,629   to:794,577   to:728,552\n 2016-5-3 11:56:31; MouseMove; to:828,740\n 2016-5-3 11:56:32; MouseMove; to:1033,696\n 2016-5-3 11:57:26; MouseMove; to:598,0\n 2016-5-3 11:57:27; MouseMove; to:788,136\n 2016-5-3 11:57:58; MouseMove; to:690,970\n 2016-5-3 11:57:58; MouseMove; to:631,402   to:694,490   to:730,537\n 2016-5-3 11:58:4; MouseMove; to:1202,493\n 2016-5-3 11:58:4; MouseMove; to:933,495   to:847,510\n 2016-5-3 11:58:6; MouseClick; on oid=null;\n 2016-5-3 11:58:9; MouseMove; to:626,528   to:532,534\n 2016-5-3 11:58:9; MouseClick; on oid=null;\n 2016-5-3 11:58:11; MouseMove; to:238,542\n 2016-5-3 11:58:13; MouseClick; on oid=null;\n 2016-5-3 11:58:17; MouseMove; to:362,486'),
(7, 1, 0, '21g6pfejg3s5p083pu6knr28a6', 'index', 'index', 1, 155223, 10, 1203, 971, 1203, 0, '', '2016-05-03 12:00:09', '2016-05-03 12:05:04', 272223, 9, 4, 19800, 17766, 0, 0, 0, 0, 0, '', '', 0, '', 0, 0, 0, '', '\n 2016-5-3 12:0:11; MouseMove; to:603,554\n 2016-5-3 12:0:20; MouseMove; to:1201,545   to:564,483   to:563,836   to:605,961   to:649,923   to:684,844   to:458,747   to:441,422   to:443,520\n 2016-5-3 12:0:24; MouseClick; on oid=null;\n 2016-5-3 12:0:35; MouseClick; on oid=null;\n 2016-5-3 12:0:36; MouseMove; to:1106,128   to:963,543\n 2016-5-3 12:0:56; MouseMove; to:600,597\n 2016-5-3 12:0:56; MouseMove; to:454,721   to:399,564   to:405,372\n 2016-5-3 12:0:58; MouseClick; on oid=null;\n 2016-5-3 12:1:3; MouseMove; to:1125,142   to:1201,77   to:1129,39   to:1066,27\n 2016-5-3 12:1:10; MouseClick; on oid=null;\n 2016-5-3 12:1:12; MouseMove; to:1172,14   to:1082,37\n 2016-5-3 12:1:13; MouseClick; on oid=null;\n 2016-5-3 12:1:14; MouseMove; to:1116,136   to:1071,43\n 2016-5-3 12:1:17; MouseClick; on oid=null;\n 2016-5-3 12:1:19; MouseMove; to:1106,124   to:705,969   to:467,144\n 2016-5-3 12:3:11; MouseMove; to:1201,280\n 2016-5-3 12:3:12; MouseMove; to:600,104   to:681,539   to:614,618   to:582,570   to:465,674\n 2016-5-3 12:3:15; MouseClick\n 2016-5-3 12:3:17; MouseMove; to:1137,139   to:960,138\n 2016-5-3 12:3:58; MouseMove; to:718,947\n 2016-5-3 12:3:59; MouseMove; to:578,655   to:1200,537   to:953,522   to:381,604\n 2016-5-3 12:4:2; MouseClick; on oid=23;\n 2016-5-3 12:4:7; MouseMove; to:1126,162   to:1072,138   to:1199,445   to:1032,379   to:797,107   to:763,438   to:758,536\n 2016-5-3 12:4:18; MouseClick; on oid=26;\n 2016-5-3 12:4:20; MouseMove; to:703,432   to:447,530\n 2016-5-3 12:4:36; MouseMove; to:1202,406'),
(8, 1, 0, 'u9tn2tammesi7gcas360hfsqu5', 'index', 'index', 1, 155095, 10, 1920, 971, 1920, 0, '', '2016-05-03 12:06:11', '2016-05-03 12:07:58', 105144, 11, 2, 21800, 16380, 0, 0, 0, 0, 10, ';\nn, together in The Night Manager, and broadcasting legend Sir David Attenborough. Plus music from Elle King.;\nn, together in The Night Manager, and broadcasting legend Sir David Attenborough. Plus music from Elle King.;\n\n;\n\n;\n\n;\n\n\n\nWar Walks, Series 2, Hastings\nProfessor Richard Holmes walks and rides over the Hastings battlefield that marks a turning point in British history, handling the weapons and equipment of the period and becoming a Norman knight to reveal just how close William the Conqueror came to defeat.\n\n#Harold#William#shield wall#men#King Harold#Saxons#Normans#Bayeux Tapestry#battle#tattered shield wall#army#ad Bosham ecclesia#freely no-one knows#sheer bad luck#Caldbec Hill#holy relics#Old English verse#biggest amphibious operation#open-top wooden boats#Senlac Ridge\nSharable Link\n\n\nWatch Later\n\nLike\n\nDislike\n\nShared by friends\n\nRecenlty Viewed\nMORE LIKE THISView All ?\n\n\nWar Walks, Series 2, Battle of Naseby\n\nWar Walks, Series 2, Bosworth\n\nWar Walks, Series 1, Mons\n\nWar Walks, Series 1, Goodwood\n\nWar Walks, Series 2, Dunkirk\n\nWar Walks, Series 1, The Somme\n\nWar Walks, Series 1, Arras\n;\nment of the period and becoming a Norman knight to reveal just ;\nthat marks a turning point in British history, handling the weapons and;\nthat marks a turning point in British history, handling the weapons and;\nthat marks a turning point in British history, handling the weapons and', '', 2, ';\nn, together in The Night Manager, and broadcasting legend Sir David Attenborough. Plus music from Elle King.;\nthat marks a turning point in British history, handling the weapons and', 0, 0, 0, '', '\n 2016-5-3 12:6:12; MouseMove; to:734,500\n 2016-5-3 12:6:13; MouseMove; to:267,598   to:225,670   to:298,705\n 2016-5-3 12:6:18; MouseClick; on oid=23;\n 2016-5-3 12:6:22; MouseMove; to:1121,173   to:978,201   to:909,194   to:474,259   to:535,233   to:439,227\n 2016-5-3 12:6:24; Select text:n, together in The Night Manager, and broadcasting legend Sir David Attenborough. Plus music from Elle King.\n 2016-5-3 12:6:28; MouseClick; on oid=null;\n 2016-5-3 12:6:30; MouseMove; to:1145,125   to:983,156   to:751,231\n 2016-5-3 12:6:31; Select text:n, together in The Night Manager, and broadcasting legend Sir David Attenborough. Plus music from Elle King.\n 2016-5-3 12:6:35; Copy text\n 2016-5-3 12:6:35; MouseMove; to:863,251 text:n, together in The Night Manager, and broadcasting legend Sir David Attenborough. Plus music from Elle King.   to:1139,150   to:1715,11   to:1318,331   to:960,914\n 2016-5-3 12:6:59; MouseMove; to:735,697\n 2016-5-3 12:7:1; MouseMove; to:611,651   to:108,417\n 2016-5-3 12:7:4; MouseClick; on oid=11;\n 2016-5-3 12:7:5; Select text:\n\n 2016-5-3 12:7:5; MouseClick; on oid=null;\n 2016-5-3 12:7:5; on oid=11; Select text:\n\n 2016-5-3 12:7:6; MouseMove; to:211,609   to:893,588   to:1201,119   to:277,467   to:284,404   to:136,391\n 2016-5-3 12:7:13; on oid=11; Select text:\n\n 2016-5-3 12:7:13; MouseClick; on oid=11;\n 2016-5-3 12:7:15; MouseMove; to:972,48   to:906,66\n 2016-5-3 12:7:16; Select text:\n\n\nWar Walks, Series 2, Hastings\nProfessor Richard Holmes walks and rides over the Hastings battlefield that marks a turning point in British history, handling the weapons and equipment of the period and becoming a Norman knight to reveal just how close William the Conqueror came to defeat.\n\n#Harold#William#shield wall#men#King Harold#Saxons#Normans#Bayeux Tapestry#battle#tattered shield wall#army#ad Bosham ecclesia#freely no-one knows#sheer bad luck#Caldbec Hill#holy relics#Old English verse#biggest amphibious operation#open-top wooden boats#Senlac Ridge\nSharable Link\n\n\nWatch Later\n\nLike\n\nDislike\n\nShared by friends\n\nRecenlty Viewed\nMORE LIKE THISView All ?\n\n\nWar Walks, Series 2, Battle of Naseby\n\nWar Walks, Series 2, Bosworth\n\nWar Walks, Series 1, Mons\n\nWar Walks, Series 1, Goodwood\n\nWar Walks, Series 2, Dunkirk\n\nWar Walks, Series 1, The Somme\n\nWar Walks, Series 1, Arras\n\n 2016-5-3 12:7:16; MouseClick; on oid=null;\n 2016-5-3 12:7:17; MouseMove; to:1067,0\n 2016-5-3 12:7:17; MouseClick; on oid=null;\n 2016-5-3 12:7:18; MouseMove; to:357,137   to:125,101\n 2016-5-3 12:7:19; MouseClick; on oid=11;\n 2016-5-3 12:7:20; MouseMove; to:861,89   to:863,175   to:611,217   to:355,228   to:312,211\n 2016-5-3 12:7:23; Select text:ment of the period and becoming a Norman knight to reveal just \n 2016-5-3 12:7:23; MouseClick; on oid=null;\n 2016-5-3 12:7:24; Select text:that marks a turning point in British history, handling the weapons and\n 2016-5-3 12:7:24; MouseClick; on oid=null;\n 2016-5-3 12:7:25; Select text:that marks a turning point in British history, handling the weapons and\n 2016-5-3 12:7:25; Copy text text:that marks a turning point in British history, handling the weapons and\n 2016-5-3 12:7:25; MouseMove; to:457,201   to:836,209   to:730,621\n 2016-5-3 12:7:27; on oid=6; Select text:that marks a turning point in British history, handling the weapons and\n 2016-5-3 12:7:27; MouseClick; on oid=6;\n 2016-5-3 12:7:28; MouseMove; to:403,182\n 2016-5-3 12:7:28; MouseMove; to:551,306\n 2016-5-3 12:7:29; MouseMove; to:1195,327'),
(9, 1, 0, 'gjjmqqcalhdb2oocugm3ho8n50', 'index', 'index', 1, 155543, 10, 1203, 971, 1203, 0, '', '2016-05-03 12:14:42', '2016-05-03 12:21:14', 390543, 31, 2, 39000, 21196, 0, 0, 0, 0, 2, ';\nYOUR ;\nYOUR FRIENDS\n\n', '', 0, '', 0, 0, 0, '', '\n 2016-5-3 12:14:44; MouseMove; to:578,609\n 2016-5-3 12:14:45; MouseMove; to:489,634   to:441,680   to:333,664\n 2016-5-3 12:14:52; MouseClick; on oid=23;\n 2016-5-3 12:14:53; MouseMove; to:447,607   to:510,320   to:443,320\n 2016-5-3 12:14:55; MouseClick; on oid=tags_Hugh Laurie;\n 2016-5-3 12:14:56; MouseMove; to:278,218\n 2016-5-3 12:14:57; MouseClick; on oid=23;\n 2016-5-3 12:14:58; MouseMove; to:514,578   to:725,553\n 2016-5-3 12:14:59; MouseClick; on oid=26;\n 2016-5-3 12:15:1; MouseMove; to:1201,262   to:1125,369   to:1198,497   to:532,666   to:522,577   to:449,367\n 2016-5-3 12:15:4; MouseClick; on oid=tags_APPLAUSE DROWNS SPEECH;\n 2016-5-3 12:15:5; MouseMove; to:333,178\n 2016-5-3 12:15:6; MouseClick; on oid=26;\n 2016-5-3 12:15:10; MouseMove; to:605,199   to:703,359   to:818,376   to:793,221\n 2016-5-3 12:15:12; MouseClick; on oid=video_26;\n 2016-5-3 12:15:12; MouseMove; to:784,275   to:786,337\n 2016-5-3 12:15:13; MouseClick; on oid=video_26;\n 2016-5-3 12:15:14; MouseMove; to:667,167\n 2016-5-3 12:16:5; MouseMove; to:1035,3\n 2016-5-3 12:16:8; MouseClick; on oid=close;\n 2016-5-3 12:16:9; MouseMove; to:257,585   to:264,726   to:213,717\n 2016-5-3 12:16:11; MouseClick; on oid=11;\n 2016-5-3 12:16:12; MouseMove; to:601,450\n 2016-5-3 12:16:13; MouseMove; to:747,447\n 2016-5-3 12:16:14; MouseClick; on oid=video_11;\n 2016-5-3 12:16:15; MouseMove; to:1001,0   to:1062,23\n 2016-5-3 12:16:16; MouseClick; on oid=null;\n 2016-5-3 12:16:17; MouseClick; on oid=null;\n 2016-5-3 12:16:17; MouseMove; to:1012,902   to:992,952   to:574,655   to:528,541   to:567,459   to:1079,97   to:1003,121   to:884,863\n 2016-5-3 12:16:20; MouseClick; on oid=null;\n 2016-5-3 12:16:21; MouseClick; on oid=null;\n 2016-5-3 12:16:21; Select text:YOUR \n 2016-5-3 12:16:21; MouseClick; on oid=null;\n 2016-5-3 12:16:21; Select text:YOUR FRIENDS\n\n\n 2016-5-3 12:16:21; MouseClick; on oid=null;\n 2016-5-3 12:16:21; MouseMove; to:603,739\n 2016-5-3 12:16:22; MouseClick; on oid=null;\n 2016-5-3 12:16:22; MouseClick; on oid=null;\n 2016-5-3 12:16:22; MouseMove; to:549,682\n 2016-5-3 12:16:23; MouseClick; on oid=null;\n 2016-5-3 12:16:23; MouseClick; on oid=null;\n 2016-5-3 12:16:24; MouseMove; to:617,747   to:716,970   to:287,0\n 2016-5-3 12:17:23; MouseMove; to:1202,54\n 2016-5-3 12:17:23; MouseMove; to:544,366   to:568,814   to:290,670   to:144,307\n 2016-5-3 12:17:25; MouseClick; on oid=11;\n 2016-5-3 12:17:26; MouseMove; to:854,252\n 2016-5-3 12:17:27; MouseClick; on oid=play_11;\n 2016-5-3 12:17:29; MouseMove; to:1039,115   to:1075,46\n 2016-5-3 12:17:29; MouseClick; on oid=null;\n 2016-5-3 12:17:30; MouseMove; to:467,321   to:487,583   to:510,685\n 2016-5-3 12:17:31; MouseClick; on oid=null;\n 2016-5-3 12:17:32; MouseMove; to:488,659   to:415,593\n 2016-5-3 12:17:32; MouseClick; on oid=23;\n 2016-5-3 12:17:33; MouseMove; to:465,502\n 2016-5-3 12:17:34; MouseClick; on oid=22;\n 2016-5-3 12:17:35; MouseMove; to:832,130   to:563,445   to:542,512\n 2016-5-3 12:17:36; MouseClick; on oid=23;\n 2016-5-3 12:17:38; MouseMove; to:692,538\n 2016-5-3 12:17:38; MouseClick; on oid=26;\n 2016-5-3 12:17:39; MouseMove; to:449,550   to:362,559   to:302,555\n 2016-5-3 12:17:40; MouseClick; on oid=24;\n 2016-5-3 12:17:41; MouseMove; to:318,503   to:333,430\n 2016-5-3 12:17:42; MouseClick; on oid=tags_Sir Johnny Depp;\n 2016-5-3 12:17:44; MouseMove; to:334,226'),
(10, 1, 0, 'lqtd280e6fuptf6ajkirkfg0v7', 'index', 'index', 1, 155455, 10, 1203, 971, 1203, 0, 'side-b:0,0,2;content:397,0,2;content2:738,0,2;content3:1079,0,2;content4:1420,0,2;content5:1761,0,2;', '2016-05-03 12:48:30', '2016-05-03 12:53:44', 153529, 1, 3, 5400, 4779, 0, 0, 0, 0, 0, '', '', 0, '', 0, 0, 0, '', '\n 2016-5-3 12:48:36; MouseMove; to:1202,773\n 2016-5-3 12:48:36; MouseMove; to:746,900   to:749,969\n 2016-5-3 12:49:3; MouseMove; to:1199,306\n 2016-5-3 12:49:3; MouseMove; to:792,99\n 2016-5-3 12:49:46; MouseMove; to:1198,437\n 2016-5-3 12:49:46; MouseMove; to:1044,524   to:849,578   to:685,788   to:681,931\n 2016-5-3 12:49:49; MouseClick; on oid=null;\n 2016-5-3 12:49:51; MouseMove; to:699,731   to:696,483   to:961,492   to:1202,498');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `traceuser`
--
ALTER TABLE `traceuser`
  ADD PRIMARY KEY (`visitID`),
  ADD UNIQUE KEY `userID` (`userID`,`sessionID`,`pageID`(500));

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `traceuser`
--
ALTER TABLE `traceuser`
  MODIFY `visitID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
