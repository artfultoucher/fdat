#### Existing accounts on the old site
Sorry, I don't have the time to migrate existing accounts to the new site. So you have to register anew. Please notice:
- If you are a lecturer and register on the new site with the **same email address** as on the old site, then your account
will automatically be configured as a **lecturer** account. If you register with another address (or if you are a new lecturer), then either let me know before or just go ahead and create a default student account. I can subsequently sort this out without hassle.

- You can copy and paste old project content between the edit forms of the old and new site (so that special chars are not escaped). However, contrary to what I
said earlier, you **cannot keep existing HTML** markup. That's because formatting of user content is now done with [Markdown](https://www.markdownguide.org/cheat-sheet/).
This is more user friendly and definitely safer in regard to JavaScript or SQL injection attacks.


#### Some (but not all) new features

##### Matters (or categories or tags)

All users can now subscribe to several categories or tags, aka *matters*. Currently, the set of these tags is made up by the different project types, e.g. BSc, MScDA etc but more will be added later. For example, *site-news*, *lab-organisation* or similar could be added later.

You can (and should) change your selected tags any time you wish. The idea is that certain pages with large collections of data objects are filtered against your individual set of enabled tags. For example, if you only select "BSc" and a colleague only selects "MScDA" then it's more or less as if you work with two different project systems, each of which dedicated to one special degree programme.

These tags have **nothing to do with permissions**. They simply act as filter patterns.

##### Electronic Deliverables

The system now handles **submission, feedback and grading** of electronic deliverables, i.e. documents submitted by students (progress reports, final reports etc). It is already configured for the upcoming BSc FYPs but adding configurations for other project modules is fairly straightforward.

##### Privacy

- Mail addresses are not shown anywhere in the whole app except to logged in users under *My Account*.

- Student numbers are now used. They are only shown to lecturers and appear also in the footer of email which was sent via the website.

- The visibility of a project is now one of these:
  - private (owner only, formerly called 'unpublished'),
  - platform (logged in users with the permission to view projects, this was requested by some lecturers),
  - public (formerly called 'published')

##### Dashboard

The **home** page for logged-in users. Look there for everything where you are **directly involved**.

##### Accounts, roles, permissions

- Support for avatars (file upload or [gavatar](https://en.gravatar.com/)). I think this can be helpful in view of our growing staff and student numbers.
- Flexible permission system. This allows the administrator to deal with uncommon requests, e.g. approving a student to supervise a project.
- Students are now required to identify their accounts with their UCC student ID.


##### Small Projects (in planning)

As before, there is no limit to the number of students which can be assigned to a given project. However, a given student can be assigned to only one "semester project" but to multiple "small projects". Thus, the relationship between the new small projects and students is many-to-many. The old version only supported what I now call semester projects.

Currently only semester projects are supported. Once implemented, lecturers are welcome to create small projects for any teaching purpose they wish.

##### Miscellaneous

- The type of a project (its associated *matter*) can now be changed in the edit view.
- The clickable **breadcrumbs** in the left side of the menu bar might sometimes be helpful for navigation.
- Plenty of layout improvements. Improved responsive design. Most pages are now (kind of) workable on handheld devices.
