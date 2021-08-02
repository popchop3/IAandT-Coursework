-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 01, 2021 at 04:26 PM
-- Server version: 5.7.35-0ubuntu0.18.04.1
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";




--
-- Database: `u_190220725_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `category` varchar(20) NOT NULL,
  `name` varchar(30) NOT NULL,
  `datetime` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  `organiser_id` int(11) NOT NULL,
  `venue` varchar(25) NOT NULL,
  `picture` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `category`, `name`, `datetime`, `description`, `organiser_id`, `venue`, `picture`) VALUES
(1, 'Other', 'Prom', '2021-10-05T18:00:00', '2021 Prom for all students', 1, 'Main Hall', 'Prom.jpg'),
(2, 'Culture', 'Christmas Ball', '2021-12-24T17:30:00', 'Celebrate Christmas with all the Students', 8, 'Main hall', 'Christmas Ball.jpg'),
(3, 'Sport', 'College Cricket', '2021-10-27T12:00:00', 'Cricket matches between the different colleges', 1, 'Cricket Pitch ', 'College Cricket.jpg'),
(4, 'Sport', 'College Swimming', '2021-05-28T12:30:00', 'Swimming competition between colleges', 10, 'Pool', 'College Swimming.jpg'),
(5, 'Culture', 'Diwali', '2021-11-04T15:00:00', 'Celebrate Diwali with students', 5, 'Great hall', 'Diwali.jpg'),
(6, 'Other', 'College Music', '2021-07-23T11:30:00', 'College Music Competition', 5, 'Great hall', 'College Music.jpg'),
(7, 'Sport', 'Lacrosse', '2021-02-15T18:30:00', 'Casual Lacrosse game', 5, 'Cricket Pitch', 'Lacrosse.jpg'),
(8, 'Other', 'Feed the homeless day', '2021-01-26T08:00:00', 'Sudents go around and feed nearby homeless', 8, 'Outisde School', 'Homeless.jpg'),
(11, 'Culture', 'Chinese New Year', '2022-02-01T16:15:00', 'Students can celebrate chinese new year', 1, 'Great Hall', 'Chinese New Year.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `interest_in_event`
--

CREATE TABLE `interest_in_event` (
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `interest_in_event`
--

INSERT INTO `interest_in_event` (`event_id`, `user_id`) VALUES
(1, 3),
(1, 2),
(3, 3),
(2, 3),
(4, 7),
(2, 5),
(1, 9),
(4, 9),
(1, 4),
(1, 5),
(2, 5),
(6, 5),
(7, 5),
(5, 2),
(7, 2),
(5, 1),
(7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phoneNo` varchar(11) NOT NULL,
  `password` varchar(20) NOT NULL,
  `forename` varchar(20) NOT NULL,
  `surname` varchar(30) NOT NULL,
  `student` tinyint(1) NOT NULL,
  `organiser` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `phoneNo`, `password`, `forename`, `surname`, `student`, `organiser`) VALUES
(1, 'thodge@aston.ac.uk', '7928065542', 'YtbxyS7%qnC3', 'Tom', 'Hodge', 0, 1),
(2, 'bjanus@aston.ac.uk', '7928065552', 'JnFD)@N7qY)y;:t', 'Ben', 'Janus', 1, 0),
(3, 'apatel@aston.ac.uk', '7928065532', 'E^weQ4;YP(.G2Wu4', 'Ashish', 'Patel', 1, 0),
(4, 'sgree@aston.ac.uk', '7928065512', 'm+vN$(5fy9~(ApZu', 'Sam', 'Greenleaf', 0, 1),
(5, 'psatish@aston.ac.uk', '7928065242', '*Hn36fB`7m~xy,Kk', 'Pranav', 'Satish', 0, 1),
(7, 'hotter@aston.ac.uk', '7928065742', '\"nzH5.[L5Z>RCqh2', 'Harry', 'Otter', 1, 0),
(8, 'rkulkarni@aston.ac.uk', '7928465542', '^K/5>4j(g2MEBTjj', 'Rutwik', 'Kulkarni', 0, 1),
(9, 'jwong@aston.ac.uk', '7926065542', 'RJ(H_qL75[$Uqxx*', 'Jari', 'Wong', 1, 0),
(10, 'oguest@aston.ac.uk', '7528065542', 'RT(H_QL55[$UqYx', 'Ollie', 'Guest', 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organiser_id` (`organiser_id`);

--
-- Indexes for table `interest_in_event`
--
ALTER TABLE `interest_in_event`
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events` FOREIGN KEY (`organiser_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `interest_in_event`
--
ALTER TABLE `interest_in_event`
  ADD CONSTRAINT `interest_in_event_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  ADD CONSTRAINT `interest_in_event_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;


