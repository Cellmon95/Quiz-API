-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 24 apr 2023 kl 15:58
-- Serverversion: 10.4.25-MariaDB
-- PHP-version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `api_quiz`
--

--
-- Dumpning av Data i tabell `questions`
--

INSERT INTO `questions` (`id`, `created_at`, `updated_at`, `the_question`, `alt_1`, `alt_2`, `alt_3`, `alt_4`, `the_answer`) VALUES
(1, NULL, NULL, 'What is the name of the famous battle between Hannibal and the roman consuls Lucius and Gaius?', ' Battle of Trebia', 'Battle of the Alps', 'Battle of Cannae', 'Battle of Rome', 3),
(2, NULL, NULL, 'What does the m in E = mc² stands for?', 'Molecules', 'Mass', 'Mole', 'Metre', 2),
(3, NULL, NULL, 'What kind of kitchen appliance is a salamander?', 'a special kind of oven', 'a pasta machine', 'a restaurant graded dishwasher', 'a mixer', 1),
(4, NULL, NULL, 'Who did a big contribution to crack the nazi cypher enigma?', 'Gustav Fröding', 'Bill Tutte', 'William Tritton', 'Alan Turing', 4);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
