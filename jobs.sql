
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



CREATE TABLE `jobs` (
  `job_ref` char(5) NOT NULL,
  `category` varchar(100) NOT NULL,
  `job_title` varchar(150) NOT NULL,
  `team` varchar(150) NOT NULL,
  `reports_to` varchar(150) NOT NULL,
  `work_mode` varchar(150) NOT NULL,
  `type_and_hours` varchar(200) NOT NULL,
  `salary` varchar(200) NOT NULL,
  `about_role` text NOT NULL,
  `key_responsibilities` text NOT NULL,
  `essential_requirements` text NOT NULL,
  `preferable_criteria` text NOT NULL,
  `performance_goals` text NOT NULL,
  `benefits` text NOT NULL,
  `equal_opportunity` text NOT NULL,
  `closing_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `jobs` (`job_ref`, `category`, `job_title`, `team`, `reports_to`, `work_mode`, `type_and_hours`, `salary`, `about_role`, `key_responsibilities`, `essential_requirements`, `preferable_criteria`, `performance_goals`, `benefits`, `equal_opportunity`, `closing_date`) VALUES
('VF001', 'Software & Web Development', 'E-Commerce UX/UI Front-End Developer', 'Digital Retail UI Team', 'Lead Digital Retail Architect', 'Hybrid (Ho Chi Minh City / Remote)', 'Full-Time, flexible hours (core hours 10:00 AM - 4:00 PM ICT)', '30,000,000 - 45,000,000 VND per month (gross, negotiable based on experience)', 'Design and develop responsive web interfaces for the digital retail platform. Combine visual prototyping with front-end engineering to optimise the vehicle purchasing journey.', 'Create wireframes and interactive prototypes in Figma for vehicle configurations.\r\nBuild responsive web interfaces for vehicle configuration and online ordering.\r\nMaintain web accessibility standards (WCAG) across all customer-facing retail pages.\r\nImplement loading states and performance budgets to optimise page speed.\r\nIntegrate interfaces with order, payment and inventory APIs, minimising third-party dependencies.', 'Bachelor\'s degree in Computer Science, Software Engineering, Design, Human-Computer Interaction, or a related field.\r\nMinimum 3 years of commercial experience in front-end development or UX/UI design.\r\nExpert-level proficiency in Figma.\r\nStrong proficiency in semantic HTML5, CSS3, and core JavaScript.\r\nPractical knowledge of web accessibility standards and ARIA practices.', 'Professional working proficiency in English (IELTS 6.0 equivalent or higher).\r\nExperience with modern front-end frameworks (such as React or Vue.js).\r\nFamiliarity with secure data handling practices for online checkout systems.\r\nPrevious experience in e-commerce or automotive digital retail platforms.', '60-day goal: Deliver reusable checkout and configurator components with zero critical errors in an accessibility audit.\r\n90-day goal: Raise vehicle-configurator completion rate by 15%.', 'Comprehensive health insurance, flexible working hours, and employee discounts on eligible company products.', 'VinFast Digital Retail is an equal opportunity employer, considering candidates regardless of gender, age, ethnicity, religion, or disability.', '2026-10-09'),
('VF002', 'Product & Retail Merchandising', 'Digital Merchandising & Catalogue Specialist', 'E-Commerce Operations Team', 'Head of Digital Merchandising', 'Hybrid (Ho Chi Minh City / Remote)', 'Full-Time, flexible hours (core hours 10:00 AM - 4:00 PM ICT)', '15,000,000 - 25,000,000 VND per month (gross)', 'Manage digital product displays and catalogue data across VinFast\'s online retail platform. Ensure vehicle listings and accessories for models like the VF 2 are accurate and positioned to maximise online sales.', 'Maintain accurate pricing, specifications, and imagery for digital vehicle and accessory listings.\r\nStructure catalogue data in the CMS and keep product tags and compatibility attributes correct.\r\nConduct weekly audits of product detail pages to correct data discrepancies.\r\nCoordinate storefront layouts and promotional campaigns for accessories and upcoming launches.\r\nRun A/B tests on product detail pages to identify effective merchandising layouts.', 'Bachelor\'s degree in Business, Marketing, E-Commerce, or a related field.\r\nMinimum 2 years of experience in e-commerce catalogue management or digital merchandising.\r\nProficiency in Content Management Systems and Product Information Management tools.\r\nAbility to interpret sales and engagement data.\r\nWorking knowledge of A/B testing methodologies.', 'Professional working proficiency in English (IELTS 6.0 equivalent or higher).\r\nExperience managing complex automotive product variants.\r\nUnderstanding of SEO best practices for product pages.', '30-day goal: Achieve at least 98% data accuracy in a catalogue audit of the VF MPV 7.\r\n90-day goal: Raise accessory conversion rate by 12% through layout adjustments.', 'Comprehensive health insurance, flexible working hours, and employee discounts on eligible company products.', 'VinFast Digital Retail is an equal opportunity employer, considering candidates regardless of gender, age, ethnicity, religion, or disability.', '2026-10-09'),
('VF003', 'Data & Analytics', 'E-Commerce Data Analyst', 'Digital Retail Analytics Team', 'Head of Data Analytics', 'Hybrid (Ho Chi Minh City / Remote)', 'Full-Time, flexible hours (core hours 10:00 AM - 4:00 PM ICT)', '20,000,000 - 35,000,000 VND per month (gross)', 'Analyse digital retail data to optimise the customer journey for VinFast\'s online retail platform. Provide actionable insights to improve sales performance for recent launches.', 'Track and report on e-commerce metrics including conversion rates and customer drop-offs.\r\nAnalyse user behaviour during digital pre-ordering workflows for upcoming launches.\r\nBuild dashboards in Power BI or Tableau to visualise daily online retail performance.\r\nIdentify traffic bottlenecks to help front-end teams optimise page load speed.\r\nProvide data-driven recommendations to improve the digital checkout experience.', 'Bachelor\'s degree in Data Science, Statistics, Computer Science, or a related field.\r\nMinimum 2 years of experience in e-commerce data analysis.\r\nProficiency in SQL and data visualisation tools.\r\nStrong analytical skills to interpret complex web traffic data.', 'Professional working proficiency in English (IELTS 6.0 equivalent or higher).\r\nExperience with Python or R for advanced data modelling.\r\nBackground in automotive retail analytics.', '30-day goal: Build one tracking dashboard for the VF MPV 7 digital sales funnel.\r\n90-day goal: Deliver evidence-backed recommendations for two major checkout drop-off points, adopted by the product teams within 90 days.', 'Comprehensive health insurance, flexible working hours, and employee discounts on eligible company products.', 'VinFast Digital Retail is an equal opportunity employer, considering candidates regardless of gender, age, ethnicity, religion, or disability.', '2026-10-09'),
('VF004', 'Customer Service & Operations', 'Customer Experience CRM Manager', 'Customer Experience Team', 'Director of E-Commerce', 'Hybrid (Ho Chi Minh City / Remote)', 'Full-Time, flexible hours (core hours 10:00 AM - 4:00 PM ICT)', '40,000,000 - 65,000,000 VND per month (gross)', 'Lead customer relationship management initiatives to enhance the digital purchasing journey for VinFast\'s online customers. Design and execute automated communication strategies to support the online retail platform.', 'Manage the CRM platform to track customer interactions across the digital retail ecosystem.\r\nDevelop automated email and SMS workflows to nurture leads for upcoming launches.\r\nSegment customer data to deliver personalised post-purchase communications.\r\nMonitor customer satisfaction metrics to identify friction points in the e-commerce journey.\r\nCollaborate with the Data Analytics team to measure the ROI of retention campaigns.', 'Bachelor\'s degree in Marketing, Business, Communications, or a related field.\r\nMinimum 4 years of experience managing enterprise CRM systems (such as Salesforce or HubSpot).\r\nProven track record in designing customer journey maps and automated marketing workflows.\r\nStrong analytical skills to interpret engagement data and customer feedback.', 'Professional working proficiency in English (IELTS 6.0 equivalent or higher).\r\nExperience in the automotive sector or high-value e-commerce retail.\r\nFamiliarity with data privacy regulations regarding customer communications.', '60-day goal: Deploy an automated post-purchase email sequence for the VF MPV 7 with an open rate of at least 40%.\r\n90-day goal: Raise repeat-purchase rate by 8% through targeted re-engagement campaigns.', 'Comprehensive health insurance, flexible working hours, and employee discounts on eligible company products.', 'VinFast Digital Retail is an equal opportunity employer, considering candidates regardless of gender, age, ethnicity, religion, or disability.', '2026-10-09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_ref`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
