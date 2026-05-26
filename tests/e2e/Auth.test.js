// tests/e2e/auth.test.js
// Authentication Tests

const BASE = 'http://localhost:8000';

export default {
  // ────────────────────────────────────────────────
  // 1. Login page loads
  // ────────────────────────────────────────────────
  'Login page loads correctly': (browser) => {
    browser
      .navigateTo(`${BASE}/login`)
      .waitForElementVisible('body', 3000)
      .assert.titleContains('Student')
      .assert.visible('#email')
      .assert.visible('#password')
      .assert.visible('button[type=submit]')
      .assert.containsText('h2', 'Login');
  },

  // ────────────────────────────────────────────────
  // 2. Login fails with wrong credentials
  // ────────────────────────────────────────────────
  'Login fails with wrong credentials': (browser) => {
    browser
      .navigateTo(`${BASE}/login`)
      .waitForElementVisible('#email', 3000)
      .clearValue('#email')
      .setValue('#email', 'wrong@example.com')
      .clearValue('#password')
      .setValue('#password', 'wrongpassword')
      .click('button[type=submit]')
      .waitForElementVisible('.alert-danger, .invalid-feedback, [class*=error]', 3000)
      .assert.urlContains('login');
  },

  // ────────────────────────────────────────────────
  // 3. Admin login redirects to dashboard
  // ────────────────────────────────────────────────
  'Admin can login and is redirected to dashboard': (browser) => {
    browser
      .navigateTo(`${BASE}/login`)
      .waitForElementVisible('#email', 3000)
      .clearValue('#email')
      .setValue('#email', 'admin@gmail.com')
      .clearValue('#password')
      .setValue('#password', 'admin123')
      .click('button[type=submit]')
      .waitForElementVisible('body', 5000)
      .assert.urlContains('/admin');
  },

  // ────────────────────────────────────────────────
  // 4. Teacher login redirects to attendance
  // ────────────────────────────────────────────────
  'Teacher can login and is redirected to attendance page': (browser) => {
    browser
      .navigateTo(`${BASE}/login`)
      .waitForElementVisible('#email', 3000)
      .clearValue('#email')
      .setValue('#email', 'teacher@example.com')
      .clearValue('#password')
      .setValue('#password', 'teacher123')
      .click('button[type=submit]')
      .waitForElementVisible('body', 5000)
      .assert.urlContains('/attendance');
  },

  // ────────────────────────────────────────────────
  // 5. Logout works
  // ────────────────────────────────────────────────
  'User can logout successfully': (browser) => {
    browser
      .navigateTo(`${BASE}/login`)
      .waitForElementVisible('#email', 3000)
      .setValue('#email', 'teacher@example.com')
      .setValue('#password', 'teacher123')
      .click('button[type=submit]')
      .waitForElementVisible('body', 5000)
      .assert.urlContains('/attendance')
      // click logout button/form
      .click('form[action*=logout] button, button[form*=logout], .logout-btn')
      .waitForElementVisible('body', 3000)
      .assert.urlContains('/login');
  },

  // ────────────────────────────────────────────────
  // 6. Register page loads
  // ────────────────────────────────────────────────
  'Register page loads correctly': (browser) => {
    browser
      .navigateTo(`${BASE}/register`)
      .waitForElementVisible('body', 3000)
      .assert.visible('#email')
      .assert.visible('#password')
      .assert.containsText('body', 'Register');
  },

  after: (browser) => browser.end(),
};