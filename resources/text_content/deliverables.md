#### Electronic Deliverables
Here you will find **technical** information about how FDAT handles document hand ups from project students.
The particular details, marking arrangements etc of such deliverables are the responsibility of the corresponding **project module
coordinators**.

#### Deliverable Requests
Deliverable requests are defined per **project type** (aka degree programme). For each deliverable there is a **hard deadline**.
It is not possible to hand up a document after this deadline has passed.

#### Uploading a Document
Only **students** who are assigned to a project can upload documents.
Logically speaking, uploaded documents are **attached to the corresponding project**. You can manage your existing deliverables from **Deliverables->My Deliverables** (a more logical choice would be the dashboard but I had my reasons..).

To upload a requested document
1. Prepare your document as single self contained file. Please avoid proprietary or platform specific file formats. **PDF** is recommended. Make sure the file name has the **correct extension**. The name itself does not matter (the system assignes its own names).
2. Log in to FDAT. Navigate to the **single view** of your project (i.e. click on the book icon).
3. You will see a menu button *Deliverables* with a single menu item *Submit a requested document*. Select this menu.
4. You will now see a **form** for file submission. Please **read everything** before clicking the upload button.

#### Administrative Details and Restrictions
- Documents can only be uploaded for the **current open request**, if any. This means if you try to upload a file **after its deadline** has passed, you will either see the **upload form for the next deliverable** (if any) or none at all. Please be aware of this!
- Students can **re-submit**. For example, after reading **comments from the supervisor** you may wish (or be asked) to re-submit.  Re-submissions will **overwrite** the previous file upload.
There is no limit to the number of re-submissions.
- You **cannot re-submit** after the deadline or after the examiner has set a **final mark** on the deliverable.
- The relevant examiners can **mark and comment** on file submissions. Marks are always numerical out of 100. It is currently **not possible** to configure a deliverable request for pass/fail marking.
Therefore, a numerical mark **must always be given**. Pass/fail marking is simulated by marking **above or below the specified pass mark**.
- Supervisors and second readers (if assigned) will receive **automatic email notifications** when their students have uploaded files.
- Students will receive **automatic email notifications** when their examiners have marked or commented on their deliverables.

#### Deletion of Uploaded Files
Examiners can delete documents which were uploaded by their students. For instance, documents attached to a project **must be deleted** in the following circumstances:
- before the project can be **deleted** from the system
- before making **changes to the students** who undertake the project
- before the visibility of the project can be set back to **private**
- if a supervisor wants to allow for **re-submission** of a document which has already received a **final mark**

There are informative error messages if you try to side step this logic. Files will never get automatically deleted (I dislike side effects in business logic).

#### Project Module Coordinators
I haven't found the time to write an admin interface for the configuration of deliverable requests. However, deliverable requests can be configured via a simple straightforward **JSON file**.
Contact me if you want to use FDAT for document submissions by your project students.

