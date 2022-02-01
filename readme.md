![WordPress LMS](https://ps.w.org/learning-management-system/assets/banner-1544x500.png?rev=2599799)

# LMS for WordPress ![status](https://github.com/wpeverest/wordpress-lms/actions/workflows/deploy-to-staging.yml/badge.svg)

**Contributors**: Masteriyo

**Tags**: lms, learning management system, courses, elearning

**Requires at least**: 5.0

**Tested up to**: 5.9

**Requires PHP**: 7.0

**Stable tag**: 1.4.0

**License: GNU** General Public License v3.0

**License URI**: http://www.gnu.org/licenses/gpl-3.0.html

A Complete WordPress LMS plugin to create and sell online courses in no time.

# Description

A complete solution to create and sell online courses. The plugin is powered by React js; hence creating courses, lessons, quizzes are super smooth and easy. The best part is the beautifully designed interactive learn page with easy course navigation, distraction-free mode, Course Progress bar, and dedicated place for Questions and Answers.

### Features And Options:

- Unlimited courses and lessons
- Unlimited Quizzes
- Sell courses
- Accept Payment via PayPal
- Clean Design
- Interactive Learn Page
- Distraction Free Mode
- Questions and Answers
- Course Progress Bar
- Advanced Quiz Builder
- Responsive Design
- Compatible with any theme

# Frequently Asked **Questions**

### Do I need to have coding skills to use the LMS Plugin?

No, you don't need any coding skills. One can click and create courses, lessons and publish.

# Contribute to Masteriyo

Want to contribute? Please have a look at [Contributor Guidelines](docs/contributor-guide.md)

# Changelog

### 1.4.0 - 01-02-2022

- Enhancement - Migrate Account page from PHP to React JS.
- Enhancement - For Instructor, account page now comes with 'Add New Course' and 'My Courses' menu options.
- Enhancement - Minor enhancements in account page.
- Tweak - Add admin notice for plugin review.
- Fix - Delete order items from database when order is deleted.
- Fix - Main pages duplication issue after plugin reactivate.

### 1.3.13 - 24-01-2022

- Enhancement - Push floating navigation previous button to the right if sidebar is opened.
- Fix - WP Media Manager file upload permission for instructor.

### 1.3.12 - 20-01-2022

- Fix - Minor fixes.

### 1.3.11 - 20-01-2022

- Enhancement - Redirect to first quiz or lesson on learning page.
- Enhancement - The sidebar on the learning page now opens by default.
- Enhancement - Active menu section now expands by default on the sidebar of the learning page.
- Enhancement - Active menu item is now highlighted on the sidebar.
- Enhancement - Global settings UI.
- Enhancement - Text strings refinement.
- Enhancement - Updated 'Start Course' text to 'Continue' and 'Completed' for in-progress and completed course respectively.
- Enhancement - Open the first section by default for the curriculum tab on the single course page.

### 1.3.10 - 13-01-2022

- Fix - Not able to set WordPress default role by an administrator.
- Fix - Image blurring in courses and single course page.
- Fix - Course sections, quizzes, lessons and questions are not completely deleted.
- Fix - CSS issue in Spacious theme single course page.
- Enhancement - Added support for multisite.
- Enhancement - Back to course button does not align properly in learn page sidebar.
- Enhancement - Singular and plural text for string Answer and Answers in learn page QA chat and questions list.
- Enhancement - Added respective icon for lesson containing videos in learn page navigation.

### 1.3.9 - 06-01-2022

- Fix - Active courses, enrolled courses and course progress status not working on the Account page.

### 1.3.8 - 04-01-2022

- Feature - Forms to add students and instructors in backend.
- Feature - Added setting to add unique logo image to learn page.
- Enhancement - Added currency position info in payment settings inside the setup wizard.
- Enhancement - Remove unnecessary fields from the payment settings inside the setup wizard.
- Enhancement - Make the pages tab more informative in the setup wizard.
- Enhancement - Change question display per page setting from input to a slider in the setup wizard.
- Enhancement - Change course per page setting from input to a slider in the setup wizard.
- Enhancement - Liked the site logo in the learn page to the site homepage.
- Enhancement - Improved UX in the setup wizard.
- Enhancement - Remove unnecessary fields from the advance settings tab.
- Enhancement - Added one more option to the single course permalink setting.
- Enhancement - Check/Uncheck quiz-radio input when clicked on its label inside the learn page.
- Fix - Typo 'pervious' in the learn page
- Fix - Admins not being listed in instructor setting of a course.
- Fix - Blocks not being translated.
- Fix - Blocks properly not working in the widgets editor and the theme customizer.
- Fix - Mark as complete button not working as expected for guest users in learn page.
- Fix - Students not being able to update the country and address fields.

### 1.3.7 - 28-12-2021

- Fix - Course categories block CSS issue.

### 1.3.6 - 28-12-2021

- Enhancement - Getting started page.
- Enhancement - Refactor global settings.
- Enhancement - String refinements.
- Fix - 505 error due to google translation.
- Fix - Log file size increase due to invalid callbacks.
- Fix - Student account details update issue.

### 1.3.5 - 21-12-2021

- Fix - Getting started steps issue.

### 1.3.4 - 21-12-2021

- Enhancement - Added scrolling on learn page sidebar.
- Enhancement - Implemented database migration.
- Enhancement - Decreased the JS bundle size.
- Enhancement - Improve the plugin performance.
- Tweak - Show name on delete confirm modal in section builder.
- Fix - Question search on learn page sidebar.
- Fix - Classic editor compatibility issue.
- Fix - Used admin email address as a sender email address.

### 1.3.3 - 15-12-2021

- Fix - Quiz result not showing after submitting a quiz.
- Fix - Disable start quiz button on attempts limit reached.
- Fix - Edit button and menu layout design issue on categories backend.
- Tweak - By default, set question point to 1.
- Tweak - By default, set attempts allowed to no limit on quiz setting.
- Tweak - Renamed strings 'Full mark' and 'Pass mark' to 'Full point' and 'Pass Point' on quiz setting.

### 1.3.2 - 10-12-2021

- Enhancement - Quiz Attempts Listing on the back-end.
- Enhancement - Allow the administrator to change the instructor of the course.
- Enhancement - Added users/me REST API endpoint.
- Enhancement - Optimize images.
- Enhancement - Populate checkout form with current user information.
- Tweak - Remove the description column from the category list.
- Tweak - Optimize the toast messages in the back-end.
- Tweak - Moved the delete menu inside the order edit page to right.
- Fix - Paid course being converted to free course issue.
- Fix - Back to course button link in the course complete message.
- Fix - Section title issue in the delete confirmation message.
- Fix - Pagination button jumping up and down.

### 1.3.1 - 30-11-2021

- Feature - Allow manual order creation from the backend by an administrator.
- Enhancement - Added user course REST API.
- Tweak - Load students and instructs list in descending order by ID.
- Tweak - Renamed plugin slug from admin notices.
- Fix - Fatal error due to PHP dependency issue.

### 1.3.0 - 22-11-2021

- Feature - Instructor registration system.
- Feature - Instructor approval system.
- Feature - Instructor listing in the backend.
- Feature - Added courses gutenberg block.
- Feature - Added courses categories gutenberg block.
- Tweak - Added author support to course post type.
- Tweak - Added support of links in the editor.
- Fix - Remove the static ANSWERED from the floating quiz timer in learn page.

### 1.2.1 - 12-11-2021

- Enhancement - Added unlimited quiz attempt limit option.
- Enhancement - Display courses page content in the courses list page.
- Enhancement - Show completion icon to the completed lesson and quiz in the learn page.
- Enhancement - Added course completion header in learn page.
- Fix - Quiz limit attempt share between users.
- Fix - Translation strings for quiz in learn page.

### 1.2.0 - 02-11-2021

- Fix - Course update and delete when the PUT and DELETE HTTP methods are blocked.
- Feature - Added students list on WordPress backend.
- Feature - Added course categories shortcode [masteriyo_course_categories].

### 1.0.10 - 29-10-2021

- Fix - Missing translation in learn page.
- Fix - Standard paypal sandbox toggle issue.

### 1.0.9 - 26-10-2021

- Fix - Add to cart button text filter in courses and single course page.
- Fix - Backend order API listing issue when the course in order is deleted.
- Tweak - Changed single course heading tag to h1.

### 1.0.8 - 22-10-2021

- Tweak - Show admin error notice when assets are not built.
- Tweak - Show admin error notice when autoload.php file does not exist.
- Fix - Editor not typing issue.
- Fix - Design issue in safari.
- Fix - Elementor width issue.

### 1.0.7 - 18-10-2021

- Fix - Missing translations.
- Fix - Course pricing setting for free option acting inconsistently.
- Fix - Categories listing issue on add/edit course categories select option for categories greater than 20.
- Fix - Special chars issue in the course, lesson, section, and quiz name.
- Enhancement - Remove the terms and conditions page.
- Enhancement - Use free text to show free courses instead of price.

### 1.0.6 - 06-10-2021

- Enhancement - Added user and course information on the order list page.
- Enhancement - Added `masteriyo_courses` shortcode.
- Enhancement - Added ability to switch to WordPress editor for courses.
- Enhancement - Decreased the size of the "Add New Category" modal.
- Fix - Remove title hover effect while hovering course list card in courses archive page.
- Fix - Pagination alignment in the courses, categories, and orders backend list page.
- Fix - Use of smaller image for course feature image.
- Fix - Overflow of course single page sidebar.
- Fix - String translation issue.
- Fix - Student count issue in the single course and course archive page.

#### 1.0.5 - 01-10-2021

- Enhancement - Show review deleted message if it still has some replies.
- Fix - The course edit button which worked only when clicked on the button's text.

#### 1.0.4 - 29-09-2021

- Enhancement - Added filter to order listing page in backend.
- Enhancement - Remove course reviews, course question answers and order notes from the WordPress comments page and activity widget.
- Enhancement - Added support for primary colour change.
- Enhancement - Added hover effecting to course review rating.
- Fix - Remove the gap between the categories list and the navigation bar.
- Fix - Filter disappearance while using the filter in courses and categories listing page.
- Fix - Logo size issue in learn page.

#### 1.0.3 - 24-09-2021

- Enhancement - Make course category slug optional while creating a category.
- Enhancement - Cache bust the default frontend and backend JS and CSS assets as well.
- Enhancement - Added enrollment limit to courses.
- Enhancement - Added course question-answer (QA) permission.
- Fix - Course progress issue.
- Fix - Course progress permission issue.
- Fix - Difficulty badge spacing and font colour.
- Fix - Account page responsive issue.
- Fix - Single course-related post width issue.

#### 1.0.2 - 21-09-2021

- Enhancement - Added search on categories page admin side.
- Enhancement - Account page Responsive.
- Fix - Course question answers for open courses.
- Fix - White space issue in learn page content.

#### 1.0.1 - 16-09-2021

- Release of version 1.0.1

#### 1.0.0 - 16-09-2021

- Initial Release
