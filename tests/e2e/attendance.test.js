// tests/e2e/attendance.test.js
// Attendance & Reports Tests

const BASE = 'http://localhost:8000';

const loginAsTeacher = (browser) => {
  return browser
    .navigateTo(`${BASE}/login`)
    .waitForElementVisible('#email', 3000)
    .clearValue('#email')
    .setValue('#email', 'teacher@example.com')
    .clearValue('#password')
    .setValue('#password', 'teacher123')
    .click('button[type=submit]')
    .waitForElementVisible('body', 5000);
};

export default {
  // ────────────────────────────────────────────────
  // 1. Attendance page loads for teacher
  // ────────────────────────────────────────────────
  'Attendance page loads for teacher': (browser) => {
    loginAsTeacher(browser)
      .assert.urlContains('/attendance')
      .assert.containsText('body', 'Attendance Management')
      .assert.visible('input[name=date]')
      .assert.visible('button[type=submit]');
  },

  // ────────────────────────────────────────────────
  // 2. Teacher sees their classroom name
  // ────────────────────────────────────────────────
  'Teacher sees their assigned classroom': (browser) => {
    loginAsTeacher(browser)
      .navigateTo(`${BASE}/attendance`)
      .waitForElementVisible('body', 3000)
      .assert.containsText('body', 'L3SWD');
  },

  // ────────────────────────────────────────────────
  // 3. Teacher sees students listed
  // ────────────────────────────────────────────────
  'Teacher can see their students on attendance page': (browser) => {
    loginAsTeacher(browser)
      .navigateTo(`${BASE}/attendance`)
      .waitForElementVisible('body', 3000)
      .assert.containsText('body', 'Alice Uwimana');
  },

  // ────────────────────────────────────────────────
  // 4. Teacher can select a date
  // ────────────────────────────────────────────────
  'Teacher can change the attendance date': (browser) => {
    loginAsTeacher(browser)
      .navigateTo(`${BASE}/attendance`)
      .waitForElementVisible('input[name=date]', 3000)
      .clearValue('input[name=date]')
      .setValue('input[name=date]', '2026-05-10')
      .click('button[type=submit]')
      .waitForElementVisible('body', 3000)
      .assert.urlContains('date=2026-05-10');
  },

  // ────────────────────────────────────────────────
  // 5. Teacher can mark attendance as present
  // ────────────────────────────────────────────────
  'Teacher can mark a student as present': (browser) => {
    loginAsTeacher(browser)
      .navigateTo(`${BASE}/attendance`)
      .waitForElementVisible('body', 3000)
      // select "present" for first student radio/select
      .click('input[type=radio][value=present]:first-of-type, select[name*=statuses] option[value=present]')
      .click('button[type=submit]')
      .waitForElementVisible('body', 3000)
      .assert.containsText('body', 'Attendance saved');
  },

  // ────────────────────────────────────────────────
  // 6. Teacher can mark attendance as absent
  // ────────────────────────────────────────────────
  'Teacher can mark a student as absent': (browser) => {
    loginAsTeacher(browser)
      .navigateTo(`${BASE}/attendance`)
      .waitForElementVisible('body', 3000)
      .click('input[type=radio][value=absent]:first-of-type')
      .click('button[type=submit]')
      .waitForElementVisible('body', 3000)
      .assert.containsText('body', 'Attendance saved');
  },

  // ────────────────────────────────────────────────
  // 7. Teacher can mark attendance as late
  // ────────────────────────────────────────────────
  'Teacher can mark a student as late': (browser) => {
    loginAsTeacher(browser)
      .navigateTo(`${BASE}/attendance`)
      .waitForElementVisible('body', 3000)
      .click('input[type=radio][value=late]:first-of-type')
      .click('button[type=submit]')
      .waitForElementVisible('body', 3000)
      .assert.containsText('body', 'Attendance saved');
  },

  // ────────────────────────────────────────────────
  // 8. Reports page loads for teacher
  // ────────────────────────────────────────────────
  'Reports page loads for teacher': (browser) => {
    loginAsTeacher(browser)
      .navigateTo(`${BASE}/reports`)
      .waitForElementVisible('body', 3000)
      .assert.urlContains('/reports')
      .assert.containsText('body', 'Report');
  },

  // ────────────────────────────────────────────────
  // 9. Reports show attendance counts
  // ────────────────────────────────────────────────
  'Reports page shows attendance statistics': (browser) => {
    loginAsTeacher(browser)
      .navigateTo(`${BASE}/reports`)
      .waitForElementVisible('body', 3000)
      .assert.visible('body');
  },

  // ────────────────────────────────────────────────
  // 10. Unauthenticated user redirected from attendance
  // ────────────────────────────────────────────────
  'Unauthenticated user cannot access attendance page': (browser) => {
    browser
      .navigateTo(`${BASE}/attendance`)
      .waitForElementVisible('body', 3000)
      .assert.urlContains('/login');
  },

  after: (browser) => browser.end(),
};