module.exports = {
  'Lifeline Login Test': function(browser) {
    browser
      .url('http://127.0.0.1:8001/login')
      .waitForElementVisible('body', 1000)
      .setValue('input[name="email"]', 'bahatiandy07@gmail.com')
      .setValue('input[name="password"]', '12345678')
      .click('button[type="submit"]')
      .pause(2000)
      .assert.not.urlContains('/login')
      .saveScreenshot('nightwatch-login-result.png')
      .end();
  },

  'Lifeline Wrong Password Test': function(browser) {
    browser
      .url('http://127.0.0.1:8001/login')
      .setValue('input[name="email"]', 'bahatiandy07@gmail.com')
      .setValue('input[name="password"]', 'wrongpass')
      .click('button[type="submit"]')
      .pause(2000)
      .assert.containsText('body', 'credentials')
      .saveScreenshot('nightwatch-login-failed.png')
      .end();
  }
};