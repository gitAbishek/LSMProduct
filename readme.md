![WordPress LMS](https://ps.w.org/learning-management-system/assets/banner-1544x500.png?rev=2599799)

# LMS for WordPress ![status](https://github.com/wpeverest/wordpress-lms/actions/workflows/deploy-to-staging.yml/badge.svg)

**Contributors**: Masteriyo

**Tags**: lms, learning management system, courses, elearning

**Requires at least**: 5.0

**Tested up to**: 5.8.2

**Requires PHP**: 7.0

**Stable tag**: 1.3.3

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
Want to contribute? Please have a look at [Contributer Guidelines](docs/contributer-guide.md)
# Changelog

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
