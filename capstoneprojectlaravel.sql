-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Aug 27, 2023 at 04:45 PM
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
(1, 'admin1@adamson.edu.ph', 'DEFAULT', NULL, 'false', 'AD', 8339, NULL, 'active'),
(2, 'admin2@adamson.edu.ph', 'DEFAULT', NULL, 'false', 'AD', 8632, NULL, 'active'),
(3, 'admin3@adamson.edu.ph', 'DEFAULT', NULL, 'false', 'AD', NULL, NULL, 'active'),
(4, 'admin4@adamson.edu.ph', 'DEFAULT', NULL, 'false', 'AD', NULL, NULL, 'active'),
(5, 'maori.trixia.leonardo@adamson.edu.ph', 'DEFAULT', '$2y$10$IzCSE5/2BM4DFI6uOX2ifO9u.E5p9seMI/86A/ygb89/5cqcVG38K', 'false', 'IS', 9481, 'true', 'active'),
(6, 'irish.mae.ong@adamson.edu.ph', 'gno3enkl', NULL, 'false', 'PE', 9533, 'true', 'active'),
(7, 'angelyne.tan@adamson.edu.ph', 'a8sGjYbz', NULL, 'false', 'PE', 8478, 'true', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `appraisals_2023_2024`
--

CREATE TABLE `appraisals_2023_2024` (
  `appraisal_id` bigint(20) UNSIGNED NOT NULL,
  `evaluation_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` int(11) NOT NULL,
  `evaluator_id` int(11) DEFAULT NULL,
  `date_submitted` date DEFAULT NULL,
  `signature` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appraisals_2023_2024`
--

INSERT INTO `appraisals_2023_2024` (`appraisal_id`, `evaluation_type`, `employee_id`, `evaluator_id`, `date_submitted`, `signature`) VALUES
(1, 'self evaluation', 6, 6, NULL, NULL),
(2, 'is evaluation', 6, NULL, NULL, NULL),
(3, 'internal customer', 6, 7, NULL, NULL),
(4, 'internal customer', 6, NULL, NULL, NULL),
(5, 'self evaluation', 7, 7, NULL, NULL),
(6, 'is evaluation', 7, NULL, NULL, NULL),
(7, 'internal customer', 7, 6, '2023-08-27', NULL),
(8, 'internal customer', 7, NULL, NULL, NULL);

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

--
-- Dumping data for table `appraisal_answers_2023_2024`
--

INSERT INTO `appraisal_answers_2023_2024` (`appraisal_answer_id`, `appraisal_id`, `kra_id`, `question_id`, `score`) VALUES
(1, 7, NULL, 1, 5),
(2, 7, NULL, 2, 5),
(3, 7, NULL, 3, 5),
(4, 7, NULL, 4, 5),
(5, 7, NULL, 5, 5),
(6, 7, NULL, 6, 5),
(7, 7, NULL, 7, 5),
(8, 7, NULL, 8, 5),
(9, 7, NULL, 9, 5),
(10, 7, NULL, 10, 5),
(11, 7, NULL, 11, 5),
(12, 7, NULL, 12, 5),
(13, 7, NULL, 13, 5),
(14, 7, NULL, 14, 5),
(15, 7, NULL, 15, 5),
(16, 7, NULL, 82, 5),
(17, 7, NULL, 83, 5);

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

--
-- Dumping data for table `comments_2023_2024`
--

INSERT INTO `comments_2023_2024` (`comment_id`, `appraisal_id`, `customer_service`, `suggestion`) VALUES
(1, 7, 'none', 'none');

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
(5, 5, 202011989, 'Maori Trixia', 'Leonardo', 72, NULL, NULL, NULL),
(6, 6, 202011988, 'Irish Mae', 'Ong', 72, NULL, NULL, NULL),
(7, 7, 202010007, 'Angelyne', 'Tan', 77, NULL, NULL, NULL);

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
(1, 2023, 2024, '2023-08-01', '2023-08-02', '2023-08-03', '2023-08-04', '2023-08-05', '2023-08-06', 'active', '2023-08-27 04:57:49', '2023-08-27 04:57:49');

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
(82, 'internal customer', 'IC', 'EDIT Q DAW', 16, 'active', '2023-05-29 12:00:23', '2023-05-29 12:00:06'),
(83, 'internal customer', 'IC', 'HATDOG', 17, 'active', '2023-05-29 12:00:54', '2023-05-29 12:00:54');

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
(82, 'internal customer', 'IC', 'EDIT Q DAW', 16, 'active', '2023-05-29 12:00:06', '2023-05-29 12:00:23'),
(83, 'internal customer', 'IC', 'HATDOG', 17, 'active', '2023-05-29 12:00:54', '2023-05-29 12:00:54');

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

--
-- Dumping data for table `signature_2023_2024`
--

INSERT INTO `signature_2023_2024` (`signature_id`, `appraisal_id`, `sign_data`, `sign_type`, `created_at`, `updated_at`) VALUES
(1, 7, 0x646174613a696d6167652f706e673b6261736536342c6956424f5277304b47676f414141414e53556845556741414267494141414a4143414d4141414234306f306241414143736c424d564555794d6a494e4451332b2f7635776348442f417741796839496d674f762f2f2f38414141446433643347787362372b2f7657317462713675723039505166487838364f6a713575626c69596d4c4c793876323976595345684c392f66316458563367344f414175763930644851334e7a66352b666d4969496a7036656c5054302f6d3575616c70615875377536506a342b696f7149764c792b5a6d5a6b6864636a45784d516e4a79667938764a71616d716b704b526b5a475459324e675a47526e7a382f50743765314451304e675947437873624851304e424251554647526b6152752b5743676f49314e54574f6a6f3536656e725034665139505433382f507834654867686463745255564841774d44362b76704c53307538764c784d54457a623239746e5a32654b696f70556c646679392f793675727174726133523064484e7a63324467344e7662322b30744c53636e4a7a663339394852306539766232546b354f2f76373956565657516b4a444b79737147686f5a736247796e7036666c35655855314e5443777349704b536e6f364f685a57566b754f542b717a7664396658327171716f744c533232307536596d4a694e6a593173704e306c4a53584477384e786358466f61476a6f387630344f446a6e38506f324e6a62653374376a342b4f416749444733766f794d7a4f327472622f515439656f66442f6759423172764937686447676f4b44302b6635555646536f714b69576c7061616d707258313963784d54467a6333502f4578422f66332b337437645856316336684e45756663344a6f646e6236767a3139665848783866693475494d6c3875686f61456c66655833392f662f4979442f30744755776657656e70372f7773456d6439622f3374372f34754c2f523055684953482f4d7a456d652b416e6373784953456a2f616d6a2f646e542f736241784e547672362b76362f502f2f38764c2f5531482f4c53736e62734971594b41755357722f3775372f596d414373664c2f6b704571565952307164332f6f61442f4351622f2b2f7479636e494943416972792b762f696f6e2f764c762f31645572584a5575506b372f7136722f7a637a753976372f42514c2f4f44622f313963715a4b6857566c596f6136306759486b57506d442f74725747732b502f364f68486a4e542f6d706b7767383236312f6c676e4e722f4752622f392f637062726d646e5a3363334e774b48692b65772b674a6f4e6771585a71666e3538494669494745426f7566634f4875665162674d385a4141424149456c455156523432757a64373273555a774c4138576e654e4437756d7579754a6e4774524c4e62757064774237644a6c435167516245524965453032706a446e71746753596f4254345172314665424b6a323467432b75306864393354643533586633376c36572f6776395632356d6b35684c31485a6e4631636e2b5877513361365a365441377a48666e6d52394752774134704b496a455143486b6751415341414145674341424141674151424941414153414941454143414241456741414249414756414e6131594345674348306c6859507466656c503337575a6c49414854424c7a2b64664d56507637517a7077767a345a39744c63496635384e2b382b4d2b4753514133727258464342755144747a6d676d7230323074776c4a34315a4a5042676d41742b376b7966392b73642f4a6b2b6e6e4d377765517656784f3076514830494c62344545774674497742657665532f745844353747734b6c35624465624d446766795141435943734a4f4435387a322f55696667334e56617146587146356553427453666866426a42776d5943544d536741524131784c77335864376671564d774e63726c304a344e68652f53686f7765796b38584f6e6b4b4344452f796b42534142304b7747767670646d446f3943754c4f7839664a69636d6133656a6e6445757a6233362b464e516c414171427243656873494f687075506279395139682f6c48614a646a653339655878743555425a41416547734a36476767364f763555492b6977584a2f2f486f6a5050777339524a73372b3866684b573642434142304f30457650706569686b4d68346e6b742b617a4962344a70394d767764622b66694f4538434935474668636b51416b414c7159674f65376434553954357541325753335039684d51444573583267334159764a4457466a55663971574f715841435141757065415833635438477661424b794838656a386b39506c433948305246687059776d612b2f7556725875437831666a333130556967524146785077796e7574542f2f345871332f2f4a505a354f58314d502b3466364f74424e5433504353694c67464941475168416666446e6530436e5039582b487630494d794e6837485543586978357746424c795141435942753650517863646643726267416a2b3950726462437848533047756f76776b713950313043396a3872756934425341423051616350692f346d684b657a362f6669372b374c7a34616a6d524447586f534e6d5164704673475451704541794b59377a5431323763374b7a394e522f324a595336377357553133576e6a63767865414245416d445336466961766a3239654372713145632b745050772b665779394941427747307865734135414141416b41514149416b4141414a41414143514241416743514148676e71766c457164334a79304f645442307246487747534143384d38584a537673546c33506c4b43726c696849414573446854454255716b6f415341415a54554178462b2f444336584b3548492b48372b714e504a443554514a714a6169306e4b6a554d336e4735584b5a44457146614a797162556a677a67426865566350706d323268785769704e51794f6472385878544c415a49414c523946424476772b4d2f4b6f3143564c6c53546e6269315262483933635473445559564333463735527a6b3856575a35416b6f46457044355769616e4d4f38655456654d646647457131474341423048594334683176764d644e6472705271646f3851397a69385035324167724e736142694c706b756e7346797156786f6357776f5355436875517a4a2f3732514845636b59304e4a43564973426b6741744a32417146434e39396b3743556a783162755a674751634b553541386b636c2b66342f55536d736c6c6f6377746d5467454c7a59435370535a4941527742494148516c415a5653764d2b754446576141304644725a386654684a517a4a57615a34544c56797052495663735439614b6c537531466d65774a77476c5172494d795968514d6841305650486849414851685151556334573441376b722b555a6c3631364246692f55616434586b507873387457396b4d39506270304d6270356654702b4153694e2f4a56654a536c756e67314d73426b674164486f6f4d506e656a4c792f523473434573424231377763357a336137355a382f5563436f457648414c6e6d42666a76535149716a6336654f51455341494145414341424145674141424941674151414941454153414348785364765a4e32414243414251496f45514961384f5148574461546e4b414441514241414567434142414167415142494141415341494145414341424145674141424941674151414941454153414141456743414241416741514249414141534149414541434142414567414142494167415141494145415341414145674341424141674151415341494145764e3763784e306a4147544d33596e4c6e536467734448774951435a4d394159374451426377327245534362476a39326d494146787741415754304f574f6777415865745134437375747468416f35596851425a64555143414352414167416b514149414a454143414352414167416b514149414a454143414352414167416b4141414a41454143414a41414143514141416b41514149416b4141414a41414143514241416743514141416b4141414a41454143414a41414143514141416b41514149416b4141414a414241416951415141496b414541434a414241416951415141496b414541434a41424141695141514149416b4141414a41414143514241416743514141416b4141414a41454143414a41414143514141416b41514149416b4141414a41414143514241416743514141416b4141414a41454143414a414141416d514141414a6b414141435a414141416d514141414a6b414141435a414141416d514141414a41454143414a41414143514141416b41514149416b4141414a4141414365694b676333624e303566585a77633666487841784a77574249774d4a4b626e786c2f334c756a372b6348667a70714577416b3447416e34506a7473315044332f612b52742f66486e356b4b77416b344d416d344e6a366c37322f6f572f71706945685141494f59674a36626b373139663665692f634d43414553634e4153634c51322b4546764b7934734f5249414a4f41674a574430365a6539726570376f67474142427959424f53766e6469336c782b6557706a6348426c492f764b6a7a647a3836626e2f2f34472b533759475141494f52674b4f7a65774a774c637274646663427a435158393774774b6d537a51475167414f51674a35532f2b37752f345068695a48662b4e45624f32634c54743231505141536b506b454846302f735476366333626b64334c786c7a6e4841594145484a4145624e343674524f416a2b384e74444442774f723264614d66313577544269516777776b3439734d6655702f6937586d324d384871674730436b49434d4a7141783966493630425058507a33543670663630646e746963343162424f414247517a41636647586834417247326d6d584267636676596f576f6f434a434154436267357632644934435a3062545466765658747763414570445a4242782f6553764171647474664a632f63376b356266324d72514b51674b776c59505252623263582b442f596d6e724256674649514c59533850335a6e517542426a2f64624738577061314c5136392f62374d414a43424c43666a33344d3674774c6661336f50334c445162634f6f664e67744141724b54674e3144674f6c4f627537715757672b4b2b4b477a514b51674b776b6f4b6455337a6b456d50317a52334d6133576a65472f4456757a2b6f4f64597a4d754975746177624f6650683855332f4d436b53384662745068426f7574506e4f347a2b6a37327a2f576b69322b4e347072664a6a4e75576f72534643425536433575575a336c71436751494c6f384a724642414b6854427855534d5272307845624d616b34326f2b50526d4e6159536a58424e566b326a7646686a6f74786b4a626c47622b344c592b344c7a66316e376a4c6e6e4b4846516d65476e756d5a3876752b303553424d3533356663373550534a335572673176537643445376674e474a7758666576665932315758416e51494141656e4a6349456541775062667454714f676278517877447158567075744965704f68714e327359397a52304e7a766e6a587073314e4e54636355367731395132395a305864317a4a6e656b32386b37324f4d444d67414142314a54566a416e677535304349324f5272735a31706e4e465254697962613833306f4e555944335a746656634e6c2f48773530306f546b79697075516a344b564151454371456b75426d68507a5638793237423273594530626c6d6e384b51447274635942744d52726539724b6a756e5a44676e3178547133696d486764494a764f6a443032426c514941415774706667562b3074744c55584e42304b373265494f4939454c685235676b6765676f626e494936375a542b47336e7470457246426b5947424169672f614c3567696e4c6e696c5061345877656d6a374273734661675732386636393577517479726675684e667149426c616c7a304d4e675945434b4331592b347377616d674b64787031556d58624d6c4c6a314d4642344946626f684e416a6a4f3934667674484271624c377a6c2f682f4878597a2f7157536a334a43425a774251494141616b36674e6d534b436c4e717275394b46793270544d654b5a502b78304d796946796a36513030794e30394e72647354386b35486f2b4b50596a5136375131313368687150644d653979463370676546585235796c474f6777675145796c5145574a71706542613670553372365a2f537343496261584c426e7676414a4e5948393237683935387644505350327a5972676a494e422b77784835374c3746664b516c4b42424b344f444177494545424c422b78555272364c5636544c6576566645423559774e7a634774666b6b5672667076762b6a6d5a50765a683858322f78357372756f34474d7a67714b754f5746566b4252474167515145765766446f5a4a71316f684753663767764b3635447461686b3770714e672f50676d35743965474135617131566361706763636a4a36496f4e637162685449743867514542614e4632496a7471656c4f386f373073587670382b416e444e7057773847365a6f4d44654238346472576176323156447961686c432b3250754c694d5066393556747a764639586378424f693644755946424169674a4f4932707842617a4b6d533667783062684d6b45324450555359795a687a6a5132554a747638317862505632693971475244533557564c4a4d6d566d4e7232445449423876767a6f4d736643424341744752576f69554e424b44684d5a6d55366f4f76364775493861464745416f5a715355645375443636664c73322b5a567a30766575312b366d5668695555334b6337387337683157415163434243684267466d5a5648676f726c47306c3545792f63325554494473536a596569394c636a5a372f326d414b6f47677459576773577839615751717a646d51434f492b59774c534141414855454941612b6443796c30323678344f7a796c674c49434a76474f6e765675757054496e44376273536c6a7167346c485234635266695465556f355a554a67387037504d414155434141486f4947443547315634653044747a3056544d5841724a58417742576c4a46576e532b456b3463596d4f4e4157487a587336563252724f422b694d417751414151496f4977433138616d695a556b717054633556372b3048467a68494f52504d664e596c4d6358666f326c494f5953365565317853574d63413431424253456a67514f4c76477768744d4b38655a78785241494267454336434841386a386633543444305a47317934394539666f4b694f6c67694142787077417046424359327562744e6f336853343078736b5632394f416b4a39746d787a4a3143434342414341414342424146514764484f58554757516264454d41435153775241412b362b45334761483269667074474f39705650724d5458685a36583948454a41674a516958486435586854676343414143674141425642474133317737766478793543485143774634366c6e4a68527932486778487148646a4f326875722b6147664b3168397362673446333774376c6633626a547335724d4a564f2f354d336a42756373594652416749416b43486877557a7343384347415a72764a7349344949444e764169772b484b365a44576342626d4a4b79783433307664505a3472484f7151433966644f33706e3333546d77385777547559465871324c754f796c38317339394341495a467747664872313472525542576258304b366a4b3958755a722f765a4c6955535132337a472b72444c716f76707833464137547547654b56714d4f706e58626c35374c537470335343427345434e672b417436756d702f79627a5569594c794b767358554451487255794a5a54694d557663333275426b7767327150416c4c365a574f664d5a7a6b77396d717a626b384a62494d576f4f434141464a4566446b6e586d4a66366b4e41666951546e657a705263435445656342746b38357355506652464731423046447667455a374e42356d65524d5251714470715632597831397743426d45624138714f2f54674850744346673675653156363264726a6e52437748645a4b5469425166726a346c6c3676766634385a462b6b625633422b586142674879573769426c4b63627841687464306465574250514941414259366742664d622f70556d424c516531364f4956693845584d536d59394159376f4f434d37577872614f6462634d5a5741534c65342b6f6363334e7472445633776b4559687742356c587a4b722b734351476f30795474486a503364554741593878754f4164797161636c4e6a2b6f646972546375446c62434446726a6e546f52595945414d43424b6843774b4d6e58336c744755464475737a3144656942414c6b31334b4368516f69755858466a6746754b3932585332794133656c4d38386c482b435467446741414253684877636f482f72416b42714d4d373956622b4152312b6939784f594d42687445664734546b524778566f4358526e7a4e766774617664305a502b547530514277414241705169775079436636344a4158323639504245346230657171615a62423450317876526e353456644c767a317947514d596b777046755438673645754a56457955774554416b49454b4143415a6530494b4467724c527637715339792b32686a77437965545475634b6c644d586d695855637a6f694a4b4874777a59464835453765674f7a5149454b4163415a6634524e486735416a496b2f4b764779597033784c554b5a54713847433865525432474c6966674b55764a6a5463324e39712b4664686c6843676f306a7061516a486378707a774a43414141484b4566434f583133516767446b4236492b32423252686d625448727835724b6f7a646b4a4e784c506551716a464b6872384a4a445851524b646c49364b496647635a71674a42674543564344677777722f3863316c44516749367a50523861665461372f6d4a4c3166514461507a6e476a507a30356272663741706d5674647651532b6e3245356f6456706749494363447563474d674141424b6844776a502f442f4f48784a6455492b4b3143467a38514868784a722f674162783664765a6d5254326d36526d7246426d774758735741664b4470542f4a52527a5236506854797548735141553463674145424945434147675238356c2b627a513857483668466745314b43543162515075576c4e4d4e314f4c4e493363784a6f516f5874786c3345636f45706137526e68554f594e4d6f59355449694f4c4b434a75494346375933716e53347a61786b4f64563933684f33372f4e354e3042434666546f6f744741394258426745434569436743562b5a573163774e4c697831642f766e6e2b35764954705168414d333372614e38524e44476d696c7145447963442f62772b4a477834676c4e65693851694130626c396b4637696c5463364455574d704a5157767033326149666d376149305769305068547364646636612b7843556758524e624a476663774d52676142324558414b762f4a66505070703458484b352b657672793064506d507a38764b45484439524f497866796b57386a64522b7a303447616864766e474f4a716678696f546a5a566c6e514b4e58385434594666726453484e4776556d63396762446a594a576e665a585342517a48577145496d455149434135416937782f4c2f2b766672666a32627a38754d2f706437522f48746c4350424b5930644b61642b52795161612f5346774d7442363371476358322f6b61534f6d6f714f685a6f51423536394b3139456e434c6f775062477162654f6456357337356a56592f666c7a76686f554136384b6b73746c4254686f4741304342435248774d31462f76465450432f6d7735764872373938654d347671454241442f562b43696768714f49334b68636e795542442b4e2b756f473954483754526a674934525649494b3976586b2f4874756c66484f553735577a694e652f35352f3531527130744f42704a48533859557974574255514542416a5a4477494e334c2f69764d515044336c352b2f2f6e46366a4a54434b696a574831416b6f454763567154725733644674452f33314257784d32704341684d373857664c74667a6233546c754c75637969322b7338462f5470676650484f2b72386e7464682b5a4a4c6b2f4f4a375464525376504b5a41676e6f545178444973416a34386d6a31316464456b774a554949422b516c43546f4b6f736f4c6f2b6c4b50556e5231425138627a635351343632467366736d51345a386c7936394f35624e5479736d3639554f4177786f386c737a6f6a3878346a6e46534339532b3371486878443474303767557a2b474b455246635a3770694c6c414c6457496751454269424478646647562b78792b5974345741414f306267714c42436776513976315145782f6233646f452f5331754c506e4237466a623032347a2f734e6b38754b77616e5a4f6b71417774714c626341535a4a74586d6b33703979626639306c477364506463336c61352f6a4f4964474e6f6a66764c59692b67664e6759434c5444455044362f56767a4b2f377254633049794b6e53777847456f73454b6a764f5757592b382b334d324b666d7a3670447234786f7948642b56784a6f4f356330704e376f32784871766a5a6e69704c5845534f516a32646f557a6a6933357a6778485777582f437162564d386c732f2f58576b556c5a30773858783437376d7935636463597349424e41514543456946672b612f742f3863562f714e5a4d774b6b75674471434a42414939516b335a504c305538385932522f38694e44596379514d4e654d2f647674703372586933644375737746647071306964676b4e6d33356f537679776e2f586b6e7146386d39553372506875464f582f542b6a643231695676645a2b5838615a685664706a55634f314c67756a382b525251694153424151474945504c7473586c705a65576c6d4851466f723169577a4c307674305567536a722b455448444e376132414565594147416b38412f4e3759616a6f32523253773144586951386633334c6470766965674c4e356e48336770797263346e33314e6a75636971644c6e64526139503532743667745a72415248626a4b4f304f4a3457436e5258333168364332385364356273317774775841514978685941766934763834724a35477769514f6e6a536e75665932705a3844797362676c68787761312f7744456d6d6362376353654978723466305a36794b72464850464c6e64766476636f746a7a78456c4442556b6b6356746c52736143524e48304b625448364b6e374a734d63626434794d4b62565035704c6a456146654f635a715a692b523471713874774853315a7a786b6d4434477a4b657441465670304b3567554543416749514c4d48355a766d73336251594455783539326c7a6a736f6b67574462626d662b4e4a336a70516a5865625574384a567a484f68387774496c596b6359327361557a61575362635438666d6b7a4b46414c6c6e304a5932566353354f65324a6737705a714751363064714863785865386e5631312f69754a4c354431684c46707a6a306a65434367502b7a64363039555a31624f487443737263646d644844584577564151386b773057704342497545514d4851524951454151706c30505242416a4553307a454943457870636655342f6d6b615562534a69596d54596d786e7871544a76306e2f54466e5a722f727656396d7a773149664e655857706b5a5a2b385a31764f755a7a3372575437344c4d4f586f434556676f6d2f55394d326f396977454b4342674a79524d7a735048674c5a696f79496371565534693066564132496a38615a42305775502f47497a37773564614457744d71754b4662507956754f3131685a62425564384a634d6a304875454272324a54464b444274454c4b366469487635516f42764144537041746e306a336b757636514754364731712f424f4f6f6678782b76597354416246674c4b4277466f6e4c544d36774a47417a526e5937657070557a44733934324a32636e454238655777636f4365525570334674454e55494931506f57524c6c6e5234545047306d6a39667132713272714231676b47326972727679374a3134794f796b584f434c67372b63344955585159326b466957427676474332727668346d2b686a7a4b426331766b343632326369416246674c4b445145393562306650546b333143664731706b73564a39355a476a542f2b4e657a734f6a4e78456d65306179586a7234384667584d72305a6358684b416742644a2b484941693753564574746f4f7046306e5847426c61352b6f5a726b354f6242374558354d31677a633649544e4d33455537705034473638586856384741745a514b624c354f5074396c4f68646d774546424743484337416d6c31696f7263673246764f523134744d505038446c4550546866314e784c314d58703846537577794f38475835344b6a512b4a484651782b373069624474776269656d306d714a344e4a6d34525139476e3944774f5267674d595552527a356166783637554638726a473671464d3062594d417450347a326e79385671444f42735741673442417372624430627375353561357a3064765058586674723362617a317a6e4c3438486a325a426735524467317a324f304e744461524a2b42504d4d5351654552326548734750704d58356a7a705a492f365041617456776b7149314956386477505532396a7066764c45586f526f4e6538306d4b674f6a7a4945554170764179525673565376706e5a382f546a396661524e75774546426d43476770524179655879434b576a4e386b47352f7a4570426f382f6d77327843413033344e2b4a704d37594966504e58735766736d696b34504f727041326746734f316759735847524f39785a4b4254555350466a687943316c2f794a63426f7430466e316445702f697941713149377556324c4d5730524548417965776b39334a6e39312f4d6f302b36473275437358525a6a77304c415955434174316e4f3358772f47547a6965444d596c6e35422f74495071734a62395731534778517669767a36444f704c59756f667a71434731494834636d2b62457561312f3543565346706c2f5447676772776e6679742f436b62522f4a414876626a347946626c4b74384849525a30336c4476437442714f5863766836694156444739545971415942674b694433304d41594c63684232773479677470396a7734614667464a42774b305667354b384a49454777395163737967455a5537764b66346e7133337965646a62544741504e59414f64454231546d76667a446e774c694349776e6f53557a2f6a46386454686f4c6f4555314847486767566b664c6c414433702b6e46703367534a7474446753636237707a4d484330714f4b4d62546a3544465a58394d4d65416b51716f66374364364c56694942735741736f4e41576853716e572b6648634444595a397277535a42543731736f3479504154774b51553341715a4734687830444a2f316375784e72477a6a4f714b71506a42496b6f356c444b67467258364d53614c2f793254537a5a6d493059754865306d32453964735966315662717351394858526b6d326b434e6a4d70784877474d75786f486f446c732f4f684e6d774546422b434143392b577235687643487437573655797a507758467444796676384b31466a71446e424962684353466c772b45525477736238417a52532f6a74534d31516542736e6a7573334339334c646b4e39517a763759566f4372492f35446734774e2b425851425438736a6676316b71775467437a30466870414963374163477939307772663939686c414f4c756a597444575444516b4435496343644b724d6d61454176435556614958613579456333636546656175704b314b5254575249536438305764366730695672326d41485a667966565a766544746366326d345571716733447653515371766b37704c4852426566314461726b704c6b38652f4d69414d55727938474f37646b582f554831397561383344504d464d38455135446f444d7235494f7179427145324c4151634367513839583854783870324e3334533271394d70494b746d33586d7547654c586b4a594f516948797336584268376a763954386754656c76334e374d55393379364d49644e5258476f484345623948756b6d4e722b45306a52634c3937416f6d70334349762b586f785a6b45454335767762377730584867707a6649344953494c36456e6c574c2b6b50527459544e4a44597342427743425053746c6e584c4c714b486c532b504757687a2f722b2f7872635249674a37684f552f6341513143676b785633307a357436625931366a657a52634259564836345a705469375738664249317845766149636c576a6931442b36575a47577a2b4246344a474c5544654d65654e5a516733674c6b6456724f76796b633854716d347a707049556756384c3469584c79482b6732574457514451734268775142364378654e686b6b306f676f3353477138456d31345a514f414e5a6e525a5569303544306b336464452f63506d632f77694576503144794a3555664d2b624d6e516f37496d72343169756e4a2b424562694c5a34476e4e7641446467542b6a536c555936426a314b356e2b7873742b5a386a5533593847456e4e544c577933365430384751684c3834584e63582f7a5a4e4878444149392b544e733859734e43774f4641414e6f316372453834374451705653314175684a39626c7167456c6a68354469557363634d524334644372416443746b77645933337a4776346d38704a7234472b6f346f4d6c732b57676a5930796b753466316e766531436c36345435382f754e2f5168344937554d492b62344c43725058496c674a417a394c5a2b4f38636d782b55566a7449335232552f2b7a46536f496f6748466d786a5141624667494f43774b67545667654a5354714e4b6a634963687850723777455674506b734e2f743834736d7549476a774441665a764e3366413564667361632f374d4f744b51527162653251616a314e4643514572544b4d5876503173443054617273386d49504e504e49735747454d43646238317436315a37306646796259506379495042345665454d676a51374e6b314d545973424f51504154753772796f713375332b555241456f4f4e6251316d6d38647431646d4c75552b7730383632664d394a37316456646d645463324e6b3864616e70706d5a536964654455684d787a4f4d73476f5874383631536e644750626a597850576a526355444a676a664a4a4a5a6e37315a2b4c4d31797a70526d4d414247736a4a5147356f684252567630794e32305a3052644a6248765a562b2f583747304f74475a634c6d536262315042593263337051787463624f68706e6832305773574568494238492b47552f677743665848652f454167414a6d4377444474535947656b4972464f503946724d48486a55556f6e51424e677153684e577143484d664d48684f2f3378475079655678627a4b6b374163774d636236625a4a7271634d6e52746e6533343573693736646d4e677866325a334b6d5752556c4e6a412b30687172464242446d5177396146474572366251346579544d44735567435449546530784c346762566a6a2f58494266615a74324c415151464c393534714b412f65335866663341694141647738624f30702b4b354261586461624e2f584535534d675458525254554a7659644e335679525045754b3071454874546f454b42652b746a366f4862316e31554837534b64462f49723552484d50526f6f59416647585261374c454270646a6a74714c41387759444c3167336b62766d614b63435a32637842396e6f4b47775377317948655948544f375a4a5145324c41546b4377473746652f63582f7a2f4667414237686b30563154363451436b2f4266545a6d4b4e4a7361526b4f37514b5364306a73372b6c766b70554f45476a2f2f4b6865322f61675145614d5a56424536446d68665971736e5053704f35722f316933624664484d57786f657746584a6854744e4c3561784566516a693076567a38466c69784775345132485837745553676b5941397470334431463351486a497343556950646335313241786a77304b415241533933396c3364777147414468337833744b4c58727638525475454c584d6c6e5a5a67786e70315755627a6c61554e66534835714a65314d5269446a6d517a78493369676e547374745146534e5a6456627a4f6142692b7230724864367147797242526d4930326961395272733859706363547067714943727337354942316343397163376e374e36786d7747306e4279767847304547496a6d6145713948417138693861476a53384b416e5979662f566e526358765752676f42414c36526b44735564702b41434b672b5633746b52346d41386a4d43306b355572616865386e526d6e6778422b6e31364a652f6c672f4a44536d53394c424558556b444d572b3375333772584435586a35303559523144354d302f61776248696d4b354c3978584656586e61334a7576424536415a52394f3348527a4e396376716a75767a4e336634595232415a5a51636f43567565776a4a68614e4172585251303265545a73664d6b516f46494435514d4252477a5a58744b78664554362f76705278367249436e4e434f386a5a686a3349736f7730506f564f684150544d567a766d36524868537932716135626f55414e474e436b64545a4b4e655945453756696e683254726b3645543245556c7a614b4d62576d735753723749317a794b454132636f4a3969476a7553396936367279557941496f4c566f696b30346e6f55414778594331465541696f6f507277714541444a79315431615168424179596e78744248577445752f376b51764c6c735173477732522f6d414c344832494373754a5243326e656a4c446a6643704465752b3577504247443566516b43395378454a315138326b557a2f454a4d79664d7047735767324771744d70344c3141316d71615354567059705959795a3775596f48304269375867334f5279634f6d4e546a41304c416572542f6766336f46414949497975303175796867434d59744666617436615464623945775251654530796169424f4b745333616c62304433664b434d445353466965497055646f58486d6d64307a2b544d3443414a4b364c363035366e322b777a773077374f43306d7a673757576370506c7133584e434a36663335744e446562734264594c4c5a627663363865597456416e505954526874304b77744969306850464e6d77386556437743364b5677642f46416f427a50785066366d4f575767306d49684e4a4273495359694961574b465453544c483079797a4d714651564d764f4a52716b4a336e71744d79366f696e7a39414a5a6d4e42316b556f2f304151384f68637962355958536f65534254384b7a5137565278494c4e437268794a414c515672756d37754c3854576d49387a376d6b3954486d5137325636306b2f5a6e797834706c34776e523633696c45624667494543486833384e6d66446434706f6865516a57557366576d734b77313750635570366165546e6b615943484675426851306e624c756a306a33737a484c4a714a56787a415546756d5378544c4f4c4a4d6b77794f4f5375392f376d475365574b79735031564b4d482b2b7246455879766f686f7044466f4c57523545682b785930434f444f724768644d61534661734a4141452f70505a6b7a4c4968325a64624f6a2f2b7874377939305441556c726a6461467058616350476c7730422b352f65377838635a503779742b496767476e56726d3957466e38666b464946637a706a55624d48484b31436172614d2f454879424a736d7750524279513545666c597468654861787068465963324677764f50325066615031346f38394254796c5830654d754c5942557464414a556d683257653847324546775249507534686b356563557764356a425830446e584c3645464e58764243694e73304d33576376333636657a4551344a4761414f6d4452735741746769774e31353562372f744c2f7a352f7369495944316b576d634c6272675a6f7541653165597671476a614f7652505662784e666d6c714343554f384f54546f434b634664756868644f733967346a74496e6f656b754e7638377966484375376d6a58756d61416152584978424c764f2b52797369664c514c694c38497968795274326f7739487a4c307a7a4d356565594f70362b396764665070344a65424f66756b593062326c714f4c5566492b6873624e6977454d444d42325a4777583362395078514841653564356e44584f467363685947616b43692f5242674b324774626b526c396f766854654d4f35374b35496e7572755734727155736630665a7248317a56556557774e4a564148792f584635446330586b7a4f676352594853344241684262486237746a5a7668674142565a7536464759624942697962354b6d71794f33485557502f50503336446e2b50587049724e554e4161507971726c705a37745452514a4552576f3763663275546977304c41524945664842665a534467383666642f65496867502f394c71774c69674d3149647579707a314f4364543833514f7842786c6d2f6c57564f6f58326767577147346746655938574f77386376616d526b2b4a747570414551322f2f7a3937352f375278336e4663646c75647759426859494d496a724548477a59514672345a466b4a6f4b4a416c67515277435241674957465343676c72554b49514c644755534d744343466d6b4e717163715650613959656b517330766b62704f7174622b30476f2f724b716d7472394d363652392b55506d7533767537726d37357a6b4d746d2f4f356633364a6645644e6a36662b6279667a2b6635664c6d7033362f4f3750705648344e4d5a736c49416454364b304e44543258694631384271496f363435593332535a75704866486637686d37546e746e6c796b7a2f514769774b613244565a66687276616f376757594f333073544c4b6230395a4e326543414249774d76762f656c587776574837776c434669524144504e537663626f6b53506274566f583143512f656869414c2b78744d4d614271693561687a4b303745546a575a4c75614b72727062656536322b653562793647706b2b6c7a793179397842596a546a5467544b546b4e666872456762536c73374b70486c34577870795a546b534a6a4b2b313230723943732b386264316a6a6e496e73314b774f6476667152375a463950364f525561514c6e2f495532315937704e784e6f634e7a36664c446c7954474351474941464d43586a366b64516b2b7672547245684161726c577030554366454d44643873326478494e49657654585364574531524d76765334307652657a5347503065564672495455514a324c4e3971395750346c733772564f745734556777367a327479594367333243532b78782b4747596c4450323349504f3673316a6b777836326e4335564d717838426f4e384d7274374c656e4b736d612b7578416c3668377a5a4d345971366748467735443974514a6a6b66566437564d6e6d626e63396a316566556e676d4d476179373250504b485864566533535473634f796e4d414f44466b494358313638723163465a6b514447582f74617650704d4f6e2b437457556c72505770766a6e6e327934742b534e51514f2b2b4d6b7477715431455531543939714a706a46577366556733474c376b4b6a646e6e7335514e4443306b5a553170376f483475726263596f563564464570397a634a6236354937646b6d3664352b69656f4c654c2b4a62356f624b545836414d52752b355a6e4264337966743064634375746d4e76766161396b74494f696550755650334d4f6d3370725762544c7042336874627854454e7941446861416c52546e79304a4547497658544b756933316c712b56622f42324b3056374653706e61466d6a4e4f666655632b78752f53424c5a7252707753736d41394e76574e3557584176523733746949306e5649756c4b7767546a5671724f413868616c7777746868563959306576536666674b54574f557159757a7364755144647355544f6d3247315062302f336b4639664f7945713169466c5772432f4d3737476262496e513270376464332f6c562b6a4d2b626937544b4a6c657a4d6151306f41764f36744e784d45784d41634c7745534c5842483332634e516b517a637667684c6d7171764a4e693170587559734c69566233743342444b7a774a614751756c4c56474e597971494e4931497672755169537965696252726374576235735030434d4b6d343076663255582b3332342b765a6d3738744156545666484e7a32314c43537953686c646b32377975552b336b3676776a364c696f47616931774a6c4f34555636723966615a776e56716a374a756c506a7833704f5038744e2f7737464b7a75304a2b6b3676365543525350444d5331413033364233484e6a434142477768415249662f793662456941362f35574d7a63473165507a656246446b38486a484a715549536747765650535a484f4e622b4e4f643231683561316e74724d3441546478496a762b715a4b5a7131523578356e6d3037656e6174347a6f304b575a446f31737735346c773476363345756a5144517a4a363877725478726d3732622f6445704a53484672637a507033477177744a586d78356638417142794f70674d4f526a504a2f3152726869342f4838764441416977496741656c4977507148516e596c5150544969316c5a346a6f7578516436556e725148656f6b797a327037655438576236464e34327874517232716b7431557747546c51517333694965513049394d7079574257774e5a37304251574259463258333963796b317a564956344872616a6137534f706d7338637a4d634d5738564e57387a6e5a47716839416f7a3746443348652f4f47667452385748327065524c67487976414a6a4341424b516841664c4244374d754158497339364c507378312b6251367a2b32674c377834316856376d7546313432693362677732366d48753531355231716a70716b724650615a346d332f756a327078596e4f5334767434677571396f53312b41366f6f6768715a5971545a617652797643595636696579707971744764797a6156305a35453661396b705635697857357361386f423559546f4b397530485238636a654d4359414570435542767864357370345443524256344d3147662f6f533047514d737938617476504b395a4c6961724e59367a565a4458566b4c6c5248716454433244312b54726c4241685a5050736a6469704f756353556a46762f427252647a5077673378692f52692b4662587574726e2b4d6f43716e4e3442596f362f6f32746436714d6c6834756c646f615746526f6256754c59517376684d744c526265694c344a494c6e6d7467597654416d414247776a454a5452314c4174715a6971644b576e414b6b6c4b54305878464d2f6272532f67644d627379456c782b54537035624f66684e6a76685339667679424d5643684d7877315066786b6e414d58584c72636f5a782b4a397854706f5a467274377166634d4e43357552314a7572325a5370454771756e445473557275713264652b4f63467334474f4d734c6a6d396e684c75415056676c4b51796a2f523363427973704a336f6e474a74764e70374a41586353503676703743656d5a64672f714c4a6e564c44482f6c4f427143416b6841586b6d41754a53646d6b736e496a52576f6e50734b376b31747537495a6d544c6c5a36553165686634576256422b61375779565448683234753348615a4f71543552336374577567554f7064352b2b384e7a75632b3872546e797955395555393234612f4f36314b77417266584872623237634b7072676a6b5377747439323170796f5a7a6d4c30334445765351466f35526562373037383153636e6f446147693270675241416b4950386b514451706b614b706b646c376c37675771334a66655543337a37695334584b75716c5258687670383435325a6332314c41486f7464716539742f79694a7845386e452b44464b766f614a4172644c376a6b4678614c71596d445254414e67424977504d7441617235325a784a565063617a4a6e763574515a615465527967674e4c5754346d397a6a5066314f5367733074466d7a496a70615a48336c336a795752752f6d56494c322f777236336b47424c3441454f4555436c4f44476c616d52594c424e6a425a5842784e716d775a744d7a682b4346384873797551526f5a5661674664685952344143414265533042544b6943707459392b4461774346534632396134356e384e30584141494148507177526f30324663436254337461446979754374366e696e6e3671376267754747785a672f51474142447933456b4356724a35455a53634141424b5143776c346b705943504c452f784b4832396a634d364149414145684174695241654a7947416a7932335155347069553973756235416741414a434162456943387636554376472f3368565044585468646151414141424b5144516c3439734557437644424d377376764e39485458704668786341414351675a7849675048746f715141503756634171762f42494877414141416b49496353494478617431434139556332583356676e43353461734458414141414363696c42416866386a56672f55743772396c646f4f742b553171467277454141424b5155776b515075644b774f6632586e4c465331755068674941414568414e6956412b494b6a41462f5937414f453952336a666f4745554141414a43446e45694238786c53417a2b79393445435964446c776b646c51763052334f4141414a43443345734173453761354b4c68696c506741726c6b796266593369414d42414341424e6b6941384e516f4148393561752f6c716e5044585a4f4473686351777468764141416b7742594a4d4a554a32317755664f43774d6a486d6a64663631506e7841414141436242444167786c776a59584253667253446c4176454d5a46544d64775863414141414a73456343684564306d66424465307643486c5153483641764a67674a3949634441454143624a5941756b7a5933714a673930685543514946424346795550702f473570454177416741665a4a6746596d62484e52634f30415559415459677051753577594e494a7641414141456d436a424b676c597261576841564f544e414b454a4e6a5167657845774141674154594b674843743549436647766e5a535a6e5062514343475753452b4171777863414141414a7346634370444a68573475435979514431464e664b44324f54474d6e414141412f6a3853494479326430376b36725453464852655074416b50577270782f304841454143624a6341657a6c655368546749476b4c76646f70506279547850304841454143484330423767316c51466a6a586e4c6b4a476f4341414467525a4141373654534679366f4c507233314573485a672f6739674d414941455a536341722b63313358784d583444392f4e427a362f752b7641414441693433444a65432f69674a382f355636374b742f5330652b2f67353348774141435842774945684e4261717331534a4450664b685962694141414145676877734162666a5a42746774454937654f7973644b7931466a63664141414a634b3445464e5a54666545556b6e646b56546946657738416741513456674c63313754684142516e704746684c585665334873414143544171524c67487648707177466b6975556d305a326e63657342414d43704575424e6b484b41526c30664946495635686c44595441414144685641745343734a5759376e6852505171444151444132524b67396f6275306663434a5876426e6a6f33376a774141446854416b70366a443068434f58792f6b426c44446365414143634b51456c593749432b4d4d422f596b44352b515431334466415144416d5249515779486c414e654d345a3472753651543158747833774541774a4553734c645a566f44657430326e6d6c41584441414154706141596e6b77764766576e505a5a496f7444483570454177434149795641555942504b38774b49473852524274773177454177496b53734271534661427a3158544b47355148794b4d6b4141414148436b4256514e6b5450787838376c3275566a734a456f434141444169524a774b47366144714379572f5950517274787a774541774945534d4638714b384163712f49724952654c74654f5741774341417956414751396761416f68453545376842364d344a59444149446a4a454272447331534147465133676c49344934444149446a4a4d41645a7263474a53546c684e4378457478784145436573762b332b7a4e2b6a614e48756c3549435342646f44326842656270592b4a7031324141587a4941514e37523957714b4c6b6a417a6c6b673951432b6371614c634d71507a68414167507a6b386f316c305158345a4b63536f4c50364c3659455646776c336148447a4b7a2f426e6d6234414a4b416741412b595a6d744345424f3451552f6e6f386378577330325265634f6e722b4c494241504b4e7052755856516c5975694547684f5449554264395146682b396455622f78522f556a79317044773139662f6c2f666646483135656c75322f4c4147706e3736666e70773451514c554b5a467349362f4d4338616f4d4142412f69455a62316b43377164732f464c4b646c2f2b6d2f6867535473674c4238354b6a2f716b76367a70486f4e71522b56724c346b4a4b6b44306750784a5a6653307741485349437141497a3230436b433433495971426e5a51414341504a53414c6b30436c7252496a766976656b414f4561584d2f4f5676396d75715166774836526c486a36522b7447745a2f756c764c6d7576382b6366457a35787141516f43754372537a4c5039376449703366396a3731372b59326a50674134376f635575396762737437675748616861624b4a534d416773456e4d4b366d4a516836457046517079484562484545697145745149774b703442497066616971554139557053634f556349424c6f684c626a6e4144665741656b4271706634746e643838316d74375a6b4e694f33686e5078384a69486657452b537866742b64333777472f616f4236336f7649417a303864423938664c434b554c6868666e6f73333838354d657a5074505435394e4b784a4e4579574166442f2f7a7962766a3930796e61626e5a716744746e3442474156374a6e2b644a7a7856363942572f616341364e425550373473534d445539762f4468663345432f6e32782b58756a5645796c435969575050766473615a334e39787355594332543844417965355735774c3144627955336a5669774738617341356c4d2f734c4355686e64706f5445452f735431322b655048792f4c4b4170464d2b352b656e6d72397463514f4b43744475436367754375342b576a4445703765497a6e6c2b414d42364d4239503257545842595178504d774e5454565042423137495473636644374d2f2f387647654c6e35354e3969475171616636373735726633666674777637437a634943744873434469534865752f39615545424c753178673142676659746e2f7150502b59305250377a7762664e65514a6a326e3337685a686a2b7a7a634f4263527a2f6d4777662f5a763031505a4657614e6430392f762b7344326a734268386553456637566f6a6563624857394145413737533563767467794a504e33734d36325473446d30376359345166486e4130456c4d544375554f33483467794a69413745464134305a2b65445653386b774451426f36644439634674377a593633627644464743424b536638653839554c423834426e54514541706467436d6233484c68366e7038336530346a5a4f774d627870414166394c546553526737375063486f46774a474568752f624e7270716631546b4c5246514d41457443754355672f34312f35653945627470324b432f445563526546415a5172415430664a46634550467a3068763148342b56762f3977324269685a41744a72776e377977433365634757626251785172675163324e58365a4b4473664e44694e7744516e676e59657641575233717a7538506437304141514d6b534d4a6b4d38482f355a394562586b334f42333275596773446c437342325456666854642f79793461653941474269685a416d724a6f6435486177584c30774d422f55663332384141355572413769334a5a2f7733682f4f586233776f4b6342446e68594d554c49456250356a504e482f584e4666765447644a747272786841414a5576417750334a4651477674313765762b6b52577865675a416b3438566f59344863555051496765357a386f363449414368624176612f45592f77733765345057694c45305942614e4d4576426566377a6e325873486939475168643467474b463843657634556a2f4276464a7a756d5a304f366e6e78414f564c77434d3777676a2f31344c5450586466534172512f354962517743554c51484438656b2b342f6b46364c6e6e5546714177744f4641476a584246512b6a452f322b56582b306a4f767051555947375268416371576742666a455834696679636775334f514f3051446c44414236516d68782f4f5870672b4a386242676744496d34504e50573577516d70304d35426b424147564d5144572b374f757433412f35326233683342774f6f49774a534f34516d7238546b4e305a794d6c41414b564d7750506478566546545859374751696776416e6f695a38482f4e714a7647587059384b63444152517a675273757849472b562f6b336634746659694d6b344541537071414532385833514f36702b362b4541436c5473426b345756686c2f593446417851356754732f316b5935562f4d57335179505244776f4f304a554d6f454a5050394f615038787166764d773045554f6f45665035794f4e373730624c587436614867767633584c49354163715a6750684a4154755750524e2b354742616750365474695a4153524e7749417a7a6a31655776486f70757a4e512f34635657784f6770416e3464526a6e443235622f474c6a336e4464623232324d51484b6d6f416e7730682f5a50464158386d4f413354586832314c6745354b774549425842554d30466b4a6547793833315842414232546743323738777267716d4341446b684130786c4243775859737456324243682f4174342b736277417a33684d4745416e4a4b442f34665372787048672f743837477853674d784c777578394866787765765038335751484737514d416445674377704f44653937716238682f6942674170557041376435347a4b3976752f535048593043375071763030454279702b417a5566366c37727661624e41414a3251674c37664c6933416f542f62666743646b59435075686358595077786d772b6751784b7739664646425841354145446e4a4b44766c317355414b42444539413338464a36454b412b6334384c776741364b67463966634d6658526c393951476244614144457743414241416741514249414141534149414541434142414567414142494167415141534941454145694142414249674151415349414541456941424142496741514153494145414567414142494167415141494145415341414145674341424141674151424941414153414941454143414241456741414249416741514149414541334d30456e50456a424768585a316159674f5044666f594137576e344479744d774d453550305341396a543338676f54304c586c3966312b6a41427475412f77774b6d756c5361673639504a4d7a38436f4d32634f66357931386f5441454135535143414241416741514249414141534149414541434142414567414142494167415141494145415341414145674341424141674151424941414153414941454143414241456741414249416741514149414541724530436a7578734f4c4b69763673324f4f51484474425743646a5a5a506e5337584f56537155714151436c547344736b7a6b4a714d39746a7a49777551722f4a304f44565a73445948306d594862733674486c4361697432726774415144724e5146524164374a3251746f544f3645436148426f57516348783270522f2b457237505276564b4a39685a717466414e6c637249354d686f5633326b486e314850586b6c2b6d38796f54513047463670527439597139557131555672416544754a2b443033746d34414c4d35435267646959627a5947593047756c72386567642f685571304f68444750736e6b7753456c376250685153456f6230612f53484d496f5870704c676554516b49655669304667447566674c32586830376d685167353342774e46616e455969483775306a79574265445a2f347778646443314d3855514b53562b6f6841654567516a7a457037734e53784d51743652704c51443841416d5966656671316151414f3350665547764d35345368753971312f66426f2f4655382b7850556b30704543616a486e2b6a6a42495358346754455530444c4535444f47693273425943376e344451674b51412b516d49502f3350315a4b684f78726b773266334a59654a717945432b516d6f52534e38336c35416e4142486941462b344154736e4e307975374e4641714c685044734730445636654763597871744c4a76446a7762365754502f4568774379424d5137416b304a43494e2b4c5574413157454167423836416357586873324d6473574867634f49766e30753773415454777946325a316f4c422b646964387a4e444f554a57426f734845344f453141504f6a4855306e4a6f4a38644b59362f616c344c4148632f4153317645424850316c655450777a4f44476144667a4c446e30376968334e43737a452b2f486e777a65614a6f5044477966535951445665586d744d42445776425943376e34445656322b6351775241687955672b5951505145636c594b6757666636766d746f42364d5339674a727a2f4145364e51454153414141456743414241416741514249414141534149414541434142414567414142494167415141494145415341414145674341424141674151415341494145414341424145674141424941674151414941454153414141456743414241416741514249414141534149414577497138752f66737849586532335a6834757a6564322b7836692b2f7566374a787874753038656658502f6d5335734643594331742b6c733777716333565338356e4e6658647477783635396463363251514a6762666341546b586a2b4d5368505a74752f31763337546b30486e337a71594c465439315951514469434e783479765a42416d414e437a44652b6f503839396d46474e2b58742b537a2f327859735839395a6773684162425739703075474d427659785558656b2f6e724f4c72367874577766577662534d6b414e5a6f482b4230373853374b31374a524f2f705a53733574796f4669427267674141534147745467504865696456597a3054762b4a494776502f46686c58797866753245784941612b445573714837446c4e796f66653578612f63324c42716274684f53414373766b3239766674575a303337656e73334e583939377472714a6543617153416b67502b33642f2b7562614e784849414e676e69786f625958343956655053556b5734597575582f6862696865584f30786448416747447831315a5168344b6c5a516f6130532f63554f6e5476634e4d7439332b63586a6c78334669364e493655752b4f6542304a655764694c345076522b304f764b4e3834476c663055783933537654526c554945514e6e65506268317a7779617163466a583230313268736469725568706375433534466e3879542f784852792b6a6650436c735a696769417368336b7a4156337572316172622f333541696f44614f442b344e50426355386e6e2b64505430436475775667516941736f326a6f38644c2b383947774e4836534644524f46447966524a764551464767684142554c626461502f424a3733756167696f3032794f2b7256326f35553257746d705a6a4f742b2f31526472675a41667672585971434661477a7953797239664538767170666e61383170704d2f4a3950515463694e676d7658436845414a5476656d41726f2f394b3743344e66737a7266626a5148745547614262317557766a33327631755038754a7a5167346a496233423475434566396b353377654b6e383972665278577670586a54515a736e4249706e6e66573768576941416f575251392f4754775132565041364864364e5453726b4261387a764c766b486f454b54746e41476a39562f4c6e77302b6e6354705831726b5139485057717447577639444f4d792b6e75664f423774576941436f50674b3676565839487a5762335451435769454357746e2f30417272685a714e396c59526342376d6773504e666a77506b384c7048662b716b5836614a55482b67694552674169417375554d4248583774363357714c2f73426478467747415a416133623039734d4245337251526a2f795975416e5467354c5a67734e6843454349437962553448317a724c385a3573374763394175354f334a33665a6a72347473416e79624c7968384e5649305441624249584c426b3148597749674c4c6c4c41727464554e74372b2b4657743975334138453166716a515459644850344e426c7374436c3365386f642f635432354852473661797a6e6772386d466f55694175426c35443061466861444e72753973415a30744c665743386a6d426a724c6834665438722f4e6f32464a6376754563427a5076396672495242576a6556613061747a6a3459684175426c354734517361576632794469782f3741576d4e3556504238324a6e746f684542554c702f617075342f4169597a616647675241423846497133437a36374b6b5263447170467954416d6332694551465167646652635557766a506d6a764c32696231776e524142556f4b775852773433336a353265564657416c7a624b686f52414e566b514657766a7a3935752f4436654551412f4c7364766f6d477a3577504f44794f336d7a2b784d6d58557434642b65474c61345149674d723641634d6f47722f612f767576786c4675694a7963764c312b66674c3872672b41434941714d2b42316c4262786f2f30742b674b482b3064706745532f465a772b2b667a4d776144465a30384549414b6757754647666e764658596a33377939766e6a4561394f484752444169414636674a3341773368302b76666f66373434504870744d2f765474596e4832314f702f74726a345a6c6349524141414967414145514341434142414241416741674151415143494141424541414169414141524149414941454145414341434142414241496741414551414143494141424541674167414541454169414141524141414967414145514341434142414241416741674151415143494141442b4578454177502f555879392f3764574b502b4f384141414141456c46546b5375516d4343, 'IC', '2023-08-27 07:54:59', '2023-08-27 07:54:59');

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
  MODIFY `account_id` int(255) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `appraisals_2023_2024`
--
ALTER TABLE `appraisals_2023_2024`
  MODIFY `appraisal_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `appraisal_answers_2023_2024`
--
ALTER TABLE `appraisal_answers_2023_2024`
  MODIFY `appraisal_answer_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `comments_2023_2024`
--
ALTER TABLE `comments_2023_2024`
  MODIFY `comment_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(255) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(255) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `form_questions_2023_2024`
--
ALTER TABLE `form_questions_2023_2024`
  MODIFY `question_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

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
  MODIFY `signature_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
