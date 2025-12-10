-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2025 at 06:33 AM
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
-- Database: `oro_vision_care_clinic`
--

-- --------------------------------------------------------

--
-- Table structure for table `case_record`
--

CREATE TABLE `case_record` (
  `CaseID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `PatientID` int(11) NOT NULL,
  `ChiefComplaint` text NOT NULL,
  `AssociatedComplaint` text NOT NULL,
  `OcularHistory` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `case_record`
--

INSERT INTO `case_record` (`CaseID`, `Date`, `PatientID`, `ChiefComplaint`, `AssociatedComplaint`, `OcularHistory`) VALUES
(3, '2025-01-06', 3, 'Difficulty reading up close', 'Eye strain in evenings', 'Wears reading glasses'),
(4, '2025-01-07', 8, 'Dry eyes', 'Burning sensation', 'Long daily screen time'),
(5, '2025-01-07', 9, 'Floaters in vision', 'Occasional flashes of light', 'Myopia diagnosed at age 12'),
(9, '2025-01-10', 1, 'Vision distortion', 'Eyestrain while reading', 'Astigmatism since childhood'),
(10, '2025-01-10', 3, 'Headache after long screen use', 'Blurry distance vision', 'Wears glasses irregularly'),
(12, '2020-01-12', 1, 'Blurry vision', 'Occasional headaches', 'Wears glasses since 2018'),
(13, '2020-03-05', 2, 'Eye redness', 'Mild tearing', 'History of allergic conjunctivitis'),
(14, '2020-06-19', 3, 'Difficulty reading small text', 'Eye strain at night', 'No prior ocular conditions'),
(15, '2020-09-22', 8, 'Foreign body sensation', 'Mild itching', 'Dry eyes during cold seasons'),
(16, '2020-11-10', 9, 'Sudden blurry vision', 'Light sensitivity', 'History of contact lens overuse'),
(17, '2021-01-08', 14, 'Watery eyes', 'Sneezing and itching', 'Seasonal allergies'),
(18, '2021-02-25', 15, 'Headache around the eyes', 'Blurry distant objects', 'Family history of myopia'),
(19, '2021-04-14', 16, 'Double vision', 'Dizziness', 'None'),
(20, '2021-06-09', 17, 'Eye pain', 'Redness', 'Trauma from sports last year'),
(21, '2021-08-18', 18, 'Glare at night', 'Halos around lights', 'Possible early cataract'),
(22, '2021-10-03', 19, 'Dry eyes', 'Burning sensation', 'Uses digital screens for long hours'),
(23, '2021-12-27', 20, 'Flashes of light', 'Floaters', 'Suspected vitreous detachment'),
(24, '2022-02-11', 21, 'Itchy eyes', 'Watery discharge', 'Allergic reactions every summer'),
(25, '2022-03-29', 22, 'Blurred near vision', 'Eyestrain while reading', 'Presbyopia suspected'),
(26, '2022-05-16', 23, 'Eye twitching', 'Stress-related symptoms', 'No significant ocular history'),
(27, '2022-07-04', 24, 'Sharp eye pain', 'Headache on left side', 'Previous migraine episodes'),
(28, '2022-09-21', 25, 'Swollen eyelid', 'Mild discharge', 'History of stye 2 years ago'),
(29, '2022-11-30', 26, 'Photophobia', 'Nausea', 'Ocular migraine history'),
(30, '2023-01-15', 27, 'Vision distortion', 'Wavy lines when reading', 'Possible macular involvement'),
(31, '2023-03-22', 28, 'Dryness and burning', 'Tearing when exposed to wind', 'Long-term computer use'),
(32, '2023-04-18', 29, 'Difficulty seeing at night', 'Mild headaches', 'Family history of night blindness'),
(33, '2023-06-25', 30, 'Eye irritation', 'Red eyes after swimming', 'No goggles used during swimming'),
(34, '2023-08-12', 31, 'Sudden vision loss in right eye', 'Mild pain', 'Minor trauma from accidental hit'),
(35, '2023-09-30', 32, 'General vision decrease', 'Eye strain', 'High screen time daily'),
(36, '2023-11-09', 33, 'Swelling around the eye', 'Mild bruising', 'Minor fall last week'),
(37, '2024-01-05', 1, 'Blurry distance vision', 'Difficulty seeing road signs', 'Myopia progression'),
(38, '2024-02-14', 2, 'Eye dryness', 'Burning sensation', 'Uses contact lenses daily'),
(39, '2024-03-10', 3, 'Light sensitivity', 'Mild headache', 'History of migraine with aura'),
(40, '2024-04-21', 8, 'Stinging sensation', 'Redness', 'Chemical exposure from work'),
(41, '2024-05-03', 9, 'Visual floaters', 'Occasional flashes', 'Possible posterior vitreous detachment');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `EmployeeID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Contact` varchar(15) NOT NULL,
  `Role` enum('Doctor','Sales Representative') NOT NULL DEFAULT 'Sales Representative',
  `DateHired` date NOT NULL,
  `Province` varchar(100) NOT NULL,
  `City` varchar(100) NOT NULL,
  `Barangay` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`EmployeeID`, `FirstName`, `LastName`, `Contact`, `Role`, `DateHired`, `Province`, `City`, `Barangay`) VALUES
(1, 'Juan', 'Dela Cruz', '09171234567', 'Sales Representative', '2022-03-15', 'LAGUNA', 'SAN PABLO CITY', 'SAN ROQUE'),
(2, 'Maria', 'Santos', '09281234567', 'Sales Representative', '2021-11-02', 'BATANGAS', 'LIPA CITY', 'BALINTAWAK'),
(3, 'Pedro', 'Reyes', '09391234567', 'Sales Representative', '2023-01-20', 'CAVITE', 'DASMARINAS CITY', 'SALITRAN'),
(4, 'Ana', 'Torres', '09451234567', 'Sales Representative', '2020-06-10', 'QUEZON', 'LUCBAN', 'ILAYA'),
(5, 'Roberto', 'Gonzales', '09561234567', 'Sales Representative', '2024-02-01', 'RIZAL', 'TAYTAY', 'SANTA ANA'),
(6, 'Josefina', 'Lopez', '09671234567', 'Sales Representative', '2019-12-18', 'LAGUNA', 'CALAMBA CITY', 'REAL'),
(7, 'Mark', 'Fernandez', '09781234567', 'Sales Representative', '2023-08-09', 'BATANGAS', 'BATANGAS CITY', 'KUMINTANG IBABA'),
(8, 'Cynthia', 'Ramos', '09891234567', 'Sales Representative', '2022-05-25', 'BULACAN', 'MALOLOS', 'SAN VICENTE'),
(9, 'Elijah', 'Garcia', '09175556677', 'Sales Representative', '2021-03-12', 'LAGUNA', 'ALAMINOS', 'SAN GREGORIO'),
(10, 'Kristine', 'Villanueva', '09275556677', 'Sales Representative', '2023-10-30', 'CAVITE', 'IMUS CITY', 'ALAPAN I-B');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `InvoiceID` int(11) NOT NULL,
  `PatientID` int(11) NOT NULL,
  `InvoiceDate` date NOT NULL,
  `DueDate` date DEFAULT NULL,
  `Status` enum('Paid','Unpaid') NOT NULL DEFAULT 'Unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`InvoiceID`, `PatientID`, `InvoiceDate`, `DueDate`, `Status`) VALUES
(1, 2, '2025-01-10', '2025-01-20', 'Paid'),
(2, 2, '2025-02-05', '2025-02-15', 'Paid'),
(3, 2, '2025-01-25', '2025-02-05', 'Paid'),
(5, 14, '2020-01-10', '2020-01-24', 'Unpaid'),
(6, 15, '2020-02-18', '2020-03-05', 'Paid'),
(7, 16, '2020-03-02', '2020-03-16', 'Unpaid'),
(8, 17, '2020-04-11', '2020-04-25', 'Paid'),
(9, 18, '2020-05-09', '2020-05-23', 'Unpaid'),
(10, 19, '2020-06-15', '2020-06-29', 'Paid'),
(11, 20, '2020-07-04', '2020-07-18', 'Unpaid'),
(12, 21, '2020-08-12', '2020-08-26', 'Paid'),
(13, 22, '2020-09-01', '2020-09-15', 'Unpaid'),
(14, 23, '2020-10-19', '2020-11-02', 'Paid'),
(15, 24, '2020-11-22', '2020-12-06', 'Unpaid'),
(16, 25, '2021-01-08', '2021-01-22', 'Paid'),
(17, 26, '2021-02-14', '2021-02-28', 'Unpaid'),
(18, 27, '2021-03-11', '2021-03-25', 'Paid'),
(19, 28, '2021-04-09', '2021-04-23', 'Unpaid'),
(20, 29, '2021-05-17', '2021-05-31', 'Paid'),
(21, 30, '2021-06-05', '2021-06-19', 'Unpaid'),
(22, 31, '2021-07-12', '2021-07-26', 'Paid'),
(23, 32, '2021-08-03', '2021-08-17', 'Unpaid'),
(24, 33, '2021-09-10', '2021-09-24', 'Paid'),
(25, 1, '2021-10-22', '2021-11-05', 'Unpaid'),
(26, 2, '2021-11-14', '2021-11-28', 'Paid'),
(27, 3, '2021-12-03', '2021-12-17', 'Unpaid'),
(28, 8, '2022-01-19', '2022-02-02', 'Paid'),
(29, 9, '2022-02-25', '2022-03-11', 'Unpaid'),
(30, 14, '2022-03-10', '2022-03-24', 'Paid'),
(31, 15, '2022-04-18', '2022-05-02', 'Unpaid'),
(32, 16, '2022-05-27', '2022-06-10', 'Paid'),
(33, 17, '2022-06-09', '2022-06-23', 'Unpaid'),
(34, 18, '2022-07-14', '2022-07-28', 'Paid'),
(35, 19, '2022-08-03', '2022-08-17', 'Unpaid'),
(36, 20, '2022-09-01', '2022-09-15', 'Paid'),
(37, 21, '2022-10-12', '2022-10-26', 'Unpaid'),
(38, 22, '2022-11-08', '2022-11-22', 'Paid'),
(39, 23, '2022-12-01', '2022-12-15', 'Unpaid'),
(40, 24, '2023-01-17', '2023-01-31', 'Paid'),
(41, 25, '2023-02-04', '2023-02-18', 'Unpaid'),
(42, 26, '2023-03-10', '2023-03-24', 'Paid'),
(43, 27, '2023-04-05', '2023-04-19', 'Unpaid'),
(44, 28, '2023-05-13', '2023-05-27', 'Paid'),
(45, 29, '2023-06-07', '2023-06-21', 'Unpaid'),
(46, 30, '2023-07-02', '2023-07-16', 'Paid'),
(47, 31, '2023-08-15', '2023-08-29', 'Unpaid'),
(48, 32, '2023-09-09', '2023-09-23', 'Paid'),
(49, 33, '2023-10-11', '2023-10-25', 'Paid'),
(50, 1, '2023-11-05', '2023-11-19', 'Paid'),
(51, 2, '2023-12-14', '2023-12-28', 'Paid'),
(52, 3, '2024-01-09', '2024-01-23', 'Paid'),
(53, 8, '2024-02-12', '2024-02-26', 'Paid'),
(54, 9, '2024-03-18', '2024-04-01', 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `ItemID` int(11) NOT NULL,
  `InvoiceID` int(11) NOT NULL,
  `Description` text NOT NULL,
  `Quantity` int(11) NOT NULL,
  `UnitPrice` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`ItemID`, `InvoiceID`, `Description`, `Quantity`, `UnitPrice`) VALUES
(1, 1, 'Eye Examination', 1, 300.00),
(2, 1, 'Anti-Radiation Lenses', 1, 750.00),
(3, 1, 'Optical Frame', 1, 400.00),
(4, 2, 'Follow-up Eye Exam', 1, 300.00),
(5, 2, 'Lens Replacement', 1, 550.00),
(6, 2, 'Cleaning Solution', 1, 100.00),
(7, 3, 'Premium Progressive Lenses', 1, 1700.00),
(8, 3, 'Titanium Frame', 1, 550.00),
(9, 3, 'Eye Drops (Lubricating)', 2, 50.00),
(371, 5, 'Consultation Fee', 1, 500.00),
(372, 5, 'Refraction Test', 1, 300.00),
(373, 5, 'Eyedrops – Lubricant', 1, 180.00),
(374, 6, 'Frames – Basic', 1, 1200.00),
(375, 6, 'Single Vision Lenses', 1, 1500.00),
(376, 6, 'Blue Light Filter Add-on', 1, 600.00),
(377, 7, 'Consultation Fee', 1, 500.00),
(378, 7, 'Contact Lens Fitting', 1, 800.00),
(379, 7, 'Contact Lens Solution', 1, 350.00),
(380, 8, 'Progressive Lenses', 1, 4200.00),
(381, 8, 'Frames – Premium', 1, 2800.00),
(382, 8, 'Anti-Reflective Coating', 1, 700.00),
(383, 9, 'Consultation Fee', 1, 500.00),
(384, 9, 'Refraction Test', 1, 300.00),
(385, 9, 'Eyedrops – Antibiotic', 1, 250.00),
(386, 10, 'Frames – Basic', 1, 1100.00),
(387, 10, 'Single Vision Lenses', 1, 1500.00),
(388, 10, 'Blue Light Filter Add-on', 1, 600.00),
(389, 11, 'Consultation Fee', 1, 500.00),
(390, 11, 'Refraction Test', 1, 300.00),
(391, 11, 'Eyedrops – Lubricant', 1, 180.00),
(392, 12, 'Frames – Midrange', 1, 1800.00),
(393, 12, 'Single Vision Lenses', 1, 1500.00),
(394, 12, 'Hard Coating', 1, 400.00),
(395, 13, 'Consultation Fee', 1, 500.00),
(396, 13, 'Refraction Test', 1, 300.00),
(397, 13, 'Eyedrops – Antibiotic', 1, 250.00),
(398, 14, 'Progressive Lenses', 1, 4200.00),
(399, 14, 'Frames – Premium', 1, 2800.00),
(400, 14, 'UV Protection Add-on', 1, 500.00),
(401, 15, 'Consultation Fee', 1, 500.00),
(402, 15, 'Refraction Test', 1, 300.00),
(403, 15, 'Eyedrops – Lubricant', 1, 180.00),
(404, 16, 'Frames – Basic', 1, 1200.00),
(405, 16, 'Single Vision Lenses', 1, 1500.00),
(406, 16, 'Blue Light Filter Add-on', 1, 600.00),
(407, 17, 'Consultation Fee', 1, 500.00),
(408, 17, 'Refraction Test', 1, 300.00),
(409, 17, 'Eyedrops – Antibiotic', 1, 250.00),
(410, 18, 'Frames – Midrange', 1, 1800.00),
(411, 18, 'Single Vision Lenses', 1, 1500.00),
(412, 18, 'Anti-Reflective Coating', 1, 700.00),
(413, 19, 'Consultation Fee', 1, 500.00),
(414, 19, 'Refraction Test', 1, 300.00),
(415, 19, 'Eyedrops – Lubricant', 1, 180.00),
(416, 20, 'Frames – Basic', 1, 1100.00),
(417, 20, 'Single Vision Lenses', 1, 1500.00),
(418, 20, 'Hard Coating', 1, 400.00),
(419, 21, 'Consultation Fee', 1, 500.00),
(420, 21, 'Refraction Test', 1, 300.00),
(421, 21, 'Eyedrops – Antibiotic', 1, 250.00),
(422, 22, 'Progressive Lenses', 1, 4200.00),
(423, 22, 'Frames – Premium', 1, 2800.00),
(424, 22, 'Anti-Reflective Coating', 1, 700.00),
(425, 23, 'Consultation Fee', 1, 500.00),
(426, 23, 'Eyedrops – Lubricant', 1, 180.00),
(427, 23, 'Refraction Test', 1, 300.00),
(428, 24, 'Frames – Basic', 1, 1200.00),
(429, 24, 'Single Vision Lenses', 1, 1500.00),
(430, 24, 'Blue Light Filter Add-on', 1, 600.00),
(431, 25, 'Consultation Fee', 1, 500.00),
(432, 25, 'Eyedrops – Antibiotic', 1, 250.00),
(433, 25, 'Refraction Test', 1, 300.00),
(434, 26, 'Frames – Premium', 1, 2800.00),
(435, 26, 'Progressive Lenses', 1, 4200.00),
(436, 26, 'UV Protection Add-on', 1, 500.00),
(437, 27, 'Consultation Fee', 1, 500.00),
(438, 27, 'Refraction Test', 1, 300.00),
(439, 27, 'Eyedrops – Lubricant', 1, 180.00),
(440, 28, 'Frames – Basic', 1, 1100.00),
(441, 28, 'Single Vision Lenses', 1, 1500.00),
(442, 28, 'Hard Coating', 1, 400.00),
(443, 29, 'Consultation Fee', 1, 500.00),
(444, 29, 'Refraction Test', 1, 300.00),
(445, 29, 'Eyedrops – Antibiotic', 1, 250.00),
(446, 30, 'Frames – Midrange', 1, 1800.00),
(447, 30, 'Single Vision Lenses', 1, 1500.00),
(448, 30, 'Blue Light Filter Add-on', 1, 600.00),
(449, 31, 'Consultation Fee', 1, 500.00),
(450, 31, 'Refraction Test', 1, 300.00),
(451, 31, 'Eyedrops – Lubricant', 1, 180.00),
(452, 32, 'Frames – Premium', 1, 2800.00),
(453, 32, 'Progressive Lenses', 1, 4200.00),
(454, 32, 'Anti-Reflective Coating', 1, 700.00),
(455, 33, 'Consultation Fee', 1, 500.00),
(456, 33, 'Refraction Test', 1, 300.00),
(457, 33, 'Eyedrops – Antibiotic', 1, 250.00),
(458, 34, 'Frames – Basic', 1, 1100.00),
(459, 34, 'Single Vision Lenses', 1, 1500.00),
(460, 34, 'UV Protection Add-on', 1, 500.00),
(461, 35, 'Consultation Fee', 1, 500.00),
(462, 35, 'Eyedrops – Lubricant', 1, 180.00),
(463, 35, 'Refraction Test', 1, 300.00),
(464, 36, 'Frames – Midrange', 1, 1800.00),
(465, 36, 'Single Vision Lenses', 1, 1500.00),
(466, 36, 'Hard Coating', 1, 400.00),
(467, 37, 'Consultation Fee', 1, 500.00),
(468, 37, 'Eyedrops – Antibiotic', 1, 250.00),
(469, 37, 'Refraction Test', 1, 300.00),
(470, 38, 'Frames – Premium', 1, 2800.00),
(471, 38, 'Progressive Lenses', 1, 4200.00),
(472, 38, 'Blue Light Filter Add-on', 1, 600.00),
(473, 39, 'Consultation Fee', 1, 500.00),
(474, 39, 'Refraction Test', 1, 300.00),
(475, 39, 'Eyedrops – Lubricant', 1, 180.00),
(476, 40, 'Frames – Basic', 1, 1200.00),
(477, 40, 'Single Vision Lenses', 1, 1500.00),
(478, 40, 'Anti-Reflective Coating', 1, 700.00),
(479, 41, 'Consultation Fee', 1, 500.00),
(480, 41, 'Eyedrops – Antibiotic', 1, 250.00),
(481, 41, 'Refraction Test', 1, 300.00),
(482, 42, 'Frames – Premium', 1, 2800.00),
(483, 42, 'Progressive Lenses', 1, 4200.00),
(484, 42, 'UV Protection Add-on', 1, 500.00),
(485, 43, 'Consultation Fee', 1, 500.00),
(486, 43, 'Refraction Test', 1, 300.00),
(487, 43, 'Eyedrops – Lubricant', 1, 180.00),
(488, 44, 'Frames – Basic', 1, 1100.00),
(489, 44, 'Single Vision Lenses', 1, 1500.00),
(490, 44, 'Blue Light Filter Add-on', 1, 600.00),
(491, 45, 'Consultation Fee', 1, 500.00),
(492, 45, 'Refraction Test', 1, 300.00),
(493, 45, 'Eyedrops – Antibiotic', 1, 250.00),
(494, 46, 'Frames – Midrange', 1, 1800.00),
(495, 46, 'Single Vision Lenses', 1, 1500.00),
(496, 46, 'Hard Coating', 1, 400.00),
(497, 47, 'Consultation Fee', 1, 500.00),
(498, 47, 'Refraction Test', 1, 300.00),
(499, 47, 'Eyedrops – Lubricant', 1, 180.00),
(500, 48, 'Frames – Premium', 1, 2800.00),
(501, 48, 'Progressive Lenses', 1, 4200.00),
(502, 48, 'Anti-Reflective Coating', 1, 700.00),
(503, 49, 'Consultation Fee', 1, 500.00),
(504, 49, 'Eyedrops – Antibiotic', 1, 250.00),
(505, 49, 'Refraction Test', 1, 300.00),
(506, 50, 'Frames – Basic', 1, 1100.00),
(507, 50, 'Single Vision Lenses', 1, 1500.00),
(508, 50, 'UV Protection Add-on', 1, 500.00),
(509, 51, 'Consultation Fee', 1, 500.00),
(510, 51, 'Refraction Test', 1, 300.00),
(511, 51, 'Eyedrops – Lubricant', 1, 180.00);

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `PatientID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Birthday` date NOT NULL,
  `Sex` enum('Male','Female') NOT NULL,
  `CivilStatus` enum('Single','Married','Widowed','Separated') NOT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Contact` varchar(15) DEFAULT NULL,
  `Occupation` varchar(50) DEFAULT NULL,
  `Allergies` text DEFAULT NULL,
  `Surgery` text DEFAULT NULL,
  `Injury` text DEFAULT NULL,
  `ChronicIllness` text DEFAULT NULL,
  `Medications` text DEFAULT NULL,
  `Eyedrops` text DEFAULT NULL,
  `Province` varchar(100) DEFAULT NULL,
  `City` varchar(100) DEFAULT NULL,
  `Barangay` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`PatientID`, `FirstName`, `LastName`, `Birthday`, `Sex`, `CivilStatus`, `Email`, `Contact`, `Occupation`, `Allergies`, `Surgery`, `Injury`, `ChronicIllness`, `Medications`, `Eyedrops`, `Province`, `City`, `Barangay`) VALUES
(1, 'Melrose', 'Oro', '2000-01-01', 'Female', 'Single', 'melrose_oro@gmail.com', '09123456789', 'Optometrist', '', '', '', '', '', '', 'Laguna', 'Nagcarlan', 'Poblacion Ii (Pob.)'),
(2, 'Melrose', 'Oro', '2000-01-01', 'Female', 'Single', 'melrose_oro@gmail.com', '09123456789', 'Optometrist', '', '', '', '', '', '', 'LAGUNA', 'LOS BAÑOS', 'BAYOG'),
(3, 'Jaspher Andrei', 'Malabanan', '2004-10-21', 'Male', 'Single', 'jaspherandreim@gmail.com', '09852288768', 'Student', 'Dust', 'None', 'Broken Rib', 'None', 'Multivitamins', 'Systane', 'DAVAO OCCIDENTAL', 'DON MARCELINO', 'SOUTH LAMIDAN'),
(8, 'James Harry', 'Ursolino', '2004-11-21', 'Male', 'Single', NULL, '09123456789', 'Student', '', '', '', '', '', '', 'Laguna', 'San Pablo City', 'Santa Maria'),
(9, 'Prince Harry', 'Windsor', '1987-10-10', 'Male', 'Married', NULL, '0985789752', 'Prince', '', '', '', '', '', '', 'Cotabato (North Cot.)', 'Makilala', 'Kawayanon'),
(14, 'Cj', 'Malabanan', '2025-12-03', 'Male', 'Married', NULL, '09123456789', NULL, '', '', '', '', '', '', 'BATANGAS', 'TAAL', 'GAHOL'),
(15, 'Denis', 'Menor', '2025-12-10', 'Female', 'Single', NULL, '09123456789', NULL, '', '', '', '', '', '', 'Davao Del Sur', 'Santa Cruz', 'Tibolo'),
(16, 'Dennise', 'Marie', '2025-12-09', 'Female', 'Married', NULL, '09123456789', NULL, '', '', '', '', '', '', 'COTABATO (NORTH COT.)', 'MATALAM', 'LATAGAN'),
(17, 'Maria', 'Santos', '1990-03-15', 'Female', 'Single', 'maria.santos@gmail.com', '09171234567', 'Teacher', 'None', 'Appendix removal', 'None', 'Asthma', 'Salbutamol', 'None', 'BATANGAS', 'BATANGAS CITY', 'POBLACION'),
(18, 'Juan', 'Dela Cruz', '1985-07-20', 'Male', 'Married', 'juan.dc@gmail.com', '09281234567', 'Engineer', 'Penicillin', 'None', 'Sprained ankle', 'Diabetes', 'Metformin', 'None', 'LAGUNA', 'CALAMBA', 'REAL'),
(19, 'Ana', 'Reyes', '1993-11-02', 'Female', 'Single', 'ana.reyes@yahoo.com', '09351234567', 'Nurse', 'Seafood', 'None', 'None', 'None', 'None', 'None', 'CAVITE', 'BACoor', 'MOLINO'),
(20, 'Mark', 'Lopez', '1991-01-25', 'Male', 'Married', 'mark.lopez@gmail.com', '09991234567', 'IT Specialist', 'None', 'None', 'None', 'Hypertension', 'Amlodipine', 'None', 'RIZAL', 'ANTIPOLO', 'SAN ROQUE'),
(21, 'Ella', 'Torres', '1998-08-14', 'Female', 'Single', 'ella.torres@gmail.com', '09181239876', 'Student', 'Dust', 'None', 'None', 'None', 'None', 'None', 'QUEZON', 'LUCENA', 'DALAHICAN'),
(22, 'Carlos', 'Villanueva', '1979-04-10', 'Male', 'Widowed', 'carlos.v@gmail.com', '09184561239', 'Driver', 'None', 'Gallbladder removal', 'Back injury', 'Arthritis', 'Ibuprofen', 'None', 'BATANGAS', 'TANAUAN', 'DARASA'),
(23, 'Rica', 'Morales', '2000-12-05', 'Female', 'Single', 'rica.m@gmail.com', '09276543211', 'Cashier', 'Pollen', 'None', 'None', 'None', 'None', 'None', 'LAGUNA', 'SAN PEDRO', 'LANDAYAN'),
(24, 'Leo', 'Garcia', '1988-09-19', 'Male', 'Married', 'leo.garcia@yahoo.com', '09353451234', 'Mechanic', 'Shellfish', 'None', 'Shoulder strain', 'None', 'None', 'None', 'CAVITE', 'DASMARINAS', 'SALAWAG'),
(25, 'Danica', 'Cruz', '1995-02-11', 'Female', 'Single', 'danica.cruz@gmail.com', '09175556677', 'Call Center Agent', 'None', 'Tonsil removal', 'None', 'None', 'None', 'None', 'QUEZON', 'TAYABAS', 'ALSAM'),
(26, 'Patrick', 'Gonzales', '1987-10-29', 'Male', 'Separated', 'patrick.g@gmail.com', '09994561234', 'Chef', 'Peanuts', 'None', 'None', 'None', 'None', 'None', 'BATANGAS', 'LIPA', 'BALINTAWAK'),
(27, 'Jasmine', 'Flores', '1992-06-18', 'Female', 'Married', 'jasmine.f@gmail.com', '09172349813', 'Bank Teller', 'Milk', 'None', 'None', 'None', 'None', 'None', 'CAVITE', 'IMUS', 'BAYAN LUMA'),
(28, 'Ramon', 'Salvador', '1980-12-22', 'Male', 'Married', 'ramon.salvador@gmail.com', '09182349817', 'Electrician', 'None', 'None', 'Knee injury', 'Hypertension', 'Losartan', 'None', 'RIZAL', 'TAYTAY', 'SAN ISIDRO'),
(29, 'Bianca', 'Mendoza', '2001-04-07', 'Female', 'Single', 'bianca.m@gmail.com', '09382341235', 'Student', 'None', 'None', 'None', 'Anemia', 'Iron supplements', 'None', 'LAGUNA', 'BINAN', 'MALAMIG'),
(30, 'Andre', 'Castillo', '1994-01-30', 'Male', 'Single', 'andre.c@gmail.com', '09276547890', 'Graphic Designer', 'None', 'None', 'None', 'None', 'None', 'None', 'BATANGAS', 'STO. TOMAS', 'POBLACION 3'),
(31, 'Faith', 'Navarro', '1989-03-21', 'Female', 'Widowed', 'faith.nav@gmail.com', '09083451234', 'Vendor', 'Chocolate', 'None', 'None', 'Asthma', 'Inhaler', 'None', 'CAVITE', 'GENERAL TRIAS', 'SAN FRANCISCO'),
(32, 'Daryl', 'Santiago', '1977-05-03', 'Male', 'Married', 'daryl.s@gmail.com', '09983331234', 'Technician', 'None', 'Cyst removal', 'Back pain', 'None', 'None', 'None', 'LAGUNA', 'LOS BANOS', 'BATONG MALAKE'),
(33, 'Mia', 'Robles', '1996-09-17', 'Female', 'Single', 'mia.r@gmail.com', '09184443322', 'Sales Associate', 'Shrimp', 'None', 'None', 'None', 'None', 'None', 'QUEZON', 'SARIAYA', 'POBLACION'),
(34, 'Julian', 'Peralta', '1993-07-26', 'Male', 'Married', 'julian.p@gmail.com', '09363334455', 'Architect', 'None', 'None', 'None', 'Hyperacidity', 'Antacid', 'None', 'RIZAL', 'BINANGONAN', 'TATALA'),
(36, 'Ivan', 'Domingo', '1984-11-09', 'Male', 'Married', 'ivan.d@gmail.com', '09273451266', 'Security Guard', 'Eggs', 'None', 'None', 'Hypertension', 'Amlodipine', 'None', 'BATANGAS', 'BAUAN', 'APLAYA'),
(37, 'Patricia', 'Lim', '1997-04-12', 'Female', 'Single', 'pat.lim@gmail.com', '09278945612', 'Student', 'None', 'None', 'None', 'Migraines', 'Pain relievers', 'None', 'QUEZON', 'INFANTA', 'POTOTAN'),
(38, 'Ethan', 'Cortez', '1990-08-30', 'Male', 'Married', 'ethan.c@gmail.com', '09197845623', 'Accountant', 'Pollen', 'None', 'None', 'None', 'None', 'None', 'RIZAL', 'CAINTA', 'SAN ANDRES'),
(39, 'Sofia', 'Ramos', '1994-02-19', 'Female', 'Single', 'sofia.r@gmail.com', '09083456712', 'Writer', 'None', 'None', 'None', 'None', 'None', 'None', 'LAGUNA', 'STA. ROSA', 'LABAS'),
(40, 'Victor', 'Ramirez', '1982-06-27', 'Male', 'Married', 'victor.r@gmail.com', '09184567321', 'Plumber', 'None', 'None', 'Shoulder strain', 'None', 'None', 'None', 'CAVITE', 'ROSARIO', 'WAWA'),
(41, 'Lara', 'Jimenez', '1998-10-04', 'Female', 'Single', 'lara.j@gmail.com', '09175678123', 'Clerk', 'None', 'None', 'None', 'None', 'None', 'None', 'BATANGAS', 'SAN JOSE', 'PALANAS'),
(42, 'Nathan', 'Ferrer', '1991-12-22', 'Male', 'Separated', 'nathan.f@gmail.com', '09987654321', 'Technician', 'Seafood', 'None', 'Hand injury', 'None', 'None', 'None', 'QUEZON', 'GUMACA', 'MAHARLIKA'),
(43, 'Isabelle', 'Velasco', '1996-05-13', 'Female', 'Single', 'isavel@gmail.com', '09289876543', 'Designer', 'Dust', 'None', 'None', 'None', 'None', 'None', 'CAVITE', 'NAIC', 'LABAC'),
(44, 'Harold', 'Santos', '1980-09-01', 'Male', 'Married', 'harold.s@gmail.com', '09359876512', 'Supervisor', 'None', 'Appendix removal', 'None', 'Hypertension', 'Losartan', 'None', 'BATANGAS', 'TALISAY', 'PINAGKAISAHAN'),
(46, 'Juan', 'Dela Cruz', '2000-01-01', 'Male', 'Single', 'juan.delacruz@gmail.com', '09123456789', 'Student', NULL, NULL, NULL, NULL, NULL, NULL, 'BATANGAS', 'BATANGAS CITY', 'ALANGILAN'),
(47, 'Juana', 'Dela Cruz', '2000-01-01', 'Female', 'Single', 'jd@gmail.com', '09123456789', NULL, '', '', '', '', '', '', 'BATANGAS', 'BATANGAS CITY', 'ALANGILAN'),
(48, 'John', 'Dc', '2000-01-01', 'Male', 'Single', 'jdc@gmail.com', '09123456789', NULL, '', '', '', '', '', '', 'BATANGAS', 'BATANGAS CITY', 'ALANGILAN'),
(49, 'James', 'Watson', '2000-01-01', 'Male', 'Single', 'james.watson@gmail.com', '09123456789', 'Student', NULL, NULL, NULL, NULL, NULL, NULL, 'BATANGAS', 'BATANGAS CITY', 'ALANGILAN'),
(50, 'Jenny', 'De Belen', '2000-01-01', 'Female', 'Married', 'jenny@gmail.com', '09123456789', NULL, '', '', '', '', '', '', 'BUKIDNON', 'BAUNGON', 'BALINTAD'),
(51, 'Imogen', 'Kinemerlu', '2001-01-01', 'Male', 'Single', 'jenny@gmail.com', '09123456789', NULL, '', '', '', '', '', '', 'BUKIDNON', 'BAUNGON', 'BALINTAD'),
(53, 'Tatakae', 'Eren', '2021-06-21', 'Male', 'Single', 'jenny@gmail.com', '09123456789', NULL, '', '', '', '', '', '', 'BUKIDNON', 'DON CARLOS', 'CALAOCALAO'),
(54, 'Germany', 'Germany', '2021-04-21', 'Female', 'Single', 'jenny@gmail.com', '09123456789', NULL, '', '', '', '', '', '', 'ILOCOS NORTE', 'PINILI', 'PUGAOAN'),
(55, 'Juan', 'Dela Cruz', '2001-01-01', 'Male', 'Single', 'juan.dela.cruz@gmail.com', '09123456789', 'Student', NULL, NULL, NULL, NULL, NULL, NULL, 'BATANGAS', 'BATANGAS CITY', 'ALANGILAN'),
(56, 'James', 'Smith', '2000-01-01', 'Male', 'Single', 'james.smith@gmail.com', '09123456789', 'Student', NULL, NULL, NULL, NULL, NULL, NULL, 'BATANGAS', 'BATANGAS CITY', 'ALANGILAN'),
(57, 'Joanna', 'Smith', '2001-01-01', 'Female', 'Married', 'joanna.smith@gmail.com', '09123456789', NULL, '', '', '', '', '', '', 'BATANGAS', 'BATANGAS CITY', 'ALANGILAN');

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

CREATE TABLE `prescription` (
  `PrescriptionID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `PatientID` int(11) NOT NULL,
  `Sph_OD` decimal(4,2) DEFAULT NULL,
  `Cyl_OD` decimal(4,2) DEFAULT NULL,
  `Axis_OD` tinyint(4) DEFAULT NULL,
  `Sph_OS` decimal(4,2) DEFAULT NULL,
  `Cyl_OS` decimal(4,2) DEFAULT NULL,
  `Axis_OS` tinyint(4) DEFAULT NULL,
  `AddPower` decimal(3,2) DEFAULT NULL,
  `Prism` varchar(20) DEFAULT NULL,
  `LensType` enum('Single','Bifocal','Progressive','Reading') DEFAULT NULL,
  `Material` enum('Plastic','Polycarbonate','High-index','Glass') DEFAULT NULL,
  `pd` decimal(4,1) DEFAULT NULL,
  `Notes` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prescription`
--

INSERT INTO `prescription` (`PrescriptionID`, `Date`, `PatientID`, `Sph_OD`, `Cyl_OD`, `Axis_OD`, `Sph_OS`, `Cyl_OS`, `Axis_OS`, `AddPower`, `Prism`, `LensType`, `Material`, `pd`, `Notes`) VALUES
(1, '2025-11-30', 2, -2.00, -1.00, 30, -1.50, -0.75, 60, NULL, NULL, NULL, NULL, NULL, 'Not normal'),
(3, '2020-01-10', 1, -1.50, -0.75, 90, -1.25, -0.50, 85, 0.00, 'None', 'Single', 'Polycarbonate', 63.0, 'Mild myopia, stable'),
(4, '2020-03-18', 2, -2.25, -1.00, 100, -2.00, -0.75, 95, 0.00, 'None', 'Single', 'Plastic', 64.5, 'Updated due to headaches'),
(5, '2020-05-22', 3, -0.75, -0.50, 80, -1.00, -0.25, 70, 0.00, 'None', 'Reading', 'Glass', 62.0, 'Reading difficulty'),
(6, '2020-07-14', 8, -3.00, -1.25, 120, -2.75, -1.00, 115, 0.00, 'None', 'Single', 'Polycarbonate', 61.5, 'High myopia'),
(7, '2020-09-03', 9, 1.25, -0.50, 45, 1.50, -0.25, 40, 1.50, 'None', 'Bifocal', 'Plastic', 65.0, 'Presbyopia emerging'),
(8, '2020-11-27', 14, -1.00, -0.25, 60, -0.75, -0.25, 55, 0.00, 'None', 'Single', 'High-index', 63.5, 'Stable prescription'),
(9, '2021-01-05', 15, -4.25, -1.50, 10, -4.00, -1.25, 15, 0.00, 'None', 'Single', 'Polycarbonate', 62.5, 'High myopia long term'),
(10, '2021-02-19', 16, 0.75, -0.50, 90, 1.00, -0.25, 85, 2.00, 'Vertical 1Δ', 'Progressive', 'Plastic', 64.0, 'Add power for near work'),
(11, '2021-04-01', 17, -2.00, -1.25, 110, -1.75, -1.00, 105, 0.00, 'None', 'Single', 'Polycarbonate', 61.0, 'Complaints of glare'),
(12, '2021-06-24', 18, 2.00, -0.75, 30, 2.25, -0.50, 25, 1.25, 'Horizontal 1Δ', 'Bifocal', 'Glass', 66.0, 'Presbyopia'),
(13, '2021-08-05', 19, -0.50, -0.25, 70, -0.25, -0.25, 65, 0.00, 'None', 'Reading', 'Plastic', 63.0, 'Computer eye strain'),
(14, '2021-10-17', 20, -3.50, -1.50, 100, -3.75, -1.25, 95, 0.00, 'None', 'Single', 'High-index', 62.0, 'Frequent floaters'),
(15, '2021-12-03', 21, 1.50, -0.50, 95, 1.75, -0.25, 90, 1.75, 'None', 'Progressive', 'Plastic', 65.5, 'Difficulty reading menus'),
(16, '2022-01-26', 22, 0.25, -0.25, 80, 0.50, -0.25, 75, 2.00, 'None', 'Bifocal', 'Glass', 64.0, 'Presbyopia onset'),
(17, '2022-03-11', 23, -1.75, -0.75, 127, -1.50, -0.50, 125, 0.00, 'None', 'Single', 'Polycarbonate', 63.5, 'Astigmatism correction'),
(18, '2022-05-20', 24, -0.25, -0.50, 55, -0.25, -0.25, 50, 0.00, 'None', 'Reading', 'Plastic', 62.5, 'Occasional blur'),
(19, '2022-07-02', 25, -2.50, -1.00, 127, -2.75, -1.25, 127, 0.00, 'None', 'Single', 'High-index', 61.8, 'Night driving issues'),
(20, '2022-09-15', 26, 1.00, -0.25, 15, 1.25, -0.25, 10, 2.25, 'None', 'Progressive', 'Plastic', 65.2, 'Frequent near tasks'),
(21, '2022-11-27', 27, -4.00, -1.50, 127, -3.75, -1.25, 127, 0.00, 'None', 'Single', 'Polycarbonate', 62.3, 'High myopia'),
(22, '2023-01-06', 28, -1.25, -0.50, 45, -1.00, -0.75, 40, 0.00, 'None', 'Single', 'Plastic', 63.0, 'Digital strain'),
(23, '2023-02-18', 29, 0.50, -0.25, 85, 0.75, -0.25, 80, 1.50, 'None', 'Bifocal', 'Glass', 66.0, 'Difficulty reading small text'),
(24, '2023-04-04', 30, -2.75, -1.00, 125, -2.50, -0.75, 120, 0.00, 'None', 'Single', 'High-index', 62.0, 'OS discomfort reported'),
(25, '2023-05-29', 31, -0.50, -0.25, 95, -0.75, -0.25, 90, 0.00, 'None', 'Reading', 'Plastic', 63.7, 'Mild eye fatigue'),
(26, '2023-07-13', 32, 1.25, -0.50, 55, 1.25, -0.25, 50, 2.00, 'None', 'Progressive', 'Polycarbonate', 64.5, 'Reading blur increasing'),
(27, '2023-08-30', 33, -5.00, -1.75, 10, -4.75, -1.50, 15, 0.00, 'None', 'Single', 'High-index', 61.0, 'Strong myopia'),
(28, '2023-10-14', 1, -2.00, -0.75, 110, -1.75, -1.00, 105, 0.00, 'None', 'Single', 'Polycarbonate', 62.5, 'Blur when driving'),
(29, '2023-11-28', 2, 0.75, -0.25, 125, 1.00, -0.25, 120, 1.75, 'None', 'Bifocal', 'Plastic', 65.0, 'Difficulty reading fine print'),
(30, '2024-01-05', 3, -3.25, -1.25, 127, -3.00, -1.00, 127, 0.00, 'None', 'Single', 'High-index', 62.0, 'Astigmatism progressing'),
(31, '2024-02-12', 8, 1.50, -0.50, 45, 1.75, -0.25, 40, 2.25, 'Vertical 1Δ', 'Progressive', 'Glass', 66.3, 'Difficulty with near work'),
(32, '2024-03-18', 9, -0.75, -0.50, 90, -1.00, -0.25, 85, 0.00, 'None', 'Single', 'Plastic', 63.8, 'Dry eye symptoms'),
(33, '2024-04-10', 14, -2.00, -1.00, 127, -1.75, -0.75, 125, 0.00, 'None', 'Single', 'Polycarbonate', 62.1, 'New corrective lenses needed'),
(34, '2024-05-01', 15, 2.25, -0.50, 20, 2.50, -0.50, 15, 2.50, 'None', 'Progressive', 'High-index', 65.9, 'Reading difficulty increased'),
(35, '2024-06-09', 16, -1.50, -0.75, 88, -1.25, -0.50, 90, 0.00, 'None', 'Single', 'Plastic', 63.3, 'Computer strain'),
(36, '2024-07-27', 17, 0.25, -0.50, 75, 0.50, -0.25, 70, 1.50, 'None', 'Bifocal', 'Glass', 65.1, 'Age-related changes');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `ProductID` int(11) NOT NULL,
  `Brand` varchar(50) NOT NULL,
  `Description` text NOT NULL,
  `Quantity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`ProductID`, `Brand`, `Description`, `Quantity`) VALUES
(1, 'Essilor', 'Crizal UV Blue Light Filter Lenses', 0),
(2, 'Essilor', 'Transitions Signature Gen 8', 21),
(3, 'Essilor', 'Crizal Easy Anti-Reflective Lenses', 0),
(4, 'Zeiss', 'Zeiss Cleaning Solution 60ml', 0),
(5, 'Zeiss', 'Zeiss AntiFOG Wipes (30 sheets)', 0),
(6, 'Zeiss', 'Zeiss Single Vision Hard Coat Lenses', 0),
(7, 'Hoya', 'Hoya BlueControl Lens', 0),
(8, 'Hoya', 'Hoya MultiCoat Lens', 0),
(9, 'Hoya', 'Hoya Suntech Photochromic Lens', 0),
(10, 'Kodak', 'Kodak UV400 Single Vision', 0),
(11, 'Kodak', 'Kodak Clean&Clear Coated Lens', 0),
(12, 'Kodak', 'Kodak Photochromic Brown', 0),
(13, 'Nikon', 'Nikon SeeCoat Blue Premiere', 0),
(14, 'Nikon', 'Nikon Presio Power Progressive', 0),
(15, 'Nikon', 'Nikon Lite AS Lens', 0),
(16, 'AO Eyewear', 'AO Premium Glass Lenses', 0),
(17, 'AO Eyewear', 'AO Anti-Scratch Coated Lenses', 0),
(18, 'Generic', 'Lens Cleaning Cloth – Microfiber', 0),
(19, 'Generic', 'Eyeglass Case – Hard Shell', 0),
(20, 'Generic', 'Eyeglass Repair Mini Toolkit', 3);

-- --------------------------------------------------------

--
-- Table structure for table `test_record`
--

CREATE TABLE `test_record` (
  `TestID` int(11) NOT NULL,
  `CaseID` int(11) NOT NULL,
  `CoverTest` varchar(100) DEFAULT NULL,
  `MotilityTest` varchar(255) DEFAULT NULL,
  `Perrla` varchar(50) DEFAULT NULL,
  `CornealReflection` varchar(100) DEFAULT NULL,
  `DiplopiaTest` varchar(255) DEFAULT NULL,
  `Notes` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `test_record`
--

INSERT INTO `test_record` (`TestID`, `CaseID`, `CoverTest`, `MotilityTest`, `Perrla`, `CornealReflection`, `DiplopiaTest`, `Notes`) VALUES
(3, 3, 'Esotropia 10Δ', 'Full range of motion', 'PERRLA', 'Symmetric', 'No diplopia', 'Possible accommodative component'),
(4, 4, 'Orthophoric', 'Smooth pursuit intact', 'PERRLA', 'Reflection slightly nasal OS', 'No diplopia', 'Dry eye noted'),
(5, 5, 'Intermittent exotropia', 'Small overaction OS on upgaze', 'PERRLA', 'Symmetric', 'Diplopia present in extreme right gaze', 'Patient reports eye strain at work'),
(9, 9, 'Orthophoric', 'Normal pursuits and saccades', 'PERRLA', 'Symmetric', 'No diplopia', 'All tests within normal limits'),
(10, 10, 'Esotropia 5Δ', 'Full movement', 'PERRLA', 'Centered but faint', 'Occasional diplopia near', 'Monitor progression over time');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `PatientID` int(11) NOT NULL,
  `Password` char(60) NOT NULL,
  `Role` enum('Patient','Admin') NOT NULL DEFAULT 'Patient'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`PatientID`, `Password`, `Role`) VALUES
(1, '$2b$12$NBz4pjdLAYxMjjQiDuJVLOem6T04TSm..5I4ogn1bjNaQFQqRQBDy', 'Admin'),
(2, '$2b$12$ppPrcM/yAQvMjy/CXyf13.yG2UYEjdBht.PBGnmT1jWw/l.v5IU/u', 'Patient'),
(8, '$2y$10$Oq2WlUiKEg7roIb5JWI.SOTWCMPiMJhuObtyWpS7PrHCKEXr7l9lS', 'Patient'),
(9, '$2y$10$A45lYTtaQfuRdpkC8R2ELOWkqUeAd33ZSVULAi/9LugjtRWxPlyqq', 'Patient'),
(15, '$2y$10$2btneuBt3rtjWfphkNSz0u/5PQdT7xrFKNO4AkO1zGBQUvlb7fuW.', 'Patient'),
(16, '$2y$10$t1BO5F34tqc70C/tETcVoO6B45/7E7OS8N6QcWVSZ58b.PwlcCZhe', 'Patient'),
(46, '$2y$10$OOz4PxX3f/.dAZSDwyL0T.DMK5NmOdYZQeuru7VwbmzGOotyI51WS', 'Patient'),
(47, '$2y$10$XvEnBnQuYRIK4BoyrZNIUuNNATfsOY066sR1Arp6BmXU0sXbQFhY.', 'Patient'),
(48, '$2y$10$jCIMKatJFpvZiRpoiYXR8.JapPZkuVf1puqmUYeoO3uwy.gWWYaUu', 'Patient'),
(49, '$2y$10$xfvxWu6T8UV4A9eVLDE8MO34Hop8rXeabL90h4L63HD2M6aMqXIhS', 'Patient'),
(50, '$2y$10$02q.HOGgwOkZ1zn6bT.Gz.E0I15ougKL1UaV0uCfr/joFhAvF.ES.', 'Patient'),
(51, '$2y$10$Gl2y8gtCKyf.HFsl1XTFfurc7u45/Ir.tBiQqm1q3fy4Pe8TP7G5q', 'Patient'),
(53, '$2y$10$i6i9nhHvSE8KM.Y9tCDRwepmQaAB7XIQLP7XYtpzkNDArINNWvVWm', 'Patient'),
(54, '$2y$10$G3fZuuBr3QuIyrxampPWLe.EWkiYgufbGbsJjy30Mk7vVbCbQnR5W', 'Patient'),
(55, '$2y$10$wLDdJTLxrfcXRPt3wBvqquD7L90LswoEqfwL9K3VM5ZGBj.Vp9SDy', 'Patient'),
(56, '$2y$10$ee5cs/BiaZkfymwTP2k/9OT2ti776ssVUBMs1.kL3usd.XtLm4nCK', 'Patient'),
(57, '$2y$10$TiaLXuV/coXhGuDrO./aLuecJBRX7ZVGf1D.h6gXmDORzwqmePxM.', 'Patient');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `case_record`
--
ALTER TABLE `case_record`
  ADD PRIMARY KEY (`CaseID`),
  ADD KEY `PatientID` (`PatientID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`EmployeeID`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`InvoiceID`),
  ADD KEY `PatientID` (`PatientID`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `InvoiceID` (`InvoiceID`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`PatientID`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`PrescriptionID`),
  ADD KEY `PatientID` (`PatientID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`ProductID`);

--
-- Indexes for table `test_record`
--
ALTER TABLE `test_record`
  ADD PRIMARY KEY (`TestID`),
  ADD KEY `CaseID` (`CaseID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD KEY `PatientID` (`PatientID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `case_record`
--
ALTER TABLE `case_record`
  MODIFY `CaseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `EmployeeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `InvoiceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `ItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=512;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `PatientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `PrescriptionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `test_record`
--
ALTER TABLE `test_record`
  MODIFY `TestID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `case_record`
--
ALTER TABLE `case_record`
  ADD CONSTRAINT `case_record_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`) ON DELETE CASCADE;

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`InvoiceID`) REFERENCES `invoice` (`InvoiceID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prescription`
--
ALTER TABLE `prescription`
  ADD CONSTRAINT `prescription_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`) ON DELETE CASCADE;

--
-- Constraints for table `test_record`
--
ALTER TABLE `test_record`
  ADD CONSTRAINT `test_record_ibfk_1` FOREIGN KEY (`CaseID`) REFERENCES `case_record` (`CaseID`) ON DELETE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
