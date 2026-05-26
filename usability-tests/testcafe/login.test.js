import { Selector } from 'testcafe';

fixture('Lifeline Login Tests')
  .page('http://127.0.0.1:8001/login');

test('Successful Login', async t => {
  await t
    .typeText('input[name="email"]', 'bahatiandy07@gmail.com')
    .typeText('input[name="password"]', '12345678')
    .click('button[type="submit"]')
    .expect(t.eval(() => window.location.href))
    .notContains('/login');
});

test('Failed Login with wrong password', async t => {
  await t
    .typeText('input[name="email"]', 'bahatiandy07@gmail.com')
    .typeText('input[name="password"]', 'wrongpass')
    .click('button[type="submit"]')
    .expect(Selector('body').innerText)
    .contains('credentials');
});