-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Sep 01, 2023 at 06:40 AM
-- Server version: 5.7.39
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `capstoneprojectlaravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `account_id` int(255) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `default_password` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `password_changed` varchar(255) NOT NULL DEFAULT 'false',
  `type` varchar(255) NOT NULL,
  `verification_code` int(11) DEFAULT NULL,
  `first_login` varchar(255) DEFAULT 'true',
  `status` varchar(255) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `email`, `default_password`, `password`, `password_changed`, `type`, `verification_code`, `first_login`, `status`) VALUES
(1, 'admin1@adamson.edu.ph', 'DEFAULT', 'qJN2FkQk', 'false', 'AD', 8339, NULL, 'active'),
(2, 'admin2@adamson.edu.ph', 'DEFAULT', NULL, 'false', 'AD', 8632, NULL, 'active'),
(3, 'admin3@adamson.edu.ph', 'DEFAULT', NULL, 'false', 'AD', NULL, NULL, 'active'),
(4, 'admin4@adamson.edu.ph', 'DEFAULT', NULL, 'false', 'AD', NULL, NULL, 'active'),
(5, 'maori.trixia.leonardo@adamson.edu.ph', 'DjfAMg04', 'xrEbWti0', 'false', 'IS', 6015, 'false', 'active'),
(6, 'irish.mae.ong@adamson.edu.ph', 'gno3enkl', NULL, 'false', 'PE', 9651, 'false', 'active'),
(7, 'angelyne.tan@adamson.edu.ph', 'a8sGjYbz', NULL, 'false', 'PE', 8478, 'true', 'active'),
(11, 'kyle.martin.roperez@adamson.edu.ph', 'HyfBvjHM', NULL, 'false', 'CE', 9627, 'true', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `appraisals_2023_2024`
--

CREATE TABLE `appraisals_2023_2024` (
  `appraisal_id` bigint(20) UNSIGNED NOT NULL,
  `evaluation_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` int(11) NOT NULL,
  `evaluator_id` int(11) DEFAULT NULL,
  `date_submitted` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appraisals_2023_2024`
--

INSERT INTO `appraisals_2023_2024` (`appraisal_id`, `evaluation_type`, `employee_id`, `evaluator_id`, `date_submitted`) VALUES
(1, 'self evaluation', 6, 6, NULL),
(2, 'is evaluation', 6, NULL, NULL),
(3, 'internal customer 1', 6, 11, NULL),
(4, 'internal customer 2', 6, NULL, NULL),
(5, 'self evaluation', 7, 7, NULL),
(6, 'is evaluation', 7, NULL, NULL),
(7, 'internal customer 1', 7, NULL, NULL),
(8, 'internal customer 2', 7, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `appraisal_answers_2023_2024`
--

CREATE TABLE `appraisal_answers_2023_2024` (
  `appraisal_answer_id` bigint(20) UNSIGNED NOT NULL,
  `appraisal_id` int(11) DEFAULT NULL,
  `kra_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments_2023_2024`
--

CREATE TABLE `comments_2023_2024` (
  `comment_id` bigint(20) UNSIGNED NOT NULL,
  `appraisal_id` int(11) NOT NULL,
  `customer_service` text COLLATE utf8mb4_unicode_ci,
  `suggestion` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(255) UNSIGNED NOT NULL,
  `department_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`) VALUES
(6, 'ACCOUNTANCY DEPARTMENT'),
(87, 'ACCOUNTING OFFICE'),
(60, 'ADMISSION AND STUDENT RECRUITMENT'),
(1, 'BED-GRADE SCHOOL AND JHS (OFFICE)'),
(2, 'BED-GRADE SCHOOL AND JUNIOR HIGH SCHOOL'),
(3, 'BED-SENIOR HIGH SCHOOL'),
(4, 'BED-SENIOR HIGH SCHOOL (OFFICE)'),
(38, 'BIOLOGY DEPARTMENT'),
(39, 'BIOLOGY LABORATORY'),
(91, 'BUDGET OFFICE'),
(99, 'CAMPUS MINISTRY'),
(82, 'CAMPUS SECURITY AND SAFETY'),
(88, 'CASH MANAGEMENT OFFICE'),
(7, 'CBA-GRADUATE SCHOOL'),
(14, 'CELA-GRADUATE SCHOOL'),
(72, 'CENTER FOR HEALTH SERVICES'),
(61, 'CENTER FOR INNOVATIVE LEARNING'),
(62, 'CENTER FOR RESEARCH AND DEVELOPMENT (CRD)'),
(22, 'CHEMICAL ENGINEERING DEPARTMENT'),
(40, 'CHEMISTRY DEPARTMENT'),
(41, 'CHEMISTRY LABORATORY'),
(23, 'CIVIL ENGINEERING DEPARTMENT'),
(24, 'COE-GRADUATE SCHOOL'),
(8, 'COLLEGE OF BUSINESS ADMINISTRATION'),
(15, 'COLLEGE OF EDUCATION AND LIBERAL ARTS'),
(25, 'COLLEGE OF ENGINEERING'),
(16, 'COMMUNICATION DEPARTMENT'),
(26, 'COMPUTER ENGINEERING DEPARTMENT'),
(43, 'COMPUTER SCIENCE DEPARTMENT'),
(63, 'CONTINUING PROFESSIONAL DEVELOPMENT DEPARTMENT'),
(90, 'CONTROLLER\'S OFFICE'),
(44, 'COS-GRADUATE SCHOOL'),
(100, 'CULTURAL AFFAIRS OFFICE'),
(9, 'CUSTOMS ADMINISTRATION DEPARTMENT'),
(5, 'DEAN\'S OFFICE - COLLEGE OF ARCHITECTURE'),
(10, 'DEAN\'S OFFICE - COLLEGE OF BUSINESS ADMINISTRATION'),
(17, 'DEAN\'S OFFICE - COLLEGE OF EDUCATION & LIBERAL ARTS'),
(27, 'DEAN\'S OFFICE - COLLEGE OF ENGINEERING'),
(35, 'DEAN\'S OFFICE - COLLEGE OF LAW'),
(36, 'DEAN\'S OFFICE - COLLEGE OF NURSING'),
(37, 'DEAN\'S OFFICE - COLLEGE OF PHARMACY'),
(42, 'DEAN\'S OFFICE - COLLEGE OF SCIENCE'),
(18, 'EDUCATION DEPARTMENT'),
(28, 'ELECTRICAL ENGINEERING DEPARTMENT'),
(29, 'ELECTRONICS ENGINEERING DEPARTMENT'),
(30, 'ENGINEERING LAB.'),
(11, 'FINANCE & ECONOMICS DEPARTMENT'),
(89, 'GENERAL ACCOUNTING'),
(31, 'GEOLOGY DEPARTMENT'),
(102, 'GUIDANCE-CAREER AND PLACEMENT'),
(101, 'GUIDANCE, COUNSELING, TESTING & PLACEMENT- BED'),
(12, 'HOSPITALITY MANAGEMENT DEPARTMENT'),
(73, 'HRMDO-PEOPLE AND ORGANIZATIONAL DEVELOPMENT'),
(74, 'HRMDO-RECORDS AND INFORMATION MANAGEMENT'),
(75, 'HRMDO-RECRUITMENT AND PLACEMENT'),
(76, 'HRMDO-SALARIES AND BENEFITS'),
(32, 'INDUSTRIAL ENGINEERING DEPARTMENT'),
(45, 'INFORMATION TECHNOLOGY AND INFORMATION SYSTEMS'),
(50, 'INNOVATION TECHNOLOGY SUPPORT OFFICE (ITSO)'),
(49, 'INSTITUTE OF RELIGIOUS EDUCATION (IRED)'),
(81, 'INSTITUTIONAL DEVELOPMENT AND EXTERNAL AFFAIRS'),
(53, 'INSTITUTIONAL PLANNING AND POLICY DEVELOPMENT'),
(103, 'INTEGRATED COMMUNITY EXTENSION SERVICES (ICES)'),
(54, 'INTERNAL AUDIT'),
(77, 'ITC-INFORMATION TECHNOLOGY CENTER'),
(78, 'ITC-NETWORK INFRASTRUCTURE'),
(79, 'ITC-SYSTEMS DEVELOPMENT'),
(80, 'ITC-SYSTEMS MAINTENANCE'),
(51, 'ITSO-INTELLECTUAL PROPERTY'),
(52, 'ITSO-VIGORMIN TECHNOLOGY'),
(19, 'LANGUAGES DEPARTMENT'),
(65, 'LIBRARY SERVICES'),
(66, 'LIBRARY SERVICES - READERS SERVICES'),
(13, 'MANAGEMENT AND MARKETING DEPARTMENT'),
(46, 'MATH AND PHYSICS DEPARTMENT'),
(33, 'MECHANICAL ENGINEERING DEPARTMENT'),
(34, 'MGC DEPARTMENT'),
(104, 'OAD-ATHLETES\' ELIGIBILITY AND INTERNAL OPERATIONS'),
(96, 'OFFICE FOR ALLIED SERVICES (OAS)'),
(55, 'OFFICE FOR INSTITUTIONAL ADVANCEMENT (OIA)'),
(64, 'OFFICE FOR PROGRAMS AND STANDARDS (OPS)'),
(56, 'OFFICE FOR UNIVERSITY RELATIONS (OUR)'),
(57, 'OFFICE OF THE PRESIDENT'),
(59, 'OFFICE OF THE UNIVERSITY LEGAL COUNSEL'),
(97, 'OFFICE OF THE VICE PRESIDENT FOR FINANCE'),
(107, 'OFFICE OF THE VP FOR STUDENT AFFAIRS'),
(105, 'OSA-OFFICE FOR STUDENT AFFAIRS SCHOLARSHIP'),
(106, 'OSA-STUDENT DEVELOPMENT'),
(83, 'PFGSO-BLDG. MAINTENANCE & OPERATIONS (ELECTRICAL)'),
(84, 'PFGSO-BLDG. MAINTENANCE & OPERATIONS (MECH. WORKS)'),
(85, 'PFGSO-BLDG. MAINTENANCE & OPERATIONS(CIVIL WORKS)'),
(86, 'PFGSO-HOUSEKEEPING'),
(20, 'PHYSICAL EDUCATION DEPARTMENT'),
(47, 'PHYSICS LABORATORY'),
(48, 'PSYCHOLOGY DEPARTMENT'),
(98, 'PURCHASING'),
(68, 'REGISTRAR - SCHEDULING AND ENROLLMENT'),
(69, 'REGISTRAR - STUDENT RECORDS MGT. AND EVALUATION'),
(67, 'REGISTRAR\'S OFFICE'),
(21, 'SOCIAL SCIENCE DEPARTMENT'),
(108, 'ST. VINCENT SCHOOL OF THEOLOGY'),
(93, 'STUDENT\'S ACCOUNTS'),
(109, 'SVST-PASTORAL SERVICES'),
(110, 'SVST-PHILOSOPHY PROGRAM'),
(111, 'SVST-TREASURY'),
(95, 'TREASURER\'S OFFICE'),
(58, 'UNIVERSITY INFORMATION SECURITY OFFICE (UISO)'),
(71, 'VPA OFFICE'),
(70, 'VPAA OFFICE'),
(94, 'WAREHOUSE INVENTORY AND CONTROL SECTION');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(255) UNSIGNED NOT NULL,
  `account_id` int(255) UNSIGNED NOT NULL,
  `employee_number` int(11) NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` int(255) UNSIGNED DEFAULT NULL,
  `job_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `immediate_superior_id` int(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `account_id`, `employee_number`, `first_name`, `last_name`, `department_id`, `job_title`, `position`, `immediate_superior_id`) VALUES
(1, 1, 202011111, 'Administrator', 'One', NULL, NULL, NULL, NULL),
(2, 2, 202011222, 'Administrator', 'Two', NULL, NULL, NULL, NULL),
(3, 3, 202011333, 'Administrator', 'Three', NULL, NULL, NULL, NULL),
(4, 4, 202011444, 'Administrator', 'Four', NULL, NULL, NULL, NULL),
(5, 5, 202011989, 'Maori Trixia', 'Leonardo', 72, NULL, 'Developer', NULL),
(6, 6, 202011988, 'Irish Mae', 'Ong', 72, 'Developer', NULL, NULL),
(7, 7, 202010007, 'Angelyne', 'Tan', 72, NULL, NULL, NULL),
(11, 11, 202011987, 'Kyle Martin', 'Roperez', 72, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_years`
--

CREATE TABLE `evaluation_years` (
  `eval_id` int(11) NOT NULL,
  `sy_start` year(4) NOT NULL,
  `sy_end` year(4) NOT NULL,
  `kra_start` date NOT NULL,
  `kra_end` date NOT NULL,
  `pr_start` date NOT NULL,
  `pr_end` date NOT NULL,
  `eval_start` date NOT NULL,
  `eval_end` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `evaluation_years`
--

INSERT INTO `evaluation_years` (`eval_id`, `sy_start`, `sy_end`, `kra_start`, `kra_end`, `pr_start`, `pr_end`, `eval_start`, `eval_end`, `status`, `updated_at`, `created_at`) VALUES
(1, 2023, 2024, '2023-08-30', '2023-09-02', '2023-09-05', '2023-09-08', '2023-09-11', '2023-09-14', 'active', '2023-08-30 06:52:30', '2023-08-30 06:52:30');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `form_questions`
--

CREATE TABLE `form_questions` (
  `question_id` int(11) NOT NULL,
  `form_type` enum('appraisal','internal customer') DEFAULT NULL,
  `table_initials` varchar(255) DEFAULT NULL,
  `question` varchar(255) DEFAULT NULL,
  `question_order` int(11) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `form_questions`
--

INSERT INTO `form_questions` (`question_id`, `form_type`, `table_initials`, `question`, `question_order`, `status`, `updated_at`, `created_at`) VALUES
(1, 'internal customer', 'IC', 'Understands who the customer is.', 1, 'active', '2023-05-29 15:29:54', '2023-05-29 14:03:18'),
(2, 'internal customer', 'IC', 'Are available to meet the customer personally.', 2, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(3, 'internal customer', 'IC', 'Exhibits clear understanding of the office’s issues.', 3, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(4, 'internal customer', 'IC', 'Provides a prompt and helpful service to meet the customer needs and expectations.', 4, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(5, 'internal customer', 'IC', 'Projects a courteous and professional image when dealing with the customers.', 5, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(6, 'internal customer', 'IC', 'Corrects problems promptly and puts the customer first.', 6, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(7, 'internal customer', 'IC', 'Provides accurate and helpful information to meet customers’ needs.', 7, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(8, 'internal customer', 'IC', 'Explains reasons why things can’t be done in a particular way.', 8, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(9, 'internal customer', 'IC', 'Treats requests and/or concerns with confidentiality.', 9, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(10, 'internal customer', 'IC', 'Responds to queries and concerns in a timely manner.', 10, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(11, 'internal customer', 'IC', 'Regularly checks out whether customers are satisfied and their expectations are met.', 11, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(12, 'internal customer', 'IC', 'Is proactive and goes \"the extra mile\" to help meet a customer’s needs.', 12, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(13, 'internal customer', 'IC', 'Is happy to receive customer feedback.', 13, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(14, 'internal customer', 'IC', 'Owns customer issues and takes an action for solution.', 14, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(15, 'internal customer', 'IC', 'Presents himself in a professional manner with regard to attire, personal grooming, and appearance.', 15, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(16, 'appraisal', 'SID', 'Sets goals for oneself in the pursuit of excellence.', 1, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(17, 'appraisal', 'SID', 'Attends training programs to improve.', 2, 'inactive', '2023-05-29 09:58:34', '2023-05-29 14:03:18'),
(18, 'appraisal', 'SID', 'Shows patience and tact when dealing with superiors, colleagues, students, and clients.', 3, 'inactive', '2023-05-29 10:30:41', '2023-05-29 14:03:18'),
(19, 'appraisal', 'SID', 'Conforms to standard operating procedures.', 4, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(20, 'appraisal', 'SID', 'Continuously applies specific changes/improvements in one\'s work methods for quality outputs.', 5, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(21, 'appraisal', 'SID', 'Demonstrates the ability to accomplish tasks according to deadlines and quality set despite minimal resources.', 6, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(22, 'appraisal', 'SID', 'Strictly uses time for work. Reports and starts work on time.', 7, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(23, 'appraisal', 'SID', 'Attains and maintains sound housekeeping practices through 5S compliance.', 8, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(24, 'appraisal', 'SID', 'Maintains a professional image through good grooming and demonstrates pleasant customer service skills.', 9, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(25, 'appraisal', 'SID', 'Demonstrates the ability to communicate effectively whether orally or written.', 10, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(26, 'appraisal', 'SR', 'Attends and participates in all activities organized by the Integrated Community and Extension Services (ICES) as an expression of Vincentian and the University mission.', 1, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(27, 'appraisal', 'SR', 'Participates in liturgical/religious/spiritual activities of the University community (retreats/recollections).', 2, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(28, 'appraisal', 'SR', 'Participates in civic activities conducted in the community to which one belongs.', 3, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(29, 'appraisal', 'SR', 'Avoids situations and actions considered inappropriate or which present a conflict of interest.', 4, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(30, 'appraisal', 'SR', 'Manifests observable positive changes in one\'s behavior as a member of an organization.', 5, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(31, 'appraisal', 'SR', 'Volunteers time and shares resources during times of emergency.', 6, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(32, 'appraisal', 'SR', 'Shows respect and treats student assistants well.', 7, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(33, 'appraisal', 'SR', 'Shows sensitivity to the needs of one\'s colleagues.', 8, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(34, 'appraisal', 'SR', 'Readily accepts criticisms and shows willingness to change.', 9, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(35, 'appraisal', 'SR', 'Shows devotion to St. Vincent de Paul and the Vincentian saints as well as Our Lady of the Miraculous Medal by regularly attending liturgical/religious celebrations in their honor.', 10, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(36, 'appraisal', 'S', 'Acts with a sense of urgency and responsibility to meet the organization\'s needs and objectives.', 1, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(37, 'appraisal', 'S', 'Shows willingness to do things beyond assigned tasks. (To go the \"extra mile\" in doing service for the University and to others.)', 2, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(38, 'appraisal', 'S', 'Shows respect for the university properties e.g. office supplies, furniture, facilities that belong to the University.', 3, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(39, 'appraisal', 'S', 'Participates and inspires others to participate enthusiastically in activities being undertaken to improve the entire organization.', 4, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(40, 'appraisal', 'S', 'Follows and enjoins others to uphold the University\'s policies, guidelines set for compliance as a member of the Adamson University community.', 5, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(41, 'appraisal', 'S', 'Respects the diversity of cultures, values, perspectives, and beliefs.', 6, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(42, 'appraisal', 'S', 'Promotes collaboration and cooperation and removes barriers towards the attainment of organizational goals.', 7, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(43, 'appraisal', 'S', 'Resolves conflict with colleagues in a win-win situation.', 8, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(44, 'appraisal', 'S', 'Communicates messages and gives accurate information in a simple and easily understood way.', 9, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(45, 'appraisal', 'S', 'Keep confidential matters such as information, data, documents and records.', 10, 'active', '2023-05-29 14:02:47', '2023-05-29 14:03:18'),
(82, 'internal customer', 'IC', 'EDIT Q DAW', 16, 'inactive', '2023-08-15 06:17:32', '2023-05-29 12:00:06'),
(83, 'internal customer', 'IC', 'HATDOG', 17, 'inactive', '2023-08-15 06:17:34', '2023-05-29 12:00:54'),
(84, 'appraisal', 'SID', 'Test Question 9.', 18, 'inactive', '2023-08-15 05:48:26', '2023-08-15 05:46:08'),
(85, 'appraisal', 'SID', 'Test Question 10.', 19, 'inactive', '2023-08-15 05:48:28', '2023-08-15 05:46:16'),
(86, 'appraisal', 'SR', 'Test', 20, 'inactive', '2023-08-15 05:48:30', '2023-08-15 05:46:20'),
(87, 'appraisal', 'S', 'Test', 21, 'inactive', '2023-08-15 05:48:32', '2023-08-15 05:46:24');

-- --------------------------------------------------------

--
-- Table structure for table `form_questions_2023_2024`
--

CREATE TABLE `form_questions_2023_2024` (
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `form_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_initials` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_order` int(11) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `form_questions_2023_2024`
--

INSERT INTO `form_questions_2023_2024` (`question_id`, `form_type`, `table_initials`, `question`, `question_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'internal customer', 'IC', 'Understands who the customer is.', 1, 'active', '2023-05-29 14:03:18', '2023-05-29 15:29:54'),
(2, 'internal customer', 'IC', 'Are available to meet the customer personally.', 2, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(3, 'internal customer', 'IC', 'Exhibits clear understanding of the office’s issues.', 3, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(4, 'internal customer', 'IC', 'Provides a prompt and helpful service to meet the customer needs and expectations.', 4, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(5, 'internal customer', 'IC', 'Projects a courteous and professional image when dealing with the customers.', 5, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(6, 'internal customer', 'IC', 'Corrects problems promptly and puts the customer first.', 6, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(7, 'internal customer', 'IC', 'Provides accurate and helpful information to meet customers’ needs.', 7, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(8, 'internal customer', 'IC', 'Explains reasons why things can’t be done in a particular way.', 8, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(9, 'internal customer', 'IC', 'Treats requests and/or concerns with confidentiality.', 9, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(10, 'internal customer', 'IC', 'Responds to queries and concerns in a timely manner.', 10, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(11, 'internal customer', 'IC', 'Regularly checks out whether customers are satisfied and their expectations are met.', 11, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(12, 'internal customer', 'IC', 'Is proactive and goes \"the extra mile\" to help meet a customer’s needs.', 12, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(13, 'internal customer', 'IC', 'Is happy to receive customer feedback.', 13, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(14, 'internal customer', 'IC', 'Owns customer issues and takes an action for solution.', 14, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(15, 'internal customer', 'IC', 'Presents himself in a professional manner with regard to attire, personal grooming, and appearance.', 15, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(16, 'appraisal', 'SID', 'Sets goals for oneself in the pursuit of excellence.', 1, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(17, 'appraisal', 'SID', 'Attends training programs to improve.', 2, 'inactive', '2023-05-29 14:03:18', '2023-05-29 09:58:34'),
(18, 'appraisal', 'SID', 'Shows patience and tact when dealing with superiors, colleagues, students, and clients.', 3, 'inactive', '2023-05-29 14:03:18', '2023-05-29 10:30:41'),
(19, 'appraisal', 'SID', 'Conforms to standard operating procedures.', 4, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(20, 'appraisal', 'SID', 'Continuously applies specific changes/improvements in one\'s work methods for quality outputs.', 5, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(21, 'appraisal', 'SID', 'Demonstrates the ability to accomplish tasks according to deadlines and quality set despite minimal resources.', 6, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(22, 'appraisal', 'SID', 'Strictly uses time for work. Reports and starts work on time.', 7, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(23, 'appraisal', 'SID', 'Attains and maintains sound housekeeping practices through 5S compliance.', 8, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(24, 'appraisal', 'SID', 'Maintains a professional image through good grooming and demonstrates pleasant customer service skills.', 9, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(25, 'appraisal', 'SID', 'Demonstrates the ability to communicate effectively whether orally or written.', 10, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(26, 'appraisal', 'SR', 'Attends and participates in all activities organized by the Integrated Community and Extension Services (ICES) as an expression of Vincentian and the University mission.', 1, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(27, 'appraisal', 'SR', 'Participates in liturgical/religious/spiritual activities of the University community (retreats/recollections).', 2, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(28, 'appraisal', 'SR', 'Participates in civic activities conducted in the community to which one belongs.', 3, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(29, 'appraisal', 'SR', 'Avoids situations and actions considered inappropriate or which present a conflict of interest.', 4, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(30, 'appraisal', 'SR', 'Manifests observable positive changes in one\'s behavior as a member of an organization.', 5, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(31, 'appraisal', 'SR', 'Volunteers time and shares resources during times of emergency.', 6, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(32, 'appraisal', 'SR', 'Shows respect and treats student assistants well.', 7, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(33, 'appraisal', 'SR', 'Shows sensitivity to the needs of one\'s colleagues.', 8, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(34, 'appraisal', 'SR', 'Readily accepts criticisms and shows willingness to change.', 9, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(35, 'appraisal', 'SR', 'Shows devotion to St. Vincent de Paul and the Vincentian saints as well as Our Lady of the Miraculous Medal by regularly attending liturgical/religious celebrations in their honor.', 10, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(36, 'appraisal', 'S', 'Acts with a sense of urgency and responsibility to meet the organization\'s needs and objectives.', 1, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(37, 'appraisal', 'S', 'Shows willingness to do things beyond assigned tasks. (To go the \"extra mile\" in doing service for the University and to others.)', 2, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(38, 'appraisal', 'S', 'Shows respect for the university properties e.g. office supplies, furniture, facilities that belong to the University.', 3, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(39, 'appraisal', 'S', 'Participates and inspires others to participate enthusiastically in activities being undertaken to improve the entire organization.', 4, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(40, 'appraisal', 'S', 'Follows and enjoins others to uphold the University\'s policies, guidelines set for compliance as a member of the Adamson University community.', 5, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(41, 'appraisal', 'S', 'Respects the diversity of cultures, values, perspectives, and beliefs.', 6, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(42, 'appraisal', 'S', 'Promotes collaboration and cooperation and removes barriers towards the attainment of organizational goals.', 7, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(43, 'appraisal', 'S', 'Resolves conflict with colleagues in a win-win situation.', 8, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(44, 'appraisal', 'S', 'Communicates messages and gives accurate information in a simple and easily understood way.', 9, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(45, 'appraisal', 'S', 'Keep confidential matters such as information, data, documents and records.', 10, 'active', '2023-05-29 14:03:18', '2023-05-29 14:02:47'),
(82, 'internal customer', 'IC', 'EDIT Q DAW', 16, 'inactive', '2023-05-29 12:00:06', '2023-08-15 06:17:32'),
(83, 'internal customer', 'IC', 'HATDOG', 17, 'inactive', '2023-05-29 12:00:54', '2023-08-15 06:17:34'),
(84, 'appraisal', 'SID', 'Test Question 9.', 18, 'inactive', '2023-08-15 05:46:08', '2023-08-15 05:48:26'),
(85, 'appraisal', 'SID', 'Test Question 10.', 19, 'inactive', '2023-08-15 05:46:16', '2023-08-15 05:48:28'),
(86, 'appraisal', 'SR', 'Test', 20, 'inactive', '2023-08-15 05:46:20', '2023-08-15 05:48:30'),
(87, 'appraisal', 'S', 'Test', 21, 'inactive', '2023-08-15 05:46:24', '2023-08-15 05:48:32');

-- --------------------------------------------------------

--
-- Table structure for table `job_incumbents_2023_2024`
--

CREATE TABLE `job_incumbents_2023_2024` (
  `job_incumbent_id` bigint(20) UNSIGNED NOT NULL,
  `appraisal_id` int(11) NOT NULL,
  `job_incumbent_question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci,
  `comments` int(11) DEFAULT NULL,
  `question_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kras_2023_2024`
--

CREATE TABLE `kras_2023_2024` (
  `kra_id` bigint(20) UNSIGNED NOT NULL,
  `appraisal_id` int(11) NOT NULL,
  `kra` text COLLATE utf8mb4_unicode_ci,
  `kra_weight` decimal(8,2) DEFAULT NULL,
  `objective` text COLLATE utf8mb4_unicode_ci,
  `performance_indicator` text COLLATE utf8mb4_unicode_ci,
  `actual_result` text COLLATE utf8mb4_unicode_ci,
  `weighted_total` decimal(8,2) DEFAULT NULL,
  `kra_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `learning_development_plans_2023_2024`
--

CREATE TABLE `learning_development_plans_2023_2024` (
  `development_plan_id` bigint(20) UNSIGNED NOT NULL,
  `appraisal_id` int(11) NOT NULL,
  `learning_need` text COLLATE utf8mb4_unicode_ci,
  `methodology` text COLLATE utf8mb4_unicode_ci,
  `development_plan_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(4, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(5, '2019_08_19_000000_create_failed_jobs_table', 1),
(6, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(7, '2023_05_22_174608_modify_accounts_table', 2),
(8, '2023_05_22_181540_modify_accounts', 3),
(9, '2023_05_28_052649_drop_table_employees', 4),
(10, '2023_05_28_052829_drop_table_employees', 5),
(11, '2023_05_28_052927_create_employees_table', 6),
(12, '2023_05_28_053125_add_fk_employees', 7),
(13, '2023_05_28_055350_drop_employee_number', 8),
(14, '2023_05_28_081108_create_departments_table', 9),
(15, '2023_05_28_081657_create_departments_table2', 10);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `signature_2023_2024`
--

CREATE TABLE `signature_2023_2024` (
  `signature_id` bigint(20) UNSIGNED NOT NULL,
  `appraisal_id` int(11) NOT NULL,
  `sign_data` blob NOT NULL,
  `sign_type` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `work_performance_plans_2023_2024`
--

CREATE TABLE `work_performance_plans_2023_2024` (
  `performance_plan_id` bigint(20) UNSIGNED NOT NULL,
  `appraisal_id` int(11) NOT NULL,
  `continue_doing` text COLLATE utf8mb4_unicode_ci,
  `stop_doing` text COLLATE utf8mb4_unicode_ci,
  `start_doing` text COLLATE utf8mb4_unicode_ci,
  `performance_plan_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `accounts_email_unique` (`email`);

--
-- Indexes for table `appraisals_2023_2024`
--
ALTER TABLE `appraisals_2023_2024`
  ADD PRIMARY KEY (`appraisal_id`);

--
-- Indexes for table `appraisal_answers_2023_2024`
--
ALTER TABLE `appraisal_answers_2023_2024`
  ADD PRIMARY KEY (`appraisal_answer_id`);

--
-- Indexes for table `comments_2023_2024`
--
ALTER TABLE `comments_2023_2024`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `departments_department_name_unique` (`department_name`),
  ADD UNIQUE KEY `department_name` (`department_name`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `employees_account_id_unique` (`account_id`),
  ADD UNIQUE KEY `employees_employee_number_unique` (`employee_number`),
  ADD KEY `employees_immediate_superior_id_foreign` (`immediate_superior_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `evaluation_years`
--
ALTER TABLE `evaluation_years`
  ADD PRIMARY KEY (`eval_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `form_questions`
--
ALTER TABLE `form_questions`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `form_questions_2023_2024`
--
ALTER TABLE `form_questions_2023_2024`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `job_incumbents_2023_2024`
--
ALTER TABLE `job_incumbents_2023_2024`
  ADD PRIMARY KEY (`job_incumbent_id`);

--
-- Indexes for table `kras_2023_2024`
--
ALTER TABLE `kras_2023_2024`
  ADD PRIMARY KEY (`kra_id`);

--
-- Indexes for table `learning_development_plans_2023_2024`
--
ALTER TABLE `learning_development_plans_2023_2024`
  ADD PRIMARY KEY (`development_plan_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `signature_2023_2024`
--
ALTER TABLE `signature_2023_2024`
  ADD PRIMARY KEY (`signature_id`);

--
-- Indexes for table `work_performance_plans_2023_2024`
--
ALTER TABLE `work_performance_plans_2023_2024`
  ADD PRIMARY KEY (`performance_plan_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `account_id` int(255) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `appraisals_2023_2024`
--
ALTER TABLE `appraisals_2023_2024`
  MODIFY `appraisal_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `appraisal_answers_2023_2024`
--
ALTER TABLE `appraisal_answers_2023_2024`
  MODIFY `appraisal_answer_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments_2023_2024`
--
ALTER TABLE `comments_2023_2024`
  MODIFY `comment_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(255) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(255) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `evaluation_years`
--
ALTER TABLE `evaluation_years`
  MODIFY `eval_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `form_questions`
--
ALTER TABLE `form_questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `form_questions_2023_2024`
--
ALTER TABLE `form_questions_2023_2024`
  MODIFY `question_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `job_incumbents_2023_2024`
--
ALTER TABLE `job_incumbents_2023_2024`
  MODIFY `job_incumbent_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kras_2023_2024`
--
ALTER TABLE `kras_2023_2024`
  MODIFY `kra_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learning_development_plans_2023_2024`
--
ALTER TABLE `learning_development_plans_2023_2024`
  MODIFY `development_plan_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `signature_2023_2024`
--
ALTER TABLE `signature_2023_2024`
  MODIFY `signature_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `work_performance_plans_2023_2024`
--
ALTER TABLE `work_performance_plans_2023_2024`
  MODIFY `performance_plan_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`),
  ADD CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  ADD CONSTRAINT `employees_ibfk_3` FOREIGN KEY (`immediate_superior_id`) REFERENCES `employees` (`account_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
