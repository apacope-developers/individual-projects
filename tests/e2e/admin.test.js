// tests/e2e/admin.test.js
// Admin Panel Tests

const BASE = 'http://localhost:8000';

// Helper to log in as admin before each test
const loginAsAdmin = (browser) => {
  return browser
    .navigateTo(`${BASE}/login`)
    .waitForElementVisible('#email', 3000)
    .clearValue('#email')
    .setValue('#email', 'admin@gmail.com')
    .clearValue('#password')
    .setValue('#password', 'admin123')
    .click('button[type=submit]')
    .waitForElementVisible('body', 5000);
};

export default {
  // ────────────────────────────────────────────────
  // 1. Admin dashboard loads
  // ────────────────────────────────────────────────
  'Admin dashboard loads and shows statistics': (browser) => {
    loginAsAdmin(browser)
      .assert.urlContains('/admin')
      .assert.visible('body')
      .assert.containsText('body', 'Dashboard');
  },

  // ────────────────────────────────────────────────
  // 2. Admin can view teachers list
  // ────────────────────────────────────────────────
  'Admin can view list of teachers': (browser) => {
    loginAsAdmin(browser)
      .navigateTo(`${BASE}/admin/teachers`)
      .waitForElementVisible('body', 3000)
      .assert.containsText('body', 'Demo Teacher')
      .assert.containsText('body', 'teacher@example.com');
  },

  // ────────────────────────────────────────────────
  // 3. Admin can view students list
  // ────────────────────────────────────────────────
  'Admin can view list of students': (browser) => {
    loginAsAdmin(browser)
      .navigateTo(`${BASE}/admin/students`)
      .waitForElementVisible('body', 3000)
      .assert.containsText('body', 'Alice Uwimana');
  },

  // ────────────────────────────────────────────────
  // 4. Admin can view classes list
  // ────────────────────────────────────────────────
  'Admin can view list of classes': (browser) => {
    loginAsAdmin(browser)
      .navigateTo(`${BASE}/admin/classes`)
      .waitForElementVisible('body', 3000)
      .assert.containsText('body', 'L3SWD')
      .assert.containsText('body', 'L4SWD')
      .assert.containsText('body', 'L5SWD')
      .assert.containsText('body', 'S5')
      .assert.containsText('body', 'S6');
  },

  // ────────────────────────────────────────────────
  // 5. Admin can view single class details
  // ────────────────────────────────────────────────
  'Admin can view class details with students': (browser) => {
    loginAsAdmin(browser)
      .navigateTo(`${BASE}/admin/classes/1`)
      .waitForElementVisible('body', 3000)
      .assert.containsText('body', 'L3SWD');
  },

  // ────────────────────────────────────────────────
  // 6. Admin can open create teacher form
  // ────────────────────────────────────────────────
  'Admin can open create teacher form': (browser) => {
    loginAsAdmin(browser)
      .navigateTo(`${BASE}/admin/teachers/create`)
      .waitForElementVisible('body', 3000)
      .assert.visible('input[name=name]')
      .assert.visible('input[name=email]')
      .assert.visible('input[name=password]');
  },

  // ────────────────────────────────────────────────
  // 7. Admin can create a new teacher
  // ────────────────────────────────────────────────
  'Admin can create a new teacher': (browser) => {
    loginAsAdmin(browser)
      .navigateTo(`${BASE}/admin/teachers/create`)
      .waitForElementVisible('input[name=name]', 3000)
      .setValue('input[name=name]', 'Test Teacher Nightwatch')
      .setValue('input[name=email]', 'nightwatch.teacher@example.com')
      .setValue('input[name=password]', 'password123')
      .setValue('input[name=password_confirmation]', 'password123')
      .click('select[name=classroom]')
      .click('select[name=classroom] option[value=L3SWD]')
      .click('button[type=submit]')
      .waitForElementVisible('body', 5000)
      .assert.urlContains('/admin/teachers')
      .assert.containsText('body', 'Test Teacher Nightwatch');
  },

  // ────────────────────────────────────────────────
  // 8. Admin can open create student form
  // ────────────────────────────────────────────────
  'Admin can open create student form': (browser) => {
    loginAsAdmin(browser)
      .navigateTo(`${BASE}/admin/students/create`)
      .waitForElementVisible('body', 3000)
      .assert.visible('input[name=name]')
      .assert.visible('input[name=email]')
      .assert.visible('input[name=password]');
  },

  // ────────────────────────────────────────────────
  // 9. Admin can view attendance records
  // ────────────────────────────────────────────────
  'Admin can view attendance records page': (browser) => {
    loginAsAdmin(browser)
      .navigateTo(`${BASE}/admin/attendance`)
      .waitForElementVisible('body', 3000)
      .assert.urlContains('/admin/attendance');
  },

  // ────────────────────────────────────────────────
  // 10. Non-admin cannot access admin panel
  // ────────────────────────────────────────────────
  'Teacher cannot access admin dashboard': (browser) => {
    browser
      .navigateTo(`${BASE}/login`)
      .waitForElementVisible('#email', 3000)
      .setValue('#email', 'teacher@example.com')
      .setValue('#password', 'teacher123')
      .click('button[type=submit]')
      .waitForElementVisible('body', 5000)
      .navigateTo(`${BASE}/admin`)
      .waitForElementVisible('body', 3000)
      .assert.not.urlContains('/admin/dashboard');
  },

  after: (browser) => browser.end(),
};