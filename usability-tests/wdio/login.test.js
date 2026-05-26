describe('Lifeline Login', () => {
  it('should login successfully', async () => {
    await browser.url('http://127.0.0.1:8001/login');
    
    const email = await $('input[name="email"]');
    const password = await $('input[name="password"]');
    const submit = await $('button[type="submit"]');
    
    await email.setValue('bahatiandy07@gmail.com');
    await password.setValue('12345678');
    await submit.click();
    
    await browser.waitUntil(async () => {
      const url = await browser.getUrl();
      return !url.includes('/login');
    });
    
    const url = await browser.getUrl();
    expect(url).not.toContain('/login');
  });
});