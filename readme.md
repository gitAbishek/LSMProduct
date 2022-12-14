![WordPress LMS](https://ps.w.org/learning-management-system/assets/banner-1544x500.png?rev=2599799)

# LMS for WordPress ![status](https://github.com/Masteriyo/learning-management-system/actions/workflows/deploy-to-staging.yml/badge.svg)

**Contributors**: Masteriyo

**Tags**: lms, learning management system, courses, elearning

**Requires at least**: 5.0

**Tested up to**: 6.1.1

**Requires PHP**: 7.0

**Stable tag**: 1.5.26

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

### 1.5.26 - 14-12-2022

- Fix - Duplicate enrolled courses.
- Fix - Plugin deletion issue due to function redeclaration.

### 1.5.25 - 06-12-2022

- Fix - Minor Fixes.

### 1.5.24 - 01-12-2022

- Fix - Sending of password reset email after updating the user.
- Fix - Typo '.masteriyo-expand-collape-all' to '.masteriyo-expand-collapse-all'.
- Fix - Featured image width issue while adding new course.

### 1.5.23 - 15-11-2022

- Enhancement - Add primary color support on account page.
- Fix - Word break in quiz.
- Fix - Course slug error.
- Fix - Random name being displayed on logout modal in account page.
- Fix - Other user scoredata being shown in new user quiz page.

### 1.5.22 - 03-11-2022

- Fix - 404 page not found issue while checking out when WooCommerce is active.

### 1.5.21 - 02-11-2022

- Fix - Fatal error due to Type Error in Masteriyo\MetaData::get_data().

### 1.5.20 - 01-11-2022

- Enhancement - Add option in global settings to delete plugin data while uninstalling.
- Fix - Payment method enums in orders controller.

### 1.5.19 - 19-10-2022

- Enhancement - Add course difficulty slug in the difficulty badge html markup.
- Enhancement - Removed account endpoints from global settings.

### 1.5.18 - 13-10-2022

- Feature - Manage course difficulties through categories page.
- Fix - Course difficulties translation issue.

### 1.5.17 - 11-10-2022

- Fix - Undefined get_id() method.
- Fix - Lessons count in courses page.
- Fix - Enrolled users count in courses page.

### 1.5.16 - 28-09-2022

- Fix - Course progress completion issue.
- Fix - Documentation URL updated in section builder.
- Fix - Bullet appearing in the account page menus in Divi theme.
- Fix - Responsive issue in the account and global settings page.

### 1.5.15 - 21-09-2022

- Feature - Added RTL support in react pages like admin, learn and account.
- Enhancement - Global settings and course settings UI.
- Enhancement - Added support to change course slug.
- Tweak - Updated skeleton loader.
- Tweak - Compatibility fixes.

### 1.5.14 - 13-09-2022

- Fix - Currency decimals not being set to zero.
- Fix - Responsive issue in the account page.

### 1.5.13 - 06-09-2022

- Fix - Paypal lives payment issue.
- Fix - Width issue in Astra Theme.
- Fix - Responsive issue on Edit Lesson.
- Fix - The instructor's course archive page throws an error when there are no author courses.
- Fix - Lesson preview issue.

### 1.5.12 - 23-08-2022

- Enhancement - Made Masteriyo backend pages responsive.
- Enhancement - Support non-english characters in single choice answers option.
- Enhancement - If the course pricing type is 'Need Registration', a logged-in user can now directly access the course.
- Enhancement - Renamed 'Buy Now' to 'Register Now' button when course pricing type is 'Need registration' and the user is not logged in.
- Fix - Initialize placeholder image in case the file is deleted from the uploads directory.
- Fix - Featured image breaking in Twenty Twenty Two theme.

### 1.5.11 - 10-08-2022

- Enhancement - Add multiple categories support to courses shortcode.
- Enhancement - Login form design updated.
- Fix - Translation issue.
- Fix - Long single word overflows in quiz question's answers.
- Fix - Sorting in backend pages.
- Fix - Order items listing permission issue causing 505 error in the account page order history.
- Fix - Logout issue due to undefined callback destroy_session.

### 1.5.10 - 03-08-2022

- Enhancement - Added skeleton loader in add new quiz page skeleton loader.
- Enhancement - Added skeleton loader in add new lesson and lesson edit page.
- Fix - Question and answer overflow in quiz attempt detail page
- Fix - Backend throwing 505 if missing learn page logo image.
- Fix - Undefined variable page_id.

### 1.5.9 - 19-07-2022

- Enhancement - Add load more button in course reviews listing on the single course page.
- Fix - Heading text colour.
- Fix - Redundant courses in the cart when the order is uncompleted.
- Fix - Categories list disappearing after opening the add new category modal.
- Fix - request_filesystem_credentials not exists.
- Fix - Syntax token error while loading global settings.

### 1.5.8 - 11-07-2022

- Enhancement - Added two-column layout on lesson page backend.
- Fix - Cannot read property of undefined (reading 24) issue on avatar URL.
- Fix - Backend page throwing 505 when deleting featured image from the site.
- Fix - Course duration not being saved on adding a new course.
- Fix - Translation not working.

### 1.5.7 - 07-07-2022

- Enhancement - Shift pages on top of general global settings.
- Enhancement - Added filters for admin menu and icon.
- Fix - Alignment issue on the addons page.
- Fix - Eliminate unnecessary loading in backend pages.

### 1.5.6 - 29-06-2022

- Enhancement - Load all the questions of the quiz in the learn page for faster pagination.
- Fix - Quiz timer expires issue.

### 1.5.5 - 27-06-2022

- Enhancement - Added class names on the account page.
- Enhancement - Show skeleton loader when changing status in courses, orders and reviews page backend.
- Fix - Edit lesson page throwing 505 error when lesson video deleted from the media library.
- Fix - Text colour in courses page being affected by theme customizer.
- Fix - Enrolled courses count and start button in the account page.

### 1.5.4 - 17-06-2022

- Enhancement - Moved pages tab from advance to general tab in global settings.
- Enhancement - Added order status tab on the orders list page.
- Enhancement - Make learn page responsive.
- Enhancement - Add formatting feature using keyboard shortcuts (CTRL+B to bold, CTRL+I to italic and CTRL+U to underline) in course highlights.
- Fix - Course highlights design issues on the single course page.
- Fix - Quiz options flickering issue on live server.
- Fix - Users admin menu not being highlighted when going to instructors tab.
- Fix - Students and Instructors lists filtering by order issue.
- Fix - Courses lists filtering by order issue.
- Fix - Approval status filter not working in instructors listing page.
- Fix - Question not being permanently deleted.
- Fix - Enrolled courses count when the order status is updated.
- Fix - Typo 'No reviewes found.' to 'No reviews found.'.

### 1.5.3 - 07-06-2022

- Feature - Implemented drag and drop feature on quiz question builder.
- Enhancement - Show delete action for a quiz in progress on quiz attempts listing page.
- Enhancement - Make addons listing page responsive.
- Enhancement - Replace fullscreen loader (Spinner) with skeleton loader in backend pages.
- Fix - Show approval notice for instructors only on the account page.
- Fix - Enrolled course count on the account page.
- Fix - Instructor unable to access add new course page when WooCommerce is enabled.

### 1.5.2 - 30-05-2022

- Enhancement - Set minimum value to 0 and maximum to 5 on the number of decimals in a global setting.
- Fix - Translation issue on course builder backend and account page.
- Fix - Renamed "Publish" to "Published" on the course listing page tab on the backend.
- Fix - Renamed "No state founds" to "No state found" on state option account page.
- Fix - Backend courses, orders and users listing order by id on initial query.

### 1.5.1 - 26-05-2022

- Enhancement - Admin can now view details of quiz attempts on the backend.
- Enhancement - Filter courses, users and orders by ascending descending order on the backend page.
- Fix - Font size of a website being overwritten by the plugin.
- Fix - Load react account page js file only on the account page.
- Fix - Addons submenu colour replicated to other submenus.
- Fix - All courses count based on the draft and published courses on the backend course listing page.

### 1.5.0 - 17-05-2022

- Feature: Add a course review management page on the backend.
- Enhancement: Made banner responsive on addons listing page.
- Enhancement: Made account page responsive.
- Fix - Instructor approval notification on the account page.
- Fix - Unable to create course review as a student.

### 1.4.12 - 04-05-2022

- Enhancement - Added tabs to differentiate status of course on backend course list page.
- Fix - Renamed "No state founds" to "No state found" on state option.
- Fix - Cancel queries being cached on error boundary which leads to 505 error on backend pages.
- Fix - Deprecated Message: Required parameter follows optional parameter in PHP8.

### 1.4.11 - 29-04-2022

- Enhancement - Added Masteriyo addons listing page.
- Enhancement - By default show 10 options on dropdown of filters.
- Fix - Question answers tab not working on learn page.
- Fix - Backend sub menu not being active when clicking on the Masteriyo logo.

### 1.4.10 - 25-04-2022

- Enhancement - Lazy load categories filter options.
- Enhancement - Show completed button instead of continue, if user has completed the course.
- Fix - Account page enrolled, progress and completed courses count issue.
- Fix - Clearing all content on editor not being updated.
- Fix - Courses, Lessons and categories featured image not being set.

### 1.4.9 - 12-04-2022

- Enhancement - Added sub-categories feature.
- Enhancement - On learn page hide the user avatar menu if the user is not logged in.
- Enhancement - Added 'Users not found' message in the filters while the user doesn't exist.
- Enhancement - Added order status colour on order listing.
- Fix - Course preview link being directed to the learning page.
- Fix - Deprecated Message: usort(): Returning bool from comparison function is deprecated in PHP8.
- Fix - Deprecated Message: Required parameter follows optional parameter in PHP8.
- Fix - Extra skeleton loading on orders listing.

### 1.4.8 - 05-04-2022

- Enhancement - Adds new style for the editor on our plugin.
- Enhancement - Disable right click on the self-hosted video player.
- Fix - Start course URL when the first item is a quiz in the course section.
- Fix - Highlight the submenu during page load and when changed.

### 1.4.7 - 29-03-2022

- Feature - User profile image uploader on the account page.
- Enhancement - Added delete button for quiz attempts.
- Enhancement - Backend listing pages minor enhancements.
- Enhancement - Display users option and user not found message in course instructor setting if the user is not found.

### 1.4.6 - 23-03-2022

- Enhancement - Getting started steps label orientation to vertical.
- Enhancement - Replace WordPress logout with custom logout process.
- Fix - Enroll button for guest users not working properly.
- Fix - Session key duplication.
- Fix - `__wakeup()` should be public warning message.
- Fix - Course completion for guest users.

### 1.4.5 - 16-03-2022

- Enhancement - Display proper error message on the backend while 505 error.
- Enhancement - Added pagination on order history and enrolled course on the account page.
- Enhancement - Support transfer content of instructors to others while deleting.
- Fix - Iframe not being displayed in lesson and quiz.

### 1.4.4 - 09-03-2022

- Enhancement - Replaced tiptap editor with WordPress TinyMCE Editor.
- Enhancement - Added Company Name and Company VAT Number on user profile billing details.
- Fix - Extra space created by review notice in the header section.
- Fix - Only 10 pages being listed in page setup of global settings.
- Tweak - Added Masteriyo Pro compatibility.

### 1.4.3 - 24-02-2022

- Fix - String translation in Account page.
- Enhancement - Added course categories archive page.
- Enhancement - Added instructor course archive page.

### 1.4.2 - 15-02-2022

- Fix - Add a link to an image in course categories shortcode or blocks.
- Fix - Blank iframe tag on learn page while removing the self-hosted video.
- Fix - Spacing on plugin review admin notice.
- Enhancement - Text strings refinement.

### 1.4.1 - 08-02-2022

- Feature - Added course trash and restore functionality.
- Feature - Added order trash and restore functionality.
- Enhancement - Added lesson and quiz preview from course builder backend.
- Enhancement - Added srcset attributes on learn page image tag to support different screen size images.
- Fix - Typo ('Please make sure your old password is corrent' to 'Please make sure your old password is correct').

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
