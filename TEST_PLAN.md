# Eco-Connect Automation Test Plan

This document outlines the test cases and edge cases for the Eco-Connect platform.

## 1. Authentication & Security
### Test Cases
- **TC_AUTH_01: User Registration (Volunteer)** - Verify that a new volunteer can register with valid details.
- **TC_AUTH_02: User Registration (NGO)** - Verify that a new NGO can register.
- **TC_AUTH_03: User Login** - Verify that a registered user can log in with correct credentials.
- **TC_AUTH_04: Invalid Login** - Verify that login fails with incorrect credentials.
- **TC_AUTH_05: Logout** - Verify that a user can securely log out.
- **TC_AUTH_06: Password Reset** - Verify the "Forgot Password" flow.

### Edge Cases
- **EC_AUTH_01: Duplicate Email** - Attempt registration with an already registered email.
- **EC_AUTH_02: Weak Password** - Test registration with a very short or simple password.
- **EC_AUTH_03: SQL Injection Attempt** - Input malicious SQL characters in login/registration fields.
- **EC_AUTH_04: Session Timeout** - Verify user is redirected to login after session expiration.

## 2. NGO & Project Management
### Test Cases
- **TC_NGO_01: Create Project** - Verify that an NGO can create a new cleanup drive or event.
- **TC_NGO_02: Edit Project** - Verify that an NGO can update project details.
- **TC_NGO_03: Delete Project** - Verify that an NGO can remove a project.
- **TC_NGO_04: View Volunteers** - Verify that an NGO can see the list of volunteers for their project.

### Edge Cases
- **EC_NGO_01: Past Date Project** - Attempt to create a project with a date in the past.
- **EC_NGO_02: Empty Project Name** - Attempt to create a project without a title.
- **EC_NGO_03: Large Image Upload** - Upload a very large file for the project banner.

## 3. Volunteer Interaction
### Test Cases
- **TC_VOL_01: Search Projects** - Verify that volunteers can search/filter projects.
- **TC_VOL_02: Join Project** - Verify that a volunteer can sign up for a drive.
- **TC_VOL_03: Leave Project** - Verify that a volunteer can withdraw from a project.
- **TC_VOL_04: Mark Attendance** - Verify that attendance can be recorded (QR code or admin side).

### Edge Cases
- **EC_VOL_01: Double Joining** - Attempt to join the same project twice.
- **EC_VOL_02: Joining Expired Project** - Attempt to join a project that has already started or ended.
- **EC_VOL_03: Incomplete Profile** - Attempt to join a project with missing profile information (if required).

## 4. Community Feed (Eco-Action Feed)
### Test Cases
- **TC_FEED_01: Create Post** - Verify that a user can share a post with an image.
- **TC_FEED_02: Admin Approval** - Verify that posts require admin approval before appearing in the feed.
- **TC_FEED_03: Like/Comment** - Verify engagement on posts.

### Edge Cases
- **EC_FEED_01: Invalid File Formats** - Uploading a .txt or .exe file instead of an image.
- **EC_FEED_02: Empty Post** - Attempting to post without content or image.

## 5. Admin Dashboard
### Test Cases
- **TC_ADM_01: User Management** - Delete a user and verify cascading data deletion.
- **TC_ADM_02: Content Moderation** - Approve/Reject community posts.
- **TC_ADM_03: Order Management** - Mark orders as completed.

### Edge Cases
- **EC_ADM_01: Unauthorized Access** - Attempting to access `/admin` as a regular volunteer.
- **EC_ADM_02: Deleting Super Admin** - Attempting to delete the main admin account.

## 6. Point System & Rewards
### Test Cases
- **TC_PTS_01: Earn Points** - Verify points are added after completing a drive.
- **TC_PTS_02: Leaderboard Update** - Verify the leaderboard reflects the new points.
- **TC_PTS_03: Redeem Rewards** - Verify points are deducted when redeeming a reward.

### Edge Cases
- **EC_PTS_01: Negative Points** - Check if the system handles any potential negative point scenarios.
- **EC_PTS_02: Insufficient Points** - Attempting to redeem a reward that costs more than available points.
