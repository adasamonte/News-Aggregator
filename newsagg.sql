-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2025 at 04:33 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `newsagg`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activity_type` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blacklist`
--

CREATE TABLE `blacklist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blacklist`
--

INSERT INTO `blacklist` (`id`, `user_id`, `keyword`, `created_at`) VALUES
(1, 10, 'Donald Trump', '2025-03-19 15:12:30');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `concern` text NOT NULL,
  `created_at` datetime NOT NULL,
  `status` enum('new','read','replied') DEFAULT 'new'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `email`, `phone`, `concern`, `created_at`, `status`) VALUES
(1, 'esteffanieringad@gmail.com', '09777094352', 'I want to remove a certain article from my feed, its the Donald trump related to elon musk', '2025-03-19 19:28:20', 'new');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `campaign_id` int(11) DEFAULT NULL,
  `donor_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `donation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','completed','failed') DEFAULT 'pending',
  `transaction_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `campaign_id`, `donor_id`, `amount`, `donation_date`, `status`, `transaction_id`) VALUES
(1, NULL, 1, 100.00, '2025-03-19 14:52:16', 'completed', 'DON_67dada20dd1c7');

-- --------------------------------------------------------

--
-- Table structure for table `donation_campaigns`
--

CREATE TABLE `donation_campaigns` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `goal_amount` decimal(10,2) NOT NULL,
  `current_amount` decimal(10,2) DEFAULT 0.00,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active','completed','cancelled') DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` datetime NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `registration_deadline` datetime DEFAULT NULL,
  `organizer_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_registrations`
--

CREATE TABLE `event_registrations` (
  `id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `member_profiles`
--

CREATE TABLE `member_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `expertise` varchar(255) DEFAULT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `social_linkedin` varchar(255) DEFAULT NULL,
  `social_twitter` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `category` enum('document','presentation','video','link') NOT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `download_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `saved_articles`
--

CREATE TABLE `saved_articles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `published_at` datetime NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saved_articles`
--

INSERT INTO `saved_articles` (`id`, `user_id`, `title`, `url`, `published_at`, `description`) VALUES
(1, 1, 'Game Developers Are Getting Fed Up With Their Boss\' AI Initiatives', 'https://www.wired.com/story/video-game-industry-artificial-intelligence-developers/', '2025-01-21 17:00:00', 'A survey of video game developers released Tuesday indicates that a growing number of developers fear artificial intelligence will have a negative impact on the industry as a whole.'),
(2, 1, 'Luka deal improves Lakers\' title odds dramatically', 'https://www.espn.com/nba/story/_/id/43665047/lakers-title-odds-improve-dramatically-luka-doncic-trade', '2025-02-02 16:40:14', 'The trade that sent Luka Doncic to the Lakers and Anthony Davis to the Mavericks had swift, drastic implications on the sports betting markets.'),
(3, 3, 'U.S.-Canadian Hockey Match Devolves Into Fights After Trump Tariffs', 'https://www.tmz.com/2025/02/16/united-states-canada-hockey-fight-over-trump-tariffs/', '2025-02-16 12:59:19', 'Canadians are pissed at Americans over President Trump imposing tariffs on our ally to the north — and nowhere is this more evident than in the hockey rink. Three fights broke out in a manner of 9 seconds during Saturday’s USA-Canada 4 Nations…'),
(4, 3, 'The Vision Pro NBA app turns some games into a miniature 3D diorama', 'https://www.theverge.com/news/613796/nba-tabletop-ar-vision-pro-app-league-pass', '2025-02-15 15:32:50', 'The NBA has introduced a new AR feature for its Vision Pro app this week called Tabletop, which places a floating render of a basketball court in your space during “select” live games, according to an NBA help page describing the feature. On the court, digita…'),
(6, 1, 'Today\'s NYT Connections: Sports Edition Hints and Answers for Feb. 19, #149', 'https://www.cnet.com/tech/gaming/todays-nyt-connections-sports-edition-hints-and-answers-for-feb-19-149/', '2025-02-18 21:00:06', 'Here\'s today\'s Connections: Sports Edition answer and hints for groups. These clues will help you solve The New York Times\' popular puzzle game, Connections: Sports Edition, every day.'),
(7, 1, 'Paramount and YouTube TV finalize deal keeping CBS, CBS Sports available', 'https://www.androidcentral.com/streaming-tv/paramount-and-youtube-tv-finalize-deal-keeping-cbs-cbs-sports-available', '2025-02-17 18:47:59', 'YouTube and Paramount were struggling to reach \"a fair deal,\" potentially causing all 30+ Paramount channels to disappear. That crisis was avoided.'),
(8, 1, 'Carbon removal is the next big fossil fuel boom, oil company says', 'https://www.theverge.com/news/616662/carbon-removal-dac-oil-gas-occidental', '2025-02-20 22:33:06', 'Occidental, the oil giant that has tried to fashion itself as a climate tech leader, is being real clear now about capturing carbon dioxide emissions, which it sees as the next big thing for fossil fuel production. That shouldn’t be surprising coming from a p…'),
(9, 1, 'Pixel phones to gain an important battery feature, tipped by Android 16 beta', 'https://www.androidcentral.com/apps-software/pixel-phones-might-finally-introduce-battery-health-feature-with-android-16', '2025-02-19 20:48:31', 'Google seems to be reintroducing the previously spotted Pixel Battery Health feature with Android 16.'),
(10, 1, 'OnePlus Watch 3: The Battery King of Smartwatches', 'https://www.wired.com/review/oneplus-watch-3/', '2025-02-18 13:00:00', 'The OnePlus Watch 3 raises the bar, putting Apple, Google, and Samsung to shame with its five-day battery.'),
(11, 1, 'Today\'s NYT Connections: Sports Edition Hints and Answers for Feb. 19, #149', 'https://www.cnet.com/tech/gaming/todays-nyt-connections-sports-edition-hints-and-answers-for-feb-19-149/', '2025-02-18 21:00:06', 'Here\'s today\'s Connections: Sports Edition answer and hints for groups. These clues will help you solve The New York Times\' popular puzzle game, Connections: Sports Edition, every day.'),
(13, 1, '\'We quit our jobs and sold our house to travel the world with the kids\'', 'https://www.bbc.com/news/articles/c897j2jl5kxo', '2025-02-16 06:13:35', 'The Hutchinsons say selling their home and travelling the world with their daughters has saved them.'),
(14, 10, 'Sen. Mark Kelly is looking into selling his Tesla over Elon Musk. Other big names have already pulled the trigger.', 'https://www.businessinsider.com/celebrities-politicians-who-sold-tesla-over-elon-musk-trump', '2025-03-12 17:39:50', '\"It\'s kind of cheaply built on the inside, but I love the performance,\" the Arizona Senator told reporters this week about his Tesla.'),
(15, 10, 'Amazon, Google and Meta Support Tripling Nuclear Power By 2050', 'https://hardware.slashdot.org/story/25/03/12/1350256/amazon-google-and-meta-support-tripling-nuclear-power-by-2050', '2025-03-12 14:00:00', 'Amazon, Alphabet\'s Google and Meta Platforms on Wednesday said they support efforts to at least triple nuclear energy worldwide by 2050. From a report: The tech companies signed a pledge first adopted in December 2023 by more than 20 countries, including the …'),
(16, 10, 'Android 16 is getting a major hearing accessibility feature', 'https://www.theverge.com/tech/628533/android-16-auracast-hearing-aid-support', '2025-03-13 16:00:15', 'Android phones will soon support Auracast with Bluetooth LE hearing aids, letting people tune in to audio broadcasts in places where it’s otherwise hard to hear. Auracast is a Bluetooth Audio LE feature, and it allows one broadcaster to connect to a virtually…'),
(17, 10, 'Chinese Companies Rush to Put DeepSeek in Everything', 'https://www.wired.com/story/deepseek-china-nationalism/', '2025-03-12 10:00:00', 'From video game developers to a nuclear power plant, companies across China are adopting DeepSeek’s AI models to boost stock prices and flaunt their national pride.'),
(18, 10, 'Sen. Mark Kelly is looking into selling his Tesla over Elon Musk. Other big names have already pulled the trigger.', 'https://www.businessinsider.com/celebrities-politicians-who-sold-tesla-over-elon-musk-trump', '2025-03-12 17:39:50', '\"It\'s kind of cheaply built on the inside, but I love the performance,\" the Arizona Senator told reporters this week about his Tesla.'),
(19, 10, 'Apple Watch Series 10 Just Hit Its Lowest Price Since Launch, $100 Off on Amazon for a Limited Time', 'https://gizmodo.com/apple-watch-series-10-just-hit-its-lowest-price-since-launch-100-off-on-amazon-for-a-limited-time-2000573441', '2025-03-10 12:40:50', 'It\'s time for a smartwatch upgrade, and these major Apple Watch savings are well worth your time.'),
(20, 10, 'Want to Live Longer, Healthier, and Happier? Then Cultivate Your Social Connections', 'https://www.wired.com/story/want-to-live-longer-healthier-and-happier-cultivate-your-social-connections-wired-health-kasley-killam/', '2025-03-07 10:00:00', 'Chronic loneliness can increase cortisol and inflammation and weaken your immune system, says social scientist Kasley Killiam. She argues it’s time to accept that good quality social connections are a fundamental human need.'),
(22, 10, 'Starbucks\' CEO is planning a huge expansion in the Middle East and China', 'https://www.businessinsider.com/starbucks-ceo-plotting-huge-expansion-in-middle-east-and-china-2025-2', '2025-02-14 08:43:55', 'Starbucks plans to open 500 new stores in the Middle East and \"many more thousands\" in China, said Brian Niccol, the company\'s CEO.'),
(26, 10, 'Andor Season 2 Will Explore Luthen’s Past and the Future of His Rebellion', 'https://gizmodo.com/andor-season-2-will-explore-luthens-past-and-the-future-of-his-rebellion-2000575895', '2025-03-13 21:28:52', 'Showrunner Tony Gilroy teases the layers of Luthen\'s mysteries that will be uncovered when the Star Wars streaming series returns next month.'),
(27, 10, 'Net zero by 2050 \'impossible\' for UK, says Badenoch', 'https://www.bbc.com/news/articles/cly3pnjyzp4o', '2025-03-17 22:55:05', 'The Conservative leader says the target is impossible \"without a serious drop in our living standards or by bankrupting us\".');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `is_admin` int(1) NOT NULL,
  `username` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL DEFAULT 'Male',
  `birthday` date DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT 'default.png',
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `is_admin`, `username`, `firstname`, `lastname`, `email`, `password`, `gender`, `birthday`, `profile_picture`, `is_active`) VALUES
(1, 0, 'boggart', 'boggart', 'boga', 'boggart@gmail.com', 'boggart', 'Male', '2004-03-03', 'uploads/profile_67b91b2c39f523.86521007.jpg', 1),
(3, 0, 'John Marry', 'John', 'Marston', 'john@gmail.com', 'samonte3', 'Male', '2025-02-22', 'uploads/profile_67b91c2d5ceb77.22023369.png', 1),
(8, 0, 'SKURT', 'Kurt', 'Taningco', 'kurtpogi38@gmail.com', '$2y$10$iEBXeXDzdxgBZW7Qmr5e2OjzkefAyGKzHqmsGRdV8.AnhxQUZu3LO', 'Male', '2004-04-02', '227f5321266d6da4ac1f3a705972eed6.png', 1),
(10, 0, 'Stef', 'Esteffanie', 'Ringad', 'esteffanieringad@gmail.com', '$2y$10$cn2.Hc.O9KON4yYR4BnZr.088R8FwX6WDCe9XSurOQYvttXG3065e', 'Female', '2025-04-05', 'uploads/profile_67d97b5a5249f4.32418167.jpg', 1),
(11, 1, 'AdrielS', 'Adriel', 'Samonte', 'adsamonte@gmail.com', '$2y$10$iP0LfjspchQDYWA2EoZ2h.TDAnRKkT7JCTU8CadxXSNVVmwWFnAXK', '', NULL, 'default.png', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `blacklist`
--
ALTER TABLE `blacklist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_id` (`campaign_id`),
  ADD KEY `donor_id` (`donor_id`);

--
-- Indexes for table `donation_campaigns`
--
ALTER TABLE `donation_campaigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organizer_id` (`organizer_id`);

--
-- Indexes for table `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `member_profiles`
--
ALTER TABLE `member_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `saved_articles`
--
ALTER TABLE `saved_articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blacklist`
--
ALTER TABLE `blacklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `donation_campaigns`
--
ALTER TABLE `donation_campaigns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_registrations`
--
ALTER TABLE `event_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `member_profiles`
--
ALTER TABLE `member_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `saved_articles`
--
ALTER TABLE `saved_articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`);

--
-- Constraints for table `blacklist`
--
ALTER TABLE `blacklist`
  ADD CONSTRAINT `blacklist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `donation_campaigns` (`id`),
  ADD CONSTRAINT `donations_ibfk_2` FOREIGN KEY (`donor_id`) REFERENCES `users` (`ID`);

--
-- Constraints for table `donation_campaigns`
--
ALTER TABLE `donation_campaigns`
  ADD CONSTRAINT `donation_campaigns_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`ID`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`organizer_id`) REFERENCES `users` (`ID`);

--
-- Constraints for table `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD CONSTRAINT `event_registrations_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  ADD CONSTRAINT `event_registrations_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`);

--
-- Constraints for table `member_profiles`
--
ALTER TABLE `member_profiles`
  ADD CONSTRAINT `member_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`);

--
-- Constraints for table `resources`
--
ALTER TABLE `resources`
  ADD CONSTRAINT `resources_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`ID`);

--
-- Constraints for table `saved_articles`
--
ALTER TABLE `saved_articles`
  ADD CONSTRAINT `saved_articles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
