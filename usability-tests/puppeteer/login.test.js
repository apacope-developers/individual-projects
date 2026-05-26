const puppeteer = require('puppeteer');

(async () => {
  const browser = await puppeteer.launch({ 
    headless: false,  // Set true for headless
    slowMo: 50        // Slow down to see actions
  });
  
  const page = await browser.newPage();
  await page.setViewport({ width: 1280, height: 720 });

  // Test Login
  console.log('Testing Login...');
  await page.goto('http://127.0.0.1:8001/login');
  await page.type('input[name="email"]', 'bahatiandy07@gmail.com');
  await page.type('input[name="password"]', '12345678');
  
  // Take screenshot before submit
  await page.screenshot({ path: 'before-login.png' });
  
  await page.click('button[type="submit"]');
  await page.waitForNavigation();
  
  // Take screenshot after submit
  await page.screenshot({ path: 'after-login.png' });
  
  const url = page.url();
  if (!url.includes('/login')) {
    console.log('✅ Login SUCCESS! Redirected to:', url);
  } else {
    console.log('❌ Login FAILED!');
  }

  await browser.close();
})();