--
-- Table structure for table `tbl_employee`
--

CREATE TABLE `tbl_employee` (
  `id` int(11) NOT NULL COMMENT 'primary key',
  `employee_name` varchar(255) NOT NULL COMMENT 'employee name',
  `employee_salary` double NOT NULL COMMENT 'employee salary',
  `employee_age` int(11) NOT NULL COMMENT 'employee age'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='datatable demo table';

--
-- Dumping data for table `tbl_employee`
--

INSERT INTO `tbl_employee` (`id`, `employee_name`, `employee_salary`, `employee_age`) VALUES
(1, 'Zim Kary', 20800, 61),
(2, 'George', 10750, 63),
(6, 'Williamson', 37200, 61),
(7, 'Harry', 137500, 59),
(8, 'David', 32790, 55),
(11, 'Jenny', 90560, 40),
(13, 'Kim', 70600, 36);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_employee`
--
ALTER TABLE `tbl_employee`
  ADD PRIMARY KEY (`id`);
COMMIT;